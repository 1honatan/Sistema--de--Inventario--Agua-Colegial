<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AlertaStock extends Model
{
    protected $table = 'alertas_stock';

    protected $fillable = [
        'id_producto',
        'cantidad_minima',
        'cantidad_actual',
        'estado_alerta',
        'nivel_urgencia',
        'fecha_alerta',
        'fecha_atencion',
        'observaciones',
    ];

    protected $casts = [
        'cantidad_minima' => 'integer',
        'cantidad_actual' => 'integer',
        'fecha_alerta' => 'datetime',
        'fecha_atencion' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class, 'id_producto');
    }

    public static function generarSiNecesario(Producto $producto, int $cantidadMinima = 10): ?self
    {
        $stockActual = Inventario::stockDisponible($producto->id);

        if ($stockActual >= $cantidadMinima) {
            return null;
        }

        $alertaExistente = self::where('id_producto', $producto->id)
            ->where('estado_alerta', 'activa')
            ->first();

        if ($alertaExistente) {
            $alertaExistente->update([
                'cantidad_actual' => $stockActual,
                'nivel_urgencia' => self::determinarNivelUrgencia($stockActual, $cantidadMinima),
            ]);
            return $alertaExistente;
        }

        return self::create([
            'id_producto' => $producto->id,
            'cantidad_minima' => $cantidadMinima,
            'cantidad_actual' => $stockActual,
            'estado_alerta' => 'activa',
            'nivel_urgencia' => self::determinarNivelUrgencia($stockActual, $cantidadMinima),
            'fecha_alerta' => now(),
        ]);
    }

    protected static function determinarNivelUrgencia(int $stockActual, int $cantidadMinima): string
    {
        $porcentaje = ($stockActual / $cantidadMinima) * 100;

        if ($stockActual <= 0) return 'critica';
        if ($porcentaje < 25) return 'alta';
        if ($porcentaje < 50) return 'media';
        return 'baja';
    }

    public function marcarComoAtendida(?string $observaciones = null): bool
    {
        return $this->update([
            'estado_alerta' => 'atendida',
            'fecha_atencion' => now(),
            'observaciones' => $observaciones ?? $this->observaciones,
        ]);
    }

    public function marcarComoIgnorada(?string $observaciones = null): bool
    {
        return $this->update([
            'estado_alerta' => 'ignorada',
            'fecha_atencion' => now(),
            'observaciones' => $observaciones ?? $this->observaciones,
        ]);
    }

    public function estaActiva(): bool
    {
        return $this->estado_alerta === 'activa';
    }

    public function scopeActivas($query)
    {
        return $query->where('estado_alerta', 'activa');
    }

    public function scopePorNivelUrgencia($query, string $nivel)
    {
        return $query->where('nivel_urgencia', $nivel);
    }

    public function scopeOrdenadoPorUrgencia($query)
    {
        return $query->orderByRaw("FIELD(nivel_urgencia, 'critica', 'alta', 'media', 'baja')");
    }

    public function colorNivelUrgencia(): string
    {
        return match ($this->nivel_urgencia) {
            'critica' => 'bg-red-600 text-white',
            'alta' => 'bg-orange-500 text-white',
            'media' => 'bg-yellow-500 text-gray-900',
            'baja' => 'bg-blue-500 text-white',
            default => 'bg-gray-500 text-white',
        };
    }

    public function iconoNivelUrgencia(): string
    {
        return match ($this->nivel_urgencia) {
            'critica' => 'fa-solid fa-circle-exclamation',
            'alta' => 'fa-solid fa-triangle-exclamation',
            'media' => 'fa-solid fa-exclamation',
            'baja' => 'fa-solid fa-info-circle',
            default => 'fa-solid fa-bell',
        };
    }
}
