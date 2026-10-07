<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'role'            => \App\Http\Middleware\RoleMiddleware::class,
            'roleProtection'  => \App\Http\Middleware\RoleProtection::class,
            'guest'           => \App\Http\Middleware\RedirectIfAuthenticated::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\HttpException $e, $request) {
            if ($e->getStatusCode() === 419) {
                return redirect()->route('login')->with('error', 'Sesi Anda telah kedaluwarsa. Silakan coba login kembali.');
            }
        });
        $exceptions->respond(function ($response, $e, $request) {
            if ($response->getStatusCode() === 419) {
                return redirect()->route('login')->with('error', 'Sesi Anda telah kedaluwarsa. Silakan coba login kembali.');
            }
            return $response;
        });
    })->create();
