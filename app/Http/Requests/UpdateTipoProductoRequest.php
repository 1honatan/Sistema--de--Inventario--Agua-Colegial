<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Request de validación para actualizar tipo de producto.
 */
class UpdateTipoProductoRequest extends FormRequest
{
    /**
     * Determinar si el usuario está autorizado.
     */
    public function authorize(): bool
    {
        // Solo administradores pueden actualizar tipos de producto
        return $this->user() && $this->user()->tieneRol('admin');
    }

    /**
     * Reglas de validación.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $tipoProductoId = $this->route('tipos_producto');

        return [
            'nombre' => [
                'required',
                'string',
                'max:100',
                Rule::unique('tipos_producto', 'nombre')->ignore($tipoProductoId),
            ],
            'codigo' => [
                'required',
                'string',
                'max:20',
                Rule::unique('tipos_producto', 'codigo')->ignore($tipoProductoId),
                'regex:/^[A-Z0-9_-]+$/', // Solo mayúsculas, números, guiones y guiones bajos
            ],
            'descripcion' => [
                'nullable',
                'string',
                'max:500',
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
            'nombre.required' => 'El nombre del tipo de producto es obligatorio',
            'nombre.unique' => 'Ya existe un tipo de producto con este nombre',
            'nombre.max' => 'El nombre no puede exceder 100 caracteres',
            'codigo.required' => 'El código es obligatorio',
            'codigo.unique' => 'Ya existe un tipo de producto con este código',
            'codigo.max' => 'El código no puede exceder 20 caracteres',
            'codigo.regex' => 'El código solo puede contener letras mayúsculas, números, guiones y guiones bajos',
            'descripcion.max' => 'La descripción no puede exceder 500 caracteres',
            'estado.required' => 'El estado es obligatorio',
            'estado.in' => 'El estado debe ser activo o inactivo',
        ];
    }

    /**
     * Preparar datos para validación.
     */
    protected function prepareForValidation(): void
    {
        // Convertir el código a mayúsculas automáticamente
        if ($this->has('codigo')) {
            $this->merge([
                'codigo' => strtoupper(trim($this->codigo)),
            ]);
        }

        // Limpiar nombre
        if ($this->has('nombre')) {
            $this->merge([
                'nombre' => trim($this->nombre),
            ]);
        }
    }
}
