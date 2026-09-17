<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private function fkExists(string $fkName): bool
    {
        $dbName = DB::connection()->getDatabaseName();
        $result = DB::select("
            SELECT COUNT(*) as total
            FROM information_schema.TABLE_CONSTRAINTS
            WHERE CONSTRAINT_SCHEMA = ?
              AND TABLE_NAME        = 'portofolios'
              AND CONSTRAINT_NAME   = ?
              AND CONSTRAINT_TYPE   = 'FOREIGN KEY'
        ", [$dbName, $fkName]);
        return ($result[0]->total ?? 0) > 0;
    }

    public function up(): void
    {
        if (!Schema::hasTable('portofolios')) {
            return;
        }

        Schema::table('portofolios', function (Blueprint $table) {
            // Drop FK lama kalau masih ada
            if ($this->fkExists('portofolios_user_pkl_id_foreign')) {
                $table->dropForeign(['user_pkl_id']);
            }
        });

        // Tambahkan FK baru ke tbl_login kalau belum ada
        if (!$this->fkExists('portofolios_user_pkl_id_foreign')) {
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
            if ($this->fkExists('portofolios_user_pkl_id_foreign')) {
                $table->dropForeign(['user_pkl_id']);
            }
            // rollback → FK ke users
            if (!$this->fkExists('portofolios_user_pkl_id_foreign')) {
                $table->foreign('user_pkl_id')
                      ->references('id')
                      ->on('users')
                      ->onDelete('cascade');
            }
        });
    }
};
