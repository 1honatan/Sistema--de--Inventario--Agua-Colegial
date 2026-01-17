@extends('layouts.app')

@section('title', 'Editar Empleado')
@section('page-title', 'Editar Empleado')
@section('page-subtitle', 'Modificar los datos de {{ $empleado->nombre_completo }}')

@section('content')
<div class="max-w-3xl mx-auto">
    {{-- Breadcrumb --}}
    <div class="mb-6">
        <nav class="text-sm">
            <a href="{{ route('admin.dashboard') }}" class="text-amber-600 hover:text-amber-800">Dashboard</a>
            <span class="mx-2 text-gray-500">/</span>
            <a href="{{ route('control.asistencia-semanal.registro-rapido') }}" class="text-amber-600 hover:text-amber-800">Personal</a>
            <span class="mx-2 text-gray-500">/</span>
            <span class="text-gray-600">Editar Empleado</span>
        </nav>
    </div>

    {{-- Estado de Acceso al Sistema (Solo si tiene usuario) --}}
    @if($empleado->usuario)
    <div class="mb-6 p-4 rounded-lg border-2 {{ $empleado->usuario->estado === 'activo' ? 'bg-green-50 border-green-400' : 'bg-red-50 border-red-400' }}">
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div class="flex items-center">
                @if($empleado->usuario->estado === 'activo')
                    <div class="w-12 h-12 rounded-full bg-green-500 flex items-center justify-center mr-4">
                        <i class="fas fa-user-check text-white text-xl"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-green-800 text-lg">Estado: ACTIVO</h3>
                        <p class="text-green-600 text-sm">El empleado puede acceder al sistema</p>
                    </div>
                @else
                    <div class="w-12 h-12 rounded-full bg-red-500 flex items-center justify-center mr-4">
                        <i class="fas fa-user-slash text-white text-xl"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-red-800 text-lg">Estado: AUSENTE</h3>
                        <p class="text-red-600 text-sm">El empleado NO puede acceder al sistema</p>
                    </div>
                @endif
            </div>
            <div>
                @if($empleado->usuario->estado === 'activo')
                    <button type="button" onclick="confirmarDeshabilitarAcceso()"
                            class="bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-lg font-bold transition shadow-lg">
                        <i class="fas fa-user-slash mr-2"></i>
                        Marcar como Ausente
                    </button>
                @else
                    <button type="button" onclick="confirmarHabilitarAcceso()"
                            class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-bold transition shadow-lg">
                        <i class="fas fa-user-check mr-2"></i>
                        Reactivar Acceso
                    </button>
                @endif
            </div>
        </div>
    </div>
    @endif

    <div class="bg-white rounded-lg shadow-md p-8">
        <form action="{{ route('control.empleados.update', $empleado->id) }}" method="POST" id="empleadoForm" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                {{-- Información Personal --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Nombre Completo --}}
                    <div>
                        <label for="nombre_completo" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-user text-amber-600 mr-1"></i>
                            Nombre Completo <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               name="nombre_completo"
                               id="nombre_completo"
                               value="{{ old('nombre_completo', $empleado->nombre_completo) }}"
                               required
                               class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-amber-500 focus:border-transparent transition @error('nombre_completo') border-red-500 @enderror"
                               placeholder="Ej: Juan Pérez García">
                        @error('nombre_completo')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Cargo --}}
                    <div>
                        <label for="cargo" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-briefcase text-amber-600 mr-1"></i>
                            Cargo <span class="text-red-500">*</span>
                        </label>
                        <select name="cargo"
                                id="cargo"
                                required
                                onchange="toggleLicenciaSection(this.value)"
                                class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-amber-500 focus:border-transparent transition @error('cargo') border-red-500 @enderror">
                            <option value="">Seleccione un cargo...</option>
                            <option value="Encargado de Producción (Embolsador)" {{ old('cargo', $empleado->cargo) == 'Encargado de Producción (Embolsador)' ? 'selected' : '' }}>Encargado de Producción (Embolsador)</option>
                            <option value="Encargado de Inventario/Almacén" {{ old('cargo', $empleado->cargo) == 'Encargado de Inventario/Almacén' ? 'selected' : '' }}>Encargado de Inventario/Almacén</option>
                            <option value="Ayudante (Distribuidor)" {{ old('cargo', $empleado->cargo) == 'Ayudante (Distribuidor)' ? 'selected' : '' }}>Ayudante (Distribuidor)</option>
                            <option value="Chofer (Despacho)" {{ old('cargo', $empleado->cargo) == 'Chofer (Despacho)' ? 'selected' : '' }}>Chofer (Despacho)</option>
                            <option value="Limpieza" {{ old('cargo', $empleado->cargo) == 'Limpieza' ? 'selected' : '' }}>Limpieza</option>
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
                            <i class="fas fa-phone text-amber-600 mr-1"></i>
                            Teléfono
                        </label>
                        <input type="text"
                               name="telefono"
                               id="telefono"
                               value="{{ old('telefono', $empleado->telefono) }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-amber-500 focus:border-transparent transition @error('telefono') border-red-500 @enderror"
                               placeholder="Ej: 7777-7777">
                        @error('telefono')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Dirección --}}
                    <div>
                        <label for="direccion" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-map-marker-alt text-amber-600 mr-1"></i>
                            Dirección
                        </label>
                        <input type="text"
                               name="direccion"
                               id="direccion"
                               value="{{ old('direccion', $empleado->direccion) }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-amber-500 focus:border-transparent transition @error('direccion') border-red-500 @enderror"
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
                            <i class="fas fa-calendar-alt text-amber-600 mr-1"></i>
                            Fecha de Ingreso <span class="text-red-500">*</span>
                        </label>
                        <input type="date"
                               name="fecha_ingreso"
                               id="fecha_ingreso"
                               value="{{ old('fecha_ingreso', $empleado->fecha_ingreso ? \Carbon\Carbon::parse($empleado->fecha_ingreso)->format('Y-m-d') : '') }}"
                               required
                               class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-amber-500 focus:border-transparent transition @error('fecha_ingreso') border-red-500 @enderror">
                        @error('fecha_ingreso')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Salario --}}
                    <div>
                        <label for="salario" class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-dollar-sign text-amber-600 mr-1"></i>
                            Salario
                        </label>
                        <input type="number"
                               name="salario"
                               id="salario"
                               value="{{ old('salario', $empleado->salario) }}"
                               step="0.01"
                               min="0"
                               class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-amber-500 focus:border-transparent transition @error('salario') border-red-500 @enderror"
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
                                   {{ old('ausente', $empleado->estado === 'inactivo') ? 'checked' : '' }}
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
                    @php
                        $tieneAcceso = $empleado->usuario !== null || $empleado->tiene_acceso;
                    @endphp
                    <div id="accesoSistemaBox" class="bg-blue-50 border-2 border-blue-200 rounded-lg p-4 {{ $empleado->estado === 'inactivo' ? 'opacity-50 cursor-not-allowed' : '' }}">
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox"
                                   name="acceso_sistema"
                                   id="acceso_sistema"
                                   value="1"
                                   {{ old('acceso_sistema', $tieneAcceso) ? 'checked' : '' }}
                                   {{ $empleado->estado === 'inactivo' ? 'disabled' : '' }}
                                   onchange="toggleCredencialesSection(this.checked)"
                                   class="w-5 h-5 text-amber-600 border-gray-300 rounded focus:ring-amber-500">
                            <span class="ml-3">
                                <span class="font-semibold text-gray-700">
                                    <i class="fas fa-key text-amber-600 mr-1"></i>
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
                                <i class="fas fa-envelope text-amber-600 mr-1"></i>
                                Correo Electrónico <span class="text-red-500">*</span>
                            </label>
                            <input type="email"
                                   name="email_acceso"
                                   id="email_acceso"
                                   value="{{ old('email_acceso', $empleado->usuario->email ?? '') }}"
                                   class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-amber-500 focus:border-transparent transition @error('email_acceso') border-red-500 @enderror"
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
                                <i class="fas fa-lock text-amber-600 mr-1"></i>
                                Contraseña
                            </label>
                            <input type="password"
                                   name="password_acceso"
                                   id="password_acceso"
                                   class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-amber-500 focus:border-transparent transition @error('password_acceso') border-red-500 @enderror"
                                   placeholder="Dejar vacío para mantener la actual">
                            <p class="mt-1 text-xs text-gray-500">
                                <i class="fas fa-info-circle mr-1"></i>
                                {{ $empleado->usuario ? 'Dejar vacío para mantener la contraseña actual' : 'Mínimo 6 caracteres' }}
                            </p>
                            @error('password_acceso')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Credenciales actuales --}}
                    @if($empleado->usuario)
                    <div class="bg-blue-100 border border-blue-300 rounded-lg p-4 mt-4">
                        <h6 class="font-bold text-blue-800 mb-2">
                            <i class="fas fa-user-check mr-2"></i>
                            Credenciales Actuales
                        </h6>
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <p class="text-blue-700"><strong>Correo:</strong> {{ $empleado->usuario->email }}</p>
                            <p class="text-blue-700"><strong>Rol:</strong> {{ ucfirst($empleado->usuario->rol->nombre ?? 'No asignado') }}</p>
                        </div>
                    </div>
                    @endif
                </div>

                {{-- Sección Licencia de Conducir (solo para Chofer) --}}
                <div id="licenciaSection" class="hidden bg-amber-50 border-2 border-amber-200 rounded-lg p-4">
                    <label class="block text-sm font-semibold text-amber-800 mb-3">
                        <i class="fas fa-car text-amber-600 mr-1"></i>
                        Licencia de Conducir
                    </label>
                    @if($empleado->foto_licencia)
                        <div class="mb-3">
                            <p class="text-sm font-semibold text-amber-800 mb-2">
                                <i class="fas fa-image mr-1"></i> Imagen actual:
                            </p>
                            <img src="{{ asset($empleado->foto_licencia) }}" alt="Licencia actual"
                                 class="max-w-xs max-h-40 rounded-lg border-2 border-amber-400 shadow">
                        </div>
                    @endif
                    <input type="file"
                           name="foto_licencia"
                           id="foto_licencia"
                           accept="image/*"
                           onchange="previewLicencia(this)"
                           class="w-full border border-amber-300 rounded-lg px-4 py-3 bg-white focus:ring-2 focus:ring-amber-500 focus:border-transparent transition @error('foto_licencia') border-red-500 @enderror">
                    <p class="mt-1 text-xs text-amber-700">
                        <i class="fas fa-info-circle mr-1"></i>
                        Formatos: JPG, PNG, GIF. Max: 5MB {{ $empleado->foto_licencia ? '(Dejar vacío para mantener)' : '' }}
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
                        <i class="fas fa-id-card text-amber-600 mr-1"></i>
                        Foto del Documento (DUI, Pasaporte, etc.)
                    </label>
                    @if($empleado->foto_documento)
                        <div class="mb-3">
                            <p class="text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-image mr-1"></i> Imagen actual:
                            </p>
                            <img src="{{ asset($empleado->foto_documento) }}" alt="Documento actual"
                                 class="max-w-xs max-h-40 rounded-lg border-2 border-amber-400 shadow">
                        </div>
                    @endif
                    <input type="file"
                           name="foto_documento"
                           id="foto_documento"
                           accept="image/*"
                           onchange="previewImage(this)"
                           class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-amber-500 focus:border-transparent transition @error('foto_documento') border-red-500 @enderror">
                    <p class="mt-1 text-xs text-gray-500">
                        <i class="fas fa-info-circle mr-1"></i>
                        Formatos: JPG, PNG, GIF. Max: 5MB {{ $empleado->foto_documento ? '(Dejar vacío para mantener)' : '' }}
                    </p>
                    @error('foto_documento')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <div id="imagePreview" class="hidden mt-3">
                        <p class="text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-eye mr-1"></i> Vista previa:
                        </p>
                        <img id="preview" src="" alt="Vista previa"
                             class="max-w-xs max-h-40 rounded-lg border-2 border-amber-400 shadow">
                    </div>
                </div>

                {{-- Observaciones --}}
                <div>
                    <label for="observaciones" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-sticky-note text-amber-600 mr-1"></i>
                        Observaciones
                    </label>
                    <textarea name="observaciones"
                              id="observaciones"
                              rows="3"
                              class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-amber-500 focus:border-transparent transition @error('observaciones') border-red-500 @enderror"
                              placeholder="Ingrese observaciones relevantes sobre el empleado...">{{ old('observaciones', $empleado->observaciones) }}</textarea>
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
                            Restablecer
                        </button>

                        <button type="submit" class="bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-white px-6 py-3 rounded-lg font-semibold transition shadow-lg">
                            <i class="fas fa-save mr-2"></i>
                            Actualizar Empleado
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Formularios ocultos para cambiar estado de acceso --}}
<form id="formDeshabilitarAcceso" action="{{ route('control.empleados.deshabilitar-acceso', $empleado->id) }}" method="POST" style="display: none;">
    @csrf
</form>
<form id="formHabilitarAcceso" action="{{ route('control.empleados.habilitar-acceso', $empleado->id) }}" method="POST" style="display: none;">
    @csrf
</form>
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

// Confirmar deshabilitar acceso (marcar ausente)
function confirmarDeshabilitarAcceso() {
    Swal.fire({
        icon: 'warning',
        title: 'Marcar como Ausente',
        html: `<div class="text-left">
            <p class="mb-3">¿Está seguro de marcar a este empleado como <strong class="text-red-600">AUSENTE</strong>?</p>
            <div class="bg-red-50 border-l-4 border-red-500 p-3 rounded">
                <p class="text-sm text-red-700">
                    <i class="fas fa-exclamation-triangle mr-1"></i>
                    Esto deshabilitará su acceso al sistema y <strong>NO podrá iniciar sesión</strong> hasta que se reactive.
                </p>
            </div>
        </div>`,
        showCancelButton: true,
        confirmButtonText: '<i class="fas fa-user-slash mr-1"></i> Sí, Marcar Ausente',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('formDeshabilitarAcceso').submit();
        }
    });
}

// Confirmar habilitar acceso (reactivar)
function confirmarHabilitarAcceso() {
    Swal.fire({
        icon: 'question',
        title: 'Reactivar Acceso',
        html: `<div class="text-left">
            <p class="mb-3">¿Está seguro de <strong class="text-green-600">REACTIVAR</strong> el acceso de este empleado?</p>
            <div class="bg-green-50 border-l-4 border-green-500 p-3 rounded">
                <p class="text-sm text-green-700">
                    <i class="fas fa-check-circle mr-1"></i>
                    El empleado podrá volver a <strong>iniciar sesión</strong> en el sistema.
                </p>
            </div>
        </div>`,
        showCancelButton: true,
        confirmButtonText: '<i class="fas fa-user-check mr-1"></i> Sí, Reactivar',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#22c55e',
        cancelButtonColor: '#6b7280',
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('formHabilitarAcceso').submit();
        }
    });
}

$(document).ready(function() {
    // Verificar cargo al cargar (para old values)
    const cargoActual = $('#cargo').val();
    if (cargoActual === 'Chofer (Despacho)') {
        $('#licenciaSection').removeClass('hidden');
    }

    // Verificar acceso_sistema al cargar (para old values o si ya tiene usuario)
    @if($empleado->usuario || old('acceso_sistema'))
        $('#credencialesSection').removeClass('hidden');
        $('#email_acceso').prop('required', true);
    @endif

    if ($('#acceso_sistema').is(':checked')) {
        $('#credencialesSection').removeClass('hidden');
        $('#email_acceso').prop('required', true);
    }
});
</script>
@endpush
