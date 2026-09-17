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
        Schema::table('tbl_sertifikat_pkl', function (Blueprint $table) {
            // Menambahkan kolom foto_profil_url sebagai string yang bisa nullable
            // Setelah kolom 'created_id' (sesuaikan jika Anda ingin posisi lain)
            $table->string('foto_profil_url')->nullable()->after('created_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_sertifikat_pkl', function (Blueprint $table) {
            // Saat rollback, hapus kolom foto_profil_url
            $table->dropColumn('foto_profil_url');
        });
    }
};