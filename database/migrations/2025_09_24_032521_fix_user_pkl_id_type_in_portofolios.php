<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('portofolios')) {
            return;
        }
        
        Schema::table('portofolios', function (Blueprint $table) {
            // 1. Hapus kolom sementara user_pkl_id_new jika ada
            if (Schema::hasColumn('portofolios', 'user_pkl_id_new')) {
                $table->dropColumn('user_pkl_id_new');
            }
        });

        // 2. Cek apakah foreign key lama untuk user_pkl_id masih ada
        $fkExists = DB::select("
            SELECT CONSTRAINT_NAME
            FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
            WHERE TABLE_NAME = 'portofolios'
              AND COLUMN_NAME = 'user_pkl_id'
              AND CONSTRAINT_SCHEMA = DATABASE()
              AND REFERENCED_TABLE_NAME IS NOT NULL
        ");

        if (!empty($fkExists)) {
            // Kalau FK lama ada, hapus manual
            DB::statement('ALTER TABLE portofolios DROP FOREIGN KEY portofolios_user_pkl_id_foreign');
        }

        // 3. Tambahkan FK baru
        Schema::table('portofolios', function (Blueprint $table) {
            $table->foreign('user_pkl_id')
                  ->references('id')
                  ->on('tbl_login')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('portofolios', function (Blueprint $table) {
            // Hapus FK saat rollback
            $table->dropForeign(['user_pkl_id']);

            // Jika perlu, kembalikan kolom user_pkl_id_new
            $table->bigInteger('user_pkl_id_new')->nullable();
        });
    }
};
