@extends('layouts.app')

@section('title', 'Historial de Movimientos de Inventario')

@section('page-title', 'Historial de Movimientos')
@section('page-subtitle', 'Registro completo de entradas y salidas del inventario')

@section('content')
<div class="min-h-screen px-4 py-6" style="background-color: #c0eaff20;">
    {{-- Breadcrumb --}}
    <div class="mb-6">
        <nav class="text-sm" style="color: #333333;">
            <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-800" style="color: #1e3a8a;">
                <i class="fa-solid fa-home mr-1"></i>Dashboard
            </a>
            <span class="mx-2 text-gray-500">/</span>
            <a href="{{ route('inventario.dashboard') }}" class="hover:text-blue-800" style="color: #1e3a8a;">
                <i class="fa-solid fa-boxes mr-1"></i>Inventario
            </a>
            <span class="mx-2 text-gray-500">/</span>
            <span class="text-gray-600"><i class="fa-solid fa-history mr-1"></i>Historial de Movimientos</span>
        </nav>
    </div>

    {{-- Estadísticas Rápidas --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        {{-- Total Movimientos --}}
        <div class="bg-white rounded-lg shadow-md p-4 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Total Movimientos</p>
                    <p class="text-2xl font-bold" style="color: #1e3a8a;">{{ $movimientos->total() }}</p>
                </div>
                <div class="bg-blue-100 rounded-full p-3">
                    <i class="fa-solid fa-exchange-alt text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>

        {{-- Total Entradas --}}
        <div class="bg-white rounded-lg shadow-md p-4 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Total Entradas</p>
                    <p class="text-2xl font-bold text-green-600">
                        {{ $movimientos->where('tipo_movimiento', 'entrada')->count() }}
                    </p>
                </div>
                <div class="bg-green-100 rounded-full p-3">
                    <i class="fa-solid fa-arrow-down text-green-600 text-xl"></i>
                </div>
            </div>
        </div>

        {{-- Total Salidas --}}
        <div class="bg-white rounded-lg shadow-md p-4 border-l-4 border-red-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Total Salidas</p>
                    <p class="text-2xl font-bold text-red-600">
                        {{ $movimientos->where('tipo_movimiento', 'salida')->count() }}
                    </p>
                </div>
                <div class="bg-red-100 rounded-full p-3">
                    <i class="fa-solid fa-arrow-up text-red-600 text-xl"></i>
                </div>
            </div>
        </div>

        {{-- Productos Activos --}}
        <div class="bg-white rounded-lg shadow-md p-4 border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Productos Activos</p>
                    <p class="text-2xl font-bold text-purple-600">{{ $productos->count() }}</p>
                </div>
                <div class="bg-purple-100 rounded-full p-3">
                    <i class="fa-solid fa-box text-purple-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Panel de Filtros --}}
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold flex items-center" style="color: #1e3a8a;">
                <i class="fa-solid fa-filter mr-2"></i>
                Filtros Avanzados
            </h2>
            <button type="button" id="toggleFiltros" class="text-gray-600 hover:text-gray-900">
                <i class="fa-solid fa-chevron-down transition-transform" id="iconoFiltros"></i>
            </button>
        </div>

        <form action="{{ route('inventario.movimiento.historial') }}" method="GET" id="formFiltros">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                {{-- Fecha Desde --}}
                <div>
                    <label for="fecha_inicio" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fa-solid fa-calendar-alt mr-1"></i>Fecha Desde
                    </label>
                    <input type="text"
                           id="fecha_inicio"
                           name="fecha_inicio"
                           value="{{ $fechaInicio ?? '' }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="Seleccionar fecha"
                           readonly>
                </div>

                {{-- Fecha Hasta --}}
                <div>
                    <label for="fecha_fin" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fa-solid fa-calendar-alt mr-1"></i>Fecha Hasta
                    </label>
                    <input type="text"
                           id="fecha_fin"
                           name="fecha_fin"
                           value="{{ $fechaFin ?? '' }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="Seleccionar fecha"
                           readonly>
                </div>

                {{-- Tipo de Movimiento --}}
                <div>
                    <label for="tipo_movimiento" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fa-solid fa-exchange-alt mr-1"></i>Tipo de Movimiento
                    </label>
                    <select id="tipo_movimiento"
                            name="tipo_movimiento"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Todos</option>
                        <option value="entrada" {{ ($tipoMovimiento ?? '') == 'entrada' ? 'selected' : '' }}>
                            <i class="fa-solid fa-arrow-down"></i> Entrada
                        </option>
                        <option value="salida" {{ ($tipoMovimiento ?? '') == 'salida' ? 'selected' : '' }}>
                            <i class="fa-solid fa-arrow-up"></i> Salida
                        </option>
                    </select>
                </div>

                {{-- Producto --}}
                <div>
                    <label for="id_producto" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fa-solid fa-box mr-1"></i>Producto
                    </label>
                    <select id="id_producto"
                            name="id_producto"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Todos los productos</option>
                        @foreach($productos as $producto)
                            <option value="{{ $producto->id }}" {{ ($idProducto ?? '') == $producto->id ? 'selected' : '' }}>
                                {{ $producto->nombre }} - Stock: {{ $producto->stock_actual }} {{ $producto->unidad_medida }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Usuario Responsable --}}
                <div>
                    <label for="id_usuario" class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fa-solid fa-user mr-1"></i>Usuario Responsable
                    </label>
                    <select id="id_usuario"
                            name="id_usuario"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Todos los usuarios</option>
                        @foreach($usuarios as $usuario)
                            <option value="{{ $usuario->id }}" {{ ($idUsuario ?? '') == $usuario->id ? 'selected' : '' }}>
                                {{ $usuario->nombre }} - {{ $usuario->personal->nombre_completo ?? 'Sin personal' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Botones de Acción --}}
                <div class="flex items-end gap-2">
                    <button type="submit"
                            class="flex-1 px-4 py-2 text-white rounded-lg hover:shadow-lg transition flex items-center justify-center gap-2"
                            style="background-color: #1e3a8a;">
                        <i class="fa-solid fa-search"></i>
                        Filtrar
                    </button>
                    <a href="{{ route('inventario.movimiento.historial') }}"
                       class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition flex items-center justify-center"
                       style="color: #333333;">
                        <i class="fa-solid fa-times"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>

    {{-- Acciones Superiores --}}
    <div class="bg-white rounded-lg shadow-md p-4 mb-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-info-circle text-blue-600"></i>
                <span class="text-sm text-gray-600">
                    Mostrando {{ $movimientos->firstItem() ?? 0 }} - {{ $movimientos->lastItem() ?? 0 }}
                    de {{ $movimientos->total() }} movimientos
                </span>
            </div>

            <div class="flex flex-wrap gap-2">
                {{-- Exportar a PDF --}}
                <form action="{{ route('inventario.movimiento.exportar-pdf') }}" method="POST" class="inline">
                    @csrf
                    <input type="hidden" name="fecha_inicio" value="{{ $fechaInicio ?? '' }}">
                    <input type="hidden" name="fecha_fin" value="{{ $fechaFin ?? '' }}">
                    <input type="hidden" name="tipo_movimiento" value="{{ $tipoMovimiento ?? '' }}">
                    <input type="hidden" name="id_producto" value="{{ $idProducto ?? '' }}">
                    <input type="hidden" name="id_usuario" value="{{ $idUsuario ?? '' }}">
                    <button type="submit"
                            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition flex items-center gap-2 shadow-md hover:shadow-lg">
                        <i class="fa-solid fa-file-pdf"></i>
                        Exportar PDF
                    </button>
                </form>

                {{-- Exportar a Excel --}}
                <form action="{{ route('inventario.movimiento.exportar-excel') }}" method="POST" class="inline">
                    @csrf
                    <input type="hidden" name="fecha_inicio" value="{{ $fechaInicio ?? '' }}">
                    <input type="hidden" name="fecha_fin" value="{{ $fechaFin ?? '' }}">
                    <input type="hidden" name="tipo_movimiento" value="{{ $tipoMovimiento ?? '' }}">
                    <input type="hidden" name="id_producto" value="{{ $idProducto ?? '' }}">
                    <input type="hidden" name="id_usuario" value="{{ $idUsuario ?? '' }}">
                    <button type="submit"
                            class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition flex items-center gap-2 shadow-md hover:shadow-lg">
                        <i class="fa-solid fa-file-excel"></i>
                        Exportar Excel
                    </button>
                </form>

                {{-- Nuevo Movimiento --}}
                <a href="{{ route('inventario.movimiento.create') }}"
                   class="px-4 py-2 text-white rounded-lg hover:shadow-lg transition flex items-center gap-2"
                   style="background-color: #1e3a8a;">
                    <i class="fa-solid fa-plus"></i>
                    Nuevo Movimiento
                </a>
            </div>
        </div>
    </div>

    {{-- Tabla de Movimientos --}}
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead style="background-color: #1e3a8a;">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">
                            Fecha/Hora
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">
                            Producto
                        </th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-white uppercase tracking-wider">
                            Tipo
                        </th>
                        <th class="px-4 py-3 text-right text-xs font-medium text-white uppercase tracking-wider">
                            Cantidad
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">
                            Responsable
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">
                            Motivo/Detalle
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($movimientos as $movimiento)
                        <tr class="hover:bg-gray-50 transition">
                            {{-- Fecha y Hora --}}
                            <td class="px-4 py-3 whitespace-nowrap">
                                <div class="text-sm font-medium" style="color: #333333;">
                                    {{ \Carbon\Carbon::parse($movimiento->fecha)->format('d/m/Y') }}
                                </div>
                                <div class="text-xs text-gray-500">
                                    {{ \Carbon\Carbon::parse($movimiento->fecha)->format('H:i') }}
                                </div>
                            </td>

                            {{-- Producto --}}
                            <td class="px-4 py-3">
                                <div class="text-sm font-medium" style="color: #333333;">
                                    {{ $movimiento->producto }}
                                </div>
                                <div class="text-xs text-gray-500">
                                    <i class="fa-solid fa-tag"></i>
                                    {{ ucfirst($movimiento->origen) }}
                                </div>
                            </td>

                            {{-- Tipo de Movimiento --}}
                            <td class="px-4 py-3 text-center whitespace-nowrap">
                                @if($movimiento->tipo === 'entrada')
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        <i class="fa-solid fa-arrow-down mr-1"></i>
                                        Entrada
                                    </span>
                                @else
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                        <i class="fa-solid fa-arrow-up mr-1"></i>
                                        Salida
                                    </span>
                                @endif
                            </td>

                            {{-- Cantidad --}}
                            <td class="px-4 py-3 text-right whitespace-nowrap">
                                <span class="text-sm font-bold {{ $movimiento->tipo === 'entrada' ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $movimiento->tipo === 'entrada' ? '+' : '-' }}{{ number_format($movimiento->cantidad, 0) }}
                                </span>
                            </td>

                            {{-- Responsable --}}
                            <td class="px-4 py-3">
                                <div class="text-sm text-gray-700">
                                    <i class="fa-solid fa-user text-blue-500 mr-1"></i>
                                    {{ $movimiento->responsable }}
                                </div>
                            </td>

                            {{-- Motivo/Detalle --}}
                            <td class="px-4 py-3">
                                <div class="text-sm text-gray-600">
                                    {{ Str::limit($movimiento->motivo, 50) }}
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="fa-solid fa-inbox text-gray-300 text-6xl mb-4"></i>
                                    <p class="text-gray-500 text-lg mb-2">No hay movimientos registrados</p>
                                    <p class="text-gray-400 text-sm mb-4">
                                        Intenta ajustar los filtros o registra un nuevo movimiento
                                    </p>
                                    <a href="{{ route('inventario.movimiento.create') }}"
                                       class="px-6 py-2 text-white rounded-lg transition"
                                       style="background-color: #1e3a8a;">
                                        <i class="fa-solid fa-plus mr-2"></i>
                                        Registrar Movimiento
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Paginación --}}
        @if($movimientos->hasPages())
            <div class="bg-gray-50 px-4 py-3 border-t border-gray-200">
                {{ $movimientos->links() }}
            </div>
        @endif
    </div>
</div>

{{-- Estilos Personalizados --}}
<style>
    /* Fondo celeste claro */
    body {
        background-color: #c0eaff20;
    }

    /* Colores institucionales */
    .btn-azul-colegial {
        background-color: #1e3a8a;
        color: #ffffff;
    }

    .btn-azul-colegial:hover {
        background-color: #1e40af;
    }

    /* Animación para el icono de filtros */
    #iconoFiltros.rotate {
        transform: rotate(180deg);
    }

    /* Estilos para Flatpickr */
    .flatpickr-calendar {
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }

    /* Scroll suave */
    html {
        scroll-behavior: smooth;
    }

    /* Tooltip personalizado */
    [title] {
        position: relative;
    }
</style>

{{-- Scripts --}}
@push('scripts')
<!-- Flatpickr para fechas -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inicializar Flatpickr para fechas
    flatpickr('#fecha_inicio', {
        locale: 'es',
        dateFormat: 'Y-m-d',
        maxDate: 'today',
        allowInput: true,
        onChange: function(selectedDates, dateStr, instance) {
            // Actualizar fecha mínima del campo "fecha_fin"
            const fechaFinPicker = document.querySelector('#fecha_fin')._flatpickr;
            if (fechaFinPicker) {
                fechaFinPicker.set('minDate', dateStr);
            }
        }
    });

    flatpickr('#fecha_fin', {
        locale: 'es',
        dateFormat: 'Y-m-d',
        maxDate: 'today',
        allowInput: true
    });

    // Toggle de filtros
    const toggleFiltros = document.getElementById('toggleFiltros');
    const formFiltros = document.getElementById('formFiltros');
    const iconoFiltros = document.getElementById('iconoFiltros');

    toggleFiltros.addEventListener('click', function() {
        formFiltros.classList.toggle('hidden');
        iconoFiltros.classList.toggle('rotate');
    });

    // Confirmación para exportar con muchos registros
    const totalMovimientos = {{ $movimientos->total() }};

    document.querySelectorAll('form[action*="exportar"]').forEach(form => {
        form.addEventListener('submit', function(e) {
            if (totalMovimientos > 1000) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: '¿Exportar muchos registros?',
                    html: `
                        <p class="text-gray-700 mb-3">
                            Estás a punto de exportar <strong>${totalMovimientos}</strong> movimientos.
                        </p>
                        <p class="text-gray-600 text-sm">
                            Esto puede tomar varios segundos. ¿Deseas continuar?
                        </p>
                    `,
                    showCancelButton: true,
                    confirmButtonText: 'Sí, exportar',
                    cancelButtonText: 'Cancelar',
                    confirmButtonColor: '#1e3a8a',
                    cancelButtonColor: '#6b7280',
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                        Swal.fire({
                            icon: 'info',
                            title: 'Generando archivo...',
                            text: 'Por favor espera, esto puede tomar unos momentos.',
                            allowOutsideClick: false,
                            showConfirmButton: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                    }
                });
            }
        });
    });

    // Mensajes de éxito/error con SweetAlert2
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: '¡Éxito!',
            text: '{{ session('success') }}',
            confirmButtonText: 'Aceptar',
            confirmButtonColor: '#1e3a8a',
            timer: 3000,
            timerProgressBar: true,
            showClass: {
                popup: 'animate__animated animate__fadeInDown'
            },
            hideClass: {
                popup: 'animate__animated animate__fadeOutUp'
            }
        });
    @endif

    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: '{{ session('error') }}',
            confirmButtonText: 'Aceptar',
            confirmButtonColor: '#1e3a8a',
            showClass: {
                popup: 'animate__animated animate__shakeX'
            }
        });
    @endif

    // Auto-submit del formulario cuando cambien los filtros (opcional)
    // Descomenta si quieres filtrado automático
    /*
    document.querySelectorAll('#formFiltros select').forEach(select => {
        select.addEventListener('change', function() {
            document.getElementById('formFiltros').submit();
        });
    });
    */
});
</script>
@endpush
@endsection
