@extends('layouts.app')

@section('title', 'Nuevo Empleado')

@push('styles')
<style>
    body {
        background: #c0eaff;
        min-height: 100vh;
    }

    .form-container {
        max-width: 900px;
        margin: 0 auto;
        padding: 2rem;
    }

    .form-card {
        background: #ffffff;
        border-radius: 20px;
        box-shadow: 0 8px 25px rgba(30, 58, 138, 0.15);
        overflow: hidden;
        animation: slideUp 0.5s ease-out;
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .form-header {
        background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
        color: white;
        padding: 2rem 2.5rem;
        border-bottom: 4px solid #1e3a8a;
    }

    .form-header h2 {
        color: white;
        font-size: 1.75rem;
        font-weight: 800;
        margin: 0 0 0.5rem 0;
    }

    .form-header p {
        color: rgba(255, 255, 255, 0.9);
        margin: 0;
        font-size: 0.95rem;
    }

    .form-body {
        padding: 2.5rem;
    }

    .section-divider {
        border: none;
        height: 2px;
        background: linear-gradient(90deg, transparent, #3b82f6, transparent);
        margin: 2rem 0;
        opacity: 0.3;
    }

    .input-group {
        margin-bottom: 1.5rem;
    }

    .input-label {
        color: #333333;
        font-size: 0.875rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .input-label i {
        color: #3b82f6;
    }

    .input-label.required::after {
        content: " *";
        color: #dc2626;
        font-weight: 900;
    }

    .form-input,
    .form-select,
    .form-textarea {
        background: #f8fafc;
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        padding: 0.75rem 1rem;
        color: #333333;
        font-size: 1rem;
        font-weight: 500;
        transition: all 0.3s ease;
        width: 100%;
    }

    .form-input:focus,
    .form-select:focus,
    .form-textarea:focus {
        background: #ffffff;
        border-color: #3b82f6;
        outline: none;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    /* Sección de información */
    .info-section {
        background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
        border: 2px solid #3b82f6;
        border-radius: 15px;
        padding: 2rem;
        margin: 1.5rem 0;
    }

    .info-section-title {
        color: #1e40af;
        font-size: 1rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    /* Sección de licencia de conducir */
    .licencia-section {
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        border: 2px solid #f59e0b;
        border-radius: 15px;
        padding: 2rem;
        margin: 1.5rem 0;
        display: none;
    }

    .licencia-section-title {
        color: #92400e;
        font-size: 1rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    /* Observaciones */
    .obs-section {
        background: linear-gradient(135deg, #e0e7ff 0%, #c7d2fe 100%);
        border: 2px solid #6366f1;
        border-radius: 15px;
        padding: 1.5rem;
        margin-top: 1.5rem;
    }

    .obs-section label {
        color: #4338ca;
        font-size: 0.875rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.75rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .obs-section label i {
        color: #6366f1;
        font-size: 1.25rem;
    }

    .obs-section textarea {
        background: #ffffff;
        border: 2px solid #6366f1;
        border-radius: 10px;
        padding: 1rem;
        color: #333333;
        font-size: 0.95rem;
        resize: vertical;
        min-height: 100px;
    }

    .obs-section textarea:focus {
        border-color: #4f46e5;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        outline: none;
    }

    /* Botones */
    .btn-colegial {
        padding: 0.875rem 2.5rem;
        border-radius: 10px;
        font-weight: 800;
        font-size: 1rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border: none;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .btn-save {
        background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);
        color: white;
        box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
    }

    .btn-save:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(59, 130, 246, 0.4);
        color: white;
    }

    .btn-cancel {
        background: linear-gradient(135deg, #64748b 0%, #475569 100%);
        color: white;
        box-shadow: 0 4px 15px rgba(100, 116, 139, 0.3);
    }

    .btn-cancel:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(100, 116, 139, 0.4);
        color: white;
    }

    @media (max-width: 768px) {
        .form-header h2 {
            font-size: 1.5rem;
        }
    }
</style>
@endpush

@section('content')
<div class="form-container">
    <div class="form-card">
        <div class="form-header">
            <h2>
                <i class="fas fa-user-plus mr-3"></i>
                Nuevo Empleado
            </h2>
            <p>Complete los datos para registrar un nuevo empleado</p>
        </div>

        <div class="form-body">
            <form action="{{ route('control.empleados.store') }}" method="POST" id="empleadoForm" enctype="multipart/form-data">
                @csrf

                <!-- Información Personal -->
                <div class="info-section">
                    <div class="info-section-title">
                        <i class="fas fa-id-card"></i>
                        Información Personal
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="input-group">
                                <div class="input-label required">
                                    <i class="fas fa-user"></i>
                                    Nombre Completo
                                </div>
                                <input type="text"
                                       name="nombre_completo"
                                       id="nombre_completo"
                                       class="form-input @error('nombre_completo') is-invalid @enderror"
                                       value="{{ old('nombre_completo') }}"
                                       placeholder="Ej: Juan Pérez García"
                                       required>
                                @error('nombre_completo')
                                    <div class="text-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="input-group">
                                <div class="input-label required">
                                    <i class="fas fa-briefcase"></i>
                                    Cargo
                                </div>
                                <select name="cargo"
                                        id="cargo"
                                        class="form-select @error('cargo') is-invalid @enderror"
                                        required
                                        onchange="toggleLicenciaSection(this.value)">
                                    <option value="">Seleccione un cargo...</option>
                                    <option value="Operador de Producción" {{ old('cargo') == 'Operador de Producción' ? 'selected' : '' }}>Operador de Producción</option>
                                    <option value="Distribuidor" {{ old('cargo') == 'Distribuidor' ? 'selected' : '' }}>Distribuidor</option>
                                    <option value="Chofer" {{ old('cargo') == 'Chofer' ? 'selected' : '' }}>Chofer</option>
                                    <option value="Supervisor" {{ old('cargo') == 'Supervisor' ? 'selected' : '' }}>Supervisor</option>
                                    <option value="Encargado de Almacén" {{ old('cargo') == 'Encargado de Almacén' ? 'selected' : '' }}>Encargado de Almacén</option>
                                    <option value="Encargado de Producción" {{ old('cargo') == 'Encargado de Producción' ? 'selected' : '' }}>Encargado de Producción</option>
                                    <option value="Mantenimiento" {{ old('cargo') == 'Mantenimiento' ? 'selected' : '' }}>Mantenimiento</option>
                                    <option value="Administrador" {{ old('cargo') == 'Administrador' ? 'selected' : '' }}>Administrador</option>
                                </select>
                                @error('cargo')
                                    <div class="text-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="input-group">
                                <div class="input-label">
                                    <i class="fas fa-phone"></i>
                                    Teléfono
                                </div>
                                <input type="text"
                                       name="telefono"
                                       id="telefono"
                                       class="form-input @error('telefono') is-invalid @enderror"
                                       value="{{ old('telefono') }}"
                                       placeholder="Ej: 7777-7777">
                                @error('telefono')
                                    <div class="text-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="input-group">
                                <div class="input-label">
                                    <i class="fas fa-map-marker-alt"></i>
                                    Dirección
                                </div>
                                <input type="text"
                                       name="direccion"
                                       id="direccion"
                                       class="form-input @error('direccion') is-invalid @enderror"
                                       value="{{ old('direccion') }}"
                                       placeholder="Ej: Colonia Centro, Calle Principal">
                                @error('direccion')
                                    <div class="text-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="section-divider">

                <!-- Información Laboral -->
                <div class="info-section">
                    <div class="info-section-title">
                        <i class="fas fa-building"></i>
                        Información Laboral
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="input-group">
                                <div class="input-label required">
                                    <i class="fas fa-calendar-alt"></i>
                                    Fecha de Ingreso
                                </div>
                                <input type="date"
                                       name="fecha_ingreso"
                                       id="fecha_ingreso"
                                       class="form-input @error('fecha_ingreso') is-invalid @enderror"
                                       value="{{ old('fecha_ingreso', date('Y-m-d')) }}"
                                       required>
                                @error('fecha_ingreso')
                                    <div class="text-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="input-group">
                                <div class="input-label">
                                    <i class="fas fa-dollar-sign"></i>
                                    Salario
                                </div>
                                <input type="number"
                                       name="salario"
                                       id="salario"
                                       class="form-input @error('salario') is-invalid @enderror"
                                       value="{{ old('salario') }}"
                                       placeholder="Ej: 400.00"
                                       step="0.01"
                                       min="0">
                                @error('salario')
                                    <div class="text-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="input-group">
                                <div class="d-flex align-items-center p-3" style="background: #f0f9ff; border: 2px solid #3b82f6; border-radius: 10px;">
                                    <div class="form-check form-switch" style="flex-grow: 1;">
                                        <input class="form-check-input"
                                               type="checkbox"
                                               name="acceso_sistema"
                                               id="acceso_sistema"
                                               value="1"
                                               {{ old('acceso_sistema') ? 'checked' : '' }}
                                               style="width: 3rem; height: 1.5rem; cursor: pointer;">
                                        <label class="form-check-label" for="acceso_sistema" style="font-weight: 700; font-size: 1rem; color: #1e40af; margin-left: 0.5rem; cursor: pointer;">
                                            <i class="fas fa-key mr-2"></i>
                                            Acceder al Sistema
                                        </label>
                                        <p class="text-muted mb-0 mt-1" style="font-size: 0.85rem; margin-left: 3.5rem;">
                                            <i class="fas fa-info-circle mr-1"></i>
                                            Permite al empleado iniciar sesión en el sistema
                                        </p>
                                    </div>
                                </div>
                                @error('acceso_sistema')
                                    <div class="text-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sección Licencia de Conducir (solo para Chofer) -->
                <div class="licencia-section" id="licenciaSection">
                    <div class="licencia-section-title">
                        <i class="fas fa-car" style="color: #f59e0b;"></i>
                        Licencia de Conducir
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="input-group">
                                <div class="input-label">
                                    <i class="fas fa-id-badge"></i>
                                    Foto de Licencia de Conducir
                                </div>
                                <div class="custom-file-upload" style="position: relative;">
                                    <input type="file"
                                           name="foto_licencia"
                                           id="foto_licencia"
                                           class="form-input @error('foto_licencia') is-invalid @enderror"
                                           accept="image/*"
                                           onchange="previewLicencia(this)"
                                           style="padding: 0.5rem;">
                                    <small class="text-muted d-block mt-2">
                                        <i class="fas fa-info-circle"></i>
                                        Formatos: JPG, PNG, GIF. Max: 5MB
                                    </small>
                                    @error('foto_licencia')
                                        <div class="text-danger mt-2">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div id="licenciaPreview" style="display: none; margin-top: 1rem;">
                                    <p class="text-sm font-semibold mb-2" style="color: #92400e;">
                                        <i class="fas fa-eye"></i> Vista previa:
                                    </p>
                                    <img id="previewLicencia" src="" alt="Vista previa licencia"
                                         style="max-width: 250px; max-height: 150px; border-radius: 10px; border: 3px solid #f59e0b; box-shadow: 0 4px 10px rgba(245, 158, 11, 0.3);">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="input-group">
                                <div class="input-label">
                                    <i class="fas fa-camera"></i>
                                    Foto del ID / DUI
                                </div>
                                <div class="custom-file-upload" style="position: relative;">
                                    <input type="file"
                                           name="foto_id_chofer"
                                           id="foto_id_chofer"
                                           class="form-input @error('foto_id_chofer') is-invalid @enderror"
                                           accept="image/*"
                                           onchange="previewIdChofer(this)"
                                           style="padding: 0.5rem;">
                                    <small class="text-muted d-block mt-2">
                                        <i class="fas fa-info-circle"></i>
                                        Formatos: JPG, PNG, GIF. Max: 5MB
                                    </small>
                                    @error('foto_id_chofer')
                                        <div class="text-danger mt-2">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div id="idChoferPreview" style="display: none; margin-top: 1rem;">
                                    <p class="text-sm font-semibold mb-2" style="color: #92400e;">
                                        <i class="fas fa-eye"></i> Vista previa:
                                    </p>
                                    <img id="previewIdChofer" src="" alt="Vista previa ID"
                                         style="max-width: 250px; max-height: 150px; border-radius: 10px; border: 3px solid #f59e0b; box-shadow: 0 4px 10px rgba(245, 158, 11, 0.3);">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Documento de Identificación -->
                <div class="info-section">
                    <div class="info-section-title">
                        <i class="fas fa-id-card"></i>
                        Documento de Identificación / Garantía
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="input-group">
                                <div class="input-label">
                                    <i class="fas fa-camera"></i>
                                    Foto del Documento (DUI, Pasaporte, Libreta Militar, etc.)
                                </div>
                                <div class="custom-file-upload" style="position: relative;">
                                    <input type="file"
                                           name="foto_documento"
                                           id="foto_documento"
                                           class="form-input @error('foto_documento') is-invalid @enderror"
                                           accept="image/*"
                                           onchange="previewImage(this)"
                                           style="padding: 0.5rem;">
                                    <small class="text-muted d-block mt-2">
                                        <i class="fas fa-info-circle"></i>
                                        Formatos permitidos: JPG, PNG, GIF. Tamaño máximo: 5MB
                                    </small>
                                    @error('foto_documento')
                                        <div class="text-danger mt-2">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Preview de imagen -->
                                <div id="imagePreview" style="display: none; margin-top: 1rem;">
                                    <p class="text-sm font-semibold mb-2" style="color: #1e40af;">
                                        <i class="fas fa-eye"></i> Vista previa:
                                    </p>
                                    <img id="preview" src="" alt="Vista previa"
                                         style="max-width: 300px; max-height: 200px; border-radius: 10px; border: 3px solid #3b82f6; box-shadow: 0 4px 10px rgba(59, 130, 246, 0.3);">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Observaciones -->
                <div class="obs-section">
                    <label>
                        <i class="fas fa-sticky-note"></i>
                        Observaciones
                    </label>
                    <textarea name="observaciones"
                              id="observaciones"
                              class="form-textarea @error('observaciones') is-invalid @enderror"
                              placeholder="Ingrese cualquier observación relevante sobre el empleado...">{{ old('observaciones') }}</textarea>
                    @error('observaciones')
                        <div class="text-danger mt-2">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Botones -->
                <div class="d-flex justify-content-end gap-3 mt-4">
                    <a href="{{ route('control.asistencia-semanal.registro-rapido') }}" class="btn-colegial btn-cancel">
                        <i class="fas fa-times mr-2"></i>
                        Cancelar
                    </a>
                    <button type="submit" class="btn-colegial btn-save">
                        <i class="fas fa-save mr-2"></i>
                        Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Toggle sección de licencia según cargo
function toggleLicenciaSection(cargo) {
    if (cargo === 'Chofer') {
        $('#licenciaSection').slideDown();
    } else {
        $('#licenciaSection').slideUp();
    }
}

// Vista previa de imagen documento
function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            $('#preview').attr('src', e.target.result);
            $('#imagePreview').fadeIn();
        }
        reader.readAsDataURL(input.files[0]);
    } else {
        $('#imagePreview').fadeOut();
    }
}

// Vista previa de licencia
function previewLicencia(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            $('#previewLicencia').attr('src', e.target.result);
            $('#licenciaPreview').fadeIn();
        }
        reader.readAsDataURL(input.files[0]);
    } else {
        $('#licenciaPreview').fadeOut();
    }
}

// Vista previa de ID chofer
function previewIdChofer(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            $('#previewIdChofer').attr('src', e.target.result);
            $('#idChoferPreview').fadeIn();
        }
        reader.readAsDataURL(input.files[0]);
    } else {
        $('#idChoferPreview').fadeOut();
    }
}

$(document).ready(function() {
    // Verificar si ya hay cargo seleccionado (para old values)
    const cargoActual = $('#cargo').val();
    if (cargoActual === 'Chofer') {
        $('#licenciaSection').show();
    }

    // Atajos de teclado
    $(document).on('keydown', function(e) {
        if (e.ctrlKey && e.key === 's') {
            e.preventDefault();
            $('#empleadoForm').submit();
        }
        if (e.key === 'Escape') {
            window.location.href = "{{ route('control.asistencia-semanal.registro-rapido') }}";
        }
    });

    // Animación al enviar
    $('#empleadoForm').on('submit', function() {
        const btn = $(this).find('button[type="submit"]');
        btn.html('<i class="fas fa-spinner fa-spin mr-2"></i>Guardando...').prop('disabled', true);
    });
});
</script>
@endpush
