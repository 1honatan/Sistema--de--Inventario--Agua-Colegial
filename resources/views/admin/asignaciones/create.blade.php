@extends('layouts.app')

@section('title', 'Nueva Asignación')

@section('page-title', 'Nueva Asignación de Personal')
@section('page-subtitle', 'Asignar empleado a una tarea o módulo')

@section('content')
<div class="space-y-6">
    {{-- Breadcrumb --}}
    <div class="mb-4">
        <nav class="text-sm">
            <a href="{{ route('admin.dashboard') }}" class="text-blue-600 hover:text-blue-800">Dashboard</a>
            <span class="mx-2 text-gray-500">/</span>
            <a href="{{ route('admin.asignaciones.index') }}" class="text-blue-600 hover:text-blue-800">Asignaciones</a>
            <span class="mx-2 text-gray-500">/</span>
            <span class="text-gray-600">Nueva</span>
        </nav>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="{{ route('admin.asignaciones.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Empleado --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Empleado <span class="text-red-500">*</span>
                    </label>
                    <select name="id_personal" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500">
                        <option value="">Seleccionar empleado...</option>
                        @foreach($empleados as $empleado)
                            <option value="{{ $empleado->id }}" {{ old('id_personal') == $empleado->id ? 'selected' : '' }}>
                                {{ $empleado->nombre }} {{ $empleado->apellidos }} - {{ $empleado->cargo }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_personal')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Tipo de Asignación --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Tipo de Asignación <span class="text-red-500">*</span>
                    </label>
                    <select name="tipo_asignacion" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500">
                        <option value="">Seleccionar tipo...</option>
                        @foreach($tiposAsignacion as $key => $label)
                            <option value="{{ $key }}" {{ old('tipo_asignacion') == $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    @error('tipo_asignacion')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Fecha Inicio --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Fecha Inicio <span class="text-red-500">*</span>
                    </label>
                    <input type="date"
                           name="fecha_inicio"
                           value="{{ old('fecha_inicio', now()->format('Y-m-d')) }}"
                           required
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500">
                    @error('fecha_inicio')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Fecha Fin --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Fecha Fin (opcional)
                    </label>
                    <input type="date"
                           name="fecha_fin"
                           value="{{ old('fecha_fin') }}"
                           class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500">
                    <p class="text-sm text-gray-500 mt-1">Dejar vacío para asignación indefinida</p>
                    @error('fecha_fin')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Descripción --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Descripción <span class="text-red-500">*</span>
                    </label>
                    <textarea name="descripcion"
                              rows="3"
                              required
                              maxlength="500"
                              class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500"
                              placeholder="Descripción detallada de la asignación...">{{ old('descripcion') }}</textarea>
                    @error('descripcion')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Observaciones --}}
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Observaciones
                    </label>
                    <textarea name="observaciones"
                              rows="2"
                              class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500"
                              placeholder="Observaciones adicionales...">{{ old('observaciones') }}</textarea>
                    @error('observaciones')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Botones --}}
            <div class="mt-6 flex justify-end gap-3">
                <a href="{{ route('admin.asignaciones.index') }}" class="btn btn-secondary">
                    Cancelar
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save mr-2"></i>
                    Guardar Asignación
                </button>
            </div>
        </form>
    </div>

    {{-- Ayuda --}}
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
        <h4 class="font-semibold text-blue-900 mb-2">
            <i class="fas fa-info-circle mr-2"></i>
            Tipos de Asignación
        </h4>
        <ul class="list-disc list-inside text-sm text-blue-800 space-y-1">
            <li><strong>Chofer de Vehículo:</strong> Asignar chofer permanente a un vehículo</li>
            <li><strong>Responsable de Vehículo:</strong> Encargado del mantenimiento del vehículo</li>
            <li><strong>Técnico de Mantenimiento:</strong> Personal asignado a mantenimiento de equipos</li>
            <li><strong>Personal de Producción:</strong> Empleado asignado a producción diaria</li>
            <li><strong>Responsable de Fumigación:</strong> Encargado de fumigaciones periódicas</li>
            <li><strong>Limpieza de Tanques:</strong> Personal para limpieza de tanques de agua</li>
            <li><strong>Limpieza de Fosa Séptica:</strong> Responsable de mantenimiento de fosa</li>
            <li><strong>Control de Insumos:</strong> Encargado del control y registro de insumos</li>
            <li><strong>Supervisor General:</strong> Supervisor de área o procesos</li>
        </ul>
    </div>
</div>
@endsection
