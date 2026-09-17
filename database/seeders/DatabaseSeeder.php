<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * Seeder yang berjalan OTOMATIS saat `php artisan migrate:fresh --seed`:
     *   - RoleSeeder        : akun uji coba (siswa/mentor/sekretariat @example.com)
     *   - AnnouncementSeeder: data pengumuman awal
     *
     * Seeder MANUAL (jalankan SEKALI saat setup awal / deployment baru):
     *   php artisan db:seed --class=SekretariatAccountSeeder
     *   → Membuat 1 akun sekretariat produksi. Aman jika diulang (ada cek duplikat).
     *   → JANGAN tambahkan ke array $this->call() di bawah agar tidak
     *     otomatis berjalan di environment production.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            AnnouncementSeeder::class,
            InstansiSeeder::class,
            // SekretariatAccountSeeder::class, // ← JANGAN uncomment ini!
            //   Jalankan manual: php artisan db:seed --class=SekretariatAccountSeeder
        ]);
    }
}
