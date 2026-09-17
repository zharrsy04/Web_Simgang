<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Portofolio extends Model
{
    use HasFactory;

    protected $table = 'portofolios';  // sesuai migration
    protected $primaryKey = 'id';      // default primary key

    protected $fillable = [
        'user_pkl_id',
        'sertifikat_pkl_id', // foreign key ke tbl_sertifikat_pkl
        'nama',
        'bidang',
        'deskripsi',
        'file',
    ];

    public function sertifikatPKL()
    {
        return $this->belongsTo(SertifikatPKL::class, 'sertifikat_pkl_id', 'id_sertifikat');
    }

    public function user()
{
    return $this->belongsTo(User::class, 'user_pkl_id');
}

}
