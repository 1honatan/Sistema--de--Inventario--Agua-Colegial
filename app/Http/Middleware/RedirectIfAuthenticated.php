<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Http\Controllers\Auth\LoginController;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware para redirigir usuarios autenticados.
 *
 * Este middleware previene que usuarios ya autenticados accedan a páginas
 * como login o registro, redirigiéndolos a su página principal según su rol.
 *
 * IMPORTANTE: Este middleware está diseñado para NUNCA causar loops de redirección.
 */
class RedirectIfAuthenticated
{
    /**
     * Manejar una solicitud entrante.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$guards
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::guard($guard)->user();

                // Verificar que el usuario existe y está activo
                if (!$user || $user->estado !== 'activo') {
                    // Usuario inválido o inactivo: cerrar sesión y continuar al login
                    Auth::guard($guard)->logout();
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();
                    return $next($request);
                }

                // Obtener la ruta de destino según el rol
                $rolNombre = $user->rol?->nombre ?? 'admin';
                $rutaDestino = LoginController::obtenerRutaPorRol($rolNombre);

                // Redirigir a la página correspondiente
                return redirect()->route($rutaDestino);
            }
        }

        return $next($request);
    }
}
