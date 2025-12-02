# MantenimientoController - Documentación Detallada

**Ubicación**: `app/Http/Controllers/Control/MantenimientoController.php`

## Propósito General
Este controlador gestiona el registro y control de mantenimientos de equipos de producción y limpieza. Permite registrar qué equipos se limpiaron/mantuvieron, qué productos de limpieza se utilizaron, quién lo realizó, y programar el próximo mantenimiento.

---

## Línea por Línea

### Líneas 1-9: Declaración de namespace e imports
```php
<?php

namespace App\Http\Controllers\Control;

use App\Http\Controllers\Controller;
use App\Models\Control\MantenimientoEquipo;
use App\Models\Personal;
use Illuminate\Http\Request;
```

**¿Qué hace?**
- **Línea 3**: Define namespace en carpeta Control
- **Línea 5**: Importa Controller base de Laravel
- **Línea 6**: Importa modelo MantenimientoEquipo de la carpeta Control
- **Línea 7**: Importa modelo Personal para responsables
- **Línea 8**: Importa Request para peticiones HTTP

**¿De dónde sale?**
- Laravel framework y modelos del sistema

---

### Líneas 15-33: Método privado `getProductosLimpieza()` - Lista de productos
```php
private function getProductosLimpieza(): array
{
    return [
        'Detergente Industrial',
        'Cloro / Hipoclorito de Sodio',
        'Desinfectante para Superficies',
        'Alcohol al 70%',
        'Jabón Antibacterial',
        'Desengrasante',
        'Limpiador Multiusos',
        'Cepillos de Limpieza',
        'Esponjas Abrasivas',
        'Paños de Microfibra',
        'Ácido Cítrico',
        'Bicarbonato de Sodio',
        'Sanitizante de Grado Alimenticio',
        'Vinagre Blanco',
    ];
}
```

**¿Qué hace?**
- Método privado que retorna array con lista predefinida de productos de limpieza
- **private**: Solo accesible dentro de esta clase
- **array**: Tipo de retorno (PHP 7.4+)
- Lista 14 productos de limpieza estándar para mantenimiento

**¿De dónde sale?**
- Productos comunes en industria alimentaria
- Normativas de limpieza para plantas de producción de agua

**¿Para qué sirve?**
- Estandarizar productos de limpieza
- Facilitar selección en formularios (checkboxes o select multiple)
- No requiere tabla en BD, son valores fijos

**Tipos de productos**:
1. **Químicos de limpieza**: Detergente, Cloro, Desinfectante, Desengrasante
2. **Desinfectantes**: Alcohol 70%, Sanitizante grado alimenticio
3. **Herramientas**: Cepillos, Esponjas, Paños
4. **Naturales**: Ácido cítrico, Bicarbonato, Vinagre (opciones ecológicas)

---

### Líneas 35-39: Método `index()` - Listar mantenimientos
```php
public function index()
{
    $mantenimientos = MantenimientoEquipo::with('personal')->orderBy('fecha', 'desc')->paginate(15);
    return view('control.mantenimiento.index', compact('mantenimientos'));
}
```

**¿Qué hace?**
- **Línea 37**: Obtiene todos los mantenimientos
  - `.with('personal')`: Eager loading - carga relación con empleado que realizó el mantenimiento
  - `.orderBy('fecha', 'desc')`: Ordena por fecha descendente (más recientes primero)
  - `.paginate(15)`: Divide en páginas de 15 registros
- **Línea 38**: Retorna vista con los mantenimientos

**¿De dónde sale?**
- Tabla `control_mantenimiento_equipos` en la base de datos
- Relación `personal()` definida en modelo MantenimientoEquipo

**¿Para qué sirve?**
- Ver historial de todos los mantenimientos
- Seguimiento de fechas de limpieza
- Auditoría de mantenimiento preventivo

---

### Líneas 41-47: Método `create()` - Formulario de creación
```php
public function create()
{
    $personal = Personal::where('estado', 'activo')->orderBy('nombre_completo')->get();
    $productosLimpieza = $this->getProductosLimpieza();

    return view('control.mantenimiento.create', compact('personal', 'productosLimpieza'));
}
```

**¿Qué hace?**
- **Línea 43**: Obtiene empleados activos ordenados alfabéticamente
- **Línea 44**: Llama al método privado para obtener lista de productos de limpieza
  - `$this->`: Accede a método de la misma clase
- **Línea 46**: Retorna vista del formulario con ambas listas

**¿De dónde sale?**
- Personal de tabla `personal`
- Productos del método `getProductosLimpieza()`

**¿Para qué sirve?**
- Prellenar select de personal
- Mostrar checkboxes o select multiple de productos de limpieza
- Facilitar registro rápido

---

### Líneas 49-88: Método `store()` - Guardar mantenimiento
```php
public function store(Request $request)
{
    // Líneas 51-60: Validación de datos
    $validated = $request->validate([
        'fecha' => 'required|date',
        'equipo' => 'required|array|min:1',
        'equipo.*' => 'required|string',
        'id_personal' => 'required|exists:personal,id',
        'productos_limpieza' => 'required|array|min:1',
        'productos_limpieza.*' => 'string',
        'proxima_fecha' => 'nullable|date|after:fecha',
        'supervisado_por' => 'required|string',
    ]);
```

**¿Qué hace cada validación?**
- **fecha**: Requerido, fecha válida del mantenimiento
- **equipo**: Requerido, debe ser array con al menos 1 elemento
- **equipo.\***: Cada elemento del array debe ser string (nombre del equipo)
- **id_personal**: Requerido, debe existir en tabla personal (quien realizó el mantenimiento)
- **productos_limpieza**: Requerido, array con al menos 1 producto
- **productos_limpieza.\***: Cada producto debe ser string
- **proxima_fecha**: Opcional, fecha válida, debe ser POSTERIOR a la fecha actual
- **supervisado_por**: Requerido, nombre de quien supervisó

**¿De dónde sale?**
- Formulario POST con:
  - Fecha picker
  - Checkboxes o select multiple para equipos
  - Select de personal
  - Checkboxes de productos de limpieza
  - Fecha picker para próximo mantenimiento
  - Input de supervisor

**Ejemplo de datos**:
```php
[
    'fecha' => '2025-12-02',
    'equipo' => ['Tanque de Ozono', 'Filtros de Agua', 'Envasadora'],
    'id_personal' => 5,
    'productos_limpieza' => ['Cloro / Hipoclorito de Sodio', 'Desinfectante para Superficies'],
    'proxima_fecha' => '2025-12-09',
    'supervisado_por' => 'Carlos Méndez'
]
```

```php
    // Líneas 62-73: Validación de duplicados
    $equiposTextoValidacion = implode(', ', $validated['equipo']);
    $existeDuplicado = MantenimientoEquipo::whereDate('fecha', $validated['fecha'])
        ->where('id_personal', $validated['id_personal'])
        ->whereRaw("JSON_CONTAINS(equipo, ?)", [json_encode($validated['equipo'][0])])
        ->exists();

    if ($existeDuplicado) {
        return redirect()->back()
            ->withInput()
            ->withErrors(['error' => 'Ya existe un registro de mantenimiento para este equipo en la fecha ' . date('d/m/Y', strtotime($validated['fecha'])) . '. Por favor, verifique los registros existentes.']);
    }
```

**¿Qué hace?**
- **Línea 63**: Convierte array de equipos a string separado por comas (para mensaje de error)
- **Líneas 64-67**: Busca si ya existe mantenimiento:
  - Misma fecha
  - Mismo personal
  - Mismo equipo (verifica primer elemento del array con JSON_CONTAINS)
- **whereRaw**: Ejecuta SQL raw para buscar en columna JSON
- **JSON_CONTAINS**: Función MySQL para buscar valor dentro de JSON array

**¿De dónde sale?**
- Lógica de negocio: evitar duplicados
- Columna `equipo` es tipo JSON en base de datos

**¿Para qué sirve?**
- Prevenir registro duplicado del mismo equipo en la misma fecha
- Alertar al usuario si ya existe el registro

**Nota técnica**:
- La columna `equipo` se guarda como JSON: `["Tanque de Ozono", "Filtros de Agua"]`
- MySQL/MariaDB 5.7+ soporta JSON nativo

```php
    // Líneas 75-78: Generar detalle automático
    $equiposTexto = implode(', ', $validated['equipo']);
    $productosTexto = implode(', ', $validated['productos_limpieza']);
    $validated['detalle_mantenimiento'] = "Equipos: {$equiposTexto} | Productos: {$productosTexto}";
```

**¿Qué hace?**
- **Línea 76**: Convierte array de equipos a string
  - Ejemplo: `['Tanque A', 'Tanque B']` → `"Tanque A, Tanque B"`
- **Línea 77**: Convierte array de productos a string
  - Ejemplo: `['Cloro', 'Detergente']` → `"Cloro, Detergente"`
- **Línea 78**: Crea string descriptivo combinado
  - Ejemplo: `"Equipos: Tanque A, Tanque B | Productos: Cloro, Detergente"`

**¿De dónde sale?**
- `implode()`: Función PHP que une array en string

**¿Para qué sirve?**
- Campo `detalle_mantenimiento` es texto legible para reportes
- Facilita búsquedas y visualización sin parsear JSON

```php
    // Líneas 80-82: Asignar nombre del responsable
    $personal = Personal::find($validated['id_personal']);
    $validated['realizado_por'] = $personal->nombre_completo;
```

**¿Qué hace?**
- **Línea 81**: Busca el empleado por ID
- **Línea 82**: Guarda el nombre completo en campo `realizado_por`

**¿De dónde sale?**
- Campo `id_personal` ya validado
- Modelo Personal

**¿Para qué sirve?**
- Redundancia intencional: guardar tanto ID como nombre
- Facilita reportes sin joins
- Si el empleado se da de baja, el nombre se mantiene en histórico

```php
    // Líneas 84-87: Crear registro
    MantenimientoEquipo::create($validated);

    return redirect()->route('control.mantenimiento.index')
        ->with('success', 'Registro de mantenimiento creado exitosamente.');
}
```

**¿Qué hace?**
- Inserta registro en tabla `control_mantenimiento_equipos`
- Redirige a vista index con mensaje de éxito

**Datos guardados**:
- `fecha`: DATE
- `equipo`: JSON array
- `id_personal`: INT (foreign key)
- `realizado_por`: VARCHAR (nombre)
- `productos_limpieza`: JSON array
- `proxima_fecha`: DATE nullable
- `supervisado_por`: VARCHAR
- `detalle_mantenimiento`: TEXT

---

### Líneas 90-93: Método `show()` - Ver detalles
```php
public function show(MantenimientoEquipo $mantenimiento)
{
    return view('control.mantenimiento.show', compact('mantenimiento'));
}
```

**¿Qué hace?**
- Route Model Binding: Laravel busca el mantenimiento por ID automáticamente
- Muestra vista de detalles del mantenimiento

**¿De dónde sale?**
- URL con ID: `/control/mantenimiento/5`

---

### Líneas 95-101: Método `edit()` - Formulario de edición
```php
public function edit(MantenimientoEquipo $mantenimiento)
{
    $personal = Personal::where('estado', 'activo')->orderBy('nombre_completo')->get();
    $productosLimpieza = $this->getProductosLimpieza();

    return view('control.mantenimiento.edit', compact('mantenimiento', 'personal', 'productosLimpieza'));
}
```

**¿Qué hace?**
- Similar a `create()` pero incluye el objeto `$mantenimiento` con datos actuales
- Prellenará el formulario con valores existentes

**¿De dónde sale?**
- Route Model Binding
- Petición GET a `/control/mantenimiento/5/edit`

---

### Líneas 103-129: Método `update()` - Actualizar mantenimiento
```php
public function update(Request $request, MantenimientoEquipo $mantenimiento)
{
    // Líneas 105-114: Validación (igual que store)
    $validated = $request->validate([
        'fecha' => 'required|date',
        'equipo' => 'required|array|min:1',
        'equipo.*' => 'required|string',
        'id_personal' => 'required|exists:personal,id',
        'productos_limpieza' => 'required|array|min:1',
        'productos_limpieza.*' => 'string',
        'proxima_fecha' => 'nullable|date|after:fecha',
        'supervisado_por' => 'required|string',
    ]);

    // Líneas 116-119: Regenerar detalle_mantenimiento
    $equiposTexto = implode(', ', $validated['equipo']);
    $productosTexto = implode(', ', $validated['productos_limpieza']);
    $validated['detalle_mantenimiento'] = "Equipos: {$equiposTexto} | Productos: {$productosTexto}";

    // Líneas 121-123: Actualizar realizado_por
    $personal = Personal::find($validated['id_personal']);
    $validated['realizado_por'] = $personal->nombre_completo;

    $mantenimiento->update($validated);

    return redirect()->route('control.mantenimiento.index')
        ->with('success', 'Registro de mantenimiento actualizado exitosamente.');
}
```

**¿Qué hace?**
- Validación idéntica a `store()`
- Regenera `detalle_mantenimiento` con nuevos datos
- Actualiza `realizado_por` por si cambió el personal
- Actualiza todos los campos del registro

**Diferencia con store()**:
- NO valida duplicados (se permite editar sin restricción)
- Usa `.update()` en lugar de `.create()`

---

### Líneas 131-137: Método `destroy()` - Eliminar mantenimiento
```php
public function destroy(MantenimientoEquipo $mantenimiento)
{
    $mantenimiento->delete();

    return redirect()->route('control.mantenimiento.index')
        ->with('success', 'Registro de mantenimiento eliminado exitosamente.');
}
```

**¿Qué hace?**
- **Línea 133**: Elimina permanentemente el registro (hard delete)
- Redirige con mensaje de éxito

**Nota**:
- Es eliminación real, no soft delete
- Se pierde el histórico de mantenimiento
- Considerar soft delete para auditoría

---

## Resumen de Funcionalidades

### Operaciones CRUD:
1. **Listar mantenimientos**: `index()` - Vista paginada con relaciones
2. **Crear mantenimiento**: `create()` + `store()` - Registrar limpieza/mantenimiento
3. **Ver mantenimiento**: `show()` - Detalles de un mantenimiento
4. **Editar mantenimiento**: `edit()` + `update()` - Modificar registro
5. **Eliminar mantenimiento**: `destroy()` - Borrar registro

### Características Especiales:
6. **Lista predefinida de productos**: Método `getProductosLimpieza()` con 14 productos
7. **Múltiples equipos**: Permite seleccionar varios equipos en un solo registro
8. **Múltiples productos**: Permite seleccionar varios productos de limpieza
9. **Detalle automático**: Genera string legible para reportes
10. **Próxima fecha**: Programar mantenimiento preventivo
11. **Validación de duplicados**: Evita registros duplicados
12. **Supervisor**: Campo para quien supervisó el trabajo

---

## Tabla de Base de Datos

### **control_mantenimiento_equipos**

Campos:
- `id`: INT, Primary Key, Auto Increment
- `fecha`: DATE, fecha del mantenimiento
- `equipo`: JSON, array de nombres de equipos
  - Ejemplo: `["Tanque de Ozono", "Filtros de Agua", "Envasadora"]`
- `id_personal`: INT, Foreign Key → personal.id, quien realizó el mantenimiento
- `realizado_por`: VARCHAR(255), nombre completo del responsable (redundante)
- `productos_limpieza`: JSON, array de productos utilizados
  - Ejemplo: `["Cloro / Hipoclorito de Sodio", "Desinfectante para Superficies"]`
- `detalle_mantenimiento`: TEXT, resumen legible
  - Ejemplo: `"Equipos: Tanque A, Filtro B | Productos: Cloro, Jabón"`
- `proxima_fecha`: DATE, NULLABLE, fecha del próximo mantenimiento programado
- `supervisado_por`: VARCHAR(255), nombre de quien supervisó
- `created_at`: TIMESTAMP, fecha de creación del registro
- `updated_at`: TIMESTAMP, fecha de última actualización

**Índices recomendados**:
```sql
INDEX idx_fecha (fecha)
INDEX idx_id_personal (id_personal)
INDEX idx_proxima_fecha (proxima_fecha)
```

---

## Relaciones de Modelos

En el modelo **MantenimientoEquipo**:
```php
public function personal()
{
    return $this->belongsTo(Personal::class, 'id_personal');
}
```

**Casts recomendados**:
```php
protected $casts = [
    'fecha' => 'date',
    'proxima_fecha' => 'date',
    'equipo' => 'array',
    'productos_limpieza' => 'array',
];
```

Esto convierte automáticamente JSON a array en PHP.

---

## Rutas Web Necesarias

```php
Route::prefix('control/mantenimiento')->name('control.mantenimiento.')->group(function () {
    Route::get('/', [MantenimientoController::class, 'index'])->name('index');
    Route::get('/create', [MantenimientoController::class, 'create'])->name('create');
    Route::post('/', [MantenimientoController::class, 'store'])->name('store');
    Route::get('/{mantenimiento}', [MantenimientoController::class, 'show'])->name('show');
    Route::get('/{mantenimiento}/edit', [MantenimientoController::class, 'edit'])->name('edit');
    Route::put('/{mantenimiento}', [MantenimientoController::class, 'update'])->name('update');
    Route::delete('/{mantenimiento}', [MantenimientoController::class, 'destroy'])->name('destroy');
});
```

---

## Flujo de Trabajo Típico

### Escenario 1: Mantenimiento semanal de tanques
1. Operador accede a `/control/mantenimiento/create` → método `create()`
2. Ve formulario con:
   - Fecha: 2025-12-02
   - Equipos (checkboxes): Marca "Tanque de Ozono" y "Filtros de Agua"
   - Personal: Selecciona "Juan Pérez"
   - Productos (checkboxes): Marca "Cloro" y "Desinfectante"
   - Próxima fecha: 2025-12-09 (7 días después)
   - Supervisor: "Carlos Méndez"
3. Submit → método `store()`
4. Sistema:
   - Valida datos
   - Verifica que no exista duplicado para ese equipo en esa fecha
   - Genera detalle: "Equipos: Tanque de Ozono, Filtros de Agua | Productos: Cloro, Desinfectante"
   - Busca nombre de Juan Pérez
   - Guarda registro
5. Redirige a index con mensaje de éxito

### Escenario 2: Consultar próximos mantenimientos
1. Supervisor accede a `/control/mantenimiento` → método `index()`
2. Ve tabla paginada con:
   - Fecha realizada
   - Equipos mantenidos
   - Responsable
   - Próxima fecha
3. Puede filtrar/buscar por fecha próxima
4. Identifica equipos que necesitan mantenimiento pronto

### Escenario 3: Intentar duplicado
1. Operador intenta registrar mantenimiento de "Tanque A" en fecha 2025-12-02
2. Sistema detecta que Juan Pérez ya registró ese equipo en esa fecha
3. Retorna error: "Ya existe un registro de mantenimiento para este equipo en la fecha 02/12/2025"
4. Operador verifica y se da cuenta del error

---

## Equipos Comunes en Planta de Agua

### Equipos de Tratamiento:
- Tanque de Ozono
- Filtros de Arena
- Filtros de Carbón Activado
- Sistema UV (Ultravioleta)
- Tanques de Almacenamiento
- Tuberías y Conexiones

### Equipos de Envasado:
- Envasadora Automática
- Tapadora
- Etiquetadora
- Selladora
- Banda Transportadora

### Equipos de Limpieza:
- Lavadora de Botellones
- Enjuagadora
- Sistema CIP (Clean In Place)

### Otros:
- Bomba de Agua
- Compresor de Aire
- Calderas (si aplica)

---

## Integración con Otros Módulos

### 1. Sistema de Alertas
Crear comando artisan que corra diariamente:
```php
// app/Console/Commands/AlertarMantenimientos.php
$proximosMantenimientos = MantenimientoEquipo::whereDate('proxima_fecha', '<=', now()->addDays(3))
    ->get();

foreach ($proximosMantenimientos as $mant) {
    // Enviar notificación
    Notification::send($supervisor, new MantenimientoProgramado($mant));
}
```

### 2. Dashboard de Producción
Widget mostrando:
- Últimos mantenimientos realizados
- Mantenimientos pendientes
- Equipos sin mantenimiento en X días

### 3. Reportes
Generar PDF mensual con:
- Historial de mantenimientos
- Frecuencia por equipo
- Productos más utilizados
- Responsables con más mantenimientos

---

## Mejoras Futuras Sugeridas

### 1. Catálogo de equipos en BD:
En lugar de texto libre, tabla `equipos`:
```php
Schema::create('equipos', function (Blueprint $table) {
    $table->id();
    $table->string('nombre');
    $table->string('codigo_interno')->unique();
    $table->string('marca')->nullable();
    $table->string('modelo')->nullable();
    $table->date('fecha_instalacion')->nullable();
    $table->integer('dias_entre_mantenimientos')->default(7);
    $table->enum('estado', ['activo', 'inactivo'])->default('activo');
    $table->timestamps();
});
```

### 2. Frecuencia de mantenimiento:
```php
'frecuencia_dias' => 'nullable|integer|min:1|max:365',
```
Calcular automáticamente `proxima_fecha`.

### 3. Checklist de mantenimiento:
Tabla `mantenimiento_checklists` con tareas específicas:
```php
- [ ] Revisar presión del tanque
- [ ] Limpiar filtros
- [ ] Verificar fugas
- [ ] Sanitizar tuberías
```

### 4. Costo del mantenimiento:
```php
'costo_productos' => 'nullable|numeric|min:0',
'costo_mano_obra' => 'nullable|numeric|min:0',
```

### 5. Evidencia fotográfica:
```php
'fotos_antes' => 'nullable|array',
'fotos_antes.*' => 'image|max:5120',
'fotos_despues' => 'nullable|array',
'fotos_despues.*' => 'image|max:5120',
```

### 6. Firma digital del supervisor:
```php
'firma_supervisor' => 'nullable|string', // Base64 de imagen de firma
```

### 7. Integrar con inventario de insumos:
Descontar productos de limpieza del stock:
```php
$insumo = Insumo::where('producto_insumo', 'Cloro')->first();
$insumo->decrement('stock_actual', 5); // Usó 5 litros
```

### 8. Historial por equipo:
Método en modelo:
```php
public static function historialEquipo($nombreEquipo)
{
    return self::whereRaw("JSON_CONTAINS(equipo, ?)", [json_encode($nombreEquipo)])
        ->orderBy('fecha', 'desc')
        ->get();
}
```

### 9. Soft delete:
```php
use SoftDeletes;

$mantenimiento->delete(); // Marca como eliminado
```

### 10. Validación de fechas pasadas:
```php
'fecha' => 'required|date|before_or_equal:today',
```
No permitir registrar mantenimientos futuros.

---

## Consultas Útiles

### Mantenimientos pendientes esta semana:
```php
$pendientes = MantenimientoEquipo::whereBetween('proxima_fecha', [
    now()->startOfWeek(),
    now()->endOfWeek()
])->get();
```

### Producto más usado:
```php
$mantenimientos = MantenimientoEquipo::all();
$productos = [];

foreach ($mantenimientos as $m) {
    foreach ($m->productos_limpieza as $producto) {
        $productos[$producto] = ($productos[$producto] ?? 0) + 1;
    }
}

arsort($productos); // Ordenar descendente
// ['Cloro' => 45, 'Detergente' => 32, ...]
```

### Equipos sin mantenimiento en 30 días:
Requiere tabla de equipos. Con sistema actual:
```php
$equiposMantenidos = MantenimientoEquipo::where('fecha', '>=', now()->subDays(30))
    ->get()
    ->pluck('equipo')
    ->flatten()
    ->unique();
```

### Empleado con más mantenimientos:
```php
$top = MantenimientoEquipo::select('id_personal', DB::raw('COUNT(*) as total'))
    ->groupBy('id_personal')
    ->orderByDesc('total')
    ->with('personal')
    ->first();
```

---

## Validaciones Adicionales Recomendadas

### 1. Fecha no futura:
```php
'fecha' => 'required|date|before_or_equal:today',
```

### 2. Próxima fecha en rango razonable:
```php
'proxima_fecha' => 'nullable|date|after:fecha|before:' . now()->addYear(),
```

### 3. Mínimo de equipos y productos:
Ya implementado: `'equipo' => 'required|array|min:1'`

### 4. Supervisor diferente al realizador:
```php
// En store() después de validación
$personal = Personal::find($validated['id_personal']);
if (strtolower($personal->nombre_completo) === strtolower($validated['supervisado_por'])) {
    return back()->withErrors(['supervisado_por' => 'El supervisor debe ser diferente al responsable']);
}
```

---

## Seguridad Implementada

### 1. Validación de tipos:
- Arrays verificados con `|array|min:1`
- IDs validados con `|exists:personal,id`

### 2. Prevención de duplicados:
- Validación por equipo + fecha + personal

### 3. SQL Injection:
- Eloquent ORM protege automáticamente
- `whereRaw` usa bindings: `[json_encode(...)]`

### 4. XSS Protection:
- Blade escapa automáticamente: `{{ $mantenimiento->detalle }}`

### 5. CSRF Protection:
- Laravel protege formularios automáticamente

---

## Testing Recomendado

### Test 1: Crear mantenimiento:
```php
$response = $this->post('/control/mantenimiento', [
    'fecha' => '2025-12-02',
    'equipo' => ['Tanque A', 'Filtro B'],
    'id_personal' => Personal::factory()->create()->id,
    'productos_limpieza' => ['Cloro', 'Jabón'],
    'supervisado_por' => 'Test Supervisor',
]);
$response->assertRedirect();
$this->assertDatabaseHas('control_mantenimiento_equipos', [
    'supervisado_por' => 'Test Supervisor'
]);
```

### Test 2: Validar duplicados:
```php
$personal = Personal::factory()->create();
MantenimientoEquipo::create([
    'fecha' => '2025-12-02',
    'equipo' => ['Tanque A'],
    'id_personal' => $personal->id,
    'productos_limpieza' => ['Cloro'],
    'supervisado_por' => 'Supervisor',
    'realizado_por' => $personal->nombre_completo,
]);

$response = $this->post('/control/mantenimiento', [
    'fecha' => '2025-12-02',
    'equipo' => ['Tanque A'],
    'id_personal' => $personal->id,
    'productos_limpieza' => ['Cloro'],
    'supervisado_por' => 'Supervisor',
]);

$response->assertSessionHasErrors();
```

---

## Dependencias

- **Laravel 8+**: Framework
- **MySQL 5.7+/MariaDB 10.2+**: Soporte JSON nativo
- **Eloquent ORM**: Queries y relaciones
- **Carbon**: Manejo de fechas
- **Blade**: Vistas

---

## Conclusión

Este controlador es fundamental para:
- Cumplimiento de normas sanitarias
- Mantenimiento preventivo de equipos
- Trazabilidad de limpiezas
- Programación de mantenimientos
- Auditorías de calidad

Es especialmente importante en industria alimentaria donde las normas (BPM, HACCP) requieren registros detallados de limpieza y sanitización de equipos.
