<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menambahkan kolom tempat_lahir dan tgl_lahir ke tabel pengajuan_magang.
     * Kolom ini dibutuhkan oleh PengajuanMagangController::store() saat siswa
     * mengajukan permohonan magang.
     */
    public function up(): void
    {
        Schema::table('pengajuan_magang', function (Blueprint $table) {
            // Tambahkan tempat_lahir setelah jurusan jika belum ada
            if (!Schema::hasColumn('pengajuan_magang', 'tempat_lahir')) {
                $table->string('tempat_lahir', 100)->nullable()->after('jurusan');
            }

            // Tambahkan tgl_lahir setelah tempat_lahir jika belum ada
            if (!Schema::hasColumn('pengajuan_magang', 'tgl_lahir')) {
                $table->date('tgl_lahir')->nullable()->after('tempat_lahir');
            }
        });
    }

    /**
     * Rollback: hapus kolom yang ditambahkan.
     */
    public function down(): void
    {
        Schema::table('pengajuan_magang', function (Blueprint $table) {
            $table->dropColumn(['tempat_lahir', 'tgl_lahir']);
        });
    }
};
