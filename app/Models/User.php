<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'tbl_login';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'username',
        'email',
        'nama',
        'pass',
        'role',
        'id_sertifikat',
        'bidang',
        'email_verified_at',
        'verification_code',
        'verification_expires_at',
        'verification_attempts',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'verification_expires_at' => 'datetime',
        'verification_attempts' => 'integer',
    ];

    protected $hidden = [
        'pass',
        'remember_token',
    ];

    // Mapping password field ke kolom 'pass'
    public function getAuthPassword()
    {
        return $this->pass;
    }

    // ===== Relasi Siswa: sertifikat yang dimiliki =====
    public function sertifikatDimiliki()
    {
        return $this->belongsTo(SertifikatPKL::class, 'id_sertifikat', 'id_sertifikat');
    }

    // ===== Relasi Mentor: semua sertifikat yang dia buat =====
    public function sertifikatDibuat()
    {
        return $this->hasMany(SertifikatPKL::class, 'id_sertifikat', 'id_sertifikat');
    }

    // Relasi ke portofolio (siswa → portofolionya sendiri)
    public function portofolios()
    {
        return $this->hasMany(Portofolio::class, 'user_pkl_id', 'id');
    }

    // Cek role
    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }
    
    // Relasi ke pengajuan magang
    public function pengajuanMagang()
    {
        return $this->hasMany(\App\Models\PengajuanMagang::class, 'user_id', 'id');
    }

    // Relasi untuk mentors: sertifikat yang dibuat oleh mentor
    public function sertifikatsPKL()
    {
        return $this->hasMany(SertifikatPKL::class, 'created_id', 'id');
    }

    // Relasi ke MentorSiswa (mentor → siswa yang dibimbing)
    public function mentorSiswa()
    {
        return $this->hasMany(MentorSiswa::class, 'mentor_id', 'id');
    }

    // Scope untuk mentor berdasarkan bidang
    public function scopeByBidang($query, $bidang)
    {
        return $query->where('bidang', $bidang);
    }

    // Cek apakah user adalah mentor dengan bidang tertentu
    public function isMentorOfBidang($bidang): bool
    {
        return $this->role === 'mentor' && $this->bidang === $bidang;
    }
}
