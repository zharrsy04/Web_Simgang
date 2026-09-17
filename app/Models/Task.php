<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $table = 'tasks'; // Pastikan ini sesuai dengan nama tabel
    protected $primaryKey = 'id'; // Default, bisa dihilangkan jika 'id'
    // public $timestamps = true; // Default true, bisa dihilangkan jika ada created_at/updated_at

    protected $fillable = [
        'sertifikat_pkl_id',
        'assigned_by',
        'title',
        'description',
        'due_date',
        'status',
        'is_read',
        'catatan_izin',
    ];

    protected $casts = [
        'due_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relasi ke siswa penerima tugas
    public function recipientStudent()
    {
        return $this->belongsTo(SertifikatPKL::class, 'sertifikat_pkl_id', 'id_sertifikat');
    }

    // Relasi ke pengguna (mentor/admin) yang menetapkan tugas
    public function assignedByUser()
    {
        return $this->belongsTo(User::class, 'assigned_by', 'id');
    }
    
    // Relasi ke submissions
    public function submissions()
    {
        return $this->hasMany(TaskSubmission::class, 'task_id');
    }
    
    // Get submission untuk user tertentu
    public function submissionByUser($userId)
    {
        return $this->submissions()->where('student_id', $userId)->first();
    }

    // Cek apakah tugas ini cross-bidang (mentor pemberi ≠ mentor pembimbing)
    public function isCrossBidang(): bool
    {
        if (!$this->assigned_by || !$this->recipientStudent) {
            return false;
        }

        // Get mentor pembimbing siswa
        $mentorSiswa = MentorSiswa::where('siswa_id', $this->sertifikat_pkl_id)
            ->where('is_active', true)
            ->first();

        if (!$mentorSiswa) {
            return true; // Tidak ada mentor pembimbing, dianggap cross-bidang
        }

        return $this->assigned_by !== $mentorSiswa->mentor_id;
    }

    // Get mentor pembimbing siswa
    public function getMentorPembimbing()
    {
        if (!$this->recipientStudent) {
            return null;
        }

        return MentorSiswa::where('siswa_id', $this->sertifikat_pkl_id)
            ->where('is_active', true)
            ->with('mentor')
            ->first();
    }
}
