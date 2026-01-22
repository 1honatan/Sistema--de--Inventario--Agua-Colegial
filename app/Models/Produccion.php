<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class Produccion extends Model
{
    protected $table = 'produccion';

    protected $fillable = [
        'id_producto',
        'id_personal',
        'lote',
        'cantidad',
        'fecha_produccion',
    ];

    protected $casts = [
        'fecha_produccion' => 'date',
        'cantidad' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class, 'id_producto');
    }

    public function personal(): BelongsTo
    {
        return $this->belongsTo(Personal::class, 'id_personal');
    }

    public static function generarCodigoLote(): string
    {
        return DB::transaction(function () {
            $fecha = date('Ymd');
            $patron = "PROD-{$fecha}-%";

            $ultimoLote = self::lockForUpdate()
                ->where('lote', 'LIKE', $patron)
                ->orderBy('lote', 'desc')
                ->value('lote');

            if ($ultimoLote) {
                $secuencia = intval(substr($ultimoLote, -4));
                $nuevaSecuencia = $secuencia + 1;
            } else {
                $nuevaSecuencia = 1;
            }

            $secuenciaPadded = str_pad((string) $nuevaSecuencia, 4, '0', STR_PAD_LEFT);

            return "PROD-{$fecha}-{$secuenciaPadded}";
        });
    }

    public function scopePorRangoFechas($query, string $fechaInicio, string $fechaFin)
    {
        return $query->whereBetween('fecha_produccion', [$fechaInicio, $fechaFin]);
    }

    public function scopeDelDia($query)
    {
        return $query->whereDate('fecha_produccion', today());
    }

    public function scopeDelMes($query)
    {
        return $query->whereYear('fecha_produccion', date('Y'))
                     ->whereMonth('fecha_produccion', date('m'));
    }
}
