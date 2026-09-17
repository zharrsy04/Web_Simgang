<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleProtection
{
    /**
     * Handle middleware untuk proteksi berdasarkan role
     * Pastikan user hanya akses fitur sesuai dengan role-nya
     * 
     * @param Request $request
     * @param Closure $next
     * @param string ...$roles Role yang diizinkan mengakses route ini
     * @return \Illuminate\Http\Response
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // Jika user tidak login
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu');
        }

        $user = Auth::user();
        
        // Cek apakah role user ada dalam role yang diizinkan
        if (in_array($user->role, $roles)) {
            return $next($request);
        }

        // Jika user mencoba akses fitur yang tidak sesuai dengan role-nya
        // Redirect ke dashboard sesuai dengan role
        $dashboardRoutes = [
            'siswa' => 'siswa.dashboard',
            'mentor' => 'mentor.dashboard',
            'sekretariat' => 'sekretariat.dashboard',
        ];
        
        $dashboardRoute = $dashboardRoutes[$user->role] ?? 'home';
        
        return redirect()->route($dashboardRoute)
            ->with('error', 'Anda tidak memiliki akses ke halaman ini. Akses dibatasi berdasarkan role Anda.');
    }
}
