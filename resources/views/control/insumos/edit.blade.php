@extends('layouts.app')

@section('title', 'Editar Control de Insumo')

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
                        <i class="fas fa-edit mr-2"></i>
                        Editar Control de Insumo #{{ $insumo->id }}
                    </h3>
                    <p class="modern-card-subtitle">
                        Modifique los datos del registro de insumo
                    </p>
                </div>

                <!-- Cuerpo del Formulario -->
                <div class="modern-card-body">
                    <form action="{{ route('control.insumos.update', $insumo) }}" method="POST" id="insumoForm">
                        @csrf
                        @method('PUT')

                        <!-- Información del Insumo -->
                        <div class="section-box border-cyan">
                            <div class="section-header">
                                <i class="fas fa-info-circle text-info"></i>
                                <h4>Información del Insumo</h4>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="fecha" class="form-label required-mark">
                                            <i class="fas fa-calendar-alt"></i> Fecha
                                        </label>
                                        <input type="date"
                                               name="fecha"
                                               id="fecha"
                                               class="modern-input @error('fecha') is-invalid @enderror"
                                               value="{{ old('fecha', $insumo->fecha->format('Y-m-d')) }}"
                                               readonly
                                               style="background-color: #e9ecef; cursor: not-allowed;"
                                               required>
                                        @error('fecha')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="producto_insumo" class="form-label required-mark">
                                            <i class="fas fa-cube"></i> Producto del Insumo
                                        </label>
                                        <input type="text"
                                               name="producto_insumo"
                                               id="producto_insumo"
                                               class="modern-input @error('producto_insumo') is-invalid @enderror"
                                               value="{{ old('producto_insumo', $insumo->producto_insumo) }}"
                                               placeholder="Ej: Cloro, Detergente, Químico de limpieza, etc."
                                               required>
                                        @error('producto_insumo')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Cantidad y Medidas -->
                        <div class="section-box border-purple">
                            <div class="section-header">
                                <i class="fas fa-balance-scale text-purple-600"></i>
                                <h4>Cantidad y Medidas</h4>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="cantidad" class="form-label required-mark">
                                            <i class="fas fa-sort-numeric-up"></i> Cantidad
                                        </label>
                                        <input type="number"
                                               step="0.01"
                                               name="cantidad"
                                               id="cantidad"
                                               class="modern-input @error('cantidad') is-invalid @enderror"
                                               value="{{ old('cantidad', $insumo->cantidad) }}"
                                               min="0"
                                               required>
                                        @error('cantidad')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="unidad_medida" class="form-label required-mark">
                                            <i class="fas fa-ruler"></i> Unidad de Medida
                                        </label>
                                        <select name="unidad_medida"
                                                id="unidad_medida"
                                                class="modern-input @error('unidad_medida') is-invalid @enderror"
                                                required>
                                            <option value="">Seleccione...</option>
                                            <option value="kg" {{ old('unidad_medida', $insumo->unidad_medida) == 'kg' ? 'selected' : '' }}>Kilogramos (kg)</option>
                                            <option value="g" {{ old('unidad_medida', $insumo->unidad_medida) == 'g' ? 'selected' : '' }}>Gramos (g)</option>
                                            <option value="L" {{ old('unidad_medida', $insumo->unidad_medida) == 'L' ? 'selected' : '' }}>Litros (L)</option>
                                            <option value="ml" {{ old('unidad_medida', $insumo->unidad_medida) == 'ml' ? 'selected' : '' }}>Mililitros (ml)</option>
                                        </select>
                                        @error('unidad_medida')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="numero_lote" class="form-label">
                                            <i class="fas fa-barcode"></i> Número de Lote
                                        </label>
                                        <input type="text"
                                               name="numero_lote"
                                               id="numero_lote"
                                               class="modern-input @error('numero_lote') is-invalid @enderror"
                                               value="{{ old('numero_lote', $insumo->numero_lote) }}"
                                               placeholder="Ej: L-2025-001">
                                        @error('numero_lote')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Fechas y Responsable -->
                        <div class="section-box border-orange">
                            <div class="section-header">
                                <i class="fas fa-calendar-check text-warning"></i>
                                <h4>Vencimiento y Responsable</h4>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="fecha_vencimiento" class="form-label">
                                            <i class="fas fa-calendar-times"></i> Fecha de Vencimiento
                                        </label>
                                        <input type="date"
                                               name="fecha_vencimiento"
                                               id="fecha_vencimiento"
                                               class="modern-input @error('fecha_vencimiento') is-invalid @enderror"
                                               value="{{ old('fecha_vencimiento', $insumo->fecha_vencimiento ? $insumo->fecha_vencimiento->format('Y-m-d') : '') }}">
                                        @error('fecha_vencimiento')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
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
                                                <option value="{{ $persona->nombre_completo }}" {{ old('responsable', $insumo->responsable) == $persona->nombre_completo ? 'selected' : '' }}>
                                                    {{ $persona->nombre_completo }} ({{ $persona->cargo }})
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('responsable')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="proveedor" class="form-label">
                                            <i class="fas fa-truck"></i> Proveedor
                                        </label>
                                        <input type="text"
                                               name="proveedor"
                                               id="proveedor"
                                               class="modern-input @error('proveedor') is-invalid @enderror"
                                               value="{{ old('proveedor', $insumo->proveedor) }}"
                                               placeholder="Nombre del proveedor">
                                        @error('proveedor')
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
                                          placeholder="Ingrese observaciones adicionales (opcional)...">{{ old('observaciones', $insumo->observaciones) }}</textarea>
                                @error('observaciones')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Botones de Acción -->
                        <div class="d-flex justify-content-end gap-3 mt-4">
                            <a href="{{ route('control.insumos.index') }}" class="btn-modern btn-secondary">
                                <i class="fas fa-times"></i>
                                Cancelar
                            </a>
                            <button type="submit" class="btn-modern btn-primary">
                                <i class="fas fa-save"></i>
                                Actualizar Insumo
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
        ModernComponents.initFormSubmitAnimation('#insumoForm');

        // Atajos de teclado
        ModernComponents.initKeyboardShortcuts('#insumoForm', '{{ route("control.insumos.index") }}');

        // Advertencia de cambios no guardados
        ModernComponents.initUnsavedChangesWarning('#insumoForm');
    });
</script>
@endpush
