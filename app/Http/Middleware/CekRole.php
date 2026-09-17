<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CekRole
{

    public function handle(Request $request, Closure $next, $role)
    {
        $user = Session::get('user');

        // Jika user belum login
        if (!$user) {
            return redirect('/login');
        }

        // Jika role tidak sesuai
        if ($user->role !== $role) {
            abort(403, 'Akses ditolak. Anda tidak memiliki izin.');
        }

        return $next($request);
    }
}
