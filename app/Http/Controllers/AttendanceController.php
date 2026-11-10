<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\SertifikatPKL;
use App\Models\MentorSiswa;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    /**
     * Koordinat dan Konfigurasi Kantor Diskominfo Kabupaten Cilacap
     * Alamat: Jl. Sindoro No. 36, Sidanegara, Cilacap Tengah, Kabupaten Cilacap
     */
    public const OFFICE_LAT = -7.722072;
    public const OFFICE_LNG = 109.008530;
    public const OFFICE_RADIUS_METERS = 200; // Maksimal 200 meter (mencakup seluruh kompleks gedung Diskominfo & deviasi indoor)
    public const LATE_TIME_TOLERANCE = '07:40:00'; // Batas toleransi jam masuk 07:40 WIB

    /**
     * Ambil konfigurasi GPS kantor Diskominfo Cilacap
     */
    public static function getOfficeCoordinates()
    {
        return [
            'latitude' => (float) env('ATTENDANCE_OFFICE_LAT', self::OFFICE_LAT),
            'longitude' => (float) env('ATTENDANCE_OFFICE_LNG', self::OFFICE_LNG),
            'radius' => (int) env('ATTENDANCE_OFFICE_RADIUS', self::OFFICE_RADIUS_METERS),
            'late_time' => env('ATTENDANCE_LATE_TIME', self::LATE_TIME_TOLERANCE),
            'strict_gps' => filter_var(env('ATTENDANCE_STRICT_GPS', false), FILTER_VALIDATE_BOOLEAN),
        ];
    }

    /**
     * Hitung jarak dua titik koordinat GPS menggunakan formula Haversine (hasil dalam meter)
     */
    public static function calculateDistanceInMeters($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371000; // Radius bumi dalam meter
        $dLat = deg2rad((float)$lat2 - (float)$lat1);
        $dLon = deg2rad((float)$lon2 - (float)$lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad((float)$lat1)) * cos(deg2rad((float)$lat2)) *
             sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        return (int) round($earthRadius * $c);
    }

    /**
     * Halaman Absensi Harian Siswa
     */
    public function siswaIndex()
    {
        $user = Auth::user();
        if ($user->role !== 'siswa') {
            abort(403, 'Akses khusus untuk siswa.');
        }

        // Cari data magang siswa
        $siswa = SertifikatPKL::getByUser($user);

        $mentorNama = 'Belum Ditentukan';
        $mentor = null;
        $today = now()->format('Y-m-d');
        $attendanceToday = null;
        $recentAttendances = collect();
        $totalHadir = 0;

        if ($siswa) {
            // Cari data mentor pembimbing
            $mentorSiswa = MentorSiswa::where('siswa_id', $siswa->id_sertifikat)
                ->where('is_active', true)
                ->with('mentor')
                ->first();

            if ($mentorSiswa && $mentorSiswa->mentor) {
                $mentor = $mentorSiswa->mentor;
                $mentorNama = $mentor->nama;
            } elseif ($siswa->created_id) {
                $creator = User::where('id', $siswa->created_id)->where('role', 'mentor')->first();
                if ($creator) {
                    $mentor = $creator;
                    $mentorNama = $creator->nama;
                } elseif (!empty($siswa->nm_pembimbing)) {
                    $mentorNama = $siswa->nm_pembimbing;
                }
            } elseif (!empty($siswa->nm_pembimbing)) {
                $mentorNama = $siswa->nm_pembimbing;
            }

            // Cek absensi hari ini
            $attendanceToday = Attendance::where('id_sertifikat', $siswa->id_sertifikat)
                ->where('date', $today)
                ->first();

            // Riwayat absensi siswa
            $recentAttendances = Attendance::where('id_sertifikat', $siswa->id_sertifikat)
                ->orderBy('date', 'desc')
                ->take(15)
                ->get();

            // Total hari hadir
            $totalHadir = Attendance::where('id_sertifikat', $siswa->id_sertifikat)
                ->where('status', 'Hadir')
                ->count();
        }

        $cfg = self::getOfficeCoordinates();
        $currentTime = now('Asia/Jakarta')->format('H:i:s');
        $isPastLateTolerance = $currentTime > $cfg['late_time'];

        return view('siswa.absensi.index', compact(
            'siswa',
            'mentorNama',
            'mentor',
            'today',
            'attendanceToday',
            'recentAttendances',
            'totalHadir',
            'cfg',
            'currentTime',
            'isPastLateTolerance'
        ));
    }

    /**
     * Simpan Absensi Masuk Siswa
     */
    public function siswaStore(Request $request)
    {
        $user = Auth::user();
        if ($user->role !== 'siswa') {
            abort(403, 'Hanya siswa yang dapat melakukan absensi.');
        }

        $siswa = SertifikatPKL::getByUser($user);
        if (!$siswa) {
            return redirect()->route('siswa.absensi.index')
                ->with('error', 'Data magang siswa belum ditemukan atau belum disetujui.');
        }

        // Tanggal dan waktu WAJIB dari server WIB
        $now = now('Asia/Jakarta');
        $serverDate = $now->format('Y-m-d');
        $serverTime = $now->format('H:i:s');

        // Validasi: Tidak boleh absen 2 kali pada tanggal yang sama
        $alreadyAttended = Attendance::where('id_sertifikat', $siswa->id_sertifikat)
            ->where('date', $serverDate)
            ->first();

        if ($alreadyAttended) {
            return redirect()->route('siswa.absensi.index')
                ->with('warning', 'Anda sudah melakukan absensi masuk hari ini pada pukul ' . ($alreadyAttended->time ? substr($alreadyAttended->time, 0, 5) : '-') . ' WIB.');
        }

        // Status yang diizinkan (default: Hadir)
        $status = $request->input('status', 'Hadir');
        if (!in_array($status, ['Hadir', 'Sakit', 'Izin'])) {
            $status = 'Hadir';
        }

        $lat = $request->input('latitude');
        $lng = $request->input('longitude');
        $notes = $request->input('notes');

        $cfg = self::getOfficeCoordinates();
        $distance = null;
        $isLate = false;
        $locationName = 'Di luar area kantor';

        // Validasi Geofencing GPS untuk Status Hadir
        if ($status === 'Hadir') {
            if (empty($lat) || empty($lng)) {
                if ($cfg['strict_gps']) {
                    return redirect()->route('siswa.absensi.index')
                        ->with('error', 'Izin lokasi (GPS) ditolak atau tidak aktif. Mohon izinkan akses GPS di browser Anda untuk melakukan absensi Hadir.');
                }
                $locationName = "Lokasi tidak terdeteksi (GPS Non-aktif)";
                $isLate = ($serverTime > $cfg['late_time']);
            } else {
                // Hitung jarak ke kantor Diskominfo Cilacap
                $distance = self::calculateDistanceInMeters($lat, $lng, $cfg['latitude'], $cfg['longitude']);

                // Jika strict_gps aktif, tolak jika jarak melebihi batas radius (100 meter)
                if ($cfg['strict_gps'] && $distance > $cfg['radius']) {
                    return redirect()->route('siswa.absensi.index')
                        ->with('error', "Gagal Absen: Lokasi Anda terdeteksi berjarak {$distance} meter dari kantor Diskominfo Cilacap. Status Hadir wajib berada di area kantor (Maks. {$cfg['radius']} meter).");
                }

                if ($distance <= $cfg['radius']) {
                    $locationName = "Diskominfo Kab. Cilacap ({$distance}m)";
                } else {
                    $locationName = "Luar Area Kantor ({$distance}m)";
                }
                $isLate = ($serverTime > $cfg['late_time']);
            }
        } else {
            // Status Sakit / Izin
            if ($lat && $lng) {
                $distance = self::calculateDistanceInMeters($lat, $lng, $cfg['latitude'], $cfg['longitude']);
                $locationName = "Luar Kantor ({$distance}m)";
            } else {
                $locationName = "Lokasi tidak terdeteksi";
            }
            $isLate = false;
        }

        // Tentukan ID Mentor pembimbing
        $mentorId = null;
        $mentorSiswa = MentorSiswa::where('siswa_id', $siswa->id_sertifikat)
            ->where('is_active', true)
            ->first();

        if ($mentorSiswa && $mentorSiswa->mentor_id) {
            $mentorId = $mentorSiswa->mentor_id;
        } elseif (!empty($siswa->nm_pembimbing)) {
            $mentorUser = User::where('role', 'mentor')->where(function($q) use ($siswa) {
                $q->where('nama', $siswa->nm_pembimbing);
                if (stripos($siswa->nm_pembimbing, 'Slamet Arifin') !== false) {
                    $q->orWhere('username', 'mentor1');
                }
            })->first();
            if ($mentorUser) {
                $mentorId = $mentorUser->id;
            }
        } elseif ($siswa->created_id && User::where('id', $siswa->created_id)->where('role', 'mentor')->where('username', '!=', 'sekretariat')->exists()) {
            $mentorId = $siswa->created_id;
        }

        // Fallback default mentor jika belum terisi
        if (!$mentorId) {
            $defaultMentor = User::where('role', 'mentor')->where('username', 'mentor1')->first();
            if ($defaultMentor) {
                $mentorId = $defaultMentor->id;
            }
        }

        try {
            Attendance::create([
                'id_sertifikat' => $siswa->id_sertifikat,
                'mentor_id'     => $mentorId,
                'date'          => $serverDate,
                'time'          => $serverTime,
                'status'        => $status,
                'is_late'       => $isLate,
                'location'      => $locationName,
                'latitude'      => $lat ? substr((string)$lat, 0, 30) : null,
                'longitude'     => $lng ? substr((string)$lng, 0, 30) : null,
                'notes'         => $notes,
                'ip_address'    => $request->ip(),
                'user_agent'    => substr($request->userAgent() ?? '', 0, 500),
            ]);

            $lateMsg = $isLate ? ' (Tercatat Terlambat)' : '';
            return redirect()->route('siswa.absensi.index')
                ->with('success', "Absensi masuk ({$status}) berhasil dicatat pada pukul " . substr($serverTime, 0, 5) . " WIB{$lateMsg}.");
        } catch (\Illuminate\Database\QueryException $e) {
            // Tangani duplicate key secara graceful
            if (isset($e->errorInfo[1]) && $e->errorInfo[1] == 1062) {
                return redirect()->route('siswa.absensi.index')
                    ->with('warning', 'Anda sudah melakukan absensi hari ini.');
            }
            Log::error('Gagal mencatat absensi: ' . $e->getMessage());
            return redirect()->route('siswa.absensi.index')
                ->with('error', 'Absensi gagal disimpan. Silakan coba lagi.');
        } catch (\Exception $e) {
            Log::error('Gagal mencatat absensi: ' . $e->getMessage());
            return redirect()->route('siswa.absensi.index')
                ->with('error', 'Absensi gagal disimpan. Silakan coba lagi.');
        }
    }

    /**
     * Halaman Absensi Siswa Bimbingan untuk Mentor
     */
    public function mentorIndex(Request $request)
    {
        $user = Auth::user();
        if (!in_array($user->role, ['mentor', 'sekretariat'])) {
            abort(403, 'Akses terbatas untuk mentor dan sekretariat.');
        }

        // Ambil daftar ID siswa bimbingan
        if ($user->role === 'mentor') {
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
            
            // Jika belum ada siswa bimbingan sama sekali
            if (empty($studentIds)) {
                $studentIds = [0];
            }
        } else {
            // Sekretariat dapat melihat seluruh siswa aktif
            $studentIds = SertifikatPKL::where('sts_diambil', 0)
                ->whereDate('tgl_selesai', '>=', now()->toDateString())
                ->pluck('id_sertifikat')
                ->toArray();
        }

        // Filter parameter
        $selectedDate = $request->input('tanggal', now()->format('Y-m-d'));
        $search = $request->input('search');
        $statusFilter = $request->input('status');

        $query = SertifikatPKL::whereIn('id_sertifikat', $studentIds);

        // Hanya tampilkan siswa yang aktif PKL (tidak menampilkan data alumni lama)
        if (!$request->has('semua_periode')) {
            $query->where('sts_diambil', 0)
                  ->whereDate('tgl_selesai', '>=', now()->toDateString());
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('nim_nis', 'like', "%{$search}%")
                  ->orWhere('asal_sekolah', 'like', "%{$search}%")
                  ->orWhere('bidang', 'like', "%{$search}%");
            });
        }

        $students = $query->orderBy('nama_lengkap', 'asc')->get();

        // Ambil data absensi pada tanggal terpilih
        $attendances = Attendance::whereIn('id_sertifikat', $students->pluck('id_sertifikat'))
            ->where('date', $selectedDate)
            ->get()
            ->keyBy('id_sertifikat');

        // Petakan status absensi ke setiap siswa
        $mappedStudents = $students->map(function ($student) use ($attendances) {
            $att = $attendances->get($student->id_sertifikat);
            $student->attendance_status = $att ? $att->status : 'Belum Absen';
            $student->attendance_time = $att && $att->time ? $att->time : '-';
            $student->attendance_time_out = $att && $att->time_out ? $att->time_out : null;
            $student->attendance_is_late = $att ? (bool)$att->is_late : false;
            $student->attendance_location = $att ? $att->location : '-';
            $student->attendance_notes = $att && $att->notes ? $att->notes : '-';
            $student->attendance_id = $att ? $att->id : null;
            return $student;
        });

        // Filter status jika dipilih
        if ($statusFilter && $statusFilter !== 'all') {
            $mappedStudents = $mappedStudents->filter(function ($s) use ($statusFilter) {
                return strcasecmp($s->attendance_status, $statusFilter) === 0;
            });
        }

        // Hitung statistik
        $totalBimbingan = $students->count();
        $totalHadir = $attendances->where('status', 'Hadir')->count();
        $totalBelumAbsen = max(0, $totalBimbingan - $attendances->count());

        return view('mentor.absensi.index', compact(
            'mappedStudents',
            'selectedDate',
            'search',
            'statusFilter',
            'totalBimbingan',
            'totalHadir',
            'totalBelumAbsen'
        ));
    }

    /**
     * Riwayat Absensi Siswa Tertentu untuk Mentor
     */
    public function mentorRiwayat(Request $request, $idSertifikat)
    {
        $user = Auth::user();
        if (!in_array($user->role, ['mentor', 'sekretariat'])) {
            abort(403, 'Akses terbatas untuk mentor.');
        }

        $siswa = SertifikatPKL::findOrFail($idSertifikat);

        // Validasi apakah siswa ini bimbingan mentor yang sedang login
        if ($user->role === 'mentor') {
            $isMentoring = MentorSiswa::where('mentor_id', $user->id)
                ->where('siswa_id', $idSertifikat)
                ->where('is_active', true)
                ->exists();

            $pembimbingNames = [$user->nama];
            if ($user->id == 3 || $user->username === 'mentor1') {
                $pembimbingNames[] = 'Slamet Arifin, A.Md. T.';
                $pembimbingNames[] = 'Slamet Arifin, A.Md.T.';
                $pembimbingNames[] = 'Slamet Arifin,A.md.T.';
                $pembimbingNames[] = 'Slamet Arifin';
                $pembimbingNames[] = 'SLAMET ARIFIN, A.Md. T.';
            }

            $isCreatorOrPembimbing = ($siswa->created_id == $user->id) || in_array($siswa->nm_pembimbing, $pembimbingNames);

            if (!$isMentoring && !$isCreatorOrPembimbing) {
                abort(403, 'Anda hanya dapat melihat riwayat absensi siswa bimbingan Anda.');
            }
        }

        // Filter
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $status = $request->input('status');

        $query = Attendance::where('id_sertifikat', $idSertifikat);

        if ($startDate) {
            $query->whereDate('date', '>=', $startDate);
        }
        if ($endDate) {
            $query->whereDate('date', '<=', $endDate);
        }
        if ($status && $status !== 'all') {
            $query->where('status', $status);
        }

        $attendances = $query->orderBy('date', 'desc')->paginate(20)->withQueryString();

        // Hitung statistik keseluruhan siswa ini
        $allRecords = Attendance::where('id_sertifikat', $idSertifikat)->get();
        $totalHadir = $allRecords->where('status', 'Hadir')->count();
        $totalIzin  = $allRecords->where('status', 'Izin')->count();
        $totalSakit = $allRecords->where('status', 'Sakit')->count();
        $totalAlpa  = $allRecords->where('status', 'Alpa')->count();

        return view('mentor.absensi.riwayat', compact(
            'siswa',
            'attendances',
            'startDate',
            'endDate',
            'status',
            'totalHadir',
            'totalIzin',
            'totalSakit',
            'totalAlpa'
        ));
    }
}
