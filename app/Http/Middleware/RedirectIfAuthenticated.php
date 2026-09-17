<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     * Jika user SUDAH login mencoba akses halaman guest (login/register),
     * redirect ke dashboard sesuai role-nya.
     */
    public function handle(Request $request, Closure $next, string ...$guards): mixed
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::guard($guard)->user();

                $dashboardRoutes = [
                    'siswa'       => 'siswa.dashboard',
                    'mentor'      => 'mentor.dashboard',
                    'sekretariat' => 'sekretariat.dashboard',
                ];

                $route = $dashboardRoutes[$user->role] ?? 'home';

                return redirect()->route($route)
                    ->with('info', 'Anda sudah login. Silakan gunakan menu yang tersedia.');
            }
        }

        return $next($request);
    }
}
