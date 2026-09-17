<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tbl_sertifikat_pkl', function (Blueprint $table) {
            // Add created_id column to track who created the sertifikat
            if (!Schema::hasColumn('tbl_sertifikat_pkl', 'created_id')) {
                $table->bigInteger('created_id')->nullable()->after('sts_diambil')
                      ->comment('ID user (mentor/sekretariat) yang membuat sertifikat');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_sertifikat_pkl', function (Blueprint $table) {
            if (Schema::hasColumn('tbl_sertifikat_pkl', 'created_id')) {
                $table->dropColumn('created_id');
            }
        });
    }
};
