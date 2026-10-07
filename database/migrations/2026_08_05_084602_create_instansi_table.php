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
        if (!Schema::hasTable('instansi')) {
            Schema::create('instansi', function (Blueprint $table) {
                $table->id();
                $table->string('nama')->unique();
                $table->string('logo')->nullable();
                $table->timestamps();
            });
        }

        if (Schema::hasTable('pengajuan_magang') && !Schema::hasColumn('pengajuan_magang', 'instansi_id')) {
            Schema::table('pengajuan_magang', function (Blueprint $table) {
                $table->unsignedBigInteger('instansi_id')->nullable()->after('asal_sekolah');
                $table->foreign('instansi_id')->references('id')->on('instansi')->onDelete('set null');
            });
        }

        if (Schema::hasTable('tbl_sertifikat_pkl') && !Schema::hasColumn('tbl_sertifikat_pkl', 'instansi_id')) {
            Schema::table('tbl_sertifikat_pkl', function (Blueprint $table) {
                $table->unsignedBigInteger('instansi_id')->nullable()->after('asal_sekolah');
                $table->foreign('instansi_id')->references('id')->on('instansi')->onDelete('set null');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_sertifikat_pkl', function (Blueprint $table) {
            $table->dropForeign(['instansi_id']);
            $table->dropColumn('instansi_id');
        });

        Schema::table('pengajuan_magang', function (Blueprint $table) {
            $table->dropForeign(['instansi_id']);
            $table->dropColumn('instansi_id');
        });

        Schema::dropIfExists('instansi');
    }
};
