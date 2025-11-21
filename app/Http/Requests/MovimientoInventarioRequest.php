<?php

declare(strict_types=1);

namespace App\Http\Requests;

/**
 * Request de validación para movimientos de inventario.
 *
 * Alias de StoreInventarioRequest para mantener consistencia
 * de nomenclatura en el proyecto.
 *
 * Este FormRequest valida:
 * - Producto existe y es válido
 * - Tipo de movimiento (entrada/salida)
 * - Cantidad positiva y dentro de límites
 * - Fecha válida (no futura)
 * - Stock disponible en salidas
 * - Campos de trazabilidad (origen, destino, referencia)
 *
 * @see StoreInventarioRequest Para la implementación completa
 */
class MovimientoInventarioRequest extends StoreInventarioRequest
{
    // Hereda todas las validaciones de StoreInventarioRequest
    // Esta clase existe como alias para mantener nomenclatura consistente
}
