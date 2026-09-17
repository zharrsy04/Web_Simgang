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
            // 1. Kalau ada index lama, hapus dulu
            // $sm = Schema::getConnection()->getDoctrineSchemaManager();
            // $indexes = $sm->listTableIndexes('portofolios');
            // if (array_key_exists('portofolios_user_pkl_id_foreign', $indexes)) {
            //     $table->dropIndex('portofolios_user_pkl_id_foreign');
            // }

            // 2. Pastikan kolom tipenya cocok
            $table->bigInteger('user_pkl_id')->nullable()->change();

            // // 3. Tambahkan foreign key baru ke tbl_login(id)
            $table->foreign('user_pkl_id', 'portofolios_user_pkl_id_foreign')
                   ->references('id')
                    ->on('tbl_login')
                   ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('portofolios', function (Blueprint $table) {
            $table->dropForeign('portofolios_user_pkl_id_foreign');
        });
    }
};


