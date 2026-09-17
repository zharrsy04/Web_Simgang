<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\MentorSiswa;
use App\Models\SertifikatPKL;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class MentorDummySeeder extends Seeder
{
    /**
     * Seed dummy mentors and their mentored students
     */
    public function run(): void
    {
        // Get some existing sertifikat to use as dummy students
        $dummyStudents = SertifikatPKL::limit(10)->get();
        
        if ($dummyStudents->isEmpty()) {
            $this->command->warn('No existing students found. Please create some students first.');
            return;
        }

        // Create 3 mentors for each bidang
        $mentors = [
            [
                'username' => 'mentor_sekretariat',
                'email' => 'mentor_sekretariat@example.com',
                'nama' => 'Budi Santoso',
                'bidang' => 'Sekretariat',
                'students' => [$dummyStudents[0], $dummyStudents[1]] ?? []
            ],
            [
                'username' => 'mentor_informatika',
                'email' => 'mentor_informatika@example.com',
                'nama' => 'Ani Wijaya',
                'bidang' => 'Bidang Informatika',
                'students' => [$dummyStudents[2], $dummyStudents[3]] ?? []
            ],
            [
                'username' => 'mentor_statistik',
                'email' => 'mentor_statistik@example.com',
                'nama' => 'Cahyo Pratama',
                'bidang' => 'Bidang Statistik dan Persandian',
                'students' => [$dummyStudents[4], $dummyStudents[5]] ?? []
            ]
        ];

        foreach ($mentors as $mentorData) {
            // Create or update mentor
            $mentor = User::firstOrCreate(
                ['username' => $mentorData['username']],
                [
                    'email' => $mentorData['email'],
                    'nama' => $mentorData['nama'],
                    'pass' => Hash::make('password123'),
                    'role' => 'mentor',
                    'bidang' => $mentorData['bidang'],
                ]
            );

            $this->command->info("Mentor created/updated: {$mentor->nama} ({$mentor->bidang})");

            // Assign students to mentor
            foreach ($mentorData['students'] as $student) {
                if ($student) {
                    MentorSiswa::firstOrCreate(
                        [
                            'mentor_id' => $mentor->id,
                            'siswa_id' => $student->id_sertifikat,
                        ],
                        [
                            'tanggal_bimbingan' => now(),
                            'keterangan' => 'Bimbingan aktif',
                            'is_active' => true,
                        ]
                    );

                    $this->command->info("  - Student assigned: {$student->nama_lengkap}");
                }
            }
        }

        $this->command->info('MentorDummySeeder completed successfully!');
    }
}
