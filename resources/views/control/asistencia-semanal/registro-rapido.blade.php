@extends('layouts.app')

@section('title', 'Registro Rápido de Asistencia')

@push('styles')
<style>
    body {
        background: linear-gradient(135deg, #0ea5e9 0%, #06b6d4 50%, #14b8a6 100%);
        min-height: 100vh;
    }

    .registro-card {
        background: white;
        border-radius: 20px;
        padding: 2rem;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        margin-bottom: 1.5rem;
        transition: all 0.3s ease;
    }

    .registro-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
    }

    .personal-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1.5rem;
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        margin-bottom: 1rem;
        transition: all 0.3s ease;
        background: linear-gradient(145deg, #ffffff 0%, #f9fafb 100%);
    }

    .personal-item:hover {
        border-color: #3b82f6;
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.2);
    }

    .personal-info {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .personal-avatar {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.5rem;
        font-weight: bold;
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    }

    .personal-details h4 {
        margin: 0;
        font-size: 1.1rem;
        font-weight: 700;
        color: #1f2937;
    }

    .personal-details p {
        margin: 0;
        font-size: 0.9rem;
        color: #6b7280;
    }

    .estado-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.9rem;
        font-weight: 700;
        margin-right: 1rem;
    }

    .estado-badge.entrada {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }

    .estado-badge.pendiente {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        color: white;
        box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
    }

    .estado-badge.completo {
        background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
        color: white;
        box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
    }

    .btn-action {
        padding: 0.75rem 1.5rem;
        border-radius: 10px;
        font-weight: 700;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.95rem;
    }

    .btn-entrada {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
    }

    .btn-entrada:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(16, 185, 129, 0.4);
    }

    .btn-salida {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
    }

    .btn-salida:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(239, 68, 68, 0.4);
    }

    .btn-detalle {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        color: white;
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        padding: 0.75rem 1.5rem;
        border-radius: 10px;
        font-weight: 700;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.95rem;
        text-decoration: none;
    }

    .btn-detalle:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(59, 130, 246, 0.4);
        color: white;
        text-decoration: none;
    }

    .btn-editar {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        color: white;
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        padding: 0.75rem 1.5rem;
        border-radius: 10px;
        font-weight: 700;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.95rem;
        text-decoration: none;
    }

    .btn-editar:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(59, 130, 246, 0.4);
        color: white;
        text-decoration: none;
    }

    .btn-eliminar {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
        padding: 0.75rem 1.5rem;
        border-radius: 10px;
        font-weight: 700;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.95rem;
    }

    .btn-eliminar:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(239, 68, 68, 0.4);
    }

    .btn-action:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        transform: none !important;
    }

    .header-info {
        background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 100%);
        color: white;
        padding: 2rem;
        border-radius: 16px;
        margin-bottom: 2rem;
        box-shadow: 0 8px 20px rgba(30, 58, 138, 0.3);
    }

    .hora-actual {
        font-size: 3rem;
        font-weight: 900;
        text-align: center;
        margin: 1rem 0;
        color: white;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
    }

    .search-box {
        position: relative;
    }

    .search-box input {
        width: 100%;
        padding: 1rem 1rem 1rem 3rem;
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        font-size: 1rem;
        transition: all 0.3s ease;
    }

    .search-box input:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .search-box i {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: #9ca3af;
        font-size: 1.2rem;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4 py-4">
    <!-- Header con hora actual -->
    <div class="header-info">
        <h2 class="text-center text-2xl font-bold mb-2">
            <i class="fas fa-clock mr-2"></i>
            Registro de Asistencia
        </h2>
        <div class="hora-actual" id="horaActual">--:--:--</div>
        <p class="text-center text-lg opacity-90">
            {{ $hoy->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY') }}
        </p>
    </div>

    <div class="registro-card">
        <!-- Header con buscador y botones -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="search-box flex-grow-1 mr-3 mb-0">
                <i class="fas fa-search"></i>
                <input type="text"
                       id="searchPersonal"
                       placeholder="Buscar personal por nombre..."
                       autocomplete="off">
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('control.asistencia-semanal.index') }}"
                   class="btn btn-primary btn-lg d-flex align-items-center gap-2"
                   style="white-space: nowrap;">
                    <i class="fas fa-calendar-week"></i>
                    Ver Registro Semanal
                </a>
                <a href="{{ route('control.empleados.create') }}"
                   class="btn btn-success btn-lg d-flex align-items-center gap-2"
                   style="white-space: nowrap;">
                    <i class="fas fa-user-plus"></i>
                    Crear Empleado
                </a>
            </div>
        </div>

        <!-- Lista de Personal -->
        <div id="personalList">
            @forelse($personal as $empleado)
                @php
                    $asistencia = $asistenciasHoy->get($empleado->id);
                    $tieneEntrada = $asistencia && $asistencia->entrada_hora;
                    $tieneSalida = $asistencia && $asistencia->salida_hora;
                @endphp

                <div class="personal-item" data-nombre="{{ strtolower($empleado->nombre_completo) }}">
                    <div class="personal-info">
                        <div class="personal-avatar">
                            {{ strtoupper(substr($empleado->nombre_completo, 0, 1)) }}
                        </div>
                        <div class="personal-details">
                            <h4>{{ $empleado->nombre_completo }}</h4>
                            <p>{{ $empleado->cargo }}</p>
                        </div>
                    </div>

                    <div class="d-flex align-items-center">
                        @if($tieneSalida)
                            <span class="estado-badge completo">
                                <i class="fas fa-check-double"></i>
                                Completo ({{ \Carbon\Carbon::parse($asistencia->entrada_hora)->format('H:i') }} - {{ \Carbon\Carbon::parse($asistencia->salida_hora)->format('H:i') }})
                            </span>
                        @elseif($tieneEntrada)
                            <span class="estado-badge entrada">
                                <i class="fas fa-sign-in-alt"></i>
                                Entrada: {{ \Carbon\Carbon::parse($asistencia->entrada_hora)->format('H:i') }}
                            </span>
                        @else
                            <span class="estado-badge pendiente">
                                <i class="fas fa-clock"></i>
                                Pendiente
                            </span>
                        @endif

                        <div class="btn-group" role="group">
                            <form action="{{ route('control.asistencia-semanal.registrar-entrada') }}"
                                  method="POST"
                                  style="display: inline;">
                                @csrf
                                <input type="hidden" name="personal_id" value="{{ $empleado->id }}">
                                <button type="submit"
                                        class="btn-action btn-entrada"
                                        {{ $tieneEntrada ? 'disabled' : '' }}>
                                    <i class="fas fa-sign-in-alt"></i>
                                    Entrada
                                </button>
                            </form>

                            <form action="{{ route('control.asistencia-semanal.registrar-salida') }}"
                                  method="POST"
                                  style="display: inline;"
                                  class="ml-2">
                                @csrf
                                <input type="hidden" name="personal_id" value="{{ $empleado->id }}">
                                <button type="submit"
                                        class="btn-action btn-salida"
                                        {{ !$tieneEntrada || $tieneSalida ? 'disabled' : '' }}>
                                    <i class="fas fa-sign-out-alt"></i>
                                    Salida
                                </button>
                            </form>

                            <a href="{{ route('control.empleados.show', $empleado->id) }}"
                               class="btn-action btn-detalle ml-2"
                               title="Ver detalle">
                                <i class="fas fa-eye"></i>
                            </a>

                            <a href="{{ route('control.empleados.edit', $empleado->id) }}"
                               class="btn-action btn-editar ml-2"
                               title="Editar empleado">
                                <i class="fas fa-edit"></i>
                            </a>

                            <form action="{{ route('control.empleados.destroy', $empleado->id) }}"
                                  method="POST"
                                  style="display: inline;"
                                  class="ml-2"
                                  onsubmit="return confirm('¿Está seguro de eliminar a {{ $empleado->nombre_completo }}? Esta acción no se puede deshacer.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="btn-action btn-eliminar">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-5">
                    <i class="fas fa-users-slash text-gray-300" style="font-size: 4rem;"></i>
                    <p class="text-gray-600 mt-3">No hay personal activo registrado</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Actualizar hora actual cada segundo
    function actualizarHora() {
        const now = new Date();
        const horas = String(now.getHours()).padStart(2, '0');
        const minutos = String(now.getMinutes()).padStart(2, '0');
        const segundos = String(now.getSeconds()).padStart(2, '0');
        $('#horaActual').text(`${horas}:${minutos}:${segundos}`);
    }

    actualizarHora();
    setInterval(actualizarHora, 1000);

    // Búsqueda en tiempo real
    $('#searchPersonal').on('keyup', function() {
        const searchTerm = $(this).val().toLowerCase();

        $('.personal-item').each(function() {
            const nombre = $(this).data('nombre');
            if (nombre.includes(searchTerm)) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });

    // Confirmación antes de registrar (solo para entrada/salida, no para eliminar)
    $('form').on('submit', function(e) {
        const isEntrada = $(this).find('.btn-entrada').length > 0;
        const isSalida = $(this).find('.btn-salida').length > 0;

        if (isEntrada || isSalida) {
            const accion = isEntrada ? 'entrada' : 'salida';
            if (!confirm(`¿Confirmar registro de ${accion}?`)) {
                e.preventDefault();
            }
        }
    });

    // Animación de entrada
    $('.personal-item').hide().each(function(index) {
        $(this).delay(50 * index).fadeIn(300);
    });
});
</script>
@endpush
