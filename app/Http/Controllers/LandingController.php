<?php
// app/Http/Controllers/LandingController.php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\SertifikatPKL;
use Illuminate\Support\Facades\Auth;
use App\Constants\BidangConstants;

class LandingController extends Controller
{
   public function home()
{
    $today = now()->format('Y-m-d');

    // jumlah siswa aktif
    $siswaAktif = SertifikatPKL::where('sts_diambil', false)
        ->whereDate('tgl_mulai', '<=', $today)
        ->whereDate('tgl_selesai', '>=', $today)
        ->count();

    // jumlah siswa menunggu magang
    $siswaMenunggu = SertifikatPKL::whereDate('tgl_mulai', '>', $today)
        ->count();

    // jumlah sekolah unik (institusi)
    $sekolah = SertifikatPKL::select('asal_sekolah')
        ->distinct()
        ->count('asal_sekolah');

    // jumlah siswa lulus
    $siswaLulus = SertifikatPKL::where('sts_diambil', true)
        ->whereDate('tgl_selesai', '<', $today)
        ->count();

    // HITUNG SISA KUOTA 
    // Tentukan kapasitas maksimal (Ganti angka sesuai kapasitas asli Diskominfo Cilacap)
    $kapasitasMaksimal = 15; 
    
    // Rumus: Kapasitas - Siswa yang sedang aktif sekarang
    $sisaKuota = $kapasitasMaksimal - $siswaAktif;
    
    // Pastikan tidak minus (kalau over kuota, tetap 0)
    if ($sisaKuota < 0) {
        $sisaKuota = 0;
    }


    // statistik per bidang
    $data = SertifikatPKL::select('bidang', DB::raw('count(*) as total'))
        ->where('sts_diambil', false)
        ->whereDate('tgl_mulai', '<=', $today)
        ->whereDate('tgl_selesai', '>=', $today)
        ->groupBy('bidang')
        ->get();

    // data bidang untuk landing page
    $bidangInfo = BidangConstants::getBidangInfo();

    return view('landing.home', compact(
        'siswaAktif', 'siswaMenunggu', 'sekolah', 'siswaLulus', 'data', 
        'sisaKuota', 'kapasitasMaksimal', 'bidangInfo'
    ));
}

    public function portfolio()
    {
        return view('landing.portfolio');
    }

    public function contact()
    {
        return view('landing.contact');
    }
}