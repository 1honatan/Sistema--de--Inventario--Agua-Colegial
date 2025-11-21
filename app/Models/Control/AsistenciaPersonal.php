<?php

namespace App\Models\Control;

use Illuminate\Database\Eloquent\Model;
use App\Models\Personal;

class AsistenciaPersonal extends Model
{
    protected $table = 'control_asistencia_personal';

    protected $fillable = [
        'fecha',
        'personal_id',
        'lunes',
        'martes',
        'miercoles',
        'jueves',
        'viernes',
        'sabado',
        'domingo',
        'observaciones',
    ];

    protected $casts = [
        'fecha' => 'date',
    ];

    // Relación con Personal
    public function personal()
    {
        return $this->belongsTo(Personal::class);
    }
}
