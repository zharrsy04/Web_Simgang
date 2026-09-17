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
        Schema::create('penempatan_bidang', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_sertifikat');
            $table->string('bidang_asal', 100)->nullable()->comment('Bidang asal dari pengajuan siswa');
            $table->string('bidang_ditempatkan', 100)->comment('Bidang tempat penempatan');
            $table->enum('ditempatkan_oleh', ['mentor', 'sekretariat'])->comment('Role yang melakukan penempatan');
            $table->bigInteger('user_id')->comment('User yang melakukan penempatan'); // Changed to match tbl_login id type (signed bigint)
            $table->timestamp('tanggal_penempatan')->useCurrent();
            $table->string('keterangan', 255)->nullable();
            $table->timestamps();

            // Indexes
            $table->foreign('id_sertifikat')->references('id_sertifikat')->on('tbl_sertifikat_pkl')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('tbl_login')->onDelete('cascade');
            $table->unique('id_sertifikat')->comment('Satu siswa hanya bisa ditempatkan satu kali');
            $table->index('bidang_ditempatkan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penempatan_bidang');
    }
};
