<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Announcement;
use App\Models\User;
use Carbon\Carbon;

class AnnouncementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pastikan ada user untuk dijadikan creator
        $adminUser = User::first();
        
        if (!$adminUser) {
            // Jika tidak ada user, skip
            $this->command->info('Skipping AnnouncementSeeder - tidak ada user di database');
            return;
        }

        $announcements = [
            [
                'title' => 'Pendaftaran Magang Batch 3 Dibuka',
                'content' => 'Pendaftaran magang untuk batch 3 tahun 2025 telah dibuka. Siswa dapat mendaftar melalui sistem mulai tanggal 5 Juli 2025.',
                'type' => 'info',
                'published_at' => Carbon::now()->subDays(2),
                'created_by' => $adminUser->id,
            ],
            [
                'title' => 'Perubahan Jadwal Orientasi Magang',
                'content' => 'Jadwal orientasi magang batch 2 diundur dari tanggal 10 Juli menjadi 15 Juli 2025.',
                'type' => 'warning',
                'published_at' => Carbon::now()->subDays(5),
                'created_by' => $adminUser->id,
            ],
            [
                'title' => 'Sertifikat Magang Batch 1 Telah Siap',
                'content' => 'Sertifikat untuk siswa magang batch 1 telah selesai dicetak dan siap untuk diambil.',
                'type' => 'success',
                'published_at' => Carbon::now()->subDays(7),
                'created_by' => $adminUser->id,
            ],
        ];

        foreach ($announcements as $announcement) {
            Announcement::firstOrCreate(
                ['title' => $announcement['title']],
                $announcement
            );
        }

        $this->command->info('Berhasil membuat announcements.');
    }
}
