@extends('layouts.app')

@section('title', 'Detalle de Empleado')

@push('styles')
<style>
    body {
        background: linear-gradient(135deg, #0ea5e9 0%, #06b6d4 50%, #14b8a6 100%);
        min-height: 100vh;
        position: relative;
    }

    body::before {
        content: '';
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        pointer-events: none;
        z-index: 0;
    }

    .empleado-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        margin-bottom: 1rem;
        position: relative;
        z-index: 1;
    }

    .empleado-header {
        background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%);
        color: white;
        padding: 1.5rem;
        border-radius: 12px 12px 0 0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .empleado-body {
        padding: 2rem;
    }

    .section-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: #4f46e5;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid #e5e7eb;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .info-item {
        display: flex;
        flex-direction: column;
    }

    .info-label {
        font-size: 0.75rem;
        font-weight: 600;
        color: #6b7280;
        text-transform: uppercase;
        margin-bottom: 0.5rem;
        letter-spacing: 0.5px;
    }

    .info-value {
        font-size: 1.1rem;
        color: #1f2937;
        font-weight: 600;
    }

    .badge-estado-activo {
        display: inline-block;
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.9rem;
    }

    .badge-estado-inactivo {
        display: inline-block;
        background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.9rem;
    }

    .badge-cargo {
        display: inline-block;
        background: linear-gradient(135deg, #4f46e5 0%, #4338ca 100%);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.9rem;
    }

    .observaciones-box {
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        border: 2px solid #f59e0b;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 2rem;
    }

    .observaciones-box p {
        color: #92400e;
        margin: 0;
        font-weight: 500;
    }

    .documentos-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
    }

    .documento-item {
        background: #f9fafb;
        padding: 1rem;
        border-radius: 12px;
        border: 2px solid #e5e7eb;
        transition: all 0.3s ease;
    }

    .documento-item:hover {
        border-color: #4f46e5;
        box-shadow: 0 4px 12px rgba(79, 70, 229, 0.2);
        transform: translateY(-2px);
    }

    .documento-item img {
        width: 100%;
        height: 200px;
        object-fit: cover;
        border-radius: 8px;
        cursor: pointer;
        transition: transform 0.3s ease;
    }

    .documento-item img:hover {
        transform: scale(1.05);
    }

    .documento-label {
        font-size: 0.85rem;
        font-weight: 600;
        color: #4b5563;
        margin-bottom: 0.75rem;
        display: block;
    }

    .metadata-box {
        background: #f9fafb;
        padding: 1rem;
        border-radius: 8px;
        border-left: 4px solid #4f46e5;
        margin-top: 2rem;
    }

    .metadata-box p {
        margin: 0.25rem 0;
        font-size: 0.85rem;
        color: #6b7280;
    }

    .btn-volver {
        background: white;
        color: #4f46e5;
        border: 2px solid white;
        padding: 0.5rem 1.5rem;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-volver:hover {
        background: #4f46e5;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
    }

    .divider {
        height: 2px;
        background: linear-gradient(90deg, transparent, #4f46e5, transparent);
        margin: 2rem 0;
        opacity: 0.3;
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <div class="empleado-card">
        <div class="empleado-header">
            <div>
                <h2 class="mb-0">
                    <i class="fas fa-user-circle mr-2"></i>
                    {{ $empleado->nombre_completo }}
                </h2>
                <p class="mb-0 mt-2" style="opacity: 0.9;">
                    <i class="fas fa-id-badge mr-1"></i>
                    Información detallada del empleado
                </p>
            </div>
            <a href="{{ route('control.asistencia-semanal.registro-rapido') }}" class="btn-volver">
                <i class="fas fa-arrow-left mr-2"></i>
                Volver
            </a>
        </div>

        <div class="empleado-body">
            <!-- Información Personal -->
            <div class="section-title">
                <i class="fas fa-id-card"></i>
                Información Personal
            </div>

            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">
                        <i class="fas fa-user mr-1"></i>
                        Nombre Completo
                    </span>
                    <span class="info-value">{{ $empleado->nombre_completo }}</span>
                </div>

                <div class="info-item">
                    <span class="info-label">
                        <i class="fas fa-briefcase mr-1"></i>
                        Cargo
                    </span>
                    <span class="badge-cargo">{{ $empleado->cargo }}</span>
                </div>

                <div class="info-item">
                    <span class="info-label">
                        <i class="fas fa-phone mr-1"></i>
                        Teléfono
                    </span>
                    <span class="info-value">{{ $empleado->telefono ?? 'No especificado' }}</span>
                </div>

                <div class="info-item">
                    <span class="info-label">
                        <i class="fas fa-map-marker-alt mr-1"></i>
                        Dirección
                    </span>
                    <span class="info-value">{{ $empleado->direccion ?? 'No especificada' }}</span>
                </div>

                <div class="info-item">
                    <span class="info-label">
                        <i class="fas fa-envelope mr-1"></i>
                        Email
                    </span>
                    <span class="info-value">{{ $empleado->email ?? 'No especificado' }}</span>
                </div>

                <div class="info-item">
                    <span class="info-label">
                        <i class="fas fa-toggle-on mr-1"></i>
                        Estado
                    </span>
                    @if($empleado->estado === 'activo')
                        <span class="badge-estado-activo">
                            <i class="fas fa-check-circle mr-1"></i>
                            Activo
                        </span>
                    @else
                        <span class="badge-estado-inactivo">
                            <i class="fas fa-times-circle mr-1"></i>
                            Inactivo
                        </span>
                    @endif
                </div>
            </div>

            <div class="divider"></div>

            <!-- Información Laboral -->
            <div class="section-title">
                <i class="fas fa-building"></i>
                Información Laboral
            </div>

            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">
                        <i class="fas fa-calendar-alt mr-1"></i>
                        Fecha de Ingreso
                    </span>
                    <span class="info-value">{{ $empleado->fecha_ingreso ? \Carbon\Carbon::parse($empleado->fecha_ingreso)->format('d/m/Y') : 'No especificada' }}</span>
                </div>

                <div class="info-item">
                    <span class="info-label">
                        <i class="fas fa-dollar-sign mr-1"></i>
                        Salario
                    </span>
                    <span class="info-value">{{ $empleado->salario ? 'Bs. ' . number_format($empleado->salario, 2) : 'No especificado' }}</span>
                </div>

                <div class="info-item">
                    <span class="info-label">
                        <i class="fas fa-layer-group mr-1"></i>
                        Área
                    </span>
                    <span class="info-value">{{ $empleado->area ?? 'No especificada' }}</span>
                </div>
            </div>

            @if($empleado->observaciones)
            <div class="divider"></div>

            <div class="section-title">
                <i class="fas fa-sticky-note"></i>
                Observaciones
            </div>

            <div class="observaciones-box">
                <p>{{ $empleado->observaciones }}</p>
            </div>
            @endif

            <!-- Documentos -->
            @if($empleado->foto_documento || $empleado->foto_licencia || $empleado->foto_id_chofer)
            <div class="divider"></div>

            <div class="section-title">
                <i class="fas fa-images"></i>
                Documentos Adjuntos
            </div>

            <div class="documentos-grid">
                @if($empleado->foto_documento)
                <div class="documento-item">
                    <span class="documento-label">
                        <i class="fas fa-id-card mr-1"></i>
                        Documento de Identificación
                    </span>
                    <img src="{{ asset($empleado->foto_documento) }}"
                         alt="Documento"
                         onclick="window.open('{{ asset($empleado->foto_documento) }}', '_blank')"
                         title="Click para ver en tamaño completo">
                </div>
                @endif

                @if($empleado->foto_licencia)
                <div class="documento-item">
                    <span class="documento-label">
                        <i class="fas fa-car mr-1"></i>
                        Licencia de Conducir
                    </span>
                    <img src="{{ asset($empleado->foto_licencia) }}"
                         alt="Licencia"
                         onclick="window.open('{{ asset($empleado->foto_licencia) }}', '_blank')"
                         title="Click para ver en tamaño completo">
                </div>
                @endif

                @if($empleado->foto_id_chofer)
                <div class="documento-item">
                    <span class="documento-label">
                        <i class="fas fa-address-card mr-1"></i>
                        ID/DUI Chofer
                    </span>
                    <img src="{{ asset($empleado->foto_id_chofer) }}"
                         alt="ID Chofer"
                         onclick="window.open('{{ asset($empleado->foto_id_chofer) }}', '_blank')"
                         title="Click para ver en tamaño completo">
                </div>
                @endif
            </div>
            @endif

            <!-- Metadata -->
            <div class="metadata-box">
                <p>
                    <i class="fas fa-calendar-plus mr-2"></i>
                    <strong>Registrado:</strong> {{ $empleado->created_at ? $empleado->created_at->format('d/m/Y H:i') : 'No disponible' }}
                </p>
                <p>
                    <i class="fas fa-calendar-edit mr-2"></i>
                    <strong>Última actualización:</strong> {{ $empleado->updated_at ? $empleado->updated_at->format('d/m/Y H:i') : 'No disponible' }}
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
