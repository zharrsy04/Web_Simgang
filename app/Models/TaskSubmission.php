<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskSubmission extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'task_id',
        'student_id',
        'notes',
        'file_paths',
        'status',
        'grade',
        'mentor_feedback',
        'submitted_at',
        'graded_at',
        'viewed_at',
    ];

    protected $casts = [
        'file_paths' => 'array',
        'submitted_at' => 'datetime',
        'graded_at' => 'datetime',
        'viewed_at' => 'datetime',
    ];

    public function task()
    {
        return $this->belongsTo(Task::class, 'task_id');
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }
}
