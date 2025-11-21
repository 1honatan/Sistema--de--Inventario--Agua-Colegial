<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Request de validación para crear usuario.
 *
 * Valida:
 * - Nombre de usuario único
 * - Contraseña segura con confirmación
 * - Rol válido existente
 * - Estado válido
 */
class StoreUsuarioRequest extends FormRequest
{
    /**
     * Determinar si el usuario está autorizado para hacer esta solicitud.
     */
    public function authorize(): bool
    {
        // Solo administradores pueden crear usuarios
        return $this->user() && $this->user()->tieneRol('admin');
    }

    /**
     * Reglas de validación.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'nombre_usuario' => [
                'required',
                'string',
                'max:100',
                'unique:usuarios,nombre_usuario',
                'email', // Debe ser formato email
            ],
            'password' => [
                'required',
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
            'nombre_usuario.max' => 'El nombre de usuario no puede exceder 100 caracteres',
            'password.required' => 'La contraseña es obligatoria',
            'password.min' => 'La contraseña debe tener al menos 6 caracteres',
            'password.max' => 'La contraseña no puede exceder 255 caracteres',
            'password.confirmed' => 'Las contraseñas no coinciden',
            'id_rol.required' => 'Debe seleccionar un rol',
            'id_rol.exists' => 'El rol seleccionado no es válido',
            'estado.required' => 'El estado es obligatorio',
            'estado.in' => 'El estado debe ser activo o inactivo',
        ];
    }

    /**
     * Nombres de atributos personalizados.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'nombre_usuario' => 'nombre de usuario',
            'password' => 'contraseña',
            'id_rol' => 'rol',
            'estado' => 'estado',
        ];
    }
}
