<?php
/**
 * Script untuk mengecek relasi data siswa-sertifikat-penilaian
 * Jalankan dengan: php check_nilai_data.php
 */

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "\n=== CEK DATA NILAI SERTIFIKAT ===\n\n";

try {
    // 1. Cek jumlah siswa dengan role siswa
    echo "1. JUMLAH USER SISWA:\n";
    echo str_repeat("-", 60) . "\n";
    $siswaCount = DB::table('users')->where('role', 'siswa')->count();
    echo "Total user dengan role 'siswa': {$siswaCount}\n\n";
    
    // 2. Cek sample user siswa
    echo "2. SAMPLE 5 USER SISWA:\n";
    echo str_repeat("-", 60) . "\n";
    $siswaUsers = DB::table('users')
        ->where('role', 'siswa')
        ->select('id', 'username', 'name', 'email')
        ->limit(5)
        ->get();
    
    foreach ($siswaUsers as $user) {
        echo "- ID: {$user->id}, Username: {$user->username}, Name: {$user->name}\n";
    }
    echo "\n";
    
    // 3. Cek relasi user -> sertifikat
    echo "3. CEK RELASI USER -> SERTIFIKAT:\n";
    echo str_repeat("-", 60) . "\n";
    
    foreach ($siswaUsers as $user) {
        echo "\nUser ID {$user->id} ({$user->username}):\n";
        
        // Cari sertifikat berdasarkan created_id
        $sertByCreatedId = DB::table('tbl_sertifikat_pkl')
            ->where('created_id', $user->id)
            ->first();
        
        // Cari sertifikat berdasarkan nim_nis
        $sertByNimNis = DB::table('tbl_sertifikat_pkl')
            ->where('nim_nis', $user->username)
            ->first();
        
        $sertifikat = $sertByCreatedId ?? $sertByNimNis;
        
        if ($sertifikat) {
            echo "  ✅ Sertifikat DITEMUKAN:\n";
            echo "     ID Sertifikat: {$sertifikat->id_sertifikat}\n";
            echo "     Nama: {$sertifikat->nama_lengkap}\n";
            echo "     NIM/NIS: {$sertifikat->nim_nis}\n";
            echo "     Created ID: {$sertifikat->created_id}\n";
            
            // Cek penilaian
            $penilaianCount = DB::table('tbl_penilaian')
                ->where('id_sertifikat', $sertifikat->id_sertifikat)
                ->count();
            
            echo "     Jumlah Penilaian: {$penilaianCount}\n";
            
            if ($penilaianCount > 0) {
                $penilaians = DB::table('tbl_penilaian')
                    ->where('id_sertifikat', $sertifikat->id_sertifikat)
                    ->select('aspek', 'nilai', 'ket')
                    ->limit(3)
                    ->get();
                
                echo "     Sample Penilaian:\n";
                foreach ($penilaians as $p) {
                    echo "       - {$p->aspek}: {$p->nilai} ({$p->ket})\n";
                }
            }
        } else {
            echo "  ❌ Sertifikat TIDAK DITEMUKAN\n";
            echo "     (dicari berdasarkan created_id={$user->id} atau nim_nis={$user->username})\n";
        }
    }
    
    echo "\n\n";
    
    // 4. Statistik keseluruhan
    echo "4. STATISTIK KESELURUHAN:\n";
    echo str_repeat("-", 60) . "\n";
    
    $totalSertifikat = DB::table('tbl_sertifikat_pkl')->count();
    $totalPenilaian = DB::table('tbl_penilaian')->count();
    $sertifikatWithPenilaian = DB::table('tbl_penilaian')
        ->distinct('id_sertifikat')
        ->count('id_sertifikat');
    
    echo "Total Sertifikat PKL: {$totalSertifikat}\n";
    echo "Total Record Penilaian: {$totalPenilaian}\n";
    echo "Sertifikat yang Punya Nilai: {$sertifikatWithPenilaian}\n\n";
    
    // 5. Cek sample sertifikat yang punya nilai
    echo "5. SAMPLE SERTIFIKAT YANG SUDAH DINILAI:\n";
    echo str_repeat("-", 60) . "\n";
    
    $sertifikatWithNilai = DB::table('tbl_sertifikat_pkl as s')
        ->join('tbl_penilaian as p', 's.id_sertifikat', '=', 'p.id_sertifikat')
        ->select('s.id_sertifikat', 's.nama_lengkap', 's.nim_nis', 's.created_id', 
                 DB::raw('COUNT(p.id) as jumlah_nilai'),
                 DB::raw('AVG(p.nilai) as rata_rata'))
        ->groupBy('s.id_sertifikat', 's.nama_lengkap', 's.nim_nis', 's.created_id')
        ->limit(5)
        ->get();
    
    foreach ($sertifikatWithNilai as $s) {
        echo "\n- Sertifikat ID: {$s->id_sertifikat}\n";
        echo "  Nama: {$s->nama_lengkap}\n";
        echo "  NIM/NIS: {$s->nim_nis}\n";
        echo "  Created ID: {$s->created_id}\n";
        echo "  Jumlah Penilaian: {$s->jumlah_nilai}\n";
        echo "  Rata-rata Nilai: " . number_format($s->rata_rata, 2) . "\n";
        
        // Cek apakah ada user dengan created_id ini
        $user = DB::table('users')->where('id', $s->created_id)->first();
        if ($user) {
            echo "  ✅ User EXISTS: {$user->username} (role: {$user->role})\n";
        } else {
            echo "  ❌ User NOT FOUND untuk created_id={$s->created_id}\n";
            
            // Cek apakah ada user dengan username=nim_nis
            $userByNim = DB::table('users')->where('username', $s->nim_nis)->first();
            if ($userByNim) {
                echo "  ⚠️  TAPI ada user dengan username={$s->nim_nis} (ID: {$userByNim->id})\n";
                echo "     **INI KEMUNGKINAN MASALAHNYA!**\n";
            }
        }
    }
    
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}

echo "\n=== SELESAI ===\n\n";
