@extends('layouts.app')

@section('title', 'Asistencia Semanal')
@section('page-subtitle', 'Registro semanal estilo cuaderno - ' . $inicioSemana->format('d/m/Y') . ' al ' . $finSemana->format('d/m/Y'))

@push('styles')
<style>
    /* Estilo de cuaderno manuscrito */
    .cuaderno-container {
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    .cuaderno-header {
        background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 100%);
        color: white;
        padding: 1.5rem;
        border-bottom: 4px solid #1e40af;
    }

    .tabla-asistencia {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.9rem;
    }

    .tabla-asistencia th {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        color: #1e3a8a;
        font-weight: 700;
        padding: 1rem;
        text-align: center;
        border: 2px solid #dee2e6;
        position: sticky;
        top: 0;
        z-index: 10;
    }

    .tabla-asistencia td {
        padding: 0.75rem;
        border: 1px solid #dee2e6;
        vertical-align: top;
        min-height: 80px;
    }

    .tabla-asistencia tbody tr:hover {
        background: #f8f9fa;
    }

    .celda-empleado {
        background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
        font-weight: 700;
        color: #1e3a8a;
        white-space: nowrap;
        width: 200px;
        position: sticky;
        left: 0;
        z-index: 5;
    }

    .celda-dia {
        background: #ffffff;
        min-width: 150px;
        position: relative;
    }

    .dia-header {
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 0.5px;
    }

    .dia-numero {
        font-size: 1.5rem;
        font-weight: 900;
        color: #1e3a8a;
    }

    /* Estilos de registros de asistencia */
    .registro-asistencia {
        background: #ffffff;
        border: 2px solid #e2e8f0;
        border-radius: 8px;
        padding: 0.5rem;
        margin-bottom: 0.5rem;
        position: relative;
        transition: all 0.3s ease;
    }

    .registro-asistencia:hover {
        border-color: #1e3a8a;
        box-shadow: 0 2px 8px rgba(30, 58, 138, 0.15);
        transform: translateY(-2px);
    }

    .horario-entrada-salida {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-weight: 700;
        color: #1e3a8a;
        margin-bottom: 0.25rem;
    }

    .badge-estado {
        display: inline-flex;
        align-items: center;
        padding: 0.25rem 0.5rem;
        border-radius: 12px;
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .badge-presente {
        background: #d1fae5;
        color: #065f46;
    }

    .badge-ausente {
        background: #fee2e2;
        color: #991b1b;
    }

    .badge-permiso {
        background: #fef3c7;
        color: #92400e;
    }

    .badge-tardanza {
        background: #fed7aa;
        color: #9a3412;
    }

    .observaciones-text {
        font-size: 0.75rem;
        color: #6b7280;
        font-style: italic;
        margin-top: 0.25rem;
        padding: 0.25rem;
        background: #f9fafb;
        border-radius: 4px;
    }

    /* Botón de agregar registro */
    .btn-agregar-registro {
        display: block;
        width: 100%;
        padding: 0.5rem;
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        border: 2px dashed #059669;
        border-radius: 8px;
        font-size: 0.8rem;
        font-weight: 700;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-agregar-registro:hover {
        background: linear-gradient(135deg, #059669 0%, #047857 100%);
        transform: scale(1.02);
    }

    /* Navegación de semanas */
    .navegacion-semanas {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .btn-semana {
        padding: 0.75rem 1.5rem;
        background: white;
        border: 2px solid #1e3a8a;
        color: #1e3a8a;
        border-radius: 8px;
        font-weight: 700;
        transition: all 0.3s ease;
    }

    .btn-semana:hover {
        background: #1e3a8a;
        color: white;
        transform: translateY(-2px);
    }

    .semana-actual {
        padding: 0.75rem 2rem;
        background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 100%);
        color: white;
        border-radius: 8px;
        font-weight: 800;
        font-size: 1.1rem;
        box-shadow: 0 4px 12px rgba(30, 58, 138, 0.3);
    }

    /* Acciones rápidas */
    .acciones-registro {
        position: absolute;
        top: 0.25rem;
        right: 0.25rem;
        display: flex;
        gap: 0.25rem;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .registro-asistencia:hover .acciones-registro {
        opacity: 1;
    }

    .btn-accion-mini {
        padding: 0.25rem 0.5rem;
        font-size: 0.7rem;
        border-radius: 4px;
        border: none;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-editar-mini {
        background: #3b82f6;
        color: white;
    }

    .btn-editar-mini:hover {
        background: #2563eb;
    }

    .btn-eliminar-mini {
        background: #ef4444;
        color: white;
    }

    .btn-eliminar-mini:hover {
        background: #dc2626;
    }

    /* Celda vacía */
    .celda-vacia {
        text-align: center;
        color: #9ca3af;
        font-style: italic;
        padding: 2rem;
    }

    /* Scroll horizontal */
    .tabla-scroll {
        overflow-x: auto;
        margin: 0 -1.5rem;
        padding: 0 1.5rem;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .tabla-asistencia {
            font-size: 0.8rem;
        }

        .celda-empleado {
            width: 150px;
            font-size: 0.85rem;
        }

        .celda-dia {
            min-width: 120px;
        }

        .dia-numero {
            font-size: 1.2rem;
        }
    }

    /* Día actual destacado */
    .dia-actual {
        background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
    }

    .dia-actual .dia-header {
        color: #1e40af;
        font-weight: 900;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4 py-4">

    <!-- Navegación de Semanas -->
    <div class="navegacion-semanas">
        <a href="{{ route('control.asistencia-semanal.index', ['semana' => $inicioSemana->copy()->subWeek()->format('Y-m-d')]) }}"
           class="btn-semana">
            <i class="fas fa-chevron-left"></i> Semana Anterior
        </a>

        <div class="semana-actual">
            <i class="fas fa-calendar-week mr-2"></i>
            {{ $inicioSemana->format('d/m/Y') }} - {{ $finSemana->format('d/m/Y') }}
        </div>

        <a href="{{ route('control.asistencia-semanal.index', ['semana' => $inicioSemana->copy()->addWeek()->format('Y-m-d')]) }}"
           class="btn-semana">
            Semana Siguiente <i class="fas fa-chevron-right"></i>
        </a>
    </div>

    <!-- Botón para hoy -->
    @if(!$fechaSeleccionada->isCurrentWeek())
    <div class="text-center mb-4">
        <a href="{{ route('control.asistencia-semanal.index') }}" class="btn btn-primary">
            <i class="fas fa-calendar-day"></i> Ir a Semana Actual
        </a>
    </div>
    @endif

    <!-- Cuaderno de Asistencia -->
    <div class="cuaderno-container">
        <div class="cuaderno-header">
            <div>
                <h3 class="text-xl font-bold mb-2">
                    <i class="fas fa-book"></i> Registro de Asistencia Semanal
                </h3>
                <p class="text-sm opacity-90">Control manuscrito digital del personal</p>
            </div>
        </div>

        <div class="p-4">
            <div class="tabla-scroll">
                <table class="tabla-asistencia">
                    <thead>
                        <tr>
                            <th class="celda-empleado">
                                <i class="fas fa-user"></i> EMPLEADO
                            </th>
                            @foreach($diasSemana as $dia)
                                <th class="dia-header {{ $dia['fecha']->isToday() ? 'dia-actual' : '' }}">
                                    <div>{{ strtoupper($dia['nombre']) }}</div>
                                    <div class="dia-numero">{{ $dia['numero'] }}</div>
                                    <div style="font-size: 0.7rem; font-weight: 500; margin-top: 0.25rem;">
                                        {{ $dia['fecha']->format('M Y') }}
                                    </div>
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($personal as $empleado)
                            <tr>
                                <td class="celda-empleado">
                                    <div class="flex items-center gap-2">
                                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 font-bold">
                                            {{ strtoupper(substr($empleado->nombre_completo, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="font-bold">{{ $empleado->nombre_completo }}</div>
                                            <div class="text-xs text-gray-600">{{ $empleado->cargo }}</div>
                                        </div>
                                    </div>
                                </td>

                                @foreach($diasSemana as $dia)
                                    <td class="celda-dia {{ $dia['fecha']->isToday() ? 'dia-actual' : '' }}">
                                        @php
                                            $key = $empleado->id . '_' . $dia['fecha']->format('Y-m-d');
                                            $registrosDelDia = $asistencias->get($key, collect());
                                        @endphp

                                        @if($registrosDelDia->count() > 0)
                                            @foreach($registrosDelDia as $registro)
                                                <div class="registro-asistencia">
                                                    <div class="horario-entrada-salida">
                                                        <i class="fas fa-sign-in-alt text-green-600"></i>
                                                        {{ \Carbon\Carbon::parse($registro->entrada_hora)->format('H:i') }}
                                                        @if($registro->salida_hora)
                                                            <i class="fas fa-arrow-right text-gray-400"></i>
                                                            <i class="fas fa-sign-out-alt text-red-600"></i>
                                                            {{ \Carbon\Carbon::parse($registro->salida_hora)->format('H:i') }}
                                                        @endif
                                                    </div>

                                                    <span class="badge-estado badge-{{ $registro->estado }}">
                                                        @if($registro->estado == 'presente')
                                                            <i class="fas fa-check-circle mr-1"></i>
                                                        @elseif($registro->estado == 'ausente')
                                                            <i class="fas fa-times-circle mr-1"></i>
                                                        @elseif($registro->estado == 'permiso')
                                                            <i class="fas fa-file-alt mr-1"></i>
                                                        @elseif($registro->estado == 'tardanza')
                                                            <i class="fas fa-clock mr-1"></i>
                                                        @endif
                                                        {{ ucfirst($registro->estado) }}
                                                    </span>

                                                    @if($registro->observaciones)
                                                        <div class="observaciones-text">
                                                            <i class="fas fa-comment-dots"></i> {{ $registro->observaciones }}
                                                        </div>
                                                    @endif

                                                    <div class="acciones-registro">
                                                        <a href="{{ route('control.asistencia-semanal.edit', $registro->id) }}"
                                                           class="btn-accion-mini btn-editar-mini">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <form action="{{ route('control.asistencia-semanal.destroy', $registro->id) }}"
                                                              method="POST"
                                                              style="display: inline;"
                                                              onsubmit="return confirm('¿Eliminar este registro de asistencia?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn-accion-mini btn-eliminar-mini">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="celda-vacia">
                                                <i class="fas fa-minus text-gray-300"></i>
                                            </div>
                                        @endif

                                        <a href="{{ route('control.asistencia-semanal.create', ['fecha' => $dia['fecha']->format('Y-m-d'), 'personal_id' => $empleado->id]) }}"
                                           class="btn-agregar-registro">
                                            <i class="fas fa-plus"></i> Agregar
                                        </a>
                                    </td>
                                @endforeach
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-8">
                                    <i class="fas fa-users-slash text-gray-300 text-5xl mb-3"></i>
                                    <p class="text-gray-600 font-semibold">No hay personal activo registrado</p>
                                    <a href="{{ route('admin.personal.index') }}" class="btn btn-primary mt-3">
                                        <i class="fas fa-user-plus"></i> Agregar Personal
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Leyenda -->
    <div class="mt-4 p-4 bg-white rounded-lg shadow">
        <h4 class="font-bold text-gray-700 mb-3"><i class="fas fa-info-circle"></i> Leyenda de Estados</h4>
        <div class="flex flex-wrap gap-3">
            <span class="badge-estado badge-presente"><i class="fas fa-check-circle mr-1"></i> Presente</span>
            <span class="badge-estado badge-ausente"><i class="fas fa-times-circle mr-1"></i> Ausente</span>
            <span class="badge-estado badge-permiso"><i class="fas fa-file-alt mr-1"></i> Permiso</span>
            <span class="badge-estado badge-tardanza"><i class="fas fa-clock mr-1"></i> Tardanza</span>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Animación suave al cargar
    $('.registro-asistencia').hide().each(function(index) {
        $(this).delay(50 * index).fadeIn(300);
    });

    // Tooltip para botones
    $('[title]').tooltip();
});
</script>
@endpush
