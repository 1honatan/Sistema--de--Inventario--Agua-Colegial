@extends('layouts.app')

@section('title', 'Nuevo Mantenimiento')

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

    .custom-control-label {
        font-weight: 500;
        color: #374151;
        cursor: pointer;
        user-select: none;
    }

    .custom-control-input:checked ~ .custom-control-label {
        color: #059669;
        font-weight: 600;
    }

    .custom-checkbox .custom-control-label::before {
        border-radius: 0.25rem;
        border: 2px solid #d1d5db;
    }

    .custom-checkbox .custom-control-input:checked ~ .custom-control-label::before {
        background-color: #10b981;
        border-color: #10b981;
    }

    .custom-control {
        padding-left: 1.8rem;
        min-height: 1.5rem;
    }

    .custom-control-label::before {
        width: 1.2rem;
        height: 1.2rem;
        top: 0.15rem;
    }

    .custom-control-label::after {
        width: 1.2rem;
        height: 1.2rem;
        top: 0.15rem;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4">
    <div class="row">
        <div class="col-xl-10 col-lg-11 mx-auto">
            <!-- Tarjeta Principal -->
            <div class="modern-card">
                <!-- Encabezado con Gradiente -->
                <div class="modern-card-header">
                    <h3 class="modern-card-title">
                        <i class="fas fa-wrench mr-2"></i>
                        Nuevo Registro de Mantenimiento
                    </h3>
                    <p class="modern-card-subtitle">
                        Complete el formulario para registrar el mantenimiento de equipos
                    </p>
                </div>

                <!-- Cuerpo del Formulario -->
                <div class="modern-card-body">
                    <form action="{{ route('control.mantenimiento.store') }}" method="POST" id="mantenimientoForm">
                        @csrf

                        <!-- Información del Equipo -->
                        <div class="section-box border-cyan">
                            <div class="section-header">
                                <i class="fas fa-cogs text-info"></i>
                                <h4>Información del Equipo</h4>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="fecha" class="form-label required-mark">
                                            <i class="fas fa-calendar-alt"></i> Fecha
                                        </label>
                                        <input type="date"
                                               name="fecha"
                                               id="fecha"
                                               class="modern-input @error('fecha') is-invalid @enderror"
                                               value="{{ old('fecha', date('Y-m-d')) }}"
                                               required>
                                        @error('fecha')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label class="form-label required-mark">
                                            <i class="fas fa-tools"></i> Equipos a Mantener
                                        </label>
                                        <div class="row">
                                            @php
                                            $equipos = [
                                                'Máquina de Agua Natural',
                                                'Máquina de Limón y Sabor',
                                                'Máquina de Bolos',
                                                'Máquina de Hielo',
                                                'Turiles Grandes (Gelatina y Bolos)',
                                                'Turiles Medianos (Gelatina y Bolos)',
                                                'Máquina Limpiadora de Botellones 20L',
                                                'Otro'
                                            ];
                                            @endphp
                                            @foreach($equipos as $eq)
                                                <div class="col-md-6 mb-3">
                                                    <div class="custom-control custom-checkbox">
                                                        <input type="checkbox"
                                                               class="custom-control-input"
                                                               name="equipo[]"
                                                               id="equipo_{{ $loop->index }}"
                                                               value="{{ $eq }}"
                                                               {{ in_array($eq, old('equipo', [])) ? 'checked' : '' }}>
                                                        <label class="custom-control-label" for="equipo_{{ $loop->index }}">
                                                            {{ $eq }}
                                                        </label>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        @error('equipo')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="proxima_fecha" class="form-label">
                                            <i class="fas fa-calendar-check"></i> Próxima Fecha
                                        </label>
                                        <input type="date"
                                               name="proxima_fecha"
                                               id="proxima_fecha"
                                               class="modern-input @error('proxima_fecha') is-invalid @enderror"
                                               value="{{ old('proxima_fecha') }}">
                                        @error('proxima_fecha')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Personal Responsable -->
                        <div class="section-box border-purple">
                            <div class="section-header">
                                <i class="fas fa-users text-purple-600"></i>
                                <h4>Personal Responsable</h4>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="id_personal" class="form-label required-mark">
                                            <i class="fas fa-user-check"></i> Realizado por
                                        </label>
                                        <select name="id_personal"
                                                id="id_personal"
                                                class="modern-input @error('id_personal') is-invalid @enderror"
                                                required>
                                            <option value="">Seleccione el personal...</option>
                                            @foreach($personal as $p)
                                                <option value="{{ $p->id }}" {{ old('id_personal') == $p->id ? 'selected' : '' }}>
                                                    {{ $p->nombre_completo }} - {{ $p->cargo }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('id_personal')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="supervisado_por" class="form-label required-mark">
                                            <i class="fas fa-user-tie"></i> Supervisado por
                                        </label>
                                        <input type="text"
                                               name="supervisado_por"
                                               id="supervisado_por"
                                               class="modern-input @error('supervisado_por') is-invalid @enderror"
                                               value="Lucia Cruz Farfan"
                                               readonly
                                               required>
                                        @error('supervisado_por')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Productos de Limpieza Utilizados -->
                        <div class="section-box border-orange">
                            <div class="section-header">
                                <i class="fas fa-spray-can text-warning"></i>
                                <h4>Productos de Limpieza Utilizados</h4>
                            </div>
                            <div class="form-group">
                                <label class="form-label required-mark">
                                    <i class="fas fa-check-square"></i> Seleccione los productos utilizados
                                </label>
                                <div class="row">
                                    @foreach($productosLimpieza as $producto)
                                        <div class="col-md-6 col-lg-4 mb-3">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox"
                                                       class="custom-control-input"
                                                       name="productos_limpieza[]"
                                                       id="producto_{{ $loop->index }}"
                                                       value="{{ $producto }}"
                                                       {{ in_array($producto, old('productos_limpieza', [])) ? 'checked' : '' }}>
                                                <label class="custom-control-label" for="producto_{{ $loop->index }}">
                                                    {{ $producto }}
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                @error('productos_limpieza')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Botones de Acción -->
                        <div class="d-flex justify-content-end gap-3 mt-4">
                            <a href="{{ route('control.mantenimiento.index') }}" class="btn-modern btn-secondary">
                                <i class="fas fa-times"></i>
                                Cancelar
                            </a>
                            <button type="submit" class="btn-modern btn-success">
                                <i class="fas fa-save"></i>
                                Guardar Mantenimiento
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Animación de secciones
        ModernComponents.initSectionAnimations();

        // Animación al enviar formulario
        ModernComponents.initFormSubmitAnimation('#mantenimientoForm');

        // Atajos de teclado
        ModernComponents.initKeyboardShortcuts('#mantenimientoForm', '{{ route("control.mantenimiento.index") }}');

        // Advertencia de cambios no guardados
        ModernComponents.initUnsavedChangesWarning('#mantenimientoForm');
    });
</script>
@endpush
