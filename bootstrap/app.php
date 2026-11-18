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
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
        ]);

        // La ruta llamada 'login' es POST; sin esto Laravel mandaría al invitado a un GET que no existe
        $middleware->redirectGuestsTo(fn () => route('vochito.iniciosesion'));
        $middleware->redirectUsersTo(fn () => route('vochito.inicio'));
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
