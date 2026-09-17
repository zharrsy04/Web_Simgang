<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * SekretariatAccountSeeder
 *
 * TUJUAN  : Membuat SATU akun role "sekretariat" sebagai akun bersama (shared).
 * CARA RUN: php artisan db:seed --class=SekretariatAccountSeeder
 *
 * CATATAN PENTING:
 * - Seeder ini HANYA dijalankan SEKALI di awal setup project / deployment baru.
 * - JANGAN masukkan seeder ini ke dalam daftar otomatis DatabaseSeeder::run()
 *   supaya tidak ikut berjalan saat `php artisan migrate:fresh --seed`.
 * - Logic firstOrCreate() sudah memastikan tidak ada duplikat meski
 *   seeder ini tidak sengaja dijalankan lebih dari sekali.
 * - Segera ganti password default setelah login pertama kali!
 */
class SekretariatAccountSeeder extends Seeder
{
    /**
     * Kredensial default akun Sekretariat.
     *
     * USERNAME : sekretariat
     * PASSWORD : Simgang@2024!   ← WAJIB diganti setelah login pertama
     * EMAIL    : sekretariat@diskominfo-cilacap.go.id
     */
    public function run(): void
    {
        // Cek: apakah akun sekretariat resmi sudah ada?
        $existing = User::where('email', 'sekretariat@diskominfo-cilacap.go.id')
            ->orWhere('username', 'sekretariat')
            ->first();

        if ($existing) {
            // Pastikan data email & password ter-sync sesuai default
            $existing->update([
                'username' => 'sekretariat',
                'email'    => 'sekretariat@diskominfo-cilacap.go.id',
                'nama'     => 'Admin Sekretariat Diskominfo',
                'pass'     => Hash::make('Simgang@2024!'),
                'role'     => 'sekretariat',
            ]);
            $this->command->info('✅ Akun Sekretariat utama berhasil diperbarui.');
            return;
        }

        // Belum ada → buat akun sekretariat
        $user = User::create([
            'username' => 'sekretariat',
            'email'    => 'sekretariat@diskominfo-cilacap.go.id',
            'nama'     => 'Admin Sekretariat Diskominfo',
            'pass'     => Hash::make('Simgang@2024!'),
            'role'     => 'sekretariat',
            'bidang'   => null,
        ]);

        $this->command->info('✅ Akun Sekretariat berhasil dibuat:');
        $this->command->table(
            ['Field', 'Value'],
            [
                ['Username', $user->username],
                ['Email', $user->email],
                ['Nama', $user->nama],
                ['Role', $user->role],
                ['Password Default', 'Simgang@2024!'],
                ['', '⚠  SEGERA GANTI PASSWORD SETELAH LOGIN PERTAMA!'],
            ]
        );
    }
}
