@extends('layouts.app')

@section('title', 'Asistencias de ' . $personal->nombre_completo)
@section('page-title', 'Asistencias de ' . $personal->nombre_completo)
@section('page-subtitle', $personal->cargo . ' - ' . $personal->area)

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <!-- Botón volver -->
    <div>
        <a href="{{ route('admin.asistencia.index') }}" class="text-yellow-600 hover:text-yellow-700 font-semibold">
            <i class="fas fa-arrow-left mr-2"></i>
            Volver al Listado
        </a>
    </div>

    <!-- Estadísticas del personal -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
        <!-- Total días -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Total Días</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $estadisticas['total_dias'] }}</p>
                </div>
                <i class="fas fa-calendar-check text-4xl text-blue-500"></i>
            </div>
        </div>

        <!-- Entradas -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Entradas</p>
                    <p class="text-3xl font-bold text-green-600">{{ $estadisticas['dias_entrada'] }}</p>
                </div>
                <i class="fas fa-sign-in-alt text-4xl text-green-500"></i>
            </div>
        </div>

        <!-- Salidas -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Salidas</p>
                    <p class="text-3xl font-bold text-blue-600">{{ $estadisticas['dias_salida'] }}</p>
                </div>
                <i class="fas fa-sign-out-alt text-4xl text-blue-500"></i>
            </div>
        </div>

        <!-- Ausencias -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Ausencias</p>
                    <p class="text-3xl font-bold text-red-600">{{ $estadisticas['dias_ausente'] }}</p>
                </div>
                <i class="fas fa-user-times text-4xl text-red-500"></i>
            </div>
        </div>

        <!-- Horas trabajadas -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Horas Trabajadas</p>
                    <p class="text-3xl font-bold text-purple-600">{{ number_format($estadisticas['horas_trabajadas'], 1) }}</p>
                </div>
                <i class="fas fa-clock text-4xl text-purple-500"></i>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4">
            <i class="fas fa-filter text-yellow-600 mr-2"></i>
            Filtrar por Rango de Fechas
        </h2>

        <form action="{{ route('admin.asistencia.ver-personal', $personal->id) }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label for="fecha_inicio" class="block text-sm font-semibold text-gray-700 mb-2">
                    Fecha Inicio
                </label>
                <input
                    type="date"
                    name="fecha_inicio"
                    id="fecha_inicio"
                    value="{{ request('fecha_inicio', today()->startOfMonth()->format('Y-m-d')) }}"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-yellow-500"
                >
            </div>

            <div>
                <label for="fecha_fin" class="block text-sm font-semibold text-gray-700 mb-2">
                    Fecha Fin
                </label>
                <input
                    type="date"
                    name="fecha_fin"
                    id="fecha_fin"
                    value="{{ request('fecha_fin', today()->format('Y-m-d')) }}"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-yellow-500"
                >
            </div>

            <div class="flex items-end">
                <button
                    type="submit"
                    class="bg-yellow-600 hover:bg-yellow-700 text-white px-6 py-2 rounded-lg font-semibold transition w-full"
                >
                    <i class="fas fa-search mr-2"></i>
                    Buscar
                </button>
            </div>
        </form>
    </div>

    <!-- Tabla de asistencias -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4">
            <i class="fas fa-list text-yellow-600 mr-2"></i>
            Historial de Asistencias
        </h2>

        @if($asistencias->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Fecha</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Estado</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Hora Entrada</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Hora Salida</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Horas Trabajadas</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Observaciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($asistencias as $asistencia)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                                    {{ $asistencia->fecha->format('d/m/Y') }}
                                    <span class="text-xs text-gray-500 block">{{ $asistencia->fecha->translatedFormat('l') }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($asistencia->estado === 'entrada')
                                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                            <i class="fas fa-sign-in-alt mr-1"></i>Entrada
                                        </span>
                                    @elseif($asistencia->estado === 'salida')
                                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                            <i class="fas fa-sign-out-alt mr-1"></i>Salida
                                        </span>
                                    @else
                                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                            <i class="fas fa-user-times mr-1"></i>Ausente
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $asistencia->hora_entrada ? \Carbon\Carbon::parse($asistencia->hora_entrada)->format('H:i') : '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $asistencia->hora_salida ? \Carbon\Carbon::parse($asistencia->hora_salida)->format('H:i') : '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                                    @if($asistencia->horasTrabajadas())
                                        <span class="text-purple-600">{{ number_format($asistencia->horasTrabajadas(), 2) }} hrs</span>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    {{ $asistencia->observaciones ?? '-' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-50">
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-right text-sm font-bold text-gray-900">
                                Total Horas Trabajadas:
                            </td>
                            <td class="px-6 py-4 text-sm font-bold text-purple-600">
                                {{ number_format($estadisticas['horas_trabajadas'], 2) }} hrs
                            </td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- Paginación -->
            <div class="mt-6">
                {{ $asistencias->appends(request()->query())->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <i class="fas fa-clipboard-list text-6xl text-gray-300 mb-4"></i>
                <p class="text-gray-500 text-lg">No se encontraron registros de asistencia para el período seleccionado.</p>
            </div>
        @endif
    </div>
</div>
@endsection
