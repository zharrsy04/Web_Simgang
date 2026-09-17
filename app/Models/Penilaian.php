<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penilaian extends Model
{
    use HasFactory;

    protected $table = 'tbl_penilaian';
    protected $primaryKey = 'id';
    public $timestamps = false;


    protected $fillable = [
        'aspek',
        'nilai',
        'ket',
        'id_sertifikat',
        'is_parent',
        'id_parent'
    ];

    public function sertifikat(){
        return $this->belongsTo(SertifikatPKL::class, 'id_sertifikat', 'id_sertifikat');
    }
}
