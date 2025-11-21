@extends('layouts.app')

@section('title', 'Mi Asistencia')
@section('page-title', 'Control de Asistencia')
@section('page-subtitle', 'Registra tu entrada, salida o ausencia')

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

    <!-- Estado de hoy -->
    <div class="bg-white rounded-lg shadow-md p-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">
            <i class="fas fa-calendar-day text-green-600 mr-2"></i>
            Asistencia de Hoy - {{ today()->format('d/m/Y') }}
        </h2>

        @if($asistenciaHoy)
            <!-- Ya registró asistencia -->
            <div class="bg-green-50 border-l-4 border-green-500 p-6 rounded mb-6">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">Estado</p>
                        <p class="text-lg font-bold text-green-700 uppercase">{{ $asistenciaHoy->estado }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Hora de Entrada</p>
                        <p class="text-lg font-bold text-gray-800">
                            {{ $asistenciaHoy->hora_entrada ? \Carbon\Carbon::parse($asistenciaHoy->hora_entrada)->format('H:i') : 'N/A' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Hora de Salida</p>
                        <p class="text-lg font-bold text-gray-800">
                            {{ $asistenciaHoy->hora_salida ? \Carbon\Carbon::parse($asistenciaHoy->hora_salida)->format('H:i') : 'Pendiente' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Horas Trabajadas</p>
                        <p class="text-lg font-bold text-gray-800">
                            {{ $asistenciaHoy->horasTrabajadas() ? number_format($asistenciaHoy->horasTrabajadas(), 2) . ' hrs' : 'N/A' }}
                        </p>
                    </div>
                </div>
                @if($asistenciaHoy->observaciones)
                    <div class="mt-4">
                        <p class="text-sm text-gray-600">Observaciones</p>
                        <p class="text-gray-800">{{ $asistenciaHoy->observaciones }}</p>
                    </div>
                @endif
            </div>

            @if($asistenciaHoy->estado === 'entrada' && !$asistenciaHoy->hora_salida)
                <!-- Puede registrar salida -->
                <form action="{{ route('personal.asistencia.salida') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label for="observaciones" class="block text-sm font-semibold text-gray-700 mb-2">
                            Observaciones (Opcional)
                        </label>
                        <textarea
                            name="observaciones"
                            id="observaciones"
                            rows="3"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500"
                            placeholder="Ingrese cualquier observación sobre su salida..."
                        ></textarea>
                    </div>
                    <button
                        type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg font-semibold transition shadow-md hover:shadow-lg"
                    >
                        <i class="fas fa-sign-out-alt mr-2"></i>
                        Registrar Salida
                    </button>
                </form>
            @endif
        @else
            <!-- No ha registrado asistencia hoy -->
            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded mb-6">
                <p class="text-blue-900">
                    <i class="fas fa-info-circle mr-2"></i>
                    No has registrado tu asistencia el día de hoy. Por favor, registra tu entrada, salida o ausencia.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Registrar Entrada -->
                <form action="{{ route('personal.asistencia.entrada') }}" method="POST" class="space-y-4">
                    @csrf
                    <div class="bg-green-50 border border-green-200 rounded-lg p-6">
                        <h3 class="text-xl font-bold text-green-800 mb-4">
                            <i class="fas fa-sign-in-alt mr-2"></i>
                            Registrar Entrada
                        </h3>
                        <div>
                            <label for="observaciones_entrada" class="block text-sm font-semibold text-gray-700 mb-2">
                                Observaciones (Opcional)
                            </label>
                            <textarea
                                name="observaciones"
                                id="observaciones_entrada"
                                rows="3"
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-green-500"
                                placeholder="Ej: Llegué temprano para preparar..."
                            ></textarea>
                        </div>
                        <button
                            type="submit"
                            class="w-full bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-semibold transition shadow-md hover:shadow-lg mt-4"
                        >
                            <i class="fas fa-check mr-2"></i>
                            Registrar Entrada
                        </button>
                    </div>
                </form>

                <!-- Registrar Ausencia -->
                <form action="{{ route('personal.asistencia.ausencia') }}" method="POST" class="space-y-4">
                    @csrf
                    <div class="bg-red-50 border border-red-200 rounded-lg p-6">
                        <h3 class="text-xl font-bold text-red-800 mb-4">
                            <i class="fas fa-user-times mr-2"></i>
                            Registrar Ausencia
                        </h3>
                        <div>
                            <label for="observaciones_ausencia" class="block text-sm font-semibold text-gray-700 mb-2">
                                Motivo de Ausencia <span class="text-red-500">*</span>
                            </label>
                            <textarea
                                name="observaciones"
                                id="observaciones_ausencia"
                                rows="3"
                                required
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-red-500"
                                placeholder="Ej: Permiso médico, emergencia familiar..."
                            ></textarea>
                        </div>
                        <button
                            type="submit"
                            class="w-full bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-lg font-semibold transition shadow-md hover:shadow-lg mt-4"
                            onclick="return confirm('¿Está seguro de registrar su ausencia?')"
                        >
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            Registrar Ausencia
                        </button>
                    </div>
                </form>
            </div>
        @endif
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
