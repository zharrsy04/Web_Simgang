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
            // Make no_sertifikat nullable
            $table->string('no_sertifikat', 50)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_sertifikat_pkl', function (Blueprint $table) {
            // Revert to NOT NULL (though existing NULL values may cause issues)
            $table->string('no_sertifikat', 50)->nullable(false)->change();
        });
    }
};
