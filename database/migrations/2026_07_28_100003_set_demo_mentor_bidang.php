<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Set mentor1 sebagai Bidang Informatika
        DB::table('tbl_login')
            ->where('username', 'mentor1')
            ->update(['bidang' => 'Bidang Informatika']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert bidang mentor1 ke null
        DB::table('tbl_login')
            ->where('username', 'mentor1')
            ->update(['bidang' => null]);
    }
};
