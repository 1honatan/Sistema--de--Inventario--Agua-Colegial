@extends('layouts.app')

@section('title', 'Editar Producto')

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
                        Editar Producto
                    </h3>
                    <p class="modern-card-subtitle">
                        Modifique los datos del producto
                    </p>
                </div>

                <!-- Cuerpo del Formulario -->
                <div class="modern-card-body">
                    <form action="{{ route('inventario.productos.update', $producto->id) }}" method="POST" id="productoForm">
                        @csrf
                        @method('PUT')

                        <!-- Información Básica -->
                        <div class="section-box border-cyan">
                            <div class="section-header">
                                <i class="fas fa-info-circle text-info"></i>
                                <h4>Información del Producto</h4>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="nombre" class="form-label required-mark">
                                            <i class="fas fa-tag"></i> Nombre del Producto
                                        </label>
                                        <input type="text"
                                               name="nombre"
                                               id="nombre"
                                               class="modern-input @error('nombre') is-invalid @enderror"
                                               value="{{ old('nombre', $producto->nombre) }}"
                                               placeholder="Ej: Botellón 20L, Agua Natural, etc."
                                               required>
                                        @error('nombre')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="descripcion" class="form-label">
                                            <i class="fas fa-align-left"></i> Descripción
                                        </label>
                                        <textarea name="descripcion"
                                                  id="descripcion"
                                                  rows="3"
                                                  class="modern-textarea @error('descripcion') is-invalid @enderror"
                                                  placeholder="Descripción detallada del producto (opcional)">{{ old('descripcion', $producto->descripcion) }}</textarea>
                                        @error('descripcion')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Medidas y Stock -->
                        <div class="section-box border-purple">
                            <div class="section-header">
                                <i class="fas fa-balance-scale text-purple-600"></i>
                                <h4>Unidad de Medida y Stock</h4>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="unidad_medida" class="form-label required-mark">
                                            <i class="fas fa-ruler"></i> Unidad de Medida
                                        </label>
                                        <select name="unidad_medida"
                                                id="unidad_medida"
                                                class="modern-input @error('unidad_medida') is-invalid @enderror"
                                                required>
                                            <option value="">Seleccione unidad...</option>
                                            <option value="unidad" {{ old('unidad_medida', $producto->unidad_medida) == 'unidad' ? 'selected' : '' }}>Unidad</option>
                                            <option value="litro" {{ old('unidad_medida', $producto->unidad_medida) == 'litro' ? 'selected' : '' }}>Litro</option>
                                            <option value="bolsa" {{ old('unidad_medida', $producto->unidad_medida) == 'bolsa' ? 'selected' : '' }}>Bolsa</option>
                                            <option value="bolsa de hielo" {{ old('unidad_medida', $producto->unidad_medida) == 'bolsa de hielo' ? 'selected' : '' }}>Bolsa de Hielo</option>
                                        </select>
                                        @error('unidad_medida')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="stock_minimo" class="form-label">
                                            <i class="fas fa-exclamation-triangle"></i> Stock Mínimo
                                        </label>
                                        <input type="number"
                                               name="stock_minimo"
                                               id="stock_minimo"
                                               class="modern-input @error('stock_minimo') is-invalid @enderror"
                                               value="{{ old('stock_minimo', $producto->stock_minimo ?? 10) }}"
                                               min="0"
                                               placeholder="Cantidad mínima en stock">
                                        <small class="text-muted">
                                            <i class="fas fa-info-circle"></i> Se generará alerta cuando el stock esté por debajo de este valor
                                        </small>
                                        @error('stock_minimo')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Botones de Acción -->
                        <div class="d-flex justify-content-end gap-3 mt-4">
                            <a href="{{ route('inventario.index') }}" class="btn-modern btn-secondary">
                                <i class="fas fa-times"></i>
                                Cancelar
                            </a>
                            <button type="submit" class="btn-modern btn-success">
                                <i class="fas fa-save"></i>
                                Actualizar Producto
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
        ModernComponents.initFormSubmitAnimation('#productoForm');

        // Atajos de teclado
        ModernComponents.initKeyboardShortcuts('#productoForm', '{{ route("inventario.index") }}');

        // Advertencia de cambios no guardados
        ModernComponents.initUnsavedChangesWarning('#productoForm');
    });
</script>
@endpush
