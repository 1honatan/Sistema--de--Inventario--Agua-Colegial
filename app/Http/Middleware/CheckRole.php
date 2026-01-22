<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Debe iniciar sesión para acceder a esta página');
        }

        $usuario = Auth::user();

        if ($usuario->estado !== 'activo') {
            Auth::logout();
            return redirect()->route('login')
                ->with('error', 'Su cuenta está inactiva. Contacte al administrador');
        }

        if (!$usuario->rol) {
            abort(403, 'Usuario sin rol asignado');
        }

        $rolUsuario = $usuario->rol->nombre;

        // Admin tiene acceso total
        if ($rolUsuario === 'admin') {
            return $next($request);
        }

        if (!in_array($rolUsuario, $roles, true)) {
            abort(403, 'No tiene permisos para acceder a este módulo');
        }

        return $next($request);
    }
}
