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
        Schema::table('attendances', function (Blueprint $table) {
            // Lepas unique key dulu
            $table->dropUnique(['id_sertifikat', 'date']);

            // Ubah kolom id_sertifikat menjadi BIGINT UNSIGNED
            $table->unsignedBigInteger('id_sertifikat')->change();

            // Pasang kembali unique key setelah perubahan
            $table->unique(['id_sertifikat', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            // Lepas unique key dulu
            $table->dropUnique(['id_sertifikat', 'date']);

            // Rollback ke tipe awal (string)
            $table->string('id_sertifikat')->change();

            // Pasang kembali unique key
            $table->unique(['id_sertifikat', 'date']);
        });
    }
};
