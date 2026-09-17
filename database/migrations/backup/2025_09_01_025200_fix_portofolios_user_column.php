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
            // ubah tipe kolom biar sama dengan tbl_login.id
            $table->bigInteger('user_pkl_id')->change();

            // lalu bikin foreign key baru
            // $table->foreign('user_pkl_id')
            //       ->references('id')
            //       ->on('tbl_login')
            //       ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('portofolios', function (Blueprint $table) {
            //$table->dropForeign(['user_pkl_id']);
            $table->unsignedBigInteger('user_pkl_id')->change();

            // balikin ke users kalau rollback
            // $table->foreign('user_pkl_id')
            //       ->references('id')
            //       ->on('users')
            //       ->onDelete('cascade');
        });
    }
};
