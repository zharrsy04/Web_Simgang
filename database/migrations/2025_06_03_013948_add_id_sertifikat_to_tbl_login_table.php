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
        Schema::table('tbl_login', function (Blueprint $table) {
            // Tambahkan kolom id_sertifikat
            // unsignedBigInteger karena id_sertifikat di tbl_sertifikat_pkl kemungkinan besar auto-incrementing big integer
            // nullable() karena siswa mungkin mendaftar sebelum sertifikatnya dibuat/dihubungkan
            $table->unsignedBigInteger('id_sertifikat')->nullable()->after('role');

            // Tambahkan UNIQUE constraint untuk memastikan 1 siswa (login) hanya punya 1 sertifikat
            // Ini penting jika Anda ingin menegakkan relasi 1-ke-1 dari sisi Login ke SertifikatPKL
            $table->unique('id_sertifikat', 'tbl_login_id_sertifikat_unique'); // Beri nama custom untuk indeks unik

            // Tambahkan foreign key constraint
            // onDelete('set null') berarti jika record sertifikat dihapus, id_sertifikat di tbl_login akan diatur menjadi NULL.
            // Ini mencegah error dan memungkinkan siswa untuk tetap login meskipun sertifikatnya terhapus (namun tidak akan melihat nilainya).
            $table->foreign('id_sertifikat')
                  ->references('id_sertifikat')
                  ->on('tbl_sertifikat_pkl')
                  ->onDelete('set null'); // Atau onDelete('cascade') jika ingin record login ikut terhapus
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_login', function (Blueprint $table) {
            // Hapus foreign key constraint terlebih dahulu
            $table->dropForeign(['id_sertifikat']);
            // Hapus unique constraint juga
            $table->dropUnique('tbl_login_id_sertifikat_unique'); // Gunakan nama indeks yang sama
            // Kemudian hapus kolomnya
            $table->dropColumn('id_sertifikat');
        });
    }
};