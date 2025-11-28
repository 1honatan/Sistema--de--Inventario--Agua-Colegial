@extends('layouts.app')

@section('title', 'Nueva Fumigación')

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
                        <i class="fas fa-spray-can mr-2"></i>
                        Nuevo Registro de Fumigación
                    </h3>
                    <p class="modern-card-subtitle">
                        Complete el formulario para registrar la fumigación realizada
                    </p>
                </div>

                <!-- Cuerpo del Formulario -->
                <div class="modern-card-body">
                    <form action="{{ route('control.fumigacion.store') }}" method="POST" id="fumigacionForm">
                        @csrf

                        <!-- Información de la Fumigación -->
                        <div class="section-box border-cyan">
                            <div class="section-header">
                                <i class="fas fa-calendar-check text-info"></i>
                                <h4>Información de la Fumigación</h4>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="fecha_fumigacion" class="form-label required-mark">
                                            <i class="fas fa-calendar-alt"></i> Fecha de Fumigación
                                        </label>
                                        <input type="date"
                                               name="fecha_fumigacion"
                                               id="fecha_fumigacion"
                                               class="modern-input @error('fecha_fumigacion') is-invalid @enderror"
                                               value="{{ date('Y-m-d') }}"
                                               readonly
                                               style="background-color: #f3f4f6; cursor: not-allowed;"
                                               required>
                                        @error('fecha_fumigacion')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">
                                            <i class="fas fa-info-circle"></i> Fecha automática (hoy)
                                        </small>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="area_fumigada" class="form-label required-mark">
                                            <i class="fas fa-map-marked-alt"></i> Área Fumigada
                                        </label>
                                        <input type="text"
                                               name="area_fumigada"
                                               id="area_fumigada"
                                               class="modern-input @error('area_fumigada') is-invalid @enderror"
                                               value="{{ old('area_fumigada') }}"
                                               placeholder="Ej: Área de producción, Bodega, etc."
                                               required>
                                        @error('area_fumigada')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Producto Utilizado -->
                        <div class="section-box border-purple">
                            <div class="section-header">
                                <i class="fas fa-flask text-purple-600"></i>
                                <h4>Producto y Cantidad</h4>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="producto_utilizado" class="form-label required-mark">
                                            <i class="fas fa-prescription-bottle"></i> Producto Utilizado
                                        </label>
                                        <input type="text"
                                               name="producto_utilizado"
                                               id="producto_utilizado"
                                               class="modern-input @error('producto_utilizado') is-invalid @enderror"
                                               value="{{ old('producto_utilizado') }}"
                                               placeholder="Ej: Insecticida, Raticida, etc."
                                               required>
                                        @error('producto_utilizado')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="cantidad_producto" class="form-label required-mark">
                                            <i class="fas fa-sort-numeric-up"></i> Cantidad del Producto
                                        </label>
                                        <input type="number"
                                               step="0.01"
                                               name="cantidad_producto"
                                               id="cantidad_producto"
                                               class="modern-input @error('cantidad_producto') is-invalid @enderror"
                                               value="{{ old('cantidad_producto', 0) }}"
                                               min="0"
                                               required>
                                        @error('cantidad_producto')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Responsable y Empresa -->
                        <div class="section-box border-orange">
                            <div class="section-header">
                                <i class="fas fa-users text-warning"></i>
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
                                        <label for="empresa_contratada" class="form-label">
                                            <i class="fas fa-building"></i> Empresa Contratada
                                        </label>
                                        <input type="text"
                                               name="empresa_contratada"
                                               id="empresa_contratada"
                                               class="modern-input @error('empresa_contratada') is-invalid @enderror"
                                               value="{{ old('empresa_contratada') }}"
                                               placeholder="Nombre de la empresa (opcional)">
                                        @error('empresa_contratada')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Próxima Fumigación -->
                        <div class="section-box border-green">
                            <div class="section-header">
                                <i class="fas fa-calendar-plus text-success"></i>
                                <h4>Próxima Fumigación</h4>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="proxima_fumigacion" class="form-label">
                                            <i class="fas fa-calendar-day"></i> Fecha Próxima Fumigación
                                        </label>
                                        <input type="date"
                                               name="proxima_fumigacion"
                                               id="proxima_fumigacion"
                                               class="modern-input @error('proxima_fumigacion') is-invalid @enderror"
                                               value="{{ old('proxima_fumigacion') }}">
                                        <small class="text-muted">
                                            <i class="fas fa-info-circle"></i> Calculado automáticamente (+3 meses)
                                        </small>
                                        @error('proxima_fumigacion')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
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
                            <a href="{{ route('control.fumigacion.index') }}" class="btn-modern btn-secondary">
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
        ModernComponents.initFormSubmitAnimation('#fumigacionForm');

        // Atajos de teclado
        ModernComponents.initKeyboardShortcuts('#fumigacionForm', '{{ route("control.fumigacion.index") }}');

        // Advertencia de cambios no guardados
        ModernComponents.initUnsavedChangesWarning('#fumigacionForm');

        // Calcular próxima fumigación automáticamente (3 meses después de hoy)
        function calcularProximaFumigacion() {
            const fechaFumigacion = new Date($('#fecha_fumigacion').val());
            if (fechaFumigacion) {
                // Agregar 3 meses
                fechaFumigacion.setMonth(fechaFumigacion.getMonth() + 3);
                const year = fechaFumigacion.getFullYear();
                const month = String(fechaFumigacion.getMonth() + 1).padStart(2, '0');
                const day = String(fechaFumigacion.getDate()).padStart(2, '0');
                $('#proxima_fumigacion').val(`${year}-${month}-${day}`);
            }
        }

        // Calcular próxima fumigación al cargar la página
        calcularProximaFumigacion();

        // Mantener el evento por si se necesita en el futuro
        $('#fecha_fumigacion').on('change', calcularProximaFumigacion);
    });
</script>
@endpush
