@extends('layouts.app')

@section('title', 'Reportes')
@section('page-title', 'Generación de Reportes')
@section('page-subtitle', 'Reportes de todos los módulos del sistema')

@push('styles')
<style>
    .report-card {
        transition: all 0.3s ease;
        cursor: pointer;
    }
    .report-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
    }
    .report-header {
        min-height: 150px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
</style>
@endpush

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-2">
            <i class="fas fa-chart-bar text-blue-600 mr-2"></i>
            Generación de Reportes
        </h2>
        <p class="text-sm text-gray-600">Seleccione el tipo de reporte, configure los filtros y genere documentos en PDF o Excel</p>
    </div>

    <!-- Reportes de Control -->
    <div>
        <h3 class="text-lg font-bold text-gray-800 mb-4">
            <i class="fas fa-clipboard-check text-cyan-600 mr-2"></i>
            Reportes de Control
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            <!-- Salidas de Productos -->
            <div class="report-card bg-white rounded-lg shadow-md overflow-hidden">
                <div class="report-header bg-gradient-to-r from-blue-600 to-blue-500 p-6 text-white">
                    <div class="flex items-center justify-between mb-3">
                        <i class="fas fa-truck-loading text-4xl opacity-80"></i>
                        <span class="bg-white bg-opacity-20 px-3 py-1 rounded-full text-xs font-semibold">PDF</span>
                    </div>
                    <h4 class="text-lg font-bold mb-1">Salidas de Productos</h4>
                    <p class="text-sm text-blue-100">Control de despachos</p>
                </div>
                <div class="p-4">
                    <form action="{{ route('admin.reportes.salidas') }}" method="GET" class="space-y-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Fecha Inicio</label>
                            <input type="date" name="fecha_inicio" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Fecha Fin</label>
                            <input type="date" name="fecha_fin" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500">
                        </div>
                        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg font-semibold transition">
                            <i class="fas fa-file-pdf mr-1"></i> Generar
                        </button>
                    </form>
                </div>
            </div>

            <!-- Producción Diaria -->
            <div class="report-card bg-white rounded-lg shadow-md overflow-hidden">
                <div class="report-header bg-gradient-to-r from-indigo-600 to-indigo-500 p-6 text-white">
                    <div class="flex items-center justify-between mb-3">
                        <i class="fas fa-industry text-4xl opacity-80"></i>
                        <span class="bg-white bg-opacity-20 px-3 py-1 rounded-full text-xs font-semibold">PDF</span>
                    </div>
                    <h4 class="text-lg font-bold mb-1">Producción Diaria</h4>
                    <p class="text-sm text-indigo-100">Control de producción</p>
                </div>
                <div class="p-4">
                    <form action="{{ route('admin.reportes.produccion') }}" method="GET" class="space-y-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Fecha Inicio</label>
                            <input type="date" name="fecha_inicio" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Fecha Fin</label>
                            <input type="date" name="fecha_fin" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500">
                        </div>
                        <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white py-2 rounded-lg font-semibold transition">
                            <i class="fas fa-file-pdf mr-1"></i> Generar
                        </button>
                    </form>
                </div>
            </div>

            <!-- Mantenimiento de Equipos -->
            <div class="report-card bg-white rounded-lg shadow-md overflow-hidden">
                <div class="report-header bg-gradient-to-r from-orange-600 to-orange-500 p-6 text-white">
                    <div class="flex items-center justify-between mb-3">
                        <i class="fas fa-tools text-4xl opacity-80"></i>
                        <span class="bg-white bg-opacity-20 px-3 py-1 rounded-full text-xs font-semibold">PDF</span>
                    </div>
                    <h4 class="text-lg font-bold mb-1">Mantenimiento</h4>
                    <p class="text-sm text-orange-100">Equipos y máquinas</p>
                </div>
                <div class="p-4">
                    <form action="{{ route('admin.reportes.mantenimiento') }}" method="GET" class="space-y-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Fecha Inicio</label>
                            <input type="date" name="fecha_inicio" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-orange-500">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Fecha Fin</label>
                            <input type="date" name="fecha_fin" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-orange-500">
                        </div>
                        <button type="submit" class="w-full bg-orange-600 hover:bg-orange-700 text-white py-2 rounded-lg font-semibold transition">
                            <i class="fas fa-file-pdf mr-1"></i> Generar
                        </button>
                    </form>
                </div>
            </div>

            <!-- Fumigación -->
            <div class="report-card bg-white rounded-lg shadow-md overflow-hidden">
                <div class="report-header bg-gradient-to-r from-green-600 to-green-500 p-6 text-white">
                    <div class="flex items-center justify-between mb-3">
                        <i class="fas fa-spray-can text-4xl opacity-80"></i>
                        <span class="bg-white bg-opacity-20 px-3 py-1 rounded-full text-xs font-semibold">PDF</span>
                    </div>
                    <h4 class="text-lg font-bold mb-1">Fumigación</h4>
                    <p class="text-sm text-green-100">Control de plagas</p>
                </div>
                <div class="p-4">
                    <form action="{{ route('admin.reportes.fumigacion') }}" method="GET" class="space-y-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Fecha Inicio</label>
                            <input type="date" name="fecha_inicio" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Fecha Fin</label>
                            <input type="date" name="fecha_fin" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-500">
                        </div>
                        <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white py-2 rounded-lg font-semibold transition">
                            <i class="fas fa-file-pdf mr-1"></i> Generar
                        </button>
                    </form>
                </div>
            </div>

            <!-- Fosa Séptica -->
            <div class="report-card bg-white rounded-lg shadow-md overflow-hidden">
                <div class="report-header bg-gradient-to-r from-teal-600 to-teal-500 p-6 text-white">
                    <div class="flex items-center justify-between mb-3">
                        <i class="fas fa-water text-4xl opacity-80"></i>
                        <span class="bg-white bg-opacity-20 px-3 py-1 rounded-full text-xs font-semibold">PDF</span>
                    </div>
                    <h4 class="text-lg font-bold mb-1">Fosa Séptica</h4>
                    <p class="text-sm text-teal-100">Control de limpieza</p>
                </div>
                <div class="p-4">
                    <form action="{{ route('admin.reportes.fosa-septica') }}" method="GET" class="space-y-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Fecha Inicio</label>
                            <input type="date" name="fecha_inicio" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-teal-500">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Fecha Fin</label>
                            <input type="date" name="fecha_fin" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-teal-500">
                        </div>
                        <button type="submit" class="w-full bg-teal-600 hover:bg-teal-700 text-white py-2 rounded-lg font-semibold transition">
                            <i class="fas fa-file-pdf mr-1"></i> Generar
                        </button>
                    </form>
                </div>
            </div>

            <!-- Tanques de Agua -->
            <div class="report-card bg-white rounded-lg shadow-md overflow-hidden">
                <div class="report-header bg-gradient-to-r from-cyan-600 to-cyan-500 p-6 text-white">
                    <div class="flex items-center justify-between mb-3">
                        <i class="fas fa-tint text-4xl opacity-80"></i>
                        <span class="bg-white bg-opacity-20 px-3 py-1 rounded-full text-xs font-semibold">PDF</span>
                    </div>
                    <h4 class="text-lg font-bold mb-1">Tanques de Agua</h4>
                    <p class="text-sm text-cyan-100">Control de limpieza</p>
                </div>
                <div class="p-4">
                    <form action="{{ route('admin.reportes.tanques') }}" method="GET" class="space-y-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Fecha Inicio</label>
                            <input type="date" name="fecha_inicio" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-cyan-500">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Fecha Fin</label>
                            <input type="date" name="fecha_fin" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-cyan-500">
                        </div>
                        <button type="submit" class="w-full bg-cyan-600 hover:bg-cyan-700 text-white py-2 rounded-lg font-semibold transition">
                            <i class="fas fa-file-pdf mr-1"></i> Generar
                        </button>
                    </form>
                </div>
            </div>

            <!-- Insumos -->
            <div class="report-card bg-white rounded-lg shadow-md overflow-hidden">
                <div class="report-header bg-gradient-to-r from-purple-600 to-purple-500 p-6 text-white">
                    <div class="flex items-center justify-between mb-3">
                        <i class="fas fa-boxes text-4xl opacity-80"></i>
                        <span class="bg-white bg-opacity-20 px-3 py-1 rounded-full text-xs font-semibold">PDF</span>
                    </div>
                    <h4 class="text-lg font-bold mb-1">Insumos</h4>
                    <p class="text-sm text-purple-100">Control de suministros</p>
                </div>
                <div class="p-4">
                    <form action="{{ route('admin.reportes.insumos') }}" method="GET" class="space-y-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Fecha Inicio</label>
                            <input type="date" name="fecha_inicio" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-purple-500">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Fecha Fin</label>
                            <input type="date" name="fecha_fin" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-purple-500">
                        </div>
                        <button type="submit" class="w-full bg-purple-600 hover:bg-purple-700 text-white py-2 rounded-lg font-semibold transition">
                            <i class="fas fa-file-pdf mr-1"></i> Generar
                        </button>
                    </form>
                </div>
            </div>

            <!-- Asistencia Semanal -->
            <div class="report-card bg-white rounded-lg shadow-md overflow-hidden">
                <div class="report-header bg-gradient-to-r from-pink-600 to-pink-500 p-6 text-white">
                    <div class="flex items-center justify-between mb-3">
                        <i class="fas fa-user-check text-4xl opacity-80"></i>
                        <span class="bg-white bg-opacity-20 px-3 py-1 rounded-full text-xs font-semibold">PDF</span>
                    </div>
                    <h4 class="text-lg font-bold mb-1">Asistencia</h4>
                    <p class="text-sm text-pink-100">Control de personal</p>
                </div>
                <div class="p-4">
                    <form action="{{ route('admin.reportes.asistencia') }}" method="GET" class="space-y-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Fecha Inicio</label>
                            <input type="date" name="fecha_inicio" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-pink-500">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 mb-1">Fecha Fin</label>
                            <input type="date" name="fecha_fin" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-pink-500">
                        </div>
                        <button type="submit" class="w-full bg-pink-600 hover:bg-pink-700 text-white py-2 rounded-lg font-semibold transition">
                            <i class="fas fa-file-pdf mr-1"></i> Generar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Reportes de Gestión -->
    <div>
        <h3 class="text-lg font-bold text-gray-800 mb-4">
            <i class="fas fa-chart-line text-green-600 mr-2"></i>
            Reportes de Gestión
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Inventario -->
            <div class="report-card bg-white rounded-lg shadow-md overflow-hidden">
                <div class="report-header bg-gradient-to-r from-emerald-600 to-emerald-500 p-6 text-white">
                    <div class="flex items-center justify-between mb-3">
                        <i class="fas fa-warehouse text-4xl opacity-80"></i>
                        <span class="bg-white bg-opacity-20 px-3 py-1 rounded-full text-xs font-semibold">PDF</span>
                    </div>
                    <h4 class="text-xl font-bold mb-2">Reporte de Inventario</h4>
                    <p class="text-sm text-emerald-100">Análisis de stock y movimientos</p>
                </div>
                <div class="p-6">
                    <form action="{{ route('admin.reportes.inventario') }}" method="GET" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Fecha Inicio</label>
                            <input type="date" name="fecha_inicio" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-emerald-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Fecha Fin</label>
                            <input type="date" name="fecha_fin" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-emerald-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tipo de Movimiento</label>
                            <select name="tipo_movimiento" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-emerald-500">
                                <option value="">Todos</option>
                                <option value="entrada">Entradas</option>
                                <option value="salida">Salidas</option>
                            </select>
                        </div>
                        <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white py-3 rounded-lg font-semibold transition">
                            <i class="fas fa-file-pdf mr-1"></i> Generar PDF
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Info Box -->
    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded">
        <div class="flex items-start">
            <i class="fas fa-info-circle text-blue-600 mt-1 mr-3"></i>
            <div class="text-sm text-blue-900">
                <p class="font-semibold mb-1">Notas Importantes:</p>
                <ul class="list-disc list-inside space-y-1 text-xs">
                    <li>Los reportes PDF se generan automáticamente y se descargan al equipo</li>
                    <li>Todos los reportes incluyen información detallada del período seleccionado</li>
                    <li>Las fechas por defecto muestran el mes actual</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Auto-set default dates
    document.querySelectorAll('input[type="date"]').forEach(input => {
        if (!input.value && input.name === 'fecha_fin') {
            input.value = new Date().toISOString().split('T')[0];
        }
        if (!input.value && input.name === 'fecha_inicio') {
            const firstDay = new Date();
            firstDay.setDate(1);
            input.value = firstDay.toISOString().split('T')[0];
        }
    });
</script>
@endpush
