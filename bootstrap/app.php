<?php

// =====================================================================
// LARAVEL 12 — resources/bootstrap/app.php
// Daftarkan middleware 'role' agar bisa dipakai di routes.
// =====================================================================

use App\Http\Middleware\EnsureRole;
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

        // Daftarkan alias middleware 'role'
        $middleware->alias([
            'role' => EnsureRole::class,
        ]);

    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->create();


// =====================================================================
// LARAVEL 10/11 (Kernel.php) — jika masih menggunakan Kernel:
// Tambahkan ke $routeMiddleware di app/Http/Kernel.php:
// =====================================================================
//
//   protected $routeMiddleware = [
//       // ... middleware lainnya ...
//       'role' => \App\Http\Middleware\EnsureRole::class,
//   ];
//
// =====================================================================