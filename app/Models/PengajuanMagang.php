<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajuanMagang extends Model
{
    use HasFactory;
    
    protected $table = 'pengajuan_magang';
    
    protected $fillable = [
        'user_id',
        'nama',
        'nim_nis',
        'asal_sekolah',
        'instansi_id',
        'jurusan',
        'tempat_lahir',
        'tgl_lahir',
        'bidang',
        'tanggal_mulai',
        'tanggal_selesai',
        'dokumen_path',
        'status',
        'alasan_reject',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'tgl_lahir' => 'date',
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'approved_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            if (empty($model->instansi_id) && !empty($model->asal_sekolah)) {
                $instansi = Instansi::findMatchingInstansi($model->asal_sekolah);
                if ($instansi) {
                    $model->instansi_id = $instansi->id;
                }
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by', 'id');
    }

    public function instansi()
    {
        return $this->belongsTo(Instansi::class, 'instansi_id');
    }
}
