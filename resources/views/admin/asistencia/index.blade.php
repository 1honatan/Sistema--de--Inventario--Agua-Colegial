@extends('layouts.app')

@section('title', 'Asistencia Personal')
@section('page-title', 'Asistencia del Personal')
@section('page-subtitle', 'Visualización y seguimiento de asistencias de todo el personal')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <!-- Estadísticas del día -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
        <!-- Total Personal -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Total Personal</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $estadisticas['total_personal'] }}</p>
                </div>
                <i class="fas fa-users text-4xl text-gray-500"></i>
            </div>
        </div>

        <!-- Registrados -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Registrados</p>
                    <p class="text-3xl font-bold text-blue-600">{{ $estadisticas['total_registrados'] }}</p>
                </div>
                <i class="fas fa-clipboard-check text-4xl text-blue-500"></i>
            </div>
        </div>

        <!-- Presentes -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Presentes</p>
                    <p class="text-3xl font-bold text-green-600">{{ $estadisticas['presentes'] }}</p>
                </div>
                <i class="fas fa-user-check text-4xl text-green-500"></i>
            </div>
        </div>

        <!-- Sin Registro -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Sin Registro</p>
                    <p class="text-3xl font-bold text-red-600">{{ $estadisticas['ausentes_sin_registro'] }}</p>
                </div>
                <i class="fas fa-exclamation-triangle text-4xl text-red-500"></i>
            </div>
        </div>

        <!-- Ausentes Justificados -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Ausentes Justificados</p>
                    <p class="text-3xl font-bold text-orange-600">{{ $estadisticas['ausentes_justificados'] }}</p>
                </div>
                <i class="fas fa-user-times text-4xl text-orange-500"></i>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4">
            <i class="fas fa-filter text-yellow-600 mr-2"></i>
            Filtrar Asistencias
        </h2>

        <form action="{{ route('admin.asistencia.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label for="fecha" class="block text-sm font-semibold text-gray-700 mb-2">
                    Fecha
                </label>
                <input
                    type="date"
                    name="fecha"
                    id="fecha"
                    value="{{ request('fecha', today()->format('Y-m-d')) }}"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-yellow-500"
                >
            </div>

            <div>
                <label for="id_personal" class="block text-sm font-semibold text-gray-700 mb-2">
                    Personal
                </label>
                <select
                    name="id_personal"
                    id="id_personal"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-yellow-500"
                >
                    <option value="">Todos</option>
                    @foreach($personalActivo as $persona)
                        <option value="{{ $persona->id }}" {{ request('id_personal') == $persona->id ? 'selected' : '' }}>
                            {{ $persona->nombre_completo }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="estado" class="block text-sm font-semibold text-gray-700 mb-2">
                    Estado
                </label>
                <select
                    name="estado"
                    id="estado"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-yellow-500"
                >
                    <option value="">Todos</option>
                    <option value="entrada" {{ request('estado') == 'entrada' ? 'selected' : '' }}>Entrada</option>
                    <option value="salida" {{ request('estado') == 'salida' ? 'selected' : '' }}>Salida</option>
                    <option value="ausente" {{ request('estado') == 'ausente' ? 'selected' : '' }}>Ausente</option>
                </select>
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
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-bold text-gray-800">
                <i class="fas fa-list text-yellow-600 mr-2"></i>
                Registros de Asistencia
            </h2>
            <a
                href="{{ route('admin.personal.index') }}"
                class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-semibold transition"
            >
                <i class="fas fa-users mr-2"></i>
                Ver Personal
            </a>
        </div>

        @if($listaCompleta->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Personal</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Fecha</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Estado</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Entrada</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Salida</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Horas</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Observaciones</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($listaCompleta as $asistencia)
                            <tr class="hover:bg-gray-50 {{ isset($asistencia->es_ausente_sin_registro) && $asistencia->es_ausente_sin_registro ? 'bg-red-50' : '' }}">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div class="h-10 w-10 rounded-full {{ isset($asistencia->es_ausente_sin_registro) && $asistencia->es_ausente_sin_registro ? 'bg-red-500' : 'bg-yellow-500' }} flex items-center justify-center text-white font-bold">
                                                {{ substr($asistencia->personal->nombre_completo, 0, 1) }}
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-semibold text-gray-900 flex items-center">
                                                {{ $asistencia->personal->nombre_completo }}
                                                @if(isset($asistencia->es_ausente_sin_registro) && $asistencia->es_ausente_sin_registro)
                                                    <i class="fas fa-exclamation-circle text-red-600 ml-2" title="Sin registro de asistencia"></i>
                                                @endif
                                            </div>
                                            <div class="text-xs text-gray-500">
                                                {{ $asistencia->personal->cargo }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ \Carbon\Carbon::parse($asistencia->fecha)->format('d/m/Y') }}
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
                                    @elseif(isset($asistencia->es_ausente_sin_registro) && $asistencia->es_ausente_sin_registro)
                                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                            <i class="fas fa-exclamation-triangle mr-1"></i>Sin Registro
                                        </span>
                                    @else
                                        <span class="px-3 py-1 text-xs font-semibold rounded-full bg-orange-100 text-orange-800">
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
                                        {{ number_format($asistencia->horasTrabajadas(), 2) }} hrs
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm {{ isset($asistencia->es_ausente_sin_registro) && $asistencia->es_ausente_sin_registro ? 'text-red-600 font-semibold' : 'text-gray-600' }}">
                                    {{ Str::limit($asistencia->observaciones ?? '-', 30) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    @if(!isset($asistencia->es_ausente_sin_registro) || !$asistencia->es_ausente_sin_registro)
                                        <a
                                            href="{{ route('admin.asistencia.ver-personal', $asistencia->personal->id) }}"
                                            class="text-yellow-600 hover:text-yellow-700 font-semibold"
                                            title="Ver asistencias de {{ $asistencia->personal->nombre_completo }}"
                                        >
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    @else
                                        <a
                                            href="{{ route('admin.asistencia.ver-personal', $asistencia->personal->id) }}"
                                            class="text-gray-400 hover:text-gray-500"
                                            title="Ver asistencias de {{ $asistencia->personal->nombre_completo }}"
                                        >
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-12">
                <i class="fas fa-clipboard-list text-6xl text-gray-300 mb-4"></i>
                <p class="text-gray-500 text-lg">No se encontraron registros de personal activo.</p>
            </div>
        @endif
    </div>
</div>
@endsection
