<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('portofolios', function (Blueprint $table) {
            if (Schema::hasColumn('portofolios', 'user_pkl_id_new')) {
                $table->dropColumn('user_pkl_id_new');
            }
        });
    }

    public function down(): void
    {
        Schema::table('portofolios', function (Blueprint $table) {
            if (!Schema::hasColumn('portofolios', 'user_pkl_id_new')) {
                $table->bigInteger('user_pkl_id_new')->nullable();
            }
        });
    }
};

