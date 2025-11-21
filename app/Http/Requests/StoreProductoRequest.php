<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Request de validación para crear producto.
 *
 * Validaciones:
 * - Nombre del producto (único)
 * - Tipo de producto válido
 * - Unidad de medida
 * - Estado activo/inactivo
 */
class StoreProductoRequest extends FormRequest
{
    /**
     * Determinar si el usuario está autorizado.
     */
    public function authorize(): bool
    {
        // Solo administradores pueden crear productos
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
            'nombre' => [
                'required',
                'string',
                'max:100',
                'unique:productos,nombre',
            ],
            'descripcion' => [
                'nullable',
                'string',
                'max:255',
            ],
            'id_tipo_producto' => [
                'nullable',
                'integer',
                'exists:tipos_producto,id',
            ],
            'tipo' => [
                'nullable',
                'string',
                'max:100',
            ],
            'unidad_medida' => [
                'required',
                'string',
                'max:50',
                'in:unidad,litro,bolsa',
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
            'nombre.required' => 'El nombre del producto es obligatorio',
            'nombre.unique' => 'Ya existe un producto con este nombre',
            'nombre.max' => 'El nombre no puede exceder 100 caracteres',
            'descripcion.max' => 'La descripción no puede exceder 255 caracteres',
            'id_tipo_producto.required' => 'Debe seleccionar un tipo de producto',
            'id_tipo_producto.exists' => 'El tipo de producto seleccionado no es válido',
            'tipo.required' => 'El tipo de producto es obligatorio',
            'tipo.max' => 'El tipo no puede exceder 100 caracteres',
            'unidad_medida.required' => 'La unidad de medida es obligatoria',
            'unidad_medida.in' => 'La unidad de medida debe ser: unidad, litro o bolsa',
            'unidad_medida.max' => 'La unidad de medida no puede exceder 50 caracteres',
            'estado.required' => 'El estado es obligatorio',
            'estado.in' => 'El estado debe ser activo o inactivo',
        ];
    }

    /**
     * Preparar datos para validación.
     */
    protected function prepareForValidation(): void
    {
        // Limpiar nombre
        if ($this->has('nombre')) {
            $this->merge([
                'nombre' => trim($this->nombre),
            ]);
        }

        // Limpiar tipo
        if ($this->has('tipo')) {
            $this->merge([
                'tipo' => trim($this->tipo),
            ]);
        }
    }
}
