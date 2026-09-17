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
        Schema::table('tasks', function (Blueprint $table) {
            // Tambah kolom catatan_izin untuk cross-bidang assignment
            if (!Schema::hasColumn('tasks', 'catatan_izin')) {
                // Gunakan kolom referensi yang ada (is_read atau read_at)
                $col = $table->text('catatan_izin')
                             ->nullable()
                             ->comment('Catatan alasan ketika mentor memberi tugas ke siswa bukan bimbingannya (cross-bidang)');

                if (Schema::hasColumn('tasks', 'is_read')) {
                    $col->after('is_read');
                } elseif (Schema::hasColumn('tasks', 'read_at')) {
                    $col->after('read_at');
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            if (Schema::hasColumn('tasks', 'catatan_izin')) {
                $table->dropColumn('catatan_izin');
            }
        });
    }
};
