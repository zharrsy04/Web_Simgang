<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasColumn('tbl_sertifikat_pkl', 'nip_jabatan_pegawai')) {
            Schema::table('tbl_sertifikat_pkl', function (Blueprint $table) {
                $table->string('nip_jabatan_pegawai', 50)->nullable()->after('jabatan_pegawai');
            });

            // Isi default NIP untuk data eksisting yang sudah memilih Kepala Bidang Informatika
            DB::table('tbl_sertifikat_pkl')
                ->where('jabatan_pegawai', 'Kepala Bidang Informatika')
                ->whereNull('nip_jabatan_pegawai')
                ->update(['nip_jabatan_pegawai' => '198503232010011028']);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('tbl_sertifikat_pkl', 'nip_jabatan_pegawai')) {
            Schema::table('tbl_sertifikat_pkl', function (Blueprint $table) {
                $table->dropColumn('nip_jabatan_pegawai');
            });
        }
    }
};
