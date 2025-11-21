<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

/**
 * Modelo de registro de producción.
 *
 * @property int $id
 * @property int $id_producto
 * @property int $id_personal
 * @property string $lote Código único PROD-{fecha}-{secuencia}
 * @property int $cantidad
 * @property \Carbon\Carbon $fecha_produccion
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read Producto $producto
 * @property-read Personal $personal
 */
class Produccion extends Model
{
    /**
     * Nombre de la tabla asociada al modelo.
     */
    protected $table = 'produccion';

    /**
     * Atributos asignables en masa.
     */
    protected $fillable = [
        'id_producto',
        'id_personal',
        'lote',
        'cantidad',
        'fecha_produccion',
    ];

    /**
     * Conversión de tipos de atributos.
     */
    protected $casts = [
        'fecha_produccion' => 'date',
        'cantidad' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relación: Una producción pertenece a un producto.
     */
    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class, 'id_producto');
    }

    /**
     * Relación: Una producción pertenece a un personal.
     */
    public function personal(): BelongsTo
    {
        return $this->belongsTo(Personal::class, 'id_personal');
    }

    /**
     * Generar código de lote único.
     *
     * CRITICAL: Usar transacción para evitar duplicados con concurrencia
     *
     * Formato: PROD-YYYYMMDD-NNNN
     * Ejemplo: PROD-20251021-0001
     *
     * @return string Código de lote único
     */
    public static function generarCodigoLote(): string
    {
        return DB::transaction(function () {
            $fecha = date('Ymd'); // 20251021
            $patron = "PROD-{$fecha}-%";

            // CRITICAL: lockForUpdate() previene race conditions
            $ultimoLote = self::lockForUpdate()
                ->where('lote', 'LIKE', $patron)
                ->orderBy('lote', 'desc')
                ->value('lote'); // Ejemplo: "PROD-20251021-0042"

            if ($ultimoLote) {
                // Extraer secuencia (últimos 4 caracteres)
                $secuencia = intval(substr($ultimoLote, -4)); // 42
                $nuevaSecuencia = $secuencia + 1; // 43
            } else {
                $nuevaSecuencia = 1; // Primer lote del día
            }

            // Formatear con padding de ceros: 43 → "0043"
            $secuenciaPadded = str_pad((string) $nuevaSecuencia, 4, '0', STR_PAD_LEFT);

            return "PROD-{$fecha}-{$secuenciaPadded}";
        });
    }

    /**
     * Scope: Filtrar producciones por rango de fechas.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  string  $fechaInicio
     * @param  string  $fechaFin
     */
    public function scopePorRangoFechas($query, string $fechaInicio, string $fechaFin)
    {
        return $query->whereBetween('fecha_produccion', [$fechaInicio, $fechaFin]);
    }

    /**
     * Scope: Filtrar producciones del día actual.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     */
    public function scopeDelDia($query)
    {
        return $query->whereDate('fecha_produccion', today());
    }

    /**
     * Scope: Filtrar producciones del mes actual.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     */
    public function scopeDelMes($query)
    {
        return $query->whereYear('fecha_produccion', date('Y'))
                     ->whereMonth('fecha_produccion', date('m'));
    }
}
