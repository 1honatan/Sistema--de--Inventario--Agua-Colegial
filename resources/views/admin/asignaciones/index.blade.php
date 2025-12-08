@extends('layouts.app')

@section('title', 'Gestión de Asignaciones')

@section('page-title', 'Gestión de Asignaciones de Personal')
@section('page-subtitle', 'Control centralizado de asignaciones a tareas y módulos')

@section('content')
<div class="space-y-6">
    {{-- Breadcrumb --}}
    <div class="mb-4">
        <nav class="text-sm">
            <a href="{{ route('admin.dashboard') }}" class="text-blue-600 hover:text-blue-800">Dashboard</a>
            <span class="mx-2 text-gray-500">/</span>
            <span class="text-gray-600">Asignaciones</span>
        </nav>
    </div>

    {{-- Estadísticas --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-lg shadow-md p-4 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Activas</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($estadisticas['activas']) }}</p>
                </div>
                <div class="bg-green-100 rounded-full p-3">
                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-4 border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Suspendidas</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($estadisticas['suspendidas']) }}</p>
                </div>
                <div class="bg-yellow-100 rounded-full p-3">
                    <i class="fas fa-pause-circle text-yellow-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-4 border-l-4 border-gray-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Finalizadas</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($estadisticas['finalizadas']) }}</p>
                </div>
                <div class="bg-gray-100 rounded-full p-3">
                    <i class="fas fa-flag-checkered text-gray-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-4 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Total</p>
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($estadisticas['total']) }}</p>
                </div>
                <div class="bg-blue-100 rounded-full p-3">
                    <i class="fas fa-clipboard-list text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Acciones --}}
    <div class="flex justify-between items-center">
        <h3 class="text-lg font-semibold text-gray-800">Lista de Asignaciones</h3>
        <a href="{{ route('admin.asignaciones.create') }}" class="btn btn-primary flex items-center gap-2">
            <i class="fas fa-plus"></i>
            Nueva Asignación
        </a>
    </div>

    {{-- Tabla de Asignaciones --}}
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Empleado</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tipo Asignación</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Descripción</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Período</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($asignaciones as $asignacion)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $asignacion->personal->nombre }} {{ $asignacion->personal->apellidos }}
                                        </div>
                                        <div class="text-sm text-gray-500">{{ $asignacion->personal->cargo }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    {{ ucfirst(str_replace('_', ' ', $asignacion->tipo_asignacion)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">{{ \Str::limit($asignacion->descripcion, 50) }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $asignacion->fecha_inicio->format('d/m/Y') }}
                                @if($asignacion->fecha_fin)
                                    - {{ $asignacion->fecha_fin->format('d/m/Y') }}
                                @else
                                    - <span class="text-gray-400">Indefinida</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($asignacion->estado === 'activa')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Activa
                                    </span>
                                @elseif($asignacion->estado === 'suspendida')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        Suspendida
                                    </span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                        Finalizada
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('admin.asignaciones.show', $asignacion) }}" class="text-blue-600 hover:text-blue-900">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.asignaciones.edit', $asignacion) }}" class="text-green-600 hover:text-green-900">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    @if($asignacion->estado === 'activa')
                                        <form action="{{ route('admin.asignaciones.suspend', $asignacion) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="text-yellow-600 hover:text-yellow-900" title="Suspender">
                                                <i class="fas fa-pause"></i>
                                            </button>
                                        </form>

                                        <form action="{{ route('admin.asignaciones.finalize', $asignacion) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="text-gray-600 hover:text-gray-900" title="Finalizar">
                                                <i class="fas fa-flag-checkered"></i>
                                            </button>
                                        </form>
                                    @elseif($asignacion->estado === 'suspendida')
                                        <form action="{{ route('admin.asignaciones.reactivate', $asignacion) }}" method="POST" class="inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="text-green-600 hover:text-green-900" title="Reactivar">
                                                <i class="fas fa-play"></i>
                                            </button>
                                        </form>
                                    @endif

                                    <form action="{{ route('admin.asignaciones.destroy', $asignacion) }}" method="POST" class="inline"
                                          onsubmit="return confirm('¿Estás seguro de eliminar esta asignación?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                                <i class="fas fa-clipboard-list text-3xl mb-2"></i>
                                <p>No hay asignaciones registradas</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Paginación --}}
        @if($asignaciones->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $asignaciones->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
