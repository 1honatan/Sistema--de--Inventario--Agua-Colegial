<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware para refrescar el token CSRF y evitar errores 419
 *
 * Este middleware regenera el token CSRF en cada request para
 * evitar que expire cuando el usuario deja la página abierta
 */
class RefreshCsrfToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Regenerar el token CSRF en cada request GET
        // Esto evita que expire cuando el usuario deja el formulario abierto
        if ($request->isMethod('GET')) {
            $request->session()->regenerateToken();
        }

        return $next($request);
    }
}
