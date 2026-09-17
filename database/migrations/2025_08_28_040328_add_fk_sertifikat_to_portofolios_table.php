<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('portofolios')) {
            return;
        }

        // Tambah kolom kalau belum ada
        if (!Schema::hasColumn('portofolios', 'sertifikat_pkl_id')) {
            Schema::table('portofolios', function (Blueprint $table) {
                $table->unsignedBigInteger('sertifikat_pkl_id')->nullable();
            });
        }

        // Cek apakah FK sudah ada sebelum menambahkan
        $dbName   = DB::connection()->getDatabaseName();
        $fkExists = DB::select("
            SELECT COUNT(*) as total
            FROM information_schema.TABLE_CONSTRAINTS
            WHERE CONSTRAINT_SCHEMA = ?
              AND TABLE_NAME        = 'portofolios'
              AND CONSTRAINT_NAME   = 'portofolios_sertifikat_pkl_id_foreign'
              AND CONSTRAINT_TYPE   = 'FOREIGN KEY'
        ", [$dbName]);

        if (($fkExists[0]->total ?? 0) == 0) {
            Schema::table('portofolios', function (Blueprint $table) {
                $table->foreign('sertifikat_pkl_id')
                      ->references('id_sertifikat')
                      ->on('tbl_sertifikat_pkl')
                      ->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        Schema::table('portofolios', function (Blueprint $table) {
            $table->dropForeign(['sertifikat_pkl_id']);
        });
    }
};
