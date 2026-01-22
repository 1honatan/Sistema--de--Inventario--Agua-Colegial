<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Inventario extends Model
{
    protected $table = 'inventario';

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

    protected $casts = [
        'fecha_movimiento' => 'datetime',
        'cantidad' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class, 'id_producto');
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'id_usuario');
    }

    public static function stockDisponible(int $idProducto): int
    {
        $entradas = self::where('id_producto', $idProducto)
            ->where('tipo_movimiento', 'entrada')
            ->sum('cantidad');

        $salidas = self::where('id_producto', $idProducto)
            ->where('tipo_movimiento', 'salida')
            ->sum('cantidad');

        return max(0, (int) ($entradas - $salidas));
    }

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

    public function scopeEntradas($query)
    {
        return $query->where('tipo_movimiento', 'entrada');
    }

    public function scopeSalidas($query)
    {
        return $query->where('tipo_movimiento', 'salida');
    }

    public function scopePorRangoFechas($query, string $fechaInicio, string $fechaFin)
    {
        return $query->whereBetween('fecha_movimiento', [$fechaInicio, $fechaFin]);
    }
}
