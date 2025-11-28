@extends('layouts.app')

@section('title', 'Registrar Producción')

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

    .item-row {
        background: white;
        border: 3px solid #e5e7eb;
        border-radius: 16px;
        padding: 20px;
        margin-bottom: 15px;
        transition: all 0.3s ease;
        animation: slideIn 0.3s ease-out;
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateX(-20px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    .item-row:hover {
        border-color: #3b82f6;
        box-shadow: 0 8px 20px rgba(59, 130, 246, 0.15);
        transform: translateY(-2px);
    }

    .btn-add-item {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        border: none;
        color: white;
        padding: 12px 24px;
        border-radius: 12px;
        font-weight: 700;
        transition: all 0.3s ease;
        box-shadow: 0 6px 20px rgba(16, 185, 129, 0.3);
    }

    .btn-add-item:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 30px rgba(16, 185, 129, 0.4);
        color: white;
    }

    .btn-remove-item {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        border: none;
        color: white;
        padding: 10px 16px;
        border-radius: 10px;
        transition: all 0.3s ease;
    }

    .btn-remove-item:hover {
        transform: scale(1.1);
        box-shadow: 0 6px 20px rgba(239, 68, 68, 0.4);
        color: white;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4">
    <div class="row">
        <div class="col-xl-11 col-lg-12 mx-auto">
            <!-- Tarjeta Principal -->
            <div class="modern-card">
                <!-- Encabezado con Gradiente -->
                <div class="modern-card-header">
                    <h3 class="modern-card-title">
                        <i class="fas fa-industry mr-2"></i>
                        Nuevo Registro de Producción Diaria
                    </h3>
                    <p class="modern-card-subtitle">
                        Complete el formulario para registrar la producción del día
                    </p>
                </div>

                <!-- Cuerpo del Formulario -->
                <div class="modern-card-body">
                    <form action="{{ route('control.produccion.store') }}" method="POST" id="produccionForm">
                        @csrf

                        <!-- Información General -->
                        <div class="section-box border-cyan">
                            <div class="section-header">
                                <i class="fas fa-info-circle text-info"></i>
                                <h4>Información General</h4>
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
                                               value="{{ old('fecha', date('Y-m-d')) }}"
                                               readonly
                                               style="background-color: #e5e7eb; cursor: not-allowed;"
                                               required>
                                        @error('fecha')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="responsable" class="form-label required-mark">
                                            <i class="fas fa-user-tie"></i> Responsable
                                        </label>
                                        <select name="responsable"
                                                id="responsable"
                                                class="modern-select @error('responsable') is-invalid @enderror"
                                                required>
                                            <option value="">Seleccione un responsable...</option>
                                            @foreach($personal ?? [] as $persona)
                                                @php
                                                    $isCurrentUser = auth()->check() &&
                                                                     auth()->user()->personal &&
                                                                     auth()->user()->personal->id === $persona->id;
                                                    $shouldAutoSelect = $isCurrentUser && auth()->user()->rol->nombre === 'produccion';
                                                @endphp
                                                <option value="{{ $persona->nombre_completo }}"
                                                        {{ old('responsable') == $persona->nombre_completo || $shouldAutoSelect ? 'selected' : '' }}>
                                                    {{ $persona->nombre_completo }} ({{ $persona->cargo }})
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('responsable')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Gasto Material (Campo oculto) -->
                            <input type="hidden" name="gasto_material" value="0">
                        </div>

                        <!-- Productos Producidos -->
                        <div class="section-box border-green">
                            <div class="section-header">
                                <i class="fas fa-boxes text-success"></i>
                                <h4>Productos Producidos</h4>
                            </div>
                            <div class="d-flex justify-content-end mb-3">
                                <button type="button" class="btn-add-item" onclick="agregarProducto()">
                                    <i class="fas fa-plus"></i>
                                    Agregar Producto
                                </button>
                            </div>

                            <div id="productos-container">
                                <!-- Producto inicial -->
                                <div class="item-row" data-index="0">
                                    <div class="row align-items-end">
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label class="form-label required-mark">
                                                    <i class="fas fa-box"></i> Producto
                                                </label>
                                                <select name="productos[0][producto]" class="modern-select" required>
                                                    <option value="">Seleccione un producto...</option>
                                                    @foreach($productos ?? [] as $producto)
                                                        <option value="{{ $producto->nombre }}">{{ $producto->nombre }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label class="form-label required-mark">
                                                    <i class="fas fa-sort-numeric-up"></i> Cantidad
                                                </label>
                                                <input type="number"
                                                       name="productos[0][cantidad]"
                                                       class="modern-input text-center"
                                                       min="0"
                                                       value="0"
                                                       data-product-qty
                                                       required>
                                            </div>
                                        </div>
                                        <div class="col-md-1">
                                            <button type="button"
                                                    class="btn-remove-item w-100"
                                                    onclick="eliminarItem(this)"
                                                    title="Eliminar">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Materiales Utilizados -->
                        <div class="section-box border-purple">
                            <div class="section-header">
                                <i class="fas fa-tools text-purple-600"></i>
                                <h4>Materiales Utilizados</h4>
                            </div>
                            <div class="d-flex justify-content-end mb-3">
                                <button type="button" class="btn-add-item" onclick="agregarMaterial()">
                                    <i class="fas fa-plus"></i>
                                    Agregar Material
                                </button>
                            </div>

                            <div id="materiales-container">
                                <!-- Material inicial -->
                                <div class="item-row" data-index="0">
                                    <div class="row align-items-end">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">
                                                    <i class="fas fa-tools"></i> Material
                                                </label>
                                                <select name="materiales[0][material]" class="modern-select">
                                                    <option value="">Seleccione un material...</option>
                                                    <option value="Bolsa para empaquetar">Bolsa para empaquetar</option>
                                                    <option value="Etiquetas para botellones">Etiquetas para botellones</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label class="form-label">
                                                    <i class="fas fa-sort-numeric-up"></i> Cantidad
                                                </label>
                                                <input type="number"
                                                       name="materiales[0][cantidad]"
                                                       class="modern-input text-center"
                                                       min="0"
                                                       value="0">
                                            </div>
                                        </div>
                                        <div class="col-md-1">
                                            <button type="button"
                                                    class="btn-remove-item w-100"
                                                    onclick="eliminarItem(this)"
                                                    title="Eliminar">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Total de Producción -->
                        <div class="stat-display">
                            <p>Total de Productos Producidos</p>
                            <h3 id="totalProduccion">0</h3>
                        </div>

                        <!-- Observaciones -->
                        <div class="section-box border-blue">
                            <div class="section-header">
                                <i class="fas fa-sticky-note text-primary"></i>
                                <h4>Observaciones</h4>
                            </div>
                            <div class="form-group">
                                <textarea name="observaciones"
                                          id="observaciones"
                                          rows="4"
                                          class="modern-textarea @error('observaciones') is-invalid @enderror"
                                          placeholder="Ingrese observaciones adicionales...">{{ old('observaciones') }}</textarea>
                                @error('observaciones')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Botones de Acción -->
                        <div class="d-flex justify-content-end gap-3 mt-4">
                            <a href="{{ route('control.produccion.index') }}" class="btn-modern btn-secondary">
                                <i class="fas fa-times"></i>
                                Cancelar
                            </a>
                            <button type="submit" class="btn-modern btn-success">
                                <i class="fas fa-save"></i>
                                Guardar Producción
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
    let productoIndex = 1;
    let materialIndex = 1;

    $(document).ready(function() {
        // Inicializar Select2
        ModernComponents.initModernSelect2('.modern-select');

        // Calcular total dinámico
        function calcularTotal() {
            let total = 0;
            $('[data-product-qty]').each(function() {
                total += parseInt($(this).val()) || 0;
            });

            $('#totalProduccion').css('transform', 'scale(1.2)');
            setTimeout(() => {
                $('#totalProduccion').text(total.toLocaleString());
                $('#totalProduccion').css('transform', 'scale(1)');
            }, 150);
        }

        // Event listeners para cálculo
        $(document).on('input', '[data-product-qty]', function() {
            calcularTotal();
            $(this).addClass('input-changed');
            setTimeout(() => $(this).removeClass('input-changed'), 400);
        });

        // Calcular inicial
        calcularTotal();

        // Auto-select en inputs numéricos
        ModernComponents.initAutoSelect('input[type="number"]');

        // Validación en tiempo real
        ModernComponents.initRealtimeValidation('input[type="number"]');

        // Animación de secciones
        ModernComponents.initSectionAnimations();

        // Animación al enviar
        ModernComponents.initFormSubmitAnimation('#produccionForm');

        // Atajos de teclado
        ModernComponents.initKeyboardShortcuts('#produccionForm', '{{ route("control.produccion.index") }}');
    });

    // Agregar Producto
    function agregarProducto() {
        const html = `
            <div class="item-row" data-index="${productoIndex}">
                <div class="row align-items-end">
                    <div class="col-md-5">
                        <div class="form-group">
                            <label class="form-label required-mark">
                                <i class="fas fa-box"></i> Producto
                            </label>
                            <select name="productos[${productoIndex}][producto]" class="modern-select" required>
                                <option value="">Seleccione un producto...</option>
                                @foreach($productos ?? [] as $producto)
                                    <option value="{{ $producto->nombre }}">{{ $producto->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="form-label required-mark">
                                <i class="fas fa-sort-numeric-up"></i> Cantidad
                            </label>
                            <input type="number" name="productos[${productoIndex}][cantidad]" class="modern-input text-center" min="0" value="0" data-product-qty required>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn-remove-item w-100" onclick="eliminarItem(this)" title="Eliminar">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        `;
        $('#productos-container').append(html);
        ModernComponents.initModernSelect2(`select[name="productos[${productoIndex}][producto]"]`);
        productoIndex++;
    }

    // Agregar Material
    function agregarMaterial() {
        const html = `
            <div class="item-row" data-index="${materialIndex}">
                <div class="row align-items-end">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-tools"></i> Material
                            </label>
                            <select name="materiales[${materialIndex}][material]" class="modern-select">
                                <option value="">Seleccione un material...</option>
                                <option value="Bolsa para empaquetar">Bolsa para empaquetar</option>
                                <option value="Etiquetas para botellones">Etiquetas para botellones</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group">
                            <label class="form-label">
                                <i class="fas fa-sort-numeric-up"></i> Cantidad
                            </label>
                            <input type="number" name="materiales[${materialIndex}][cantidad]" class="modern-input text-center" min="0" value="0">
                        </div>
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn-remove-item w-100" onclick="eliminarItem(this)" title="Eliminar">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        `;
        $('#materiales-container').append(html);
        ModernComponents.initModernSelect2(`select[name="materiales[${materialIndex}][material]"]`);
        materialIndex++;
    }

    // Eliminar Item
    function eliminarItem(btn) {
        $(btn).closest('.item-row').fadeOut(300, function() {
            $(this).remove();
            // Recalcular si es producto
            if ($(btn).closest('#productos-container').length) {
                $(document).find('[data-product-qty]').first().trigger('input');
            }
        });
    }
</script>
@endpush
