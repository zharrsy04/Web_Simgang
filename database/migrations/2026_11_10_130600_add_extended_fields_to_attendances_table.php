
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $columns = [
            'mentor_id' => fn($table) => $table->unsignedBigInteger('mentor_id')->nullable(),
            'is_late' => fn($table) => $table->boolean('is_late')->default(false),
            'latitude' => fn($table) => $table->decimal('latitude', 10, 7)->nullable(),
            'longitude' => fn($table) => $table->decimal('longitude', 10, 7)->nullable(),
            'attachment' => fn($table) => $table->string('attachment')->nullable(),
            'time_out' => fn($table) => $table->time('time_out')->nullable(),
            'ip_address' => fn($table) => $table->string('ip_address', 45)->nullable(),
            'user_agent' => fn($table) => $table->string('user_agent', 512)->nullable(),
        ];

        foreach ($columns as $name => $definition) {
            if (!Schema::hasColumn('attendances', $name)) {
                Schema::table('attendances', $definition);
            }
        }
    }

    public function down(): void
    {
        // Tidak menghapus kolom untuk menjaga data Absensi.
    }
};
