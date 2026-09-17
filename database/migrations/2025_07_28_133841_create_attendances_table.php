<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttendancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('attendances')) {
            return;
        }
        
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            
            // Kolom id_sertifikat tetap ada
            // Tipe data (string) di sini harus sesuai dengan tipe data id_sertifikat di tabel 'users'
            $table->string('id_sertifikat'); 
            
            $table->date('date');
            $table->string('status'); // Contoh: 'Hadir', 'Alpha', 'Izin'
            $table->string('location')->nullable(); // Lokasi GPS atau nama tempat
            $table->text('notes')->nullable(); // Catatan tambahan (misal alasan izin)
            $table->time('time')->nullable(); // Waktu presensi (misal: '08:00:00')
            $table->timestamps(); // created_at dan updated_at

            // ---------- BARIS INI YANG DIHAPUS ATAU DIKOMENTARI ----------
            // $table->foreign('id_sertifikat')
            //       ->references('id_sertifikat')
            //       ->on('users')
            //       ->onDelete('cascade'); 
            // -----------------------------------------------------------

            // Menambahkan unique constraint untuk mencegah presensi ganda di tanggal yang sama
            // Ini tetap disarankan untuk mencegah duplikasi data presensi per user per hari
            $table->unique(['id_sertifikat', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attendances');
    }
}