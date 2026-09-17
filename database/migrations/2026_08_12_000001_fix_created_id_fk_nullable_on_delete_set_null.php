<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ubah kolom created_id menjadi nullable dan tambahkan ON DELETE SET NULL
     * agar sertifikat tidak error saat user yang membuatnya dihapus.
     */
    public function up(): void
    {
        // 1. Hapus Foreign Key lama yang tidak punya ON DELETE SET NULL
        try {
            Schema::table('tbl_sertifikat_pkl', function (Blueprint $table) {
                $table->dropForeign('mentor_siswa');
            });
        } catch (\Exception $e) {
            // FK mungkin sudah tidak ada, lanjutkan
        }

        // 2. Ubah kolom created_id menjadi nullable (disable FK checks dulu)
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::statement('ALTER TABLE `tbl_sertifikat_pkl` MODIFY `created_id` BIGINT(20) NULL DEFAULT NULL');
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        // 3. Set baris yang created_id = 0 menjadi NULL (sisa dari fix sebelumnya)
        DB::table('tbl_sertifikat_pkl')->where('created_id', 0)->update(['created_id' => null]);

        // 4. Tambahkan FK baru dengan ON DELETE SET NULL
        Schema::table('tbl_sertifikat_pkl', function (Blueprint $table) {
            $table->foreign('created_id', 'mentor_siswa')
                  ->references('id')
                  ->on('tbl_login')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_sertifikat_pkl', function (Blueprint $table) {
            try {
                $table->dropForeign('mentor_siswa');
            } catch (\Exception $e) {
                //
            }
        });

        // Kembalikan ke NOT NULL (set null ke 0 dulu agar tidak error)
        DB::table('tbl_sertifikat_pkl')->whereNull('created_id')->update(['created_id' => 0]);
        DB::statement('ALTER TABLE `tbl_sertifikat_pkl` MODIFY `created_id` BIGINT(20) NOT NULL');

        Schema::table('tbl_sertifikat_pkl', function (Blueprint $table) {
            $table->foreign('created_id', 'mentor_siswa')
                  ->references('id')
                  ->on('tbl_login');
        });
    }
};
