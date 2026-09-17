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
        Schema::create('mentor_siswa', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('mentor_id')->comment('ID mentor dari tbl_login');
            $table->unsignedBigInteger('siswa_id')->comment('ID sertifikat siswa dari tbl_sertifikat_pkl');
            $table->timestamp('tanggal_bimbingan')->useCurrent()->comment('Tanggal mulai bimbingan');
            $table->string('keterangan', 255)->nullable()->comment('Catatan tambahan');
            $table->boolean('is_active')->default(true)->comment('Status bimbingan aktif/tidak');
            $table->timestamps();

            // Foreign keys
            $table->foreign('mentor_id')->references('id')->on('tbl_login')->onDelete('cascade');
            $table->foreign('siswa_id')->references('id_sertifikat')->on('tbl_sertifikat_pkl')->onDelete('cascade');

            // Indexes
            $table->unique(['mentor_id', 'siswa_id'])->comment('Satu mentor hanya bisa membimbing satu siswa sekali');
            $table->index('mentor_id');
            $table->index('siswa_id');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mentor_siswa');
    }
};
