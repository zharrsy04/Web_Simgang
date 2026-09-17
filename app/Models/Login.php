<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class Login extends Authenticatable
{
    use HasFactory, Notifiable; // Jika kamu ingin menggunakan fitur notifikasi

    protected $table = 'tbl_login';
    protected $primaryKey = 'id';
    public $timestamps = false; // Jika tabel kamu tidak memiliki timestamps

    protected $fillable = [
        'username',
        'pass',
        'role',
    ];

    protected $hidden = [
        'pass',
        'remember_token',
    ];

    public function getAuthPassword()
    {
        return $this->pass;
    }

    // Jika kamu memiliki relasi dengan tabel lain, definisikan di sini
}