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
        Schema::create('logbooks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_sertifikat');
            $table->unsignedBigInteger('mentor_id')->nullable();
            $table->date('tanggal');
            $table->time('jam_mulai')->nullable();
            $table->time('jam_selesai')->nullable();
            $table->string('judul_kegiatan');
            $table->text('deskripsi_kegiatan');
            $table->text('hasil_kegiatan')->nullable();
            $table->text('kendala')->nullable();
            $table->string('dokumentasi')->nullable(); // path file foto / lampiran kegiatan
            $table->enum('status', ['Menunggu', 'Disetujui', 'Perlu Revisi'])->default('Menunggu');
            $table->text('catatan_mentor')->nullable();
            $table->timestamp('waktu_dinilai')->nullable();
            $table->timestamps();

            // Indexes for fast querying
            $table->index(['id_sertifikat', 'tanggal']);
            $table->index('mentor_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logbooks');
    }
};
