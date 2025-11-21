@extends('layouts.app')

@section('title', 'Usuarios')
@section('page-title', 'Gestión de Usuarios')
@section('page-subtitle', 'Administración de usuarios del sistema')

@section('content')
<div class="space-y-6">
    <!-- Header Actions -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-gray-800">Usuarios del Sistema</h2>
            <p class="text-sm text-gray-600 mt-1">Total: {{ $usuarios->total() }} usuarios</p>
        </div>

        <a href="{{ route('admin.usuarios.create') }}" class="bg-blue-900 hover:bg-blue-800 text-white px-6 py-3 rounded-lg font-semibold transition shadow-md hover:shadow-lg">
            <i class="fas fa-user-plus mr-2"></i>
            Nuevo Usuario
        </a>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <form method="GET" action="{{ route('admin.usuarios.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label for="rol" class="block text-sm font-medium text-gray-700 mb-2">Rol</label>
                <select name="rol" id="rol" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500">
                    <option value="">Todos los roles</option>
                    @foreach($roles ?? [] as $rol)
                        <option value="{{ $rol->id }}" {{ request('rol') == $rol->id ? 'selected' : '' }}>
                            {{ ucfirst($rol->nombre) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="estado" class="block text-sm font-medium text-gray-700 mb-2">Estado</label>
                <select name="estado" id="estado" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500">
                    <option value="">Todos</option>
                    <option value="activo" {{ request('estado') == 'activo' ? 'selected' : '' }}>Activo</option>
                    <option value="inactivo" {{ request('estado') == 'inactivo' ? 'selected' : '' }}>Inactivo</option>
                </select>
            </div>

            <div>
                <label for="buscar" class="block text-sm font-medium text-gray-700 mb-2">Buscar</label>
                <input type="text" name="buscar" id="buscar" value="{{ request('buscar') }}" placeholder="Nombre o email..." class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="flex items-end space-x-2">
                <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-semibold transition">
                    <i class="fas fa-search mr-1"></i>
                    Filtrar
                </button>
                <a href="{{ route('admin.usuarios.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-lg font-semibold transition">
                    <i class="fas fa-times"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Activos</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $usuariosActivos ?? 0 }}</p>
                </div>
                <i class="fas fa-check-circle text-green-500 text-3xl"></i>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Administradores</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $admins ?? 0 }}</p>
                </div>
                <i class="fas fa-user-shield text-blue-500 text-3xl"></i>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Operadores</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $operadores ?? 0 }}</p>
                </div>
                <i class="fas fa-users text-purple-500 text-3xl"></i>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-red-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Inactivos</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $usuariosInactivos ?? 0 }}</p>
                </div>
                <i class="fas fa-ban text-red-500 text-3xl"></i>
            </div>
        </div>
    </div>

    <!-- Users Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Usuario</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Rol</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Estado</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Último Acceso</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($usuarios as $usuario)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 rounded-full bg-blue-900 flex items-center justify-center text-white font-bold mr-3">
                                        {{ strtoupper(substr($usuario->nombre, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-900">{{ $usuario->nombre }}</p>
                                        <p class="text-xs text-gray-500">ID: {{ $usuario->id }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm text-gray-600">{{ $usuario->email }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $rolNombre = $usuario->rol->nombre ?? 'N/A';
                                    $rolColor = match($rolNombre) {
                                        'admin' => 'blue',
                                        'produccion' => 'green',
                                        'inventario' => 'yellow',
                                        'despacho' => 'purple',
                                        default => 'gray'
                                    };
                                @endphp
                                <span class="bg-{{ $rolColor }}-100 text-{{ $rolColor }}-800 px-3 py-1 rounded-full text-xs font-semibold">
                                    <i class="fas fa-tag mr-1"></i>
                                    {{ ucfirst($rolNombre) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($usuario->estado === 'activo')
                                    <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-semibold">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        Activo
                                    </span>
                                @else
                                    <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-xs font-semibold">
                                        <i class="fas fa-ban mr-1"></i>
                                        Inactivo
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ $usuario->ultimo_acceso ? $usuario->ultimo_acceso->diffForHumans() : 'Nunca' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <div class="flex space-x-3">
                                    <a href="{{ route('admin.usuarios.edit', $usuario) }}" class="text-blue-600 hover:text-blue-800 font-semibold">
                                        <i class="fas fa-edit mr-1"></i>
                                        Editar
                                    </a>

                                    @if($usuario->id !== auth()->id())
                                        <form action="{{ route('admin.usuarios.destroy', $usuario) }}" method="POST" class="inline" onsubmit="return confirm('¿Está seguro de eliminar este usuario?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800 font-semibold">
                                                <i class="fas fa-trash mr-1"></i>
                                                Eliminar
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center text-gray-500">
                                    <i class="fas fa-users text-5xl mb-3"></i>
                                    <p class="text-lg font-semibold">No hay usuarios registrados</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($usuarios->hasPages())
            <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                {{ $usuarios->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
