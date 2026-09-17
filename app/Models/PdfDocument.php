<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage; // Penting untuk file_url

class PdfDocument extends Model
{
    use HasFactory;

    /**
     * Nama tabel
     */
    protected $table = 'pdf_documents';

    /**
     * Atribut yang dapat diisi secara massal.
     */
    protected $fillable = [
          // Sesuaikan dengan field form
        'id_sertifikat',    // ID unik sertifikat
        'nim_nis',
        'title',
        'description',
        'filename',
        'file_path',
        'file_size',
        'uploaded_at',
        'type',
    ];

    /**
     * Atribut yang harus di-casting ke tipe data tertentu.
     */
    protected $casts = [
        'uploaded_at' => 'datetime',
        'file_size' => 'integer',
    ];

    /**
     * Relasi dengan SertifikatPKL
     */
    public function sertifikatPkl()
    {
        return $this->belongsTo(SertifikatPKL::class, 'id_sertifikat');
    }

    /**
     * Accessor untuk mendapatkan ukuran file dalam format human readable
     */
    public function getFileSizeHumanAttribute()
    {
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    /**
     * Accessor untuk mendapatkan URL file
     */
    public function getFileUrlAttribute()
    {
        return asset('storage/' . $this->file_path);
    }
}