<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Request de validación para actualizar usuario.
 *
 * Diferencias con Store:
 * - Nombre de usuario único excepto el actual
 * - Contraseña es opcional (solo si se cambia)
 */
class UpdateUsuarioRequest extends FormRequest
{
    /**
     * Determinar si el usuario está autorizado.
     */
    public function authorize(): bool
    {
        return $this->user() && $this->user()->tieneRol('admin');
    }

    /**
     * Reglas de validación.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $usuarioId = $this->route('usuario')->id;

        return [
            'nombre_usuario' => [
                'required',
                'string',
                'max:100',
                'email',
                Rule::unique('usuarios', 'nombre_usuario')->ignore($usuarioId),
            ],
            'password' => [
                'nullable', // Opcional al actualizar
                'string',
                'min:6',
                'max:255',
                'confirmed',
            ],
            'id_rol' => [
                'required',
                'integer',
                'exists:roles,id',
            ],
            'estado' => [
                'required',
                'in:activo,inactivo',
            ],
        ];
    }

    /**
     * Mensajes de error personalizados.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'nombre_usuario.required' => 'El nombre de usuario es obligatorio',
            'nombre_usuario.unique' => 'Este nombre de usuario ya está registrado',
            'nombre_usuario.email' => 'El nombre de usuario debe ser un correo electrónico válido',
            'password.min' => 'La contraseña debe tener al menos 6 caracteres',
            'password.confirmed' => 'Las contraseñas no coinciden',
            'id_rol.required' => 'Debe seleccionar un rol',
            'id_rol.exists' => 'El rol seleccionado no es válido',
        ];
    }
}
