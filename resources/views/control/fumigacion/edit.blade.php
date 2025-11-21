@extends('layouts.app')
@section('title', 'Editar Fumigación')
@section('content')
<div class="container-fluid">
    <div class="card"><div class="card-header bg-gradient-to-r from-blue-900 to-blue-800 text-white"><h3 class="text-xl font-bold">Editar #{{ $fumigacion->id }}</h3></div>
        <div class="card-body">
            <form action="{{ route('control.fumigacion.update', $fumigacion) }}" method="POST">@csrf @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div><label class="form-label font-bold">Fecha *</label><input type="date" name="fecha_fumigacion" class="form-control" value="{{ old('fecha_fumigacion', $fumigacion->fecha_fumigacion->format('Y-m-d')) }}" required></div>
                    <div><label class="form-label font-bold">Área *</label><input type="text" name="area_fumigada" class="form-control" value="{{ old('area_fumigada', $fumigacion->area_fumigada) }}" required></div>
                    <div><label class="form-label font-bold">Producto *</label><input type="text" name="producto_utilizado" class="form-control" value="{{ old('producto_utilizado', $fumigacion->producto_utilizado) }}" required></div>
                    <div><label class="form-label font-bold">Cantidad *</label><input type="number" step="0.01" name="cantidad_producto" class="form-control" value="{{ old('cantidad_producto', $fumigacion->cantidad_producto) }}" required></div>
                    <div><label class="form-label font-bold">Responsable *</label>
                        <select name="responsable" class="form-control" required>
                            <option value="">Seleccione responsable...</option>
                            @foreach($personal as $persona)
                                <option value="{{ $persona->nombre_completo }}" {{ old('responsable', $fumigacion->responsable) == $persona->nombre_completo ? 'selected' : '' }}>{{ $persona->nombre_completo }} ({{ $persona->cargo }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div><label class="form-label font-bold">Empresa</label><input type="text" name="empresa_contratada" class="form-control" value="{{ old('empresa_contratada', $fumigacion->empresa_contratada) }}"></div>
                    <div><label class="form-label font-bold">Próxima</label><input type="date" name="proxima_fumigacion" class="form-control" value="{{ old('proxima_fumigacion', $fumigacion->proxima_fumigacion ? $fumigacion->proxima_fumigacion->format('Y-m-d') : '') }}"></div>
                </div>
                <div class="form-group mb-4"><label class="form-label font-bold">Observaciones</label><textarea name="observaciones" rows="3" class="form-control">{{ old('observaciones', $fumigacion->observaciones) }}</textarea></div>
                <div class="flex justify-end gap-2">
                    <a href="{{ route('control.fumigacion.index') }}" class="btn btn-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-primary">Actualizar</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
