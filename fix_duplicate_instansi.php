<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== FIXING DUPLICATE INSTANSI ===\n\n";

// 1. Hapus data duplikat lama (yang tanpa alias dalam kurung)
$oldUntidar = DB::table('instansi')->where('nama', 'Universitas Tidar')->first();
$oldUmy = DB::table('instansi')->where('nama', 'Universitas Muhammadiyah Yogyakarta')->first();

if ($oldUntidar) {
    echo "Menghapus duplikat: Universitas Tidar (ID: {$oldUntidar->id})\n";
    
    // Update referensi di tabel lain jika ada
    $newUntidar = DB::table('instansi')->where('nama', 'Universitas Tidar (UNTIDAR)')->first();
    if ($newUntidar) {
        DB::table('tbl_sertifikat_pkl')->where('instansi_id', $oldUntidar->id)->update(['instansi_id' => $newUntidar->id]);
        DB::table('pengajuan_magang')->where('instansi_id', $oldUntidar->id)->update(['instansi_id' => $newUntidar->id]);
    }
    
    DB::table('instansi')->where('id', $oldUntidar->id)->delete();
    echo "✓ Berhasil dihapus\n\n";
}

if ($oldUmy) {
    echo "Menghapus duplikat: Universitas Muhammadiyah Yogyakarta (ID: {$oldUmy->id})\n";
    
    // Update referensi di tabel lain jika ada
    $newUmy = DB::table('instansi')->where('nama', 'Universitas Muhammadiyah Yogyakarta (UMY)')->first();
    if ($newUmy) {
        DB::table('tbl_sertifikat_pkl')->where('instansi_id', $oldUmy->id)->update(['instansi_id' => $newUmy->id]);
        DB::table('pengajuan_magang')->where('instansi_id', $oldUmy->id)->update(['instansi_id' => $newUmy->id]);
    }
    
    DB::table('instansi')->where('id', $oldUmy->id)->delete();
    echo "✓ Berhasil dihapus\n\n";
}

echo "=== SELESAI ===\n";
echo "Silakan refresh halaman Lembaga Pendidikan di browser.\n";
