<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Password;

/**
 * Controlador para solicitar restablecimiento de contraseña.
 *
 * Permite a los usuarios solicitar un enlace para restablecer su contraseña
 * cuando la han olvidado.
 */
class ForgotPasswordController extends Controller
{
    /**
     * Mostrar formulario para solicitar enlace de restablecimiento.
     */
    public function showLinkRequestForm(): View
    {
        return view('auth.passwords.email');
    }

    /**
     * Enviar enlace de restablecimiento de contraseña.
     */
    public function sendResetLinkEmail(Request $request): RedirectResponse
    {
        // Validar email
        $request->validate(
            ['email' => ['required', 'email']],
            [
                'email.required' => 'El correo electrónico es obligatorio',
                'email.email' => 'El formato del correo electrónico no es válido',
            ]
        );

        // Verificar que el usuario existe y está activo
        $usuario = \App\Models\Usuario::where('email', $request->email)->first();

        if (!$usuario) {
            return back()
                ->withErrors(['email' => 'No encontramos ningún usuario con ese correo electrónico'])
                ->withInput();
        }

        if ($usuario->estado !== 'activo') {
            return back()
                ->withErrors(['email' => 'Su cuenta está inactiva. Contacte al administrador'])
                ->withInput();
        }

        // Enviar enlace de restablecimiento
        $status = Password::sendResetLink(
            $request->only('email')
        );

        // Verificar resultado
        if ($status === Password::RESET_LINK_SENT) {
            return back()->with('success', 'Te hemos enviado por correo el enlace para restablecer tu contraseña');
        }

        return back()
            ->withErrors(['email' => 'No pudimos enviar el enlace de restablecimiento. Inténtalo de nuevo'])
            ->withInput();
    }
}
