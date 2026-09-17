use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('portofolios', function (Blueprint $table) {
            // Drop FK lama (kalau ada)
            $table->dropForeign(['sertifikat_pkl_id']);

            // Tambah FK ke tabel tbl_sertifikat_pkl
            $table->foreign('sertifikat_pkl_id')
                  ->references('id_sertifikat') // <- sesuai hasil DESCRIBE
                  ->on('tbl_sertifikat_pkl')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('portofolios', function (Blueprint $table) {
            $table->dropForeign(['sertifikat_pkl_id']);

            // Balikin ke FK lama kalau perlu
            $table->foreign('sertifikat_pkl_id')
                  ->references('id')
                  ->on('sertifikat_pkl')
                  ->onDelete('cascade');
        });
    }
};
