<?php

namespace App\Http\Controllers;

use App\Models\SertifikatPKL;
use App\Models\SertifikatHandover;
use App\Models\User;
use App\Models\PengajuanMagang;
use App\Models\Instansi;
use App\Models\Announcement;
use App\Constants\BidangConstants;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use DB;

class SekretariatController extends Controller
{
    /**
     * Dashboard Sekretariat - View all fields with complete statistics
     */
    public function dashboard()
    {
        try {
            $user = Auth::user();

            // Get official bidang for dashboard widget display (whitelist 4 bidang)
            $officialBidang = BidangConstants::getDashboardBidangList();

            $bidang = SertifikatPKL::select('bidang')
                ->whereIn('bidang', $officialBidang)
                ->distinct()
                ->pluck('bidang')
                ->filter()
                ->sort()
                ->values();

            // Ensure all 4 official bidang exist in list (even if 0 count)
            foreach ($officialBidang as $ob) {
                if (!$bidang->contains($ob)) {
                    $bidang->push($ob);
                }
            }

            // Get all mentors from all fields
            $totalMentor = User::where('role', 'mentor')->count();

            // Count statistics
            $totalSiswa = SertifikatPKL::count();
            $siswaAktif = SertifikatPKL::where('sts_diambil', false)
                ->whereDate('tgl_mulai', '<=', now())
                ->whereDate('tgl_selesai', '>=', now())
                ->count();
            $siswaLulus = SertifikatPKL::where('sts_diambil', true)->count();
            $totalPengajuan = PengajuanMagang::count();
            $totalSertifikat = SertifikatPKL::count();

            // Recent certificates
            $recentSertifikat = SertifikatPKL::orderBy('created_id', 'desc')
                ->limit(10)
                ->get();

            // Pending handovers
            $pendingHandovers = SertifikatHandover::where('status', 'pending')
                ->where('from_user_id', $user->id)
                ->with(['sertifikat', 'toUser'])
                ->get();

            // Ambil pengumuman terbaru yang sudah dipublikasikan - SEMUA pengumuman dari semua mentor/sekretariat
            $pengumumans = Announcement::where('published_at', '<=', Carbon::now())
                                     ->orderByDesc('published_at')
                                     ->limit(10) // Batasi 10 pengumuman terbaru
                                     ->get();

            // Statistics per bidang (filtered to 4 official bidang)
            $bidangStats = [];
            foreach ($officialBidang as $b) {
                $bidangStats[] = [
                    'bidang' => $b,
                    'siswa_count' => SertifikatPKL::where('bidang', $b)->count(),
                    'mentor_count' => User::where('role', 'mentor')->get()->filter(function($user) use ($b) {
                        return SertifikatPKL::where('bidang', $b)->where('created_id', $user->id)->exists()
                            || $user->bidang === $b;
                    })->count()
                ];
            }

            $totalBidang = count($officialBidang);
            
            return view('sekretariat.dashboard', compact(
                'bidang',
                'totalBidang',
                'totalMentor',
                'totalSiswa',
                'siswaAktif',
                'siswaLulus',
                'totalPengajuan',
                'totalSertifikat',
                'recentSertifikat',
                'pendingHandovers',
                'pengumumans',
                'bidangStats'
            ));
        } catch (\Exception $e) {
            Log::error('Error in SekretariatController@dashboard: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * View field details with mentors and students
     * UNTUK SEKRETARIAT: lihat semua bidang dengan mentor dan siswa
     * UNTUK MENTOR: lihat bidang mereka saja
     */
    public function viewField($bidang = null)
    {
        try {
            $user = Auth::user();
            
            // Jika user adalah Mentor, hanya bisa lihat bidang mereka sendiri
            if ($user->role === 'mentor') {
                // Ambil bidang unik yang ditangani mentor ini
                $mentorBidang = SertifikatPKL::where('created_id', $user->id)
                    ->distinct('bidang')
                    ->pluck('bidang')
                    ->sort()
                    ->values();

                // Jika bidang tidak spesifik atau tidak ada, tampilkan list bidang mentor
                if (empty($bidang) || !$mentorBidang->contains($bidang)) {
                    return view('sekretariat.mentor-bidang-list', [
                        'mentorBidang' => $mentorBidang,
                        'userRole' => 'mentor'
                    ]);
                }

                // Get siswa di bidang yang ditangani mentor ini
                $siswa = SertifikatPKL::where('bidang', $bidang)
                    ->where('created_id', $user->id)
                    ->orderBy('tgl_mulai', 'desc')
                    ->get();

            } else {
                // Sekretariat bisa lihat semua bidang
                // Get mentors in this field
                $mentors = User::where('role', 'mentor')
                    ->get()
                    ->filter(function ($u) use ($bidang) {
                        return SertifikatPKL::where('bidang', $bidang)
                            ->where('created_id', $u->id)
                            ->exists();
                    })
                    ->map(function($m) use ($bidang) {
                        return [
                            'id' => $m->id,
                            'nama' => $m->nama ?? $m->username,
                            'siswa_count' => SertifikatPKL::where('bidang', $bidang)
                                ->where('created_id', $m->id)
                                ->count()
                        ];
                    })
                    ->values();

                // Get students in this field
                $siswa = SertifikatPKL::where('bidang', $bidang)
                    ->with(['createdBy'])
                    ->orderBy('tgl_mulai', 'desc')
                    ->get();
            }

            // Statistics for this field
            $totalSiswa = $siswa->count();
            $siswaAktif = $siswa->filter(function ($item) {
                return !$item->sts_diambil && $item->tgl_mulai <= now() && $item->tgl_selesai >= now();
            })->count();
            $siswaLulus = $siswa->filter(function ($item) {
                return $item->sts_diambil && $item->tgl_selesai < now();
            })->count();

            // Ambil detail periode magang
            $periodeInfo = [
                'tgl_mulai' => $siswa->min('tgl_mulai'),
                'tgl_selesai' => $siswa->max('tgl_selesai'),
            ];

            $data = [
                'bidang' => $bidang,
                'siswa' => $siswa,
                'totalSiswa' => $totalSiswa,
                'siswaAktif' => $siswaAktif,
                'siswaLulus' => $siswaLulus,
                'periodeInfo' => $periodeInfo,
                'userRole' => $user->role,
            ];

            if ($user->role === 'mentor') {
                $data['mentorName'] = $user->nama ?? $user->username;
            } else {
                $data['mentors'] = $mentors ?? [];
            }

            return view('sekretariat.field-detail', $data);
        } catch (\Exception $e) {
            Log::error('Error in SekretariatController@viewField: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal memuat detail bidang: ' . $e->getMessage());
        }
    }

    /**
     * CRUD Sertifikat - Create
     */
    public function createSertifikat()
    {
        $bidang = SertifikatPKL::select('bidang')
            ->whereNotNull('bidang')
            ->distinct()
            ->pluck('bidang');
        $instansis = Instansi::orderBy('nama', 'asc')->get();

        return view('sekretariat.create-sertifikat', compact('bidang', 'instansis'));
    }

    /**
     * Store certificate (Sekretariat creating)
     */
    public function storeSertifikat(Request $request)
    {
        try {
            $validBidang = implode(',', BidangConstants::getValidBidang());
            
            $validated = $request->validate([
                'sts_kadis' => 'required|integer|in:0,1',
                'nama_lengkap' => 'required|string|max:50',
                'asal_sekolah' => 'required|string|max:50',
                'instansi_id' => 'nullable|integer',
                'nim_nis' => 'required|string|max:20|unique:tbl_sertifikat_pkl,nim_nis',
                'tempat_lahir' => 'required|string|max:50',
                'tgl_lahir' => 'required|date',
                'jurusan' => 'required|string|max:100',
                'tgl_mulai' => 'required|date',
                'tgl_selesai' => 'required|date|after_or_equal:tgl_mulai',
                'tgl_sertifikat' => 'required|date',
                'nm_pembimbing' => 'required|string|max:50',
                'nik_pembimbing' => 'required|string|max:25',
                'bidang' => 'required|string|max:50|in:' . $validBidang,
                'jabatan_pembimbing' => 'required|string|max:50',
                'no_sertifikat' => 'nullable|string|max:50',
                'nm_kadis' => 'required|string|max:50',
                'nip_kadis' => 'required|string|max:25',
                'jabatan_pegawai' => 'required|string|max:100',
                'aprove_pembimbing' => 'required|integer|in:0,1',
                'approve_kadis' => 'required|integer|in:0,1',
                'sts_diambil' => 'required|integer|in:0,1',
                'pejabat_ttd' => 'nullable|string|max:100',
            ], [
                'bidang.in' => 'Bidang yang dipilih tidak valid. Pilih salah satu dari: Bidang Sekretariat, Bidang Informasi dan Komunikasi Publik, Bidang Informatika, atau Bidang Statistik dan Persandian.',
            ]);

            $validated['created_id'] = Auth::id();
            $validated['created_by_role'] = 'sekretariat';
            $validated['sertifikat_status'] = 'draft';

            if ($request->filled('instansi_id')) {
                $validated['instansi_id'] = $request->instansi_id;
            } elseif (!empty($validated['asal_sekolah'])) {
                $matchedInstansi = Instansi::findMatchingInstansi($validated['asal_sekolah']);
                $validated['instansi_id'] = $matchedInstansi ? $matchedInstansi->id : null;
            }

            $sertifikat = SertifikatPKL::create($validated);

            Log::info('Sertifikat dibuat oleh Sekretariat', [
                'id_sertifikat' => $sertifikat->id_sertifikat,
                'created_by' => Auth::id(),
                'nama_siswa' => $sertifikat->nama_lengkap
            ]);

            return redirect()->route('sekretariat.dashboard')
                ->with('success', 'Sertifikat berhasil dibuat');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
        } catch (\Exception $e) {
            Log::error('Error storing sertifikat: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal membuat sertifikat: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Edit sertifikat
     */
    public function editSertifikat($id)
    {
        try {
            $sertifikat = SertifikatPKL::findOrFail($id);

            // Authorization check using policy
            $this->authorize('update', $sertifikat);

            $bidang = SertifikatPKL::select('bidang')
                ->whereNotNull('bidang')
                ->distinct()
                ->pluck('bidang');
            $instansis = Instansi::orderBy('nama', 'asc')->get();

            return view('sekretariat.edit-sertifikat', compact('sertifikat', 'bidang', 'instansis'));
        } catch (\Exception $e) {
            Log::error('Error in editSertifikat: ' . $e->getMessage());
            return redirect()->route('sekretariat.dashboard')
                ->with('error', 'Gagal memuat data sertifikat');
        }
    }

    /**
     * Update sertifikat
     */
    public function updateSertifikat(Request $request, $id)
    {
        try {
            $sertifikat = SertifikatPKL::findOrFail($id);

            $validBidang = implode(',', BidangConstants::getValidBidang());
            
            $validated = $request->validate([
                'sts_kadis' => 'required|integer|in:0,1',
                'nama_lengkap' => 'required|string|max:50',
                'asal_sekolah' => 'required|string|max:50',
                'instansi_id' => 'nullable|integer',
                'nim_nis' => 'required|string|max:20',
                'tempat_lahir' => 'required|string|max:50',
                'tgl_lahir' => 'required|date',
                'jurusan' => 'required|string|max:100',
                'tgl_mulai' => 'required|date',
                'tgl_selesai' => 'required|date|after_or_equal:tgl_mulai',
                'tgl_sertifikat' => 'required|date',
                'nm_pembimbing' => 'required|string|max:50',
                'nik_pembimbing' => 'required|string|max:25',
                'bidang' => 'required|string|max:50|in:' . $validBidang,
                'jabatan_pembimbing' => 'required|string|max:50',
                'no_sertifikat' => 'nullable|string|max:50',
                'nm_kadis' => 'required|string|max:50',
                'nip_kadis' => 'required|string|max:25',
                'jabatan_pegawai' => 'required|string|max:100',
                'aprove_pembimbing' => 'required|integer|in:0,1',
                'approve_kadis' => 'required|integer|in:0,1',
                'sts_diambil' => 'required|integer|in:0,1',
                'pejabat_ttd' => 'nullable|string|max:100',
            ], [
                'bidang.in' => 'Bidang yang dipilih tidak valid. Pilih salah satu dari: Bidang Sekretariat, Bidang Informasi dan Komunikasi Publik, Bidang Informatika, atau Bidang Statistik dan Persandian.',
            ]);

            if ($request->filled('instansi_id')) {
                $validated['instansi_id'] = $request->instansi_id;
            } elseif (!empty($validated['asal_sekolah'])) {
                $matchedInstansi = Instansi::findMatchingInstansi($validated['asal_sekolah']);
                $validated['instansi_id'] = $matchedInstansi ? $matchedInstansi->id : null;
            } else {
                $validated['instansi_id'] = null;
            }

            $sertifikat->update($validated);

            Log::info('Sertifikat diperbarui oleh Sekretariat', [
                'id_sertifikat' => $id,
                'updated_by' => Auth::id()
            ]);

            return redirect()->route('sekretariat.antrian-sertifikat')
                ->with('success', 'Sertifikat berhasil diperbarui');
        } catch (\Exception $e) {
            Log::error('Error updating sertifikat: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal memperbarui sertifikat: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Delete sertifikat
     */
    public function deleteSertifikat($id)
    {
        try {
            $sertifikat = SertifikatPKL::findOrFail($id);

            // Check if can be deleted (draft status or before handed over)
            if (!in_array($sertifikat->sertifikat_status, ['draft', 'ready_for_handover'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sertifikat tidak dapat dihapus karena sudah diserahkan'
                ], 403);
            }

            $sertifikat->delete();

            Log::info('Sertifikat dihapus oleh Sekretariat', [
                'id_sertifikat' => $id,
                'deleted_by' => Auth::id()
            ]);

            return response()->json(['success' => true, 'message' => 'Sertifikat berhasil dihapus']);
        } catch (\Exception $e) {
            Log::error('Error deleting sertifikat: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus sertifikat'
            ], 500);
        }
    }

    /**
     * Prepare certificate for handover (Mark as ready)
     */
    public function prepareCertificateForHandover($id)
    {
        try {
            $sertifikat = SertifikatPKL::findOrFail($id);

            if ($sertifikat->created_by_role !== 'sekretariat') {
                return response()->json([
                    'success' => false,
                    'message' => 'Hanya sertifikat yang dibuat Sekretariat yang dapat diserahkan'
                ], 403);
            }

            $sertifikat->update(['sertifikat_status' => 'ready_for_handover']);

            Log::info('Sertifikat siap untuk diserahkan', [
                'id_sertifikat' => $id,
                'prepared_by' => Auth::id()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Sertifikat siap untuk diserahkan ke Mentor'
            ]);
        } catch (\Exception $e) {
            Log::error('Error preparing certificate for handover: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyiapkan sertifikat untuk diserahkan'
            ], 500);
        }
    }

    /**
     * Antrian Sertifikat - Menampilkan semua sertifikat yang belum selesai proses cetak/serah-terima
     */
    public function antrianSertifikat(Request $request)
    {
        try {
            $bidangFilter = $request->query('bidang');
            $statusFilter = $request->query('status');

            // Query sertifikat yang belum selesai (belum diambil oleh siswa)
            $query = SertifikatPKL::where('sts_diambil', false);

            // Filter berdasarkan bidang jika dipilih
            if ($bidangFilter) {
                $query->where('bidang', $bidangFilter);
            }

            // Filter berdasarkan status jika dipilih
            if ($statusFilter) {
                $query->where('sertifikat_status', $statusFilter);
            }

            $sertifikats = $query->orderBy('id_sertifikat', 'desc')->paginate(20)->withQueryString();

            $bidangList = BidangConstants::getValidBidang();

            $statusOptions = [
                'draft' => 'Draft',
                'ready_for_handover' => 'Siap Diserahkan ke Mentor',
                'handed_over' => 'Sudah Diserahkan ke Mentor',
            ];

            $stats = [
                'total' => SertifikatPKL::where('sts_diambil', false)->count(),
                'draft' => SertifikatPKL::where('sts_diambil', false)->where('sertifikat_status', 'draft')->count(),
                'ready_for_handover' => SertifikatPKL::where('sts_diambil', false)->where('sertifikat_status', 'ready_for_handover')->count(),
                'handed_over' => SertifikatPKL::where('sts_diambil', false)->where('sertifikat_status', 'handed_over')->count(),
            ];

            return view('sekretariat.antrian-sertifikat', [
                'sertifikats' => $sertifikats,
                'bidangList' => $bidangList,
                'statusOptions' => $statusOptions,
                'stats' => $stats,
                'bidangFilter' => $bidangFilter,
                'statusFilter' => $statusFilter
            ]);
        } catch (\Exception $e) {
            Log::error('Error in antrianSertifikat: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return redirect()->route('sekretariat.dashboard')->with('error', 'Gagal memuat antrian sertifikat: ' . $e->getMessage());
        }
    }

    /**
     * Hand over certificate to mentor
     */
    public function handoverCertificateToMentor(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'to_mentor_id' => 'required|exists:tbl_login,id',
                'notes' => 'nullable|string|max:500'
            ]);

            $sertifikat = SertifikatPKL::findOrFail($id);

            if ($sertifikat->created_by_role !== 'sekretariat' || $sertifikat->sertifikat_status !== 'ready_for_handover') {
                return response()->json([
                    'success' => false,
                    'message' => 'Sertifikat tidak dalam status siap untuk diserahkan'
                ], 403);
            }

            // Create handover record
            $handover = SertifikatHandover::create([
                'id_sertifikat' => $id,
                'from_user_id' => Auth::id(),
                'to_user_id' => $validated['to_mentor_id'],
                'notes' => $validated['notes'] ?? null,
                'status' => 'pending'
            ]);

            Log::info('Sertifikat ditawarkan untuk diserahkan ke Mentor', [
                'id_sertifikat' => $id,
                'handover_id' => $handover->id,
                'from_user' => Auth::id(),
                'to_user' => $validated['to_mentor_id']
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Sertifikat ditawarkan ke Mentor, menunggu penerimaan'
            ]);
        } catch (\Exception $e) {
            Log::error('Error handing over certificate: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyerahkan sertifikat ke Mentor'
            ], 500);
        }
    }

    /**
     * View pending handovers
     */
    public function pendingHandovers()
    {
        try {
            $handovers = SertifikatHandover::where('from_user_id', Auth::id())
                ->where('status', 'pending')
                ->with(['sertifikat', 'toUser'])
                ->orderBy('created_at', 'desc')
                ->get();

            return view('sekretariat.pending-handovers', compact('handovers'));
        } catch (\Exception $e) {
            Log::error('Error in pendingHandovers: ' . $e->getMessage());
            return redirect()->route('sekretariat.dashboard')
                ->with('error', 'Gagal memuat data');
        }
    }

    /**
     * Print certificate (Sekretariat can print)
     */
    public function printCertificate($id)
    {
        try {
            $sertifikat = SertifikatPKL::findOrFail($id);

            return view('sekretariat.print-certificate', compact('sertifikat'));
        } catch (\Exception $e) {
            Log::error('Error in printCertificate: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memuat sertifikat');
        }
    }

    /**
     * View all mentors - Redirect to official MentorController index
     */
    public function dataMentor(Request $request)
    {
        return redirect()->route('mentor.data-mentor.index');
    }

    /**
     * View all students from all fields
     */
    public function dataSiswa()
    {
        try {
            Instansi::syncInstansiIds();

            $siswa = SertifikatPKL::with(['createdBy', 'instansi'])
                ->orderBy('tgl_mulai', 'desc')
                ->paginate(10);

            return view('sekretariat.data-siswa', compact('siswa'));
        } catch (\Exception $e) {
            Log::error('Error in dataSiswa: ' . $e->getMessage());
            return redirect()->route('sekretariat.dashboard')
                ->with('error', 'Gagal memuat data siswa');
        }
    }

    /**
     * Create new student (Sekretariat can add student directly)
     */
    public function createSiswa()
    {
        try {
            $mentors = User::where('role', 'mentor')->get();
            // Use only valid bidang from constants
            $bidang = BidangConstants::getValidBidang();
            $instansis = Instansi::orderBy('nama', 'asc')->get();

            return view('sekretariat.create-siswa', compact('mentors', 'bidang', 'instansis'));
        } catch (\Exception $e) {
            Log::error('Error in createSiswa: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memuat form');
        }
    }

    /**
     * Store new student
     */
    public function storeSiswa(Request $request)
    {
        try {
            $validBidang = implode(',', BidangConstants::getValidBidang());
            
            $validated = $request->validate([
                'sts_kadis' => 'required|integer|in:0,1',
                'nama_lengkap' => 'required|string|max:50',
                'asal_sekolah' => 'required|string|max:50',
                'instansi_id' => 'nullable|integer',
                'nim_nis' => 'required|string|max:20|unique:tbl_sertifikat_pkl,nim_nis',
                'tempat_lahir' => 'required|string|max:50',
                'tgl_lahir' => 'required|date',
                'jurusan' => 'required|string|max:100',
                'tgl_mulai' => 'required|date',
                'tgl_selesai' => 'required|date|after_or_equal:tgl_mulai',
                'tgl_sertifikat' => 'required|date',
                'nm_pembimbing' => 'required|string|max:50',
                'nik_pembimbing' => 'required|string|max:25',
                'bidang' => 'required|string|max:50|in:' . $validBidang,
                'jabatan_pembimbing' => 'required|string|max:50',
                'no_sertifikat' => 'nullable|string|max:50',
                'nm_kadis' => 'required|string|max:50',
                'nip_kadis' => 'required|string|max:25',
                'jabatan_pegawai' => 'required|string|max:100',
                'aprove_pembimbing' => 'required|integer|in:0,1',
                'approve_kadis' => 'required|integer|in:0,1',
                'sts_diambil' => 'required|integer|in:0,1',
                'pejabat_ttd' => 'nullable|string|max:100',
                'created_id' => 'required|exists:tbl_login,id',
            ], [
                'bidang.in' => 'Bidang yang dipilih tidak valid. Pilih salah satu dari: Bidang Sekretariat, Bidang Informasi dan Komunikasi Publik, Bidang Informatika, atau Bidang Statistik dan Persandian.',
            ]);

            $validated['created_by_role'] = 'sekretariat';
            $validated['sertifikat_status'] = 'draft';

            if ($request->filled('instansi_id')) {
                $validated['instansi_id'] = $request->instansi_id;
            } elseif (!empty($validated['asal_sekolah'])) {
                $matchedInstansi = Instansi::findMatchingInstansi($validated['asal_sekolah']);
                $validated['instansi_id'] = $matchedInstansi ? $matchedInstansi->id : null;
            }

            $sertifikat = SertifikatPKL::create($validated);

            if ($request->filled('created_id')) {
                \App\Models\MentorSiswa::create([
                    'mentor_id' => $request->created_id,
                    'siswa_id' => $sertifikat->id_sertifikat,
                    'tanggal_bimbingan' => now(),
                    'is_active' => true,
                ]);
            }

            Log::info('Siswa ditambahkan oleh Sekretariat', [
                'id_sertifikat' => $sertifikat->id_sertifikat,
                'created_by' => Auth::id(),
                'nama_siswa' => $sertifikat->nama_lengkap
            ]);

            return redirect()->route('sekretariat.data-siswa')
                ->with('success', 'Data siswa berhasil ditambahkan');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
        } catch (\Exception $e) {
            Log::error('Error storing siswa: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal menambahkan siswa: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Edit student
     */
    public function editSiswa($id)
    {
        try {
            $siswa = SertifikatPKL::findOrFail($id);
            $mentors = User::where('role', 'mentor')->get();
            // Use only valid bidang from constants
            $bidang = BidangConstants::getValidBidang();
            $instansis = Instansi::orderBy('nama', 'asc')->get();

            return view('sekretariat.edit-siswa', compact('siswa', 'mentors', 'bidang', 'instansis'));
        } catch (\Exception $e) {
            Log::error('Error in editSiswa: ' . $e->getMessage());
            return redirect()->route('sekretariat.data-siswa')
                ->with('error', 'Gagal memuat data siswa');
        }
    }

    /**
     * Update student
     */
    public function updateSiswa(Request $request, $id)
    {
        try {
            $siswa = SertifikatPKL::findOrFail($id);

            $validBidang = implode(',', BidangConstants::getValidBidang());
            
            $validated = $request->validate([
                'sts_kadis' => 'required|integer|in:0,1',
                'nama_lengkap' => 'required|string|max:50',
                'asal_sekolah' => 'required|string|max:50',
                'instansi_id' => 'nullable|integer',
                'nim_nis' => 'required|string|max:20',
                'tempat_lahir' => 'required|string|max:50',
                'tgl_lahir' => 'required|date',
                'jurusan' => 'required|string|max:100',
                'tgl_mulai' => 'required|date',
                'tgl_selesai' => 'required|date|after_or_equal:tgl_mulai',
                'tgl_sertifikat' => 'required|date',
                'nm_pembimbing' => 'required|string|max:50',
                'nik_pembimbing' => 'required|string|max:25',
                'bidang' => 'required|string|max:50|in:' . $validBidang,
                'jabatan_pembimbing' => 'required|string|max:50',
                'no_sertifikat' => 'nullable|string|max:50',
                'nm_kadis' => 'required|string|max:50',
                'nip_kadis' => 'required|string|max:25',
                'jabatan_pegawai' => 'required|string|max:100',
                'aprove_pembimbing' => 'required|integer|in:0,1',
                'approve_kadis' => 'required|integer|in:0,1',
                'sts_diambil' => 'required|integer|in:0,1',
                'pejabat_ttd' => 'nullable|string|max:100',
                'created_id' => 'required|exists:tbl_login,id',
            ], [
                'bidang.in' => 'Bidang yang dipilih tidak valid. Pilih salah satu dari: Bidang Sekretariat, Bidang Informasi dan Komunikasi Publik, Bidang Informatika, atau Bidang Statistik dan Persandian.',
            ]);

            if ($request->filled('instansi_id')) {
                $validated['instansi_id'] = $request->instansi_id;
            } elseif (!empty($validated['asal_sekolah'])) {
                $matchedInstansi = Instansi::findMatchingInstansi($validated['asal_sekolah']);
                $validated['instansi_id'] = $matchedInstansi ? $matchedInstansi->id : null;
            } else {
                $validated['instansi_id'] = null;
            }

            $siswa->update($validated);

            if ($request->filled('created_id')) {
                \App\Models\MentorSiswa::updateOrCreate(
                    ['siswa_id' => $id],
                    [
                        'mentor_id' => $request->created_id,
                        'tanggal_bimbingan' => now(),
                        'is_active' => true,
                    ]
                );
            }

            Log::info('Data siswa diperbarui oleh Sekretariat', [
                'id_sertifikat' => $id,
                'updated_by' => Auth::id()
            ]);

            return redirect()->route('sekretariat.data-siswa')
                ->with('success', 'Data siswa berhasil diperbarui');
        } catch (\Exception $e) {
            Log::error('Error updating siswa: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Gagal memperbarui siswa: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Delete student
     */
    public function deleteSiswa($id)
    {
        try {
            $siswa = SertifikatPKL::findOrFail($id);
            $siswa->delete();

            Log::info('Data siswa dihapus oleh Sekretariat', [
                'id_sertifikat' => $id,
                'deleted_by' => Auth::id()
            ]);

            return response()->json(['success' => true, 'message' => 'Data siswa berhasil dihapus']);
        } catch (\Exception $e) {
            Log::error('Error deleting siswa: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus siswa'
            ], 500);
        }
    }

    /**
     * View all internship applications
     */
    public function dataPengajuan()
    {
        try {
            $pengajuan = PengajuanMagang::with(['user', 'instansi'])
                ->orderBy('created_at', 'desc')
                ->paginate(20);

            return view('sekretariat.data-pengajuan', compact('pengajuan'));
        } catch (\Exception $e) {
            Log::error('Error in dataPengajuan: ' . $e->getMessage());
            return redirect()->route('sekretariat.dashboard')
                ->with('error', 'Gagal memuat data pengajuan');
        }
    }

    /**
     * View all sertifikat
     */
    public function dataSertifikat()
    {
        try {
            $sertifikat = SertifikatPKL::with(['createdBy'])
                ->orderBy('created_id', 'desc')
                ->paginate(20);

            return view('sekretariat.data-sertifikat', compact('sertifikat'));
        } catch (\Exception $e) {
            Log::error('Error in dataSertifikat: ' . $e->getMessage());
            return redirect()->route('sekretariat.dashboard')
                ->with('error', 'Gagal memuat data sertifikat');
        }
    }

    /**
     * View all internship calendar
     */
    public function kalendarMagang()
    {
        try {
            return view('sekretariat.kalender-magang');
        } catch (\Exception $e) {
            Log::error('Error in kalendarMagang: ' . $e->getMessage());
            return redirect()->route('sekretariat.dashboard')
                ->with('error', 'Gagal memuat kalender');
        }
    }

    // --- Master Data Instansi CRUD ---

    public function instansiIndex(Request $request)
    {
        return redirect()->route('dashboard.sekolah');
    }

    public function instansiCreate()
    {
        return view('sekretariat.instansi.create');
    }

    public function instansiStore(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255|unique:instansi,nama',
            'logo' => 'nullable|image|mimes:png,jpg,jpeg,svg,webp|max:2048',
        ], [
            'nama.required' => 'Nama instansi harus diisi',
            'nama.unique' => 'Nama instansi sudah terdaftar',
            'logo.image' => 'Logo harus berupa gambar',
            'logo.mimes' => 'Logo harus berformat PNG, JPG, JPEG, SVG, atau WEBP',
            'logo.max' => 'Ukuran logo maksimal 2MB',
        ]);

        try {
            $logoPath = null;
            if ($request->hasFile('logo')) {
                $logoPath = $request->file('logo')->store('logos', 'public');
            }

            Instansi::create([
                'nama' => $request->nama,
                'logo' => $logoPath,
            ]);

            return redirect()->route('sekretariat.instansi.index')
                ->with('success', 'Instansi berhasil ditambahkan');
        } catch (\Exception $e) {
            Log::error('Error in instansiStore: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menambahkan instansi: ' . $e->getMessage())->withInput();
        }
    }

    public function instansiEdit($id)
    {
        try {
            $instansi = Instansi::findOrFail($id);
            return view('sekretariat.instansi.edit', compact('instansi'));
        } catch (\Exception $e) {
            Log::error('Error in instansiEdit: ' . $e->getMessage());
            return redirect()->route('sekretariat.instansi.index')->with('error', 'Instansi tidak ditemukan');
        }
    }

    public function instansiUpdate(Request $request, $id)
    {
        $instansi = Instansi::findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:255|unique:instansi,nama,' . $id,
            'logo' => 'nullable|image|mimes:png,jpg,jpeg,svg,webp|max:2048',
        ], [
            'nama.required' => 'Nama instansi harus diisi',
            'nama.unique' => 'Nama instansi sudah terdaftar',
            'logo.image' => 'Logo harus berupa gambar',
            'logo.mimes' => 'Logo harus berformat PNG, JPG, JPEG, SVG, atau WEBP',
            'logo.max' => 'Ukuran logo maksimal 2MB',
        ]);

        try {
            $logoPath = $instansi->logo;
            if ($request->hasFile('logo')) {
                if ($instansi->logo && Storage::disk('public')->exists($instansi->logo)) {
                    Storage::disk('public')->delete($instansi->logo);
                }
                $logoPath = $request->file('logo')->store('logos', 'public');
            }

            $instansi->update([
                'nama' => $request->nama,
                'logo' => $logoPath,
            ]);

            return redirect()->route('sekretariat.instansi.index')
                ->with('success', 'Instansi berhasil diperbarui');
        } catch (\Exception $e) {
            Log::error('Error in instansiUpdate: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memperbarui instansi: ' . $e->getMessage())->withInput();
        }
    }

    public function instansiDestroy($id)
    {
        try {
            $instansi = Instansi::findOrFail($id);
            if ($instansi->logo && Storage::disk('public')->exists($instansi->logo)) {
                Storage::disk('public')->delete($instansi->logo);
            }
            $instansi->delete();

            return response()->json(['success' => true, 'message' => 'Instansi berhasil dihapus']);
        } catch (\Exception $e) {
            Log::error('Error in instansiDestroy: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal menghapus instansi']);
        }
    }

    public function instansiQuickAdd(Request $request)
    {
        $request->validate([
            'id_sertifikat' => 'required|exists:tbl_sertifikat_pkl,id_sertifikat',
            'logo' => 'required|image|mimes:png,jpg,jpeg,svg,webp|max:2048',
        ], [
            'logo.required' => 'Logo instansi harus diunggah',
            'logo.image' => 'Logo harus berupa gambar',
            'logo.mimes' => 'Logo harus berformat PNG, JPG, JPEG, SVG, atau WEBP',
            'logo.max' => 'Ukuran logo maksimal 2MB',
        ]);

        try {
            $sertifikat = SertifikatPKL::findOrFail($request->id_sertifikat);
            
            $logoPath = null;
            if ($request->hasFile('logo')) {
                $logoPath = $request->file('logo')->store('logos', 'public');
            }

            // Create new instansi
            $instansi = Instansi::create([
                'nama' => $sertifikat->asal_sekolah,
                'logo' => $logoPath,
            ]);

            // Update all students with the exact same school name in tbl_sertifikat_pkl
            SertifikatPKL::where('asal_sekolah', $sertifikat->asal_sekolah)
                ->update(['instansi_id' => $instansi->id]);

            // Update all matching pengajuan_magang records
            PengajuanMagang::where('asal_sekolah', $sertifikat->asal_sekolah)
                ->update(['instansi_id' => $instansi->id]);

            return redirect()->back()->with('success', 'Sekolah ' . $sertifikat->asal_sekolah . ' berhasil ditambahkan ke Master Data Instansi!');
        } catch (\Exception $e) {
            Log::error('Error in instansiQuickAdd: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menambahkan ke Master Data: ' . $e->getMessage());
        }
    }

    /**
     * Halaman Kelola Pengumuman untuk Sekretariat
     */
    public function pengumuman()
    {
        return view('sekretariat.pengumuman');
    }
}
