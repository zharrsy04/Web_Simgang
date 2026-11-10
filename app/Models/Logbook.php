<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Logbook extends Model
{
    use HasFactory;

    protected $table = 'logbooks';

    protected $fillable = [
        'id_sertifikat',
        'mentor_id',
        'tanggal',
        'jam_mulai',
        'jam_selesai',
        'judul_kegiatan',
        'deskripsi_kegiatan',
        'hasil_kegiatan',
        'kendala',
        'dokumentasi',
        'status',
        'catatan_mentor',
        'waktu_dinilai',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'waktu_dinilai' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relasi ke Data Magang Siswa (SertifikatPKL)
     */
    public function siswa()
    {
        return $this->belongsTo(SertifikatPKL::class, 'id_sertifikat', 'id_sertifikat');
    }

    /**
     * Relasi ke Mentor (User)
     */
    public function mentor()
    {
        return $this->belongsTo(User::class, 'mentor_id', 'id');
    }

    /**
     * Relasi ke User Siswa (via id_sertifikat)
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id_sertifikat', 'id_sertifikat');
    }

    /**
     * Helper URL file dokumentasi
     */
    public function getDokumentasiUrlAttribute()
    {
        if (!$this->dokumentasi) {
            return null;
        }

        // Jika path sudah berupa URL eksternal
        if (str_starts_with($this->dokumentasi, 'http')) {
            return $this->dokumentasi;
        }

        return asset('storage/' . $this->dokumentasi);
    }
}

