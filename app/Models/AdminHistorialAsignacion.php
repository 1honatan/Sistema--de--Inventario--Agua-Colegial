<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdminHistorialAsignacion extends Model
{
    use HasFactory;

    protected $table = 'admin_historial_asignaciones';

    protected $fillable = [
        'id_asignacion',
        'accion',
        'detalles',
        'realizado_por',
    ];

    /**
     * Relación con la asignación
     */
    public function asignacion(): BelongsTo
    {
        return $this->belongsTo(AdminAsignacion::class, 'id_asignacion');
    }

    /**
     * Relación con el usuario que realizó la acción
     */
    public function realizadoPor(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'realizado_por');
    }
}
