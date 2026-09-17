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
        Schema::table('tbl_login', function (Blueprint $table) {
            // Tambah kolom bidang untuk mentor
            if (!Schema::hasColumn('tbl_login', 'bidang')) {
                $table->string('bidang', 100)->nullable()->after('role')->comment('Bidang mentor (Sekretariat, Bidang Informatika, Bidang Statistik dan Persandian)');
            }
        });

        // Set default bidang untuk mentor yang sudah ada (Informatika)
        DB::table('tbl_login')
            ->where('role', 'mentor')
            ->whereNull('bidang')
            ->update(['bidang' => 'Bidang Informatika']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_login', function (Blueprint $table) {
            if (Schema::hasColumn('tbl_login', 'bidang')) {
                $table->dropColumn('bidang');
            }
        });
    }
};
