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
    public function showLoginForm(): View|RedirectResponse
    {
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
            $request->session()->regenerate();

            $currentUserId = Auth::id();
            if (!$currentUserId) {
                Auth::logout();
                return back()->with('error', 'Error al obtener ID del usuario');
            }

            $usuario = User::query()->find($currentUserId);

            if (!$usuario) {
                Auth::logout();
                return back()->with('error', 'Error al obtener datos del usuario');
            }

            if ($usuario->estado !== 'activo') {
                Auth::logout();
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

    protected function redirigirSegunRol(): RedirectResponse
    {
        $userId = Auth::id();
        if (!$userId) {
            Auth::logout();
            return redirect()->route('login')->with('error', 'Sesión inválida');
        }

        $usuario = User::query()->with('rol')->find($userId);

        if (!$usuario) {
            Auth::logout();
            return redirect()->route('login')->with('error', 'Usuario no encontrado');
        }

        if (!$usuario->rol) {
            Auth::logout();
            return redirect()->route('login')->with('error', 'Usuario sin rol asignado');
        }

        $rolNombre = $usuario->rol->nombre ?? 'admin';

        return match ($rolNombre) {
            'admin' => redirect()->route('admin.dashboard'),
            'produccion' => redirect()->route('control.produccion.index'),
            'inventario' => redirect()->route('inventario.dashboard'),
            default => redirect()->route('admin.dashboard'),
        };
    }
}
