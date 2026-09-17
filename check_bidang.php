<?php

require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== DISTINCT BIDANG IN SERTIFIKAT ===\n";
foreach (App\Models\SertifikatPKL::select('bidang')->distinct()->pluck('bidang') as $b) {
    echo "- '{$b}'\n";
}

echo "=== DISTINCT BIDANG IN PENGAJUAN ===\n";
foreach (App\Models\PengajuanMagang::select('bidang')->distinct()->pluck('bidang') as $b) {
    echo "- '{$b}'\n";
}

echo "=== DISTINCT ROLE/BIDANG IN USERS ===\n";
foreach (App\Models\User::select('role', 'bidang')->distinct()->get() as $u) {
    echo "- Role: '{$u->role}' | Bidang: '{$u->bidang}'\n";
}
