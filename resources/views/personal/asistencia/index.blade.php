@extends('layouts.app')

@section('title', 'Mi Asistencia')
@section('page-title', 'Mi Historial de Asistencia')
@section('page-subtitle', 'Asistencias registradas por el administrador')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <!-- Alertas -->
    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded">
            <div class="flex items-center">
                <i class="fas fa-check-circle text-green-600 mr-3"></i>
                <p class="text-green-900 font-semibold">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle text-red-600 mr-3"></i>
                <p class="text-red-900 font-semibold">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    @if(session('warning'))
        <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded">
            <div class="flex items-center">
                <i class="fas fa-exclamation-triangle text-yellow-600 mr-3"></i>
                <p class="text-yellow-900 font-semibold">{{ session('warning') }}</p>
            </div>
        </div>
    @endif

    <!-- Estadísticas del mes -->
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

    <!-- Información para el personal -->
    <div class="bg-blue-50 border-l-4 border-blue-500 p-6 rounded-lg">
        <div class="flex items-start">
            <i class="fas fa-info-circle text-blue-600 text-2xl mr-4 mt-1"></i>
            <div>
                <h3 class="text-lg font-bold text-blue-900 mb-2">Información Importante</h3>
                <p class="text-blue-800">
                    Su asistencia es registrada únicamente por el administrador del sistema.
                    Aquí puede consultar su historial de asistencias, entradas, salidas y ausencias.
                </p>
                <p class="text-blue-800 mt-2">
                    Para cualquier corrección o consulta sobre su asistencia, por favor contacte al administrador.
                </p>
            </div>
        </div>
    </div>

    <!-- Historial reciente -->
    <div class="bg-white rounded-lg shadow-md p-8">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-800">
                <i class="fas fa-history text-green-600 mr-2"></i>
                Historial Reciente (Últimos 30 días)
            </h2>
            <a href="{{ route('personal.asistencia.historial') }}" class="text-green-600 hover:text-green-700 font-semibold">
                Ver Todo <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>

        @if($historial->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Fecha</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Estado</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Entrada</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Salida</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Horas</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Observaciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($historial as $asistencia)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
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
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $asistencia->horasTrabajadas() ? number_format($asistencia->horasTrabajadas(), 2) : '-' }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    {{ $asistencia->observaciones ?? '-' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-12">
                <i class="fas fa-clipboard-list text-6xl text-gray-300 mb-4"></i>
                <p class="text-gray-500">No hay registros de asistencia en los últimos 30 días.</p>
            </div>
        @endif
    </div>
</div>
@endsection
