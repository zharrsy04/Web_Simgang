<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UpdatePasswords extends Command
{
    protected $signature = 'passwords:update';
    protected $description = 'Update passwords to use Bcrypt hash';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $users = User::all();

        foreach ($users as $user) {
            // Periksa apakah password sudah di-hash dengan Bcrypt
            // Cek apakah password mengandung prefix bcrypt $2y$ atau $2a$
            if (!preg_match('/^\$2[ayb]\$/', $user->pass)) {
                $user->pass = Hash::make($user->pass); // Hash password menggunakan Bcrypt
                $user->save();
                $this->info('Updated password for user: ' . $user->username);
            }
        }

        $this->info('Passwords updated successfully.');
    }
}
