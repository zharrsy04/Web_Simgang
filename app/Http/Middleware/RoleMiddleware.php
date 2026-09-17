<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // Ambil user dari guard default (web)
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // Cek role user
        if (in_array($user->role, $roles)) {
            return $next($request);
        }

        // Redirect sesuai role kalau tidak cocok
        switch ($user->role) {
            case 'siswa':
                return redirect()->route('siswa.dashboard')->with('error', 'Akses ditolak.');
            case 'mentor':
                return redirect()->route('mentor.dashboard')->with('error', 'Akses ditolak.');
            case 'sekretariat':
                return redirect()->route('sekretariat.dashboard')->with('error', 'Akses ditolak.');
            default:
                return redirect()->route('dashboard')->with('error', 'Akses ditolak.');
        }
    }
}
