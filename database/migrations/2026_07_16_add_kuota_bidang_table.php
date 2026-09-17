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
        if (Schema::hasTable('kuota_bidang')) {
            return;
        }

        Schema::create('kuota_bidang', function (Blueprint $table) {
            $table->id();
            $table->string('nama_bidang', 100)->unique();
            $table->integer('kuota')->default(0);
            $table->timestamps();
        });

        // Insert default data
        \DB::table('kuota_bidang')->insert([
            ['nama_bidang' => 'Bidang Informatika', 'kuota' => 10, 'created_at' => now(), 'updated_at' => now()],
            ['nama_bidang' => 'Sekretariat', 'kuota' => 8, 'created_at' => now(), 'updated_at' => now()],
            ['nama_bidang' => 'Bidang Informasi dan Kepentingan Publik', 'kuota' => 12, 'created_at' => now(), 'updated_at' => now()],
            ['nama_bidang' => 'Bidang Statistik dan Persandian', 'kuota' => 6, 'created_at' => now(), 'updated_at' => now()],
            ['nama_bidang' => 'Lembaga Penyiaran Publik Lokal', 'kuota' => 5, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kuota_bidang');
    }
};
