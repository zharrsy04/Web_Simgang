<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SertifikatPKL;
use App\Models\Attendance;
use App\Models\User;
use App\Constants\BidangConstants;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PresensiMonitoringController extends Controller
{
    /**
     * Halaman monitoring presensi untuk Mentor dan Sekretariat
     * Menampilkan SEMUA siswa aktif lintas bidang
     */
    public function index(Request $request)
    {
        try {
            $user = Auth::user();
            
            // Validasi role
            if (!in_array($user->role, ['mentor', 'sekretariat'])) {
                return redirect()->back()->with('error', 'Anda tidak memiliki akses ke halaman ini.');
            }
            
            // Filter parameters
            $search = $request->input('search');
            $bidangFilter = $request->input('bidang');
            $month = (int) $request->input('month', Carbon::now()->month);
            $year = (int) $request->input('year', Carbon::now()->year);
            
            $monthStart = Carbon::createFromDate($year, $month, 1)->startOfMonth()->toDateString();
            $monthEnd = Carbon::createFromDate($year, $month, 1)->endOfMonth()->toDateString();
            
            // Query siswa yang magang pada periode bulan & tahun yang dipilih
            $query = SertifikatPKL::whereDate('tgl_mulai', '<=', $monthEnd)
                ->whereDate('tgl_selesai', '>=', $monthStart);
            
            // Apply search filter
            if ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('nama_lengkap', 'like', "%{$search}%")
                      ->orWhere('nim_nis', 'like', "%{$search}%")
                      ->orWhere('asal_sekolah', 'like', "%{$search}%");
                });
            }
            
            // Apply bidang filter
            if ($bidangFilter) {
                $query->where('bidang', $bidangFilter);
            }
            
            $siswaList = $query->orderBy('nama_lengkap', 'asc')->get();
            
            // Hitung ringkasan presensi untuk setiap siswa
            $siswaWithPresensi = $siswaList->map(function($siswa) use ($month, $year) {
                $summary = $this->calculatePresensiSummary($siswa->id_sertifikat, $month, $year, $siswa);
                
                return [
                    'id_sertifikat' => $siswa->id_sertifikat,
                    'nama_lengkap' => $siswa->nama_lengkap,
                    'nim_nis' => $siswa->nim_nis,
                    'asal_sekolah' => $siswa->asal_sekolah,
                    'jurusan' => $siswa->jurusan,
                    'bidang' => $siswa->bidang,
                    'tgl_mulai' => $siswa->tgl_mulai,
                    'tgl_selesai' => $siswa->tgl_selesai,
                    'presensi' => $summary,
                ];
            });
            
            // Get list bidang resmi untuk filter
            $bidangList = BidangConstants::getValidBidang();
            
            return view('monitoring.presensi', [
                'siswaList' => $siswaWithPresensi,
                'bidangList' => $bidangList,
                'currentMonth' => $month,
                'currentYear' => $year,
                'search' => $search,
                'bidangFilter' => $bidangFilter,
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error in PresensiMonitoringController@index: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    
    /**
     * Detail presensi per siswa (via AJAX)
     */
    public function detail(Request $request, $idSertifikat)
    {
        try {
            $month = (int) $request->input('month', Carbon::now()->month);
            $year = (int) $request->input('year', Carbon::now()->year);
            
            $siswa = SertifikatPKL::where('id_sertifikat', $idSertifikat)->first();
            
            if (!$siswa) {
                return response()->json(['success' => false, 'message' => 'Siswa tidak ditemukan'], 404);
            }
            
            // Get attendance records
            $monthStart = Carbon::createFromDate($year, $month, 1)->startOfMonth();
            $monthEnd = Carbon::createFromDate($year, $month, 1)->endOfMonth();
            
            $internshipStart = Carbon::parse($siswa->tgl_mulai);
            $internshipEnd = Carbon::parse($siswa->tgl_selesai);
            
            $startDate = $monthStart->copy()->max($internshipStart);
            $endDate = $monthEnd->copy()->min($internshipEnd)->min(Carbon::today());
            
            if ($startDate->greaterThan($endDate)) {
                return response()->json([
                    'success' => true,
                    'siswa' => [
                        'nama_lengkap' => $siswa->nama_lengkap,
                        'nim_nis' => $siswa->nim_nis,
                        'bidang' => $siswa->bidang,
                    ],
                    'attendances' => [],
                    'message' => 'Belum ada periode magang aktif di bulan ini'
                ]);
            }
            
            $attendances = Attendance::where('id_sertifikat', $idSertifikat)
                ->whereBetween('date', [$startDate, $endDate])
                ->orderBy('date', 'desc')
                ->get()
                ->map(function($att) {
                    return [
                        'date' => Carbon::parse($att->date)->locale('id')->translatedFormat('d F Y'),
                        'day' => Carbon::parse($att->date)->locale('id')->translatedFormat('l'),
                        'status' => $att->status,
                        'check_in' => $att->check_in ? Carbon::parse($att->check_in)->format('H:i') : '-',
                        'check_out' => $att->check_out ? Carbon::parse($att->check_out)->format('H:i') : '-',
                        'location' => $att->location ?? '-',
                        'notes' => $att->notes ?? '-',
                    ];
                });
            
            // Generate list semua hari kerja untuk menampilkan Alpha
            $allWorkdays = [];
            $tempDate = $startDate->copy();
            while ($tempDate->lte($endDate)) {
                if (!$tempDate->isWeekend()) {
                    $dateStr = $tempDate->toDateString();
                    $hasRecord = Attendance::where('id_sertifikat', $idSertifikat)
                        ->where('date', $dateStr)
                        ->exists();
                    
                    if (!$hasRecord) {
                        $allWorkdays[] = [
                            'date' => $tempDate->locale('id')->translatedFormat('d F Y'),
                            'day' => $tempDate->locale('id')->translatedFormat('l'),
                            'status' => 'Alpha',
                            'check_in' => '-',
                            'check_out' => '-',
                            'location' => '-',
                            'notes' => '-',
                        ];
                    }
                }
                $tempDate->addDay();
            }
            
            // Merge dan sort
            $allAttendances = $attendances->concat($allWorkdays)
                ->sortByDesc('date')
                ->values();
            
            return response()->json([
                'success' => true,
                'siswa' => [
                    'nama_lengkap' => $siswa->nama_lengkap,
                    'nim_nis' => $siswa->nim_nis,
                    'bidang' => $siswa->bidang,
                    'asal_sekolah' => $siswa->asal_sekolah,
                ],
                'attendances' => $allAttendances,
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error in PresensiMonitoringController@detail: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan'], 500);
        }
    }
    
    /**
     * Calculate presensi summary untuk satu siswa
     * Reuse logic yang sama dengan SiswaDashboardController
     */
    private function calculatePresensiSummary($idSertifikat, int $month, int $year, $sertifikat): array
    {
        // Tanggal awal dan akhir bulan
        $monthStart = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $monthEnd = Carbon::createFromDate($year, $month, 1)->endOfMonth();
        
        // Tanggal mulai dan selesai magang
        $internshipStart = Carbon::parse($sertifikat->tgl_mulai);
        $internshipEnd = Carbon::parse($sertifikat->tgl_selesai);
        
        // Tentukan periode perhitungan yang sebenarnya
        $startDate = $monthStart->copy()->max($internshipStart);
        $endDate = $monthEnd->copy()->min($internshipEnd)->min(Carbon::today());
        
        // Jika periode tidak valid, return data kosong
        if ($startDate->greaterThan($endDate)) {
            return [
                'totalDays' => 0,
                'presentDays' => 0,
                'absentDays' => 0,
                'permissionDays' => 0,
                'presentPercentage' => 0,
                'absentPercentage' => 0,
                'permissionPercentage' => 0,
            ];
        }
        
        $attendances = Attendance::where('id_sertifikat', $idSertifikat)
            ->whereBetween('date', [$startDate, $endDate])
            ->get();
        
        // Hitung total hari kerja (Senin-Jumat)
        $totalDaysInMonth = 0;
        $tempDate = $startDate->copy();
        while ($tempDate->lte($endDate)) {
            if (!$tempDate->isWeekend()) {
                $totalDaysInMonth++;
            }
            $tempDate->addDay();
        }
        
        // Abaikan presensi di hari Sabtu & Minggu
        $presentCount = $attendances->reject(fn($att) => Carbon::parse($att->date)->isWeekend())
            ->where('status', 'Hadir')->count();
        $permissionCount = $attendances->reject(fn($att) => Carbon::parse($att->date)->isWeekend())
            ->where('status', 'Izin')->count();
        
        // Alpha = Total Hari Kerja - (Hadir + Izin)
        $absentCount = $totalDaysInMonth - ($presentCount + $permissionCount);
        
        $presentPercentage = $totalDaysInMonth > 0 ? round(($presentCount / $totalDaysInMonth) * 100) : 0;
        $absentPercentage = $totalDaysInMonth > 0 ? round(($absentCount / $totalDaysInMonth) * 100) : 0;
        $permissionPercentage = $totalDaysInMonth > 0 ? round(($permissionCount / $totalDaysInMonth) * 100) : 0;
        
        return [
            'totalDays' => $totalDaysInMonth,
            'presentDays' => $presentCount,
            'absentDays' => $absentCount,
            'permissionDays' => $permissionCount,
            'presentPercentage' => $presentPercentage,
            'absentPercentage' => $absentPercentage,
            'permissionPercentage' => $permissionPercentage,
        ];
    }
}
