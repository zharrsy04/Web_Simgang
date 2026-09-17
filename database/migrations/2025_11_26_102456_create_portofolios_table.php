<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (Schema::hasTable('portofolios')) {
            return;
        }
        
        Schema::create('portofolios', function (Blueprint $table) {

            $table->id(); // bigint unsigned AUTO_INCREMENT

            // foreign key ke tbl_sertifikat_pkl.id_sertifikat
            $table->unsignedBigInteger('sertifikat_pkl_id');

            // foreign key ke tbl_login.id
            $table->bigInteger('user_pkl_id');

            $table->string('nama');
            $table->string('bidang');
            $table->text('deskripsi');
            $table->string('file')->nullable();

            $table->timestamps();

            // Index & FK
            $table->foreign('sertifikat_pkl_id')
                  ->references('id_sertifikat')
                  ->on('tbl_sertifikat_pkl')
                  ->onDelete('cascade');

            $table->foreign('user_pkl_id')
                  ->references('id')
                  ->on('tbl_login')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('portofolios');
    }
};
