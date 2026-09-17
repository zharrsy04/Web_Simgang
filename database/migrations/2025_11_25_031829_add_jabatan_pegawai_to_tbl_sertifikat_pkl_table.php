<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
        public function up()
    {
        Schema::table('tbl_sertifikat_pkl', function (Blueprint $table) {
            if (!Schema::hasColumn('tbl_sertifikat_pkl', 'jabatan_pegawai')) {
                $table->string('jabatan_pegawai')->nullable()->after('jabatan_pembimbing');
            }
        });
    }

    public function down()
    {
        Schema::table('tbl_sertifikat_pkl', function (Blueprint $table) {
            $table->dropColumn('jabatan_pegawai');
        });
    }
};
