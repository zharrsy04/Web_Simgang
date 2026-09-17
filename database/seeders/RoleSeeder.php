<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class RoleSeeder extends Seeder
{
    /**
     * Seed test users with different roles
     */
    public function run(): void
    {
        // Create test users for each role if they don't exist
        
        // Test Siswa
        User::firstOrCreate(
            ['email' => 'siswa@example.com'],
            [
                'username' => 'siswa_test',
                'email' => 'siswa@example.com',
                'nama' => 'Siswa Test',
                'pass' => Hash::make('password123'),
                'role' => 'siswa',
            ]
        );

        // Test Mentor
        User::firstOrCreate(
            ['email' => 'mentor@example.com'],
            [
                'username' => 'mentor_test',
                'email' => 'mentor@example.com',
                'nama' => 'Mentor Test',
                'pass' => Hash::make('password123'),
                'role' => 'mentor',
            ]
        );

        // Test Sekretariat - IMPORTANT: This is the main focus
        User::firstOrCreate(
            ['email' => 'sekretariat@example.com'],
            [
                'username' => 'sekretariat_test',
                'email' => 'sekretariat@example.com',
                'nama' => 'Admin Sekretariat',
                'pass' => Hash::make('password123'),
                'role' => 'sekretariat',
            ]
        );
    }
}
