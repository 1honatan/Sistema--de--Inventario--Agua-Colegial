<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class LoginController extends Controller
{
    /**
     * Rutas de destino según el rol del usuario.
     * Definidas en un solo lugar para evitar inconsistencias.
     */
    private const RUTAS_POR_ROL = [
        'admin' => 'admin.dashboard',
        'produccion' => 'control.produccion.index',
        'inventario' => 'inventario.index',
        'despacho' => 'control.salidas.index',
    ];

    /**
     * Ruta por defecto si el rol no está definido.
     * NUNCA debe ser 'login' para evitar loops de redirección.
     */
    private const RUTA_DEFAULT = 'admin.dashboard';

    public function showLoginForm(): View|RedirectResponse
    {
        // Si ya está autenticado, redirigir según rol
        if (Auth::check()) {
            return $this->redirigirSegunRol();
        }

        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credenciales = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'min:6'],
        ], [
            'email.required' => 'El correo electrónico es obligatorio',
            'email.email' => 'El formato del correo electrónico no es válido',
            'password.required' => 'La contraseña es obligatoria',
            'password.min' => 'La contraseña debe tener al menos 6 caracteres',
        ]);

        $this->ensureIsNotRateLimited($request);

        if (Auth::attempt($credenciales, $request->filled('remember'))) {
            // Regenerar sesión para prevenir session fixation
            $request->session()->regenerate();

            $currentUserId = Auth::id();
            if (!$currentUserId) {
                Auth::logout();
                $request->session()->invalidate();
                return back()->with('error', 'Error al obtener ID del usuario');
            }

            $usuario = User::query()->find($currentUserId);

            if (!$usuario) {
                Auth::logout();
                $request->session()->invalidate();
                return back()->with('error', 'Error al obtener datos del usuario');
            }

            if ($usuario->estado !== 'activo') {
                Auth::logout();
                $request->session()->invalidate();
                RateLimiter::hit($this->throttleKey($request));
                return back()->with('error', 'Su cuenta está inactiva. Contacte al administrador');
            }

            if (method_exists($usuario, 'actualizarUltimoAcceso')) {
                $usuario->actualizarUltimoAcceso();
            } else {
                $usuario->ultimo_acceso = now();
                $usuario->save();
            }

            RateLimiter::clear($this->throttleKey($request));

            return $this->redirigirSegunRol();
        }

        RateLimiter::hit($this->throttleKey($request));

        return back()
            ->withErrors(['email' => 'Las credenciales no coinciden con nuestros registros'])
            ->withInput($request->only('email'));
    }

    protected function ensureIsNotRateLimited(Request $request): void
    {
        $throttleKey = $this->throttleKey($request);

        if (!RateLimiter::tooManyAttempts($throttleKey, 5)) {
            return;
        }

        $seconds = RateLimiter::availableIn($throttleKey);

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    protected function throttleKey(Request $request): string
    {
        $email = $request->input('email', '');
        $ip = $request->ip() ?? '';
        return Str::transliterate(Str::lower($email) . '|' . $ip);
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Sesión cerrada exitosamente');
    }

    /**
     * Redirige al usuario según su rol.
     *
     * IMPORTANTE: Esta función NUNCA debe redirigir a 'login' cuando el usuario
     * está autenticado, ya que causaría un loop infinito de redirecciones.
     */
    protected function redirigirSegunRol(): RedirectResponse
    {
        $userId = Auth::id();

        // Si no hay usuario autenticado, ir al login (esto solo pasa si se llama incorrectamente)
        if (!$userId) {
            return redirect()->route('login');
        }

        $usuario = User::query()->with('rol')->find($userId);

        // Si el usuario no existe en BD, cerrar sesión y al login
        if (!$usuario) {
            Auth::logout();
            request()->session()->invalidate();
            return redirect()->route('login')->with('error', 'Usuario no encontrado');
        }

        // Obtener nombre del rol (con fallback seguro)
        $rolNombre = $usuario->rol?->nombre ?? 'admin';

        // Buscar la ruta correspondiente al rol
        $rutaNombre = self::RUTAS_POR_ROL[$rolNombre] ?? self::RUTA_DEFAULT;

        // Verificar que la ruta existe antes de redirigir
        try {
            return redirect()->route($rutaNombre);
        } catch (\Exception $e) {
            // Si la ruta no existe, usar la ruta por defecto
            return redirect()->route(self::RUTA_DEFAULT);
        }
    }

    /**
     * Obtiene la ruta de destino para un rol específico.
     * Útil para otros componentes que necesiten conocer las rutas.
     */
    public static function obtenerRutaPorRol(string $rol): string
    {
        return self::RUTAS_POR_ROL[$rol] ?? self::RUTA_DEFAULT;
    }
}
