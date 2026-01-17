@extends('layouts.app')

@section('title', 'Nuevo Empleado')
@section('page-title', 'Nuevo Empleado')
@section('page-subtitle', 'Complete los datos para registrar un nuevo empleado')

@section('content')
<div class="max-w-3xl mx-auto">
    {{-- Breadcrumb --}}
    <div class="mb-6">
        <nav class="text-sm">
            <a href="{{ route('admin.dashboard') }}" class="text-cyan-600 hover:text-cyan-800">Dashboard</a>
            <span class="mx-2 text-gray-500">/</span>
            <a href="{{ route('control.asistencia-semanal.registro-rapido') }}" class="text-cyan-600 hover:text-cyan-800">Personal</a>
            <span class="mx-2 text-gray-500">/</span>
            <span class="text-gray-600">Nuevo Empleado</span>
        </nav>
    </div>

    <div class="bg-white rounded-lg shadow-md p-8">
        <form action="{{ route('control.empleados.store') }}" method="POST" id="empleadoForm" enctype="multipart/form-data">
            @csrf

            <div class="space-y-6">
                {{-- Información Personal --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Nombre Completo --}}
                    <div>
                        <label for="nombre_completo" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-user text-cyan-600 mr-1"></i>
                            Nombre Completo <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               name="nombre_completo"
                               id="nombre_completo"
                               value="{{ old('nombre_completo') }}"
                               required
                               class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition @error('nombre_completo') border-red-500 @enderror"
                               placeholder="Ej: Juan Pérez García">
                        @error('nombre_completo')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Cargo --}}
                    <div>
                        <label for="cargo" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-briefcase text-cyan-600 mr-1"></i>
                            Cargo <span class="text-red-500">*</span>
                        </label>
                        <select name="cargo"
                                id="cargo"
                                required
                                onchange="toggleLicenciaSection(this.value)"
                                class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition @error('cargo') border-red-500 @enderror">
                            <option value="">Seleccione un cargo...</option>
                            <option value="Encargado de Producción (Embolsador)" {{ old('cargo') == 'Encargado de Producción (Embolsador)' ? 'selected' : '' }}>Encargado de Producción (Embolsador)</option>
                            <option value="Encargado de Inventario/Almacén" {{ old('cargo') == 'Encargado de Inventario/Almacén' ? 'selected' : '' }}>Encargado de Inventario/Almacén</option>
                            <option value="Ayudante (Distribuidor)" {{ old('cargo') == 'Ayudante (Distribuidor)' ? 'selected' : '' }}>Ayudante (Distribuidor)</option>
                            <option value="Chofer (Despacho)" {{ old('cargo') == 'Chofer (Despacho)' ? 'selected' : '' }}>Chofer (Despacho)</option>
                            <option value="Limpieza" {{ old('cargo') == 'Limpieza' ? 'selected' : '' }}>Limpieza</option>
                        </select>
                        @error('cargo')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Teléfono y Dirección --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Teléfono --}}
                    <div>
                        <label for="telefono" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-phone text-cyan-600 mr-1"></i>
                            Teléfono
                        </label>
                        <input type="text"
                               name="telefono"
                               id="telefono"
                               value="{{ old('telefono') }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition @error('telefono') border-red-500 @enderror"
                               placeholder="Ej: 7777-7777">
                        @error('telefono')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Dirección --}}
                    <div>
                        <label for="direccion" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-map-marker-alt text-cyan-600 mr-1"></i>
                            Dirección
                        </label>
                        <input type="text"
                               name="direccion"
                               id="direccion"
                               value="{{ old('direccion') }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition @error('direccion') border-red-500 @enderror"
                               placeholder="Ej: Colonia Centro, Calle Principal">
                        @error('direccion')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Información Laboral --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Fecha de Ingreso --}}
                    <div>
                        <label for="fecha_ingreso" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-calendar-alt text-cyan-600 mr-1"></i>
                            Fecha de Ingreso <span class="text-red-500">*</span>
                        </label>
                        <input type="date"
                               name="fecha_ingreso"
                               id="fecha_ingreso"
                               value="{{ old('fecha_ingreso', date('Y-m-d')) }}"
                               required
                               class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition @error('fecha_ingreso') border-red-500 @enderror">
                        @error('fecha_ingreso')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Salario --}}
                    <div>
                        <label for="salario" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-dollar-sign text-cyan-600 mr-1"></i>
                            Salario
                        </label>
                        <input type="number"
                               name="salario"
                               id="salario"
                               value="{{ old('salario') }}"
                               step="0.01"
                               min="0"
                               class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition @error('salario') border-red-500 @enderror"
                               placeholder="Ej: 400.00">
                        @error('salario')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Estado del Empleado --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- Marcar Ausente --}}
                    <div class="bg-red-50 border-2 border-red-200 rounded-lg p-4">
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox"
                                   name="ausente"
                                   id="ausente"
                                   value="1"
                                   {{ old('ausente') ? 'checked' : '' }}
                                   onchange="toggleAusenteStatus(this.checked)"
                                   class="w-5 h-5 text-red-600 border-gray-300 rounded focus:ring-red-500">
                            <span class="ml-3">
                                <span class="font-semibold text-gray-700">
                                    <i class="fas fa-user-slash text-red-600 mr-1"></i>
                                    Marcar como Ausente
                                </span>
                                <p class="text-xs text-gray-500 mt-1">El empleado NO podrá acceder al sistema</p>
                            </span>
                        </label>
                    </div>

                    {{-- Acceso al Sistema --}}
                    <div id="accesoSistemaBox" class="bg-blue-50 border-2 border-blue-200 rounded-lg p-4">
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox"
                                   name="acceso_sistema"
                                   id="acceso_sistema"
                                   value="1"
                                   {{ old('acceso_sistema') ? 'checked' : '' }}
                                   onchange="toggleCredencialesSection(this.checked)"
                                   class="w-5 h-5 text-cyan-600 border-gray-300 rounded focus:ring-cyan-500">
                            <span class="ml-3">
                                <span class="font-semibold text-gray-700">
                                    <i class="fas fa-key text-cyan-600 mr-1"></i>
                                    Acceder al Sistema
                                </span>
                                <p class="text-xs text-gray-500 mt-1">Permite al empleado iniciar sesión en el sistema</p>
                            </span>
                        </label>
                    </div>
                </div>

                {{-- Credenciales de Acceso --}}
                <div id="credencialesSection" class="hidden space-y-4 bg-gray-50 p-4 rounded-lg">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Email --}}
                        <div>
                            <label for="email_acceso" class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-envelope text-cyan-600 mr-1"></i>
                                Correo Electrónico <span class="text-red-500">*</span>
                            </label>
                            <input type="email"
                                   name="email_acceso"
                                   id="email_acceso"
                                   value="{{ old('email_acceso') }}"
                                   class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition @error('email_acceso') border-red-500 @enderror"
                                   placeholder="Ej: juan.perez@aguacolegial.com">
                            <p class="mt-1 text-xs text-gray-500">
                                <i class="fas fa-info-circle mr-1"></i>
                                El empleado usará este correo para iniciar sesión
                            </p>
                            @error('email_acceso')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Contraseña --}}
                        <div>
                            <label for="password_acceso" class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-lock text-cyan-600 mr-1"></i>
                                Contraseña
                            </label>
                            <input type="password"
                                   name="password_acceso"
                                   id="password_acceso"
                                   class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition @error('password_acceso') border-red-500 @enderror"
                                   placeholder="Mínimo 6 caracteres">
                            <p class="mt-1 text-xs text-gray-500">
                                <i class="fas fa-info-circle mr-1"></i>
                                Dejar vacío para asignar: password123
                            </p>
                            @error('password_acceso')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Sección Licencia de Conducir (solo para Chofer) --}}
                <div id="licenciaSection" class="hidden bg-amber-50 border-2 border-amber-200 rounded-lg p-4">
                    <label class="block text-sm font-semibold text-amber-800 mb-3">
                        <i class="fas fa-car text-amber-600 mr-1"></i>
                        Licencia de Conducir
                    </label>
                    <input type="file"
                           name="foto_licencia"
                           id="foto_licencia"
                           accept="image/*"
                           onchange="previewLicencia(this)"
                           class="w-full border border-amber-300 rounded-lg px-4 py-3 bg-white focus:ring-2 focus:ring-amber-500 focus:border-transparent transition @error('foto_licencia') border-red-500 @enderror">
                    <p class="mt-1 text-xs text-amber-700">
                        <i class="fas fa-info-circle mr-1"></i>
                        Formatos: JPG, PNG, GIF. Max: 5MB
                    </p>
                    @error('foto_licencia')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <div id="licenciaPreview" class="hidden mt-3">
                        <p class="text-sm font-semibold text-amber-800 mb-2">
                            <i class="fas fa-eye mr-1"></i> Vista previa:
                        </p>
                        <img id="previewLicencia" src="" alt="Vista previa licencia"
                             class="max-w-xs max-h-40 rounded-lg border-2 border-amber-400 shadow">
                    </div>
                </div>

                {{-- Documento de Identificación --}}
                <div>
                    <label for="foto_documento" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-id-card text-cyan-600 mr-1"></i>
                        Foto del Documento (DUI, Pasaporte, etc.)
                    </label>
                    <input type="file"
                           name="foto_documento"
                           id="foto_documento"
                           accept="image/*"
                           onchange="previewImage(this)"
                           class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition @error('foto_documento') border-red-500 @enderror">
                    <p class="mt-1 text-xs text-gray-500">
                        <i class="fas fa-info-circle mr-1"></i>
                        Formatos: JPG, PNG, GIF. Max: 5MB
                    </p>
                    @error('foto_documento')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <div id="imagePreview" class="hidden mt-3">
                        <p class="text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-eye mr-1"></i> Vista previa:
                        </p>
                        <img id="preview" src="" alt="Vista previa"
                             class="max-w-xs max-h-40 rounded-lg border-2 border-cyan-400 shadow">
                    </div>
                </div>

                {{-- Observaciones --}}
                <div>
                    <label for="observaciones" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-sticky-note text-cyan-600 mr-1"></i>
                        Observaciones
                    </label>
                    <textarea name="observaciones"
                              id="observaciones"
                              rows="3"
                              class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-cyan-500 focus:border-transparent transition @error('observaciones') border-red-500 @enderror"
                              placeholder="Ingrese observaciones relevantes sobre el empleado...">{{ old('observaciones') }}</textarea>
                    @error('observaciones')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Botones --}}
                <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                    <a href="{{ route('control.asistencia-semanal.registro-rapido') }}" class="text-gray-600 hover:text-gray-800 font-semibold transition">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Volver
                    </a>

                    <div class="flex space-x-3">
                        <button type="reset" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-3 rounded-lg font-semibold transition">
                            <i class="fas fa-redo mr-2"></i>
                            Limpiar
                        </button>

                        <button type="submit" class="bg-gradient-to-r from-cyan-500 to-teal-500 hover:from-cyan-600 hover:to-teal-600 text-white px-6 py-3 rounded-lg font-semibold transition shadow-lg">
                            <i class="fas fa-save mr-2"></i>
                            Guardar Empleado
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function toggleLicenciaSection(cargo) {
    if (cargo === 'Chofer (Despacho)') {
        $('#licenciaSection').removeClass('hidden').hide().slideDown();
    } else {
        $('#licenciaSection').slideUp(function() {
            $(this).addClass('hidden');
        });
    }
}

function toggleCredencialesSection(checked) {
    if (checked) {
        $('#credencialesSection').removeClass('hidden').hide().slideDown();
        $('#email_acceso').prop('required', true);
    } else {
        $('#credencialesSection').slideUp(function() {
            $(this).addClass('hidden');
        });
        $('#email_acceso').prop('required', false);
    }
}

function toggleAusenteStatus(ausente) {
    if (ausente) {
        // Si está ausente, deshabilitar acceso al sistema
        $('#acceso_sistema').prop('checked', false).prop('disabled', true);
        $('#accesoSistemaBox').addClass('opacity-50 cursor-not-allowed');
        toggleCredencialesSection(false);
    } else {
        // Si no está ausente, habilitar acceso al sistema
        $('#acceso_sistema').prop('disabled', false);
        $('#accesoSistemaBox').removeClass('opacity-50 cursor-not-allowed');
    }
}

function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            $('#preview').attr('src', e.target.result);
            $('#imagePreview').removeClass('hidden');
        }
        reader.readAsDataURL(input.files[0]);
    } else {
        $('#imagePreview').addClass('hidden');
    }
}

function previewLicencia(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            $('#previewLicencia').attr('src', e.target.result);
            $('#licenciaPreview').removeClass('hidden');
        }
        reader.readAsDataURL(input.files[0]);
    } else {
        $('#licenciaPreview').addClass('hidden');
    }
}

$(document).ready(function() {
    // Verificar cargo al cargar (para old values)
    const cargoActual = $('#cargo').val();
    if (cargoActual === 'Chofer (Despacho)') {
        $('#licenciaSection').removeClass('hidden');
    }

    // Verificar acceso_sistema al cargar (para old values)
    if ($('#acceso_sistema').is(':checked')) {
        $('#credencialesSection').removeClass('hidden');
        $('#email_acceso').prop('required', true);
    }
});
</script>
@endpush
