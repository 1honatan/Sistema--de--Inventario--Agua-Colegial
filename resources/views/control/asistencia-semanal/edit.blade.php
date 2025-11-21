@extends('layouts.app')

@section('title', 'Editar Asistencia')
@push('styles')
<style>
    .form-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        padding: 2rem;
        max-width: 800px;
        margin: 0 auto;
    }

    .form-header {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        color: white;
        padding: 1.5rem;
        border-radius: 12px;
        margin-bottom: 2rem;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        display: block;
        font-weight: 700;
        color: #1e3a8a;
        margin-bottom: 0.5rem;
        font-size: 0.95rem;
    }

    .form-label-required::after {
        content: ' *';
        color: #ef4444;
    }

    .form-control {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 2px solid #e2e8f0;
        border-radius: 8px;
        font-size: 1rem;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        outline: none;
        border-color: #1e3a8a;
        box-shadow: 0 0 0 3px rgba(30, 58, 138, 0.1);
    }

    .form-control.is-invalid {
        border-color: #ef4444;
    }

    .invalid-feedback {
        color: #ef4444;
        font-size: 0.85rem;
        margin-top: 0.25rem;
        display: block;
    }

    .form-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
    }

    .estado-option {
        display: flex;
        align-items: center;
        padding: 1rem;
        border: 2px solid #e2e8f0;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
        margin-bottom: 0.5rem;
    }

    .estado-option:hover {
        border-color: #1e3a8a;
        background: #f8f9fa;
    }

    .estado-option input[type="radio"] {
        margin-right: 0.75rem;
        width: 20px;
        height: 20px;
        cursor: pointer;
    }

    .estado-option input[type="radio"]:checked + .estado-label {
        font-weight: 800;
        color: #1e3a8a;
    }

    .estado-option.presente {
        border-left: 5px solid #10b981;
    }

    .estado-option.ausente {
        border-left: 5px solid #ef4444;
    }

    .estado-option.permiso {
        border-left: 5px solid #f59e0b;
    }

    .estado-option.tardanza {
        border-left: 5px solid #f97316;
    }

    .btn-group-form {
        display: flex;
        gap: 1rem;
        margin-top: 2rem;
        justify-content: flex-end;
    }

    .icon-input-group {
        position: relative;
    }

    .icon-input-group i {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: #6b7280;
        pointer-events: none;
    }

    .icon-input-group .form-control {
        padding-left: 3rem;
    }

    .help-text {
        font-size: 0.85rem;
        color: #6b7280;
        margin-top: 0.25rem;
        font-style: italic;
    }

    .card-warning {
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        border-left: 5px solid #f59e0b;
        padding: 1rem;
        border-radius: 8px;
        margin-bottom: 1.5rem;
    }

    .card-warning-icon {
        color: #d97706;
        font-size: 1.5rem;
        margin-right: 0.75rem;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="form-card">
        <div class="form-header">
            <h3 class="text-2xl font-bold mb-2">
                <i class="fas fa-edit"></i> Editar Registro de Asistencia
            </h3>
            <p class="text-sm opacity-90">Modificar los datos del registro seleccionado</p>
        </div>

        <!-- Advertencia -->
        <div class="card-warning">
            <div class="flex items-start">
                <i class="fas fa-exclamation-triangle card-warning-icon"></i>
                <div>
                    <p class="font-bold text-yellow-900 mb-1">Edición de Registro</p>
                    <p class="text-sm text-yellow-800">
                        Está modificando un registro existente. Los cambios quedarán registrados en el sistema.
                    </p>
                </div>
            </div>
        </div>

        <form action="{{ route('control.asistencia-semanal.update', $asistencia->id) }}" method="POST" id="formEditarAsistencia">
            @csrf
            @method('PUT')

            <!-- Selección de Personal -->
            <div class="form-group">
                <label for="personal_id" class="form-label form-label-required">
                    <i class="fas fa-user"></i> Empleado
                </label>
                <select name="personal_id"
                        id="personal_id"
                        class="form-control select2 @error('personal_id') is-invalid @enderror"
                        required>
                    <option value="">-- Seleccione un empleado --</option>
                    @foreach($personal as $empleado)
                        <option value="{{ $empleado->id }}"
                                {{ old('personal_id', $asistencia->personal_id) == $empleado->id ? 'selected' : '' }}>
                            {{ $empleado->nombre_completo }} - {{ $empleado->cargo }}
                        </option>
                    @endforeach
                </select>
                @error('personal_id')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <!-- Fecha -->
            <div class="form-group">
                <label for="fecha" class="form-label form-label-required">
                    <i class="fas fa-calendar-alt"></i> Fecha
                </label>
                <div class="icon-input-group">
                    <i class="fas fa-calendar"></i>
                    <input type="date"
                           name="fecha"
                           id="fecha"
                           class="form-control @error('fecha') is-invalid @enderror"
                           value="{{ old('fecha', $asistencia->fecha->format('Y-m-d')) }}"
                           required>
                </div>
                @error('fecha')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <!-- Horarios -->
            <div class="form-row">
                <!-- Hora de Entrada -->
                <div class="form-group">
                    <label for="entrada_hora" class="form-label form-label-required">
                        <i class="fas fa-sign-in-alt text-green-600"></i> Hora de Entrada
                    </label>
                    <div class="icon-input-group">
                        <i class="fas fa-clock"></i>
                        <input type="time"
                               name="entrada_hora"
                               id="entrada_hora"
                               class="form-control @error('entrada_hora') is-invalid @enderror"
                               value="{{ old('entrada_hora', \Carbon\Carbon::parse($asistencia->entrada_hora)->format('H:i')) }}"
                               required>
                    </div>
                    @error('entrada_hora')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Hora de Salida -->
                <div class="form-group">
                    <label for="salida_hora" class="form-label">
                        <i class="fas fa-sign-out-alt text-red-600"></i> Hora de Salida
                    </label>
                    <div class="icon-input-group">
                        <i class="fas fa-clock"></i>
                        <input type="time"
                               name="salida_hora"
                               id="salida_hora"
                               class="form-control @error('salida_hora') is-invalid @enderror"
                               value="{{ old('salida_hora', $asistencia->salida_hora ? \Carbon\Carbon::parse($asistencia->salida_hora)->format('H:i') : '') }}">
                    </div>
                    @error('salida_hora')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                    <p class="help-text">
                        <i class="fas fa-info-circle"></i> Opcional - puede dejarse en blanco si aún no sale
                    </p>
                </div>
            </div>

            <!-- Estado de Asistencia -->
            <div class="form-group">
                <label class="form-label form-label-required">
                    <i class="fas fa-check-circle"></i> Estado de Asistencia
                </label>

                <label class="estado-option presente">
                    <input type="radio"
                           name="estado"
                           value="presente"
                           {{ old('estado', $asistencia->estado) == 'presente' ? 'checked' : '' }}
                           required>
                    <div class="estado-label">
                        <div class="font-bold text-green-700">
                            <i class="fas fa-check-circle"></i> Presente
                        </div>
                        <div class="text-xs text-gray-600">Asistió normalmente</div>
                    </div>
                </label>

                <label class="estado-option tardanza">
                    <input type="radio"
                           name="estado"
                           value="tardanza"
                           {{ old('estado', $asistencia->estado) == 'tardanza' ? 'checked' : '' }}>
                    <div class="estado-label">
                        <div class="font-bold text-orange-700">
                            <i class="fas fa-clock"></i> Tardanza
                        </div>
                        <div class="text-xs text-gray-600">Llegó tarde</div>
                    </div>
                </label>

                <label class="estado-option permiso">
                    <input type="radio"
                           name="estado"
                           value="permiso"
                           {{ old('estado', $asistencia->estado) == 'permiso' ? 'checked' : '' }}>
                    <div class="estado-label">
                        <div class="font-bold text-yellow-700">
                            <i class="fas fa-file-alt"></i> Permiso
                        </div>
                        <div class="text-xs text-gray-600">Con permiso autorizado</div>
                    </div>
                </label>

                <label class="estado-option ausente">
                    <input type="radio"
                           name="estado"
                           value="ausente"
                           {{ old('estado', $asistencia->estado) == 'ausente' ? 'checked' : '' }}>
                    <div class="estado-label">
                        <div class="font-bold text-red-700">
                            <i class="fas fa-times-circle"></i> Ausente
                        </div>
                        <div class="text-xs text-gray-600">No asistió</div>
                    </div>
                </label>

                @error('estado')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
            </div>

            <!-- Observaciones -->
            <div class="form-group">
                <label for="observaciones" class="form-label">
                    <i class="fas fa-comment-dots"></i> Observaciones
                </label>
                <textarea name="observaciones"
                          id="observaciones"
                          rows="4"
                          class="form-control @error('observaciones') is-invalid @enderror"
                          placeholder="Ingrese cualquier observación o nota adicional...">{{ old('observaciones', $asistencia->observaciones) }}</textarea>
                @error('observaciones')
                    <span class="invalid-feedback">{{ $message }}</span>
                @enderror
                <p class="help-text">
                    <i class="fas fa-info-circle"></i> Máximo 500 caracteres
                </p>
            </div>

            <!-- Botones de Acción -->
            <div class="btn-group-form">
                <a href="{{ route('control.asistencia-semanal.index', ['semana' => $asistencia->fecha->format('Y-m-d')]) }}"
                   class="btn btn-secondary">
                    <i class="fas fa-times"></i> Cancelar
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Actualizar Asistencia
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Inicializar Select2
    $('.select2').select2({
        theme: 'bootstrap-5',
        width: '100%',
        placeholder: '-- Seleccione un empleado --',
        allowClear: true
    });

    // Validación de hora de salida
    $('#salida_hora').on('change', function() {
        const entrada = $('#entrada_hora').val();
        const salida = $(this).val();

        if (entrada && salida && salida <= entrada) {
            alert('La hora de salida debe ser posterior a la hora de entrada');
            $(this).val('');
        }
    });

    // Animación de radio buttons
    $('input[name="estado"]').on('change', function() {
        $('.estado-option').removeClass('border-blue-500');
        $(this).closest('.estado-option').addClass('border-blue-500');
    });

    // Marcar opción preseleccionada
    $('input[name="estado"]:checked').closest('.estado-option').addClass('border-blue-500');
});
</script>
@endpush
