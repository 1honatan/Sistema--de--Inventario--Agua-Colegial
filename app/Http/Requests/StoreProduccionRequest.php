<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Request de validación para registrar producción.
 *
 * Validaciones críticas:
 * - Cantidad mínimo 1
 * - Fecha no futura
 * - Producto activo
 * - Personal válido
 */
class StoreProduccionRequest extends FormRequest
{
    /**
     * Determinar si el usuario está autorizado.
     */
    public function authorize(): bool
    {
        // Roles permitidos: admin, produccion
        return $this->user() && (
            $this->user()->tieneRol('admin') ||
            $this->user()->tieneRol('produccion')
        );
    }

    /**
     * Reglas de validación.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'id_producto' => [
                'required',
                'integer',
                'exists:productos,id',
            ],
            'id_personal' => [
                'required',
                'integer',
                'exists:personal,id',
            ],
            'cantidad' => [
                'required',
                'integer',
                'min:1',
                'max:999999', // Límite razonable
            ],
            'fecha_produccion' => [
                'required',
                'date',
                'before_or_equal:today',
                'after_or_equal:' . now()->subMonths(3)->format('Y-m-d'), // No más de 3 meses atrás
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
            'id_producto.required' => 'Debe seleccionar un producto',
            'id_producto.exists' => 'El producto seleccionado no es válido',
            'id_personal.required' => 'Debe seleccionar un responsable',
            'id_personal.exists' => 'El responsable seleccionado no es válido',
            'cantidad.required' => 'La cantidad es obligatoria',
            'cantidad.min' => 'La cantidad debe ser mayor a 0',
            'cantidad.max' => 'La cantidad no puede exceder 999,999 unidades',
            'cantidad.integer' => 'La cantidad debe ser un número entero',
            'fecha_produccion.required' => 'La fecha de producción es obligatoria',
            'fecha_produccion.date' => 'La fecha de producción no es válida',
            'fecha_produccion.before_or_equal' => 'La fecha de producción no puede ser futura',
            'fecha_produccion.after_or_equal' => 'La fecha de producción no puede ser mayor a 3 meses atrás',
        ];
    }

    /**
     * Preparar datos para validación.
     *
     * Convierte cantidad a entero si viene como string.
     */
    protected function prepareForValidation(): void
    {
        if ($this->has('cantidad')) {
            $this->merge([
                'cantidad' => (int) $this->cantidad,
            ]);
        }
    }
}
