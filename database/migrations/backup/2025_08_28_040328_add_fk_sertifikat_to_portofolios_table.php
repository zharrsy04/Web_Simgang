<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('portofolios')) {
            return;
        }
        
        Schema::table('portofolios', function (Blueprint $table) {
            // Pastikan belum ada FK biar gak error
            if (!Schema::hasColumn('portofolios', 'sertifikat_pkl_id')) {
                $table->unsignedBigInteger('sertifikat_pkl_id');
            }

            $table->foreign('sertifikat_pkl_id')
                  ->references('id_sertifikat')
                  ->on('tbl_sertifikat_pkl')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('portofolios', function (Blueprint $table) {
            $table->dropForeign(['sertifikat_pkl_id']);
        });
    }
};
