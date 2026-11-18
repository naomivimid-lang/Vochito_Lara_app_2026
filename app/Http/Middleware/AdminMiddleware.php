<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Solo deja pasar a usuarios con sesión iniciada y marcados como administradores.
     */
    public function handle(Request $request, Closure $next): Response
    {
        abort_unless($request->user()?->is_admin, 403, 'Solo los administradores pueden entrar a esta sección.');

        return $next($request);
    }
}
