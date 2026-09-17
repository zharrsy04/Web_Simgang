<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Announcement;
use App\Models\SertifikatPKL;
use App\Models\MentorSiswa;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use App\Models\Task;


class DashboardController extends Controller
{
    public function index()
    {
        // Redirect ke dashboard sesuai role karena view dashboard.index tidak ada
        $user = Auth::user();
        
        if ($user->role === 'mentor') {
            return redirect()->route('mentor.dashboard');
        } elseif ($user->role === 'siswa') {
            return redirect()->route('siswa.dashboard');
        } elseif ($user->role === 'sekretariat') {
            return redirect()->route('sekretariat.dashboard');
        }
        
        // Fallback jika role tidak dikenali
        return redirect()->route('login')->with('error', 'Role tidak dikenali');
    }

    public function siswaAktif(Request $request)
    {
        \App\Models\Instansi::syncInstansiIds();

        $userId = Auth::id();
        $user = Auth::user();

        $search = $request->input('search');
        $today = Carbon::now()->format('Y-m-d');

        // Query data siswa aktif
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

        // Urutkan berdasarkan tanggal mulai magang (ascending)
        // Jika di masa depan ada kolom apjab, tambahkan: ->orderBy('apjab', 'asc')
        $query->orderBy('tgl_mulai', 'asc');

        $siswaAktif = $query->paginate(10);

        return view('dashboard.siswaaktif', compact('siswaAktif'));
    }

    public function menungguMagang(Request $request)
    {
        $userId = Auth::id();
        $user = Auth::user();

        $search = $request->input('search');
        $today = Carbon::now()->format('Y-m-d');

        $query = SertifikatPKL::with('instansi')->whereDate('tgl_mulai', '>', $today);

        // Filter berdasarkan role: Mentor hanya lihat bidangnya, Sekretariat lihat semua
        if ($user->role === 'mentor' && $user->bidang) {
            $query->where('bidang', $user->bidang);
        }

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%$search%")
                  ->orWhere('asal_sekolah', 'like', "%$search%")
                  ->orWhere('bidang', 'like', "%$search%");
            });
        }

        $siswaMenunggu = $query->paginate(10);

        return view('dashboard.menunggumagang', compact('siswaMenunggu'));
    }

    public function sekolah(Request $request)
    {
        $search = $request->input('search');

        // Query data sekolah/universitas persis urutan web acuan (dimulai dari -, POLITEKNIK NEGERI CILACAP, SMK Bina Bhakti, dst.)
        $query = SertifikatPKL::select(
                'asal_sekolah',
                DB::raw('COUNT(id_sertifikat) as jumlah_siswa')
            )
            ->whereNotNull('asal_sekolah')
            ->where('asal_sekolah', '!=', '')
            ->groupBy('asal_sekolah')
            ->orderBy('asal_sekolah', 'asc');

        if ($search) {
            $query->where('asal_sekolah', 'like', "%{$search}%");
        }

        // Paginasi maksimal 10 baris per halaman
        $sekolahList = $query->paginate(10);
        $sekolahList->appends($request->query());

        // Load detail siswa dan logo untuk setiap sekolah
        foreach ($sekolahList as $sekolah) {
            $sekolah->logo_url = $this->resolveSchoolLogo($sekolah->asal_sekolah);

            $sekolah->siswa = SertifikatPKL::where('asal_sekolah', $sekolah->asal_sekolah)
                ->select('id_sertifikat', 'nama_lengkap', 'nim_nis', 'bidang', 'tgl_mulai', 'tgl_selesai')
                ->orderBy('nama_lengkap')
                ->get();
        }

        return view('dashboard.sekolah', compact('sekolahList', 'search'));
    }

    /**
     * Helper untuk menentukan URL logo sekolah/universitas
     */
    private function resolveSchoolLogo($schoolName)
    {
        if (empty($schoolName)) return null;

        $normalized = strtolower(trim($schoolName));

        // 1. Cek instansi terdaftar di database jika memiliki upload logo di storage
        $instansi = \App\Models\Instansi::findMatchingInstansi($schoolName);
        if ($instansi && $instansi->getLogoUrl()) {
            return $instansi->getLogoUrl();
        }

        // 2. Mapping logo statis lokal yang tersedia di public/images/
        $logoMap = [
            'pnc' => 'images/logos/pnc.png',
            'politeknik negeri cilacap' => 'images/logos/pnc.png',
            'smk telkom' => 'images/logos/smktelkom.png',
            'telkom' => 'images/logos/univtelkom.png',
            'smk negeri 1 cilacap' => 'images/logos/smkn1cilacap.jpg',
            'smkn 1 cilacap' => 'images/logos/smkn1cilacap.jpg',
            'smk n 1 cilacap' => 'images/logos/smkn1cilacap.jpg',
            'smk n 1 kawunganten' => 'images/logos/smkn1kawunganten.png',
            'smk n 1 wanareja' => 'images/logos/smkn1wanareja.jpg',
            'smk muhammadiyah 1 ajibarang' => 'images/logos/smkmuhi1ajibarang.jpg',
            'unsoed' => 'images/logos/unsoed.png',
            'jenderal soedirman' => 'images/logos/unsoed.png',
            'soedirman' => 'images/logos/unsoed.png',
            'tidar' => 'images/logos/untidar.png',
            'muhammadiyah yogyakarta' => 'images/logos/umy.webp',
            'negeri semarang' => 'images/logo unnes.png',
            'unnes' => 'images/logo unnes.png',
        ];

        foreach ($logoMap as $pattern => $relPath) {
            if (str_contains($normalized, $pattern)) {
                if (file_exists(public_path($relPath))) {
                    return asset($relPath);
                }
            }
        }

        return null;
    }


    public function siswaLulus(Request $request)
    {
        $userId = Auth::id();
        $user = Auth::user();

        $search = $request->input('search');
        $today = Carbon::now()->format('Y-m-d');

        // Query data siswa lulus
        $query = SertifikatPKL::with('instansi')->where('sts_diambil', true)
            ->whereDate('tgl_selesai', '<', $today);

        // Mentor dan sekretariat melihat semua data siswa lulus (sesuai web acuan)

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%$search%")
                  ->orWhere('asal_sekolah', 'like', "%$search%")
                  ->orWhere('bidang', 'like', "%$search%");
            });
        }

        $siswaLulus = $query->orderBy('id_sertifikat', 'asc')->paginate(10);

        return view('dashboard.siswalulus', compact('siswaLulus'));
    }

    public function statistikMagang(Request $request)
    {
        $userId = Auth::id();
        $user = Auth::user();

        $search = $request->input('search');
        $today = Carbon::now()->format('Y-m-d');

        $query = SertifikatPKL::select('bidang', DB::raw('count(*) as total'))
            ->where('sts_diambil', false)
            ->whereDate('tgl_mulai', '<=', $today)
            ->whereDate('tgl_selesai', '>=', $today)
            ->groupBy('bidang');

        // Filter berdasarkan role: Mentor hanya lihat bidangnya, Sekretariat lihat semua
        if ($user->role === 'mentor' && $user->bidang) {
            $query->where('bidang', $user->bidang);
        }

        if ($search) {
            $query->where('bidang', 'like', "%$search%");
        }

        $data = $query->paginate(10);

        foreach ($data as $bidang) {
            $siswaQuery = SertifikatPKL::where('bidang', $bidang->bidang)
                ->where('sts_diambil', false)
                ->whereDate('tgl_mulai', '<=', $today)
                ->whereDate('tgl_selesai', '>=', $today)
                ->select('id_sertifikat', 'nama_lengkap', 'nim_nis', 'bidang', 'tgl_mulai', 'tgl_selesai');

            // Filter berdasarkan role untuk detail siswa juga
            if ($user->role === 'mentor' && $user->bidang) {
                $siswaQuery->where('bidang', $user->bidang);
            }

            $bidang->siswa = $siswaQuery->orderBy('nama_lengkap')->get();
        }

        return view('dashboard.statistik', compact('data'));
    }

    //     public function statistikMagang(Request $request)
    // {
    //     $search = $request->input('search');
    //     $today = Carbon::now();

    //     // Query awal ambil semua data siswa
    //     $baseQuery = SertifikatPKL::query(); // <-- tidak pakai created_id

    //     // Filter aktif, selesai, atau semua
    //     $filter = $request->get('filter', 'all'); // all, active, completed

    //     switch ($filter) {
    //         case 'active':
    //             $baseQuery->where('sts_diambil', false)
    //                     ->where('tgl_mulai', '<=', $today)
    //                     ->where('tgl_selesai', '>=', $today);
    //             break;

    //         case 'completed':
    //             $baseQuery->where('tgl_selesai', '<', $today);
    //             break;

    //         default:
    //             // tampilkan semua data
    //             break;
    //     }

    //     // Hitung jumlah siswa per bidang
    //     $query = (clone $baseQuery)
    //         ->select('bidang', DB::raw('COUNT(*) as total'))
    //         ->groupBy('bidang');

    //     if ($search) {
    //         $query->where('bidang', 'like', "%$search%");
    //     }

    //     $data = $query->paginate(10);
        
    //     // Ambil detail siswa di setiap bidang
    //     foreach ($data as $bidang) {
    //         $bidang->siswa = (clone $baseQuery)
    //             ->where('bidang', $bidang->bidang)
    //             ->select('id_sertifikat', 'nama_lengkap', 'nim_nis', 'bidang', 'tgl_mulai', 'tgl_selesai')
    //             ->orderBy('nama_lengkap')
    //             ->get();
    //     }
    //     return view('dashboard.statistik', compact('data', 'filter'));
    // }


    // public function full()
    // {
    //     return view('dashboard.calendar.full');
    // }

    // // Fungsi untuk menampilkan halaman pengumuman
    // public function pengumuman()
    // {
    //     $userId = Auth::id();
        
    //     // Ambil semua pengumuman berdasarkan user yang sedang login
    //     $announcements = Announcement::where('created_by', $userId) 
    //         ->orderByDesc('created_at')
    //         ->get();

    //     return view('dashboard.pengumuman', compact('announcements'));
    // }

    // Fungsi untuk mengambil data pengumuman via AJAX
    public function getAnnouncements()
    {
        $user = Auth::user();
        
        try {
            // Semua role melihat semua pengumuman yang sudah dipublikasikan
            // Mentor dan sekretariat bisa edit/hapus SEMUA pengumuman, siswa hanya baca
            $announcements = Announcement::with('creator:id,nama,username')
                ->where('published_at', '<=', Carbon::now())
                ->orderByDesc('published_at')
                ->orderByDesc('created_at')
                ->get()
                ->map(function($announcement) use ($user) {
                    // Mentor dan Sekretariat bisa edit/hapus SEMUA pengumuman
                    $announcement->can_edit = ($user->role === 'mentor' || $user->role === 'sekretariat');
                    // Tambahkan nama pembuat
                    $announcement->creator_name = $announcement->creator 
                        ? ($announcement->creator->nama ?? $announcement->creator->username) 
                        : 'Admin';
                    return $announcement;
                });

            return response()->json([
                'success' => true,
                'data' => $announcements
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data pengumuman.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Fungsi untuk menyimpan pengumuman (sudah ada, diperbaiki sedikit)
    public function simpanpengumuman(Request $request)
    {
        try {
            // Validasi input data
            $validatedData = $request->validate([
                'title' => 'required|string|max:255',
                'type' => 'required|string|max:50',
                'content' => 'required|string',
                'published_at' => 'nullable|date',
            ]);

            // Ambil ID user dari Auth
            $userId = Auth::id();
            if ($userId) {
                $validatedData['created_by'] = $userId;
            }

            // Set published_at jika tidak ada
            if (!isset($validatedData['published_at'])) {
                $validatedData['published_at'] = Carbon::now()->format('Y-m-d');
            }

            // Buat record pengumuman baru
            $announcement = Announcement::create($validatedData);

            // Berikan respons sukses ke client
            return response()->json([
                'success' => true,
                'respon' => 200,
                'message' => 'Pengumuman berhasil disimpan!',
                'data' => $announcement
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Tangani error validasi
            return response()->json([
                'success' => false,
                'respon' => 422,
                'message' => 'Data input tidak valid.',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            // Tangani error umum lainnya
            \Log::error('Gagal menyimpan pengumuman: ' . $e->getMessage(), ['exception' => $e]);
            return response()->json([
                'success' => false,
                'respon' => 500,
                'message' => 'Terjadi kesalahan pada server saat menyimpan pengumuman.',
                'error_detail' => $e->getMessage()
            ], 500);
        }
    }

    // Fungsi untuk mengupdate pengumuman
    public function updatepengumuman(Request $request, $id)
    {
        try {
            $userId = Auth::id();
            $user = Auth::user();
            
            // Log incoming request
            \Log::info('Update pengumuman request', [
                'id' => $id,
                'user_id' => $userId,
                'user_role' => $user->role,
                'request_data' => $request->all(),
                'method' => $request->method()
            ]);
            
            // Validasi input data
            $validatedData = $request->validate([
                'title' => 'required|string|max:255',
                'type' => 'required|string|max:50',
                'content' => 'required|string',
                'published_at' => 'nullable|date',
            ]);

            // Kolom published_at di DB bersifat NOT NULL — jika user tidak mengisi,
            // gunakan tanggal sekarang sebagai nilai default (bukan null)
            if (empty($validatedData['published_at'])) {
                $validatedData['published_at'] = now()->toDateString();
            }

            \Log::info('Validated data for update', [
                'id' => $id,
                'validated_data' => $validatedData
            ]);

            // Mentor dan Sekretariat bisa edit SEMUA pengumuman (tidak cek created_by)
            if (!in_array($user->role, ['mentor', 'sekretariat'])) {
                \Log::warning('Unauthorized role mencoba update pengumuman', [
                    'id' => $id,
                    'user_id' => $userId,
                    'user_role' => $user->role
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki akses untuk mengupdate pengumuman.'
                ], 403);
            }

            // Cari pengumuman berdasarkan ID saja (tanpa filter created_by)
            $announcement = Announcement::find($id);

            if (!$announcement) {
                \Log::warning('Pengumuman tidak ditemukan', [
                    'id' => $id,
                    'user_id' => $userId
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Pengumuman tidak ditemukan.'
                ], 404);
            }

            // Update data pengumuman
            $announcement->update($validatedData);
            
            \Log::info('Pengumuman berhasil diupdate', [
                'id' => $announcement->id,
                'title' => $announcement->title,
                'user_id' => $userId,
                'original_creator' => $announcement->created_by
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Pengumuman berhasil diperbarui!',
                'data' => $announcement,
                'id' => $announcement->id,
                'updated_at' => $announcement->updated_at
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::warning('Validation error saat update pengumuman', [
                'id' => $id,
                'errors' => $e->errors()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Data input tidak valid.',
                'errors' => $e->errors()
            ], 422);

        } catch (\Illuminate\Database\QueryException $e) {
            // Error database (constraint, koneksi, dll) — log detail tapi tidak bocor ke user
            \Log::error('Database error saat update pengumuman', [
                'id' => $id,
                'user_id' => $userId ?? null,
                'sql_error' => $e->getMessage(),
                'sql_state' => $e->getCode(),
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan perubahan karena kendala database. Silakan coba lagi atau hubungi administrator.'
            ], 500);

        } catch (\Exception $e) {
            \Log::error('Gagal mengupdate pengumuman: ' . $e->getMessage(), [
                'id' => $id,
                'user_id' => $userId ?? null,
                'exception_class' => get_class($e),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan pada server. Silakan coba lagi.'
            ], 500);
        }
    }

    // Fungsi untuk menghapus pengumuman
    public function deletepengumuman($id)
    {
        try {
            $userId = Auth::id();
            $user = Auth::user();
            
            // Mentor dan Sekretariat bisa hapus SEMUA pengumuman (tidak cek created_by)
            if (!in_array($user->role, ['mentor', 'sekretariat'])) {
                \Log::warning('Unauthorized role mencoba hapus pengumuman', [
                    'id' => $id,
                    'user_id' => $userId,
                    'user_role' => $user->role
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki akses untuk menghapus pengumuman.'
                ], 403);
            }
            
            // Cari pengumuman berdasarkan ID saja (tanpa filter created_by)
            $announcement = Announcement::find($id);

            if (!$announcement) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pengumuman tidak ditemukan.'
                ], 404);
            }

            // Simpan info untuk log
            $announcementTitle = $announcement->title;
            $originalCreator = $announcement->created_by;

            // Hapus pengumuman
            $announcement->delete();

            \Log::info('Pengumuman berhasil dihapus', [
                'id' => $id,
                'title' => $announcementTitle,
                'original_creator' => $originalCreator,
                'deleted_by' => $userId
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Pengumuman berhasil dihapus!'
            ], 200);

        } catch (\Exception $e) {
            \Log::error('Gagal menghapus pengumuman: ' . $e->getMessage(), ['exception' => $e]);
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan pada server saat menghapus pengumuman.',
                'error_detail' => $e->getMessage()
            ], 500);
        }
    }
    public function getInternshipStudentsByDate(Request $request)
    {
        // 1. Validasi tanggal yang masuk
        $validator = Validator::make($request->all(), [
            'date' => 'required|date_format:Y-m-d',
            'search' => 'nullable|string|max:255', // Tambahkan validasi untuk parameter pencarian
        ]);

        // 2. Mengembalikan respons JSON dengan error jika validasi gagal.
        if ($validator->fails()) {
            \Log::error('Validasi gagal untuk getInternshipStudentsByDate: ' . json_encode($validator->errors()));
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal. Format tanggal tidak valid atau parameter tidak lengkap.',
                'errors' => $validator->errors()
            ], 422); // 422 Unprocessable Entity
        }

        try {
            $selectedDate = Carbon::parse($request->date)->toDateString(); // Konversi tanggal ke format 'Y-m-d'
            $searchQuery = $request->search; // Ambil query pencarian

            // Gunakan kriteria yang sama dengan kalender: peserta yang sertifikatnya
            // belum diambil dan periodenya mencakup tanggal yang dipilih. Jangan pakai
            // scope `aktif()` di sini karena scope itu juga membatasi terhadap tanggal
            // hari ini, sehingga detail untuk tanggal lampau bisa kosong walaupun
            // kalender menandainya memiliki peserta.
            $students = SertifikatPKL::where('tgl_mulai', '<=', $selectedDate)
                                      ->where('tgl_selesai', '>=', $selectedDate)
                                      ->where('sts_diambil', false)
                                      ->when($searchQuery, function ($query, $search) {
                                          // Terapkan scope pencarian jika ada query pencarian
                                          return $query->search($search);
                                      })
                                      ->orderBy('nama_lengkap') // Urutkan berdasarkan nama untuk tampilan
                                      ->get();

            // Memformat data untuk respons
            $formattedStudents = $students->map(function ($student) {
                return [
                    'id_sertifikat' => $student->id_sertifikat,
                    'nama_lengkap' => $student->nama_lengkap,
                    'nim_nis' => $student->nim_nis,
                    'asal_sekolah' => $student->asal_sekolah,
                    'jurusan' => $student->jurusan,
                    'bidang' => $student->bidang,
                    'tgl_mulai' => $student->tgl_mulai->format('Y-m-d'), // Pastikan format Y-m-d
                    'tgl_selesai' => $student->tgl_selesai->format('Y-m-d'), // Pastikan format Y-m-d
                    'status_magang' => $student->status, // Menggunakan accessor 'status' dari model
                ];
            });

            if ($formattedStudents->isEmpty()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Tidak ada siswa magang aktif ditemukan pada tanggal ' . Carbon::parse($selectedDate)->locale('id')->translatedFormat('d F Y') . '.',
                    'data' => []
                ], 200);
            }

            return response()->json([
                'success' => true,
                'message' => 'Daftar siswa magang aktif untuk tanggal ' . Carbon::parse($selectedDate)->locale('id')->translatedFormat('d F Y') . ' berhasil diambil.',
                'data' => $formattedStudents
            ], 200);

        } catch (\Exception $e) {
            \Log::error('Gagal mengambil daftar siswa magang aktif: ' . $e->getMessage(), ['exception' => $e, 'request_date' => $request->date, 'search_query' => $request->search]);
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data siswa magang aktif: ' . $e->getMessage()
            ], 500); // 500 Internal Server Error
        }
    }

    /**
     * Mengirim pesan tugas ke siswa.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function assignTask(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'sertifikat_pkl_id' => 'required|exists:tbl_sertifikat_pkl,id_sertifikat',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date_format:Y-m-d',
            'status' => 'nullable|string|in:pending,completed,in progress',
        ]);

        if ($validator->fails()) {
            \Log::warning('Validasi gagal saat menetapkan tugas', [
                'errors' => $validator->errors(),
                'mentor_id' => Auth::id(),
                'request_data' => $request->all()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal: ' . $validator->errors()->first(),
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $assignedBy = Auth::check() ? Auth::id() : null; // ID pengguna yang sedang login

            // Verify siswa exists dan belongs to mentor
            $sertifikat = SertifikatPKL::findOrFail($request->sertifikat_pkl_id);

            // Buat task
            $task = Task::create([
                'sertifikat_pkl_id' => $request->sertifikat_pkl_id,
                'assigned_by' => $assignedBy,
                'title' => $request->title,
                'description' => $request->description,
                'due_date' => $request->due_date,
                'status' => $request->status ?? 'pending',
                'catatan_izin' => $request->catatan_izin,
            ]);

            // Log dengan context lengkap
            \Log::info('Tugas berhasil ditetapkan', [
                'task_id' => $task->id,
                'sertifikat_pkl_id' => $request->sertifikat_pkl_id,
                'siswa_nama' => $sertifikat->nama_lengkap,
                'task_title' => $task->title,
                'assigned_by' => $assignedBy,
                'mentor_role' => Auth::user()->role ?? 'unknown'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Tugas berhasil ditetapkan!',
                'data' => [
                    'task_id' => $task->id,
                    'task_title' => $task->title,
                    'created_at' => $task->created_at->format('d M Y H:i')
                ]
            ], 201); // 201 Created

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            \Log::warning('Siswa tidak ditemukan saat menetapkan tugas', [
                'sertifikat_pkl_id' => $request->sertifikat_pkl_id,
                'mentor_id' => Auth::id()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Siswa tidak ditemukan. Periksa kembali data.'
            ], 404);

        } catch (\Exception $e) {
            \Log::error('Gagal menetapkan tugas', [
                'error_message' => $e->getMessage(),
                'error_file' => $e->getFile(),
                'error_line' => $e->getLine(),
                'request_data' => $request->all(),
                'assigned_by' => $assignedBy,
                'exception_class' => get_class($e)
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menetapkan tugas. Hubungi administrator jika masalah berlanjut.'
            ], 500);
        }
    }

    /**
     * Get all students for cross-mentor task assignment
     */
    public function getAllStudents()
    {
        try {
            $students = SertifikatPKL::select('id_sertifikat', 'nama_lengkap', 'nim_nis', 'bidang', 'asal_sekolah')
                ->orderBy('nama_lengkap')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $students
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching all students: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat daftar siswa'
            ], 500);
        }
    }

    /**
     * Get tasks untuk siswa tertentu (API endpoint)
     * Used untuk display tugas yang sudah diberikan ke siswa
     */
    public function getTasksForStudent($sertifikatPklId)
    {
        try {
            $tasks = Task::where('sertifikat_pkl_id', $sertifikatPklId)
                ->with('assignedByUser')
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $tasks->map(function($task) {
                    return [
                        'id' => $task->id,
                        'title' => $task->title,
                        'description' => $task->description,
                        'status' => $task->status,
                        'due_date' => $task->due_date ? $task->due_date->format('d M Y') : '-',
                        'due_date_raw' => $task->due_date,
                        'created_at' => $task->created_at->format('d M Y H:i'),
                        'assigned_by' => $task->assignedByUser->nama ?? $task->assignedByUser->username ?? 'Unknown',
                        'is_overdue' => $task->due_date && now()->gt($task->due_date) && $task->status !== 'completed'
                    ];
                })
            ]);
        } catch (\Exception $e) {
            \Log::error('Error fetching tasks for student', [
                'sertifikat_pkl_id' => $sertifikatPklId,
                'error' => $e->getMessage()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat daftar tugas'
            ], 500);
        }
    }

    /**
     * Get tasks dengan statistik untuk siswa (summary view)
     */
    public function getTasksSummary($sertifikatPklId)
    {
        try {
            $tasks = Task::where('sertifikat_pkl_id', $sertifikatPklId)->get();

            $summary = [
                'total' => $tasks->count(),
                'pending' => $tasks->where('status', 'pending')->count(),
                'in_progress' => $tasks->where('status', 'in progress')->count(),
                'completed' => $tasks->where('status', 'completed')->count(),
                'overdue' => $tasks->where('status', 'pending')
                    ->filter(function($task) {
                        return $task->due_date && now()->gt($task->due_date);
                    })->count()
            ];

            return response()->json([
                'success' => true,
                'data' => $summary
            ]);
        } catch (\Exception $e) {
            \Log::error('Error fetching tasks summary', [
                'sertifikat_pkl_id' => $sertifikatPklId,
                'error' => $e->getMessage()
            ]);
            return response()->json([
                'success' => false,
                'data' => [
                    'total' => 0,
                    'pending' => 0,
                    'in_progress' => 0,
                    'completed' => 0,
                    'overdue' => 0
                ]
            ]);
        }
    }
}
