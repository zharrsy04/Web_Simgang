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
        Schema::create('instansi', function (Blueprint $table) {
            $table->id();
            $table->string('nama')->unique();
            $table->string('logo')->nullable();
            $table->timestamps();
        });

        Schema::table('pengajuan_magang', function (Blueprint $table) {
            $table->unsignedBigInteger('instansi_id')->nullable()->after('asal_sekolah');
            $table->foreign('instansi_id')->references('id')->on('instansi')->onDelete('set null');
        });

        Schema::table('tbl_sertifikat_pkl', function (Blueprint $table) {
            $table->unsignedBigInteger('instansi_id')->nullable()->after('asal_sekolah');
            $table->foreign('instansi_id')->references('id')->on('instansi')->onDelete('set null');
        });
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
