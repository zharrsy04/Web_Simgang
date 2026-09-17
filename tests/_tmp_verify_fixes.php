<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\SertifikatPKL;
use App\Models\User;
use App\Models\Penilaian;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\PenilaianController;
use Illuminate\Support\Facades\Auth;

echo "=== TEST 1: siswaaktif filter after store logic ===\n";

$today = Carbon::today();
$nim = 'TEST-SA-' . time();

$data = [
    'sts_kadis' => 1,
    'nama_lengkap' => 'Test Peserta Aktif',
    'asal_sekolah' => 'SMK Test',
    'nim_nis' => $nim,
    'tempat_lahir' => 'Cilacap',
    'tgl_lahir' => '2005-01-01',
    'jurusan' => 'RPL',
    'tgl_mulai' => $today->copy()->addDays(60)->format('Y-m-d'),
    'tgl_selesai' => $today->copy()->subDays(5)->format('Y-m-d'),
    'tgl_sertifikat' => $today->format('Y-m-d'),
    'nm_pembimbing' => 'Pembimbing Test',
    'nik_pembimbing' => '123456789',
    'bidang' => 'Bidang Informatika',
    'jabatan_pembimbing' => 'Mentor',
    'nm_kadis' => 'Kadis Test',
    'nip_kadis' => '987654321',
    'jabatan_pegawai' => 'Kepala Dinas',
    'aprove_pembimbing' => 1,
    'approve_kadis' => 1,
    'sts_diambil' => 1,
    'created_id' => 1,
];

$data['sts_diambil'] = 0;
$tglMulai = Carbon::parse($data['tgl_mulai']);
$tglSelesai = Carbon::parse($data['tgl_selesai']);
if ($tglMulai->gt($today)) {
    $data['tgl_mulai'] = $today->format('Y-m-d');
}
if ($tglSelesai->lt($today)) {
    $data['tgl_selesai'] = $today->copy()->addDays(30)->format('Y-m-d');
}

$s = SertifikatPKL::create($data);
$inAktif = SertifikatPKL::where('sts_diambil', false)
    ->whereDate('tgl_mulai', '<=', $today->format('Y-m-d'))
    ->whereDate('tgl_selesai', '>=', $today->format('Y-m-d'))
    ->where('id_sertifikat', $s->id_sertifikat)
    ->exists();

echo $inAktif ? "PASS: record appears in siswaaktif query\n" : "FAIL: record NOT in siswaaktif query\n";
$s->delete();

echo "\n=== TEST 2: sidebar HTML via HTTP for edit?from=sekolah & siswalulus ===\n";
$mentor = User::where('role', 'mentor')->first();
$sertifikat = SertifikatPKL::first();
if ($mentor && $sertifikat) {
    Auth::login($mentor);
    $kernel = app(Illuminate\Contracts\Http\Kernel::class);

    foreach (['sekolah' => 'Lembaga Pendidikan', 'siswalulus' => 'Lulus Magang'] as $from => $label) {
        $httpRequest = Request::create('/mentor/' . $sertifikat->id_sertifikat . '/edit?from=' . $from, 'GET');
        $httpRequest->setLaravelSession(app('session.store'));
        Auth::login($mentor);
        $response = $kernel->handle($httpRequest);
        $page = $response->getContent();

        $hasContextActive = (bool) preg_match('/<li class="active"[^>]*>[\s\S]{0,500}' . preg_quote($label, '/') . '/s', $page);
        $hasTambahActive = (bool) preg_match('/<li class="active"[^>]*>[\s\S]{0,200}Tambah Siswa/s', $page);

        echo ($hasContextActive ? 'PASS' : 'FAIL') . ": {$label} active for from={$from}\n";
        echo (!$hasTambahActive ? 'PASS' : 'FAIL') . ": Tambah Siswa NOT active for from={$from}\n";
        $kernel->terminate($httpRequest, $response);
    }
} else {
    echo "SKIP: missing mentor or sertifikat\n";
}

echo "\n=== TEST 3: PenilaianController store redirect ===\n";
$mentor = User::where('role', 'mentor')->first();
$sertifikat = SertifikatPKL::first();
if ($mentor && $sertifikat) {
    Auth::login($mentor);
    $aspek = 'TestAspek' . time();
    $req = Request::create('/penilaian', 'POST', [
        'aspek' => [$aspek],
        'nilai' => '85',
        'ket' => 'A',
        'id_sertifikat' => $sertifikat->id_sertifikat,
    ]);
    $req->setUserResolver(fn () => $mentor);
    app()->instance('request', $req);

    $redirect = app(PenilaianController::class)->store($req);
    $target = $redirect->getTargetUrl();
    echo (str_contains($target, '/penilaian') && !str_contains($target, 'siswaaktif') ? 'PASS' : 'FAIL') . ": store redirects to penilaian (got: $target)\n";
    Penilaian::where('aspek', $aspek)->delete();
} else {
    echo "SKIP: missing mentor or sertifikat\n";
}

echo "\nDone.\n";
