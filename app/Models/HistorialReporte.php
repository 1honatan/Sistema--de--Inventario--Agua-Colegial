<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Modelo de historial de reportes generados.
 *
 * @property int $id
 * @property string $tipo (produccion|inventario|despachos)
 * @property string|null $fecha_inicio
 * @property string|null $fecha_fin
 * @property int $id_usuario
 * @property string $formato (pdf|excel)
 * @property array|null $filtros
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\Models\User $usuario
 */
class HistorialReporte extends Model
{
    /**
     * Nombre de la tabla asociada al modelo.
     */
    protected $table = 'historial_reportes';

    /**
     * Atributos asignables en masa.
     */
    protected $fillable = [
        'tipo',
        'fecha_inicio',
        'fecha_fin',
        'id_usuario',
        'formato',
        'filtros',
    ];

    /**
     * Conversión de tipos de atributos.
     */
    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
        'filtros' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relación: Un historial pertenece a un usuario.
     */
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'id_usuario');
    }
}
