<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Perbaikan kolom di tbl_sertifikat_pkl:
     * 1. Perluas nim_nis dari varchar(10) ke varchar(100) agar mendukung NIM/NIS panjang.
     * 2. Jadikan tempat_lahir nullable agar record pengajuan lama (sebelum field tempat_lahir
     *    ditambahkan ke pengajuan_magang) tetap bisa di-approve tanpa error NOT NULL constraint.
     */
    public function up(): void
    {
        Schema::table('tbl_sertifikat_pkl', function (Blueprint $table) {
            // Perluas nim_nis agar tidak "Data too long" saat siswa punya NIM/NIS panjang
            $table->string('nim_nis', 100)->charset('utf8mb4')->change();

            // Jadikan nullable agar record lama (tempat_lahir=NULL) bisa di-approve
            $table->string('tempat_lahir', 100)->nullable()->charset('utf8mb4')->change();
        });
    }

    /**
     * Rollback: kembalikan ke definisi sebelumnya.
     */
    public function down(): void
    {
        Schema::table('tbl_sertifikat_pkl', function (Blueprint $table) {
            $table->string('nim_nis', 10)->charset('utf8mb4')->change();
            $table->string('tempat_lahir', 50)->nullable(false)->charset('utf8mb4')->change();
        });
    }
};
