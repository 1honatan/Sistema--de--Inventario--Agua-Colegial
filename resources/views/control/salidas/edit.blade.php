@extends('layouts.app')

@section('title', 'Editar Salida de Productos')
@section('page-title', 'Editar Salida de Productos')
@section('page-subtitle', 'Modifique los datos de la salida #' . $salida->id)

@section('content')
<div class="max-w-5xl mx-auto">
    {{-- Breadcrumb --}}
    <div class="mb-6">
        <nav class="text-sm">
            <a href="{{ route('admin.dashboard') }}" class="text-blue-600 hover:text-blue-800">Dashboard</a>
            <span class="mx-2 text-gray-500">/</span>
            <a href="{{ route('control.salidas.index') }}" class="text-blue-600 hover:text-blue-800">Salidas</a>
            <span class="mx-2 text-gray-500">/</span>
            <span class="text-gray-600">Editar #{{ $salida->id }}</span>
        </nav>
    </div>

    <div class="bg-white rounded-lg shadow-md p-8">
        <form action="{{ route('control.salidas.update', $salida) }}" method="POST" id="salidaForm">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                {{-- Tipo de Salida --}}
                <div>
                    <label for="tipo_salida" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-clipboard-list text-blue-600 mr-1"></i>
                        Tipo de Salida <span class="text-red-500">*</span>
                    </label>
                    <select name="tipo_salida" id="tipo_salida" required
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                        <option value="">Seleccione un tipo...</option>
                        <option value="Despacho Interno" {{ old('tipo_salida', $salida->tipo_salida) == 'Despacho Interno' ? 'selected' : '' }}>Despacho Interno</option>
                        <option value="Pedido Cliente" {{ old('tipo_salida', $salida->tipo_salida) == 'Pedido Cliente' ? 'selected' : '' }}>Pedido Cliente</option>
                        <option value="Venta Directa" {{ old('tipo_salida', $salida->tipo_salida) == 'Venta Directa' ? 'selected' : '' }}>Venta Directa</option>
                    </select>
                </div>

                {{-- Campos DESPACHO INTERNO --}}
                <div id="campos-despacho-interno" class="tipo-salida-fields hidden border-t border-gray-200 pt-6">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-user-tie text-blue-600 mr-1"></i> Chofer
                            </label>
                            <select name="chofer" id="chofer" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500">
                                <option value="">Seleccione...</option>
                                @foreach($choferes ?? [] as $chofer)
                                    <option value="{{ $chofer->nombre_completo }}" {{ old('chofer', $salida->chofer) == $chofer->nombre_completo ? 'selected' : '' }}>{{ $chofer->nombre_completo }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-user text-blue-600 mr-1"></i> Distribuidor <span class="text-red-500">*</span>
                            </label>
                            <select name="nombre_distribuidor" id="nombre_distribuidor" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500">
                                <option value="">Seleccione...</option>
                                @foreach($distribuidores ?? [] as $distribuidor)
                                    <option value="{{ $distribuidor->nombre_completo }}" {{ old('nombre_distribuidor', $salida->nombre_distribuidor) == $distribuidor->nombre_completo ? 'selected' : '' }}>{{ $distribuidor->nombre_completo }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-car text-blue-600 mr-1"></i> Vehículo
                            </label>
                            <select name="vehiculo_placa" id="vehiculo_placa" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500">
                                <option value="">Seleccione...</option>
                                @foreach($vehiculos ?? [] as $vehiculo)
                                    <option value="{{ $vehiculo->placa }}" {{ old('vehiculo_placa', $salida->vehiculo_placa) == $vehiculo->placa ? 'selected' : '' }}>{{ $vehiculo->placa }} - {{ $vehiculo->marca }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-calendar-alt text-blue-600 mr-1"></i> Fecha
                            </label>
                            <input type="date" name="fecha" id="fecha_despacho" value="{{ old('fecha', $salida->fecha->format('Y-m-d')) }}" readonly
                                   class="w-full border border-gray-300 rounded-lg px-4 py-3 bg-gray-100 cursor-not-allowed">
                        </div>
                    </div>
                </div>

                {{-- Campos PEDIDO CLIENTE --}}
                <div id="campos-pedido-cliente" class="tipo-salida-fields hidden border-t border-gray-200 pt-6">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-user text-blue-600 mr-1"></i> Cliente <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nombre_cliente" id="nombre_cliente" value="{{ old('nombre_cliente', $salida->nombre_cliente) }}" placeholder="Nombre del cliente"
                                   class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-map-marker-alt text-blue-600 mr-1"></i> Dirección <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="direccion_entrega" id="direccion_entrega" value="{{ old('direccion_entrega', $salida->direccion_entrega) }}" placeholder="Dirección de entrega"
                                   class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-phone text-blue-600 mr-1"></i> Teléfono
                            </label>
                            <input type="text" name="telefono_cliente" value="{{ old('telefono_cliente', $salida->telefono_cliente) }}" placeholder="0000-0000"
                                   class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-calendar-alt text-blue-600 mr-1"></i> Fecha
                            </label>
                            <input type="date" name="fecha" id="fecha_pedido" value="{{ old('fecha', $salida->fecha->format('Y-m-d')) }}" readonly
                                   class="w-full border border-gray-300 rounded-lg px-4 py-3 bg-gray-100 cursor-not-allowed">
                        </div>
                    </div>
                </div>

                {{-- Campos VENTA DIRECTA --}}
                <div id="campos-venta-directa" class="tipo-salida-fields hidden border-t border-gray-200 pt-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-user text-blue-600 mr-1"></i> Cliente <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nombre_cliente" id="nombre_cliente_venta" value="{{ old('nombre_cliente', $salida->nombre_cliente) }}" placeholder="Nombre del cliente"
                                   class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-user-tie text-blue-600 mr-1"></i> Responsable <span class="text-red-500">*</span>
                            </label>
                            <select name="responsable_venta" id="responsable_venta" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500">
                                <option value="">Seleccione...</option>
                                @foreach($responsablesVenta ?? [] as $responsable)
                                    <option value="{{ $responsable->nombre_completo }}" {{ old('responsable_venta', $salida->responsable_venta) == $responsable->nombre_completo ? 'selected' : '' }}>{{ $responsable->nombre_completo }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-calendar-alt text-blue-600 mr-1"></i> Fecha
                            </label>
                            <input type="text" value="{{ $salida->fecha->format('d/m/Y') }}" readonly class="w-full border border-gray-300 rounded-lg px-4 py-3 bg-gray-100 cursor-not-allowed">
                            <input type="hidden" name="fecha" id="fecha_venta" value="{{ $salida->fecha->format('Y-m-d') }}">
                        </div>
                    </div>
                </div>

                {{-- Productos --}}
                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">
                        <i class="fas fa-boxes text-blue-600 mr-2"></i>
                        Detalle de Productos
                    </h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4">
                        @foreach($productos as $producto)
                        <div class="bg-gray-50 border-2 border-gray-200 rounded-lg p-4 hover:border-blue-400 transition">
                            <label class="block text-xs font-bold text-gray-700 mb-2">
                                <i class="fas {{ $producto['icono'] }} text-blue-600"></i> {{ $producto['nombre'] }}
                            </label>
                            <input type="number" name="productos[{{ $producto['id'] }}]" value="{{ old('productos.'.$producto['id'], $salida->{$producto['campo']} ?? 0) }}" min="0"
                                   data-product-input
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-center text-xl font-bold focus:ring-2 focus:ring-blue-500">
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- Retornos (solo Despacho Interno) --}}
                <div id="seccion-retornos" class="hidden border-t border-gray-200 pt-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">
                        <i class="fas fa-recycle text-amber-600 mr-2"></i>
                        Productos de Retorno
                    </h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4">
                        @foreach($productos as $producto)
                        <div class="bg-amber-50 border-2 border-amber-200 rounded-lg p-4 hover:border-amber-400 transition">
                            <label class="block text-xs font-bold text-amber-800 mb-2">
                                <i class="fas {{ $producto['icono'] }} text-amber-600"></i> {{ $producto['nombre'] }}
                            </label>
                            @php
                                $campoRetorno = 'retorno_' . str_replace('ñ', 'n', $producto['campo']);
                            @endphp
                            <input type="number" name="retornos[{{ $producto['id'] }}]" value="{{ old('retornos.'.$producto['id'], $salida->{$campoRetorno} ?? 0) }}" min="0" data-retorno-input
                                   class="w-full border border-amber-300 rounded-lg px-3 py-2 text-center text-xl font-bold focus:ring-2 focus:ring-amber-500">
                        </div>
                        @endforeach
                    </div>
                    <div class="bg-gradient-to-r from-amber-500 to-orange-500 rounded-lg p-6 text-center text-white mt-4">
                        <p class="text-sm font-medium opacity-90 mb-1">Total de Retornos</p>
                        <h3 id="totalRetornos" class="text-4xl font-bold">0</h3>
                    </div>
                </div>

                {{-- Total --}}
                <div class="bg-gradient-to-r from-blue-600 to-blue-800 rounded-lg p-6 text-center text-white">
                    <p class="text-sm font-medium opacity-90 mb-1">Total de Productos</p>
                    <h3 id="totalProductos" class="text-4xl font-bold">0</h3>
                </div>

                {{-- Observaciones --}}
                <div>
                    <label for="observaciones" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-sticky-note text-blue-600 mr-1"></i>
                        Observaciones
                    </label>
                    <textarea name="observaciones" id="observaciones" rows="3"
                              class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500"
                              placeholder="Ingrese observaciones...">{{ old('observaciones', $salida->observaciones) }}</textarea>
                </div>

                {{-- Botones --}}
                <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                    <a href="{{ route('control.salidas.index') }}" class="text-gray-600 hover:text-gray-800 font-semibold">
                        <i class="fas fa-arrow-left mr-2"></i> Volver
                    </a>
                    <div class="flex space-x-3">
                        <button type="reset" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-3 rounded-lg font-semibold transition">
                            <i class="fas fa-redo mr-2"></i> Restablecer
                        </button>
                        <button type="submit" class="bg-gradient-to-r from-blue-600 to-blue-800 hover:from-blue-700 hover:to-blue-900 text-white px-6 py-3 rounded-lg font-semibold shadow-lg transition">
                            <i class="fas fa-save mr-2"></i> Actualizar Salida
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    function actualizarCamposPorTipo() {
        const tipo = $('#tipo_salida').val();
        $('.tipo-salida-fields').addClass('hidden');
        $('#seccion-retornos').addClass('hidden');

        if (tipo === 'Despacho Interno') {
            $('#campos-despacho-interno').removeClass('hidden');
            $('#seccion-retornos').removeClass('hidden');
        } else if (tipo === 'Pedido Cliente') {
            $('#campos-pedido-cliente').removeClass('hidden');
        } else if (tipo === 'Venta Directa') {
            $('#campos-venta-directa').removeClass('hidden');
        }
    }

    $('#tipo_salida').on('change', actualizarCamposPorTipo);
    actualizarCamposPorTipo();

    function calcularTotal() {
        let total = 0;
        $('[data-product-input]').each(function() { total += parseInt($(this).val()) || 0; });
        $('#totalProductos').text(total.toLocaleString());
    }

    function calcularRetornos() {
        let total = 0;
        $('[data-retorno-input]').each(function() { total += parseInt($(this).val()) || 0; });
        $('#totalRetornos').text(total.toLocaleString());
    }

    $(document).on('input', '[data-product-input]', calcularTotal);
    $(document).on('input', '[data-retorno-input]', calcularRetornos);

    $('[data-product-input], [data-retorno-input]').on('click', function() { $(this).select(); });

    calcularTotal();
    calcularRetornos();
});
</script>
@endpush
