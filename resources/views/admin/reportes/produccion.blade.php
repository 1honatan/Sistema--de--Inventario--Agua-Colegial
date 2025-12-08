@extends('layouts.app')

@section('title', 'Reporte de Producción')

@section('page-title', 'Reporte de Producción')
@section('page-subtitle', 'Análisis detallado de la producción')

@section('content')
<div class="space-y-6">
    {{-- Breadcrumb --}}
    <div class="mb-4">
        <nav class="text-sm">
            <a href="{{ route('admin.dashboard') }}" class="text-blue-600 hover:text-blue-800">Dashboard</a>
            <span class="mx-2 text-gray-500">/</span>
            <a href="{{ route('admin.reportes.index') }}" class="text-blue-600 hover:text-blue-800">Reportes</a>
            <span class="mx-2 text-gray-500">/</span>
            <span class="text-gray-600">Producción</span>
        </nav>
    </div>

    {{-- Filtros --}}
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
            <i class="fas fa-filter mr-2 text-blue-600"></i>
            Filtros de Búsqueda
        </h3>

        <form action="{{ route('admin.reportes.produccion') }}" method="GET" class="space-y-4" id="formFiltros">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                {{-- Fecha Inicio --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Fecha Inicio</label>
                    <input type="date"
                           name="fecha_inicio"
                           id="fecha_inicio"
                           value="{{ request('fecha_inicio', now()->startOfMonth()->format('Y-m-d')) }}"
                           required
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500">
                </div>

                {{-- Fecha Fin --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Fecha Fin</label>
                    <input type="date"
                           name="fecha_fin"
                           id="fecha_fin"
                           value="{{ request('fecha_fin', now()->format('Y-m-d')) }}"
                           required
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500">
                </div>

                {{-- Producto --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Producto</label>
                    <select name="id_producto" id="id_producto" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500">
                        <option value="">Todos los productos</option>
                        @foreach($productos ?? [] as $producto)
                            <option value="{{ $producto->id }}" {{ request('id_producto') == $producto->id ? 'selected' : '' }}>
                                {{ $producto->nombre }} - Stock: {{ $producto->stock_actual }} {{ $producto->unidad_medida }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Botón Buscar --}}
                <div class="flex items-end">
                    <button type="submit"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg font-semibold transition flex items-center justify-center gap-2">
                        <i class="fas fa-search"></i>
                        Buscar
                    </button>
                </div>
            </div>
        </form>
    </div>

    {{-- Estadísticas Resumen --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-lg shadow-md p-4 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Total Producido</p>
                    <p class="text-2xl font-bold text-gray-900">
                        {{ number_format($totalProducido ?? 0) }}
                    </p>
                </div>
                <div class="bg-blue-100 rounded-full p-3">
                    <i class="fas fa-boxes text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-4 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Lotes Creados</p>
                    <p class="text-2xl font-bold text-gray-900">
                        {{ number_format($totalLotes ?? 0) }}
                    </p>
                </div>
                <div class="bg-green-100 rounded-full p-3">
                    <i class="fas fa-layer-group text-green-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-4 border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Productos Distintos</p>
                    <p class="text-2xl font-bold text-gray-900">
                        {{ number_format($totalProductos ?? 0) }}
                    </p>
                </div>
                <div class="bg-purple-100 rounded-full p-3">
                    <i class="fas fa-box text-purple-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-4 border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Promedio Diario</p>
                    <p class="text-2xl font-bold text-gray-900">
                        {{ number_format($promedioDiario ?? 0) }}
                    </p>
                </div>
                <div class="bg-yellow-100 rounded-full p-3">
                    <i class="fas fa-chart-line text-yellow-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabla de Producción --}}
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="p-6 border-b border-gray-200 flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                <i class="fas fa-industry mr-2 text-blue-600"></i>
                Detalle de Producción Diaria
            </h3>
            <button type="button"
                    onclick="exportarPDF()"
                    class="btn btn-danger flex items-center gap-2">
                <i class="fas fa-file-pdf"></i>
                Exportar PDF
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full" id="tablaProduccion">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Responsable</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Productos</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Materiales</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Observaciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($producciones ?? [] as $produccion)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ \Carbon\Carbon::parse($produccion->fecha)->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $produccion->responsable ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                @if($produccion->productos->count() > 0)
                                    <ul class="list-disc list-inside">
                                        @foreach($produccion->productos as $prod)
                                            @if(empty(request('id_producto')) || $prod->producto_id == request('id_producto'))
                                                <li>
                                                    <strong>{{ $prod->producto->nombre ?? 'Producto' }}:</strong>
                                                    {{ number_format($prod->cantidad) }} unidades
                                                </li>
                                            @endif
                                        @endforeach
                                    </ul>
                                @else
                                    <span class="text-gray-400">Sin productos</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                @if($produccion->materiales->count() > 0)
                                    <ul class="list-disc list-inside">
                                        @foreach($produccion->materiales as $material)
                                            <li>
                                                {{ $material->nombre_material }}:
                                                {{ number_format($material->cantidad, 2) }} {{ $material->unidad_medida }}
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <span class="text-gray-400">Sin materiales</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ $produccion->observaciones ?? '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                <i class="fas fa-inbox text-3xl mb-2"></i>
                                <p>No hay registros de producción en el rango de fechas seleccionado</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function exportarPDF() {
        // Obtener los valores actuales de los inputs del formulario usando IDs
        const fechaInicio = document.getElementById('fecha_inicio').value;
        const fechaFin = document.getElementById('fecha_fin').value;
        const idProducto = document.getElementById('id_producto').value;

        // Validar que las fechas existan
        if (!fechaInicio || !fechaFin) {
            alert('Por favor selecciona las fechas de inicio y fin');
            return;
        }

        // Construir URL con los parámetros actuales
        let url = "{{ route('admin.reportes.produccion.pdf') }}";
        url += `?fecha_inicio=${fechaInicio}&fecha_fin=${fechaFin}`;

        if (idProducto) {
            url += `&id_producto=${idProducto}`;
        }

        // Mostrar URL en consola para debug
        console.log('URL generada para PDF:', url);

        // Abrir en nueva ventana
        window.open(url, '_blank');
    }

    $(document).ready(function() {
        @if(isset($producciones) && count($producciones) > 0)
        $('#tablaProduccion').DataTable({
            order: [[0, 'desc']],
            pageLength: 25
        });
        @endif
    });
</script>
@endpush
