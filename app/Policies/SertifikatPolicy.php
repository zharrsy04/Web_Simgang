<?php

namespace App\Policies;

use App\Models\User;
use App\Models\SertifikatPKL;

class SertifikatPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if user can view a sertifikat
     */
    public function view(User $user, SertifikatPKL $sertifikat): bool
    {
        // Siswa dapat melihat sertifikat mereka sendiri
        if ($user->role === 'siswa') {
            return $sertifikat->nim_nis === $user->username;
        }

        // Mentor dapat melihat sertifikat yang mereka buat
        if ($user->role === 'mentor') {
            return $sertifikat->created_id === $user->id;
        }

        // Sekretariat dapat melihat semua sertifikat
        if ($user->role === 'sekretariat') {
            return true;
        }

        return false;
    }

    /**
     * Determine if user can create a sertifikat
     */
    public function create(User $user): bool
    {
        // Hanya mentor dan sekretariat dapat membuat sertifikat
        return in_array($user->role, ['mentor', 'sekretariat']);
    }

    /**
     * Determine if user can update a sertifikat
     */
    public function update(User $user, SertifikatPKL $sertifikat): bool
    {
        // Mentor dapat edit sertifikat
        if ($user->role === 'mentor') {
            return true;
        }

        // Sekretariat dapat edit semua sertifikat
        if ($user->role === 'sekretariat') {
            return true;
        }

        return false;
    }

    /**
     * Determine if user can delete a sertifikat
     */
    public function delete(User $user, SertifikatPKL $sertifikat): bool
    {
        // Mentor dapat hapus sertifikat
        if ($user->role === 'mentor') {
            return true;
        }

        // Sekretariat dapat hapus semua sertifikat (dengan validasi status tertentu di controller)
        if ($user->role === 'sekretariat') {
            return true;
        }

        return false;
    }

    /**
     * Determine if user can print a sertifikat
     */
    public function print(User $user, SertifikatPKL $sertifikat): bool
    {
        // Mentor dan sekretariat dapat print
        if (in_array($user->role, ['mentor', 'sekretariat'])) {
            return true;
        }

        // Siswa dapat print sertifikat mereka sendiri
        if ($user->role === 'siswa') {
            return $sertifikat->nim_nis === $user->username;
        }

        return false;
    }

    /**
     * Determine if user can manage handover
     */
    public function manageHandover(User $user, SertifikatPKL $sertifikat): bool
    {
        // Sekretariat dapat manage handover
        if ($user->role === 'sekretariat') {
            return true;
        }

        return false;
    }
}
