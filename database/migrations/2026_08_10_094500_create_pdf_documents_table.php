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
        if (Schema::hasTable('pdf_documents')) {
            return;
        }

        Schema::create('pdf_documents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_sertifikat');
            $table->string('nim_nis', 50)->nullable();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('filename');
            $table->string('file_path');
            $table->unsignedBigInteger('file_size')->nullable();
            $table->timestamp('uploaded_at')->nullable();
            $table->string('type')->default('individual');
            $table->timestamps();

            $table->index('id_sertifikat');
            $table->index('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pdf_documents');
    }
};
