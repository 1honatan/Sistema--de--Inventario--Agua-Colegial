<?php

namespace App\Models\Control;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Personal;
use App\Models\Vehiculo;

class SalidaProducto extends Model
{
    protected $table = 'control_salidas_productos';

    protected $fillable = [
        'tipo_salida',
        'nombre_distribuidor',
        'chofer',
        'nombre_cliente',
        'direccion_entrega',
        'telefono_cliente',
        'responsable',
        'responsable_venta',
        'vehiculo_placa',
        'fecha',
        'lunes',
        'martes',
        'miercoles',
        'jueves',
        'viernes',
        'sabado',
        'domingo',
        'retornos',
        'retorno_botellones',
        'retorno_bolo_grande',
        'retorno_bolo_pequeno',
        'retorno_gelatina',
        'retorno_agua_saborizada',
        'retorno_agua_limon',
        'retorno_agua_natural',
        'retorno_hielo',
        'retorno_dispenser',
        'botellones',
        'bolo_grande',
        'bolo_pequeño',
        'gelatina',
        'agua_saborizada',
        'agua_limon',
        'agua_natural',
        'hielo',
        'dispenser',
        'choreados',
        'hora_llegada',
        'observaciones',
    ];

    protected $casts = [
        'fecha' => 'date',
        'hora_llegada' => 'datetime:H:i',
    ];

    /**
     * Nota: Actualmente los campos chofer, responsable, responsable_venta y vehiculo_placa
     * son strings. Para usar estas relaciones, necesitarás migrar a FK:
     * - chofer → chofer_id (foreignId a personal)
     * - responsable → responsable_id (foreignId a personal)
     * - responsable_venta → responsable_venta_id (foreignId a personal)
     * - vehiculo_placa → vehiculo_id (foreignId a vehiculos)
     */

    /**
     * Relación: Chofer que realizó la salida
     * TODO: Requiere migración para agregar chofer_id
     */
    // public function chofer(): BelongsTo
    // {
    //     return $this->belongsTo(Personal::class, 'chofer_id');
    // }

    /**
     * Relación: Responsable de la salida
     * TODO: Requiere migración para agregar responsable_id
     */
    // public function responsablePersonal(): BelongsTo
    // {
    //     return $this->belongsTo(Personal::class, 'responsable_id');
    // }

    /**
     * Relación: Responsable de venta
     * TODO: Requiere migración para agregar responsable_venta_id
     */
    // public function responsableVenta(): BelongsTo
    // {
    //     return $this->belongsTo(Personal::class, 'responsable_venta_id');
    // }

    /**
     * Relación: Vehículo utilizado
     * TODO: Requiere migración para agregar vehiculo_id
     */
    // public function vehiculo(): BelongsTo
    // {
    //     return $this->belongsTo(Vehiculo::class, 'vehiculo_id');
    // }
}
