<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Task;
use App\Models\MentorSiswa;
use App\Models\SertifikatPKL;
use Illuminate\Database\Seeder;

class TugasDummySeeder extends Seeder
{
    /**
     * Seed dummy tasks for testing Daftar Tugas
     */
    public function run(): void
    {
        // Get mentors by username
        $mentorSekretariat = User::where('username', 'mentor_sekretariat')->first();
        $mentorInformatika = User::where('username', 'mentor_informatika')->first();
        $mentorStatistik = User::where('username', 'mentor_statistik')->first();

        if (!$mentorSekretariat || !$mentorInformatika) {
            $this->command->warn('Required mentors not found. Please run MentorDummySeeder first.');
            return;
        }

        // Get students from mentor-siswa relationships
        $mentorSiswa = MentorSiswa::with('siswa')->limit(4)->get();
        
        if ($mentorSiswa->isEmpty()) {
            $this->command->warn('No mentor-siswa relationships found. Please run MentorDummySeeder first.');
            return;
        }

        // Create 2 dummy tasks
        
        // Task 1: Active task (deadline not passed) - given by mentor to their own student
        $student1 = $mentorSiswa[0]->siswa;
        $mentor1 = $mentorSiswa[0]->mentor;
        if ($student1 && $mentor1) {
            Task::firstOrCreate(
                [
                    'sertifikat_pkl_id' => $student1->id_sertifikat,
                    'title' => 'Laporan Mingguan 1',
                ],
                [
                    'assigned_by' => $mentor1->id,
                    'description' => 'Buat laporan mingguan pertama tentang kegiatan magang yang telah dilakukan. Laporan harus mencakup: aktivitas harian, pembelajaran yang diperoleh, dan kendala yang dihadapi.',
                    'due_date' => now()->addDays(7), // 7 days from now
                    'status' => 'pending',
                    'is_read' => false,
                ]
            );

            $this->command->info("Task 1 created: Laporan Mingguan 1 for {$student1->nama_lengkap} by {$mentor1->nama} (Active)");
        }

        // Task 2: Overdue task (deadline passed) - given by mentor to their own student
        $student2 = $mentorSiswa[1]->siswa;
        $mentor2 = $mentorSiswa[1]->mentor;
        if ($student2 && $mentor2) {
            Task::firstOrCreate(
                [
                    'sertifikat_pkl_id' => $student2->id_sertifikat,
                    'title' => 'Dokumentasi Proyek',
                ],
                [
                    'assigned_by' => $mentor2->id,
                    'description' => 'Dokumentasikan seluruh proses pembuatan proyek yang dikerjakan selama magang. Termasuk screenshot, flowchart, dan penjelasan teknis.',
                    'due_date' => now()->subDays(3), // 3 days ago (overdue)
                    'status' => 'pending',
                    'is_read' => false,
                ]
            );

            $this->command->info("Task 2 created: Dokumentasi Proyek for {$student2->nama_lengkap} by {$mentor2->nama} (Overdue)");
        }

        $this->command->info('TugasDummySeeder completed successfully!');
    }
}
