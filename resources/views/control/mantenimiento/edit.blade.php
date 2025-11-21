@extends('layouts.app')
@section('title', 'Editar Mantenimiento')
@section('content')
<div class="container-fluid">
    <div class="card shadow-sm">
        <div class="card-header bg-gradient-to-r from-blue-900 to-blue-800 text-white">
            <h3 class="text-xl font-bold">Editar Registro #{{ $mantenimiento->id }}</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('control.mantenimiento.update', $mantenimiento) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div class="form-group">
                        <label class="form-label font-bold">Fecha *</label>
                        <input type="date" name="fecha" class="form-control" value="{{ old('fecha', $mantenimiento->fecha->format('Y-m-d')) }}" required>
                    </div>
                    <div class="form-group mb-4">
                        <label class="form-label font-bold">Equipos a Mantener *</label>
                        <div class="row">
                            @php
                            $equipos = [
                                'Máquina de Agua Natural',
                                'Máquina de Limón y Sabor',
                                'Máquina de Bolos',
                                'Máquina de Hielo',
                                'Turiles Grandes (Gelatina y Bolos)',
                                'Turiles Medianos (Gelatina y Bolos)',
                                'Máquina Limpiadora de Botellones 20L',
                                'Otro'
                            ];
                            $equiposSeleccionados = old('equipo', $mantenimiento->equipo ?? []);
                            @endphp
                            @foreach($equipos as $eq)
                                <div class="col-md-6 mb-3">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox"
                                               class="custom-control-input"
                                               name="equipo[]"
                                               id="equipo_{{ $loop->index }}"
                                               value="{{ $eq }}"
                                               {{ in_array($eq, $equiposSeleccionados) ? 'checked' : '' }}>
                                        <label class="custom-control-label" for="equipo_{{ $loop->index }}">
                                            {{ $eq }}
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label font-bold">Realizado por *</label>
                        <select name="id_personal" class="form-control" required>
                            <option value="">Seleccione el personal...</option>
                            @foreach($personal as $p)
                                <option value="{{ $p->id }}" {{ old('id_personal', $mantenimiento->id_personal) == $p->id ? 'selected' : '' }}>
                                    {{ $p->nombre_completo }} - {{ $p->cargo }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label font-bold">Supervisado por *</label>
                        <input type="text" name="supervisado_por" class="form-control" value="Lucia Cruz Farfan" readonly required>
                    </div>
                    <div class="form-group">
                        <label class="form-label font-bold">Próxima Fecha</label>
                        <input type="date" name="proxima_fecha" class="form-control" value="{{ old('proxima_fecha', $mantenimiento->proxima_fecha ? $mantenimiento->proxima_fecha->format('Y-m-d') : '') }}">
                    </div>
                </div>
                <div class="form-group mb-4">
                    <label class="form-label font-bold">Productos de Limpieza Utilizados *</label>
                    <div class="row">
                        @foreach($productosLimpieza as $producto)
                            <div class="col-md-6 col-lg-4 mb-3">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox"
                                           class="custom-control-input"
                                           name="productos_limpieza[]"
                                           id="producto_{{ $loop->index }}"
                                           value="{{ $producto }}"
                                           {{ in_array($producto, old('productos_limpieza', $mantenimiento->productos_limpieza ?? [])) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="producto_{{ $loop->index }}">
                                        {{ $producto }}
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="flex justify-end gap-2">
                    <a href="{{ route('control.mantenimiento.index') }}" class="btn btn-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-primary">Actualizar</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
