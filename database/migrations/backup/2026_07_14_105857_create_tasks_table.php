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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sertifikat_pkl_id'); // FK ke tbl_sertifikat_pkl.id_sertifikat
            $table->bigInteger('assigned_by')->nullable(); // FK ke tbl_login.id
            $table->string('title', 255);
            $table->text('description')->nullable();
            $table->date('due_date')->nullable();
            $table->enum('status', ['pending', 'completed', 'in progress'])->default('pending');
            $table->boolean('is_read')->default(false); // untuk fitur mark-as-read siswa
            $table->timestamps();

            // Foreign keys
            $table->foreign('sertifikat_pkl_id')
                  ->references('id_sertifikat')
                  ->on('tbl_sertifikat_pkl')
                  ->onDelete('cascade');

            $table->foreign('assigned_by')
                  ->references('id')
                  ->on('tbl_login')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
