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
        // Procesar el request primero (para que Laravel inicialice la sesión)
        $response = $next($request);

        // Regenerar el token CSRF DESPUÉS del request, solo en peticiones GET exitosas
        // Esto evita que expire cuando el usuario deja el formulario abierto
        if ($request->isMethod('GET') && $request->hasSession() && $response->isSuccessful()) {
            $request->session()->regenerateToken();
        }

        return $response;
    }
}
