<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Modify the role enum to include 'sekretariat'
        DB::statement("ALTER TABLE tbl_login MODIFY COLUMN role ENUM('siswa','mentor','sekretariat') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert the enum back to original
        DB::statement("ALTER TABLE tbl_login MODIFY COLUMN role ENUM('siswa','mentor') NOT NULL");
    }
};
