# InsumosController - Documentación Detallada

**Ubicación**: `app/Http/Controllers/Control/InsumosController.php`

## Propósito General
Este controlador gestiona el registro y control de insumos (materias primas, productos químicos, materiales) que se utilizan en la producción de agua. Permite llevar un inventario de entradas, controlar lotes, fechas de vencimiento y stock actual.

---

## Línea por Línea

### Líneas 1-9: Declaración de namespace e imports
```php
<?php

namespace App\Http\Controllers\Control;

use App\Http\Controllers\Controller;
use App\Models\Control\Insumo;
use App\Models\Personal;
use Illuminate\Http\Request;
```

**¿Qué hace?**
- **Línea 3**: Define el namespace en carpeta Control
- **Línea 5**: Importa Controller base de Laravel
- **Línea 6**: Importa modelo Insumo de la carpeta Control
- **Línea 7**: Importa modelo Personal (para responsables)
- **Línea 8**: Importa Request para peticiones HTTP

**¿De dónde sale?**
- Laravel framework y modelos del sistema

---

### Líneas 12-16: Método `index()` - Listar insumos
```php
public function index()
{
    $insumos = Insumo::orderBy('fecha', 'desc')->paginate(15);
    return view('control.insumos.index', compact('insumos'));
}
```

**¿Qué hace?**
- **Línea 14**: Obtiene todos los insumos
  - `orderBy('fecha', 'desc')`: Ordena por fecha descendente (más recientes primero)
  - `paginate(15)`: Divide en páginas de 15 registros
- **Línea 15**: Retorna vista con los insumos paginados

**¿De dónde sale?**
- Tabla `control_insumos` en la base de datos
- Query Builder de Laravel

**¿Para qué sirve?**
- Ver historial de todos los insumos registrados
- Navegación paginada para mejor rendimiento

---

### Líneas 18-22: Método `create()` - Formulario de creación
```php
public function create()
{
    $personal = Personal::where('estado', 'activo')->orderBy('nombre_completo')->get();
    return view('control.insumos.create', compact('personal'));
}
```

**¿Qué hace?**
- **Línea 20**: Obtiene empleados activos ordenados alfabéticamente
- **Línea 21**: Muestra formulario para registrar nuevo insumo

**¿De dónde sale?**
- Tabla `personal` filtrada por estado activo

**¿Para qué sirve?**
- Lista de personal para asignar responsable del registro
- Formulario prellenado con opciones válidas

---

### Líneas 24-60: Método `store()` - Guardar nuevo insumo
```php
public function store(Request $request)
{
    // Líneas 26-36: Validación de datos
    $validated = $request->validate([
        'fecha' => 'required|date',
        'producto_insumo' => 'required|string|max:255',
        'cantidad' => 'required|numeric|min:0',
        'unidad_medida' => 'required|string',
        'numero_lote' => 'nullable|string|max:100',
        'fecha_vencimiento' => 'nullable|date',
        'responsable' => 'required|string|max:255',
        'proveedor' => 'nullable|string|max:255',
        'observaciones' => 'nullable|string',
    ]);
```

**¿Qué hace cada campo?**
- **fecha**: Requerido, fecha del registro de ingreso
- **producto_insumo**: Requerido, nombre del insumo (ej: "Cloro", "Tapas plásticas", "Etiquetas")
- **cantidad**: Requerido, número positivo o cero
- **unidad_medida**: Requerido (ej: "kg", "litros", "unidades")
- **numero_lote**: Opcional, código de lote del proveedor
- **fecha_vencimiento**: Opcional, fecha de caducidad
- **responsable**: Requerido, quién recibió/registró el insumo
- **proveedor**: Opcional, empresa que suministra
- **observaciones**: Opcional, notas adicionales

**¿De dónde sale?**
- Formulario POST desde la vista create

```php
    // Líneas 38-50: Validación de duplicados
    if ($request->filled('numero_lote')) {
        $existeDuplicado = Insumo::where('producto_insumo', $validated['producto_insumo'])
            ->where('numero_lote', $validated['numero_lote'])
            ->whereDate('fecha', $validated['fecha'])
            ->exists();

        if ($existeDuplicado) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Ya existe un registro de ' . $validated['producto_insumo'] . ' con el lote ' . $validated['numero_lote'] . ' en la fecha ' . date('d/m/Y', strtotime($validated['fecha'])) . '. Por favor, verifique los registros existentes.']);
        }
    }
```

**¿Qué hace?**
- **Línea 39**: Solo valida si se ingresó número de lote (`filled()` verifica que no esté vacío)
- **Líneas 40-43**: Busca si ya existe registro con:
  - Mismo producto
  - Mismo lote
  - Misma fecha
- **Línea 45**: Si encuentra duplicado, retorna al formulario con error

**¿De dónde sale?**
- Lógica de negocio para evitar registros duplicados

**¿Para qué sirve?**
- Prevenir errores de doble ingreso
- Mantener integridad de datos
- Alertar al usuario de posibles duplicados

**Ejemplo de error**:
```
Ya existe un registro de Cloro con el lote LOT-2025-001 en la fecha 01/12/2025. Por favor, verifique los registros existentes.
```

```php
    // Líneas 52-54: Asignar valores iniciales de stock
    $validated['stock_actual'] = $validated['cantidad'];
    $validated['stock_minimo'] = 0;
```

**¿Qué hace?**
- **Línea 53**: El stock actual inicial es igual a la cantidad ingresada
  - Si entran 100 unidades, el stock_actual empieza en 100
- **Línea 54**: Stock mínimo por defecto es 0

**¿De dónde sale?**
- Lógica de inventario: al ingresar, el stock es lo que ingresó

**¿Para qué sirve?**
- Control de inventario en tiempo real
- El stock_actual se irá reduciendo cuando se use el insumo en producción

```php
    // Línea 56: Crear registro
    Insumo::create($validated);

    return redirect()->route('control.insumos.index')
        ->with('success', 'Registro de insumo creado exitosamente.');
}
```

**¿Qué hace?**
- Inserta nuevo registro en tabla control_insumos
- Redirige a vista index con mensaje de éxito

---

### Líneas 62-66: Método `edit()` - Formulario de edición
```php
public function edit(Insumo $insumo)
{
    $personal = Personal::where('estado', 'activo')->orderBy('nombre_completo')->get();
    return view('control.insumos.edit', compact('insumo', 'personal'));
}
```

**¿Qué hace?**
- **Línea 62**: Route Model Binding - Laravel automáticamente busca el insumo por ID
- **Línea 64**: Obtiene personal activo para select
- **Línea 65**: Muestra formulario prellenado con datos del insumo

**¿De dónde sale?**
- `Insumo $insumo`: Inyección de dependencia de Laravel

**¿Para qué sirve?**
- Editar registros de insumos existentes

---

### Líneas 68-86: Método `update()` - Actualizar insumo
```php
public function update(Request $request, Insumo $insumo)
{
    // Líneas 70-80: Validación (igual que store)
    $validated = $request->validate([
        'fecha' => 'required|date',
        'producto_insumo' => 'required|string|max:255',
        'cantidad' => 'required|numeric|min:0',
        'unidad_medida' => 'required|string',
        'numero_lote' => 'nullable|string|max:100',
        'fecha_vencimiento' => 'nullable|date',
        'responsable' => 'required|string|max:255',
        'proveedor' => 'nullable|string|max:255',
        'observaciones' => 'nullable|string',
    ]);

    $insumo->update($validated);

    return redirect()->route('control.insumos.index')
        ->with('success', 'Registro de insumo actualizado exitosamente.');
}
```

**¿Qué hace?**
- Valida datos (mismas reglas que store)
- Actualiza el registro existente
- Redirige con mensaje de éxito

**Nota importante**:
- NO actualiza automáticamente stock_actual ni stock_minimo
- Solo actualiza los campos del formulario
- El stock_actual debe manejarse manualmente o en otro módulo

---

### Líneas 88-94: Método `destroy()` - Eliminar insumo
```php
public function destroy(Insumo $insumo)
{
    $insumo->delete();

    return redirect()->route('control.insumos.index')
        ->with('success', 'Registro de insumo eliminado exitosamente.');
}
```

**¿Qué hace?**
- **Línea 90**: Elimina permanentemente el registro (hard delete)
- **Línea 92**: Redirige con mensaje de éxito

**¿De dónde sale?**
- Petición DELETE desde formulario o botón

**Nota de mejora**:
- Podría usar soft delete para mantener histórico
- Verificar que no haya relaciones antes de eliminar

---

## Resumen de Funcionalidades

### Operaciones CRUD:
1. **Listar insumos**: `index()` - Vista paginada de todos los insumos
2. **Crear insumo**: `create()` + `store()` - Registrar entrada de insumo
3. **Editar insumo**: `edit()` + `update()` - Modificar registro
4. **Eliminar insumo**: `destroy()` - Borrar registro

### Características Especiales:
5. **Validación de duplicados**: Previene registros duplicados por lote y fecha
6. **Control de stock**: Asigna stock_actual inicial automáticamente
7. **Paginación**: Divide listado en páginas de 15 registros
8. **Orden cronológico**: Muestra registros más recientes primero

---

## Tabla de Base de Datos

### **control_insumos**

Campos:
- `id`: INT, Primary Key, Auto Increment
- `fecha`: DATE, fecha de ingreso del insumo
- `producto_insumo`: VARCHAR(255), nombre del insumo
- `cantidad`: DECIMAL(10,2), cantidad ingresada
- `unidad_medida`: VARCHAR(50), unidad (kg, litros, unidades)
- `numero_lote`: VARCHAR(100), NULLABLE, código de lote
- `fecha_vencimiento`: DATE, NULLABLE, fecha de caducidad
- `responsable`: VARCHAR(255), quién registró
- `proveedor`: VARCHAR(255), NULLABLE, empresa proveedora
- `observaciones`: TEXT, NULLABLE, notas adicionales
- `stock_actual`: DECIMAL(10,2), cantidad disponible actual
- `stock_minimo`: DECIMAL(10,2), nivel mínimo de alerta
- `created_at`: TIMESTAMP, fecha de creación del registro
- `updated_at`: TIMESTAMP, fecha de última actualización

---

## Rutas Web Necesarias

```php
Route::prefix('control/insumos')->group(function () {
    Route::get('/', [InsumosController::class, 'index'])->name('control.insumos.index');
    Route::get('/create', [InsumosController::class, 'create'])->name('control.insumos.create');
    Route::post('/', [InsumosController::class, 'store'])->name('control.insumos.store');
    Route::get('/{insumo}/edit', [InsumosController::class, 'edit'])->name('control.insumos.edit');
    Route::put('/{insumo}', [InsumosController::class, 'update'])->name('control.insumos.update');
    Route::delete('/{insumo}', [InsumosController::class, 'destroy'])->name('control.insumos.destroy');
});
```

---

## Flujo de Trabajo Típico

### Escenario 1: Registrar ingreso de cloro
1. Llega proveedor con 50 kg de cloro
2. Operador accede a `/control/insumos/create` → método `create()`
3. Llena formulario:
   - Fecha: 2025-12-02
   - Producto: "Cloro líquido"
   - Cantidad: 50
   - Unidad: "kg"
   - Lote: "CL-2025-12-001"
   - Vencimiento: 2026-12-02
   - Responsable: "Juan Pérez"
   - Proveedor: "Químicos ABC S.A."
4. Submit → método `store()`
5. Sistema:
   - Valida datos
   - Verifica que no exista registro duplicado del mismo lote en la misma fecha
   - Asigna stock_actual = 50
   - Asigna stock_minimo = 0
   - Crea registro
6. Redirige a index con mensaje "Registro de insumo creado exitosamente"

### Escenario 2: Intento de duplicado
1. Operador intenta registrar nuevamente el mismo lote del escenario 1
2. Submit → método `store()`
3. Sistema:
   - Detecta duplicado (mismo producto + lote + fecha)
   - Retorna con error: "Ya existe un registro de Cloro líquido con el lote CL-2025-12-001 en la fecha 02/12/2025"
4. Operador verifica y se da cuenta del error

### Escenario 3: Editar registro con error
1. Se registró cantidad incorrecta (50 en lugar de 500)
2. Administrador va a `/control/insumos/{id}/edit` → método `edit()`
3. Corrige cantidad a 500
4. Submit → método `update()`
5. Registro actualizado correctamente

---

## Tipos de Insumos Comunes

### Químicos:
- Cloro líquido (kg/litros)
- Ozono (unidades)
- Desinfectantes (litros)

### Material de envasado:
- Botellones 20L (unidades)
- Tapas (unidades)
- Etiquetas (unidades)
- Sellos de seguridad (unidades)

### Material de limpieza:
- Jabón industrial (kg)
- Desengrasante (litros)
- Escobas (unidades)

### Otros:
- Guantes (pares)
- Uniformes (unidades)
- EPP (Equipo de Protección Personal)

---

## Integración con Otros Módulos

### 1. Producción Diaria
- Al producir agua, se debe descontar insumos usados
- Ejemplo: Usar 5 kg de cloro reduce stock_actual

```php
// En ProduccionDiariaController
$insumo = Insumo::where('producto_insumo', 'Cloro')->first();
$insumo->decrement('stock_actual', 5); // Descontar 5 kg
```

### 2. Alertas de Stock Bajo
- Sistema debe alertar cuando stock_actual < stock_minimo
- Enviar notificación al administrador
- Generar orden de compra automática

### 3. Reportes de Consumo
- Dashboard con consumo mensual por insumo
- Gráficos de tendencia
- Proyección de necesidades

---

## Mejoras Futuras Sugeridas

### 1. Categorías de insumos:
```php
'categoria' => 'required|in:quimicos,envases,limpieza,epp'
```

### 2. Alertas automáticas de stock bajo:
```php
if ($insumo->stock_actual <= $insumo->stock_minimo) {
    // Enviar notificación
    Notification::send($admin, new StockBajoNotification($insumo));
}
```

### 3. Historial de movimientos:
Tabla separada `control_movimientos_insumos`:
- Entradas (compras)
- Salidas (uso en producción)
- Ajustes (inventario físico)

### 4. Cálculo automático de costo:
```php
'precio_unitario' => 'required|numeric|min:0',
'costo_total' => $cantidad * $precio_unitario
```

### 5. Control de vencimientos:
Job diario que alerta de insumos próximos a vencer:
```php
$proximosAVencer = Insumo::whereBetween('fecha_vencimiento', [now(), now()->addDays(30)])->get();
```

### 6. Escaneo de código de barras:
- Lectura automática de lote y vencimiento
- Registro rápido

### 7. Soft delete:
```php
use SoftDeletes;

$insumo->delete(); // Marca como eliminado, no borra
```

### 8. Auditoría de cambios:
```php
// Guardar quién editó y cuándo
'editado_por' => auth()->id(),
'editado_en' => now()
```

### 9. Importación masiva:
- Excel con múltiples insumos
- Validación por lotes

### 10. Integración con proveedores:
- API para recibir facturas electrónicas
- Actualización automática de stock

---

## Validaciones Adicionales Recomendadas

### 1. Fecha no futura:
```php
'fecha' => 'required|date|before_or_equal:today',
```

### 2. Vencimiento posterior a fecha de ingreso:
```php
'fecha_vencimiento' => 'nullable|date|after:fecha',
```

### 3. Cantidad máxima razonable:
```php
'cantidad' => 'required|numeric|min:0|max:999999',
```

### 4. Formato de lote estandarizado:
```php
'numero_lote' => 'nullable|string|regex:/^[A-Z]{2}-\d{4}-\d{2}-\d{3}$/',
// Formato: XX-YYYY-MM-NNN (ej: CL-2025-12-001)
```

---

## Consultas Útiles

### Insumos próximos a vencer:
```php
$proximosVencer = Insumo::whereDate('fecha_vencimiento', '<=', now()->addDays(30))
    ->orderBy('fecha_vencimiento')
    ->get();
```

### Insumos con stock bajo:
```php
$stockBajo = Insumo::whereColumn('stock_actual', '<=', 'stock_minimo')
    ->get();
```

### Consumo mensual:
```php
$consumo = Insumo::selectRaw('producto_insumo, SUM(cantidad) as total')
    ->whereMonth('fecha', now()->month)
    ->groupBy('producto_insumo')
    ->get();
```

### Insumos por proveedor:
```php
$porProveedor = Insumo::selectRaw('proveedor, COUNT(*) as total_ingresos')
    ->groupBy('proveedor')
    ->orderByDesc('total_ingresos')
    ->get();
```

---

## Seguridad Implementada

### 1. Validaciones exhaustivas:
- Tipos de datos verificados
- Rangos numéricos controlados
- Longitudes máximas definidas

### 2. Prevención de duplicados:
- Validación por lote + fecha + producto
- Evita errores de doble ingreso

### 3. SQL Injection Protection:
- Eloquent ORM usa prepared statements
- Inputs sanitizados automáticamente

### 4. Route Model Binding:
- Laravel automáticamente valida que el ID exista
- Error 404 si no se encuentra

---

## Dependencias

- **Laravel 8+**: Framework
- **Eloquent ORM**: Manejo de BD
- **Blade**: Vistas
- **Carbon**: Fechas (incluido en Laravel)
- **MySQL/PostgreSQL**: Base de datos

---

## Testing Recomendado

### Test 1: Crear insumo:
```php
$response = $this->post('/control/insumos', [
    'fecha' => '2025-12-02',
    'producto_insumo' => 'Cloro',
    'cantidad' => 50,
    'unidad_medida' => 'kg',
    'responsable' => 'Test Usuario',
]);
$response->assertRedirect();
$this->assertDatabaseHas('control_insumos', ['producto_insumo' => 'Cloro']);
```

### Test 2: Prevenir duplicados:
```php
Insumo::create([
    'fecha' => '2025-12-02',
    'producto_insumo' => 'Cloro',
    'numero_lote' => 'LOT-001',
    'cantidad' => 50,
    'unidad_medida' => 'kg',
    'responsable' => 'Test',
]);

$response = $this->post('/control/insumos', [
    'fecha' => '2025-12-02',
    'producto_insumo' => 'Cloro',
    'numero_lote' => 'LOT-001',
    'cantidad' => 50,
    'unidad_medida' => 'kg',
    'responsable' => 'Test',
]);

$response->assertSessionHasErrors();
```

---

## Conclusión

Este controlador es esencial para:
- Control de inventario de materias primas
- Trazabilidad de lotes
- Gestión de vencimientos
- Planificación de compras
- Costos de producción

Es la base del módulo de inventario y se conecta con producción, compras y reportes.
