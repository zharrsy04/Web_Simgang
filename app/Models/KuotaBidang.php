<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KuotaBidang extends Model
{
    use HasFactory;

    protected $table = 'kuota_bidang';
    protected $fillable = ['nama_bidang', 'kuota'];

    /**
     * Get the number of active interns in this field
     */
    public function getActiveTerisiAttribute()
    {
        $today = now()->format('Y-m-d');
        return SertifikatPKL::where('bidang', $this->nama_bidang)
            ->where('sts_diambil', false)
            ->whereDate('tgl_mulai', '<=', $today)
            ->whereDate('tgl_selesai', '>=', $today)
            ->count();
    }
}
