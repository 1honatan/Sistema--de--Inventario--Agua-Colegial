@extends('layouts.app')

@section('title', 'Editar Salida de Productos')

@push('styles')
<style>
    body {
        background: #c0eaff;
        min-height: 100vh;
    }

    .form-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 2rem;
    }

    .form-card {
        background: #ffffff;
        border-radius: 20px;
        box-shadow: 0 8px 25px rgba(30, 58, 138, 0.15);
        overflow: hidden;
        animation: slideUp 0.5s ease-out;
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .form-header {
        background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 100%);
        color: white;
        padding: 2rem 2.5rem;
        border-bottom: 4px solid #1e3a8a;
    }

    .form-header h2 {
        color: white;
        font-size: 1.75rem;
        font-weight: 800;
        margin: 0 0 0.5rem 0;
    }

    .form-header p {
        color: rgba(255, 255, 255, 0.9);
        margin: 0;
        font-size: 0.95rem;
    }

    .form-body {
        padding: 2.5rem;
    }

    .section-divider {
        border: none;
        height: 2px;
        background: linear-gradient(90deg, transparent, #1e3a8a, transparent);
        margin: 2.5rem 0;
        opacity: 0.3;
    }

    .input-group {
        margin-bottom: 1.5rem;
    }

    .input-label {
        color: #333333;
        font-size: 0.875rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .input-label i {
        color: #1e3a8a;
    }

    .input-label.required::after {
        content: " *";
        color: #dc2626;
        font-weight: 900;
    }

    .form-input,
    .form-select,
    .form-textarea {
        background: #f8fafc;
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        padding: 0.75rem 1rem;
        color: #333333;
        font-size: 1rem;
        font-weight: 500;
        transition: all 0.3s ease;
        width: 100%;
    }

    .form-input:focus,
    .form-select:focus,
    .form-textarea:focus {
        background: #ffffff;
        border-color: #1e3a8a;
        outline: none;
        box-shadow: 0 0 0 3px rgba(30, 58, 138, 0.1);
    }

    /* Productos */
    .products-section {
        background: #f8fafc;
        border: 2px solid #e2e8f0;
        border-radius: 15px;
        padding: 2rem;
        margin: 2rem 0;
    }

    .products-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 1.25rem;
        margin-top: 1.5rem;
    }

    .product-box {
        background: #ffffff;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        padding: 1.25rem;
        transition: all 0.3s ease;
    }

    .product-box:hover {
        border-color: #1e3a8a;
        box-shadow: 0 6px 15px rgba(30, 58, 138, 0.15);
        transform: translateY(-3px);
    }

    .product-box label {
        color: #333333;
        font-size: 0.8rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 0.75rem;
    }

    .product-box label i {
        font-size: 1.25rem;
        color: #1e3a8a;
    }

    .product-box input {
        background: #f8fafc;
        border: 2px solid #cbd5e1;
        border-radius: 8px;
        color: #333333;
        font-size: 1.5rem;
        font-weight: 800;
        text-align: center;
        padding: 0.5rem;
        width: 100%;
        transition: all 0.3s ease;
    }

    .product-box input:focus {
        border-color: #1e3a8a;
        background: #ffffff;
        box-shadow: 0 0 0 3px rgba(30, 58, 138, 0.1);
        outline: none;
    }

    /* Observaciones */
    .obs-section {
        background: #fef3c7;
        border: 2px solid #fbbf24;
        border-radius: 15px;
        padding: 1.5rem;
        margin-top: 2rem;
    }

    .obs-section label {
        color: #92400e;
        font-size: 0.875rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.75rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .obs-section label i {
        color: #f59e0b;
        font-size: 1.25rem;
    }

    .obs-section textarea {
        background: #ffffff;
        border: 2px solid #fbbf24;
        border-radius: 10px;
        padding: 1rem;
        color: #333333;
        font-size: 0.95rem;
        resize: vertical;
        min-height: 100px;
    }

    .obs-section textarea:focus {
        border-color: #f59e0b;
        box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.1);
        outline: none;
    }

    /* Botones */
    .btn-colegial {
        padding: 0.875rem 2.5rem;
        border-radius: 10px;
        font-weight: 800;
        font-size: 1rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border: none;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .btn-save {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
    }

    .btn-save:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4);
        color: white;
    }

    .btn-cancel {
        background: linear-gradient(135deg, #64748b 0%, #475569 100%);
        color: white;
        box-shadow: 0 4px 15px rgba(100, 116, 139, 0.3);
    }

    .btn-cancel:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(100, 116, 139, 0.4);
        color: white;
    }
</style>
@endpush

@section('content')
<div class="form-container">
    <div class="form-card">
        <div class="form-header">
            <h2>
                <i class="fas fa-edit mr-3"></i>
                Editar Salida #{{ $salida->id }}
            </h2>
            <p>Modifique los datos de la salida de productos</p>
        </div>

        <div class="form-body">
            <form action="{{ route('control.salidas.update', $salida) }}" method="POST" id="salidaForm">
                @csrf
                @method('PUT')

                <!-- Tipo de Salida -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="input-group">
                            <div class="input-label required">
                                <i class="fas fa-clipboard-list"></i>
                                Tipo de Salida
                            </div>
                            <select name="tipo_salida" id="tipo_salida" class="form-select @error('tipo_salida') is-invalid @enderror" required>
                                <option value="">Seleccione un tipo...</option>
                                <option value="Despacho Interno" {{ old('tipo_salida', $salida->tipo_salida) == 'Despacho Interno' ? 'selected' : '' }}>Despacho Interno</option>
                                <option value="Pedido Cliente" {{ old('tipo_salida', $salida->tipo_salida) == 'Pedido Cliente' ? 'selected' : '' }}>Pedido Cliente</option>
                                <option value="Venta Directa" {{ old('tipo_salida', $salida->tipo_salida) == 'Venta Directa' ? 'selected' : '' }}>Venta Directa</option>
                            </select>
                            @error('tipo_salida')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Campos para DESPACHO INTERNO -->
                <div id="campos-despacho-interno" class="tipo-salida-fields" style="display: none;">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="input-group">
                                <div class="input-label required">
                                    <i class="fas fa-user-tie"></i>
                                    Responsable / Distribuidor
                                </div>
                                <select name="nombre_distribuidor" id="nombre_distribuidor" class="form-select @error('nombre_distribuidor') is-invalid @enderror">
                                    <option value="">Seleccione un responsable...</option>
                                    @foreach($distribuidores ?? [] as $distribuidor)
                                        <option value="{{ $distribuidor->nombre_completo }}" {{ old('nombre_distribuidor', $salida->nombre_distribuidor) == $distribuidor->nombre_completo ? 'selected' : '' }}>
                                            {{ $distribuidor->nombre_completo }} ({{ $distribuidor->cargo }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('nombre_distribuidor')
                                    <div class="text-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="input-group">
                                <div class="input-label">
                                    <i class="fas fa-car"></i>
                                    Vehículo
                                </div>
                                <select name="vehiculo_placa" id="vehiculo_placa" class="form-select @error('vehiculo_placa') is-invalid @enderror">
                                    <option value="">Seleccione un vehículo...</option>
                                    @foreach($vehiculos ?? [] as $vehiculo)
                                        <option value="{{ $vehiculo->placa }}" {{ old('vehiculo_placa', $salida->vehiculo_placa) == $vehiculo->placa ? 'selected' : '' }}>
                                            {{ $vehiculo->placa }} - {{ $vehiculo->marca }} {{ $vehiculo->modelo }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('vehiculo_placa')
                                    <div class="text-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="input-group">
                                <div class="input-label required">
                                    <i class="fas fa-calendar-alt"></i>
                                    Fecha
                                </div>
                                <input type="date" name="fecha" id="fecha_despacho" class="form-input @error('fecha') is-invalid @enderror" value="{{ old('fecha', $salida->fecha->format('Y-m-d')) }}" readonly style="background-color: #f3f4f6; cursor: not-allowed;">
                                @error('fecha')
                                    <div class="text-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="input-group">
                                <div class="input-label">
                                    <i class="fas fa-clock"></i>
                                    Hora de Llegada
                                </div>
                                <input type="time" name="hora_llegada" id="hora_llegada" class="form-input @error('hora_llegada') is-invalid @enderror" value="{{ old('hora_llegada', $salida->hora_llegada ? \Carbon\Carbon::parse($salida->hora_llegada)->format('H:i') : '') }}">
                                @error('hora_llegada')
                                    <div class="text-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Campos para PEDIDO CLIENTE -->
                <div id="campos-pedido-cliente" class="tipo-salida-fields" style="display: none;">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <div class="input-group">
                                <div class="input-label required">
                                    <i class="fas fa-user"></i>
                                    Nombre del Cliente
                                </div>
                                <input type="text" name="nombre_cliente" id="nombre_cliente" class="form-input @error('nombre_cliente') is-invalid @enderror" value="{{ old('nombre_cliente', $salida->nombre_cliente) }}" placeholder="Ingrese el nombre del cliente">
                                @error('nombre_cliente')
                                    <div class="text-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="input-group">
                                <div class="input-label required">
                                    <i class="fas fa-map-marker-alt"></i>
                                    Dirección de Entrega
                                </div>
                                <input type="text" name="direccion_entrega" id="direccion_entrega" class="form-input @error('direccion_entrega') is-invalid @enderror" value="{{ old('direccion_entrega', $salida->direccion_entrega) }}" placeholder="Ingrese la dirección de entrega">
                                @error('direccion_entrega')
                                    <div class="text-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="input-group">
                                <div class="input-label">
                                    <i class="fas fa-phone"></i>
                                    Teléfono
                                </div>
                                <input type="text" name="telefono_cliente" id="telefono_cliente" class="form-input @error('telefono_cliente') is-invalid @enderror" value="{{ old('telefono_cliente', $salida->telefono_cliente) }}" placeholder="0000-0000">
                                @error('telefono_cliente')
                                    <div class="text-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="input-group">
                                <div class="input-label required">
                                    <i class="fas fa-calendar-alt"></i>
                                    Fecha
                                </div>
                                <input type="date" name="fecha" id="fecha_pedido" class="form-input @error('fecha') is-invalid @enderror" value="{{ old('fecha', $salida->fecha->format('Y-m-d')) }}" readonly style="background-color: #f3f4f6; cursor: not-allowed;">
                                @error('fecha')
                                    <div class="text-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="input-group">
                                <div class="input-label">
                                    <i class="fas fa-user"></i>
                                    Chofer
                                </div>
                                <select name="chofer" id="chofer_pedido" class="form-select @error('chofer') is-invalid @enderror">
                                    <option value="">Seleccione...</option>
                                    @foreach($choferes ?? [] as $chofer)
                                        <option value="{{ $chofer->nombre_completo }}" {{ old('chofer', $salida->chofer) == $chofer->nombre_completo ? 'selected' : '' }}>
                                            {{ $chofer->nombre_completo }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('chofer')
                                    <div class="text-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="input-group">
                                <div class="input-label">
                                    <i class="fas fa-user-tie"></i>
                                    Distribuidor
                                </div>
                                <select name="nombre_distribuidor" id="distribuidor_pedido" class="form-select @error('nombre_distribuidor') is-invalid @enderror">
                                    <option value="">Seleccione...</option>
                                    @foreach($distribuidores ?? [] as $distribuidor)
                                        <option value="{{ $distribuidor->nombre_completo }}" {{ old('nombre_distribuidor', $salida->nombre_distribuidor) == $distribuidor->nombre_completo ? 'selected' : '' }}>
                                            {{ $distribuidor->nombre_completo }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('nombre_distribuidor')
                                    <div class="text-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="input-group">
                                <div class="input-label">
                                    <i class="fas fa-car"></i>
                                    Vehículo
                                </div>
                                <select name="vehiculo_placa" id="vehiculo_pedido" class="form-select @error('vehiculo_placa') is-invalid @enderror">
                                    <option value="">Seleccione...</option>
                                    @foreach($vehiculos ?? [] as $vehiculo)
                                        <option value="{{ $vehiculo->placa }}" {{ old('vehiculo_placa', $salida->vehiculo_placa) == $vehiculo->placa ? 'selected' : '' }}>
                                            {{ $vehiculo->placa }} - {{ $vehiculo->marca }} {{ $vehiculo->modelo }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('vehiculo_placa')
                                    <div class="text-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="input-group">
                                <div class="input-label">
                                    <i class="fas fa-clock"></i>
                                    Hora de Llegada
                                </div>
                                <input type="time" name="hora_llegada" id="hora_llegada_pedido" class="form-input @error('hora_llegada') is-invalid @enderror" value="{{ old('hora_llegada', $salida->hora_llegada ? \Carbon\Carbon::parse($salida->hora_llegada)->format('H:i') : '') }}">
                                @error('hora_llegada')
                                    <div class="text-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Campos para VENTA DIRECTA -->
                <div id="campos-venta-directa" class="tipo-salida-fields" style="display: none;">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="input-group">
                                <div class="input-label required">
                                    <i class="fas fa-user"></i>
                                    Nombre del Cliente
                                </div>
                                <input type="text" name="nombre_cliente" id="nombre_cliente_venta" class="form-input @error('nombre_cliente') is-invalid @enderror" value="{{ old('nombre_cliente', $salida->nombre_cliente) }}" placeholder="Ingrese el nombre del cliente">
                                @error('nombre_cliente')
                                    <div class="text-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="input-group">
                                <div class="input-label required">
                                    <i class="fas fa-user-tie"></i>
                                    Responsable
                                </div>
                                <select name="responsable_venta" id="responsable_venta" class="form-select @error('responsable_venta') is-invalid @enderror">
                                    <option value="">Seleccione un responsable...</option>
                                    @foreach($responsablesVenta ?? [] as $responsable)
                                        @php
                                            $isCurrentUser = auth()->check() &&
                                                             auth()->user()->personal &&
                                                             auth()->user()->personal->id === $responsable->id;
                                            $shouldAutoSelect = $isCurrentUser && auth()->user()->rol->nombre === 'produccion';
                                        @endphp
                                        <option value="{{ $responsable->nombre_completo }}" {{ old('responsable_venta', $salida->responsable_venta) == $responsable->nombre_completo || (!old('responsable_venta') && !$salida->responsable_venta && $shouldAutoSelect) ? 'selected' : '' }}>
                                            {{ $responsable->nombre_completo }} ({{ $responsable->cargo }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('responsable_venta')
                                    <div class="text-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="input-group">
                                <div class="input-label">
                                    <i class="fas fa-calendar-alt"></i>
                                    Fecha
                                </div>
                                <input type="text" class="form-input" value="{{ $salida->fecha->format('d/m/Y') }}" readonly style="background: #e2e8f0; cursor: not-allowed;">
                                <input type="hidden" name="fecha" id="fecha_venta" value="{{ $salida->fecha->format('Y-m-d') }}">
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="section-divider">

                <!-- Detalle de Productos (solo para Despacho Interno y Pedido Cliente) -->
                <div id="seccion-productos">
                    <div class="products-section">
                        <div class="input-label">
                            <i class="fas fa-boxes"></i>
                            Detalle de Productos
                        </div>

                        <div class="products-grid">
                            @php
                                $productosDisponibles = [
                                    ['id' => 1, 'nombre' => 'Agua Natural', 'campo' => 'agua_natural', 'icono' => 'fa-water'],
                                    ['id' => 2, 'nombre' => 'Agua de Limón', 'campo' => 'agua_limon', 'icono' => 'fa-lemon'],
                                    ['id' => 3, 'nombre' => 'Agua de Sabor', 'campo' => 'agua_saborizada', 'icono' => 'fa-tint'],
                                    ['id' => 4, 'nombre' => 'Bolo Grande', 'campo' => 'bolo_grande', 'icono' => 'fa-shopping-bag'],
                                    ['id' => 5, 'nombre' => 'Bolo Pequeño', 'campo' => 'bolo_pequeño', 'icono' => 'fa-shopping-bag'],
                                    ['id' => 6, 'nombre' => 'Gelatina', 'campo' => 'gelatina', 'icono' => 'fa-cube'],
                                    ['id' => 7, 'nombre' => 'Hielo', 'campo' => 'hielo', 'icono' => 'fa-snowflake'],
                                    ['id' => 9, 'nombre' => 'Botellones', 'campo' => 'botellones', 'icono' => 'fa-water'],
                                    ['id' => 11, 'nombre' => 'Dispenser', 'campo' => 'dispenser', 'icono' => 'fa-faucet'],
                                ];
                            @endphp

                            @foreach($productosDisponibles as $producto)
                            <div class="product-box">
                                <label>
                                    <i class="fas {{ $producto['icono'] }}"></i> {{ $producto['nombre'] }}
                                </label>
                                <input type="number"
                                       name="productos[{{ $producto['id'] }}]"
                                       id="producto_{{ $producto['id'] }}"
                                       value="{{ old('productos.' . $producto['id'], $salida->{$producto['campo']} ?? 0) }}"
                                       min="0"
                                       data-product-input>
                            </div>
                            @endforeach

                            <div class="product-box">
                                <label>
                                    <i class="fas fa-tint"></i> Chorreados
                                </label>
                                <input type="number"
                                       name="choreados"
                                       id="choreados"
                                       value="{{ old('choreados', $salida->choreados ?? 0) }}"
                                       min="0"
                                       data-product-input>
                            </div>
                        </div>
                    </div>

                    <!-- Sección de Retornos (solo para Despacho Interno) -->
                    <div id="seccion-retornos" style="display: none;">
                        <hr class="section-divider">

                        <div class="products-section" style="background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%); border-color: #fbbf24;">
                            <div class="input-label" style="color: #92400e;">
                                <i class="fas fa-recycle" style="color: #f59e0b;"></i>
                                Detalle de Productos de Retorno
                            </div>

                            <div class="products-grid">
                                @foreach($productosDisponibles as $producto)
                                <div class="product-box" style="border-color: #fbbf24;">
                                    <label style="color: #92400e;">
                                        <i class="fas {{ $producto['icono'] }}" style="color: #f59e0b;"></i> {{ $producto['nombre'] }}
                                    </label>
                                    <input type="number"
                                           name="retornos[{{ $producto['id'] }}]"
                                           id="retorno_{{ $producto['id'] }}"
                                           value="{{ old('retornos.' . $producto['id'], $salida->{'retorno_' . $producto['campo']} ?? 0) }}"
                                           min="0"
                                           data-retorno-input
                                           style="border-color: #fbbf24;">
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Observaciones -->
                <div class="obs-section">
                    <label>
                        <i class="fas fa-sticky-note"></i>
                        Observaciones
                    </label>
                    <textarea name="observaciones" id="observaciones" class="form-textarea @error('observaciones') is-invalid @enderror" placeholder="Ingrese cualquier observación relevante...">{{ old('observaciones', $salida->observaciones) }}</textarea>
                    @error('observaciones')
                        <div class="text-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Botones -->
                <div class="d-flex justify-content-end gap-3 mt-4">
                    <a href="{{ route('control.salidas.index') }}" class="btn-colegial btn-cancel">
                        <i class="fas fa-times mr-2"></i>
                        Cancelar
                    </a>
                    <button type="submit" class="btn-colegial btn-save">
                        <i class="fas fa-save mr-2"></i>
                        Actualizar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Función para mostrar/ocultar campos según tipo de salida
    function actualizarCamposPorTipo() {
        const tipoSalida = $('#tipo_salida').val();

        // Ocultar todos los campos específicos
        $('.tipo-salida-fields').hide();
        $('#seccion-retornos').hide();
        $('#seccion-productos').show();

        // Deshabilitar todos los campos para que no se envíen
        $('.tipo-salida-fields input, .tipo-salida-fields select').prop('required', false).prop('disabled', true);
        $('#seccion-retornos input').prop('required', false);

        // Mostrar campos según el tipo seleccionado
        if (tipoSalida === 'Despacho Interno') {
            $('#campos-despacho-interno').show();
            $('#seccion-retornos').show();
            $('#campos-despacho-interno input, #campos-despacho-interno select').prop('disabled', false);
            $('#nombre_distribuidor').prop('required', true);
            $('#fecha_despacho').prop('required', true);
        } else if (tipoSalida === 'Pedido Cliente') {
            $('#campos-pedido-cliente').show();
            $('#campos-pedido-cliente input, #campos-pedido-cliente select').prop('disabled', false);
            $('#nombre_cliente').prop('required', true);
            $('#direccion_entrega').prop('required', true);
            $('#campos-pedido-cliente input[name="fecha"]').prop('required', true);
        } else if (tipoSalida === 'Venta Directa') {
            $('#campos-venta-directa').show();
            $('#campos-venta-directa input:not([readonly]), #campos-venta-directa select').prop('disabled', false);
            $('#nombre_cliente_venta').prop('required', true);
            $('#responsable_venta').prop('required', true);
        }
    }

    // Ejecutar al cambiar el tipo de salida
    $('#tipo_salida').on('change', actualizarCamposPorTipo);

    // Ejecutar al cargar la página
    actualizarCamposPorTipo();

    // Auto-seleccionar al hacer clic en productos
    $('[data-product-input], [data-retorno-input]').on('click', function() {
        $(this).select();
    });

    // Validación en tiempo real
    $('[data-product-input], [data-retorno-input]').on('input', function() {
        if (parseInt($(this).val()) < 0) {
            $(this).val(0);
        }
    });

    // Animación de botón al enviar
    $('#salidaForm').on('submit', function(e) {
        const btn = $(this).find('button[type="submit"]');
        btn.html('<i class="fas fa-spinner fa-spin mr-2"></i>Actualizando...').prop('disabled', true);
    });
});
</script>
@endpush
