<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Modelo de movimiento de inventario.
 *
 * @property int $id
 * @property int $id_producto
 * @property string $tipo_movimiento (entrada|salida)
 * @property int $cantidad
 * @property string|null $origen
 * @property string|null $destino
 * @property string|null $referencia
 * @property int|null $id_usuario
 * @property \Carbon\Carbon $fecha_movimiento
 * @property string|null $observacion
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read Producto $producto
 * @property-read Usuario|null $usuario
 */
class Inventario extends Model
{
    /**
     * Nombre de la tabla asociada al modelo.
     */
    protected $table = 'inventario';

    /**
     * Atributos asignables en masa.
     */
    protected $fillable = [
        'id_producto',
        'tipo_movimiento',
        'cantidad',
        'origen',
        'destino',
        'referencia',
        'id_usuario',
        'fecha_movimiento',
        'observacion',
    ];

    /**
     * Conversión de tipos de atributos.
     */
    protected $casts = [
        'fecha_movimiento' => 'datetime',
        'cantidad' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relación: Un movimiento de inventario pertenece a un producto.
     */
    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class, 'id_producto');
    }

    /**
     * Relación: Un movimiento de inventario pertenece a un usuario (quien lo registró).
     */
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'id_usuario');
    }

    /**
     * Calcular stock disponible de un producto.
     *
     * CRITICAL: Esta consulta debe ejecutarse en <500ms para tiempo real
     *
     * @param  int  $idProducto
     * @return int Stock disponible (puede ser negativo si hay inconsistencias)
     */
    public static function stockDisponible(int $idProducto): int
    {
        $entradas = self::where('id_producto', $idProducto)
            ->where('tipo_movimiento', 'entrada')
            ->sum('cantidad');

        $salidas = self::where('id_producto', $idProducto)
            ->where('tipo_movimiento', 'salida')
            ->sum('cantidad');

        $stock = (int) ($entradas - $salidas);
        return max(0, $stock); // No mostrar números negativos
    }

    /**
     * Registrar entrada de inventario con trazabilidad.
     *
     * @param  int|string  $idProducto
     * @param  int|string  $cantidad
     * @param  string|null  $observacion
     * @param  string|null  $origen
     * @param  string|null  $destino
     * @param  string|null  $referencia
     * @param  int|string|null  $idUsuario
     * @return self
     */
    public static function registrarEntrada(
        int|string $idProducto,
        int|string $cantidad,
        ?string $observacion = null,
        ?string $origen = null,
        ?string $destino = null,
        ?string $referencia = null,
        int|string|null $idUsuario = null
    ): self {
        return self::create([
            'id_producto' => (int) $idProducto,
            'tipo_movimiento' => 'entrada',
            'cantidad' => (int) $cantidad,
            'origen' => $origen,
            'destino' => $destino,
            'referencia' => $referencia,
            'id_usuario' => $idUsuario ? (int) $idUsuario : null,
            'fecha_movimiento' => now(),
            'observacion' => $observacion,
        ]);
    }

    /**
     * Registrar salida de inventario con trazabilidad.
     *
     * @param  int|string  $idProducto
     * @param  int|string  $cantidad
     * @param  string|null  $observacion
     * @param  string|null  $origen
     * @param  string|null  $destino
     * @param  string|null  $referencia
     * @param  int|string|null  $idUsuario
     * @return self
     */
    public static function registrarSalida(
        int|string $idProducto,
        int|string $cantidad,
        ?string $observacion = null,
        ?string $origen = null,
        ?string $destino = null,
        ?string $referencia = null,
        int|string|null $idUsuario = null
    ): self {
        return self::create([
            'id_producto' => (int) $idProducto,
            'tipo_movimiento' => 'salida',
            'cantidad' => (int) $cantidad,
            'origen' => $origen,
            'destino' => $destino,
            'referencia' => $referencia,
            'id_usuario' => $idUsuario ? (int) $idUsuario : null,
            'fecha_movimiento' => now(),
            'observacion' => $observacion,
        ]);
    }

    /**
     * Scope: Filtrar entradas.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     */
    public function scopeEntradas($query)
    {
        return $query->where('tipo_movimiento', 'entrada');
    }

    /**
     * Scope: Filtrar salidas.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     */
    public function scopeSalidas($query)
    {
        return $query->where('tipo_movimiento', 'salida');
    }

    /**
     * Scope: Filtrar por rango de fechas.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $fechaInicio
     * @param  string  $fechaFin
     */
    public function scopePorRangoFechas($query, string $fechaInicio, string $fechaFin)
    {
        return $query->whereBetween('fecha_movimiento', [$fechaInicio, $fechaFin]);
    }
}
