<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class NormalizeTblSertifikatPklTable extends Migration
{
    public function up()
    {
        Schema::table('tbl_sertifikat_pkl', function (Blueprint $table) {
            // Ubah tipe data tgl_* menjadi DATE
            $table->date('tgl_lahir')->change();
            $table->date('tgl_mulai')->change();
            $table->date('tgl_selesai')->change();
            $table->date('tgl_sertifikat')->change();
            
            // Ubah kolom lainnya sesuai kebutuhan
            // Note: id_sertifikat is the primary key, not 'id'
            $table->string('nama_lengkap', 50)->charset('utf8mb4')->collation('utf8mb4_0900_ai_ci')->change();
            $table->string('asal_sekolah', 50)->charset('utf8mb4')->collation('utf8mb4_0900_ai_ci')->change();
            $table->string('nim_nis', 10)->charset('utf8mb4')->collation('utf8mb4_0900_ai_ci')->change();
            $table->string('tempat_lahir', 50)->charset('utf8mb4')->collation('utf8mb4_0900_ai_ci')->change();
            $table->string('jurusan', 100)->charset('utf8mb4')->collation('utf8mb4_0900_ai_ci')->change();
            $table->string('nm_pembimbing', 50)->charset('utf8mb4')->collation('utf8mb4_0900_ai_ci')->change();
            $table->string('nik_pembimbing', 25)->charset('utf8mb4')->collation('utf8mb4_0900_ai_ci')->change();
            $table->string('jabatan_pembimbing', 50)->charset('utf8mb4')->collation('utf8mb4_0900_ai_ci')->change();
            $table->string('no_sertifikat', 50)->charset('utf8mb4')->collation('utf8mb4_0900_ai_ci')->change();
            $table->string('nm_kadis', 50)->charset('utf8mb4')->collation('utf8mb4_0900_ai_ci')->change();
            $table->string('nip_kadis', 25)->charset('utf8mb4')->collation('utf8mb4_0900_ai_ci')->change();
            $table->integer('aprove_pembimbing')->change();
            $table->integer('approve_kadis')->change();
            $table->integer('sts_diambil')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tbl_sertifikat_pkl', function (Blueprint $table) {
            // Mengembalikan perubahan ke tipe data sebelumnya
            $table->string('tgl_lahir', 20)->change();
            $table->string('tgl_mulai', 20)->change();
            $table->string('tgl_selesai', 20)->change();
            $table->string('tgl_sertifikat', 20)->change();
        });
    }
}
