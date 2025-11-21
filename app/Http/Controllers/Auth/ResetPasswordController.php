<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;

/**
 * Controlador para restablecer contraseña.
 *
 * Permite a los usuarios restablecer su contraseña usando el enlace
 * recibido por correo electrónico.
 */
class ResetPasswordController extends Controller
{
    /**
     * Mostrar formulario de restablecimiento de contraseña.
     */
    public function showResetForm(Request $request, string $token): View
    {
        return view('auth.passwords.reset', [
            'token' => $token,
            'email' => $request->email,
        ]);
    }

    /**
     * Restablecer la contraseña del usuario.
     */
    public function reset(Request $request): RedirectResponse
    {
        // Validar datos
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'min:6', 'confirmed'],
        ], [
            'token.required' => 'El token de restablecimiento es requerido',
            'email.required' => 'El correo electrónico es obligatorio',
            'email.email' => 'El formato del correo electrónico no es válido',
            'password.required' => 'La contraseña es obligatoria',
            'password.min' => 'La contraseña debe tener al menos 6 caracteres',
            'password.confirmed' => 'Las contraseñas no coinciden',
        ]);

        // Intentar restablecer la contraseña
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->password = $password;
                $user->setRememberToken(Str::random(60));
                $user->save();

                event(new PasswordReset($user));
            }
        );

        // Verificar resultado
        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('login')
                ->with('success', 'Tu contraseña ha sido restablecida exitosamente. Ahora puedes iniciar sesión');
        }

        return back()
            ->withErrors(['email' => $this->getStatusMessage($status)])
            ->withInput();
    }

    /**
     * Obtener mensaje de estado personalizado en español.
     *
     * @param  string  $status
     * @return string
     */
    protected function getStatusMessage(string $status): string
    {
        return match ($status) {
            Password::INVALID_USER => 'No encontramos ningún usuario con ese correo electrónico',
            Password::INVALID_TOKEN => 'El enlace de restablecimiento no es válido o ha expirado',
            default => 'No pudimos restablecer tu contraseña. Inténtalo de nuevo',
        };
    }
}
