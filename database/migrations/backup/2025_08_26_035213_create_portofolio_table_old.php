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
        Schema::create('portofolios', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sertifikat_pkl_id'); // foreign key
            $table->unsignedBigInteger('user_pkl_id'); // foreign key
            $table->string('nama');              // Nama portofolio
            $table->string('bidang');            // Bidang/jenis portofolio
            $table->text('deskripsi');           // Deskripsi portofolio
            $table->string('file')->nullable();  // File / gambar (opsional)
            $table->timestamps();

            // Definisi foreign key
            $table->foreign('sertifikat_pkl_id')
                  ->references('id')
                  ->on('tbl_sertifikat_pkl')
                  ->onDelete('cascade'); 

            // Definisi foreign key
            $table->foreign('user_pkl_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade'); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('portofolios');
    }
};
