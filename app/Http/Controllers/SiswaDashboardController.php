<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penilaian;
use App\Models\SertifikatPKL;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Models\Announcement; // Model pengumuman yang benar
use App\Models\PdfDocument; // Pastikan ini diimpor
use Illuminate\Support\Facades\Storage; // Untuk mengelola penyimpanan file
use App\Models\ProfilePicture;
use Illuminate\Support\Facades\Validator; 
use App\Models\Task;
use App\Models\Attendance;
use App\Models\User;
use App\Models\PengajuanMagang;
use App\Models\Instansi;

class SiswaDashboardController extends Controller
{
    // Method untuk cek status pengajuan sebelum dashboard
    public function checkPengajuanStatus()
    {
        $user = Auth::user();
        
        // Hanya cek untuk role siswa
        if ($user->role !== 'siswa') {
            return null; // Mentor tidak perlu cek pengajuan
        }
        
        // Cek apakah sudah pernah mengajukan magang
        $pengajuan = PengajuanMagang::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->first();
        
        // Jika belum ada pengajuan, tampilkan form
        if (!$pengajuan) {
            $semuaMagang = SertifikatPKL::select('id_sertifikat', 'nama_lengkap', 'nim_nis', 'asal_sekolah', 'jurusan', 'bidang', 'tgl_mulai', 'tgl_selesai', 'sts_diambil')
                ->whereNotNull('tgl_mulai')
                ->whereNotNull('tgl_selesai')
                ->where('sts_diambil', false)
                ->orderBy('tgl_mulai', 'desc')
                ->get();
            
            $instansis = Instansi::orderBy('nama', 'asc')->get();
            
            return view('siswa.form-pengajuan', ['pengajuan' => null, 'semuaMagang' => $semuaMagang, 'instansis' => $instansis]);
        }
        
        // Jika ditolak, tampilkan form dengan info penolakan
        if ($pengajuan->status === 'rejected') {
            $semuaMagang = SertifikatPKL::select('id_sertifikat', 'nama_lengkap', 'nim_nis', 'asal_sekolah', 'jurusan', 'bidang', 'tgl_mulai', 'tgl_selesai', 'sts_diambil')
                ->whereNotNull('tgl_mulai')
                ->whereNotNull('tgl_selesai')
                ->where('sts_diambil', false)
                ->orderBy('tgl_mulai', 'desc')
                ->get();
            
            $instansis = Instansi::orderBy('nama', 'asc')->get();
            
            return view('siswa.form-pengajuan', compact('pengajuan', 'semuaMagang', 'instansis'));
        }
        
        // Jika masih pending, tampilkan status menunggu
        if ($pengajuan->status === 'pending') {
            return view('siswa.status-pending', compact('pengajuan'));
        }
        
        // Jika approved, lanjutkan ke dashboard normal (return null)
        return null;
    }

    public function profil()
    {
        try {
            $user = Auth::user();
            
            // Cari data siswa dengan helper terpusat
            $siswa = SertifikatPKL::getByUser($user);
            
            if (!$siswa) {
                Log::warning('Siswa profile not found for user_id: ' . $user->id);
                return redirect()->route('siswa.dashboard')
                    ->with('info', 'Profil Anda belum tersedia. Data profil akan muncul setelah pengajuan magang Anda disetujui dan data magang diproses oleh Sekretariat.');
            }
            
            return view('siswa.profil', compact('siswa'));
            
        } catch (\Exception $e) {
            Log::error('Error in profil method:', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return redirect()->route('siswa.dashboard')
                ->with('error', 'Terjadi kesalahan saat memuat profil: ' . $e->getMessage());
        }
    }

    public function profilById($id)
    {
        try {
            $siswa = SertifikatPKL::findOrFail($id);
            
            // Pastikan siswa hanya bisa melihat profilnya sendiri
            // Atau jika dia adalah admin/mentor, boleh melihat profil siswa lain
            $user = Auth::user();
            if ($siswa->created_id !== $user->id && $user->role !== 'admin' && $user->role !== 'mentor') {
                abort(403, 'Anda tidak memiliki akses untuk melihat profil ini.');
            }

            return view('siswa.profil', compact('siswa'));
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('siswa.dashboard')
                           ->with('error', 'Profil tidak ditemukan.');
        } catch (\Exception $e) {
            Log::error('Error in profilById method:', ['error' => $e->getMessage(), 'id' => $id]);
            return redirect()->route('siswa.dashboard')
                           ->with('error', 'Terjadi kesalahan saat memuat profil.');
        }
    }

    public function nilai()
    {
        try {
            $user = Auth::user();
            
            Log::info('=== NILAI SERTIFIKAT DEBUG START ===');
            Log::info('User accessing nilai:', [
                'user_id' => $user->id,
                'username' => $user->username,
                'name' => $user->name,
                'role' => $user->role
            ]);
            
            // Cari data sertifikat siswa dengan helper terpusat
            $sertifikat = SertifikatPKL::getByUser($user);

            if (!$sertifikat) {
                Log::warning('Sertifikat not found for user_id: ' . $user->id);
                return view('siswa.nilai', [
                    'sertifikat' => null,
                    'penilaians' => collect([])
                ])->with('info', 'Data magang Anda belum tersedia. Tunggu persetujuan dari mentor.');
            }

            Log::info('Sertifikat found:', [
                'id_sertifikat' => $sertifikat->id_sertifikat,
                'nama_lengkap' => $sertifikat->nama_lengkap,
                'nim_nis' => $sertifikat->nim_nis,
            ]);

            // Ambil data penilaian berdasarkan id_sertifikat
            $penilaians = Penilaian::where('id_sertifikat', $sertifikat->id_sertifikat)
                ->orderBy('aspek')
                ->get();
            
            Log::info('Penilaian query result:', [
                'sertifikat_id' => $sertifikat->id_sertifikat,
                'penilaian_count' => $penilaians->count(),
                'first_penilaian' => $penilaians->first() ? [
                    'id' => $penilaians->first()->id,
                    'aspek' => $penilaians->first()->aspek,
                    'nilai' => $penilaians->first()->nilai,
                ] : null
            ]);
            
            Log::info('=== NILAI SERTIFIKAT DEBUG END ===');

            return view('siswa.nilai', compact('sertifikat', 'penilaians'));
            
        } catch (\Exception $e) {
            Log::error('Error in nilai method:', [
                'error' => $e->getMessage(), 
                'trace' => $e->getTraceAsString(),
                'user_id' => Auth::id()
            ]);
            return redirect()->route('siswa.dashboard')
                ->with('error', 'Terjadi kesalahan saat memuat nilai: ' . $e->getMessage());
        }
    }

    public function nilaiById($id)
    {
        try {
            $sertifikat = SertifikatPKL::findOrFail($id);
            // Pastikan siswa hanya bisa melihat nilainya sendiri
            $user = Auth::user();
            if ($sertifikat->created_id !== $user->id && $user->role !== 'admin' && $user->role !== 'mentor') {
                abort(403, 'Anda tidak memiliki akses untuk melihat nilai ini.');
            }

            // Ambil data penilaian
            $penilaians = Penilaian::where('id_sertifikat', $sertifikat->id_sertifikat)
                ->orderBy('aspek')
                ->get();

            return view('siswa.nilai', compact('sertifikat', 'penilaians'));
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('siswa.dashboard')
                           ->with('error', 'Data sertifikat tidak ditemukan.');
        } catch (\Exception $e) {
            Log::error('Error in nilaiById method:', [
                'error' => $e->getMessage(), 
                'id' => $id,
                'user_id' => Auth::id()
            ]);
            return redirect()->route('siswa.dashboard')
                           ->with('error', 'Terjadi kesalahan saat memuat nilai.');
        }
    }

    // Dashboard method yang sudah diperbaiki untuk siswa
    public function dashboard()
    {
        // Cek status pengajuan terlebih dahulu untuk role siswa
        $pengajuanCheck = $this->checkPengajuanStatus();
        if ($pengajuanCheck !== null) {
            return $pengajuanCheck; // Return view form/status jika belum approved
        }
        
        try {
            $user = Auth::user();
            $sertifikatUtama = SertifikatPKL::getByUser($user);

            // Jika masih tidak ditemukan, return view dengan data kosong
            if (!$sertifikatUtama) {
                Log::warning('Sertifikat not found for user_id: ' . $user->id);
                // Ambil pengumuman meskipun sertifikat siswa tidak ditemukan - SEMUA pengumuman dari mentor dan sekretariat
                $pengumumans = Announcement::where('published_at', '<=', Carbon::now())
                                         ->orderByDesc('published_at')
                                         ->limit(5) // Batasi 5 pengumuman terbaru
                                         ->get();
                
                $taskStats = [
                    'total' => 0,
                    'pending' => 0,
                    'submitted' => 0,
                    'graded' => 0,
                    'new' => 0
                ];
                
                $semuaMagang = SertifikatPKL::select('id_sertifikat', 'nama_lengkap', 'nim_nis', 'asal_sekolah', 'jurusan', 'bidang', 'tgl_mulai', 'tgl_selesai', 'sts_diambil')
                    ->whereNotNull('tgl_mulai')
                    ->whereNotNull('tgl_selesai')
                    ->where('sts_diambil', false)
                    ->orderBy('tgl_mulai', 'desc')
                    ->get();
                
                return view('siswa.dashboard', [
                    'sertifikat' => null,
                    'idSertifikat' => Auth::user()->id_sertifikat ?? Auth::id(),
                    'siswaAktif' => 0,
                    'menungguMagang' => 0,
                    'sekolahCount' => 0,
                    'siswaLulus' => 0,
                    'data' => collect(),
                    'pengumumans' => $pengumumans,
                    'taskStats' => $taskStats,
                    'semuaMagang' => $semuaMagang
                ]);
            }

            $idSertifikat = $sertifikatUtama->id_sertifikat;
            $commonCreatedId = $sertifikatUtama->created_id;

            // Gunakan sertifikat utama yang ditemukan
            $sertifikat = $sertifikatUtama;

            $today = Carbon::now()->format('Y-m-d');

            // Hitung statistik
            $filterQuery = function($query) use ($commonCreatedId, $idSertifikat) {
                if (!empty($commonCreatedId)) {
                    $query->where('created_id', $commonCreatedId);
                } else {
                    $query->where('id_sertifikat', $idSertifikat);
                }
            };

            $siswaAktif = SertifikatPKL::where('sts_diambil', false)
                ->whereDate('tgl_mulai', '<=', $today)
                ->whereDate('tgl_selesai', '>=', $today)
                ->where($filterQuery)
                ->count();

            $menungguMagang = SertifikatPKL::whereDate('tgl_mulai', '>', $today)
                ->where($filterQuery)
                ->count();

            $sekolahCount = SertifikatPKL::where($filterQuery)
                ->distinct()
                ->count('asal_sekolah');

            $siswaLulus = SertifikatPKL::where($filterQuery)
                ->where('sts_diambil', true)
                ->whereDate('tgl_selesai', '<', $today)
                ->count();

            // Data untuk chart
            $data = SertifikatPKL::where($filterQuery)
                ->select('bidang', DB::raw('count(*) as total'))
                ->where('sts_diambil', false)
                ->whereDate('tgl_mulai', '<=', $today)
                ->whereDate('tgl_selesai', '>=', $today)
                ->groupBy('bidang')
                ->get();

            // Hitung progress
            if ($sertifikat) {
                $mulai = Carbon::parse($sertifikat->tgl_mulai);
                $selesai = Carbon::parse($sertifikat->tgl_selesai);
                $hariIni = Carbon::now();

                $progress = 0;
                $status = '';

                if ($hariIni->lt($mulai)) {
                    $progress = 0;
                    $status = 'Belum dimulai';
                } elseif ($hariIni->gt($selesai)) {
                    $progress = 100;
                    $status = 'Selesai';
                } else {
                    $totalDurasi = $mulai->diffInDays($selesai);
                    $totalDurasi = $totalDurasi == 0 ? 1 : $totalDurasi;
                    $hariBerjalan = $mulai->diffInDays($hariIni);
                    $progress = ($hariBerjalan / $totalDurasi) * 100;
                    $status = 'Sedang berlangsung';
                }

                $sertifikat->progress = round($progress, 1);
                $sertifikat->status = $status;
                $sertifikat->sisa_hari = $hariIni->lt($selesai) ? $hariIni->diffInDays($selesai) : 0;
            }

            // Ambil pengumuman terbaru - SEMUA pengumuman dari mentor dan sekretariat yang sudah dipublikasikan
            $pengumumans = Announcement::where('published_at', '<=', Carbon::now())
                                     ->orderByDesc('published_at')
                                     ->limit(5) // Batasi 5 pengumuman terbaru
                                     ->get();

            // Ambil data magang untuk kalender
            $semuaMagang = SertifikatPKL::select('id_sertifikat', 'nama_lengkap', 'nim_nis', 'asal_sekolah', 'jurusan', 'bidang', 'tgl_mulai', 'tgl_selesai', 'sts_diambil')
                ->whereNotNull('tgl_mulai')
                ->whereNotNull('tgl_selesai')
                ->where('sts_diambil', false)
                ->orderBy('tgl_mulai', 'desc')
                ->get();

            $taskStats = [];
            $recentTasks = collect();
            $temanMagang = collect();

            if ($sertifikat) {
                $taskStats = [
                    'total' => Task::where('sertifikat_pkl_id', $sertifikat->id_sertifikat)->count(),
                    'pending' => Task::where('sertifikat_pkl_id', $sertifikat->id_sertifikat)
                        ->whereDoesntHave('submissions')
                        ->count(),
                    'submitted' => Task::where('sertifikat_pkl_id', $sertifikat->id_sertifikat)
                        ->whereHas('submissions', function($q) {
                            $q->where('status', 'submitted');
                        })
                        ->count(),
                    'graded' => Task::where('sertifikat_pkl_id', $sertifikat->id_sertifikat)
                        ->whereHas('submissions', function($q) {
                            $q->where('status', 'graded');
                        })
                        ->count(),
                    'new' => Task::where('sertifikat_pkl_id', $sertifikat->id_sertifikat)
                        ->where('created_at', '>=', Carbon::now()->subDays(3))
                        ->whereDoesntHave('submissions')
                        ->count(),
                ];

                // Ambil 5 tugas terbaru (dengan eager load submissions)
                $recentTasks = Task::where('sertifikat_pkl_id', $sertifikat->id_sertifikat)
                    ->with('submissions')
                    ->orderBy('created_at', 'desc')
                    ->limit(5)
                    ->get();

                // Ambil daftar teman magang di bidang yang sama dan waktu yang bersinggungan
                if (!empty($sertifikat->bidang)) {
                    $temanMagang = SertifikatPKL::where('bidang', $sertifikat->bidang)
                        ->where('id_sertifikat', '!=', $sertifikat->id_sertifikat)
                        ->where('sts_diambil', false)
                        ->where(function($query) use ($sertifikat) {
                            $query->whereBetween('tgl_mulai', [$sertifikat->tgl_mulai, $sertifikat->tgl_selesai])
                                  ->orWhereBetween('tgl_selesai', [$sertifikat->tgl_mulai, $sertifikat->tgl_selesai])
                                  ->orWhere(function($q) use ($sertifikat) {
                                      $q->where('tgl_mulai', '<=', $sertifikat->tgl_mulai)
                                        ->where('tgl_selesai', '>=', $sertifikat->tgl_selesai);
                                  });
                        })
                        ->orderBy('tgl_mulai', 'asc')
                        ->get();
                }
            }

            return view('siswa.dashboard', compact(
                'sertifikat', 'idSertifikat', 'siswaAktif', 'menungguMagang',
                'sekolahCount', 'siswaLulus', 'data', 'pengumumans', 'taskStats', 'semuaMagang', 'recentTasks', 'temanMagang'
            ));
            
        } catch (\Exception $e) {
            Log::error('Error in dashboard method:', [
                'error' => $e->getMessage(), 
                'trace' => $e->getTraceAsString(),
                'user_id' => Auth::id()
            ]);
            
            // Ambil pengumuman meskipun terjadi error pada data sertifikat
            $pengumumans = Announcement::where('published_at', '<=', Carbon::now())
                                     ->orderByDesc('published_at')
                                     ->limit(5) // Batasi 5 pengumuman terbaru
                                     ->get();
            
            $taskStats = [
                'total' => 0,
                'pending' => 0,
                'submitted' => 0,
                'graded' => 0,
                'new' => 0
            ];
            
            $semuaMagang = SertifikatPKL::select('id_sertifikat', 'nama_lengkap', 'nim_nis', 'asal_sekolah', 'jurusan', 'bidang', 'tgl_mulai', 'tgl_selesai', 'sts_diambil')
                ->whereNotNull('tgl_mulai')
                ->whereNotNull('tgl_selesai')
                ->where('sts_diambil', false)
                ->orderBy('tgl_mulai', 'desc')
                ->get();

            // Return view dengan data kosong jika terjadi error
            return view('siswa.dashboard', [
                'sertifikat' => null,
                'siswaAktif' => 0,
                'menungguMagang' => 0,
                'sekolahCount' => 0,
                'siswaLulus' => 0,
                'data' => collect(),
                'pengumumans' => $pengumumans,
                'taskStats' => $taskStats,
                'semuaMagang' => $semuaMagang,
                'recentTasks' => collect(),
                'temanMagang' => collect()
            ]);
        }
    }
    public function uploadPhoto(Request $request)
{
    try {
        $request->validate([
            'profile_picture' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = Auth::user();
        
        // Cari data siswa berdasarkan user yang login
        $siswa = SertifikatPKL::where('created_id', $user->id)->first();
        
        if (!$siswa) {
            $siswa = SertifikatPKL::where('nim_nis', $user->username)->first();
        }
        
        if (!$siswa) {
            $siswa = SertifikatPKL::where(function($query) use ($user) {
                $query->where('created_id', $user->id)
                           ->orWhere('nim_nis', $user->username)
                           ->orWhere('nama_lengkap', 'like', '%' . $user->name . '%');
            })->first();
        }

        if (!$siswa) {
            return redirect()->back()->with('error', 'Data siswa tidak ditemukan.');
        }

        if ($request->hasFile('profile_picture')) {
            $file = $request->file('profile_picture');
            $fileName = 'profile_' . $siswa->id_sertifikat . '_' . time() . '.' . $file->getClientOriginalExtension();

            // Simpan gambar ke folder 'public/profile_pictures'
            $path = $file->storeAs('profile_pictures', $fileName, 'public');

            // Hapus foto profil lama jika ada
            if ($siswa->foto_profil_url && Storage::disk('public')->exists($siswa->foto_profil_url)) {
                Storage::disk('public')->delete($siswa->foto_profil_url);
            }

            // Update path foto profil di database tabel sertifikat_pkl
            $siswa->foto_profil_url = $path;
            $siswa->save();

            Log::info('Profile picture updated successfully for siswa: ' . $siswa->id_sertifikat);

            return redirect()->back()->with('success', 'Foto profil berhasil diubah!');
        }

        return redirect()->back()->with('error', 'Gagal mengunggah foto profil.');
        
    } catch (\Illuminate\Validation\ValidationException $e) {
        return redirect()->back()
            ->withErrors($e->validator)
            ->withInput()
            ->with('error', 'File tidak valid. Pastikan file adalah gambar dengan format JPEG, PNG, JPG, atau GIF dan ukuran maksimal 2MB.');
    } catch (\Exception $e) {
        Log::error('Error uploading profile picture: ' . $e->getMessage(), [
            'user_id' => Auth::id(),
            'trace' => $e->getTraceAsString()
        ]);
        
        return redirect()->back()->with('error', 'Terjadi kesalahan saat mengunggah foto profil: ' . $e->getMessage());
    }
}
public function getIndividualPdfs(Request $request)
{
    // 1. Mengaktifkan kembali dan menyesuaikan validasi data yang masuk.
    // Parameter yang diharapkan sekarang adalah 'id_sertifikat'
    $validator = Validator::make($request->all(), [
        'id_sertifikat' => 'required|exists:tbl_sertifikat_pkl,id_sertifikat', // Validasi id_sertifikat
    ]);

    // 2. Mengembalikan respons JSON dengan error jika validasi gagal.
    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'message' => 'Validasi gagal. Parameter ID Sertifikat diperlukan dan harus valid.',
            'errors' => $validator->errors()
        ], 422); // 422 Unprocessable Entity
    }

    try {
        $idSertifikat = $request->id_sertifikat;

        // Mencari semua dokumen PDF yang terkait dengan id_sertifikat ini.
        // Ini akan mengambil PDF dengan type 'individual' (yang diunggah siswa)
        // dan 'mentor' (yang diunggah oleh mentor).
        $pdfDocuments = PdfDocument::where('id_sertifikat', $idSertifikat)
                                   ->where(function($query) {
                                       $query->where('type', 'individual')
                                             ->orWhere('type', 'mentor');
                                   })
                                   ->orderByDesc('uploaded_at') // Urutkan berdasarkan waktu upload terbaru
                                   ->get();

        // Jika tidak ada dokumen ditemukan
        if ($pdfDocuments->isEmpty()) {
            return response()->json([
                'success' => true,
                'message' => 'Tidak ada file PDF ditemukan untuk ID Sertifikat: ' . $idSertifikat,
                'data' => []
            ], 200);
        }

        // Memformat data untuk respons, termasuk URL publik
        $formattedPdfs = $pdfDocuments->map(function ($doc) {
            return [
                'id' => $doc->id,
                'id_sertifikat' => $doc->id_sertifikat,
                'nim_nis' => $doc->nim_nis, // Tetap sertakan nim_nis jika diperlukan di frontend
                'title' => $doc->title,
                'description' => $doc->description,
                'filename' => $doc->filename,
                'file_size' => $doc->file_size,
                'uploaded_at' => $doc->uploaded_at ? Carbon::parse($doc->uploaded_at)->format('d F Y H:i') : '-',
                'type' => $doc->type,
                'pdf_url' => Storage::url($doc->file_path), // URL publik untuk mengakses PDF
            ];
        });

        // Mengembalikan respons JSON dengan daftar PDF yang ditemukan
        return response()->json([
            'success' => true,
            'message' => 'Daftar file PDF untuk ID Sertifikat ' . $idSertifikat . ' berhasil diambil.',
            'data' => $formattedPdfs
        ], 200); // 200 OK

    } catch (\Exception $e) {
        \Log::error('Gagal mengambil daftar PDF untuk ID Sertifikat: ' . $e->getMessage(), ['exception' => $e, 'id_sertifikat' => $request->id_sertifikat]);
        return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan saat mengambil file PDF: ' . $e->getMessage()
        ], 500); // 500 Internal Server Error
    }
}
public function destroy($id)
{
    try {
        $pdf = PdfDocument::find($id); // Ganti PdfDocument dengan nama model Anda

        if (!$pdf) {
            return response()->json([
                'success' => false,
                'message' => 'Dokumen PDF tidak ditemukan.'
            ], 404); // 404 Not Found
        }

        // --- Logika Otorisasi (Penting!) ---
        // Anda harus mengimplementasikan logika ini untuk keamanan.
        // Contoh: Hanya siswa dengan id_sertifikat yang cocok dengan dokumen yang bisa menghapus.
        // Misal jika Anda memiliki autentikasi dan model Siswa:
        // if (auth()->check() && $pdf->id_sertifikat !== auth()->user()->id_sertifikat_siswa_terkait) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Anda tidak memiliki izin untuk menghapus dokumen ini.'
        //     ], 403); // 403 Forbidden
        // }

        // Hapus file fisik PDF dari storage
        if (Storage::exists($pdf->file_path)) { // Asumsikan ada kolom 'file_path' di DB yang menyimpan path storage
            Storage::delete($pdf->file_path);
        }

        // Hapus entri dari database
        $pdf->delete();

        return response()->json([
            'success' => true,
            'message' => 'Dokumen PDF berhasil dihapus.'
        ]);

    } catch (\Exception $e) {
        Log::error('Gagal menghapus dokumen PDF: ' . $e->getMessage(), ['exception' => $e, 'pdf_id' => $id]);
        return response()->json([
            'success' => false,
            'message' => 'Gagal menghapus dokumen: ' . $e->getMessage()
        ], 500); // 500 Internal Server Error
    }
}
public function getMessagesForStudent(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_sertifikat' => 'required|exists:tbl_sertifikat_pkl,id_sertifikat',
        ]);

        if ($validator->fails()) {
            Log::error('Validation failed for getMessagesForStudent: ' . json_encode($validator->errors()));
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal. ID Sertifikat diperlukan dan harus valid.',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $idSertifikat = $request->id_sertifikat;

            // Ambil "pesan" dari tabel 'tasks' yang dialamatkan ke siswa ini
            // Anda mungkin perlu menambahkan kolom 'type' di tabel tasks untuk membedakan antara 'tugas' dan 'pesan'
            // Atau Anda bisa menggunakan kriteria lain (misal: task tanpa due_date spesifik, atau status tertentu)
            $messagesAsTasks = Task::where('sertifikat_pkl_id', $idSertifikat)
                                ->orderByDesc('created_at')
                                ->with(['assignedByUser']) // Mengambil relasi assignedByUser (yang merupakan sender)
                                ->get();

            $formattedMessages = $messagesAsTasks->map(function ($task) {
                return [
                    'id' => $task->id,
                    'subject' => $task->title, // Subject pesan adalah title tugas
                    'content' => $task->description, // Isi pesan adalah description tugas
                    'sender_id' => $task->assigned_by,
                    'sender_name' => $task->assignedByUser->username ?? 'Sistem', // Ambil username dari assignedByUser
                    'created_at' => $task->created_at,
                    'read_at' => $task->is_read ? ($task->updated_at ?? true) : null,
                    'is_read' => (bool)$task->is_read,
                    'type' => $task->status,
                ];
            });

            if ($formattedMessages->isEmpty()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Tidak ada pesan ditemukan untuk siswa ini.',
                    'data' => []
                ], 200);
            }

            return response()->json([
                'success' => true,
                'message' => 'Pesan berhasil diambil.',
                'data' => $formattedMessages
            ], 200);

        } catch (\Exception $e) {
            Log::error('Error fetching messages for student: ' . $e->getMessage(), ['exception' => $e, 'id_sertifikat' => $request->id_sertifikat]);
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil pesan: ' . $e->getMessage()
            ], 500);
        }
    }
    public function markTaskAsRead(Request $request, $task_id)
    {
        try {
            $task = Task::findOrFail($task_id);

            if (!$task->is_read) {
                $task->is_read = true;
                $task->save();
            }

            return response()->json([
                'success' => true,
                'message' => 'Pesan berhasil ditandai sebagai dibaca.'
            ]);

    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        \Log::error('Task not found for mark as read: ' . $task_id);
        return response()->json([
            'success' => false,
            'message' => 'Pesan tidak ditemukan.'
        ], 404);
    } catch (\Exception $e) {
        \Log::error('Error marking task as read: ' . $e->getMessage(), ['exception' => $e, 'task_id' => $task_id]);
        return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan saat menandai pesan sebagai dibaca: ' . $e->getMessage()
        ], 500);
    }
}
public function index()
{
    $user = Auth::user();

    if (!$user) {
        return redirect('/login');
    }

    // ✅ Gunakan id_sertifikat dari user yang login
    $idSertifikat = $user->id_sertifikat;

    $today = Carbon::now()->format('Y-m-d');

    // --- Statistik Sertifikat ---
    $sertifikat = SertifikatPKL::where('created_id', $idSertifikat)
        ->orderByDesc('tgl_sertifikat')
        ->first();

    $siswaAktif = SertifikatPKL::where('created_id', $idSertifikat)
        ->where('sts_diambil', false)
        ->whereDate('tgl_mulai', '<=', $today)
        ->whereDate('tgl_selesai', '>=', $today)
        ->count();

    $menungguMagang = SertifikatPKL::where('created_id', $idSertifikat)
        ->whereDate('tgl_mulai', '>', $today)->count();

    $sekolahCount = SertifikatPKL::where('created_id', $idSertifikat)
        ->distinct()->count('asal_sekolah');

    $siswaLulus = SertifikatPKL::where('created_id', $idSertifikat)
        ->where('sts_diambil', true)
        ->whereDate('tgl_selesai', '<', $today)
        ->count();

    $data = SertifikatPKL::select('bidang', DB::raw('count(*) as total'))
        ->where('created_id', $idSertifikat)
        ->where('sts_diambil', false)
        ->whereDate('tgl_mulai', '<=', $today)
        ->whereDate('tgl_selesai', '>=', $today)
        ->groupBy('bidang')
        ->get();

    if ($sertifikat) {
        $mulai = Carbon::parse($sertifikat->tgl_mulai);
        $selesai = Carbon::parse($sertifikat->tgl_selesai);
        $hariIni = Carbon::now();

        if ($hariIni->lt($mulai)) {
            $progress = 0;
            $status = 'Belum dimulai';
        } elseif ($hariIni->gt($selesai)) {
            $progress = 100;
            $status = 'Selesai';
        } else {
            $total = $mulai->diffInDays($selesai) ?: 1;
            $berjalan = $mulai->diffInDays($hariIni);
            $progress = ($berjalan / $total) * 100;
            $status = 'Sedang berlangsung';
        }

        $sertifikat->progress = round($progress, 1);
        $sertifikat->status = $status;
        $sertifikat->sisa_hari = $hariIni->lt($selesai) ? $hariIni->diffInDays($selesai) : 0;
    }

    // --- Presensi ---
    $currentMonth = Carbon::now()->month;
    $currentYear = Carbon::now()->year;

    $monthlySummary = $this->getMonthlySummaryData($idSertifikat, $currentMonth, $currentYear);
    $todayAttendance = $this->getTodayStatusData($idSertifikat);
    $recentHistory = $this->getRecentHistoryData($idSertifikat);

    return view('dashboard.index', compact(
        'sertifikat', 'siswaAktif', 'menungguMagang',
        'sekolahCount', 'siswaLulus', 'data',
        'monthlySummary', 'todayAttendance', 'recentHistory',
        'currentMonth', 'currentYear', 'idSertifikat',
    ));
}

    /**
     * Menyimpan data presensi siswa via AJAX.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
{
    $user = Auth::user();

    if (!$user) {
        return response()->json(['success' => false, 'message' => 'User not authenticated'], 401);
    }

    $today = Carbon::today()->toDateString();

    // ✅ Pakai id_sertifikat untuk pengecekan
    $existingAttendance = Attendance::where('id_sertifikat', $user->id_sertifikat)
                                    ->where('date', $today)
                                    ->first();

    if ($existingAttendance) {
        return response()->json([
            'success' => false,
            'message' => 'Anda sudah melakukan presensi hari ini.'
        ], 409);
    }

    try {
        Attendance::create([
            'id_sertifikat' => $user->id_sertifikat, // ✅ Simpan id_sertifikat
            'date'          => $today,
            'status'        => $request->status,
            'location'      => $request->location ?: 'Lokasi Online (Presensi Web)',
            'notes'         => $request->notes ?? '',  // ✅ default '' bukan null (kolom NOT NULL)
            'time'          => Carbon::now()->format('H:i:s'),
        ]);

        $todayStatus = $this->getTodayStatusData($user->id_sertifikat);
        $recentHistory = $this->getRecentHistoryData($user->id_sertifikat);

        return response()->json([
            'success' => true,
            'message' => 'Presensi berhasil dicatat!',
            'todayStatus' => $todayStatus,
            'recentHistory' => $recentHistory
        ]);
    } catch (\Exception $e) {
        Log::error('Gagal mencatat presensi: ' . $e->getMessage(), ['exception' => $e]);
        return response()->json([
            'success' => false,
            'message' => 'Gagal mencatat presensi: ' . $e->getMessage()
        ], 500);
    }
}


    /**
     * Mendapatkan ringkasan presensi bulanan untuk Chart.js via AJAX.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getMonthlySummary(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json([], 401);
        }

        $month = $request->input('month', Carbon::now()->month);
        $year = $request->input('year', Carbon::now()->year);

        $data = $this->getMonthlySummaryData($user->id_sertifikat, (int)$month, (int)$year);

        return response()->json($data);
    }

    /**
     * Mendapatkan status presensi hari ini via AJAX.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTodayStatus(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['status' => 'Unknown', 'message' => 'User not authenticated'], 401);
        }

        $statusData = $this->getTodayStatusData($user->id_sertifikat);

        return response()->json($statusData);
    }

    /**
     * Mendapatkan riwayat presensi 5 hari terakhir via AJAX.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRecentHistory(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json([], 401);
        }

        $history = $this->getRecentHistoryData($user->id_sertifikat);

        return response()->json($history);
    }

    /**
     * Helper: Mendapatkan data ringkasan bulanan.
     *
     * @param int $userId
     * @param int $month
     * @param int $year
     * @return array
     */
    /**
     * Helper: Mendapatkan semua ID pengguna/sertifikat yang mungkin terkait.
     */
    private function getPossibleUserIds($idSertifikat): array
    {
        $ids = [(string)$idSertifikat];
        if (Auth::check()) {
            $user = Auth::user();
            $ids[] = (string)$user->id;
            if (!empty($user->id_sertifikat)) {
                $ids[] = (string)$user->id_sertifikat;
            }
            try {
                $sertifikat = SertifikatPKL::where('created_id', $user->id)
                    ->orWhere('nim_nis', $user->username)
                    ->first();
                if ($sertifikat) {
                    $ids[] = (string)$sertifikat->id_sertifikat;
                }
            } catch (\Exception $e) {
                // Ignore error if query fails
            }
        }
        return array_values(array_unique(array_filter($ids)));
    }

    /**
     * Helper: Mengambil ringkasan bulanan untuk presensi siswa.
     */
    private function getMonthlySummaryData($idSertifikat, int $month, int $year): array
    {
        $possibleIds = $this->getPossibleUserIds($idSertifikat);
        
        // Ambil data sertifikat untuk mendapatkan tgl_mulai dan tgl_selesai
        $sertifikat = SertifikatPKL::whereIn('id_sertifikat', $possibleIds)->first();
        
        // Tanggal awal dan akhir bulan
        $monthStart = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $monthEnd = Carbon::createFromDate($year, $month, 1)->endOfMonth();
        
        // Tanggal mulai dan selesai magang
        $internshipStart = $sertifikat ? Carbon::parse($sertifikat->tgl_mulai) : $monthStart;
        $internshipEnd = $sertifikat ? Carbon::parse($sertifikat->tgl_selesai) : $monthEnd;
        
        // Tentukan periode perhitungan yang sebenarnya:
        // - Mulai dari yang lebih akhir antara awal bulan atau tgl_mulai magang
        // - Berakhir di yang lebih awal antara akhir bulan, tgl_selesai magang, atau hari ini
        $startDate = $monthStart->copy()->max($internshipStart);
        $endDate = $monthEnd->copy()->min($internshipEnd)->min(Carbon::today());
        
        // Jika periode tidak valid (belum mulai magang di bulan ini), return data kosong
        if ($startDate->greaterThan($endDate)) {
            return [
                'totalDays' => 0,
                'presentDays' => 0,
                'absentDays' => 0,
                'permissionDays' => 0,
                'presentPercentage' => 0,
                'absentPercentage' => 0,
                'permissionPercentage' => 0,
                'chartLabels' => [],
                'chartPresentData' => [],
                'chartAbsentData' => [],
                'chartPermissionData' => [],
            ];
        }

        $attendances = Attendance::whereIn('id_sertifikat', $possibleIds)
            ->whereBetween('date', [$startDate, $endDate])
            ->get();

        // Hitung total hari kerja (Senin-Jumat) dalam periode magang yang valid
        $totalDaysInMonth = 0;
        $tempDate = $startDate->copy();
        while ($tempDate->lte($endDate)) {
            if (!$tempDate->isWeekend()) {
                $totalDaysInMonth++;
            }
            $tempDate->addDay();
        }

        // Abaikan presensi di hari Sabtu & Minggu (jika ada record di database)
        $presentCount = $attendances->reject(fn($att) => Carbon::parse($att->date)->isWeekend())->where('status', 'Hadir')->count();
        $permissionCount = $attendances->reject(fn($att) => Carbon::parse($att->date)->isWeekend())->where('status', 'Izin')->count();
        
        // Alpha = Total Hari Kerja - (Hadir + Izin)
        // Ini menghitung hari kerja yang tidak ada presensi Hadir atau Izin
        $absentCount = $totalDaysInMonth - ($presentCount + $permissionCount);

        $presentPercentage = $totalDaysInMonth > 0 ? round(($presentCount / $totalDaysInMonth) * 100) : 0;
        $absentPercentage = $totalDaysInMonth > 0 ? round(($absentCount / $totalDaysInMonth) * 100) : 0;
        $permissionPercentage = $totalDaysInMonth > 0 ? round(($permissionCount / $totalDaysInMonth) * 100) : 0;

        $labels = [];
        $presentData = [];
        $absentData = [];
        $permissionData = [];

        // Generate data untuk grafik (hanya hari kerja dalam periode magang yang valid)
        $tempDate = $startDate->copy();
        while ($tempDate->lte($endDate)) {
            if ($tempDate->isWeekend()) {
                $tempDate->addDay();
                continue; // Skip Sabtu & Minggu dari data grafik
            }
            
            $date = $tempDate->toDateString();
            $labels[] = $tempDate->day;

            // Cek apakah ada record presensi untuk tanggal ini
            $dayAttendance = $attendances->where('date', $date)->first();
            
            if ($dayAttendance) {
                // Ada record presensi
                $presentData[] = $dayAttendance->status === 'Hadir' ? 1 : 0;
                $permissionData[] = $dayAttendance->status === 'Izin' ? 1 : 0;
                $absentData[] = ($dayAttendance->status !== 'Hadir' && $dayAttendance->status !== 'Izin') ? 1 : 0;
            } else {
                // Tidak ada record = Alpha
                $presentData[] = 0;
                $permissionData[] = 0;
                $absentData[] = 1;
            }
            
            $tempDate->addDay();
        }

        return [
            'totalDays' => $totalDaysInMonth,
            'presentDays' => $presentCount,
            'absentDays' => $absentCount,
            'permissionDays' => $permissionCount,
            'presentPercentage' => $presentPercentage,
            'absentPercentage' => $absentPercentage,
            'permissionPercentage' => $permissionPercentage,
            'chartLabels' => $labels,
            'chartPresentData' => $presentData,
            'chartAbsentData' => $absentData,
            'chartPermissionData' => $permissionData,
        ];
    }

    /**
     * Helper: Mendapatkan status presensi hari ini.
     */
    private function getTodayStatusData($idSertifikat): array
    {
        $today = Carbon::today()->toDateString();
        $possibleIds = $this->getPossibleUserIds($idSertifikat);

        $attendance = Attendance::whereIn('id_sertifikat', $possibleIds)
                                ->where('date', $today)
                                ->first();

        if ($attendance) {
            $status = $attendance->status;
            $time = Carbon::parse($attendance->created_at)->format('H:i');
            $location = $attendance->location;
            $notes = $attendance->notes;
            $message = "Anda sudah presensi $status pada pukul $time di $location. " . ($notes ? "Catatan: $notes" : "");
            $badgeClass = '';

            switch ($status) {
                case 'Hadir':
                    $badgeClass = 'status-badge-hadir';
                    break;
                case 'Izin':
                    $badgeClass = 'status-badge-izin';
                    break;
                case 'Alpha':
                    $badgeClass = 'status-badge-alpha';
                    break;
                default:
                    $badgeClass = 'status-badge-pending';
            }

            return [
                'status' => $status,
                'message' => $message,
                'badgeClass' => $badgeClass
            ];
        } else {
            return [
                'status' => 'Belum Presensi',
                'message' => 'Anda belum melakukan presensi hari ini.',
                'badgeClass' => 'status-badge-pending'
            ];
        }
    }

    /**
     * Helper: Mendapatkan riwayat presensi 5 hari terakhir.
     */
    private function getRecentHistoryData($idSertifikat)
    {
        $possibleIds = $this->getPossibleUserIds($idSertifikat);
        return Attendance::whereIn('id_sertifikat', $possibleIds)
                         ->where('date', '<', Carbon::today()->toDateString())
                         ->orderBy('date', 'desc')
                         ->limit(5)
                         ->get();
    }

    public function get(Request $request)
    {
        $request->validate([
            'id_sertifikat' => 'required|string',
            'year' => 'required|integer',
            'month' => 'required|integer|between:1,12',
        ]);
    
        $idSertifikat = $request->input('id_sertifikat');
        $year = $request->input('year');
        $month = $request->input('month');
        $possibleIds = $this->getPossibleUserIds($idSertifikat);
    
        $attendanceRecords = DB::table('attendances')
                            ->whereIn('id_sertifikat', $possibleIds)
                            ->whereYear('date', $year)
                            ->whereMonth('date', $month)
                            ->orderBy('date', 'desc')
                            ->get();
    
        return response()->json([
            'success' => true,
            'message' => 'Data retrieved successfully.',
            'data' => $attendanceRecords
        ]);
    }

    /**
     * Save a new attendance record.
     */
    public function save(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_sertifikat' => 'required|string|max:255',
            'date' => 'required|date',
            'status' => 'required|in:Hadir,Izin',
            'location' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000',
            'time' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid request data.',
                'errors' => $validator->errors()
            ], 400);
        }

        $idSertifikat = $request->input('id_sertifikat');
        $date = Carbon::parse($request->input('date'))->format('Y-m-d');
        $status = $request->input('status');
        $location = $request->input('location') ?: 'Lokasi Online (Presensi Web)';
        $notes = $request->input('notes') ?? '';  // ✅ default '' bukan null (kolom NOT NULL)
        $time = $request->input('time') ?: Carbon::now()->format('H:i:s');

        try {
            $possibleIds = $this->getPossibleUserIds($idSertifikat);

            // Cek apakah sudah ada presensi untuk tanggal ini
            $existingAttendance = Attendance::whereIn('id_sertifikat', $possibleIds)
                                            ->where('date', $date)
                                            ->first();

            if ($existingAttendance) {
                return response()->json([
                    'success' => true,
                    'message' => 'Anda sudah melakukan presensi hari ini.',
                    'data' => $existingAttendance
                ], 200);
            }

            $attendance = new Attendance();
            $attendance->id_sertifikat = $idSertifikat;
            $attendance->date = $date;
            $attendance->status = $status;
            $attendance->location = $location;
            $attendance->notes = $notes;
            $attendance->time = $time;
            $attendance->save();

            return response()->json([
                'success' => true,
                'message' => 'Attendance saved successfully.',
                'data' => $attendance
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to save attendance: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get attendance statistics for a specific user, month, and year.
     */
    public function stats(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_sertifikat' => 'required|string|max:255',
            'year' => 'required|integer|min:2000|max:2100',
            'month' => 'required|integer|min:1|max:12',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid request data.',
                'errors' => $validator->errors()
            ], 400);
        }

        $idSertifikat = $request->input('id_sertifikat');
        $year = $request->input('year');
        $month = $request->input('month');

        try {
            $possibleIds = $this->getPossibleUserIds($idSertifikat);
            
            // Ambil data sertifikat untuk mendapatkan tgl_mulai dan tgl_selesai
            $sertifikat = SertifikatPKL::whereIn('id_sertifikat', $possibleIds)->first();
            
            // Tanggal awal dan akhir bulan
            $monthStart = Carbon::createFromDate($year, $month, 1)->startOfMonth();
            $monthEnd = Carbon::createFromDate($year, $month, 1)->endOfMonth();
            
            // Tanggal mulai dan selesai magang
            $internshipStart = $sertifikat ? Carbon::parse($sertifikat->tgl_mulai) : $monthStart;
            $internshipEnd = $sertifikat ? Carbon::parse($sertifikat->tgl_selesai) : $monthEnd;
            
            // Tentukan periode perhitungan yang sebenarnya:
            // - Mulai dari yang lebih akhir antara awal bulan atau tgl_mulai magang
            // - Berakhir di yang lebih awal antara akhir bulan, tgl_selesai magang, atau hari ini
            $startDate = $monthStart->copy()->max($internshipStart);
            $endDate = $monthEnd->copy()->min($internshipEnd)->min(Carbon::today());
            
            // Jika periode tidak valid (belum mulai magang di bulan ini), return data kosong
            if ($startDate->greaterThan($endDate)) {
                return response()->json([
                    'success' => true,
                    'message' => 'Belum ada periode magang di bulan ini.',
                    'data' => [
                        'totalDays' => 0,
                        'presentDays' => 0,
                        'absentDays' => 0,
                        'permissionDays' => 0,
                        'presentPercentage' => 0,
                        'absentPercentage' => 0,
                        'permissionPercentage' => 0,
                    ]
                ]);
            }

            $attendances = Attendance::whereIn('id_sertifikat', $possibleIds)
                                    ->whereBetween('date', [$startDate, $endDate])
                                    ->get();

            // Filter presensi agar tidak menghitung presensi hari Sabtu & Minggu (jika ada)
            $presentDays = $attendances->reject(function ($att) {
                return Carbon::parse($att->date)->isWeekend();
            })->where('status', 'Hadir')->count();

            $permissionDays = $attendances->reject(function ($att) {
                return Carbon::parse($att->date)->isWeekend();
            })->where('status', 'Izin')->count();

            // Hitung hanya hari kerja (Senin - Jumat) dalam periode magang yang valid
            $totalDays = 0;
            $tempDate = $startDate->copy();
            while ($tempDate->lte($endDate)) {
                if (!$tempDate->isWeekend()) {
                    $totalDays++;
                }
                $tempDate->addDay();
            }

            $absentDays = $totalDays - ($presentDays + $permissionDays);
            if ($absentDays < 0) $absentDays = 0;

            $presentPercentage = $totalDays > 0 ? round(($presentDays / $totalDays) * 100) : 0;
            $absentPercentage = $totalDays > 0 ? round(($absentDays / $totalDays) * 100) : 0;
            $permissionPercentage = $totalDays > 0 ? round(($permissionDays / $totalDays) * 100) : 0;

            return response()->json([
                'success' => true,
                'message' => 'Attendance statistics retrieved successfully.',
                'data' => [
                    'totalDays' => $totalDays,
                    'presentDays' => $presentDays,
                    'absentDays' => $absentDays,
                    'permissionDays' => $permissionDays,
                    'presentPercentage' => $presentPercentage,
                    'absentPercentage' => $absentPercentage,
                    'permissionPercentage' => $permissionPercentage,
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve attendance statistics: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getFullHistory(Request $request)
    {
        $id_sertifikat = $request->query('id_sertifikat');
        if (empty($id_sertifikat)) {
            $id_sertifikat = Auth::id();
        }

        try {
            $possibleIds = $this->getPossibleUserIds($id_sertifikat);

            $attendances = Attendance::select('id', 'date', 'status', 'notes', 'time', 'created_at', 'updated_at')
                                    ->whereIn('id_sertifikat', $possibleIds)
                                    ->orderBy('date', 'desc')
                                    ->get()
                                    ->map(function ($attendance) {
                                        return [
                                            'tanggal' => Carbon::parse($attendance->date)->locale('id')->isoFormat('D MMMM YYYY'),
                                            'status' => $attendance->status ?? 'Status tidak tersedia',
                                            'keterangan' => $attendance->notes ?? 'Tidak ada keterangan',
                                            'time' => $attendance->time ?? null,
                                        ];
                                    });

            return response()->json([
                'success' => true,
                'data' => $attendances,
                'count' => $attendances->count(),
                'message' => 'Riwayat presensi berhasil dimuat'
            ]);

        } catch (\Exception $e) {
            \Log::error("Error in getFullHistory: " . $e->getMessage(), ['exception' => $e]);
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memuat riwayat presensi: ' . $e->getMessage()
            ], 500);
        }
    }
    public function deletePhoto(Request $request, $id_sertifikat)
    {
        // Temukan siswa berdasarkan id_sertifikat
        $siswa = Siswa::where('id_sertifikat', $id_sertifikat)->firstOrFail();

        // Pastikan pengguna yang login adalah pemilik profil yang akan dihapus
        if (auth()->guard('siswa')->user()->id_sertifikat !== $siswa->id_sertifikat) {
            abort(403, 'Unauthorized action.');
        }

        if ($siswa->foto_profil_url) {
            // Hapus file fisik dari storage
            if (Storage::disk('public')->exists($siswa->foto_profil_url)) {
                Storage::disk('public')->delete($siswa->foto_profil_url);
            }

            // Hapus path dari database
            $siswa->foto_profil_url = null;
            $siswa->save();

            // Opsional: Buat foto profil default berdasarkan id_sertifikat
            // Anda bisa menambahkan logika di sini jika ingin membuat avatar default dinamis
            
            return back()->with('success', 'Foto profil berhasil dihapus. Avatar default telah digunakan.');
        }

        return back()->with('error', 'Tidak ada foto profil untuk dihapus.');
    }

    public function siswaAktif(Request $request)
    {
        \App\Models\Instansi::syncInstansiIds();

        $search = $request->input('search');
        $today = Carbon::now()->format('Y-m-d');

        $query = SertifikatPKL::with('instansi')->where('sts_diambil', false)
            ->whereDate('tgl_mulai', '<=', $today)
            ->whereDate('tgl_selesai', '>=', $today);

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%$search%")
                  ->orWhere('asal_sekolah', 'like', "%$search%")
                  ->orWhere('bidang', 'like', "%$search%");
            });
        }

        $siswaAktif = $query->paginate(10);

        return view('dashboardsiswa.siswaaktif', compact('siswaAktif'));
    }

    public function menungguMagang(Request $request)
    {
        $search = $request->input('search');
        $today = Carbon::now()->format('Y-m-d');

        $query = SertifikatPKL::with('instansi')->whereDate('tgl_mulai', '>', $today);

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%$search%")
                  ->orWhere('asal_sekolah', 'like', "%$search%")
                  ->orWhere('bidang', 'like', "%$search%");
            });
        }

        $siswaMenunggu = $query->paginate(10);

        return view('dashboardsiswa.menunggumagang', compact('siswaMenunggu'));
    }

    public function sekolah(Request $request)
    {
        $search = $request->input('search');
        $query = SertifikatPKL::with('instansi');
        if ($search) {
            $query->where('asal_sekolah', 'like', "%$search%");
        }
        $allSiswa = $query->orderBy('nama_lengkap')->get();
        $grouped = $allSiswa->groupBy('asal_sekolah')->sortKeys();

        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 10;
        $currentPageItems = $grouped->slice(($currentPage - 1) * $perPage, $perPage)->all();
        $sekolahList = new LengthAwarePaginator($currentPageItems, count($grouped), $perPage, $currentPage, [
            'path' => LengthAwarePaginator::resolveCurrentPath(),
            'query' => $request->query()
        ]);

        return view('dashboardsiswa.sekolah', compact('sekolahList'));
    }

    public function siswaLulus(Request $request)
    {
        $search = $request->input('search');
        $today = Carbon::now()->format('Y-m-d');

        $query = SertifikatPKL::with('instansi')->where('sts_diambil', true)
            ->whereDate('tgl_selesai', '<', $today);

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%$search%")
                  ->orWhere('asal_sekolah', 'like', "%$search%")
                  ->orWhere('bidang', 'like', "%$search%");
            });
        }

        $siswaLulus = $query->orderBy('id_sertifikat', 'asc')->paginate(10);

        return view('dashboardsiswa.siswalulus', compact('siswaLulus'));
    }

    public function statistikMagang(Request $request)
    {
        $search = $request->input('search');
        $today = Carbon::now()->format('Y-m-d');

        $query = SertifikatPKL::where('sts_diambil', false)
            ->whereDate('tgl_mulai', '<=', $today)
            ->whereDate('tgl_selesai', '>=', $today);

        if ($search) {
            $query->where('bidang', 'like', "%$search%");
        }

        $allSiswa = $query->orderBy('nama_lengkap')->get();
        $grouped = $allSiswa->groupBy('bidang');

        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 10;
        $currentPageItems = $grouped->slice(($currentPage - 1) * $perPage, $perPage)->all();
        $data = new LengthAwarePaginator($currentPageItems, count($grouped), $perPage, $currentPage, [
            'path' => LengthAwarePaginator::resolveCurrentPath(),
            'query' => $request->query()
        ]);

        return view('dashboardsiswa.statistik', compact('data'));
    }
}
