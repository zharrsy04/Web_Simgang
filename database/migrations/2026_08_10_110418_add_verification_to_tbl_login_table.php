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
            $table->timestamp('email_verified_at')->nullable();
            $table->string('verification_code')->nullable();
            $table->timestamp('verification_expires_at')->nullable();
            $table->integer('verification_attempts')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_login', function (Blueprint $table) {
            $table->dropColumn([
                'email_verified_at',
                'verification_code',
                'verification_expires_at',
                'verification_attempts'
            ]);
        });
    }
};
