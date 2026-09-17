<?php

namespace App\Http\Controllers;

use App\Models\SertifikatPKL;
use App\Models\Portofolio;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MentorAttendanceController extends Controller
{
    /**
     * Menampilkan halaman daftar kehadiran yang dikelola oleh mentor.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Sesuaikan nama view dengan yang Anda gunakan
        // return view('mentor.attendance.list');
         $portofolios = Portofolio::whereHas('sertifikatPKL', function($q) {
            $q->where('nm_pembimbing', auth()->user()->name);
        })->get();

    // Sesuaikan nama view dengan yang Anda gunakan
    return view('mentor.attendance.list', compact('portofolios'));
        
    }

    /**
     * Mengambil data presensi untuk DataTables.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAttendanceData(Request $request)
    {
        try {
            $draw = $request->input('draw');
            $start = $request->input('start', 0);
            $length = $request->input('length', 10);
            $searchValue = $request->input('search.value');
            $orderColumnIndex = $request->input('order.0.column', 1);
            $orderDir = $request->input('order.0.dir', 'asc');
            
            // Log request untuk debugging
            \Log::info('DataTables Request:', [
                'draw' => $draw,
                'start' => $start,
                'length' => $length,
                'search' => $searchValue,
                'orderColumn' => $orderColumnIndex,
                'orderDir' => $orderDir
            ]);
            
            // Sesuaikan kolom ini dengan kolom DataTables di frontend (kecuali kolom NO dan PRESENSI)
            // Kolom 0 = NO (tidak di-order), Kolom 1-7 = data kolom, Kolom 8 = PRESENSI (tidak di-order)
            $columns = [
                0 => 'id_sertifikat', // Dummy untuk kolom NO
                1 => 'nama_lengkap',
                2 => 'asal_sekolah', 
                3 => 'jurusan', 
                4 => 'bidang',
                5 => 'tgl_mulai', 
                6 => 'tgl_selesai',
                7 => 'id_sertifikat' // Dummy untuk kolom PRESENSI
            ];
            
            // Pastikan orderColumnIndex valid
            if (!isset($columns[$orderColumnIndex])) {
                $orderColumnIndex = 1;
            }
            
            $orderColumn = $columns[$orderColumnIndex];

            // Query dasar untuk mengambil data sertifikat dari model SertifikatPKL
            $query = SertifikatPKL::select('id_sertifikat', 'nama_lengkap', 'asal_sekolah', 'jurusan', 'bidang','tgl_mulai', 'tgl_selesai');
            
            // Menghitung total records tanpa filter (clone query untuk menghindari modifikasi)
            $totalRecords = SertifikatPKL::count();
            
            \Log::info('Total records: ' . $totalRecords);
            
            // Menerapkan filter pencarian
            if (!empty($searchValue)) {
                \Log::info('Applying search filter: ' . $searchValue);
                $query->where(function($q) use ($searchValue) {
                    $q->where('nama_lengkap', 'LIKE', "%{$searchValue}%")
                      ->orWhere('asal_sekolah', 'LIKE', "%{$searchValue}%")
                      ->orWhere('bidang', 'LIKE', "%{$searchValue}%")
                      ->orWhere('jurusan', 'LIKE', "%{$searchValue}%")
                      ->orWhere('id_sertifikat', 'LIKE', "%{$searchValue}%");
                });
            }
            
            // Menghitung total records setelah filter (clone query lagi)
            $recordsFiltered = $query->count();
            
            \Log::info('Records after filter: ' . $recordsFiltered);
            
            // Menerapkan pengurutan
            $query->orderBy($orderColumn, $orderDir);
            
            // Menerapkan batasan untuk pagination
            $students = $query->skip($start)->take($length)->get();
            
            \Log::info('Students retrieved: ' . $students->count());

            // Mengambil data presensi dari tabel attendances
            if ($students->count() > 0) {
                $attendanceData = Attendance::select('id_sertifikat', 'status as status_kehadiran', DB::raw('count(*) as count'))
                                            ->whereIn('id_sertifikat', $students->pluck('id_sertifikat'))
                                            ->groupBy('id_sertifikat', 'status')
                                            ->get()
                                            ->groupBy('id_sertifikat');
            } else {
                $attendanceData = collect();
            }

            // Memformat data untuk DataTables
            $formattedData = $students->map(function ($student) use ($attendanceData) {
                $hadir = $attendanceData->get($student->id_sertifikat)?->where('status_kehadiran', 'Hadir')->first()?->count ?? 0;
                $izin = $attendanceData->get($student->id_sertifikat)?->where('status_kehadiran', 'Izin')->first()?->count ?? 0;
                $sakit = $attendanceData->get($student->id_sertifikat)?->where('status_kehadiran', 'Sakit')->first()?->count ?? 0;
                $alpa = $attendanceData->get($student->id_sertifikat)?->where('status_kehadiran', 'Alpa')->first()?->count ?? 0;

                // Menambahkan keterangan presensi ke objek siswa
                $student->presensi = "Hadir: {$hadir}, Izin: {$izin}, Sakit: {$sakit}, Alpa: {$alpa}";
                
                return $student;
            });

            \Log::info('Response prepared successfully');

            // Mengembalikan respons JSON
            return response()->json([
                'draw' => intval($draw),
                'recordsTotal' => intval($totalRecords),
                'recordsFiltered' => intval($recordsFiltered),
                'data' => $formattedData,
            ]);
            
        } catch (\Exception $e) {
            // Log error untuk debugging
            \Log::error('DataTables Error in MentorAttendanceController: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            \Log::error('Line: ' . $e->getLine());
            \Log::error('File: ' . $e->getFile());
            
            return response()->json([
                'draw' => intval($request->input('draw', 0)),
                'recordsTotal' => 0,
                'recordsFiltered' => 0,
                'data' => [],
                'error' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 200); // Ubah ke 200 agar DataTables bisa parse error message
        }
    }
    
    public function portofolio()
{
    // Ambil semua portofolio dengan data siswa dan sertifikat (relasi)
    $portofolios = Portofolio::with(['user', 'sertifikatPKL'])->get();

    // Kirim ke view
    return view('mentor.portofolio', compact('portofolios'));
}

//absensi history
public function getAttendanceHistory($id_sertifikat, Request $request)
{
    $bulan = $request->get('bulan'); // format "01".."12"

    $query = Attendance::where('id_sertifikat', $id_sertifikat);

    if ($bulan) {
        $query->whereMonth('created_at', $bulan);
    }

    $history = $query->orderBy('created_at', 'desc')
                ->get(['status', 'created_at', 'notes']);

    return response()->json($history);
}

}
