@extends('layouts.app')

@section('title', 'Nueva Salida de Productos')
@section('page-title', 'Nueva Salida de Productos')
@section('page-subtitle', 'Complete los datos para registrar una nueva salida')

@section('content')
<div class="max-w-5xl mx-auto">
    {{-- Breadcrumb --}}
    <div class="mb-6">
        <nav class="text-sm">
            <a href="{{ route('admin.dashboard') }}" class="text-blue-600 hover:text-blue-800">Dashboard</a>
            <span class="mx-2 text-gray-500">/</span>
            <a href="{{ route('control.salidas.index') }}" class="text-blue-600 hover:text-blue-800">Salidas</a>
            <span class="mx-2 text-gray-500">/</span>
            <span class="text-gray-600">Nueva Salida</span>
        </nav>
    </div>

    <div class="bg-white rounded-lg shadow-md p-8">
        <form action="{{ route('control.salidas.store') }}" method="POST" id="salidaForm">
            @csrf

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
                        <option value="Despacho Interno" {{ old('tipo_salida') == 'Despacho Interno' ? 'selected' : '' }}>Despacho Interno</option>
                        <option value="Pedido Cliente" {{ old('tipo_salida') == 'Pedido Cliente' ? 'selected' : '' }}>Pedido Cliente</option>
                        <option value="Venta Directa" {{ old('tipo_salida') == 'Venta Directa' ? 'selected' : '' }}>Venta Directa</option>
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
                                    <option value="{{ $chofer->nombre_completo }}">{{ $chofer->nombre_completo }}</option>
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
                                    <option value="{{ $distribuidor->nombre_completo }}">{{ $distribuidor->nombre_completo }}</option>
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
                                    <option value="{{ $vehiculo->placa }}" data-responsable="{{ $vehiculo->responsable }}">{{ $vehiculo->placa }} - {{ $vehiculo->marca }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-calendar-alt text-blue-600 mr-1"></i> Fecha
                            </label>
                            <input type="date" name="fecha" id="fecha_despacho" value="{{ date('Y-m-d') }}" readonly
                                   class="w-full border border-gray-300 rounded-lg px-4 py-3 bg-gray-100 cursor-not-allowed">
                        </div>
                    </div>
                </div>

                {{-- Campos PEDIDO CLIENTE --}}
                <div id="campos-pedido-cliente" class="tipo-salida-fields hidden border-t border-gray-200 pt-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-user text-blue-600 mr-1"></i> Cliente <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nombre_cliente" id="nombre_cliente" placeholder="Nombre del cliente"
                                   class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-map-marker-alt text-blue-600 mr-1"></i> Dirección <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="direccion_entrega" id="direccion_entrega" placeholder="Dirección de entrega"
                                   class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-phone text-blue-600 mr-1"></i> Teléfono
                            </label>
                            <input type="text" name="telefono_cliente" placeholder="0000-0000"
                                   class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-user-tie text-green-600 mr-1"></i> Chofer
                            </label>
                            <select name="chofer" id="chofer_pedido" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-green-500">
                                <option value="">Seleccione...</option>
                                @foreach($choferes ?? [] as $chofer)
                                    <option value="{{ $chofer->nombre_completo }}">{{ $chofer->nombre_completo }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-people-carry text-purple-600 mr-1"></i> Distribuidor
                            </label>
                            <select name="nombre_distribuidor" id="distribuidor_pedido" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-purple-500">
                                <option value="">Seleccione...</option>
                                @foreach($distribuidores ?? [] as $distribuidor)
                                    <option value="{{ $distribuidor->nombre_completo }}">{{ $distribuidor->nombre_completo }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-car text-blue-600 mr-1"></i> Vehículo
                            </label>
                            <select name="vehiculo_placa" id="vehiculo_pedido" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500">
                                <option value="">Seleccione...</option>
                                @foreach($vehiculos ?? [] as $vehiculo)
                                    <option value="{{ $vehiculo->placa }}" data-responsable="{{ $vehiculo->responsable }}">{{ $vehiculo->placa }} - {{ $vehiculo->marca }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-calendar-alt text-blue-600 mr-1"></i> Fecha
                            </label>
                            <input type="date" name="fecha" id="fecha_pedido" value="{{ date('Y-m-d') }}" readonly
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
                            <input type="text" name="nombre_cliente" id="nombre_cliente_venta" placeholder="Nombre del cliente"
                                   class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-user-tie text-blue-600 mr-1"></i> Responsable <span class="text-red-500">*</span>
                            </label>
                            <select name="responsable_venta" id="responsable_venta" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500">
                                <option value="">Seleccione...</option>
                                @foreach($responsablesVenta ?? [] as $responsable)
                                    <option value="{{ $responsable->nombre_completo }}">{{ $responsable->nombre_completo }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-calendar-alt text-blue-600 mr-1"></i> Fecha
                            </label>
                            <input type="text" value="{{ date('d/m/Y') }}" readonly class="w-full border border-gray-300 rounded-lg px-4 py-3 bg-gray-100 cursor-not-allowed">
                            <input type="hidden" name="fecha" id="fecha_venta" value="{{ date('Y-m-d') }}">
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
                        <div class="bg-gray-50 border-2 border-gray-200 rounded-lg p-4 hover:border-blue-400 transition {{ $producto['stock'] <= 0 ? 'opacity-50' : '' }}">
                            <label class="block text-xs font-bold text-gray-700 mb-2">
                                <i class="fas {{ $producto['icono'] }} text-blue-600"></i> {{ $producto['nombre'] }}
                            </label>
                            <input type="number" name="productos[{{ $producto['id'] }}]" value="0" min="0" max="{{ $producto['stock'] }}"
                                   data-product-input {{ $producto['stock'] <= 0 ? 'disabled' : '' }}
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-center text-xl font-bold focus:ring-2 focus:ring-blue-500 {{ $producto['stock'] <= 0 ? 'bg-red-100 cursor-not-allowed' : '' }}">
                            <small class="text-xs {{ $producto['stock'] <= 0 ? 'text-red-600' : 'text-green-600' }} font-semibold block mt-1">
                                Stock: {{ number_format($producto['stock']) }}
                            </small>
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
                            <input type="number" name="retornos[{{ $producto['id'] }}]" value="0" min="0" data-retorno-input
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
                              placeholder="Ingrese observaciones...">{{ old('observaciones') }}</textarea>
                </div>

                {{-- Botones --}}
                <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                    <a href="{{ route('control.salidas.index') }}" class="text-gray-600 hover:text-gray-800 font-semibold">
                        <i class="fas fa-arrow-left mr-2"></i> Volver
                    </a>
                    <div class="flex space-x-3">
                        <button type="reset" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-3 rounded-lg font-semibold transition">
                            <i class="fas fa-redo mr-2"></i> Limpiar
                        </button>
                        <button type="submit" class="bg-gradient-to-r from-blue-600 to-blue-800 hover:from-blue-700 hover:to-blue-900 text-white px-6 py-3 rounded-lg font-semibold shadow-lg transition">
                            <i class="fas fa-save mr-2"></i> Guardar Salida
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

        // Ocultar todas las secciones y deshabilitar sus campos
        $('.tipo-salida-fields').addClass('hidden');
        $('.tipo-salida-fields').find('input, select, textarea').prop('disabled', true);
        $('#seccion-retornos').addClass('hidden');

        // Mostrar y habilitar solo la seccion correspondiente
        if (tipo === 'Despacho Interno') {
            $('#campos-despacho-interno').removeClass('hidden');
            $('#campos-despacho-interno').find('input, select, textarea').prop('disabled', false);
            $('#seccion-retornos').removeClass('hidden');
        } else if (tipo === 'Pedido Cliente') {
            $('#campos-pedido-cliente').removeClass('hidden');
            $('#campos-pedido-cliente').find('input, select, textarea').prop('disabled', false);
        } else if (tipo === 'Venta Directa') {
            $('#campos-venta-directa').removeClass('hidden');
            $('#campos-venta-directa').find('input, select, textarea').prop('disabled', false);
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

    // Guardar todas las opciones de vehiculos originales
    var vehiculosOriginal = {};
    $('select[name="vehiculo_placa"]').each(function() {
        var selectId = $(this).attr('id');
        vehiculosOriginal[selectId] = $(this).find('option').clone();
    });

    // Funcion para filtrar vehiculos segun el chofer seleccionado
    function filtrarVehiculosPorChofer(selectChofer, selectVehiculo) {
        var choferSeleccionado = $(selectChofer).val();
        var vehiculoSelect = $(selectVehiculo);
        var selectId = vehiculoSelect.attr('id');

        // Restaurar todas las opciones originales
        vehiculoSelect.empty();
        vehiculosOriginal[selectId].each(function() {
            vehiculoSelect.append($(this).clone());
        });

        // Si hay un chofer seleccionado, filtrar
        if (choferSeleccionado) {
            vehiculoSelect.find('option').each(function() {
                var responsable = $(this).data('responsable');
                // Mantener la opcion vacia y las que coincidan con el chofer
                if ($(this).val() !== '' && responsable !== choferSeleccionado) {
                    $(this).remove();
                }
            });

            // Si solo queda la opcion vacia y hay vehiculos del chofer, seleccionar el primero
            var vehiculosDisponibles = vehiculoSelect.find('option[value!=""]');
            if (vehiculosDisponibles.length === 1) {
                vehiculosDisponibles.first().prop('selected', true);
            }
        }
    }

    // Eventos para Despacho Interno
    $('#chofer').on('change', function() {
        filtrarVehiculosPorChofer(this, '#vehiculo_placa');
    });

    // Eventos para Pedido Cliente
    $('#chofer_pedido').on('change', function() {
        filtrarVehiculosPorChofer(this, '#vehiculo_pedido');
    });
});
</script>
@endpush
