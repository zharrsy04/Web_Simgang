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
        Schema::table('task_submissions', function (Blueprint $table) {
            // Timestamp saat mentor membuka/memeriksa pengumpulan tugas ini
            // NULL = belum dilihat mentor, isi = sudah dilihat
            $table->timestamp('viewed_at')->nullable()->after('graded_at')
                ->comment('Timestamp saat mentor melihat pengumpulan ini. NULL = belum dilihat');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('task_submissions', function (Blueprint $table) {
            $table->dropColumn('viewed_at');
        });
    }
};
