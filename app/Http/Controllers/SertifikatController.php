<?php

namespace App\Http\Controllers;

use App\Models\Penilaian;
use Illuminate\Http\Request;
use App\Models\SertifikatPKL;
use App\Models\MentorSiswa;
use App\Constants\BidangConstants;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Models\Attendance;
use App\Models\Announcement;
use Carbon\Carbon;
use App\Models\SertifikatHandover;
use App\Models\PdfDocument;
use App\Models\User;
use App\Models\Instansi;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class SertifikatController extends Controller
{
    use AuthorizesRequests;
public function verifikasi($id)
    {
        try {
            // 1. Ambil Data Sertifikat & Penilaian
            $sertifikat = SertifikatPKL::findOrFail($id);
            $penilaians = Penilaian::where('id_sertifikat', $id)->get();

            // 2. Render Tampilan PDF 
            $htmlContent = view('sertifikat.cetak_gabungan', compact('sertifikat', 'penilaians'))->render();

            // 3. Convert ke PDF
            $pdf = Pdf::loadHTML($htmlContent);
            
            // Settingan wajib biar gambar/logo muncul
            $pdf->setOptions([
                'isRemoteEnabled' => false,
                'isHtml5ParserEnabled' => true,
                'isPhpEnabled' => true,
                'chroot' => public_path(),
            ]);
            
            $pdf->setPaper('A4', 'portrait');

            // 4. STREAM (TAMPILKAN) PDF
            // stream() = Buka langsung di layar HP/Browser 
            // download() = Paksa download file
            $fileName = 'Verifikasi_Sertifikat_' . str_replace(' ', '_', $sertifikat->nama_lengkap) . '.pdf';
            
            return $pdf->stream($fileName);

        } catch (\Exception $e) {
            // Kalau ID salah atau data tidak ada
            return abort(404, 'Dokumen Sertifikat Tidak Ditemukan.');
        }
    }
    
public function dashboard()
    {
        $userId = Auth::id();
        $user = Auth::user();

        // Get siswa yang dibimbing mentor yang login
        $mentorSiswaIds = MentorSiswa::where('mentor_id', $userId)
            ->where('is_active', true)
            ->pluck('siswa_id')
            ->toArray();

        // Jika mentor belum memiliki siswa bimbingan, gunakan array kosong
        if (empty($mentorSiswaIds)) {
            $mentorSiswaIds = [0]; // ID yang tidak ada agar query mengembalikan kosong
        }

        // Mendapatkan sertifikat terbaru dari siswa bimbingan
        $sertifikat = SertifikatPKL::whereIn('id_sertifikat', $mentorSiswaIds)
            ->orderBy('tgl_sertifikat', 'desc')
            ->first();

        // Menghitung jumlah siswa aktif dari siswa bimbingan
        $siswaAktif = SertifikatPKL::whereIn('id_sertifikat', $mentorSiswaIds)
            ->where('sts_diambil', 0)
            ->whereDate('tgl_mulai', '<=', now())
            ->whereDate('tgl_selesai', '>=', now())
            ->count();

        // Menghitung jumlah siswa yang menunggu magang dari siswa bimbingan
        $menungguMagang = SertifikatPKL::whereIn('id_sertifikat', $mentorSiswaIds)
            ->whereDate('tgl_mulai', '>', now())
            ->count();

        // Menghitung jumlah sekolah yang unik dari siswa bimbingan
        $sekolahCount = SertifikatPKL::whereIn('id_sertifikat', $mentorSiswaIds)
            ->distinct('asal_sekolah')
            ->count('asal_sekolah');

        // Menghitung jumlah siswa lulus dari siswa bimbingan
        $siswaLulus = SertifikatPKL::whereIn('id_sertifikat', $mentorSiswaIds)
            ->where('sts_diambil', 1)
            ->whereDate('tgl_selesai', '<', now())
            ->count();

        // Ambil data statistik bidang magang untuk chart di dashboard mentor (SEMUA siswa, bukan hanya bimbingan)
        $data = SertifikatPKL::select('bidang', DB::raw('count(*) as total'))
            ->groupBy('bidang')
            ->get();

        // Jika tidak ada data, berikan data default kosong agar chart tetap bisa render
        if ($data->isEmpty()) {
            $data = collect([
                (object) ['bidang' => 'Belum ada siswa', 'total' => 0]
            ]);
        }

        // Ambil pengumuman terbaru yang sudah dipublikasikan - SEMUA pengumuman dari semua mentor/sekretariat
        $pengumumans = Announcement::where('published_at', '<=', Carbon::now())
                                 ->orderByDesc('published_at')
                                 ->limit(10) // Batasi 10 pengumuman terbaru
                                 ->get();

        // Ambil data magang untuk Kalender (SEMUA siswa magang aktif, bukan hanya bimbingan mentor)
        $semuaMagang = SertifikatPKL::select('nama_lengkap', 'asal_sekolah', 'bidang', 'tgl_mulai', 'tgl_selesai')
            ->where('sts_diambil', 0)
            ->whereNotNull('tgl_mulai')
            ->whereNotNull('tgl_selesai')
            ->get()
            ->map(function ($item) {
                // Format tanggal ke YYYY-MM-DD agar sesuai dengan format JavaScript
                $item->tgl_mulai = $item->tgl_mulai->format('Y-m-d');
                $item->tgl_selesai = $item->tgl_selesai->format('Y-m-d');
                return $item;
            });

        // Debug: log data semuaMagang
        \Log::info('Data semuaMagang untuk kalender mentor:', [
            'mentor_id' => $userId,
            'semuaMagang_count' => $semuaMagang->count(),
            'semuaMagang_data' => $semuaMagang->take(5)->toArray() // Log 5 data pertama saja
        ]);

        return view('mentor.dashboard', compact('sertifikat', 'siswaAktif', 'menungguMagang', 'sekolahCount', 'siswaLulus', 'data', 'pengumumans', 'semuaMagang'));
    }

    public function index(Request $request)
    {
        $role = Auth::user()->role;
        $userId = Auth::id();

        // Ambil data pengumuman terbaru
        $pengumumans = Announcement::latest()->get(); 

        if ($role == 'siswa') {
            $user = Auth::user();
            $sertifikats = SertifikatPKL::where('nim_nis', $user->username)
                ->when($request->has('search') && $request->search != '', function ($query) use ($request) {
                    $query->where('nama_lengkap', 'LIKE', '%' . $request->search . '%');
                })
                ->orderBy('tgl_sertifikat', 'desc')
                ->paginate(10); 

            return view('siswa.dashboard', compact('sertifikats', 'pengumumans'));
        } elseif ($role == 'mentor' || $role == 'sekretariat') {
            // Query utama untuk mentor dan sekretariat - SEMUA data
            $query = SertifikatPKL::with('instansi');
            
            // Filter pencarian
            if ($request->has('search') && $request->search != '') {
                $searchTerm = $request->search;
                $query->where(function($q) use ($searchTerm) {
                    $q->where('nama_lengkap', 'LIKE', '%' . $searchTerm . '%')
                      ->orWhere('asal_sekolah', 'LIKE', '%' . $searchTerm . '%')
                      ->orWhere('bidang', 'LIKE', '%' . $searchTerm . '%')
                      ->orWhere('nim_nis', 'LIKE', '%' . $searchTerm . '%');
                });
            }
            
            $sertifikats = $query->orderBy('id_sertifikat', 'desc')
                                 ->paginate(10);


            // Reuse view yang sama untuk mentor dan sekretariat
            return view('mentor.index', compact('sertifikats', 'pengumumans'));
        }
        
        return abort(403, 'Unauthorized action.');
    }

    public function create()
    {
        // Allow both mentor and sekretariat
        $user = Auth::user();
        if (!in_array($user->role, ['mentor', 'sekretariat'])) {
            abort(403, 'Unauthorized: Hanya Mentor dan Sekretariat yang dapat membuat sertifikat');
        }
        
        // Jika dari halaman dashboard tertentu, persiapkan default values
        $from = request('from');
        $defaults = [];
        $today = \Carbon\Carbon::today();
        
        if ($from === 'siswaaktif') {
            // Set default dates untuk data yang akan muncul di halaman Peserta Magang
            // Kriteria: sts_diambil = 0, tgl_mulai <= hari ini, tgl_selesai >= hari ini
            $defaults['tgl_mulai'] = $today->format('Y-m-d');
            $defaults['tgl_selesai'] = $today->copy()->addDays(30)->format('Y-m-d');
            $defaults['tgl_sertifikat'] = $today->format('Y-m-d');
            $defaults['sts_diambil'] = 0;
            $defaults['aprove_pembimbing'] = 1;
            $defaults['approve_kadis'] = 1;
        } elseif ($from === 'siswalulus') {
            // Set default dates untuk data yang akan muncul di halaman Lulus Magang
            // Kriteria: sts_diambil = 1, tgl_selesai < hari ini
            $defaults['tgl_mulai'] = $today->copy()->subDays(30)->format('Y-m-d');
            $defaults['tgl_selesai'] = $today->copy()->subDays(1)->format('Y-m-d');
            $defaults['tgl_sertifikat'] = $today->copy()->subDays(1)->format('Y-m-d');
            $defaults['sts_diambil'] = 1;
            $defaults['aprove_pembimbing'] = 1;
            $defaults['approve_kadis'] = 1;
        } elseif ($from === 'menunggumagang') {
            // Set default dates untuk data yang akan muncul di halaman Menunggu Magang
            // Kriteria: tgl_mulai > hari ini
            $defaults['tgl_mulai'] = $today->copy()->addDays(1)->format('Y-m-d');
            $defaults['tgl_selesai'] = $today->copy()->addDays(31)->format('Y-m-d');
            $defaults['tgl_sertifikat'] = $today->copy()->addDays(31)->format('Y-m-d');
            $defaults['sts_diambil'] = 0;
            $defaults['aprove_pembimbing'] = 1;
            $defaults['approve_kadis'] = 1;
        } elseif ($from === 'sekolah') {
            // Set default values untuk data yang akan muncul di halaman Lembaga Pendidikan
            // Tidak ada filter khusus, gunakan default umum
            $defaults['tgl_mulai'] = $today->format('Y-m-d');
            $defaults['tgl_selesai'] = $today->copy()->addDays(30)->format('Y-m-d');
            $defaults['tgl_sertifikat'] = $today->copy()->addDays(30)->format('Y-m-d');
            $defaults['sts_diambil'] = 0;
            $defaults['aprove_pembimbing'] = 1;
            $defaults['approve_kadis'] = 1;
        }
        
        $instansis = Instansi::orderBy('nama', 'asc')->get();
        $mentors = User::where('role', 'mentor')->get();
        return view('mentor.create', compact('defaults', 'instansis', 'mentors'));
    }

    public function store(Request $request)
    {
        // Authorization: Only mentor and sekretariat can store
        $user = Auth::user();
        if (!in_array($user->role, ['mentor', 'sekretariat'])) {
            abort(403, 'Unauthorized: Hanya Mentor dan Sekretariat yang dapat membuat sertifikat');
        }

        // Validasi diluar try-catch agar ValidationException langsung ditangani Laravel
        // (muncul di form sebagai $errors dan withInput otomatis)
        $validBidang = implode(',', BidangConstants::getValidBidang());
        
        $validatedData = $request->validate([
            'sts_kadis'           => 'required|integer|in:0,1',
            'nama_lengkap'        => 'required|string|max:50',
            'asal_sekolah'        => 'required|string|max:50',
            'instansi_id'         => 'nullable|integer',
            'nim_nis'             => 'required|string|max:20|unique:tbl_sertifikat_pkl,nim_nis',
            'tempat_lahir'        => 'required|string|max:50',
            'tgl_lahir'           => 'required|date',
            'jurusan'             => 'required|string|max:100',
            'tgl_mulai'           => 'required|date',
            'tgl_selesai'         => 'required|date|after_or_equal:tgl_mulai',
            'tgl_sertifikat'      => 'required|date',
            'nm_pembimbing'       => 'required|string|max:50',
            'nik_pembimbing'      => 'required|string|max:25',
            'bidang'              => 'required|string|max:50|in:' . $validBidang,
            'jabatan_pembimbing'  => 'required|string|max:50',
            'no_sertifikat'       => ['nullable', 'string', 'max:50', new \App\Rules\UniqueIfFilled('tbl_sertifikat_pkl', 'no_sertifikat')],
            'nm_kadis'            => 'required|string|max:50',
            'nip_kadis'           => 'required|string|max:25',
            'jabatan_pegawai'     => 'required|string|max:100',
            'aprove_pembimbing'   => 'required|integer|in:0,1',
            'approve_kadis'       => 'required|integer|in:0,1',
            'sts_diambil'         => 'required|integer|in:0,1',
            'pejabat_ttd'         => 'required|string|max:100',
        ], [
            'no_sertifikat.max' => 'No. Sertifikat tidak boleh lebih dari 50 karakter.',
            'bidang.in' => 'Bidang yang dipilih tidak valid. Pilih salah satu dari: Bidang Sekretariat, Bidang Informatika, atau Bidang Statistik dan Persandian.',
        ]);

        try {
            // Convert string kosong menjadi NULL untuk no_sertifikat
            if (empty($validatedData['no_sertifikat'])) {
                $validatedData['no_sertifikat'] = null;
            }

            $from = $request->input('from');

            // Pastikan data dari Peserta Magang memenuhi filter halaman siswaaktif
            // Filter: sts_diambil = 0, tgl_mulai <= hari ini, tgl_selesai >= hari ini
            if ($from === 'siswaaktif') {
                $today = \Carbon\Carbon::today();
                $validatedData['sts_diambil'] = 0;

                $tglMulai = \Carbon\Carbon::parse($validatedData['tgl_mulai']);
                $tglSelesai = \Carbon\Carbon::parse($validatedData['tgl_selesai']);

                // Paksa tgl_mulai tidak boleh lebih dari hari ini
                if ($tglMulai->gt($today)) {
                    $validatedData['tgl_mulai'] = $today->format('Y-m-d');
                }
                // Paksa tgl_selesai tidak boleh kurang dari hari ini
                if ($tglSelesai->lt($today)) {
                    $validatedData['tgl_selesai'] = $today->format('Y-m-d');
                }
            }
            
            $validatedData['created_id'] = Auth::id();

            if ($request->filled('instansi_id')) {
                $validatedData['instansi_id'] = $request->instansi_id;
            } elseif (!empty($validatedData['asal_sekolah'])) {
                $matchedInstansi = Instansi::findMatchingInstansi($validatedData['asal_sekolah']);
                $validatedData['instansi_id'] = $matchedInstansi ? $matchedInstansi->id : null;
            }

            $sertifikat = SertifikatPKL::create($validatedData);

            \App\Models\MentorSiswa::create([
                'mentor_id' => $validatedData['created_id'],
                'siswa_id' => $sertifikat->id_sertifikat,
                'tanggal_bimbingan' => now(),
                'is_active' => true,
            ]);
            // Redirect sesuai halaman asal
            if ($from === 'siswaaktif') {
                return redirect()->route('dashboard.siswaaktif')->with('success', 'Data peserta berhasil ditambahkan.');
            } elseif ($from === 'siswalulus') {
                return redirect()->route('dashboard.siswalulus')->with('success', 'Data peserta berhasil ditambahkan.');
            } elseif ($from === 'menunggumagang') {
                return redirect()->route('dashboard.menunggumagang')->with('success', 'Data peserta berhasil ditambahkan.');
            } elseif ($from === 'sekolah') {
                $targetRoute = Auth::user()->role === 'sekretariat' 
                    ? 'sekretariat.instansi.index' 
                    : 'dashboard.sekolah';
                return redirect()->route($targetRoute)->with('success', 'Data peserta berhasil ditambahkan.');
            }
            return redirect()->route('mentor.index')->with('success', 'Sertifikat berhasil ditambahkan.');
        } catch (\Exception $e) {
            Log::error('Error storing sertifikat: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage())->withInput();
        }
    }

    public function show($id_sertifikat)
    {
        $sertifikat = SertifikatPKL::findOrFail($id_sertifikat);
        // load portofolio biar bisa dipanggil di blade
        $sertifikat->load('portofolio');
        return view('mentor.show', compact('sertifikat'));
    }

    public function edit(Request $request, $id_sertifikat)
    {
        $sertifikat = SertifikatPKL::findOrFail($id_sertifikat);
        
        // Authorization: Only mentor and sekretariat can edit
        $user = Auth::user();
        
        // Use policy authorization
        $this->authorize('update', $sertifikat);

        $from = $request->input('from');
        $instansis = Instansi::orderBy('nama', 'asc')->get();
        $mentors = User::where('role', 'mentor')->get();
        return view('mentor.edit', compact('sertifikat', 'from', 'instansis', 'mentors'));
    }

    public function update(Request $request, $id_sertifikat)
    {
        $sertifikat = SertifikatPKL::findOrFail($id_sertifikat);
        
        // Authorization: Use policy
        $this->authorize('update', $sertifikat);

        $validBidang = implode(',', BidangConstants::getValidBidang());
        
        $validatedData = $request->validate([
            'sts_kadis'           => 'required|integer|in:0,1',
            'nama_lengkap'        => 'required|string|max:50',
            'asal_sekolah'        => 'required|string|max:50',
            'instansi_id'         => 'nullable|integer',
            'nim_nis'             => 'required|string|max:20',
            'tempat_lahir'        => 'required|string|max:50',
            'tgl_lahir'           => 'required|date',
            'jurusan'             => 'required|string|max:100',
            'tgl_mulai'           => 'required|date',
            'tgl_selesai'         => 'required|date|after_or_equal:tgl_mulai',
            'tgl_sertifikat'      => 'required|date',
            'nm_pembimbing'       => 'required|string|max:50',
            'nik_pembimbing'      => 'required|string|max:25',
            'bidang'              => 'required|string|max:50|in:' . $validBidang,
            'jabatan_pembimbing'  => 'required|string|max:50',
            'no_sertifikat'       => ['nullable', 'string', 'max:50', new \App\Rules\UniqueIfFilled('tbl_sertifikat_pkl', 'no_sertifikat', $sertifikat->id_sertifikat, 'id_sertifikat')],
            'nm_kadis'            => 'required|string|max:50',
            'nip_kadis'           => 'required|string|max:25',
            'jabatan_pegawai'     => 'required|string|max:100',
            'aprove_pembimbing'   => 'required|integer|in:0,1',
            'approve_kadis'       => 'required|integer|in:0,1',
            'sts_diambil'         => 'required|integer|in:0,1',
            'pejabat_ttd'         => 'required|string|max:100',
        ], [
            'no_sertifikat.unique' => 'No. Sertifikat sudah digunakan.',
        ]);

        try {
            // Convert string kosong menjadi NULL untuk no_sertifikat
            if (empty($validatedData['no_sertifikat'])) {
                $validatedData['no_sertifikat'] = null;
            }

            if ($request->filled('instansi_id')) {
                $validatedData['instansi_id'] = $request->instansi_id;
            } elseif (!empty($validatedData['asal_sekolah'])) {
                $matchedInstansi = Instansi::findMatchingInstansi($validatedData['asal_sekolah']);
                $validatedData['instansi_id'] = $matchedInstansi ? $matchedInstansi->id : null;
            } else {
                $validatedData['instansi_id'] = null;
            }
            
            $sertifikat->update($validatedData);

            $from = $request->input('from');
            if ($from === 'siswalulus') {
                return redirect()->route('dashboard.siswalulus')->with('success', 'Sertifikat berhasil diperbarui.');
            } elseif ($from === 'siswaaktif') {
                return redirect()->route('dashboard.siswaaktif')->with('success', 'Sertifikat berhasil diperbarui.');
            } elseif ($from === 'menunggumagang') {
                return redirect()->route('dashboard.menunggumagang')->with('success', 'Sertifikat berhasil diperbarui.');
            } elseif ($from === 'sekolah') {
                $targetRoute = Auth::user()->role === 'sekretariat' 
                    ? 'sekretariat.instansi.index' 
                    : 'dashboard.sekolah';
                return redirect()->route($targetRoute)->with('success', 'Sertifikat berhasil diperbarui.');
            }

            return redirect()->route('mentor.index')->with('success', 'Sertifikat berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error('Error updating sertifikat: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memperbarui data: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy(Request $request, $id_sertifikat)
    {
        $sertifikat = SertifikatPKL::findOrFail($id_sertifikat);
        
        // Authorization: Use policy
        $this->authorize('delete', $sertifikat);

        try {
            DB::beginTransaction();

            // Delete related records manually to handle foreign key constraints
            // Some tables don't have onDelete('cascade') set up, so we delete manually
            
            // 1. Delete penilaian records
            Penilaian::where('id_sertifikat', $sertifikat->id_sertifikat)->delete();
            
            // 2. Delete attendance records
            Attendance::where('id_sertifikat', $sertifikat->id_sertifikat)->delete();
            
            // 3. Delete sertifikat handover records
            SertifikatHandover::where('id_sertifikat', $sertifikat->id_sertifikat)->delete();
            
            // 4. Update tbl_login to set id_sertifikat to NULL (to avoid cascade delete of user)
            User::where('id_sertifikat', $sertifikat->id_sertifikat)->update(['id_sertifikat' => null]);
            
            // 5. The following have onDelete('cascade') in migrations, but delete manually for clarity:
            // - portofolios (sertifikat_pkl_id)
            // - tasks (sertifikat_pkl_id)
            // - penempatan_bidang (id_sertifikat)
            // Laravel will handle these via cascade or we delete manually
            $sertifikat->portofolios()->delete();
            $sertifikat->penempatan()->delete();
            
            // 6. Finally delete the sertifikat record
            $sertifikat->delete();

            DB::commit();

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Sertifikat berhasil dihapus.'
                ]);
            }

            // Cek parameter 'from' secara eksplisit untuk menentukan redirect
            // Check both POST data and query string for 'from' parameter
            $from = $request->input('from') ?? $request->query('from');
            if ($from === 'siswaaktif') {
                return redirect()->route('dashboard.siswaaktif')->with('success', 'Sertifikat berhasil dihapus.');
            } elseif ($from === 'siswalulus') {
                return redirect()->route('dashboard.siswalulus')->with('success', 'Sertifikat berhasil dihapus.');
            } elseif ($from === 'sekolah') {
                $targetRoute = Auth::user()->role === 'sekretariat' 
                    ? 'sekretariat.instansi.index' 
                    : 'dashboard.sekolah';
                return redirect()->route($targetRoute)->with('success', 'Sertifikat berhasil dihapus.');
            } elseif ($from === 'menunggumagang') {
                return redirect()->route('dashboard.menunggumagang')->with('success', 'Sertifikat berhasil dihapus.');
            } elseif ($from === 'penilaian') {
                return redirect()->route('penilaian.index')->with('success', 'Sertifikat berhasil dihapus.');
            }
            
            // Fallback: cek referer hanya jika tidak ada parameter 'from' eksplisit
            $referer = $request->header('referer');
            if ($referer) {
                if (strpos($referer, '/penilaian') !== false) {
                    return redirect()->route('penilaian.index')->with('success', 'Sertifikat berhasil dihapus.');
                }
                if (strpos($referer, '/dashboard/siswaaktif') !== false) {
                    return redirect()->route('dashboard.siswaaktif')->with('success', 'Sertifikat berhasil dihapus.');
                }
                if (strpos($referer, '/dashboard/siswalulus') !== false) {
                    return redirect()->route('dashboard.siswalulus')->with('success', 'Sertifikat berhasil dihapus.');
                }
                if (strpos($referer, '/dashboard/sekolah') !== false || strpos($referer, '/sekretariat/instansi') !== false) {
                    $targetRoute = Auth::user()->role === 'sekretariat' 
                        ? 'sekretariat.instansi.index' 
                        : 'dashboard.sekolah';
                    return redirect()->route($targetRoute)->with('success', 'Sertifikat berhasil dihapus.');
                }
                if (strpos($referer, '/dashboard/menunggumagang') !== false) {
                    return redirect()->route('dashboard.menunggumagang')->with('success', 'Sertifikat berhasil dihapus.');
                }
            }

            return redirect()->route('mentor.index')->with('success', 'Sertifikat berhasil dihapus.');
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            
            // Log specific database error
            Log::error('Database error deleting sertifikat', [
                'sertifikat_id' => $sertifikat->id_sertifikat ?? 'unknown',
                'error' => $e->getMessage(),
                'sql' => $e->getSql() ?? 'N/A',
                'bindings' => $e->getBindings() ?? []
            ]);
            
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Database error: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Database error saat menghapus: ' . $e->getMessage());
        } catch (\Exception $e) {
            DB::rollBack();
            
            // Log general error
            Log::error('Error deleting sertifikat', [
                'sertifikat_id' => $sertifikat->id_sertifikat ?? 'unknown',
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat menghapus data.'
                ], 500);
            }

            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage());
        }
    }

    public function cetak(SertifikatPKL $sertifikat)
    {
        // Allow mentor and sekretariat to print
        $this->authorize('print', $sertifikat);
        return view('mentor.cetak', compact('sertifikat'));
    }

    public function cetakPDF(SertifikatPKL $sertifikat)
    {
        // Allow mentor and sekretariat to print
        $this->authorize('print', $sertifikat);

        try {
            $htmlContent = view('mentor.cetak', compact('sertifikat'))->render();

            $pdf = app('dompdf.wrapper')->loadHTML($htmlContent);
            $pdf->setPaper('A4', 'landscape')->setOptions([
                'margin' => 0,
                'isRemoteEnabled' => false,
                'isHtml5ParserEnabled' => true,
                'isPhpEnabled' => true,
                'chroot' => public_path(),
            ]);

            $fileName = 'sertifikat_' . $sertifikat->nim_nis . '_' . date('Y-m-d') . '.pdf';

            return $pdf->stream($fileName);
        } catch (\Exception $e) {
            Log::error('Error generating PDF: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat membuat PDF: ' . $e->getMessage());
        }
    }

    public function cetakView(SertifikatPKL $sertifikat)
    {
        $penilaians = Penilaian::where('id_sertifikat', $sertifikat->id_sertifikat)->get();
        $kabid = (object)[
            'nama' => $sertifikat->nama_kabid,
            'nip' => $sertifikat->nip_kabid
        ];
        return view('sertifikat.cetak_gabunganview', compact('sertifikat', 'penilaians', 'kabid'));
    }

    public function cetakGabungan(SertifikatPKL $sertifikat)
    {
        $penilaians = Penilaian::where('id_sertifikat', $sertifikat->id_sertifikat)->get();
        $kabid = (object)[
            'nama' => $sertifikat->nama_kabid,
            'nip' => $sertifikat->nip_kabid
        ];
        return view('sertifikat.cetak_gabung', compact('sertifikat', 'penilaians', 'kabid'));
    }

    public function cetakGabungview($id_sertifikat)
    {
        try {
            $sertifikat = SertifikatPKL::findOrFail($id_sertifikat);
            $penilaians = Penilaian::where('id_sertifikat', $id_sertifikat)->get();
            $kabid = (object)[
                'nama' => $sertifikat->nama_kabid,
                'nip' => $sertifikat->nip_kabid
            ];

            return view('sertifikat.cetak_gabunganview', compact('sertifikat', 'penilaians', 'kabid'));
        } catch (\Exception $e) {
            Log::error('Error in cetakGabungview: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }
    }

    public function cetakGabung($id)
    {
        try {
            // Allow mentor and sekretariat to print
            $this->authorize('print', SertifikatPKL::findOrFail($id));

            // Mengambil data sertifikat dan penilaian
            $sertifikat = SertifikatPKL::findOrFail($id);
            $penilaians = Penilaian::where('id_sertifikat', $id)->get();

            $kabid = (object)[
                'nama' => $sertifikat->nama_kabid,
                'nip' => $sertifikat->nip_kabid
            ];

            // Render view 'cetak_gabungan' menjadi HTML
            $htmlContent = view('sertifikat.cetak_gabungan', compact('sertifikat', 'penilaians', 'kabid'))->render();

            // Buat instance PDF dari HTML menggunakan facade
            $pdf = Pdf::loadHTML($htmlContent);
            
            // Mengatur opsi Dompdf untuk mendukung gambar eksternal (dari URL)
            $pdf->setOptions([
                'isRemoteEnabled' => false,
                'isHtml5ParserEnabled' => true,
                'isPhpEnabled' => true,
                'chroot' => public_path(),
            ]);
            
            // Mengatur ukuran kertas dan orientasi
            $pdf->setPaper('A4', 'portrait');

            // Menentukan nama file PDF dengan timestamp untuk cache busting
            $timestamp = time();
            $fileName = 'sertifikat_gabungan_' . str_replace(' ', '_', $sertifikat->nama_lengkap) . '_' . $timestamp . '.pdf';

            // AGGRESSIVE CACHE BUSTING - Paksa browser untuk tidak cache PDF
            $headers = [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . $fileName . '"',
                'Cache-Control' => 'no-store, no-cache, must-revalidate, post-check=0, pre-check=0, max-age=0',
                'Pragma' => 'no-cache',
                'Expires' => 'Sat, 01 Jan 2000 00:00:00 GMT',
                'Last-Modified' => gmdate('D, d M Y H:i:s') . ' GMT',
                'ETag' => md5($timestamp . $id),
            ];

            return response($pdf->output(), 200, $headers);

        } catch (\Exception $e) {
            // Log error jika terjadi kesalahan dan kembalikan pesan error
            Log::error('Error in cetakGabung: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat membuat PDF gabungan: ' . $e->getMessage());
        }
    }

    /**
     * Cetak sertifikat + nilai khusus untuk role siswa.
     * Siswa hanya bisa mencetak sertifikat miliknya sendiri.
     */
    public function cetakUntukSiswa($id_sertifikat)
    {
        try {
            $user = Auth::user();

            if (!$user || $user->role !== 'siswa') {
                return redirect()->route('login')->with('error', 'Anda harus login sebagai siswa.');
            }

            $sertifikat = SertifikatPKL::findOrFail($id_sertifikat);

            // Pastikan siswa hanya bisa cetak sertifikat miliknya sendiri
            $milikSiswa = (
                (string)$sertifikat->id_sertifikat === (string)$user->id_sertifikat
                || $sertifikat->nim_nis === $user->username
                || (string)$sertifikat->created_id === (string)$user->id
            );

            if (!$milikSiswa) {
                return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk mencetak sertifikat ini.');
            }

            $penilaians = Penilaian::where('id_sertifikat', $id_sertifikat)->get();

            // Render view menjadi HTML
            $htmlContent = view('sertifikat.cetak_gabungan', compact('sertifikat', 'penilaians'))->render();

            // Generate PDF
            $pdf = Pdf::loadHTML($htmlContent);
            $pdf->setOptions([
                'isRemoteEnabled'    => false,
                'isHtml5ParserEnabled' => true,
                'isPhpEnabled'       => true,
                'chroot'             => public_path(),
            ]);
            $pdf->setPaper('A4', 'portrait');

            $timestamp = time();
            $fileName  = 'sertifikat_' . str_replace(' ', '_', $sertifikat->nama_lengkap) . '_' . $timestamp . '.pdf';

            $headers = [
                'Content-Type'        => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . $fileName . '"',
                'Cache-Control'       => 'no-store, no-cache, must-revalidate, max-age=0',
                'Pragma'              => 'no-cache',
                'Expires'             => 'Sat, 01 Jan 2000 00:00:00 GMT',
            ];

            return response($pdf->output(), 200, $headers);

        } catch (\Exception $e) {
            Log::error('Error in cetakUntukSiswa: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mencetak: ' . $e->getMessage());
        }
    }

    // Method V2 dengan cache busting lebih kuat
    public function cetakGabungV2($id)
    {
        try {
            $sertifikat = SertifikatPKL::findOrFail($id);
            $penilaians = Penilaian::where('id_sertifikat', $id)->get();

            $htmlContent = view('sertifikat.cetak_gabungan', compact('sertifikat', 'penilaians'))->render();

            $pdf = Pdf::loadHTML($htmlContent);
            
            $pdf->setOptions([
                'isRemoteEnabled' => false,
                'isHtml5ParserEnabled' => true,
                'isPhpEnabled' => true,
                'chroot' => public_path(),
            ]);
            
            $pdf->setPaper('A4', 'portrait');

            $timestamp = microtime(true);
            $fileName = 'sertifikat_v2_' . str_replace(' ', '_', $sertifikat->nama_lengkap) . '_' . $timestamp . '.pdf';

            // SUPER AGGRESSIVE CACHE BUSTING
            $headers = [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . $fileName . '"',
                'Cache-Control' => 'no-store, no-cache, must-revalidate, post-check=0, pre-check=0, max-age=0, private',
                'Pragma' => 'no-cache',
                'Expires' => '0',
                'Last-Modified' => gmdate('D, d M Y H:i:s') . ' GMT',
                'ETag' => md5($timestamp . $id . rand()),
                'X-Accel-Expires' => '0',
            ];

            return response($pdf->output(), 200, $headers);

        } catch (\Exception $e) {
            Log::error('Error in cetakGabungV2: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat membuat PDF gabungan V2: ' . $e->getMessage());
        }
    }

    // Method V3 dengan layout center yang lebih baik (width 38%, 55%, 50%)
    public function cetakGabungV3($id)
    {
        try {
            $sertifikat = SertifikatPKL::findOrFail($id);
            $penilaians = Penilaian::where('id_sertifikat', $id)->get();

            $htmlContent = view('sertifikat.cetak_gabungan', compact('sertifikat', 'penilaians'))->render();

            $pdf = Pdf::loadHTML($htmlContent);
            
            $pdf->setOptions([
                'isRemoteEnabled' => false,
                'isHtml5ParserEnabled' => true,
                'isPhpEnabled' => true,
                'chroot' => public_path(),
            ]);
            
            $pdf->setPaper('A4', 'landscape');

            $timestamp = microtime(true);
            $fileName = 'sertifikat_v3_' . str_replace(' ', '_', $sertifikat->nama_lengkap) . '_' . $timestamp . '.pdf';

            // SUPER AGGRESSIVE CACHE BUSTING
            $headers = [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . $fileName . '"',
                'Cache-Control' => 'no-store, no-cache, must-revalidate, post-check=0, pre-check=0, max-age=0, private',
                'Pragma' => 'no-cache',
                'Expires' => '0',
                'Last-Modified' => gmdate('D, d M Y H:i:s') . ' GMT',
                'ETag' => md5($timestamp . $id . rand()),
                'X-Accel-Expires' => '0',
            ];

            return response($pdf->output(), 200, $headers);

        } catch (\Exception $e) {
            Log::error('Error in cetakGabungV3: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat membuat PDF gabungan V3: ' . $e->getMessage());
        }
    }

    // Method V4 dengan layout SUPER CENTER (width 30%, 45%, 40%)
    public function cetakGabungV4($id)
    {
        try {
            $sertifikat = SertifikatPKL::findOrFail($id);
            $penilaians = Penilaian::where('id_sertifikat', $id)->get();

            $htmlContent = view('sertifikat.cetak_gabungan', compact('sertifikat', 'penilaians'))->render();

            $pdf = Pdf::loadHTML($htmlContent);
            
            $pdf->setOptions([
                'isRemoteEnabled' => false,
                'isHtml5ParserEnabled' => true,
                'isPhpEnabled' => true,
                'chroot' => public_path(),
            ]);
            
            $pdf->setPaper('A4', 'landscape');

            $timestamp = microtime(true);
            $fileName = 'sertifikat_v4_CENTER_' . str_replace(' ', '_', $sertifikat->nama_lengkap) . '_' . $timestamp . '.pdf';

            // SUPER AGGRESSIVE CACHE BUSTING
            $headers = [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . $fileName . '"',
                'Cache-Control' => 'no-store, no-cache, must-revalidate, post-check=0, pre-check=0, max-age=0, private',
                'Pragma' => 'no-cache',
                'Expires' => '0',
                'Last-Modified' => gmdate('D, d M Y H:i:s') . ' GMT',
                'ETag' => md5($timestamp . $id . rand()),
                'X-Accel-Expires' => '0',
            ];

            return response($pdf->output(), 200, $headers);

        } catch (\Exception $e) {
            Log::error('Error in cetakGabungV4: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat membuat PDF gabungan V4: ' . $e->getMessage());
        }
    }

    // Method V5 - PREMIUM DESIGN dengan Triple Border Gold-Navy-Gold
    public function cetakGabungV5($id)
    {
        try {
            $sertifikat = SertifikatPKL::findOrFail($id);
            $penilaians = Penilaian::where('id_sertifikat', $id)->get();

            // Jika yang akses adalah siswa, pastikan hanya bisa cetak miliknya sendiri
            $user = Auth::user();
            if ($user && $user->role === 'siswa') {
                $milikSiswa = (
                    (string)$sertifikat->id_sertifikat === (string)$user->id_sertifikat
                    || $sertifikat->nim_nis === $user->username
                    || (string)$sertifikat->created_id === (string)$user->id
                );
                if (!$milikSiswa) {
                    return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk mencetak sertifikat ini.');
                }
            }

            // CEK APAKAH ADA DATA PENILAIAN
            if ($penilaians->isEmpty()) {
                return redirect()->back()->with('error', 'Belum ada nilai untuk siswa ini. Harap isi nilai terlebih dahulu sebelum mencetak sertifikat.');
            }

            // GUNAKAN VIEW PREMIUM BARU
            $kabid = (object)[
                'nama' => $sertifikat->nama_kabid,
                'nip' => $sertifikat->nip_kabid
            ];
            $htmlContent = view('sertifikat.cetak_gabungan_premium', compact('sertifikat', 'penilaians', 'kabid'))->render();

            $pdf = Pdf::loadHTML($htmlContent);
            
            $pdf->setOptions([
                'isRemoteEnabled' => false,
                'isHtml5ParserEnabled' => true,
                'isPhpEnabled' => true,
                'chroot' => public_path(),
            ]);
            
            $paper = strtolower(request('paper', 'a4'));
            if (in_array($paper, ['f4', 'folio'])) {
                // Ukuran F4/Folio (215mm x 330mm dalam Landscape)
                $pdf->setPaper([0, 0, 609.45, 935.43], 'landscape');
            } elseif ($paper === 'legal') {
                $pdf->setPaper('legal', 'landscape');
            } elseif ($paper === 'a3') {
                $pdf->setPaper('a3', 'landscape');
            } else {
                $pdf->setPaper('A4', 'landscape');
            }

            $timestamp = microtime(true);
            $randomHash = md5(uniqid(rand(), true));
            $fileName = 'sertifikat_premium_' . str_replace(' ', '_', $sertifikat->nama_lengkap) . '_' . $timestamp . '.pdf';

            // ULTIMATE CACHE BUSTING
            $headers = [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . $fileName . '"',
                'Cache-Control' => 'no-store, no-cache, must-revalidate, post-check=0, pre-check=0, max-age=0, private',
                'Pragma' => 'no-cache',
                'Expires' => '0',
                'Last-Modified' => gmdate('D, d M Y H:i:s') . ' GMT',
                'ETag' => $randomHash,
                'X-Accel-Expires' => '0',
                'Vary' => '*',
            ];

            return response($pdf->output(), 200, $headers);

        } catch (\Exception $e) {
            Log::error('Error in cetakGabungV5: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat membuat PDF: ' . $e->getMessage());
        }
    }

    // Method V6 - NEW MODERN DESIGN (gradient purple, elegant border, modern typography)
    public function cetakGabungV6($id)
    {
        try {
            $sertifikat = SertifikatPKL::findOrFail($id);
            $penilaians = Penilaian::where('id_sertifikat', $id)->get();

            $kabid = (object)[
                'nama' => $sertifikat->nama_kabid,
                'nip' => $sertifikat->nip_kabid
            ];
            $htmlContent = view('sertifikat.cetak_gabungan', compact('sertifikat', 'penilaians', 'kabid'))->render();

            $pdf = Pdf::loadHTML($htmlContent);
            
            $pdf->setOptions([
                'isRemoteEnabled' => false,
                'isHtml5ParserEnabled' => true,
                'isPhpEnabled' => true,
                'chroot' => public_path(),
            ]);
            
            $pdf->setPaper('A4', 'landscape');

            $timestamp = microtime(true);
            $fileName = 'sertifikat_v6_MODERN_DESIGN_' . str_replace(' ', '_', $sertifikat->nama_lengkap) . '_' . $timestamp . '.pdf';

            // SUPER AGGRESSIVE CACHE BUSTING
            $headers = [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . $fileName . '"',
                'Cache-Control' => 'no-store, no-cache, must-revalidate, post-check=0, pre-check=0, max-age=0, private',
                'Pragma' => 'no-cache',
                'Expires' => '0',
                'Last-Modified' => gmdate('D, d M Y H:i:s') . ' GMT',
                'ETag' => md5($timestamp . $id . rand()),
                'X-Accel-Expires' => '0',
            ];

            return response($pdf->output(), 200, $headers);

        } catch (\Exception $e) {
            Log::error('Error in cetakGabungV6: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat membuat PDF gabungan V6: ' . $e->getMessage());
        }
    }

    // Method V7 - ENHANCED DESIGN (improved styling, same content, better visual)
    public function cetakGabungV7($id)
    {
        try {
            $sertifikat = SertifikatPKL::findOrFail($id);
            $penilaians = Penilaian::where('id_sertifikat', $id)->get();

            $kabid = (object)[
                'nama' => $sertifikat->nama_kabid,
                'nip' => $sertifikat->nip_kabid
            ];
            $htmlContent = view('sertifikat.cetak_gabungan', compact('sertifikat', 'penilaians', 'kabid'))->render();

            $pdf = Pdf::loadHTML($htmlContent);
            
            $pdf->setOptions([
                'isRemoteEnabled' => false,
                'isHtml5ParserEnabled' => true,
                'isPhpEnabled' => true,
                'chroot' => public_path(),
            ]);
            
            $pdf->setPaper('A4', 'landscape');

            $timestamp = microtime(true);
            $fileName = 'sertifikat_v7_ENHANCED_' . str_replace(' ', '_', $sertifikat->nama_lengkap) . '_' . $timestamp . '.pdf';

            // SUPER AGGRESSIVE CACHE BUSTING
            $headers = [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . $fileName . '"',
                'Cache-Control' => 'no-store, no-cache, must-revalidate, post-check=0, pre-check=0, max-age=0, private',
                'Pragma' => 'no-cache',
                'Expires' => '0',
                'Last-Modified' => gmdate('D, d M Y H:i:s') . ' GMT',
                'ETag' => md5($timestamp . $id . rand()),
                'X-Accel-Expires' => '0',
            ];

            return response($pdf->output(), 200, $headers);

        } catch (\Exception $e) {
            Log::error('Error in cetakGabungV7: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat membuat PDF gabungan V7: ' . $e->getMessage());
        }
    }

    // Method baru untuk serve PDF raw (untuk embed di iframe)



    public function statistikMagang()
    {
        $data = SertifikatPKL::select('bidang', DB::raw('count(*) as total'))
            ->where('sts_diambil', false) 
            ->whereDate('tgl_mulai', '<=', now())
            ->where('tgl_selesai', '>=', now())
            ->groupBy('bidang')
            ->get();

        return view('dashboard.statistik', compact('data'));
    }

    // Method tambahan untuk debugging
    public function debugData()
    {
        if (app()->environment('local')) {
            $total = SertifikatPKL::count();
            $byMentor = SertifikatPKL::where('created_id', Auth::id())->count();
            
            return response()->json([
                'total_data' => $total,
                'data_by_mentor' => $byMentor,
                'user_id' => Auth::id(),
                'sample_data' => SertifikatPKL::take(5)->get()
            ]);
        }
        
        return abort(404);
    }
    public function getSertifikatData(Request $request)
    {
        $draw = $request->input('draw');
        $start = $request->input('start');
        $length = $request->input('length');
        $searchValue = $request->input('search.value');
        $orderColumnIndex = $request->input('order.0.column');
        $orderDir = $request->input('order.0.dir');
        
        $columns = ['id_sertifikat', 'nama_lengkap', 'asal_sekolah', 'jurusan', 'bidang', 'tgl_mulai', 'tgl_selesai'];
        $orderColumn = $columns[$orderColumnIndex];

        // Query dasar untuk mengambil data sertifikat dari model SertifikatPKL
        $query = SertifikatPKL::select('id_sertifikat', 'nama_lengkap', 'asal_sekolah', 'jurusan', 'bidang', 'tgl_mulai', 'tgl_selesai');
        
        // Menghitung total records tanpa filter
        $totalRecords = $query->count();
        
        // Menerapkan filter pencarian
        if (!empty($searchValue)) {
            $query->where('nama_lengkap', 'LIKE', "%{$searchValue}%")
                  ->orWhere('asal_sekolah', 'LIKE', "%{$searchValue}%")
                  ->orWhere('bidang', 'LIKE', "%{$searchValue}%")
                  ->orWhere('jurusan', 'LIKE', "%{$searchValue}%")
                  ->orWhere('id_sertifikat', 'LIKE', "%{$searchValue}%");
        }
        
        // Menghitung total records setelah filter
        $recordsFiltered = $query->count();
        
        // Menerapkan pengurutan
        $query->orderBy($orderColumn, $orderDir);
        
        // Menerapkan batasan untuk pagination
        $certificates = $query->skip($start)->take($length)->get();

        // Mengambil data presensi dari tabel attendances
        $attendanceData = Attendance::select('id_sertifikat', 'statua as status_kehadiran', DB::raw('count(*) as count'))
                                    ->whereIn('id_sertifikat', $certificates->pluck('id_sertifikat'))
                                    ->groupBy('id_sertifikat', 'statua')
                                    ->get()
                                    ->groupBy('id_sertifikat');

        // Memformat data untuk DataTables
        $formattedData = $certificates->map(function ($certificate) use ($attendanceData) {
            $hadir = $attendanceData->get($certificate->id_sertifikat)?->where('status_kehadiran', 'Hadir')->first()?->count ?? 0;
            $izin = $attendanceData->get($certificate->id_sertifikat)?->where('status_kehadiran', 'Izin')->first()?->count ?? 0;
            $sakit = $attendanceData->get($certificate->id_sertifikat)?->where('status_kehadiran', 'Sakit')->first()?->count ?? 0;

            // Menambahkan keterangan presensi ke objek sertifikat
            $certificate->presensi = "Hadir: {$hadir}, Izin: {$izin}, Sakit: {$sakit}";
            
            return $certificate;
        });

        // Mengembalikan respons JSON
        return response()->json([
            'draw' => intval($draw),
            'recordsTotal' => intval($totalRecords),
            'recordsFiltered' => intval($recordsFiltered),
            'data' => $formattedData,
        ]);
    }
    public function detail($id)
    {
        $sertifikat = SertifikatPKL::with('portofolio')->findOrFail($id);
        return view('siswa.sertifikat.detail', compact('sertifikat'));
    }

    // Method V8 - LATEST FIX with ULTIMATE cache busting
    public function cetakGabungV8($id)
    {
        try {
            // Eager load mentor (from created_id) as requested
            $sertifikat = SertifikatPKL::with('mentor')->findOrFail($id);
            $penilaians = Penilaian::where('id_sertifikat', $id)->get();

            // Set Kepala Bidang dynamically based on student's 'bidang'
            $kabid = (object)[
                'nama' => '',
                'nip' => ''
            ];
            
            if ($sertifikat->bidang == 'Bidang Informatika') {
                $kabid->nama = 'M HAYKEL RAKHMATTULLOH TAUFIQ, S.Kom., M.M.';
                $kabid->nip = '198503232010011028';
            } elseif ($sertifikat->bidang == 'Bidang Statistik dan Persandian') {
                // Example data if needed
                $kabid->nama = 'KEPALA BIDANG STATISTIK, ...';
                $kabid->nip = '...';
            } elseif ($sertifikat->bidang == 'Bidang Informasi dan Komunikasi Publik') {
                $kabid->nama = 'KEPALA BIDANG IKP, ...';
                $kabid->nip = '...';
            }
            
            // Fallback to nm_kadis if not specifically defined above
            if (empty($kabid->nama) && !empty($sertifikat->nm_kadis)) {
                $kabid->nama = $sertifikat->nm_kadis;
                $kabid->nip = $sertifikat->nip_kadis;
            }

            // Clear view cache before rendering
            \Artisan::call('view:clear');

            $htmlContent = view('sertifikat.cetak_gabungan', compact('sertifikat', 'penilaians', 'kabid'))->render();

            $pdf = Pdf::loadHTML($htmlContent);
            
            $pdf->setOptions([
                'isRemoteEnabled' => false,
                'isHtml5ParserEnabled' => true,
                'isPhpEnabled' => true,
                'chroot' => public_path(),
            ]);
            
            $pdf->setPaper('A4', 'landscape');

            $timestamp = microtime(true);
            $randomHash = md5(uniqid(rand(), true));
            $fileName = 'sertifikat_v8_LATEST_' . str_replace(' ', '_', $sertifikat->nama_lengkap) . '_' . $timestamp . '.pdf';

            // ULTIMATE CACHE BUSTING - Force browser to never cache
            $headers = [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . $fileName . '"',
                'Cache-Control' => 'no-store, no-cache, must-revalidate, post-check=0, pre-check=0, max-age=0, private',
                'Pragma' => 'no-cache',
                'Expires' => '0',
                'Last-Modified' => gmdate('D, d M Y H:i:s') . ' GMT',
                'ETag' => $randomHash,
                'X-Accel-Expires' => '0',
                'X-Cache-Control' => 'no-cache',
                'Vary' => '*',
            ];

            return response($pdf->output(), 200, $headers);

        } catch (\Exception $e) {
            Log::error('Error in cetakGabungV8: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat membuat PDF gabungan V8: ' . $e->getMessage());
        }
    }

    public function exportData()
    {
        $userId = Auth::id();
        $siswa = SertifikatPKL::where('created_id', $userId)->orderBy('id_sertifikat', 'desc')->get();

        $filename = "data_siswa_magang.csv";
        $handle = fopen('php://memory', 'w');
        
        // Header CSV
        fputcsv($handle, ['No', 'Nama Lengkap', 'NIM/NIS', 'Asal Sekolah', 'Jurusan', 'Bidang', 'Tanggal Mulai', 'Tanggal Selesai', 'Status Diambil']);

        $no = 1;
        foreach ($siswa as $s) {
            fputcsv($handle, [
                $no++,
                $s->nama_lengkap,
                $s->nim_nis,
                $s->asal_sekolah,
                $s->jurusan,
                $s->bidang,
                $s->tgl_mulai,
                $s->tgl_selesai,
                $s->sts_diambil ? 'Sudah' : 'Belum'
            ]);
        }

        fseek($handle, 0);
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        return response()->stream(function () use ($handle) {
            fpassthru($handle);
        }, 200, $headers);
    }

    /**
     * Method untuk stream PDF raw (untuk embed di iframe)
     * Fitur PDF Viewer
     */
    public function cetakGabungRaw($id_sertifikat)
    {
        try {
            // Allow mentor and sekretariat to access
            $sertifikat = SertifikatPKL::with('mentor')->findOrFail($id_sertifikat);
            $this->authorize('print', $sertifikat);

            // Mengambil data sertifikat dan penilaian
            $penilaians = Penilaian::where('id_sertifikat', $id_sertifikat)->get();

            // Set Kepala Bidang dynamically based on student's 'bidang'
            $kabid = (object)[
                'nama' => '',
                'nip' => ''
            ];
            
            if ($sertifikat->bidang == 'Bidang Informatika') {
                $kabid->nama = 'M HAYKEL RAKHMATTULLOH TAUFIQ, S.Kom., M.M.';
                $kabid->nip = '198503232010011028';
            } elseif ($sertifikat->bidang == 'Bidang Statistik dan Persandian') {
                $kabid->nama = 'KEPALA BIDANG STATISTIK, ...';
                $kabid->nip = '...';
            } elseif ($sertifikat->bidang == 'Bidang Informasi dan Komunikasi Publik') {
                $kabid->nama = 'KEPALA BIDANG IKP, ...';
                $kabid->nip = '...';
            }
            
            if (empty($kabid->nama) && !empty($sertifikat->nm_kadis)) {
                $kabid->nama = $sertifikat->nm_kadis;
                $kabid->nip = $sertifikat->nip_kadis;
            }

            // Render view 'cetak_gabungan' menjadi HTML
            $htmlContent = view('sertifikat.cetak_gabungan', compact('sertifikat', 'penilaians', 'kabid'))->render();

            // Buat instance PDF dari HTML menggunakan facade
            $pdf = Pdf::loadHTML($htmlContent);
            
            // Mengatur opsi Dompdf untuk mendukung gambar eksternal (dari URL)
            $pdf->setOptions([
                'isRemoteEnabled' => false,
                'isHtml5ParserEnabled' => true,
                'isPhpEnabled' => true,
                'chroot' => public_path(),
            ]);
            
            // Mengatur ukuran kertas dan orientasi
            $pdf->setPaper('A4', 'portrait');

            // Tampilkan PDF inline di browser tanpa trigger download
            return response($pdf->output(), 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline',
            ]);

        } catch (\Exception $e) {
            Log::error('Error in cetakGabungRaw: ' . $e->getMessage());
            return abort(500, 'Error generating PDF preview');
        }
    }

    /**
     * Method untuk download PDF
     * Fitur PDF Viewer
     */
    public function cetakGabungDownload($id_sertifikat)
    {
        try {
            // Allow mentor and sekretariat to access
            $sertifikat = SertifikatPKL::with('mentor')->findOrFail($id_sertifikat);
            $this->authorize('print', $sertifikat);

            // Mengambil data sertifikat dan penilaian
            $penilaians = Penilaian::where('id_sertifikat', $id_sertifikat)->get();

            // Set Kepala Bidang dynamically based on student's 'bidang'
            $kabid = (object)[
                'nama' => '',
                'nip' => ''
            ];
            
            if ($sertifikat->bidang == 'Bidang Informatika') {
                $kabid->nama = 'M HAYKEL RAKHMATTULLOH TAUFIQ, S.Kom., M.M.';
                $kabid->nip = '198503232010011028';
            } elseif ($sertifikat->bidang == 'Bidang Statistik dan Persandian') {
                $kabid->nama = 'KEPALA BIDANG STATISTIK, ...';
                $kabid->nip = '...';
            } elseif ($sertifikat->bidang == 'Bidang Informasi dan Komunikasi Publik') {
                $kabid->nama = 'KEPALA BIDANG IKP, ...';
                $kabid->nip = '...';
            }
            
            if (empty($kabid->nama) && !empty($sertifikat->nm_kadis)) {
                $kabid->nama = $sertifikat->nm_kadis;
                $kabid->nip = $sertifikat->nip_kadis;
            }

            // Render view 'cetak_gabungan' menjadi HTML
            $htmlContent = view('sertifikat.cetak_gabungan', compact('sertifikat', 'penilaians', 'kabid'))->render();

            // Buat instance PDF dari HTML menggunakan facade
            $pdf = Pdf::loadHTML($htmlContent);
            
            // Mengatur opsi Dompdf
            $pdf->setOptions([
                'isRemoteEnabled' => false,
                'isHtml5ParserEnabled' => true,
                'isPhpEnabled' => true,
                'chroot' => public_path(),
            ]);
            
            // Mengatur ukuran kertas dan orientasi
            $pdf->setPaper('A4', 'portrait');

            // Menentukan nama file PDF yang akan diunduh
            $fileName = 'sertifikat_gabungan_' . str_replace(' ', '_', $sertifikat->nama_lengkap) . '_' . date('Y-m-d') . '.pdf';

            // Force download PDF
            return $pdf->download($fileName);

        } catch (\Exception $e) {
            Log::error('Error in cetakGabungDownload: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengunduh PDF: ' . $e->getMessage());
        }
    }

    /**
     * Tampilkan halaman PDF Viewer
     * Method baru untuk fitur PDF Viewer
     */
    public function showPdfViewer($id_sertifikat)
    {
        try {
            // Allow mentor and sekretariat to view
            $sertifikat = SertifikatPKL::findOrFail($id_sertifikat);
            $this->authorize('view', $sertifikat);

            $sertifikat = SertifikatPKL::findOrFail($id_sertifikat);
            return view('sertifikat.pdf_viewer', compact('id_sertifikat', 'sertifikat'));
        } catch (\Exception $e) {
            Log::error('Error in showPdfViewer: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Sertifikat tidak ditemukan.');
        }
    }
}
