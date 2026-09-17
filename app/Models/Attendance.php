<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'attendances'; // Pastikan nama tabel benar

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id_sertifikat',
        'date',
        'status',
        'location',
        'notes',
        'time',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date' => 'date:Y-m-d',
        'time' => 'string',
    ];

    // --- Relationship to User ---
    // Tambahkan relasi ini untuk mendapatkan informasi user yang terkait dengan presensi ini
    public function user()
    {
        return $this->belongsTo(User::class, 'id_sertifikat', 'id_sertifikat');
        // Keterangan:
        // User::class : Nama model induk
        // 'id_sertifikat' (arg 2): Nama foreign key di tabel 'attendances' (model saat ini)
        // 'id_sertifikat' (arg 3): Nama local key di tabel 'users' (model induk)
    }
}