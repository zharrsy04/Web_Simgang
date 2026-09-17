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
            // Hapus kolom sementara user_pkl_id_new kalau ada
            if (Schema::hasColumn('portofolios', 'user_pkl_id_new')) {
                $table->dropColumn('user_pkl_id_new');
            }

            // Pastikan user_pkl_id sudah bigint signed
            $table->bigInteger('user_pkl_id')->change();
        });
    }

    public function down(): void
    {
        Schema::table('portofolios', function (Blueprint $table) {
            // Rollback: buat lagi kolom user_pkl_id_new jika mau
            $table->bigInteger('user_pkl_id_new')->nullable();
        });
    }
};
