<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;

class RoleAuthorizationService
{
    /**
     * Cek apakah user bisa mengakses halaman riwayat (read)
     */
    public static function canViewHistory($user = null)
    {
        $user = $user ?? Auth::user();
        if (!$user) return false;
        
        // Semua role bisa melihat riwayat
        return in_array($user->role, ['siswa', 'mentor']);
    }

    /**
     * Cek apakah user bisa create riwayat
     */
    public static function canCreateHistory($user = null)
    {
        $user = $user ?? Auth::user();
        if (!$user) return false;
        
        // Hanya mentor
        return $user->role === 'mentor';
    }

    /**
     * Cek apakah user bisa update riwayat
     */
    public static function canUpdateHistory($user = null)
    {
        $user = $user ?? Auth::user();
        if (!$user) return false;
        
        // Hanya mentor
        return $user->role === 'mentor';
    }

    /**
     * Cek apakah user bisa delete riwayat
     */
    public static function canDeleteHistory($user = null)
    {
        $user = $user ?? Auth::user();
        if (!$user) return false;
        
        // Hanya mentor
        return $user->role === 'mentor';
    }

    /**
     * Cek apakah user bisa download sertifikat
     */
    public static function canDownloadCertificate($user = null)
    {
        // Download sertifikat bisa dilakukan oleh siapa saja (public)
        return true;
    }

    /**
     * Cek apakah user bisa akses dashboard siswa
     */
    public static function canAccessSiswaPanel($user = null)
    {
        $user = $user ?? Auth::user();
        if (!$user) return false;
        
        return $user->role === 'siswa';
    }

    /**
     * Cek apakah user bisa akses dashboard mentor
     */
    public static function canAccessMentorPanel($user = null)
    {
        $user = $user ?? Auth::user();
        if (!$user) return false;
        
        return $user->role === 'mentor';
    }

    /**
     * Get hak akses berdasarkan role
     */
    public static function getPermissionsByRole($role)
    {
        $permissions = [
            'siswa' => [
                'view_landing' => true,
                'view_history' => true,
                'download_certificate' => true,
                'access_siswa_dashboard' => true,
                'create_history' => false,
                'update_history' => false,
                'delete_history' => false,
                'access_mentor_panel' => false,
            ],
            'mentor' => [
                'view_landing' => true,
                'view_history' => true,
                'download_certificate' => true,
                'access_siswa_dashboard' => false,
                'create_history' => true,
                'update_history' => true,
                'delete_history' => true,
                'access_mentor_panel' => true,
            ],
        ];

        return $permissions[$role] ?? [];
    }

    /**
     * Log akses yang tidak authorized
     */
    public static function logUnauthorizedAttempt($action, $user = null)
    {
        $user = $user ?? Auth::user();
        \Illuminate\Support\Facades\Log::warning(
            "Unauthorized access attempt: {$action}",
            [
                'user_id' => $user->id ?? null,
                'user_role' => $user->role ?? null,
                'username' => $user->username ?? null,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]
        );
    }
}
