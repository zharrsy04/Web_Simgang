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

        // Cek apakah kolom sudah ada
        if (!Schema::hasColumn('portofolios', 'user_pkl_id')) {
            Schema::table('portofolios', function (Blueprint $table) {
                $table->bigInteger('user_pkl_id')
                      ->after('sertifikat_pkl_id')
                      ->nullable();
            });
        } else {
            Schema::table('portofolios', function (Blueprint $table) {
                $table->bigInteger('user_pkl_id')->nullable()->change();
            });
        }

        // Cek apakah foreign key sudah ada di database sebelum menambahkan
        $dbName   = DB::connection()->getDatabaseName();
        $fkExists = DB::select("
            SELECT COUNT(*) as total
            FROM information_schema.TABLE_CONSTRAINTS
            WHERE CONSTRAINT_SCHEMA = ?
              AND TABLE_NAME        = 'portofolios'
              AND CONSTRAINT_NAME   = 'portofolios_user_pkl_id_foreign'
              AND CONSTRAINT_TYPE   = 'FOREIGN KEY'
        ", [$dbName]);

        if (($fkExists[0]->total ?? 0) == 0) {
            Schema::table('portofolios', function (Blueprint $table) {
                $table->foreign('user_pkl_id')
                      ->references('id')
                      ->on('tbl_login')
                      ->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        Schema::table('portofolios', function (Blueprint $table) {
            $table->dropForeign(['user_pkl_id']);
            $table->dropColumn('user_pkl_id');
        });
    }
};
