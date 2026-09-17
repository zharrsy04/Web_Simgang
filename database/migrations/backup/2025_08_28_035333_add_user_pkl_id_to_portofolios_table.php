<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('portofolios')) {
            return;
        }
        
        Schema::table('portofolios', function (Blueprint $table) {
            if (Schema::hasColumn('portofolios', 'user_pkl_id')) {
                // Ubah jadi BIGINT signed kalau sudah ada
                $table->bigInteger('user_pkl_id')->nullable()->change();
            } else {
                // Kalau belum ada, tambahkan kolom
                $table->bigInteger('user_pkl_id')
                      ->after('sertifikat_pkl_id')
                      ->nullable();
            }

            // Tambahkan foreign key baru
            $table->foreign('user_pkl_id')
                  ->references('id')
                  ->on('tbl_login')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('portofolios', function (Blueprint $table) {
            $table->dropForeign(['user_pkl_id']);
            $table->dropColumn('user_pkl_id');
        });
    }
};
