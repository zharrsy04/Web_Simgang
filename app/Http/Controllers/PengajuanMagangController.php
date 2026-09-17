<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PengajuanMagang;
use App\Models\SertifikatPKL;
use App\Models\Instansi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;

class PengajuanMagangController extends Controller
{
    public function showForm()
    {
        $user = Auth::user();
        
        // Cek apakah sudah ada pengajuan
        $pengajuan = PengajuanMagang::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->first();
            
        if ($pengajuan && $pengajuan->status === 'pending') {
            return redirect()->route('siswa.dashboard')->with('info', 'Anda masih memiliki pengajuan magang yang menunggu verifikasi.');
        }
        
        // Ambil data magang untuk kalender (sama seperti di SiswaDashboardController)
        $semuaMagang = SertifikatPKL::select('id_sertifikat', 'nama_lengkap', 'nim_nis', 'asal_sekolah', 'jurusan', 'bidang', 'tgl_mulai', 'tgl_selesai', 'sts_diambil')
            ->whereNotNull('tgl_mulai')
            ->whereNotNull('tgl_selesai')
            ->where('sts_diambil', false)
            ->orderBy('tgl_mulai', 'desc')
            ->get();
            
        $instansis = Instansi::orderBy('nama', 'asc')->get();
        
        return view('siswa.form-pengajuan', compact('pengajuan', 'semuaMagang', 'instansis'));
    }
    
    public function store(Request $request)
    {
        $existing = PengajuanMagang::where('user_id', Auth::id())
            ->where('status', 'pending')
            ->first();
            
        if ($existing) {
            return redirect()->route('siswa.dashboard')->with('error', 'Anda masih memiliki pengajuan magang yang menunggu verifikasi.');
        }

        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'nim_nis' => 'required|string|max:50',
            'asal_sekolah' => 'required|string|max:255',
            'instansi_id' => 'nullable|exists:instansi,id',
            'jurusan' => 'required|string|max:255',
            'tempat_lahir' => 'required|string|max:255',
            'tgl_lahir' => 'required|date',
            'bidang' => 'required|string|max:100',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
            'dokumen' => 'nullable|file|mimes:pdf,doc,docx,png,jpg,jpeg|max:5120',
            'logo' => 'nullable|image|mimes:png,jpg,jpeg,svg,webp|max:2048',
        ], [
            'nama.required' => 'Nama lengkap harus diisi',
            'nim_nis.required' => 'NIM/NIS harus diisi',
            'asal_sekolah.required' => 'Asal sekolah/universitas harus diisi',
            'jurusan.required' => 'Jurusan harus diisi',
            'tempat_lahir.required' => 'Tempat lahir harus diisi',
            'tgl_lahir.required' => 'Tanggal lahir harus diisi',
            'bidang.required' => 'Pilih bidang magang',
            'tanggal_mulai.required' => 'Tanggal mulai harus diisi',
            'tanggal_selesai.required' => 'Tanggal selesai harus diisi',
            'tanggal_selesai.after' => 'Tanggal selesai harus setelah tanggal mulai',
            'dokumen.mimes' => 'Dokumen harus berformat PDF, DOC, DOCX, PNG, atau JPG',
            'dokumen.max' => 'Ukuran dokumen maksimal 5MB',
            'logo.image' => 'Logo harus berupa gambar',
            'logo.mimes' => 'Logo harus berformat PNG, JPG, JPEG, SVG, atau WEBP',
            'logo.max' => 'Ukuran logo maksimal 2MB',
        ]);
        
        if ($validator->fails()) {
            $oldInput = $request->except(['dokumen', 'logo']);
            
            if ($request->hasFile('dokumen')) {
                $fileValidator = \Illuminate\Support\Facades\Validator::make($request->only('dokumen'), [
                    'dokumen' => 'nullable|file|mimes:pdf,doc,docx,png,jpg,jpeg|max:5120',
                ]);
                
                if (!$fileValidator->fails()) {
                    $path = $request->file('dokumen')->store('dokumen_pengajuan', 'public');
                    $oldInput['temp_dokumen_path'] = $path;
                    $oldInput['temp_dokumen_name'] = $request->file('dokumen')->getClientOriginalName();
                }
            } elseif ($request->filled('temp_dokumen_path')) {
                $oldInput['temp_dokumen_path'] = $request->temp_dokumen_path;
                $oldInput['temp_dokumen_name'] = $request->temp_dokumen_name;
            }
            
            return redirect()->back()->withErrors($validator)->withInput($oldInput);
        }

        $validated = $validator->validated();
        
        try {
            DB::beginTransaction();
            
            $dokumenPath = null;
            if ($request->hasFile('dokumen')) {
                $file = $request->file('dokumen');
                $dokumenPath = $file->store('dokumen_pengajuan', 'public');
            } elseif ($request->filled('temp_dokumen_path')) {
                $dokumenPath = $request->temp_dokumen_path;
            }

            // Handle Instansi & Logo
            $instansiId = $validated['instansi_id'] ?? null;
            $uploadedLogoPath = null;

            if ($request->hasFile('logo')) {
                $uploadedLogoPath = $request->file('logo')->store('logos', 'public');
            }

            if ($instansiId) {
                // Jika instansi dipilih dari dropdown, tetapi ada upload logo & instansi tersebut belum punya logo
                $instansi = Instansi::find($instansiId);
                if ($instansi && $uploadedLogoPath) {
                    if (empty($instansi->logo)) {
                        $instansi->update(['logo' => $uploadedLogoPath]);
                    }
                }
            } else {
                // Jika diisi manual (tanpa memilih instansi dropdown)
                $asalSekolah = trim($validated['asal_sekolah']);
                
                // Cari apakah ada instansi yang cocok dengan algoritma findMatchingInstansi
                $existingInstansi = Instansi::findMatchingInstansi($asalSekolah);

                if ($existingInstansi) {
                    $instansiId = $existingInstansi->id;
                    // Update logo jika belum ada logo dan user mengupload logo baru
                    if (empty($existingInstansi->logo) && $uploadedLogoPath) {
                        $existingInstansi->update(['logo' => $uploadedLogoPath]);
                    }
                } else {
                    // Buat record instansi baru
                    $newInstansi = Instansi::create([
                        'nama' => $asalSekolah,
                        'logo' => $uploadedLogoPath,
                    ]);
                    $instansiId = $newInstansi->id;
                }
            }
            
            PengajuanMagang::create([
                'user_id' => Auth::id(),
                'nama' => $validated['nama'],
                'nim_nis' => $validated['nim_nis'],
                'asal_sekolah' => $validated['asal_sekolah'],
                'instansi_id' => $instansiId,
                'jurusan' => $validated['jurusan'],
                'tempat_lahir' => $validated['tempat_lahir'],
                'tgl_lahir' => $validated['tgl_lahir'],
                'bidang' => $validated['bidang'],
                'tanggal_mulai' => $validated['tanggal_mulai'],
                'tanggal_selesai' => $validated['tanggal_selesai'],
                'dokumen_path' => $dokumenPath,
                'status' => 'pending',
            ]);
            
            DB::commit();
            
            return redirect()->route('siswa.dashboard')
                ->with('success', 'Pengajuan magang berhasil dikirim. Tunggu persetujuan dari mentor.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error submitting pengajuan magang: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat mengirim pengajuan.')
                ->withInput();
        }
    }
    
    /**
     * API endpoint untuk mendapatkan count pengajuan pending
     */
    public function countPending()
    {
        try {
            $count = PengajuanMagang::where('status', 'pending')->count();
            
            return response()->json([
                'success' => true,
                'count' => $count,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Detail pengajuan untuk siswa (Read-only)
     */
    public function detailSiswa()
    {
        $pengajuan = PengajuanMagang::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->first();
            
        if (!$pengajuan) {
            return redirect()->route('pengajuan.form')->with('error', 'Anda belum memiliki pengajuan magang.');
        }
        
        return view('siswa.detail-pengajuan', compact('pengajuan'));
    }

    /**
     * Halaman daftar pengajuan untuk verifikasi (Mentor)
     */
    public function listPengajuan()
    {
        $user = Auth::user();

        $query = PengajuanMagang::with('user')
            ->orderBy('created_at', 'desc');

        // Filter berdasarkan role: Mentor hanya lihat bidangnya, Sekretariat lihat semua
        if ($user->role === 'mentor' && $user->bidang) {
            $query->where('bidang', $user->bidang);
        }

        $pengajuans = $query->paginate(20);

        return view('mentor.pengajuan-list', compact('pengajuans'));
    }

    /**
     * Halaman verifikasi pengajuan magang (Mentor)
     * Menampilkan pengajuan yang belum diverifikasi
     */
    public function verifikasiPengajuan()
    {
        try {
            $user = Auth::user();

            // Ambil pengajuan yang status pending (belum diverifikasi)
            $query = PengajuanMagang::with('user')
                ->where('status', 'pending')
                ->orderBy('created_at', 'asc');

            // Filter berdasarkan role: Mentor hanya lihat bidangnya, Sekretariat lihat semua
            if ($user->role === 'mentor' && $user->bidang) {
                $query->where('bidang', $user->bidang);
            }

            $pengajuans = $query->paginate(10);

            // Hitung total pengajuan pending
            $totalPendingQuery = PengajuanMagang::where('status', 'pending');
            if ($user->role === 'mentor' && $user->bidang) {
                $totalPendingQuery->where('bidang', $user->bidang);
            }
            $totalPending = $totalPendingQuery->count();

            // Hitung total yang sudah diapprove
            $totalApprovedQuery = PengajuanMagang::where('status', 'approved');
            if ($user->role === 'mentor' && $user->bidang) {
                $totalApprovedQuery->where('bidang', $user->bidang);
            }
            $totalApproved = $totalApprovedQuery->count();

            // Hitung total yang ditolak
            $totalRejectedQuery = PengajuanMagang::where('status', 'rejected');
            if ($user->role === 'mentor' && $user->bidang) {
                $totalRejectedQuery->where('bidang', $user->bidang);
            }
            $totalRejected = $totalRejectedQuery->count();

            return view('mentor.verifikasi-pengajuan', compact(
                'pengajuans',
                'totalPending',
                'totalApproved',
                'totalRejected'
            ));

        } catch (\Exception $e) {
            Log::error('Error in verifikasiPengajuan: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat memuat halaman verifikasi');
        }
    }

    /**
     * Detail pengajuan untuk verifikasi
     */
    public function detailPengajuan($id)
    {
        try {
            $pengajuan = PengajuanMagang::with('user', 'approver', 'instansi')
                ->findOrFail($id);
            
            return view('mentor.detail-pengajuan', compact('pengajuan'));
            
        } catch (\Exception $e) {
            Log::error('Error in detailPengajuan: ' . $e->getMessage());
            return redirect()->route('mentor.verifikasi-pengajuan')
                ->with('error', 'Pengajuan tidak ditemukan');
        }
    }
    
    public function approve($id)
    {
        try {
            DB::beginTransaction();
            
            $pengajuan = PengajuanMagang::findOrFail($id);
            
            // Cek apakah sudah diverifikasi sebelumnya
            if ($pengajuan->status !== 'pending') {
                return redirect()->back()
                    ->with('error', 'Pengajuan ini sudah pernah diverifikasi sebelumnya.');
            }
            
            // Update status pengajuan
            $pengajuan->update([
                'status' => 'approved',
                'approved_by' => Auth::id(),
                'approved_at' => now(),
            ]);
            
            // Buat data sertifikat PKL
            // Catatan: 'status_magang' tidak ada di tbl_sertifikat_pkl, dihapus agar tidak error.
            // 'created_id' di DB adalah varchar(3), di-cast ke string agar tidak type mismatch.
            $sertifikat = SertifikatPKL::create([
                'nama_lengkap' => $pengajuan->nama,
                'nim_nis' => $pengajuan->nim_nis,
                'asal_sekolah' => $pengajuan->asal_sekolah,
                'instansi_id' => $pengajuan->instansi_id,
                'jurusan' => $pengajuan->jurusan,
                'tempat_lahir' => $pengajuan->tempat_lahir, // nullable, aman untuk record lama
                'tgl_lahir' => $pengajuan->tgl_lahir,
                'bidang' => $pengajuan->bidang,
                'tgl_mulai' => $pengajuan->tanggal_mulai,
                'tgl_selesai' => $pengajuan->tanggal_selesai,
                'tgl_sertifikat' => now(),
                'nm_pembimbing' => '-',
                'nik_pembimbing' => '-',
                'jabatan_pembimbing' => '-',
                'nm_kadis' => '-',
                'nip_kadis' => '-',
                'sts_kadis' => 0,
                'aprove_pembimbing' => 0,
                'approve_kadis' => 0,
                'created_id' => (string) $pengajuan->user_id,
                'created_by_role' => 'mentor',
                'sts_diambil' => false,
                'sertifikat_status' => 'draft',
            ]);
            
            // Update id_sertifikat di tabel users (tbl_login)
            $pengajuan->user->update([
                'id_sertifikat' => $sertifikat->id_sertifikat,
            ]);
            
            // Create MentorSiswa relationship
            \App\Models\MentorSiswa::create([
                'mentor_id' => Auth::id(),
                'siswa_id' => $sertifikat->id_sertifikat,
                'tanggal_bimbingan' => now(),
                'keterangan' => 'Ditambahkan otomatis saat persetujuan pengajuan magang',
                'is_active' => true,
            ]);
            
            DB::commit();
            
            // Kirim email notifikasi
            try {
                if ($pengajuan->user->email) {
                    Mail::raw("Halo {$pengajuan->nama},\n\nPengajuan magang Anda telah DISETUJUI oleh mentor.\nSilakan login ke sistem untuk mengakses dashboard siswa.\n\nTerima kasih,\nTim SIMGANG Diskominfo Cilacap", function($message) use ($pengajuan) {
                        $message->to($pengajuan->user->email)
                                ->subject('Status Pengajuan Magang - DITERIMA');
                    });
                }
            } catch (\Exception $e) {
                Log::error('Failed to send approval email: ' . $e->getMessage());
            }
            
            Log::info('Pengajuan approved', [
                'pengajuan_id' => $id,
                'user_id' => $pengajuan->user_id,
                'sertifikat_id' => $sertifikat->id_sertifikat,
                'approved_by' => Auth::id(),
            ]);
            
            return redirect()->route('mentor.verifikasi-pengajuan')
                ->with('success', 'Pengajuan magang dari ' . $pengajuan->nama . ' berhasil diterima. Siswa sekarang dapat mengakses dashboard.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error approving pengajuan: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menerima pengajuan: ' . $e->getMessage());
        }
    }
    
    public function reject(Request $request, $id)
    {
        $request->validate([
            'alasan_reject' => 'required|string|max:500',
        ], [
            'alasan_reject.required' => 'Alasan penolakan harus diisi',
        ]);
        
        try {
            $pengajuan = PengajuanMagang::findOrFail($id);
            
            // Cek apakah sudah diverifikasi sebelumnya
            if ($pengajuan->status !== 'pending') {
                return redirect()->back()
                    ->with('error', 'Pengajuan ini sudah pernah diverifikasi sebelumnya.');
            }
            
            $pengajuan->update([
                'status' => 'rejected',
                'alasan_reject' => $request->alasan_reject,
                'approved_by' => Auth::id(),
                'approved_at' => now(),
            ]);
            
            // Kirim email notifikasi
            try {
                if ($pengajuan->user->email) {
                    Mail::raw("Halo {$pengajuan->nama},\n\nMohon maaf, pengajuan magang Anda DITOLAK oleh mentor dengan alasan:\n{$request->alasan_reject}\n\nSilakan login ke sistem untuk mengajukan kembali setelah memperbaiki data Anda.\n\nTerima kasih,\nTim SIMGANG Diskominfo Cilacap", function($message) use ($pengajuan) {
                        $message->to($pengajuan->user->email)
                                ->subject('Status Pengajuan Magang - DITOLAK');
                    });
                }
            } catch (\Exception $e) {
                Log::error('Failed to send rejection email: ' . $e->getMessage());
            }
            
            Log::info('Pengajuan rejected', [
                'pengajuan_id' => $id,
                'user_id' => $pengajuan->user_id,
                'rejected_by' => Auth::id(),
                'alasan' => $request->alasan_reject,
            ]);
            
            return redirect()->back()
                ->with('success', 'Pengajuan magang dari ' . $pengajuan->nama . ' telah ditolak. Siswa dapat melihat alasannya dan mengirim ulang pengajuan.');
                
        } catch (\Exception $e) {
            Log::error('Error rejecting pengajuan: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menolak pengajuan: ' . $e->getMessage());
        }
    }
    
    /**
     * Download dokumen pendukung (Bypass storage symlink issue)
     */
    public function downloadDokumen($id)
    {
        $pengajuan = PengajuanMagang::findOrFail($id);
        
        if (!$pengajuan->dokumen_path) {
            return redirect()->back()->with('error', 'Dokumen tidak ditemukan.');
        }
        
        $path = storage_path('app/public/' . $pengajuan->dokumen_path);
        
        if (!file_exists($path)) {
            return redirect()->back()->with('error', 'File fisik dokumen tidak ditemukan di server.');
        }
        
        return response()->download($path);
    }
}
