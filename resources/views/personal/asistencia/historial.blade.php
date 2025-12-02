@extends('layouts.app')

@section('title', 'Historial de Asistencia')
@section('page-title', 'Historial de Asistencia')
@section('page-subtitle', 'Consulta tu historial completo de asistencias')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <!-- Botón volver -->
    <div>
        <a href="{{ route('personal.asistencia.index') }}" class="text-green-600 hover:text-green-700 font-semibold">
            <i class="fas fa-arrow-left mr-2"></i>
            Volver al Panel
        </a>
    </div>

    <!-- Filtros -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4">
            <i class="fas fa-filter text-green-600 mr-2"></i>
            Filtrar Asistencias
        </h2>

        <form action="{{ route('personal.asistencia.historial') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label for="fecha_inicio" class="block text-sm font-semibold text-gray-700 mb-2">
                    Fecha Inicio
                </label>
                <input
                    type="date"
                    name="fecha_inicio"
                    id="fecha_inicio"
                    value="{{ request('fecha_inicio', today()->subDays(90)->format('Y-m-d')) }}"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500"
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
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500"
                >
            </div>

            <div class="flex items-end">
                <button
                    type="submit"
                    class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg font-semibold transition w-full"
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
            <i class="fas fa-list text-green-600 mr-2"></i>
            Registros de Asistencia
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
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($asistencia->estado === 'presente')
                                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                            <i class="fas fa-check-circle mr-1"></i>Presente
                                        </span>
                                    @elseif($asistencia->estado === 'ausente')
                                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                            <i class="fas fa-user-times mr-1"></i>Ausente
                                        </span>
                                    @elseif($asistencia->estado === 'permiso')
                                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            <i class="fas fa-file-signature mr-1"></i>Permiso
                                        </span>
                                    @elseif($asistencia->estado === 'tardanza')
                                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-orange-100 text-orange-800">
                                            <i class="fas fa-clock mr-1"></i>Tardanza
                                        </span>
                                    @else
                                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                            {{ ucfirst($asistencia->estado) }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $asistencia->entrada_hora ? \Carbon\Carbon::parse($asistencia->entrada_hora)->format('H:i') : '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $asistencia->salida_hora ? \Carbon\Carbon::parse($asistencia->salida_hora)->format('H:i') : '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-semibold">
                                    @if($asistencia->horasTrabajadas())
                                        {{ number_format($asistencia->horasTrabajadas(), 2) }} hrs
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
                </table>
            </div>

            <!-- Paginación -->
            <div class="mt-6">
                {{ $asistencias->links() }}
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
