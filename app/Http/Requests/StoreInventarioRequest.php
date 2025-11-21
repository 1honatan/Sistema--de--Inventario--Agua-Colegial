<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Inventario;

/**
 * Request de validación para movimientos de inventario.
 *
 * Validaciones críticas:
 * - Stock disponible si es salida
 * - Tipo de movimiento válido
 * - Cantidad positiva
 */
class StoreInventarioRequest extends FormRequest
{
    /**
     * Determinar si el usuario está autorizado.
     */
    public function authorize(): bool
    {
        // Roles permitidos: admin, inventario, produccion
        return $this->user() && (
            $this->user()->tieneRol('admin') ||
            $this->user()->tieneRol('inventario') ||
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
            'tipo_movimiento' => [
                'required',
                'in:entrada,salida',
            ],
            'id_usuario' => [
                'required',
                'integer',
                'exists:usuarios,id',
            ],
            'cantidad' => [
                'required',
                'integer',
                'min:1',
                'max:999999',
            ],
            'fecha_movimiento' => [
                'required',
                'date',
                'before_or_equal:today',
            ],
            'origen' => [
                'nullable',
                'string',
                'max:200',
            ],
            'destino' => [
                'nullable',
                'string',
                'max:200',
            ],
            'referencia' => [
                'nullable',
                'string',
                'max:100',
            ],
            'observacion' => [
                'nullable',
                'string',
                'max:500',
            ],
        ];
    }

    /**
     * Configurar validador después de crearlo.
     *
     * Valida stock disponible si es salida.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            // Solo validar stock si es salida
            if ($this->tipo_movimiento === 'salida') {
                $stockDisponible = Inventario::stockDisponible((int) $this->id_producto);

                if ($this->cantidad > $stockDisponible) {
                    $validator->errors()->add(
                        'cantidad',
                        "Stock insuficiente. Disponible: {$stockDisponible} unidades"
                    );
                }
            }
        });
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
            'tipo_movimiento.required' => 'Debe seleccionar el tipo de movimiento',
            'tipo_movimiento.in' => 'El tipo de movimiento debe ser entrada o salida',
            'id_usuario.required' => 'Debe seleccionar un usuario responsable',
            'id_usuario.exists' => 'El usuario seleccionado no es válido',
            'cantidad.required' => 'La cantidad es obligatoria',
            'cantidad.min' => 'La cantidad debe ser mayor a 0',
            'cantidad.max' => 'La cantidad no puede exceder 999,999 unidades',
            'cantidad.integer' => 'La cantidad debe ser un número entero',
            'fecha_movimiento.required' => 'La fecha es obligatoria',
            'fecha_movimiento.before_or_equal' => 'La fecha no puede ser futura',
            'origen.max' => 'El origen no puede exceder 200 caracteres',
            'destino.max' => 'El destino no puede exceder 200 caracteres',
            'referencia.max' => 'La referencia no puede exceder 100 caracteres',
            'observacion.max' => 'La observación no puede exceder 500 caracteres',
        ];
    }

    /**
     * Preparar datos para validación.
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
