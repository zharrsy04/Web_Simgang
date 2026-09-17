<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Update user yang belum punya email (mentor lama)
        DB::table('tbl_login')
            ->whereNull('email')
            ->orWhere('email', '')
            ->update([
                'email' => DB::raw("CONCAT(username, '@simgang.local')"),
                'nama' => DB::raw("CONCAT('User ', username)"),
            ]);
        
        // Khusus untuk mentor1, set email yang proper
        DB::table('tbl_login')
            ->where('username', 'mentor1')
            ->update([
                'email' => 'mentor@simgang.local',
                'nama' => 'Mentor Administrator',
            ]);
    }

    public function down(): void
    {
        // Tidak perlu rollback karena ini adalah data migration
    }
};
