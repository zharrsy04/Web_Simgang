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
            // drop foreign key lama
            $table->dropForeign(['user_pkl_id']);

            // tambahin foreign key baru ke tbl_login
            $table->foreign('user_pkl_id')
                  ->references('id')
                  ->on('tbl_login')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('portofolios', function (Blueprint $table) {
            // rollback → balikin ke users (kalau sebelumnya FK ke users)
            $table->dropForeign(['user_pkl_id']);
            $table->foreign('user_pkl_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
        });
    }
};
