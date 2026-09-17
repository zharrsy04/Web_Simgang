<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MentorSiswa extends Model
{
    use HasFactory;

    protected $table = 'mentor_siswa';
    
    protected $fillable = [
        'mentor_id',
        'siswa_id',
        'tanggal_bimbingan',
        'keterangan',
        'is_active',
    ];

    protected $casts = [
        'tanggal_bimbingan' => 'datetime',
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relasi ke Mentor (User)
     */
    public function mentor()
    {
        return $this->belongsTo(User::class, 'mentor_id', 'id');
    }

    /**
     * Relasi ke Siswa (SertifikatPKL)
     */
    public function siswa()
    {
        return $this->belongsTo(SertifikatPKL::class, 'siswa_id', 'id_sertifikat');
    }

    /**
     * Scope untuk mencari bimbingan aktif
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope untuk mencari bimbingan berdasarkan mentor
     */
    public function scopeByMentor($query, $mentorId)
    {
        return $query->where('mentor_id', $mentorId);
    }

    /**
     * Scope untuk mencari bimbingan berdasarkan siswa
     */
    public function scopeBySiswa($query, $siswaId)
    {
        return $query->where('siswa_id', $siswaId);
    }

    /**
     * Cek apakah mentor sedang membimbing siswa tertentu
     */
    public static function isMentoring($mentorId, $siswaId)
    {
        return self::where('mentor_id', $mentorId)
            ->where('siswa_id', $siswaId)
            ->where('is_active', true)
            ->exists();
    }

    /**
     * Get mentor yang sedang membimbing siswa tertentu
     */
    public static function getMentorForSiswa($siswaId)
    {
        return self::where('siswa_id', $siswaId)
            ->where('is_active', true)
            ->with('mentor')
            ->first();
    }

    /**
     * Get semua siswa yang dibimbing mentor tertentu
     */
    public static function getSiswaByMentor($mentorId)
    {
        return self::where('mentor_id', $mentorId)
            ->where('is_active', true)
            ->with('siswa')
            ->get();
    }
}
