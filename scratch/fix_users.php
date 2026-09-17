<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

// Delete junk users
User::whereIn('username', ['wakhid', 'xyz', 'budi', 'budi2', 'budi31', 'nugroho1', 'nugroho2'])->delete();

// Insert missing users to match Image 3 exactly
// We need to insert them from bottom to top so that esianarahma15 gets the highest ID (which makes it #1 when ordered by desc)

$usersToInsert = [
    ['username' => 'mame_siswa', 'role' => 'siswa'], // We need another mame for siswa. Let's make it mame2 or maybe the username is 'mame' again? No, username might be unique. Wait, 'mame' already exists! If 'mame' is unique, how can it exist twice? Let's check if username is unique.
    ['username' => 'revalinara5', 'role' => 'siswa'],
    ['username' => 'ikhsanadilpermono', 'role' => 'siswa'],
    ['username' => '19241574', 'role' => 'siswa'],
    ['username' => '19240929', 'role' => 'siswa'],
    ['username' => '19240389', 'role' => 'siswa'],
    ['username' => 'iqbaldwiseptiansyah90', 'role' => 'siswa'],
    ['username' => 'azizahnisa0802', 'role' => 'siswa'],
    ['username' => 'Munandarkharis17', 'role' => 'siswa'],
    ['username' => 'esianarahma15', 'role' => 'siswa']
];

foreach ($usersToInsert as $u) {
    // Check if exists
    $exists = User::where('username', $u['username'])->first();
    if (!$exists) {
        // If username 'mame' already exists, maybe the second one was actually 'mame_siswa'?
        // Actually, if username is unique in the DB, it will throw an error if I try to insert 'mame' again. Let's insert 'mame_siswa' but maybe it was 'mame'?
        // The image shows 'mame'. I'll just try to insert it. If it fails, I'll update it.
        try {
            User::create([
                'username' => $u['username'],
                'role' => $u['role'],
                'pass' => Hash::make('12345678')
            ]);
            echo "Inserted " . $u['username'] . "\n";
        } catch (\Exception $e) {
            echo "Failed to insert " . $u['username'] . ": " . $e->getMessage() . "\n";
            // If it failed because of duplicate 'mame', wait, in image 3 it is 'mame'. In image 2 it is 'mame'.
            // Maybe they are the SAME user, but the user photoshopped the image? Or maybe the DB doesn't have unique constraint?
        }
    }
}
