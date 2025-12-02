# AsistenciaSemanalController - Documentación Detallada

**Ubicación**: `app/Http/Controllers/Control/AsistenciaSemanalController.php`

## Propósito General
Este controlador gestiona el registro y control de asistencias del personal de forma semanal, permitiendo registrar entradas, salidas, y generar vistas de control estilo cuaderno de asistencias.

---

## Línea por Línea

### Líneas 1-11: Declaración de namespace e imports
```php
<?php

namespace App\Http\Controllers\Control;

use App\Http\Controllers\Controller;
use App\Models\AsistenciaSemanal;
use App\Models\Personal;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
```

**¿Qué hace?**
- **Línea 1**: Etiqueta de apertura PHP
- **Línea 3**: Define que este controlador pertenece al namespace `App\Http\Controllers\Control`
- **Línea 5**: Importa la clase base `Controller` de Laravel
- **Línea 6**: Importa el modelo `AsistenciaSemanal` para interactuar con la tabla de asistencias semanales
- **Línea 7**: Importa el modelo `Personal` para obtener datos de empleados
- **Línea 8**: Importa `Request` para manejar peticiones HTTP
- **Línea 9**: Importa `Carbon` para manejo avanzado de fechas y horas
- **Línea 10**: Importa `DB` facade para consultas directas a base de datos (aunque no se usa en este código)

**¿De dónde sale?**
- Laravel framework proporciona estas clases base
- Carbon es una librería popular de PHP para fechas

---

### Líneas 12-13: Declaración de la clase
```php
class AsistenciaSemanalController extends Controller
{
```

**¿Qué hace?**
- Declara la clase `AsistenciaSemanalController` que extiende de `Controller`
- Al heredar de Controller obtiene funcionalidades como middleware, validación, etc.

**¿De dónde sale?**
- Patrón MVC de Laravel

---

### Líneas 17-59: Método `index()` - Vista principal de asistencias
```php
public function index(Request $request)
{
    // Línea 20-22: Obtener fecha seleccionada o usar la actual
    $fechaSeleccionada = $request->get('semana')
        ? Carbon::parse($request->get('semana'))
        : Carbon::now();
```

**¿Qué hace?**
- **Línea 17**: Define método público que recibe un objeto Request
- **Líneas 20-22**: Verifica si hay un parámetro 'semana' en la URL
  - Si existe: convierte esa fecha string a objeto Carbon
  - Si no existe: usa la fecha actual del sistema

**¿De dónde sale?**
- Request viene de la petición HTTP del usuario
- Carbon::now() obtiene la fecha/hora actual del servidor

**¿Qué hacer?**
- Para usar esta funcionalidad, el usuario puede pasar `?semana=2025-12-01` en la URL

```php
    // Línea 24-25: Calcular inicio y fin de semana
    $inicioSemana = $fechaSeleccionada->copy()->startOfWeek();
    $finSemana = $fechaSeleccionada->copy()->endOfWeek();
```

**¿Qué hace?**
- **Línea 24**: Crea una copia de la fecha y obtiene el lunes de esa semana
- **Línea 25**: Crea otra copia y obtiene el domingo de esa semana
- `.copy()` es importante para no modificar la fecha original

**¿De dónde sale?**
- Métodos de Carbon para manipulación de fechas

```php
    // Líneas 28-30: Obtener empleados activos
    $personal = Personal::where('estado', 'activo')
        ->orderBy('nombre_completo')
        ->get();
```

**¿Qué hace?**
- **Línea 28**: Query al modelo Personal
- Filtra solo empleados con estado 'activo'
- Ordena alfabéticamente por nombre_completo
- `.get()` ejecuta la consulta y retorna colección

**¿De dónde sale?**
- Eloquent ORM de Laravel (sistema de base de datos)
- Tabla `personal` en la base de datos

```php
    // Líneas 33-38: Obtener asistencias de la semana
    $asistencias = AsistenciaSemanal::with('personal')
        ->whereBetween('fecha', [$inicioSemana, $finSemana])
        ->get()
        ->groupBy(function ($item) {
            return $item->personal_id . '_' . $item->fecha->format('Y-m-d');
        });
```

**¿Qué hace?**
- **Línea 33**: Query al modelo AsistenciaSemanal
- `.with('personal')`: Carga la relación con Personal (eager loading - evita N+1 queries)
- **Línea 34**: Filtra registros entre inicio y fin de semana
- **Línea 35**: Ejecuta consulta
- **Líneas 36-37**: Agrupa resultados por una clave única: "id_personal_fecha"
  - Ejemplo: "5_2025-12-01" para el empleado ID 5 en fecha 1 de diciembre

**¿De dónde sale?**
- Tabla `asistencias_semanal` en la base de datos
- Relación definida en el modelo

```php
    // Líneas 41-49: Crear array de días de la semana
    $diasSemana = [];
    for ($i = 0; $i < 7; $i++) {
        $dia = $inicioSemana->copy()->addDays($i);
        $diasSemana[] = [
            'fecha' => $dia,
            'nombre' => AsistenciaSemanal::obtenerDiaSemana($dia),
            'numero' => $dia->day,
        ];
    }
```

**¿Qué hace?**
- **Línea 41**: Inicializa array vacío
- **Línea 42**: Loop de 0 a 6 (7 días)
- **Línea 43**: Para cada iteración, suma i días al inicio de semana
- **Líneas 44-48**: Crea un array con:
  - `fecha`: objeto Carbon del día
  - `nombre`: nombre del día (Lunes, Martes, etc.) - método del modelo
  - `numero`: número del día del mes (1-31)

**¿De dónde sale?**
- Cálculo matemático de fechas
- Método estático `obtenerDiaSemana()` del modelo AsistenciaSemanal

```php
    // Líneas 51-58: Retornar vista con datos
    return view('control.asistencia-semanal.index', compact(
        'personal',
        'asistencias',
        'diasSemana',
        'inicioSemana',
        'finSemana',
        'fechaSeleccionada'
    ));
}
```

**¿Qué hace?**
- Retorna la vista Blade ubicada en `resources/views/control/asistencia-semanal/index.blade.php`
- `compact()` convierte variables en array asociativo
- Todas las variables listadas estarán disponibles en la vista

**¿De dónde sale?**
- Helper `view()` de Laravel
- Las variables vienen de las consultas anteriores

---

### Líneas 64-81: Método `create()` - Formulario de registro
```php
public function create(Request $request)
{
    // Líneas 66-68: Obtener personal activo
    $personal = Personal::where('estado', 'activo')
        ->orderBy('nombre_completo')
        ->get();

    // Líneas 70-72: Obtener fecha seleccionada o actual
    $fechaSeleccionada = $request->get('fecha')
        ? Carbon::parse($request->get('fecha'))
        : Carbon::now();

    // Línea 74: Obtener ID de personal si viene por parámetro
    $personalId = $request->get('personal_id');

    // Líneas 76-80: Retornar vista del formulario
    return view('control.asistencia-semanal.create', compact(
        'personal',
        'fechaSeleccionada',
        'personalId'
    ));
}
```

**¿Qué hace?**
- Muestra el formulario para registrar nueva asistencia
- Permite preseleccionar fecha y empleado si vienen por URL
- Lista todos los empleados activos para el select

**¿De dónde sale?**
- Parámetros GET de la URL (ej: `?fecha=2025-12-01&personal_id=5`)

**¿Qué hacer?**
- Acceder a `/control/asistencia-semanal/create` para ver el formulario

---

### Líneas 86-130: Método `store()` - Guardar asistencia
```php
public function store(Request $request)
{
    // Líneas 88-106: Validación de datos
    $validated = $request->validate([
        'personal_id' => 'required|exists:personal,id',
        'fecha' => 'required|date',
        'entrada_hora' => 'required|date_format:H:i',
        'salida_hora' => 'nullable|date_format:H:i|after:entrada_hora',
        'observaciones' => 'nullable|string|max:500',
        'estado' => 'required|in:presente,ausente,permiso,tardanza',
    ], [
        // Mensajes de error personalizados
        'personal_id.required' => 'Debe seleccionar un empleado',
        'personal_id.exists' => 'El empleado seleccionado no existe',
        // ... más mensajes
    ]);
```

**¿Qué hace?**
- **Línea 88**: Valida los datos del formulario
- Reglas de validación:
  - `personal_id`: Requerido y debe existir en tabla personal
  - `fecha`: Requerida y debe ser fecha válida
  - `entrada_hora`: Requerida, formato 24 horas (HH:MM)
  - `salida_hora`: Opcional, formato HH:MM, debe ser después de entrada
  - `observaciones`: Opcional, texto máximo 500 caracteres
  - `estado`: Requerido, solo valores: presente, ausente, permiso, tardanza
- El segundo array son mensajes de error personalizados en español

**¿De dónde sale?**
- Datos del formulario POST
- Sistema de validación de Laravel

```php
    // Líneas 108-109: Calcular día de la semana
    $fecha = Carbon::parse($validated['fecha']);
    $validated['dia_semana'] = AsistenciaSemanal::obtenerDiaSemana($fecha);
```

**¿Qué hace?**
- Convierte la fecha string a objeto Carbon
- Calcula el nombre del día (Lunes, Martes, etc.) usando método del modelo
- Agrega 'dia_semana' al array de datos validados

**¿De dónde sale?**
- Método estático del modelo AsistenciaSemanal

```php
    // Líneas 111-117: Registrar quién hizo el registro
    $usuario = auth()->user();
    if ($usuario && isset($usuario->personal_id)) {
        $validated['registrado_por'] = $usuario->personal_id;
    } else {
        $validated['registrado_por'] = null;
    }
```

**¿Qué hace?**
- Obtiene el usuario autenticado actual
- Si el usuario tiene asociado un registro de personal, guarda su ID
- Si no, deja el campo en null
- Esto permite rastrear quién registró la asistencia

**¿De dónde sale?**
- `auth()->user()` obtiene el usuario logueado de la sesión
- `personal_id` es un campo en la tabla users que relaciona con personal

```php
    // Líneas 119-129: Guardar en base de datos
    try {
        AsistenciaSemanal::create($validated);

        return redirect()
            ->route('control.asistencia-semanal.index', ['semana' => $fecha->format('Y-m-d')])
            ->with('success', 'Asistencia registrada correctamente');
    } catch (\Exception $e) {
        return back()
            ->withInput()
            ->with('error', 'Error al registrar la asistencia: ' . $e->getMessage());
    }
}
```

**¿Qué hace?**
- **Línea 119**: Bloque try-catch para manejo de errores
- **Línea 120**: Crea nuevo registro en tabla asistencias_semanal
- **Líneas 122-124**: Si todo OK, redirige a vista index con mensaje de éxito
  - Incluye parámetro 'semana' para mostrar la semana correcta
- **Líneas 125-128**: Si hay error, regresa al formulario
  - `.withInput()`: mantiene los datos ingresados
  - `.with('error', ...)`: muestra mensaje de error

**¿De dónde sale?**
- Eloquent ORM para insertar en BD
- Sistema de sesión de Laravel para mensajes flash

---

### Líneas 135-143: Método `edit()` - Formulario de edición
```php
public function edit($id)
{
    $asistencia = AsistenciaSemanal::with('personal')->findOrFail($id);
    $personal = Personal::where('estado', 'activo')
        ->orderBy('nombre_completo')
        ->get();

    return view('control.asistencia-semanal.edit', compact('asistencia', 'personal'));
}
```

**¿Qué hace?**
- **Línea 137**: Busca el registro de asistencia por ID
  - `.with('personal')`: incluye datos del empleado
  - `.findOrFail()`: si no existe, retorna error 404
- **Líneas 138-140**: Obtiene lista de empleados activos
- **Línea 142**: Muestra vista del formulario de edición

**¿De dónde sale?**
- `$id` viene de la URL (ej: `/asistencia-semanal/5/edit`)
- Patrón RESTful de Laravel

---

### Líneas 148-175: Método `update()` - Actualizar asistencia
```php
public function update(Request $request, $id)
{
    $asistencia = AsistenciaSemanal::findOrFail($id);

    // Líneas 152-159: Validación (igual que store)
    $validated = $request->validate([
        'personal_id' => 'required|exists:personal,id',
        'fecha' => 'required|date',
        'entrada_hora' => 'required|date_format:H:i',
        'salida_hora' => 'nullable|date_format:H:i|after:entrada_hora',
        'observaciones' => 'nullable|string|max:500',
        'estado' => 'required|in:presente,ausente,permiso,tardanza',
    ]);

    // Líneas 161-162: Calcular día de la semana
    $fecha = Carbon::parse($validated['fecha']);
    $validated['dia_semana'] = AsistenciaSemanal::obtenerDiaSemana($fecha);

    // Líneas 164-174: Actualizar registro
    try {
        $asistencia->update($validated);

        return redirect()
            ->route('control.asistencia-semanal.index', ['semana' => $fecha->format('Y-m-d')])
            ->with('success', 'Asistencia actualizada correctamente');
    } catch (\Exception $e) {
        return back()
            ->withInput()
            ->with('error', 'Error al actualizar la asistencia: ' . $e->getMessage());
    }
}
```

**¿Qué hace?**
- Similar a `store()` pero actualiza un registro existente
- **Línea 150**: Busca el registro a actualizar
- **Línea 165**: Usa `.update()` en lugar de `.create()`
- Misma lógica de validación y redirección

**¿De dónde sale?**
- `$id` de la URL, datos del formulario PUT/PATCH

---

### Líneas 180-193: Método `destroy()` - Eliminar asistencia
```php
public function destroy($id)
{
    try {
        $asistencia = AsistenciaSemanal::findOrFail($id);
        $fecha = $asistencia->fecha;
        $asistencia->delete();

        return redirect()
            ->route('control.asistencia-semanal.index', ['semana' => $fecha->format('Y-m-d')])
            ->with('success', 'Asistencia eliminada correctamente');
    } catch (\Exception $e) {
        return back()->with('error', 'Error al eliminar la asistencia: ' . $e->getMessage());
    }
}
```

**¿Qué hace?**
- **Línea 183**: Busca el registro por ID
- **Línea 184**: Guarda la fecha antes de eliminar (para redirigir a la semana correcta)
- **Línea 185**: Elimina el registro de la base de datos
- **Líneas 187-189**: Redirige a vista index con mensaje
- Manejo de errores en catch

**¿De dónde sale?**
- Método DELETE HTTP desde formulario o link

---

### Líneas 198-202: Método `generarReporte()` - PDF (En desarrollo)
```php
public function generarReporte(Request $request)
{
    // Implementar generación de PDF si es necesario
    return back()->with('info', 'Funcionalidad de reporte en desarrollo');
}
```

**¿Qué hace?**
- Placeholder para futura funcionalidad de generar PDF
- Por ahora solo retorna mensaje informativo

---

### Líneas 207-225: Método `registroRapido()` - Vista de marcado rápido
```php
public function registroRapido()
{
    $personal = Personal::where('estado', 'activo')
        ->orderBy('nombre_completo')
        ->get();

    // Líneas 214-218: Obtener asistencias de hoy
    $hoy = Carbon::today();
    $asistenciasHoy = AsistenciaSemanal::whereDate('fecha', $hoy)
        ->with('personal')
        ->get()
        ->keyBy('personal_id');

    return view('control.asistencia-semanal.registro-rapido', compact(
        'personal',
        'asistenciasHoy',
        'hoy'
    ));
}
```

**¿Qué hace?**
- Muestra vista especial para registrar entradas/salidas rápidamente
- **Línea 214**: Obtiene fecha de hoy (00:00:00)
- **Líneas 215-218**: Busca todas las asistencias de hoy
  - `.keyBy('personal_id')`: Indexa por ID de personal para acceso rápido
- Útil para pantalla de marcación de asistencia

**¿De dónde sale?**
- Carbon::today() - fecha actual sin hora

---

### Líneas 230-268: Método `registrarEntrada()` - Marcar entrada
```php
public function registrarEntrada(Request $request)
{
    // Líneas 232-234: Validar que se envió ID de personal
    $validated = $request->validate([
        'personal_id' => 'required|exists:personal,id',
    ]);

    // Líneas 236-237: Obtener fecha y hora actuales
    $hoy = Carbon::today();
    $horaEntrada = Carbon::now()->format('H:i');

    // Líneas 240-244: Verificar si ya tiene entrada sin salida
    $asistenciaExistente = AsistenciaSemanal::where('personal_id', $validated['personal_id'])
        ->whereDate('fecha', $hoy)
        ->whereNotNull('entrada_hora')
        ->whereNull('salida_hora')
        ->first();

    if ($asistenciaExistente) {
        return back()->with('warning', 'El personal ya tiene una entrada registrada hoy sin salida');
    }
```

**¿Qué hace?**
- Registra hora de entrada automáticamente
- **Línea 237**: Toma hora exacta del sistema (ej: "14:30")
- **Líneas 240-248**: Verifica que no haya entrada pendiente
  - Busca registros de hoy con entrada pero sin salida
  - Si existe, retorna advertencia
- Previene duplicados

**¿De dónde sale?**
- Reloj del servidor para la hora
- Petición POST con personal_id

```php
    // Líneas 251-267: Crear registro de entrada
    try {
        AsistenciaSemanal::create([
            'personal_id' => $validated['personal_id'],
            'fecha' => $hoy,
            'dia_semana' => AsistenciaSemanal::obtenerDiaSemana($hoy),
            'entrada_hora' => $horaEntrada,
            'salida_hora' => null,
            'estado' => 'presente',
            'observaciones' => 'Entrada registrada automáticamente',
            'registrado_por' => auth()->user()->personal_id ?? null,
        ]);

        $personal = Personal::find($validated['personal_id']);
        return back()->with('success', "Entrada registrada para {$personal->nombre_completo} a las {$horaEntrada}");
    } catch (\Exception $e) {
        return back()->with('error', 'Error al registrar entrada: ' . $e->getMessage());
    }
}
```

**¿Qué hace?**
- Crea nuevo registro de asistencia
- Campos importantes:
  - `entrada_hora`: hora actual
  - `salida_hora`: null (aún no sale)
  - `estado`: 'presente' por defecto
  - `observaciones`: marca que fue automático
- **Línea 263**: Busca el nombre del empleado para mensaje personalizado
- Retorna mensaje con nombre y hora

---

### Líneas 273-304: Método `registrarSalida()` - Marcar salida
```php
public function registrarSalida(Request $request)
{
    $validated = $request->validate([
        'personal_id' => 'required|exists:personal,id',
    ]);

    $hoy = Carbon::today();
    $horaActual = Carbon::now()->format('H:i');

    // Líneas 283-287: Buscar entrada sin salida
    $asistencia = AsistenciaSemanal::where('personal_id', $validated['personal_id'])
        ->whereDate('fecha', $hoy)
        ->whereNotNull('entrada_hora')
        ->whereNull('salida_hora')
        ->first();

    if (!$asistencia) {
        return back()->with('error', 'No hay entrada registrada hoy para este personal');
    }

    // Líneas 294-303: Actualizar con hora de salida
    try {
        $asistencia->update([
            'salida_hora' => $horaActual,
        ]);

        $personal = Personal::find($validated['personal_id']);
        return back()->with('success', "Salida registrada para {$personal->nombre_completo} a las {$horaActual}");
    } catch (\Exception $e) {
        return back()->with('error', 'Error al registrar salida: ' . $e->getMessage());
    }
}
```

**¿Qué hace?**
- Registra hora de salida en un registro existente
- **Líneas 283-291**: Busca la entrada pendiente de hoy
  - Debe tener entrada_hora pero NO salida_hora
  - Si no encuentra, muestra error
- **Línea 296**: Actualiza solo el campo salida_hora
- Más simple que entrada porque solo actualiza un campo

---

### Líneas 309-331: Método `miRegistro()` - Vista personal
```php
public function miRegistro()
{
    $usuario = auth()->user();

    if (!$usuario->personal_id) {
        return back()->with('error', 'No tiene un perfil de personal asociado');
    }

    $personal = Personal::find($usuario->personal_id);
    $hoy = Carbon::today();

    // Líneas 321-324: Obtener última asistencia de hoy
    $asistenciaHoy = AsistenciaSemanal::where('personal_id', $personal->id)
        ->whereDate('fecha', $hoy)
        ->orderBy('created_at', 'desc')
        ->first();

    return view('control.asistencia-semanal.mi-registro', compact(
        'personal',
        'asistenciaHoy',
        'hoy'
    ));
}
```

**¿Qué hace?**
- Vista para que cada empleado vea su propia asistencia
- **Líneas 313-315**: Verifica que el usuario tenga perfil de personal
- **Línea 317**: Obtiene datos completos del personal
- **Líneas 321-324**: Busca su asistencia de hoy (si existe)
  - Ordena por created_at descendente (más reciente primero)
- Permite auto-gestión de asistencia

---

### Líneas 336-374: Método `marcarMiEntrada()` - Auto-marcar entrada
```php
public function marcarMiEntrada()
{
    $usuario = auth()->user();

    if (!$usuario->personal_id) {
        return back()->with('error', 'No tiene un perfil de personal asociado');
    }

    $hoy = Carbon::today();
    $horaEntrada = Carbon::now()->format('H:i');

    // Líneas 348-356: Verificar entrada existente
    $asistenciaExistente = AsistenciaSemanal::where('personal_id', $usuario->personal_id)
        ->whereDate('fecha', $hoy)
        ->whereNotNull('entrada_hora')
        ->whereNull('salida_hora')
        ->first();

    if ($asistenciaExistente) {
        return back()->with('warning', 'Ya tiene una entrada registrada hoy sin salida');
    }

    // Líneas 358-373: Crear registro
    try {
        AsistenciaSemanal::create([
            'personal_id' => $usuario->personal_id,
            'fecha' => $hoy,
            'dia_semana' => AsistenciaSemanal::obtenerDiaSemana($hoy),
            'entrada_hora' => $horaEntrada,
            'salida_hora' => null,
            'estado' => 'presente',
            'observaciones' => 'Entrada auto-registrada',
            'registrado_por' => $usuario->personal_id,
        ]);

        return back()->with('success', "Entrada registrada exitosamente a las {$horaEntrada}");
    } catch (\Exception $e) {
        return back()->with('error', 'Error al registrar entrada: ' . $e->getMessage());
    }
}
```

**¿Qué hace?**
- Similar a `registrarEntrada()` pero para uso personal
- El empleado marca su propia entrada
- **Línea 366**: Observaciones dice "auto-registrada"
- **Línea 367**: registrado_por es el mismo usuario
- No requiere seleccionar empleado (usa el autenticado)

---

### Líneas 379-410: Método `marcarMiSalida()` - Auto-marcar salida
```php
public function marcarMiSalida()
{
    $usuario = auth()->user();

    if (!$usuario->personal_id) {
        return back()->with('error', 'No tiene un perfil de personal asociado');
    }

    $hoy = Carbon::today();
    $horaActual = Carbon::now()->format('H:i');

    // Líneas 391-399: Buscar entrada sin salida
    $asistencia = AsistenciaSemanal::where('personal_id', $usuario->personal_id)
        ->whereDate('fecha', $hoy)
        ->whereNotNull('entrada_hora')
        ->whereNull('salida_hora')
        ->first();

    if (!$asistencia) {
        return back()->with('error', 'No hay entrada registrada hoy');
    }

    // Líneas 401-409: Actualizar salida
    try {
        $asistencia->update([
            'salida_hora' => $horaActual,
        ]);

        return back()->with('success', "Salida registrada exitosamente a las {$horaActual}");
    } catch (\Exception $e) {
        return back()->with('error', 'Error al registrar salida: ' . $e->getMessage());
    }
}
```

**¿Qué hace?**
- Similar a `registrarSalida()` pero para uso personal
- Marca la salida del empleado autenticado
- Mensajes más simples (no incluye nombre)

---

## Resumen de Funcionalidades

### Para Administradores:
1. **Ver asistencias semanales**: `index()` - Vista estilo cuaderno
2. **Registrar asistencia**: `create()` y `store()` - Formulario completo
3. **Editar asistencia**: `edit()` y `update()` - Modificar registros
4. **Eliminar asistencia**: `destroy()` - Borrar registros
5. **Registro rápido**: `registroRapido()`, `registrarEntrada()`, `registrarSalida()` - Marcado ágil

### Para Empleados:
6. **Ver mi asistencia**: `miRegistro()` - Vista personal
7. **Marcar mi entrada**: `marcarMiEntrada()` - Auto-registro entrada
8. **Marcar mi salida**: `marcarMiSalida()` - Auto-registro salida

---

## Tablas de Base de Datos Involucradas

1. **asistencias_semanal**
   - Campos: id, personal_id, fecha, dia_semana, entrada_hora, salida_hora, estado, observaciones, registrado_por, timestamps

2. **personal**
   - Campos: id, nombre_completo, estado, otros campos de empleado

3. **users**
   - Campo relevante: personal_id (relaciona usuario con empleado)

---

## Rutas Web Necesarias (en routes/web.php)

```php
Route::prefix('control/asistencia-semanal')->group(function () {
    Route::get('/', [AsistenciaSemanalController::class, 'index'])->name('control.asistencia-semanal.index');
    Route::get('/create', [AsistenciaSemanalController::class, 'create'])->name('control.asistencia-semanal.create');
    Route::post('/', [AsistenciaSemanalController::class, 'store'])->name('control.asistencia-semanal.store');
    Route::get('/{id}/edit', [AsistenciaSemanalController::class, 'edit'])->name('control.asistencia-semanal.edit');
    Route::put('/{id}', [AsistenciaSemanalController::class, 'update'])->name('control.asistencia-semanal.update');
    Route::delete('/{id}', [AsistenciaSemanalController::class, 'destroy'])->name('control.asistencia-semanal.destroy');

    Route::get('/registro-rapido', [AsistenciaSemanalController::class, 'registroRapido'])->name('control.asistencia-semanal.registro-rapido');
    Route::post('/registrar-entrada', [AsistenciaSemanalController::class, 'registrarEntrada'])->name('control.asistencia-semanal.registrar-entrada');
    Route::post('/registrar-salida', [AsistenciaSemanalController::class, 'registrarSalida'])->name('control.asistencia-semanal.registrar-salida');

    Route::get('/mi-registro', [AsistenciaSemanalController::class, 'miRegistro'])->name('control.asistencia-semanal.mi-registro');
    Route::post('/marcar-mi-entrada', [AsistenciaSemanalController::class, 'marcarMiEntrada'])->name('control.asistencia-semanal.marcar-mi-entrada');
    Route::post('/marcar-mi-salida', [AsistenciaSemanalController::class, 'marcarMiSalida'])->name('control.asistencia-semanal.marcar-mi-salida');
});
```

---

## Flujo de Trabajo Típico

### Escenario 1: Administrador registra asistencia
1. Accede a `/control/asistencia-semanal` → método `index()`
2. Ve cuadro semanal con todas las asistencias
3. Click en "Registrar" → método `create()`
4. Llena formulario (empleado, fecha, horas, estado)
5. Submit → método `store()`
6. Validación → Guardar en BD → Redirigir a index con mensaje

### Escenario 2: Empleado marca entrada
1. Empleado llega y accede a `/control/asistencia-semanal/mi-registro` → método `miRegistro()`
2. Ve su estado del día
3. Click en "Marcar Entrada" → método `marcarMiEntrada()`
4. Sistema toma hora actual → Guarda en BD
5. Muestra mensaje "Entrada registrada a las 08:30"

### Escenario 3: Registro rápido masivo
1. Encargado accede a `/control/asistencia-semanal/registro-rapido` → método `registroRapido()`
2. Ve lista de todo el personal
3. Para cada empleado que llega:
   - Click en botón entrada → método `registrarEntrada()`
   - Sistema guarda con hora actual
4. Al final del día, marca salidas → método `registrarSalida()`

---

## Mejoras Futuras Sugeridas

1. **Reporte PDF**: Implementar `generarReporte()` con librería como DomPDF
2. **Notificaciones**: Alertar a RRHH de ausencias o tardanzas
3. **Geolocalización**: Validar que entrada/salida sea desde ubicación autorizada
4. **Biométrico**: Integrar con dispositivo de huella dactilar
5. **Análisis**: Dashboard con estadísticas de puntualidad
6. **Exportar Excel**: Generar reportes mensuales para nómina

---

## Dependencias y Requisitos

- **Laravel 8+**: Framework base
- **Carbon**: Manejo de fechas (incluido en Laravel)
- **MySQL/PostgreSQL**: Base de datos
- **Eloquent ORM**: Para queries
- **Blade**: Motor de plantillas para vistas
- **Middleware Auth**: Para autenticación de usuarios

---

## Seguridad Implementada

1. **Validación de datos**: Todas las entradas son validadas
2. **Autenticación**: Requiere usuario logueado (`auth()->user()`)
3. **findOrFail**: Previene acceso a registros inexistentes (error 404)
4. **try-catch**: Manejo de excepciones para errores de BD
5. **exists validation**: Verifica que IDs existan en tablas relacionadas
6. **CSRF Protection**: Laravel automáticamente protege formularios
