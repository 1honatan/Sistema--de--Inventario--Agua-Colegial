@extends('layouts.app')
@section('title', 'Editar Limpieza Tanque')
@section('content')
<div class="container-fluid">
    <div class="card"><div class="card-header bg-gradient-to-r from-blue-900 to-blue-800 text-white"><h3 class="text-xl font-bold">Editar Registro #{{ $tanque->id }}</h3></div>
        <div class="card-body">
            <form action="{{ route('control.tanques.update', $tanque) }}" method="POST">@csrf @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div><label class="form-label font-bold">Fecha *</label><input type="date" name="fecha_limpieza" class="form-control" value="{{ old('fecha_limpieza', $tanque->fecha_limpieza->format('Y-m-d')) }}" required></div>
                    <div><label class="form-label font-bold">Tanque *</label><input type="text" name="nombre_tanque" class="form-control" value="{{ old('nombre_tanque', $tanque->nombre_tanque) }}" required></div>
                    <div><label class="form-label font-bold">Capacidad (L)</label><input type="number" step="0.01" name="capacidad_litros" class="form-control" value="{{ old('capacidad_litros', $tanque->capacidad_litros) }}"></div>
                    <div><label class="form-label font-bold">Responsable *</label>
                        <select name="responsable" class="form-control" required>
                            <option value="">Seleccione responsable...</option>
                            @foreach($personal as $persona)
                                <option value="{{ $persona->nombre_completo }}" {{ old('responsable', $tanque->responsable) == $persona->nombre_completo ? 'selected' : '' }}>{{ $persona->nombre_completo }} ({{ $persona->cargo }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div><label class="form-label font-bold">Supervisado</label><input type="text" name="supervisado_por" class="form-control" value="{{ old('supervisado_por', $tanque->supervisado_por) }}"></div>
                    <div><label class="form-label font-bold">Próxima</label><input type="date" name="proxima_limpieza" class="form-control" value="{{ old('proxima_limpieza', $tanque->proxima_limpieza ? $tanque->proxima_limpieza->format('Y-m-d') : '') }}"></div>
                </div>
                <div class="form-group mb-4"><label class="form-label font-bold">Procedimiento</label><textarea name="procedimiento_limpieza" rows="4" class="form-control">{{ old('procedimiento_limpieza', $tanque->procedimiento_limpieza) }}</textarea></div>
                <div class="form-group mb-4"><label class="form-label font-bold">Productos</label><textarea name="productos_desinfeccion" rows="3" class="form-control">{{ old('productos_desinfeccion', $tanque->productos_desinfeccion) }}</textarea></div>
                <div class="form-group mb-4"><label class="form-label font-bold">Observaciones</label><textarea name="observaciones" rows="3" class="form-control">{{ old('observaciones', $tanque->observaciones) }}</textarea></div>
                <div class="flex justify-end gap-2">
                    <a href="{{ route('control.tanques.index') }}" class="btn btn-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-primary">Actualizar</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
