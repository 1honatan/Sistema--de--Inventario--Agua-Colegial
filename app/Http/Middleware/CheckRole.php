<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware para verificar roles de usuario.
 *
 * Uso:
 * Route::middleware(['auth', 'role:admin'])->group(...);
 * Route::middleware(['auth', 'role:admin,produccion'])->group(...);
 */
class CheckRole
{
    /**
     * Manejar una solicitud entrante.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles  Roles permitidos (admin, produccion, inventario)
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        // Verificar que el usuario esté autenticado
        if (!auth()->check()) {
            return redirect()->route('login')
                ->with('error', 'Debe iniciar sesión para acceder a esta página');
        }

        $usuario = auth()->user();

        // Verificar que el usuario esté activo
        if ($usuario->estado !== 'activo') {
            auth()->logout();
            return redirect()->route('login')
                ->with('error', 'Su cuenta está inactiva. Contacte al administrador');
        }

        // Verificar que el usuario tenga un rol asignado
        if (!$usuario->rol) {
            abort(403, 'Usuario sin rol asignado');
        }

        // Verificar que el usuario tenga uno de los roles permitidos
        $rolUsuario = $usuario->rol->nombre;

        // El administrador siempre tiene acceso a todos los módulos
        if ($rolUsuario === 'admin') {
            return $next($request);
        }

        // Verificar roles específicos para otros usuarios
        if (!in_array($rolUsuario, $roles, true)) {
            abort(403, 'No tiene permisos para acceder a este módulo');
        }

        return $next($request);
    }
}
