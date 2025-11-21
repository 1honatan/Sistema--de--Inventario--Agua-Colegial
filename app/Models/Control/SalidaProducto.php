<?php

namespace App\Models\Control;

use Illuminate\Database\Eloquent\Model;

class SalidaProducto extends Model
{
    protected $table = 'control_salidas_productos';

    protected $fillable = [
        'tipo_salida',
        'nombre_distribuidor',
        'nombre_cliente',
        'direccion_entrega',
        'telefono_cliente',
        'monto_total',
        'metodo_pago',
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
        'choreados_retorno',
        'hora_llegada',
        'observaciones',
    ];

    protected $casts = [
        'fecha' => 'date',
        'hora_llegada' => 'datetime:H:i',
    ];
}
