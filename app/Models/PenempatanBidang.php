<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenempatanBidang extends Model
{
    use HasFactory;

    protected $table = 'penempatan_bidang';
    
    protected $fillable = [
        'id_sertifikat',
        'bidang_asal',
        'bidang_ditempatkan',
        'ditempatkan_oleh',
        'user_id',
        'keterangan',
    ];

    protected $casts = [
        'tanggal_penempatan' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relasi ke Sertifikat PKL
     */
    public function sertifikat()
    {
        return $this->belongsTo(SertifikatPKL::class, 'id_sertifikat', 'id_sertifikat');
    }

    /**
     * Relasi ke User yang melakukan penempatan
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Scope untuk mencari penempatan berdasarkan sertifikat
     */
    public function scopeBySertifikat($query, $idSertifikat)
    {
        return $query->where('id_sertifikat', $idSertifikat);
    }

    /**
     * Scope untuk mencari penempatan berdasarkan bidang
     */
    public function scopeByBidang($query, $bidang)
    {
        return $query->where('bidang_ditempatkan', $bidang);
    }

    /**
     * Check if siswa is already placed
     */
    public static function isPlaced($idSertifikat)
    {
        return self::where('id_sertifikat', $idSertifikat)->exists();
    }

    /**
     * Get placement record for a siswa
     */
    public static function getPlacement($idSertifikat)
    {
        return self::where('id_sertifikat', $idSertifikat)->first();
    }
}
