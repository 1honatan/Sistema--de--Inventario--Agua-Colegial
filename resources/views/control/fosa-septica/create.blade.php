@extends('layouts.app')

@section('title', 'Nueva Limpieza Fosa Séptica')

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
                        <i class="fas fa-toilet mr-2"></i>
                        Nuevo Registro de Limpieza de Fosa Séptica
                    </h3>
                    <p class="modern-card-subtitle">
                        Complete el formulario para registrar la limpieza de fosa séptica
                    </p>
                </div>

                <!-- Cuerpo del Formulario -->
                <div class="modern-card-body">
                    <form action="{{ route('control.fosa-septica.store') }}" method="POST" id="fosaForm">
                        @csrf

                        <!-- Información de la Limpieza -->
                        <div class="section-box border-cyan">
                            <div class="section-header">
                                <i class="fas fa-calendar-check text-info"></i>
                                <h4>Información de la Limpieza</h4>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="fecha_limpieza" class="form-label required-mark">
                                            <i class="fas fa-calendar-alt"></i> Fecha de Limpieza
                                        </label>
                                        <input type="date"
                                               name="fecha_limpieza"
                                               id="fecha_limpieza"
                                               class="modern-input @error('fecha_limpieza') is-invalid @enderror"
                                               value="{{ date('Y-m-d') }}"
                                               readonly
                                               style="background-color: #f3f4f6; cursor: not-allowed;"
                                               required>
                                        @error('fecha_limpieza')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">
                                            <i class="fas fa-info-circle"></i> Fecha automática (hoy)
                                        </small>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="tipo_fosa" class="form-label required-mark">
                                            <i class="fas fa-tag"></i> Tipo de Fosa (Identificación)
                                        </label>
                                        <input type="text"
                                               name="tipo_fosa"
                                               id="tipo_fosa"
                                               class="modern-input @error('tipo_fosa') is-invalid @enderror"
                                               value="{{ old('tipo_fosa') }}"
                                               placeholder="Ej: Fosa Principal, Fosa #1, etc."
                                               required>
                                        @error('tipo_fosa')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="proxima_limpieza" class="form-label required-mark">
                                            <i class="fas fa-calendar-plus"></i> Próxima Limpieza
                                        </label>
                                        <input type="date"
                                               name="proxima_limpieza"
                                               id="proxima_limpieza"
                                               class="modern-input @error('proxima_limpieza') is-invalid @enderror"
                                               value="{{ old('proxima_limpieza', date('Y-m-d', strtotime('+5 months'))) }}"
                                               required>
                                        <small class="text-muted">Predeterminado: 5 meses</small>
                                        @error('proxima_limpieza')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Responsable y Empresa -->
                        <div class="section-box border-purple">
                            <div class="section-header">
                                <i class="fas fa-users text-purple-600"></i>
                                <h4>Responsable y Empresa Contratada</h4>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="responsable" class="form-label required-mark">
                                            <i class="fas fa-user-check"></i> Responsable
                                        </label>
                                        <select name="responsable"
                                                id="responsable"
                                                class="modern-input @error('responsable') is-invalid @enderror"
                                                required>
                                            <option value="">Seleccione responsable...</option>
                                            @foreach($personal as $persona)
                                                <option value="{{ $persona->nombre_completo }}" {{ old('responsable') == $persona->nombre_completo ? 'selected' : '' }}>
                                                    {{ $persona->nombre_completo }} ({{ $persona->cargo }})
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('responsable')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="empresa_contratada" class="form-label required-mark">
                                            <i class="fas fa-building"></i> Empresa Contratada
                                        </label>
                                        <input type="text"
                                               name="empresa_contratada"
                                               id="empresa_contratada"
                                               class="modern-input @error('empresa_contratada') is-invalid @enderror"
                                               value="Servicio Master Bolivia SRL"
                                               readonly
                                               required>
                                        @error('empresa_contratada')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Detalle del Trabajo -->
                        <div class="section-box border-orange">
                            <div class="section-header">
                                <i class="fas fa-clipboard-list text-warning"></i>
                                <h4>Detalle del Trabajo</h4>
                            </div>
                            <div class="form-group">
                                <label for="detalle_trabajo" class="form-label required-mark">
                                    <i class="fas fa-tasks"></i> Tipo de Trabajo Realizado
                                </label>
                                <select name="detalle_trabajo"
                                        id="detalle_trabajo"
                                        class="modern-input @error('detalle_trabajo') is-invalid @enderror"
                                        required>
                                    <option value="">Seleccione el tipo de trabajo...</option>
                                    <option value="Limpieza y Retiro" {{ old('detalle_trabajo') == 'Limpieza y Retiro' ? 'selected' : '' }}>
                                        Limpieza y Retiro
                                    </option>
                                    <option value="Retiro de Residuos" {{ old('detalle_trabajo') == 'Retiro de Residuos' ? 'selected' : '' }}>
                                        Retiro de Residuos
                                    </option>
                                </select>
                                @error('detalle_trabajo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Observaciones -->
                        <div class="section-box border-blue">
                            <div class="section-header">
                                <i class="fas fa-sticky-note text-primary"></i>
                                <h4>Observaciones Adicionales</h4>
                            </div>
                            <div class="form-group">
                                <textarea name="observaciones"
                                          id="observaciones"
                                          rows="3"
                                          class="modern-textarea @error('observaciones') is-invalid @enderror"
                                          placeholder="Ingrese observaciones adicionales (opcional)...">{{ old('observaciones') }}</textarea>
                                @error('observaciones')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Botones de Acción -->
                        <div class="d-flex justify-content-end gap-3 mt-4">
                            <a href="{{ route('control.fosa-septica.index') }}" class="btn-modern btn-secondary">
                                <i class="fas fa-times"></i>
                                Cancelar
                            </a>
                            <button type="submit" class="btn-modern btn-success">
                                <i class="fas fa-save"></i>
                                Guardar Registro
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
        ModernComponents.initFormSubmitAnimation('#fosaForm');

        // Atajos de teclado
        ModernComponents.initKeyboardShortcuts('#fosaForm', '{{ route("control.fosa-septica.index") }}');

        // Advertencia de cambios no guardados
        ModernComponents.initUnsavedChangesWarning('#fosaForm');

        // Función para calcular próxima fecha (5 meses después)
        function calcularProximaFecha() {
            const fechaLimpieza = new Date($('#fecha_limpieza').val());
            if (fechaLimpieza) {
                // Agregar 5 meses
                fechaLimpieza.setMonth(fechaLimpieza.getMonth() + 5);
                const year = fechaLimpieza.getFullYear();
                const month = String(fechaLimpieza.getMonth() + 1).padStart(2, '0');
                const day = String(fechaLimpieza.getDate()).padStart(2, '0');
                $('#proxima_limpieza').val(`${year}-${month}-${day}`);
            }
        }

        // Calcular próxima fecha al cargar la página (fecha automática de hoy)
        calcularProximaFecha();

        // Mantener el evento por si se necesita en el futuro
        $('#fecha_limpieza').on('change', calcularProximaFecha);
    });
</script>
@endpush
