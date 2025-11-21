@extends('layouts.app')

@section('title', 'Mi Asistencia')
@push('styles')
<style>
    body {
        background: linear-gradient(135deg, #10b981 0%, #059669 50%, #047857 100%);
        min-height: 100vh;
    }

    .mi-registro-container {
        max-width: 600px;
        margin: 0 auto;
    }

    .welcome-card {
        background: white;
        border-radius: 24px;
        padding: 3rem;
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.15);
        text-align: center;
        margin-bottom: 2rem;
    }

    .avatar-grande {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 3rem;
        font-weight: bold;
        margin: 0 auto 1.5rem;
        box-shadow: 0 8px 20px rgba(16, 185, 129, 0.4);
    }

    .nombre-usuario {
        font-size: 1.8rem;
        font-weight: 900;
        color: #1f2937;
        margin-bottom: 0.5rem;
    }

    .cargo-usuario {
        font-size: 1.1rem;
        color: #6b7280;
        margin-bottom: 2rem;
    }

    .hora-display {
        background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
        border-radius: 16px;
        padding: 2rem;
        margin-bottom: 2rem;
    }

    .hora-actual-grande {
        font-size: 4rem;
        font-weight: 900;
        color: #10b981;
        text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
        line-height: 1;
    }

    .fecha-actual {
        font-size: 1.2rem;
        color: #6b7280;
        font-weight: 600;
        margin-top: 1rem;
    }

    .action-card {
        background: white;
        border-radius: 20px;
        padding: 2rem;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        margin-bottom: 1.5rem;
    }

    .btn-marcar {
        width: 100%;
        padding: 1.5rem;
        border-radius: 16px;
        font-size: 1.3rem;
        font-weight: 800;
        border: none;
        cursor: pointer;
        transition: all 0.4s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .btn-marcar i {
        font-size: 1.8rem;
    }

    .btn-marcar-entrada {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        box-shadow: 0 8px 20px rgba(16, 185, 129, 0.4);
    }

    .btn-marcar-entrada:hover:not(:disabled) {
        transform: translateY(-4px);
        box-shadow: 0 12px 30px rgba(16, 185, 129, 0.5);
    }

    .btn-marcar-salida {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
        box-shadow: 0 8px 20px rgba(239, 68, 68, 0.4);
    }

    .btn-marcar-salida:hover:not(:disabled) {
        transform: translateY(-4px);
        box-shadow: 0 12px 30px rgba(239, 68, 68, 0.5);
    }

    .btn-marcar:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        transform: none !important;
    }

    .estado-actual {
        background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
        border-left: 6px solid #3b82f6;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 2rem;
    }

    .estado-actual h4 {
        color: #1e40af;
        font-weight: 800;
        margin-bottom: 1rem;
        font-size: 1.2rem;
    }

    .estado-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0.75rem 0;
        border-bottom: 1px solid rgba(59, 130, 246, 0.2);
    }

    .estado-item:last-child {
        border-bottom: none;
    }

    .estado-label {
        color: #1e40af;
        font-weight: 600;
    }

    .estado-valor {
        color: #1f2937;
        font-weight: 800;
        font-size: 1.1rem;
    }

    .pulsing {
        animation: pulse 2s ease-in-out infinite;
    }

    @keyframes pulse {
        0%, 100% {
            transform: scale(1);
        }
        50% {
            transform: scale(1.05);
        }
    }

    .success-icon {
        color: #10b981;
        font-size: 2rem;
        margin-right: 0.5rem;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="mi-registro-container">
        <!-- Tarjeta de bienvenida -->
        <div class="welcome-card">
            <div class="avatar-grande">
                {{ strtoupper(substr($personal->nombre_completo, 0, 1)) }}
            </div>
            <h2 class="nombre-usuario">{{ $personal->nombre_completo }}</h2>
            <p class="cargo-usuario">{{ $personal->cargo }}</p>

            <!-- Hora actual -->
            <div class="hora-display">
                <div class="hora-actual-grande" id="horaActualGrande">--:--:--</div>
                <div class="fecha-actual">
                    {{ $hoy->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY') }}
                </div>
            </div>
        </div>

        <!-- Estado actual del día -->
        @if($asistenciaHoy)
        <div class="estado-actual">
            <h4>
                <i class="fas fa-info-circle mr-2"></i>
                Estado de tu Asistencia Hoy
            </h4>

            <div class="estado-item">
                <span class="estado-label">
                    <i class="fas fa-sign-in-alt mr-2"></i>
                    Hora de Entrada:
                </span>
                <span class="estado-valor">
                    @if($asistenciaHoy->entrada_hora)
                        <i class="fas fa-check-circle success-icon"></i>
                        {{ \Carbon\Carbon::parse($asistenciaHoy->entrada_hora)->format('H:i') }}
                    @else
                        <span class="text-warning">Pendiente</span>
                    @endif
                </span>
            </div>

            <div class="estado-item">
                <span class="estado-label">
                    <i class="fas fa-sign-out-alt mr-2"></i>
                    Hora de Salida:
                </span>
                <span class="estado-valor">
                    @if($asistenciaHoy->salida_hora)
                        <i class="fas fa-check-circle success-icon"></i>
                        {{ \Carbon\Carbon::parse($asistenciaHoy->salida_hora)->format('H:i') }}
                    @else
                        <span class="text-warning">Pendiente</span>
                    @endif
                </span>
            </div>

            @if($asistenciaHoy->entrada_hora && $asistenciaHoy->salida_hora)
                @php
                    $entrada = \Carbon\Carbon::parse($asistenciaHoy->entrada_hora);
                    $salida = \Carbon\Carbon::parse($asistenciaHoy->salida_hora);
                    $horas = $entrada->diffInHours($salida);
                    $minutos = $entrada->diffInMinutes($salida) % 60;
                @endphp
                <div class="estado-item">
                    <span class="estado-label">
                        <i class="fas fa-clock mr-2"></i>
                        Horas Trabajadas:
                    </span>
                    <span class="estado-valor text-success">
                        {{ $horas }}h {{ $minutos }}m
                    </span>
                </div>
            @endif
        </div>
        @endif

        <!-- Botones de acción -->
        <div class="action-card">
            <form action="{{ route('control.asistencia-semanal.marcar-mi-entrada') }}" method="POST" id="formEntrada">
                @csrf
                <button type="submit"
                        class="btn-marcar btn-marcar-entrada pulsing"
                        {{ $asistenciaHoy && $asistenciaHoy->entrada_hora && !$asistenciaHoy->salida_hora ? 'disabled' : '' }}
                        id="btnEntrada">
                    <i class="fas fa-sign-in-alt"></i>
                    @if($asistenciaHoy && $asistenciaHoy->entrada_hora)
                        Entrada Registrada
                    @else
                        Marcar Entrada
                    @endif
                </button>
            </form>

            <form action="{{ route('control.asistencia-semanal.marcar-mi-salida') }}" method="POST" id="formSalida">
                @csrf
                <button type="submit"
                        class="btn-marcar btn-marcar-salida"
                        {{ !$asistenciaHoy || !$asistenciaHoy->entrada_hora || $asistenciaHoy->salida_hora ? 'disabled' : '' }}
                        id="btnSalida">
                    <i class="fas fa-sign-out-alt"></i>
                    @if($asistenciaHoy && $asistenciaHoy->salida_hora)
                        Salida Registrada
                    @else
                        Marcar Salida
                    @endif
                </button>
            </form>

            <div class="text-center mt-3">
                <a href="{{ route('control.asistencia-semanal.index') }}" class="text-primary">
                    <i class="fas fa-calendar-week mr-2"></i>
                    Ver mi historial semanal
                </a>
            </div>
        </div>

        <!-- Instrucciones -->
        <div class="alert alert-info">
            <i class="fas fa-info-circle mr-2"></i>
            <strong>Instrucciones:</strong>
            <ul class="mb-0 mt-2">
                <li>Marca tu <strong>Entrada</strong> al llegar</li>
                <li>Marca tu <strong>Salida</strong> al terminar tu jornada</li>
                <li>Solo puedes registrar una entrada por día</li>
            </ul>
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
        $('#horaActualGrande').text(`${horas}:${minutos}:${segundos}`);
    }

    actualizarHora();
    setInterval(actualizarHora, 1000);

    // Confirmación antes de marcar
    $('#formEntrada').on('submit', function(e) {
        if (!confirm('¿Confirmar registro de ENTRADA?')) {
            e.preventDefault();
        }
    });

    $('#formSalida').on('submit', function(e) {
        if (!confirm('¿Confirmar registro de SALIDA?')) {
            e.preventDefault();
        }
    });

    // Animación de entrada
    $('.welcome-card, .action-card').hide().fadeIn(600);
});
</script>
@endpush
