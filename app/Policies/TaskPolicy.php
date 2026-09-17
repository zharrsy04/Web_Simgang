<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Task;
use App\Models\PenempatanBidang;

class TaskPolicy
{
    /**
     * Determine if user can create a task
     * Only mentor can create tasks
     */
    public function create(User $user): bool
    {
        return $user->role === 'mentor';
    }

    /**
     * Determine if user can view a task
     */
    public function view(User $user, Task $task): bool
    {
        // Mentor can view only their own tasks
        if ($user->role === 'mentor') {
            return $task->assigned_by === $user->id;
        }

        // Siswa can view their own assigned tasks
        if ($user->role === 'siswa') {
            $sertifikat = \App\Models\SertifikatPKL::getByUser($user);
            return $sertifikat && $task->sertifikat_pkl_id == $sertifikat->id_sertifikat;
        }

        return false;
    }

    /**
     * Determine if user can update a task
     * Only the mentor who created it can update
     */
    public function update(User $user, Task $task): bool
    {
        return $user->role === 'mentor' && $task->assigned_by === $user->id;
    }

    /**
     * Determine if user can delete a task
     * Only the mentor who created it can delete
     */
    public function delete(User $user, Task $task): bool
    {
        return $user->role === 'mentor' && $task->assigned_by === $user->id;
    }

    /**
     * Determine if user can grade submissions for a task
     * Only the mentor who created the task
     */
    public function gradeSubmission(User $user, Task $task): bool
    {
        return $user->role === 'mentor' && $task->assigned_by === $user->id;
    }
}
