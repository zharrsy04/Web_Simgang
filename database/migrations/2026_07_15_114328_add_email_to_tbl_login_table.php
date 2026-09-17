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
            if (!Schema::hasColumn('tbl_login', 'email')) {
                $table->string('email', 255)->nullable()->after('username');
            }
            if (!Schema::hasColumn('tbl_login', 'nama')) {
                $table->string('nama', 255)->nullable()->after('email');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_login', function (Blueprint $table) {
            $table->dropColumn(['email', 'nama']);
        });
    }
};
