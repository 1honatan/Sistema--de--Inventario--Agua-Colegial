# EmpleadoController - Documentación Detallada

**Ubicación**: `app/Http/Controllers/Control/EmpleadoController.php`

## Propósito General
Este controlador gestiona el CRUD (Crear, Leer, Actualizar, Eliminar) de empleados/personal, incluyendo la gestión de sus accesos al sistema, carga de fotografías de licencia de conducir y creación automática de usuarios.

---

## Línea por Línea

### Líneas 1-8: Declaración de namespace e imports
```php
<?php

namespace App\Http\Controllers\Control;

use App\Http\Controllers\Controller;
use App\Models\Personal;
use Illuminate\Http\Request;
```

**¿Qué hace?**
- **Línea 3**: Define el namespace del controlador en la carpeta Control
- **Línea 5**: Importa la clase base Controller de Laravel
- **Línea 6**: Importa el modelo Personal para interactuar con la tabla de empleados
- **Línea 7**: Importa Request para manejar peticiones HTTP

**¿De dónde sale?**
- Laravel framework proporciona estas clases base

---

### Líneas 9-10: Declaración de la clase
```php
class EmpleadoController extends Controller
{
```

**¿Qué hace?**
- Define la clase EmpleadoController que hereda de Controller
- Obtiene todas las funcionalidades de Laravel como middleware, helpers, etc.

---

### Líneas 14-17: Método `create()` - Mostrar formulario de creación
```php
public function create()
{
    return view('control.empleados.create');
}
```

**¿Qué hace?**
- Muestra el formulario HTML para registrar un nuevo empleado
- Retorna la vista ubicada en `resources/views/control/empleados/create.blade.php`

**¿De dónde sale?**
- Petición GET a la ruta de creación de empleados

**¿Qué hacer?**
- Acceder a `/control/empleados/create` para ver el formulario

---

### Líneas 22-78: Método `store()` - Guardar nuevo empleado
```php
public function store(Request $request)
{
    // Líneas 24-36: Validación de datos
    $validated = $request->validate([
        'nombre_completo' => 'required|string|max:255',
        'cargo' => 'required|string|max:255',
        'telefono' => 'nullable|string|max:20',
        'direccion' => 'nullable|string|max:255',
        'fecha_ingreso' => 'required|date',
        'salario' => 'nullable|numeric|min:0',
        'observaciones' => 'nullable|string',
        'foto_licencia' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        'acceso_sistema' => 'nullable|boolean',
        'email_acceso' => 'nullable|required_if:acceso_sistema,1|email|unique:usuarios,email',
        'password_acceso' => 'nullable|min:6',
    ]);
```

**¿Qué hace cada validación?**
- **nombre_completo**: Requerido, texto, máximo 255 caracteres
- **cargo**: Requerido, texto, máximo 255 caracteres (ej: "Chofer", "Operador")
- **telefono**: Opcional, texto, máximo 20 caracteres
- **direccion**: Opcional, texto, máximo 255 caracteres
- **fecha_ingreso**: Requerido, debe ser fecha válida
- **salario**: Opcional, número decimal, mínimo 0 (no negativo)
- **observaciones**: Opcional, texto largo sin límite
- **foto_licencia**: Opcional, debe ser imagen (jpeg,png,jpg,gif), máximo 5MB (5120 KB)
- **acceso_sistema**: Opcional, valor booleano (true/false)
- **email_acceso**: Opcional pero REQUERIDO si acceso_sistema es true, debe ser email válido y único en tabla usuarios
- **password_acceso**: Opcional, mínimo 6 caracteres

**¿De dónde sale?**
- Datos del formulario POST enviado desde la vista create

```php
    // Líneas 38-40: Asignar valores por defecto
    $validated['estado'] = 'activo';
    $validated['area'] = 'Producción';
    $validated['email'] = strtolower(str_replace(' ', '.', $validated['nombre_completo'])) . '@aguacolegial.com';
```

**¿Qué hace?**
- **Línea 38**: Todos los empleados nuevos inician con estado 'activo'
- **Línea 39**: Todos se asignan por defecto al área 'Producción'
- **Línea 40**: Genera email automático:
  - Toma el nombre completo
  - Reemplaza espacios por puntos
  - Convierte a minúsculas
  - Agrega @aguacolegial.com
  - Ejemplo: "Juan Carlos Lopez" → "juan.carlos.lopez@aguacolegial.com"

**¿De dónde sale?**
- Lógica de negocio del sistema
- Funciones PHP: `strtolower()`, `str_replace()`

```php
    // Líneas 42-48: Procesar imagen de licencia
    if ($request->hasFile('foto_licencia')) {
        $imagen = $request->file('foto_licencia');
        $nombreArchivo = 'lic_' . time() . '_' . uniqid() . '.' . $imagen->getClientOriginalExtension();
        $imagen->move(public_path('uploads/licencias'), $nombreArchivo);
        $validated['foto_licencia'] = 'uploads/licencias/' . $nombreArchivo;
    }
```

**¿Qué hace?**
- **Línea 43**: Verifica si se subió un archivo con nombre 'foto_licencia'
- **Línea 44**: Obtiene el archivo subido
- **Línea 45**: Genera nombre único:
  - Prefijo "lic_"
  - `time()`: timestamp actual (ej: 1733097600)
  - `uniqid()`: ID único (ej: 65abc123def)
  - Extensión original del archivo (ej: .jpg)
  - Ejemplo completo: "lic_1733097600_65abc123def.jpg"
- **Línea 46**: Mueve archivo a carpeta `public/uploads/licencias/`
- **Línea 47**: Guarda la ruta relativa en la base de datos

**¿De dónde sale?**
- Input tipo file del formulario HTML
- `public_path()`: helper de Laravel que retorna ruta a carpeta public

**¿Para qué sirve?**
- Almacenar foto de licencia de conducir de choferes
- Nombres únicos previenen sobrescritura de archivos

```php
    // Líneas 51-52: Guardar checkbox de acceso_sistema
    $validated['acceso_sistema'] = $request->has('acceso_sistema');
```

**¿Qué hace?**
- **Línea 52**: Convierte checkbox a booleano
  - Si está marcado: true (1)
  - Si no está marcado: false (0)
- `$request->has()`: Verifica si el parámetro existe en la petición

**¿De dónde sale?**
- Checkboxes HTML no envían valor cuando están desmarcados
- Esta línea maneja ese comportamiento

```php
    // Línea 54: Crear registro de empleado
    $empleado = Personal::create($validated);
```

**¿Qué hace?**
- Inserta nuevo registro en tabla `personal` con todos los datos validados
- Retorna el objeto del empleado creado con su ID

**¿De dónde sale?**
- Eloquent ORM de Laravel

```php
    // Líneas 56-74: Crear usuario si tiene acceso al sistema
    if ($request->has('acceso_sistema') && $request->email_acceso) {
        // Líneas 58-63: Buscar rol de producción
        $rolProduccion = \App\Models\Rol::where('nombre', 'produccion')->first();

        if (!$rolProduccion) {
            return redirect()->back()->with('error', 'El rol "produccion" no existe en el sistema.');
        }

        // Líneas 65-73: Crear usuario
        \App\Models\Usuario::create([
            'nombre' => $empleado->nombre_completo,
            'email' => $request->email_acceso,
            'password' => $request->password_acceso ?? 'password123',
            'id_personal' => $empleado->id,
            'id_rol' => $rolProduccion->id,
            'estado' => 'activo',
        ]);
    }
```

**¿Qué hace?**
- **Línea 57**: Verifica si se marcó "acceso_sistema" Y se proporcionó email
- **Líneas 59-63**: Busca el rol "produccion" en tabla roles
  - Si no existe, retorna error (seguridad)
- **Líneas 66-73**: Crea usuario en tabla `usuarios` con:
  - **nombre**: Nombre completo del empleado
  - **email**: Email de acceso ingresado
  - **password**: Contraseña ingresada o "password123" por defecto
  - **id_personal**: ID del empleado (relación)
  - **id_rol**: ID del rol producción
  - **estado**: 'activo'
- El operador `??` (null coalescing) usa valor por defecto si password_acceso es null

**¿De dónde sale?**
- Lógica de negocio: empleados de producción pueden tener acceso al sistema
- Tabla `roles` debe tener un registro con nombre='produccion'

**¿Para qué sirve?**
- Permite que empleados puedan iniciar sesión en el sistema
- Relaciona usuario con empleado mediante id_personal

```php
    // Líneas 76-78: Redirigir con mensaje
    return redirect()->route('control.asistencia-semanal.registro-rapido')
        ->with('success', 'Empleado registrado exitosamente.');
}
```

**¿Qué hace?**
- Redirige a la vista de registro rápido de asistencias
- Muestra mensaje flash de éxito

**¿De dónde sale?**
- Helper `redirect()` de Laravel
- Sesión flash para mensajes temporales

---

### Líneas 83-87: Método `show()` - Ver detalles de empleado
```php
public function show($id)
{
    $empleado = Personal::findOrFail($id);
    return view('control.empleados.show', compact('empleado'));
}
```

**¿Qué hace?**
- **Línea 85**: Busca empleado por ID, error 404 si no existe
- **Línea 86**: Muestra vista de detalles con datos del empleado

**¿De dónde sale?**
- `$id` viene de la URL (ej: `/empleados/5`)

---

### Líneas 92-96: Método `edit()` - Formulario de edición
```php
public function edit($id)
{
    $empleado = Personal::findOrFail($id);
    return view('control.empleados.edit', compact('empleado'));
}
```

**¿Qué hace?**
- Busca empleado por ID
- Muestra formulario de edición prellenado con datos actuales

**¿De dónde sale?**
- Petición GET a ruta de edición (ej: `/empleados/5/edit`)

---

### Líneas 101-179: Método `update()` - Actualizar empleado
```php
public function update(Request $request, $id)
{
    // Línea 103: Buscar empleado a actualizar
    $empleado = Personal::findOrFail($id);

    // Líneas 105-117: Validación (similar a store)
    $validated = $request->validate([
        'nombre_completo' => 'required|string|max:255',
        'cargo' => 'required|string|max:255',
        'telefono' => 'nullable|string|max:20',
        'direccion' => 'nullable|string|max:255',
        'fecha_ingreso' => 'required|date',
        'salario' => 'nullable|numeric|min:0',
        'observaciones' => 'nullable|string',
        'foto_licencia' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        'acceso_sistema' => 'nullable|boolean',
        'email_acceso' => 'nullable|required_if:acceso_sistema,1|email|unique:usuarios,email,' . ($empleado->usuario->id ?? 'NULL'),
        'password_acceso' => 'nullable|min:6',
    ]);
```

**¿Qué hace?**
- Similar a store() pero con diferencia en línea 115:
  - `unique:usuarios,email,' . ($empleado->usuario->id ?? 'NULL')`
  - Permite que el email sea el mismo que ya tenía (excepto su propio ID)
  - Evita error de duplicado al actualizar sin cambiar email
  - Si no tiene usuario, usa 'NULL' para evitar errores

**¿De dónde sale?**
- Validación unique con excepción de ID propio

```php
    // Líneas 119-120: Actualizar email automático
    $validated['email'] = strtolower(str_replace(' ', '.', $validated['nombre_completo'])) . '@aguacolegial.com';
```

**¿Qué hace?**
- Regenera email si cambió el nombre
- Mismo formato que en create()

```php
    // Líneas 122-128: Procesar nueva imagen si se subió
    if ($request->hasFile('foto_licencia')) {
        $imagen = $request->file('foto_licencia');
        $nombreArchivo = 'lic_' . time() . '_' . uniqid() . '.' . $imagen->getClientOriginalExtension();
        $imagen->move(public_path('uploads/licencias'), $nombreArchivo);
        $validated['foto_licencia'] = 'uploads/licencias/' . $nombreArchivo;
    }
```

**¿Qué hace?**
- Solo actualiza foto si se subió una nueva
- Si no se sube, mantiene la foto anterior
- Mismo proceso que en create()

**Nota de mejora**: Sería bueno eliminar la foto anterior del servidor para no acumular archivos

```php
    // Líneas 131-134: Actualizar empleado
    $validated['acceso_sistema'] = $request->has('acceso_sistema');
    $empleado->update($validated);
```

**¿Qué hace?**
- Actualiza campo acceso_sistema según checkbox
- Actualiza todos los campos del empleado en la base de datos

```php
    // Líneas 136-168: Gestionar usuario asociado
    if ($request->has('acceso_sistema') && $request->email_acceso) {
        // Líneas 138-143: Buscar rol
        $rolProduccion = \App\Models\Rol::where('nombre', 'produccion')->first();

        if (!$rolProduccion) {
            return redirect()->back()->with('error', 'El rol "produccion" no existe en el sistema.');
        }

        // Líneas 145-146: Buscar usuario existente
        $usuarioExistente = \App\Models\Usuario::where('id_personal', $empleado->id)->first();

        if ($usuarioExistente) {
            // Líneas 149-157: Actualizar usuario existente
            $usuarioExistente->email = $request->email_acceso;
            $usuarioExistente->nombre = $empleado->nombre_completo;

            if ($request->filled('password_acceso')) {
                $usuarioExistente->password = $request->password_acceso;
            }

            $usuarioExistente->save();
```

**¿Qué hace?**
- **Líneas 137-143**: Si se activó acceso_sistema:
  - Busca rol producción (validación)
- **Línea 146**: Busca si el empleado ya tiene usuario creado
- **Líneas 148-157**: Si existe usuario:
  - Actualiza email con el nuevo valor
  - Actualiza nombre por si cambió
  - **Línea 153**: Solo actualiza password si se ingresó uno nuevo (`filled()` verifica que no esté vacío)
  - Guarda cambios

**¿De dónde sale?**
- `filled()`: helper de Laravel que verifica que el campo tenga valor

```php
        } else {
            // Líneas 159-167: Crear nuevo usuario
            $user = \App\Models\Usuario::create([
                'nombre' => $empleado->nombre_completo,
                'email' => $request->email_acceso,
                'password' => $request->password_acceso ?? 'password123',
                'id_personal' => $empleado->id,
                'id_rol' => $rolProduccion->id,
                'estado' => 'activo',
            ]);
        }
```

**¿Qué hace?**
- Si NO existe usuario (se activó acceso ahora):
  - Crea nuevo usuario
  - Mismo proceso que en store()

**¿Para qué sirve?**
- Permite activar acceso al sistema a empleados que antes no lo tenían

```php
    } elseif (!$request->has('acceso_sistema')) {
        // Líneas 170-174: Desactivar acceso
        $usuarioExistente = \App\Models\Usuario::where('id_personal', $empleado->id)->first();
        if ($usuarioExistente) {
            $usuarioExistente->delete();
        }
    }
```

**¿Qué hace?**
- Si se DESMARCÓ el checkbox de acceso_sistema:
  - Busca usuario asociado
  - Si existe, lo ELIMINA de la base de datos
- Esto revoca el acceso al sistema

**¿De dónde sale?**
- Lógica de negocio: empleados sin acceso no deben tener usuario

**Importante**: Esto borra permanentemente el usuario. Una alternativa sería cambiar estado a 'inactivo'

```php
    // Líneas 177-179: Redirigir
    return redirect()->route('control.asistencia-semanal.registro-rapido')
        ->with('success', 'Empleado actualizado exitosamente.');
}
```

**¿Qué hace?**
- Redirige a registro rápido con mensaje de éxito

---

### Líneas 184-196: Método `destroy()` - Eliminar empleado
```php
public function destroy($id)
{
    $personal = Personal::findOrFail($id);

    $nombre = $personal->nombre_completo;

    // Líneas 190-192: Soft delete - cambiar estado
    $personal->update(['estado' => 'inactivo']);

    return redirect()->route('control.asistencia-semanal.registro-rapido')
        ->with('success', "Empleado '{$nombre}' eliminado exitosamente.");
}
```

**¿Qué hace?**
- **Línea 186**: Busca empleado por ID
- **Línea 188**: Guarda nombre para el mensaje
- **Línea 192**: NO elimina realmente (soft delete)
  - Solo cambia estado a 'inactivo'
  - Preserva histórico de asistencias y otros registros
  - Empleados inactivos no aparecen en selects
- **Línea 194**: Redirige con mensaje (dice "eliminado" pero realmente está inactivo)

**¿De dónde sale?**
- Patrón de diseño: Soft Delete
- Evita problemas con integridad referencial (foreign keys)

**¿Para qué sirve?**
- Mantener histórico completo
- Evitar que aparezcan en listados activos
- Poder reactivarlos si vuelven a trabajar

---

## Resumen de Funcionalidades

### Operaciones CRUD:
1. **Crear empleado**: `create()` + `store()` - Formulario y guardado
2. **Ver empleado**: `show()` - Detalles de un empleado
3. **Editar empleado**: `edit()` + `update()` - Modificar datos
4. **Eliminar empleado**: `destroy()` - Desactivar (soft delete)

### Características Especiales:
5. **Email automático**: Genera email @aguacolegial.com basado en nombre
6. **Foto de licencia**: Permite subir imagen de licencia de conducir
7. **Acceso al sistema**: Crea/actualiza/elimina usuario asociado
8. **Soft delete**: Empleados inactivos en lugar de eliminados

---

## Tablas de Base de Datos Involucradas

### 1. **personal** (principal)
Campos principales:
- `id`: Identificador único
- `nombre_completo`: Nombre del empleado
- `cargo`: Puesto (Chofer, Operador, etc.)
- `telefono`: Teléfono de contacto
- `direccion`: Dirección residencial
- `fecha_ingreso`: Fecha que empezó a trabajar
- `salario`: Salario mensual
- `email`: Email automático @aguacolegial.com
- `area`: Área de trabajo (default: "Producción")
- `estado`: 'activo' o 'inactivo'
- `observaciones`: Notas adicionales
- `foto_licencia`: Ruta a imagen de licencia
- `acceso_sistema`: Boolean, si tiene acceso al sistema
- `created_at`, `updated_at`: Timestamps automáticos

### 2. **usuarios** (relacionada)
Campos principales:
- `id`: Identificador único
- `nombre`: Nombre del usuario
- `email`: Email de acceso único
- `password`: Contraseña encriptada
- `id_personal`: Foreign key a tabla personal
- `id_rol`: Foreign key a tabla roles
- `estado`: 'activo' o 'inactivo'
- `created_at`, `updated_at`: Timestamps

### 3. **roles** (relacionada)
Campos principales:
- `id`: Identificador único
- `nombre`: Nombre del rol (ej: 'produccion', 'admin')
- `permisos`: JSON con permisos del rol

---

## Relaciones de Modelos

En el modelo **Personal**:
```php
public function usuario()
{
    return $this->hasOne(Usuario::class, 'id_personal');
}
```

En el modelo **Usuario**:
```php
public function personal()
{
    return $this->belongsTo(Personal::class, 'id_personal');
}

public function rol()
{
    return $this->belongsTo(Rol::class, 'id_rol');
}
```

---

## Rutas Web Necesarias

```php
Route::prefix('control/empleados')->group(function () {
    Route::get('/create', [EmpleadoController::class, 'create'])->name('control.empleados.create');
    Route::post('/', [EmpleadoController::class, 'store'])->name('control.empleados.store');
    Route::get('/{id}', [EmpleadoController::class, 'show'])->name('control.empleados.show');
    Route::get('/{id}/edit', [EmpleadoController::class, 'edit'])->name('control.empleados.edit');
    Route::put('/{id}', [EmpleadoController::class, 'update'])->name('control.empleados.update');
    Route::delete('/{id}', [EmpleadoController::class, 'destroy'])->name('control.empleados.destroy');
});
```

---

## Flujo de Trabajo Típico

### Escenario 1: Registrar nuevo chofer
1. Administrador accede a `/control/empleados/create` → método `create()`
2. Llena formulario:
   - Nombre: "Juan Pérez"
   - Cargo: "Chofer"
   - Fecha ingreso: "2025-12-01"
   - Teléfono: "555-1234"
   - Sube foto de licencia
   - Marca "Dar acceso al sistema"
   - Email acceso: "juan.perez@empresa.com"
   - Password: "juan123456"
3. Submit → método `store()`
4. Sistema:
   - Valida datos
   - Genera email automático: juan.perez@aguacolegial.com
   - Guarda foto: uploads/licencias/lic_1733097600_abc123.jpg
   - Crea empleado en tabla personal
   - Busca rol 'produccion'
   - Crea usuario en tabla usuarios
5. Redirige a registro rápido con mensaje de éxito

### Escenario 2: Editar empleado y activar acceso
1. Administrador va a `/control/empleados/5/edit` → método `edit()`
2. Ve formulario con datos actuales del empleado
3. Marca checkbox "Dar acceso al sistema"
4. Ingresa email y password
5. Submit → método `update()`
6. Sistema:
   - Valida datos
   - Busca si existe usuario asociado
   - Como no existe (checkbox recién marcado), crea nuevo usuario
7. Empleado ahora puede iniciar sesión

### Escenario 3: Desactivar empleado
1. Administrador elimina empleado → método `destroy()`
2. Sistema NO borra registro
3. Cambia estado a 'inactivo'
4. Empleado ya no aparece en selects
5. Histórico de asistencias se preserva

---

## Archivos del Sistema Involucrados

### Carpetas creadas:
- `public/uploads/licencias/` - Almacena fotos de licencias

### Vistas Blade:
- `resources/views/control/empleados/create.blade.php` - Formulario crear
- `resources/views/control/empleados/edit.blade.php` - Formulario editar
- `resources/views/control/empleados/show.blade.php` - Ver detalles

---

## Seguridad Implementada

### 1. Validaciones:
- Campos requeridos verificados
- Tipos de datos validados (string, numeric, date)
- Longitudes máximas definidas
- Email único en sistema
- Formato de imagen verificado
- Tamaño máximo de archivo (5MB)

### 2. Validación condicional:
```php
'email_acceso' => 'nullable|required_if:acceso_sistema,1|email|unique:usuarios,email'
```
- Solo requiere email si se marcó acceso_sistema
- Evita crear usuarios sin email

### 3. Nombres de archivo únicos:
```php
'lic_' . time() . '_' . uniqid() . '.' . $extension
```
- Previene sobrescritura de archivos
- Evita conflictos de nombres

### 4. Soft delete:
- No elimina registros permanentemente
- Preserva integridad referencial
- Mantiene auditoría completa

### 5. Verificación de rol:
```php
if (!$rolProduccion) {
    return redirect()->back()->with('error', 'El rol "produccion" no existe');
}
```
- Valida que el rol exista antes de crear usuario
- Previene errores de foreign key

### 6. Actualización condicional de password:
```php
if ($request->filled('password_acceso')) {
    $usuarioExistente->password = $request->password_acceso;
}
```
- Solo actualiza password si se ingresó uno nuevo
- No sobrescribe con valor vacío

---

## Mejoras Futuras Sugeridas

### 1. Eliminar foto anterior al actualizar:
```php
if ($empleado->foto_licencia && file_exists(public_path($empleado->foto_licencia))) {
    unlink(public_path($empleado->foto_licencia));
}
```

### 2. Encriptación de passwords:
El modelo Usuario debe usar mutator:
```php
public function setPasswordAttribute($value)
{
    $this->attributes['password'] = bcrypt($value);
}
```

### 3. Validar formato de teléfono:
```php
'telefono' => 'nullable|regex:/^[0-9\-\+\(\)\s]+$/',
```

### 4. Soft delete de usuario en lugar de delete():
```php
$usuarioExistente->update(['estado' => 'inactivo']);
```

### 5. Upload de foto con Storage facade:
```php
$path = $request->file('foto_licencia')->store('licencias', 'public');
```
- Mejor práctica de Laravel
- Más fácil de testear

### 6. Validación de tamaño de imagen con dimensiones:
```php
'foto_licencia' => 'nullable|image|mimes:jpeg,png,jpg|max:5120|dimensions:max_width=2000,max_height=2000',
```

### 7. Transacciones de base de datos:
```php
DB::transaction(function () use ($validated, $empleado) {
    $empleado = Personal::create($validated);
    Usuario::create([...]);
});
```
- Asegura que ambas operaciones (empleado + usuario) se completen o se revierten

### 8. Middleware de autorización:
```php
public function __construct()
{
    $this->middleware('can:gestionar-empleados');
}
```

### 9. Auditoría de cambios:
- Guardar quién editó/eliminó registros
- Timestamp de última modificación
- Log de cambios importantes

### 10. Reactivar empleados:
Agregar método para cambiar estado de 'inactivo' a 'activo'

---

## Dependencias y Requisitos

- **Laravel 8+**: Framework base
- **Eloquent ORM**: Para manejo de base de datos
- **Validación de Laravel**: Sistema de validación
- **Storage de Laravel**: Para manejo de archivos
- **GD o Imagick**: Extensiones PHP para procesar imágenes
- **MySQL/PostgreSQL**: Base de datos

---

## Errores Comunes y Soluciones

### Error 1: "The email has already been taken"
**Causa**: Email duplicado en tabla usuarios
**Solución**: Verificar que el email sea único o usar uno diferente

### Error 2: "The uploads/licencias directory does not exist"
**Causa**: Carpeta no creada
**Solución**: Crear manualmente o con:
```bash
mkdir -p public/uploads/licencias
chmod 775 public/uploads/licencias
```

### Error 3: "El rol 'produccion' no existe"
**Causa**: Falta seeder de roles
**Solución**: Ejecutar seeder:
```bash
php artisan db:seed --class=RolesSeeder
```

### Error 4: Foto no se sube
**Causa**: Límites de PHP
**Solución**: En php.ini aumentar:
```ini
upload_max_filesize = 10M
post_max_size = 10M
```

---

## Testing Recomendado

### Test 1: Crear empleado sin acceso:
```php
$response = $this->post('/control/empleados', [
    'nombre_completo' => 'Test Usuario',
    'cargo' => 'Operador',
    'fecha_ingreso' => '2025-12-01',
]);
$response->assertRedirect();
$this->assertDatabaseHas('personal', ['nombre_completo' => 'Test Usuario']);
```

### Test 2: Crear empleado con acceso:
```php
$response = $this->post('/control/empleados', [
    'nombre_completo' => 'Test Usuario',
    'cargo' => 'Chofer',
    'fecha_ingreso' => '2025-12-01',
    'acceso_sistema' => true,
    'email_acceso' => 'test@test.com',
    'password_acceso' => 'password123',
]);
$this->assertDatabaseHas('usuarios', ['email' => 'test@test.com']);
```

### Test 3: Desactivar empleado:
```php
$empleado = Personal::factory()->create();
$response = $this->delete("/control/empleados/{$empleado->id}");
$this->assertDatabaseHas('personal', [
    'id' => $empleado->id,
    'estado' => 'inactivo'
]);
```

---

## Conclusión

Este controlador es fundamental para la gestión de recursos humanos del sistema, permitiendo:
- Registro completo de empleados
- Gestión de accesos al sistema
- Almacenamiento de documentos (licencias)
- Soft delete para preservar histórico
- Generación automática de emails corporativos

Es el punto de entrada para todo el personal que luego se usará en:
- Control de asistencias
- Asignación de tareas
- Mantenimiento de equipos
- Salidas de productos
- Producción diaria
