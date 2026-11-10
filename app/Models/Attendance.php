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
        'mentor_id',
        'date',
        'status',
        'is_late',
        'location',
        'latitude',
        'longitude',
        'notes',
        'attachment',
        'time',
        'time_out',
        'ip_address',
        'user_agent',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date' => 'date:Y-m-d',
        'time' => 'string',
        'is_late' => 'boolean',
    ];

    // --- Relationship to User (Siswa by id_sertifikat) ---
    public function user()
    {
        return $this->belongsTo(User::class, 'id_sertifikat', 'id_sertifikat');
    }

    // --- Relationship to SertifikatPKL (Data Magang Siswa) ---
    public function siswa()
    {
        return $this->belongsTo(SertifikatPKL::class, 'id_sertifikat', 'id_sertifikat');
    }

    // --- Relationship to Mentor (User) ---
    public function mentor()
    {
        return $this->belongsTo(User::class, 'mentor_id', 'id');
    }
}