<?php

namespace App\Http\Controllers;

use App\Models\KuotaBidang;
use App\Models\SertifikatPKL;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LandingCalendarController extends Controller
{
    /**
     * Display the calendar on landing page
     */
    public function index()
    {
        try {
            // Get all internship data for calendar
            $semuaMagang = SertifikatPKL::select('id_sertifikat', 'nama_lengkap', 'nim_nis', 'asal_sekolah', 'jurusan', 'bidang', 'tgl_mulai', 'tgl_selesai', 'sts_diambil')
                ->whereNotNull('tgl_mulai')
                ->whereNotNull('tgl_selesai')
                ->where('sts_diambil', false)
                ->orderBy('tgl_mulai', 'desc')
                ->get();

            // Get quota data with current occupancy
            $kuotaBidang = KuotaBidang::all()->map(function ($kuota) {
                $today = now()->format('Y-m-d');
                $terisi = SertifikatPKL::where('bidang', $kuota->nama_bidang)
                    ->where('sts_diambil', false)
                    ->whereDate('tgl_mulai', '<=', $today)
                    ->whereDate('tgl_selesai', '>=', $today)
                    ->count();

                return [
                    'id' => $kuota->id,
                    'nama_bidang' => $kuota->nama_bidang,
                    'kuota' => $kuota->kuota,
                    'terisi' => $terisi,
                    'sisa' => max(0, $kuota->kuota - $terisi),
                    'persentase' => $kuota->kuota > 0 ? round(($terisi / $kuota->kuota) * 100) : 0,
                ];
            });

            return view('landing.calendar', compact('semuaMagang', 'kuotaBidang'));
        } catch (\Exception $e) {
            Log::error('Error in LandingCalendarController@index: ' . $e->getMessage());
            return view('landing.calendar', ['semuaMagang' => collect(), 'kuotaBidang' => collect(), 'error' => 'Gagal memuat data kalender']);
        }
    }

    /**
     * Get internship data for API (for calendar rendering)
     */
    public function getInternshipData(Request $request)
    {
        try {
            // Ambil semua data magang yang aktif dengan range tanggal lengkap
            // Group by bidang dan range tanggal yang sama
            $magang = DB::table('tbl_sertifikat_pkl')
                ->select(
                    'bidang',
                    'tgl_mulai',
                    'tgl_selesai',
                    DB::raw('COUNT(*) as siswa_count')
                )
                ->where('sts_diambil', 0)
                ->whereNotNull('tgl_mulai')
                ->whereNotNull('tgl_selesai')
                ->groupBy('bidang', 'tgl_mulai', 'tgl_selesai')
                ->orderBy('tgl_mulai', 'asc')
                ->get();

            // Format response dengan explicit date format
            $result = $magang->map(function($item) {
                return [
                    'bidang' => $item->bidang,
                    'tgl_mulai' => date('Y-m-d', strtotime($item->tgl_mulai)),
                    'tgl_selesai' => date('Y-m-d', strtotime($item->tgl_selesai)),
                    'siswa_count' => (int) $item->siswa_count
                ];
            });

            return response()->json($result);
        } catch (\Exception $e) {
            Log::error('Error in getInternshipData: ' . $e->getMessage());
            return response()->json([
                'error' => 'Gagal mengambil data magang',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mentor & Sekretariat: Display quota management page
     */
    public function manageQuota()
    {
        // Check if user is mentor or sekretariat
        if (!Auth::check() || !in_array(Auth::user()->role, ['mentor', 'sekretariat'])) {
            abort(403, 'Hanya mentor dan sekretariat yang dapat mengakses fitur ini.');
        }

        try {
            $kuotaBidang = KuotaBidang::all()->map(function ($kuota) {
                $today = now()->format('Y-m-d');
                $terisi = SertifikatPKL::where('bidang', $kuota->nama_bidang)
                    ->where('sts_diambil', false)
                    ->whereDate('tgl_mulai', '<=', $today)
                    ->whereDate('tgl_selesai', '>=', $today)
                    ->count();

                return [
                    'id' => $kuota->id,
                    'nama_bidang' => $kuota->nama_bidang,
                    'kuota' => $kuota->kuota,
                    'terisi' => $terisi,
                    'sisa' => max(0, $kuota->kuota - $terisi),
                ];
            });

            return view('mentor.manage-quota', compact('kuotaBidang'));
        } catch (\Exception $e) {
            Log::error('Error in manageQuota: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memuat halaman manajemen kuota');
        }
    }

    /**
     * Mentor & Sekretariat: Update quota
     */
    public function updateQuota(Request $request, $id)
    {
        // Check if user is mentor or sekretariat
        if (!Auth::check() || !in_array(Auth::user()->role, ['mentor', 'sekretariat'])) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        try {
            $request->validate([
                'kuota' => 'required|integer|min:0|max:999',
            ]);

            $kuota = KuotaBidang::findOrFail($id);
            $kuota->update(['kuota' => $request->kuota]);

            Log::info('Kuota bidang diperbarui oleh ' . Auth::user()->role . ': ' . $kuota->nama_bidang . ' = ' . $request->kuota . ' oleh user ' . Auth::id());

            return response()->json([
                'success' => true,
                'message' => 'Kuota berhasil diperbarui',
                'data' => $kuota
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->validator->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error in updateQuota: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui kuota'
            ], 500);
        }
    }

    /**
     * Mentor & Sekretariat: Add new quota field
     */
    public function addQuota(Request $request)
    {
        // Check if user is mentor or sekretariat
        if (!Auth::check() || !in_array(Auth::user()->role, ['mentor', 'sekretariat'])) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        try {
            $request->validate([
                'nama_bidang' => 'required|string|max:100|unique:kuota_bidang',
                'kuota' => 'required|integer|min:1|max:999',
            ]);

            $kuota = KuotaBidang::create($request->only('nama_bidang', 'kuota'));

            Log::info('Bidang kuota baru ditambahkan oleh ' . Auth::user()->role . ': ' . $kuota->nama_bidang . ' oleh user ' . Auth::id());

            return response()->json([
                'success' => true,
                'message' => 'Bidang kuota berhasil ditambahkan',
                'data' => $kuota
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->validator->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error in addQuota: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan bidang kuota'
            ], 500);
        }
    }

    /**
     * Mentor & Sekretariat: Delete quota field
     */
    public function deleteQuota($id)
    {
        // Check if user is mentor or sekretariat
        if (!Auth::check() || !in_array(Auth::user()->role, ['mentor', 'sekretariat'])) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        try {
            $kuota = KuotaBidang::findOrFail($id);
            $namaBidang = $kuota->nama_bidang;
            $kuota->delete();

            Log::info('Bidang kuota dihapus oleh ' . Auth::user()->role . ': ' . $namaBidang . ' oleh user ' . Auth::id());

            return response()->json([
                'success' => true,
                'message' => 'Bidang kuota berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            Log::error('Error in deleteQuota: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus bidang kuota'
            ], 500);
        }
    }
}
