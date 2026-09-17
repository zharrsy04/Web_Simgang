<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$students = App\Models\SertifikatPKL::where('asal_sekolah', 'LIKE', '%Kawunganten%')
    ->orWhere('asal_sekolah', 'LIKE', '%Cilacap%')
    ->orWhere('asal_sekolah', 'LIKE', '%UNS%')
    ->get();

foreach ($students as $s) {
    echo "Nama: '{$s->nama_lengkap}' | Asal Sekolah: '{$s->asal_sekolah}' | Instansi ID: '{$s->instansi_id}'\n";
}
