<?php

// bootstrap/app.php
// En Laravel 11 ya no existe Kernel.php, el middleware se registra aquí.
// Agrega el alias 'rol' dentro de withMiddleware:

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

        // Confiar en todos los proxies (Railway usa reverse proxy)
        $middleware->trustProxies(at: '*');

        // Registrar alias del middleware de roles
        $middleware->alias([
            'rol'                => \App\Http\Middleware\VerificarRol::class,
            'conductor.activo'   => \App\Http\Middleware\VerificarEstatusConductor::class,
        ]);

    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
