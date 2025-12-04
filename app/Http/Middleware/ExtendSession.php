<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware para extender automáticamente la sesión
 *
 * Simplemente mantiene la sesión activa en cada request
 * NO toca el token CSRF para evitar conflictos
 */
class ExtendSession
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Simplemente procesar el request
        // Laravel automáticamente extenderá la sesión si está activa
        return $next($request);
    }
}
