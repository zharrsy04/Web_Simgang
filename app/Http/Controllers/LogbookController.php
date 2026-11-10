<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Logbook;
use App\Models\SertifikatPKL;
use App\Models\MentorSiswa;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class LogbookController extends Controller
{
    /**
     * Dapatkan data mentor pembimbing untuk siswa tertentu
     */
    protected function getMentorForSiswa($siswa)
    {
        if (!$siswa) return null;

        $mentorSiswa = MentorSiswa::where('siswa_id', $siswa->id_sertifikat)
            ->where('is_active', true)
            ->first();

        if ($mentorSiswa && $mentorSiswa->mentor_id) {
            return User::find($mentorSiswa->mentor_id);
        }

        if (!empty($siswa->nm_pembimbing)) {
            $mentorUser = User::where('role', 'mentor')->where(function($q) use ($siswa) {
                $q->where('nama', $siswa->nm_pembimbing);
                if (stripos($siswa->nm_pembimbing, 'Slamet Arifin') !== false) {
                    $q->orWhere('username', 'mentor1');
                }
            })->first();
            if ($mentorUser) {
                return $mentorUser;
            }
        }

        if ($siswa->created_id && $creator = User::where('id', $siswa->created_id)->where('role', 'mentor')->where('username', '!=', 'sekretariat')->first()) {
            return $creator;
        }

        return User::where('role', 'mentor')->where('username', 'mentor1')->first();
    }

    /**
     * Dapatkan daftar id_sertifikat siswa bimbingan mentor yang login
     */
    protected function getStudentIdsForMentor($user)
    {
        if ($user->role === 'sekretariat') {
            return SertifikatPKL::where('sts_diambil', 0)
                ->whereDate('tgl_selesai', '>=', now()->toDateString())
                ->pluck('id_sertifikat')
                ->toArray();
        }

        $mentorSiswaIds = MentorSiswa::where('mentor_id', $user->id)
            ->where('is_active', true)
            ->pluck('siswa_id')
            ->toArray();

        $pembimbingNames = [$user->nama];
        if ($user->id == 3 || $user->username === 'mentor1') {
            $pembimbingNames[] = 'Slamet Arifin, A.Md. T.';
            $pembimbingNames[] = 'Slamet Arifin, A.Md.T.';
            $pembimbingNames[] = 'Slamet Arifin,A.md.T.';
            $pembimbingNames[] = 'Slamet Arifin';
            $pembimbingNames[] = 'SLAMET ARIFIN, A.Md. T.';
        }

        $fallbackIds = SertifikatPKL::where(function($q) use ($user, $pembimbingNames) {
                $q->where('created_id', $user->id)
                  ->orWhereIn('nm_pembimbing', $pembimbingNames);
            })
            ->where('sts_diambil', 0)
            ->whereDate('tgl_selesai', '>=', now()->toDateString())
            ->pluck('id_sertifikat')
            ->toArray();

        $studentIds = array_values(array_unique(array_merge($mentorSiswaIds, $fallbackIds)));
        return empty($studentIds) ? [0] : $studentIds;
    }

    // ==========================================
    // BAGIAN SISWA
    // ==========================================

    /**
     * Halaman Utama Logbook Kegiatan Siswa (Tabel Rekap Harian)
     */
    public function siswaIndex(Request $request)
    {
        $user = Auth::user();
        if ($user->role !== 'siswa') {
            abort(403, 'Akses khusus untuk siswa.');
        }

        $siswa = SertifikatPKL::getByUser($user);
        if (!$siswa) {
            return view('siswa.logbook.index', [
                'siswa' => null,
                'logbooks' => collect(),
                'stats' => ['total' => 0, 'bulan_ini' => 0, 'sudah_isi_hari_ini' => false],
                'filterTanggal' => null,
                'search' => null,
                'mentor' => null,
            ]);
        }

        $mentor = $this->getMentorForSiswa($siswa);

        // Filter parameter
        $filterTanggal = $request->input('tanggal');
        $search = $request->input('search');

        $query = Logbook::where('id_sertifikat', $siswa->id_sertifikat);

        if ($filterTanggal) {
            $query->whereDate('tanggal', $filterTanggal);
        }

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('judul_kegiatan', 'like', "%{$search}%")
                  ->orWhere('deskripsi_kegiatan', 'like', "%{$search}%")
                  ->orWhere('hasil_kegiatan', 'like', "%{$search}%")
                  ->orWhere('kendala', 'like', "%{$search}%");
            });
        }

        $logbooks = $query->orderBy('tanggal', 'desc')->orderBy('created_at', 'desc')->get();

        // Statistik sederhana aktivitas harian
        $allLogs = Logbook::where('id_sertifikat', $siswa->id_sertifikat)->get();
        $today = date('Y-m-d');
        $currentMonth = date('Y-m');

        $stats = [
            'total' => $allLogs->count(),
            'bulan_ini' => $allLogs->filter(function($item) use ($currentMonth) {
                return $item->tanggal && $item->tanggal->format('Y-m') === $currentMonth;
            })->count(),
            'sudah_isi_hari_ini' => $allLogs->contains(function($item) use ($today) {
                return $item->tanggal && $item->tanggal->format('Y-m-d') === $today;
            }),
        ];

        return view('siswa.logbook.index', compact(
            'siswa',
            'logbooks',
            'stats',
            'filterTanggal',
            'search',
            'mentor'
        ));
    }

    /**
     * Simpan Logbook Kegiatan Harian Siswa
     */
    public function siswaStore(Request $request)
    {
        $user = Auth::user();
        if ($user->role !== 'siswa') {
            abort(403, 'Hanya siswa yang dapat mengisi logbook.');
        }

        $siswa = SertifikatPKL::getByUser($user);
        if (!$siswa) {
            return redirect()->route('siswa.logbook.index')
                ->with('error', 'Profil magang Anda belum aktif.');
        }

        $request->validate([
            'tanggal' => 'required|date',
            'jam_mulai' => 'nullable',
            'jam_selesai' => 'nullable',
            'judul_kegiatan' => 'required|string|max:255',
            'deskripsi_kegiatan' => 'required|string',
            'hasil_kegiatan' => 'nullable|string',
            'kendala' => 'nullable|string',
            'dokumentasi' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ], [
            'tanggal.required' => 'Tanggal kegiatan wajib diisi.',
            'judul_kegiatan.required' => 'Judul kegiatan wajib diisi.',
            'deskripsi_kegiatan.required' => 'Uraian / rangkuman kegiatan wajib diisi.',
            'dokumentasi.mimes' => 'Dokumentasi harus berupa file JPG, PNG, atau PDF.',
            'dokumentasi.max' => 'Ukuran file dokumentasi maksimal 5MB.',
        ]);

        $mentor = $this->getMentorForSiswa($siswa);

        $dokumentasiPath = null;
        if ($request->hasFile('dokumentasi')) {
            $file = $request->file('dokumentasi');
            $filename = 'logbook_' . $siswa->id_sertifikat . '_' . date('Ymd_His') . '.' . $file->getClientOriginalExtension();
            $dokumentasiPath = $file->storeAs('logbooks', $filename, 'public');
        }

        try {
            Logbook::create([
                'id_sertifikat' => $siswa->id_sertifikat,
                'mentor_id' => $mentor ? $mentor->id : null,
                'tanggal' => $request->input('tanggal'),
                'jam_mulai' => $request->input('jam_mulai'),
                'jam_selesai' => $request->input('jam_selesai'),
                'judul_kegiatan' => $request->input('judul_kegiatan'),
                'deskripsi_kegiatan' => $request->input('deskripsi_kegiatan'),
                'hasil_kegiatan' => $request->input('hasil_kegiatan'),
                'kendala' => $request->input('kendala'),
                'dokumentasi' => $dokumentasiPath,
                'status' => 'Disetujui', // Langsung tercatat tanpa perlu menunggu verifikasi
            ]);

            return redirect()->route('siswa.logbook.index')
                ->with('success', 'Catatan logbook kegiatan harian berhasil ditambahkan.');
        } catch (\Exception $e) {
            Log::error('Gagal menyimpan logbook: ' . $e->getMessage());
            return redirect()->route('siswa.logbook.index')
                ->with('error', 'Gagal menyimpan logbook kegiatan. Silakan coba lagi.');
        }
    }

    /**
     * Update Logbook Kegiatan Siswa
     */
    public function siswaUpdate(Request $request, $id)
    {
        $user = Auth::user();
        $siswa = SertifikatPKL::getByUser($user);
        if (!$siswa) {
            abort(403);
        }

        $logbook = Logbook::where('id', $id)
            ->where('id_sertifikat', $siswa->id_sertifikat)
            ->firstOrFail();

        $request->validate([
            'tanggal' => 'required|date',
            'jam_mulai' => 'nullable',
            'jam_selesai' => 'nullable',
            'judul_kegiatan' => 'required|string|max:255',
            'deskripsi_kegiatan' => 'required|string',
            'hasil_kegiatan' => 'nullable|string',
            'kendala' => 'nullable|string',
            'dokumentasi' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $dokumentasiPath = $logbook->dokumentasi;
        if ($request->hasFile('dokumentasi')) {
            if ($logbook->dokumentasi && Storage::disk('public')->exists($logbook->dokumentasi)) {
                Storage::disk('public')->delete($logbook->dokumentasi);
            }

            $file = $request->file('dokumentasi');
            $filename = 'logbook_' . $siswa->id_sertifikat . '_' . date('Ymd_His') . '.' . $file->getClientOriginalExtension();
            $dokumentasiPath = $file->storeAs('logbooks', $filename, 'public');
        }

        try {
            $logbook->update([
                'tanggal' => $request->input('tanggal'),
                'jam_mulai' => $request->input('jam_mulai'),
                'jam_selesai' => $request->input('jam_selesai'),
                'judul_kegiatan' => $request->input('judul_kegiatan'),
                'deskripsi_kegiatan' => $request->input('deskripsi_kegiatan'),
                'hasil_kegiatan' => $request->input('hasil_kegiatan'),
                'kendala' => $request->input('kendala'),
                'dokumentasi' => $dokumentasiPath,
            ]);

            return redirect()->route('siswa.logbook.index')
                ->with('success', 'Catatan logbook kegiatan berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error('Gagal memperbarui logbook: ' . $e->getMessage());
            return redirect()->route('siswa.logbook.index')
                ->with('error', 'Gagal memperbarui logbook kegiatan.');
        }
    }

    /**
     * Hapus Logbook Kegiatan Siswa
     */
    public function siswaDestroy($id)
    {
        $user = Auth::user();
        $siswa = SertifikatPKL::getByUser($user);
        if (!$siswa) {
            abort(403);
        }

        $logbook = Logbook::where('id', $id)
            ->where('id_sertifikat', $siswa->id_sertifikat)
            ->firstOrFail();

        try {
            if ($logbook->dokumentasi && Storage::disk('public')->exists($logbook->dokumentasi)) {
                Storage::disk('public')->delete($logbook->dokumentasi);
            }

            $logbook->delete();

            return redirect()->route('siswa.logbook.index')
                ->with('success', 'Catatan logbook kegiatan berhasil dihapus.');
        } catch (\Exception $e) {
            Log::error('Gagal menghapus logbook: ' . $e->getMessage());
            return redirect()->route('siswa.logbook.index')
                ->with('error', 'Gagal menghapus logbook.');
        }
    }

    // ==========================================
    // BAGIAN MENTOR / PEMBIMBING
    // ==========================================

    /**
     * Halaman Tabel Logbook Siswa Bimbingan untuk Mentor
     */
    public function mentorIndex(Request $request)
    {
        $user = Auth::user();
        if (!in_array($user->role, ['mentor', 'sekretariat'])) {
            abort(403, 'Akses khusus untuk pembimbing dan sekretariat.');
        }

        $studentIds = $this->getStudentIdsForMentor($user);

        // Ambil daftar seluruh siswa bimbingan untuk opsi dropdown filter (hanya siswa aktif)
        $mentoredStudents = SertifikatPKL::whereIn('id_sertifikat', $studentIds)
            ->where('sts_diambil', 0)
            ->whereDate('tgl_selesai', '>=', now()->toDateString())
            ->orderBy('nama_lengkap', 'asc')
            ->get();

        if ($mentoredStudents->isEmpty()) {
            $mentoredStudents = SertifikatPKL::whereIn('id_sertifikat', $studentIds)
                ->orderBy('nama_lengkap', 'asc')
                ->get();
        }

        // Parameter Filter
        $filterStudent = $request->input('siswa_id');
        $filterTanggal = $request->input('tanggal');
        $search = $request->input('search');

        $query = Logbook::whereIn('id_sertifikat', $studentIds)
            ->with(['siswa', 'user']);

        if ($filterStudent) {
            $query->where('id_sertifikat', $filterStudent);
        }

        if ($filterTanggal) {
            $query->whereDate('tanggal', $filterTanggal);
        }

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('judul_kegiatan', 'like', "%{$search}%")
                  ->orWhere('deskripsi_kegiatan', 'like', "%{$search}%")
                  ->orWhere('hasil_kegiatan', 'like', "%{$search}%")
                  ->orWhereHas('siswa', function($sq) use ($search) {
                      $sq->where('nama_lengkap', 'like', "%{$search}%")
                        ->orWhere('nim_nis', 'like', "%{$search}%")
                        ->orWhere('asal_sekolah', 'like', "%{$search}%")
                        ->orWhere('bidang', 'like', "%{$search}%");
                  });
            });
        }

        $logbooks = $query->orderBy('tanggal', 'desc')->orderBy('created_at', 'desc')->get();

        // Statistik untuk mentor
        $allLogsQuery = Logbook::whereIn('id_sertifikat', $studentIds);
        if ($filterStudent) {
            $allLogsQuery->where('id_sertifikat', $filterStudent);
        }
        $allLogs = $allLogsQuery->get();

        $today = date('Y-m-d');
        $stats = [
            'total_kegiatan' => $allLogs->count(),
            'kegiatan_hari_ini' => $allLogs->filter(function($l) use ($today) {
                return $l->tanggal && $l->tanggal->format('Y-m-d') === $today;
            })->count(),
            'total_siswa' => $mentoredStudents->count(),
        ];

        return view('mentor.logbook.index', compact(
            'logbooks',
            'mentoredStudents',
            'stats',
            'filterStudent',
            'filterTanggal',
            'search'
        ));
    }

    /**
     * Detail Logbook Siswa untuk Modal Mentor
     */
    public function mentorShow($id)
    {
        $user = Auth::user();
        if (!in_array($user->role, ['mentor', 'sekretariat'])) {
            abort(403);
        }

        $studentIds = $this->getStudentIdsForMentor($user);

        $logbook = Logbook::where('id', $id)
            ->whereIn('id_sertifikat', $studentIds)
            ->with(['siswa', 'mentor'])
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $logbook->id,
                'nama_siswa' => $logbook->siswa ? $logbook->siswa->nama_lengkap : '-',
                'nim_nis' => $logbook->siswa ? $logbook->siswa->nim_nis : '-',
                'asal_sekolah' => $logbook->siswa ? $logbook->siswa->asal_sekolah : '-',
                'bidang' => $logbook->siswa ? $logbook->siswa->bidang : '-',
                'tanggal' => $logbook->tanggal ? $logbook->tanggal->translatedFormat('l, d F Y') : '-',
                'jam_mulai' => $logbook->jam_mulai ? substr($logbook->jam_mulai, 0, 5) . ' WIB' : '-',
                'jam_selesai' => $logbook->jam_selesai ? substr($logbook->jam_selesai, 0, 5) . ' WIB' : '-',
                'judul_kegiatan' => $logbook->judul_kegiatan,
                'deskripsi_kegiatan' => $logbook->deskripsi_kegiatan,
                'hasil_kegiatan' => $logbook->hasil_kegiatan ?? '-',
                'kendala' => $logbook->kendala ?? '-',
                'dokumentasi_url' => $logbook->dokumentasi_url,
                'is_pdf' => $logbook->dokumentasi && strtolower(pathinfo($logbook->dokumentasi, PATHINFO_EXTENSION)) === 'pdf',
                'created_at' => $logbook->created_at ? $logbook->created_at->translatedFormat('d M Y, H:i') . ' WIB' : '-',
            ]
        ]);
    }
}
