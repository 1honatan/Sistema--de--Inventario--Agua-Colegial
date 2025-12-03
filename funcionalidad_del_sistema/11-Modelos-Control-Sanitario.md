# 11. MODELOS DE CONTROL SANITARIO

## 📋 ÍNDICE DE CONTENIDO

1. [FosaSeptica.php - Limpieza de Fosa Séptica](#fosasepticaphp)
2. [Fumigacion.php - Control de Fumigaciones](#fumigacionphp)
3. [TanqueAgua.php - Limpieza de Tanques de Agua](#tanqueaguaphp)
4. [MantenimientoEquipo.php - Mantenimiento de Equipos](#mantenimientoequipophp)
5. [Resumen de Funcionalidades](#resumen)
6. [Tablas de Base de Datos](#tablas)
7. [Flujos de Trabajo](#flujos)
8. [TODOs y Mejoras Futuras](#todos)

---

## 🎯 PROPÓSITO GENERAL

Este documento explica **línea por línea** cuatro modelos del subdirectorio `app/Models/Control/` que gestionan el **control sanitario** de la planta de producción:

1. **FosaSeptica.php**: Registro de limpieza de fosa séptica (normativa sanitaria)
2. **Fumigacion.php**: Control de fumigaciones contra plagas
3. **TanqueAgua.php**: Limpieza y desinfección de tanques de agua
4. **MantenimientoEquipo.php**: Mantenimiento de equipos de producción

**¿Por qué son críticos?**
Estos modelos cumplen con **Buenas Prácticas de Manufactura (BPM)** requeridas para producción de agua purificada. Son obligatorios para certificaciones sanitarias.

---

# FOSASEPTICA.PHP

**Ubicación**: `app/Models/Control/FosaSeptica.php`
**Líneas totales**: 38
**Complejidad**: Baja
**Propósito**: Registrar limpiezas de fosa séptica según normativa sanitaria

---

## 📖 EXPLICACIÓN LÍNEA POR LÍNEA

### 🟢 SECCIÓN 1: DECLARACIONES Y NAMESPACE (Líneas 1-8)

```php
<?php

namespace App\Models\Control;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Personal;
```
**¿Qué hace?** Importa clases necesarias.
**¿De dónde sale?** Framework Laravel.
**¿Para qué sirve?**
- `namespace App\Models\Control`: Organiza modelos de control sanitario
- `Model`: Clase base de Eloquent
- `BelongsTo`: Para relaciones (comentada, pendiente de implementar)
- `Personal`: Modelo de empleados (no usado aún)

**Nota**: Este modelo está en subdirectorio `Control/` para separar modelos de control sanitario de modelos principales.

---

### 🟢 SECCIÓN 2: CLASE Y CONFIGURACIÓN (Líneas 9-26)

```php
class FosaSeptica extends Model
{
    protected $table = 'control_fosa_septica';
```
**¿Qué hace?** Define clase y tabla asociada.
**¿De dónde sale?** Eloquent ORM.
**¿Para qué sirve?** Mapear modelo a tabla `control_fosa_septica`.

**Nota**: No usa `declare(strict_types=1)` como otros modelos recientes. Es código legacy.

---

```php
    protected $fillable = [
        'fecha_limpieza',
        'tipo_fosa',
        'responsable',
        'detalle_trabajo',
        'empresa_contratada',
        'proxima_limpieza',
        'observaciones',
    ];
```
**¿Qué hace?** Define campos asignables masivamente.
**¿De dónde sale?** Protección Mass Assignment.
**¿Para qué sirve?** Permitir `FosaSeptica::create($data)`.

**Campos explicados**:
- `fecha_limpieza`: Fecha en que se realizó la limpieza
- `tipo_fosa`: Tipo de fosa séptica (ej: "Principal", "Secundaria")
- `responsable`: Nombre del responsable (⚠️ STRING en lugar de FK)
- `detalle_trabajo`: Descripción del trabajo realizado
- `empresa_contratada`: Empresa externa que hizo la limpieza (opcional)
- `proxima_limpieza`: Fecha programada para próxima limpieza
- `observaciones`: Notas adicionales

**Ejemplo de uso**:
```php
FosaSeptica::create([
    'fecha_limpieza' => '2025-12-01',
    'tipo_fosa' => 'Principal',
    'responsable' => 'Juan Pérez',
    'detalle_trabajo' => 'Limpieza completa con extracción de lodos',
    'empresa_contratada' => 'Servicios Ambientales S.A.',
    'proxima_limpieza' => '2026-06-01',
    'observaciones' => 'Se encontró acumulación moderada'
]);
```

---

```php
    protected $casts = [
        'fecha_limpieza' => 'date',
        'proxima_limpieza' => 'date',
    ];
```
**¿Qué hace?** Convierte campos a objetos Carbon.
**¿De dónde sale?** Eloquent Casting.
**¿Para qué sirve?** Manipular fechas fácilmente.

**Diferencia con otros modelos**:
- Usa `'date'` en lugar de `'datetime'` (solo fecha, sin hora)
- Carbon de todas formas permite operaciones

**Ejemplo**:
```php
$limpieza = FosaSeptica::find(1);

// Verificar si está vencida la próxima limpieza
if ($limpieza->proxima_limpieza->isPast()) {
    echo "¡Limpieza vencida!";
}

// Calcular días restantes
$diasRestantes = now()->diffInDays($limpieza->proxima_limpieza);
echo "Faltan {$diasRestantes} días para próxima limpieza";

// Formato personalizado
echo $limpieza->fecha_limpieza->format('d/m/Y'); // "01/12/2025"
```

---

### 🟢 SECCIÓN 3: RELACIONES COMENTADAS (Líneas 28-37)

```php
    /**
     * Relación: Responsable de la limpieza de fosa séptica
     * Nota: Actualmente 'responsable' es un string.
     * TODO: Migrar a responsable_id (foreignId a personal)
     */
    // public function responsablePersonal(): BelongsTo
    // {
    //     return $this->belongsTo(Personal::class, 'responsable_id');
    // }
}
```
**¿Qué hace?** Código comentado para futura relación con Personal.
**¿De dónde sale?** Patrón de diseño pendiente de implementar.
**¿Para qué sirve?** Documentar la mejora futura.

**¿Por qué está comentado?**
- El campo actual es `responsable` (VARCHAR) con nombres en texto plano
- La mejora requiere:
  1. Migración para agregar campo `responsable_id`
  2. Migración de datos: convertir nombres a IDs
  3. Descomentar este método
  4. Actualizar controller y vistas

**Implementación futura**:
```php
// Migración
Schema::table('control_fosa_septica', function (Blueprint $table) {
    $table->foreignId('responsable_id')->nullable()->constrained('personal');
    // Mantener 'responsable' temporalmente para migración de datos
});

// Migrar datos
FosaSeptica::chunk(100, function ($limpiezas) {
    foreach ($limpiezas as $limpieza) {
        $personal = Personal::where('nombres', 'LIKE', '%' . $limpieza->responsable . '%')->first();
        if ($personal) {
            $limpieza->responsable_id = $personal->id;
            $limpieza->save();
        }
    }
});

// Eliminar columna antigua
Schema::table('control_fosa_septica', function (Blueprint $table) {
    $table->dropColumn('responsable');
});

// Descomentar método
public function responsablePersonal(): BelongsTo
{
    return $this->belongsTo(Personal::class, 'responsable_id');
}
```

**Uso después de implementar**:
```php
$limpieza = FosaSeptica::with('responsablePersonal')->first();

// ✅ Ahora con relación:
echo $limpieza->responsablePersonal->nombres_completos;
echo $limpieza->responsablePersonal->puesto;

// ❌ Antes (solo string):
echo $limpieza->responsable; // "Juan Pérez"
```

---

# FUMIGACION.PHP

**Ubicación**: `app/Models/Control/Fumigacion.php`
**Líneas totales**: 40
**Complejidad**: Baja
**Propósito**: Registrar fumigaciones contra plagas según BPM

---

## 📖 EXPLICACIÓN LÍNEA POR LÍNEA

### 🟢 SECCIÓN 1: CLASE Y CONFIGURACIÓN (Líneas 1-28)

```php
<?php

namespace App\Models\Control;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Personal;

class Fumigacion extends Model
{
    protected $table = 'control_fumigacion';
```
**¿Qué hace?** Define clase y tabla asociada.
**¿De dónde sale?** Eloquent ORM.
**¿Para qué sirve?** Mapear modelo a tabla `control_fumigacion`.

---

```php
    protected $fillable = [
        'fecha_fumigacion',
        'area_fumigada',
        'producto_utilizado',
        'cantidad_producto',
        'responsable',
        'empresa_contratada',
        'proxima_fumigacion',
        'observaciones',
    ];
```
**¿Qué hace?** Define campos asignables masivamente.
**¿De dónde sale?** Protección Mass Assignment.
**¿Para qué sirve?** Permitir `Fumigacion::create($data)`.

**Campos explicados**:
- `fecha_fumigacion`: Fecha de la fumigación
- `area_fumigada`: Área tratada (ej: "Bodega de insumos", "Área de producción")
- `producto_utilizado`: Nombre del producto químico usado
- `cantidad_producto`: Cantidad en litros o kg
- `responsable`: Nombre del responsable (⚠️ STRING)
- `empresa_contratada`: Empresa externa (opcional)
- `proxima_fumigacion`: Fecha programada para próxima fumigación
- `observaciones`: Notas adicionales

**¿Por qué es importante este modelo?**
- Obligatorio para certificación sanitaria
- Control de plagas previene contaminación
- Rastreo de productos químicos usados
- Cumplimiento de normativa BPM

**Ejemplo de uso**:
```php
Fumigacion::create([
    'fecha_fumigacion' => '2025-12-01',
    'area_fumigada' => 'Bodega de insumos y área de producción',
    'producto_utilizado' => 'Cipermetrina 10% EC',
    'cantidad_producto' => 2.5, // Litros
    'responsable' => 'María López',
    'empresa_contratada' => 'Control de Plagas Total',
    'proxima_fumigacion' => '2026-03-01',
    'observaciones' => 'No se detectaron plagas activas'
]);
```

---

```php
    protected $casts = [
        'fecha_fumigacion' => 'date',
        'proxima_fumigacion' => 'date',
        'cantidad_producto' => 'decimal:2',
    ];
```
**¿Qué hace?** Convierte campos automáticamente.
**¿De dónde sale?** Eloquent Casting.
**¿Para qué sirve?** Manipular fechas y decimales.

**Nota especial**: `'decimal:2'` asegura 2 decimales (ej: 2.50 litros).

**Ejemplo**:
```php
$fumigacion = Fumigacion::find(1);

// Verificar si próxima fumigación está cerca
$diasRestantes = now()->diffInDays($fumigacion->proxima_fumigacion);
if ($diasRestantes <= 7) {
    // Enviar alerta
    Mail::to('admin@aguacolegial.com')->send(new ProximaFumigacionMail($fumigacion));
}

// Formato decimal
echo $fumigacion->cantidad_producto; // 2.50 (siempre 2 decimales)
```

---

### 🟢 SECCIÓN 2: RELACIONES COMENTADAS (Líneas 30-39)

```php
    /**
     * Relación: Responsable de la fumigación
     * Nota: Actualmente 'responsable' es un string.
     * TODO: Migrar a responsable_id (foreignId a personal)
     */
    // public function responsablePersonal(): BelongsTo
    // {
    //     return $this->belongsTo(Personal::class, 'responsable_id');
    // }
}
```
**¿Qué hace?** Código comentado para futura relación con Personal.
**¿De dónde sale?** Patrón de diseño pendiente.
**¿Para qué sirve?** Mismo TODO que FosaSeptica (migrar strings a FKs).

**Implementación futura**: Igual que FosaSeptica (ver sección anterior).

---

# TANQUEAGUA.PHP

**Ubicación**: `app/Models/Control/TanqueAgua.php`
**Líneas totales**: 51
**Complejidad**: Baja
**Propósito**: Registrar limpieza y desinfección de tanques de agua

---

## 📖 EXPLICACIÓN LÍNEA POR LÍNEA

### 🟢 SECCIÓN 1: CLASE Y CONFIGURACIÓN (Líneas 1-29)

```php
<?php

namespace App\Models\Control;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Personal;

class TanqueAgua extends Model
{
    protected $table = 'control_tanques_agua';
```
**¿Qué hace?** Define clase y tabla asociada.
**¿De dónde sale?** Eloquent ORM.
**¿Para qué sirve?** Mapear modelo a tabla `control_tanques_agua`.

---

```php
    protected $fillable = [
        'fecha_limpieza',
        'nombre_tanque',
        'capacidad_litros',
        'procedimiento_limpieza',
        'productos_desinfeccion',
        'responsable',
        'supervisado_por',
        'proxima_limpieza',
        'observaciones',
    ];
```
**¿Qué hace?** Define campos asignables masivamente.
**¿De dónde sale?** Protección Mass Assignment.
**¿Para qué sirve?** Permitir `TanqueAgua::create($data)`.

**Campos explicados**:
- `fecha_limpieza`: Fecha de la limpieza
- `nombre_tanque`: Identificador del tanque (ej: "Tanque Principal 1", "Tanque Reserva")
- `capacidad_litros`: Capacidad en litros (para tracking)
- `procedimiento_limpieza`: Descripción del procedimiento seguido
- `productos_desinfeccion`: Productos químicos usados (ej: "Cloro, Jabón industrial")
- `responsable`: Nombre del responsable (⚠️ STRING)
- `supervisado_por`: Nombre del supervisor (⚠️ STRING)
- `proxima_limpieza`: Fecha programada para próxima limpieza
- `observaciones`: Notas adicionales

**¿Por qué es crítico este modelo?**
- Agua purificada requiere limpieza periódica de tanques
- Previene contaminación microbiológica
- Obligatorio para certificación sanitaria
- Rastreo de productos desinfectantes

**Ejemplo de uso**:
```php
TanqueAgua::create([
    'fecha_limpieza' => '2025-12-01',
    'nombre_tanque' => 'Tanque Principal 1',
    'capacidad_litros' => 10000,
    'procedimiento_limpieza' => 'Vaciado completo, lavado con detergente industrial, enjuague, desinfección con cloro 200ppm, enjuague final',
    'productos_desinfeccion' => 'Cloro granulado 65%, Detergente industrial',
    'responsable' => 'Carlos Ruiz',
    'supervisado_por' => 'Ana Torres',
    'proxima_limpieza' => '2026-06-01',
    'observaciones' => 'Tanque en excelente estado, sin sedimentos'
]);
```

---

```php
    protected $casts = [
        'fecha_limpieza' => 'date',
        'proxima_limpieza' => 'date',
        'capacidad_litros' => 'decimal:2',
    ];
```
**¿Qué hace?** Convierte campos automáticamente.
**¿De dónde sale?** Eloquent Casting.
**¿Para qué sirve?** Manipular fechas y decimales.

**Ejemplo**:
```php
$limpieza = TanqueAgua::find(1);

// Verificar si limpieza está vencida
if ($limpieza->proxima_limpieza->isPast()) {
    // Generar alerta
    Notification::send(
        User::role('admin')->get(),
        new LimpiezaTanqueVencidaNotification($limpieza)
    );
}

// Mostrar capacidad formateada
echo number_format($limpieza->capacidad_litros, 0, ',', '.') . " L"; // "10.000 L"

// Días desde última limpieza
$diasDesdeUltimaLimpieza = $limpieza->fecha_limpieza->diffInDays(now());
if ($diasDesdeUltimaLimpieza > 180) {
    echo "¡Atención! Más de 6 meses desde última limpieza";
}
```

---

### 🟢 SECCIÓN 2: RELACIONES COMENTADAS (Líneas 31-50)

```php
    /**
     * Relación: Responsable de la limpieza del tanque
     * Nota: Actualmente 'responsable' es un string.
     * TODO: Migrar a responsable_id (foreignId a personal)
     */
    // public function responsablePersonal(): BelongsTo
    // {
    //     return $this->belongsTo(Personal::class, 'responsable_id');
    // }
```
**¿Qué hace?** Código comentado para futura relación con Personal.
**¿De dónde sale?** TODO de migración de strings a FKs.
**¿Para qué sirve?** Permitir acceso a datos completos del responsable.

---

```php
    /**
     * Relación: Supervisor de la limpieza
     * Nota: Actualmente 'supervisado_por' es un string.
     * TODO: Migrar a supervisado_por_id (foreignId a personal)
     */
    // public function supervisor(): BelongsTo
    // {
    //     return $this->belongsTo(Personal::class, 'supervisado_por_id');
    // }
}
```
**¿Qué hace?** Código comentado para relación de supervisor.
**¿De dónde sale?** TODO de migración.
**¿Para qué sirve?** Permitir acceso a datos completos del supervisor.

**Diferencia clave**: Este modelo tiene **2 relaciones con Personal** (responsable Y supervisor).

**Implementación futura**:
```php
// Migración
Schema::table('control_tanques_agua', function (Blueprint $table) {
    $table->foreignId('responsable_id')->nullable()->constrained('personal');
    $table->foreignId('supervisado_por_id')->nullable()->constrained('personal');
});

// Modelo después de migrar
public function responsablePersonal(): BelongsTo
{
    return $this->belongsTo(Personal::class, 'responsable_id');
}

public function supervisor(): BelongsTo
{
    return $this->belongsTo(Personal::class, 'supervisado_por_id');
}

// Uso
$limpieza = TanqueAgua::with(['responsablePersonal', 'supervisor'])->first();

echo "Responsable: " . $limpieza->responsablePersonal->nombres_completos;
echo "Supervisor: " . $limpieza->supervisor->nombres_completos;
echo "Puesto supervisor: " . $limpieza->supervisor->puesto;
```

---

# MANTENIMIENTOEQUIPO.PHP

**Ubicación**: `app/Models/Control/MantenimientoEquipo.php`
**Líneas totales**: 39
**Complejidad**: Baja-Media
**Propósito**: Registrar mantenimiento de equipos de producción

---

## 📖 EXPLICACIÓN LÍNEA POR LÍNEA

### 🟢 SECCIÓN 1: CLASE Y CONFIGURACIÓN (Líneas 1-22)

```php
<?php

namespace App\Models\Control;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Personal;

class MantenimientoEquipo extends Model
{
    protected $table = 'control_mantenimiento_equipos';
```
**¿Qué hace?** Define clase y tabla asociada.
**¿De dónde sale?** Eloquent ORM.
**¿Para qué sirve?** Mapear modelo a tabla `control_mantenimiento_equipos`.

---

```php
    protected $fillable = [
        'fecha',
        'equipo',
        'id_personal',
        'detalle_mantenimiento',
        'productos_limpieza',
        'proxima_fecha',
        'realizado_por',
        'supervisado_por',
    ];
```
**¿Qué hace?** Define campos asignables masivamente.
**¿De dónde sale?** Protección Mass Assignment.
**¿Para qué sirve?** Permitir `MantenimientoEquipo::create($data)`.

**Campos explicados**:
- `fecha`: Fecha del mantenimiento
- `equipo`: Equipos mantenidos (⚠️ ARRAY JSON)
- `id_personal`: FK a personal (✅ ÚNICO modelo con FK en lugar de string)
- `detalle_mantenimiento`: Descripción autogenerada del trabajo
- `productos_limpieza`: Productos usados (⚠️ ARRAY JSON)
- `proxima_fecha`: Fecha programada para próximo mantenimiento
- `realizado_por`: Nombre de quien lo realizó (⚠️ STRING)
- `supervisado_por`: Nombre del supervisor (⚠️ STRING)

**Diferencia clave con otros modelos**:
Este es el **único** modelo de control sanitario que YA usa FK (`id_personal`) en lugar de string para el responsable principal.

---

```php
    protected $casts = [
        'fecha' => 'date',
        'proxima_fecha' => 'date',
        'equipo' => 'array',
        'productos_limpieza' => 'array',
    ];
```
**¿Qué hace?** Convierte campos automáticamente.
**¿De dónde sale?** Eloquent Casting.
**¿Para qué sirve?** Manipular fechas y arrays JSON.

**Diferencia clave**: Cast de `'array'` para campos JSON.

**¿Qué hace el cast 'array'?**
```php
// Sin cast:
$mantenimiento->equipo; // '["Envasadora","Filtros"]' (string JSON)

// Con cast:
$mantenimiento->equipo; // ['Envasadora', 'Filtros'] (array PHP)

// Manipulación:
$mantenimiento->equipo = ['Envasadora', 'Filtros', 'Bombas'];
$mantenimiento->save();
// BD: '["Envasadora","Filtros","Bombas"]' (JSON)
```

**Ejemplo de uso completo**:
```php
// Crear mantenimiento
$mantenimiento = MantenimientoEquipo::create([
    'fecha' => '2025-12-01',
    'equipo' => ['Envasadora automática', 'Filtros de carbón', 'Bomba principal'],
    'id_personal' => 5, // FK a personal
    'detalle_mantenimiento' => 'Mantenimiento preventivo mensual',
    'productos_limpieza' => ['Desengrasante industrial', 'Alcohol isopropílico', 'Jabón neutro'],
    'proxima_fecha' => '2026-01-01',
    'realizado_por' => 'Carlos Ruiz',
    'supervisado_por' => 'Ana Torres'
]);

// Acceder a equipos (ya es array)
foreach ($mantenimiento->equipo as $equipo) {
    echo "- " . $equipo . "\n";
}

// Agregar equipo
$equipos = $mantenimiento->equipo;
$equipos[] = 'Ozono';
$mantenimiento->equipo = $equipos;
$mantenimiento->save();

// Verificar si se usó un producto específico
if (in_array('Alcohol isopropílico', $mantenimiento->productos_limpieza)) {
    echo "Se usó alcohol isopropílico";
}
```

---

### 🟢 SECCIÓN 2: RELACIÓN CON PERSONAL (Líneas 31-38)

```php
    /**
     * Relación con Personal (quien realizó el mantenimiento)
     */
    public function personal(): BelongsTo
    {
        return $this->belongsTo(Personal::class, 'id_personal');
    }
}
```
**¿Qué hace?** Define relación con Personal.
**¿De dónde sale?** Eloquent ORM.
**¿Para qué sirve?** Obtener datos completos del responsable.

**Flujo de datos**:
```
control_mantenimiento_equipos.id_personal (FK) → personal.id (PK)
```

**¿Por qué este modelo SÍ tiene relación activa?**
- Es más reciente que los otros 3 modelos
- Ya implementa la mejora que los otros tienen como TODO
- Usa FK en lugar de string

**Ejemplo de uso**:
```php
$mantenimiento = MantenimientoEquipo::with('personal')->find(1);

// ✅ Con relación:
echo "Responsable: " . $mantenimiento->personal->nombres_completos;
echo "Puesto: " . $mantenimiento->personal->puesto;
echo "Email: " . $mantenimiento->personal->email;

// Filtrar mantenimientos por personal
$mantenimientosJuan = MantenimientoEquipo::whereHas('personal', function($q) {
    $q->where('nombres', 'LIKE', '%Juan%');
})->get();

// Eager loading
$mantenimientos = MantenimientoEquipo::with('personal')
    ->whereMonth('fecha', 12)
    ->get();

foreach ($mantenimientos as $m) {
    echo "{$m->personal->nombres} - {$m->fecha->format('d/m/Y')}";
}
```

**TODOs pendientes**:
- Campos `realizado_por` y `supervisado_por` siguen siendo strings
- Deberían ser `realizado_por_id` y `supervisado_por_id` (FKs)

---

## 📊 RESUMEN DE FUNCIONALIDADES

| Modelo | Propósito | Complejidad | Relaciones |
|--------|-----------|-------------|------------|
| FosaSeptica | Limpieza de fosa séptica | Baja | Ninguna (TODO) |
| Fumigacion | Control de fumigaciones | Baja | Ninguna (TODO) |
| TanqueAgua | Limpieza de tanques de agua | Baja | Ninguna (TODO) |
| MantenimientoEquipo | Mantenimiento de equipos | Baja-Media | Personal (✅ implementada) |

### Características Comunes

| Característica | Descripción |
|----------------|-------------|
| Propósito | Cumplimiento de BPM (Buenas Prácticas de Manufactura) |
| Obligatoriedad | Requeridos para certificación sanitaria |
| Campos de fecha | Fecha de acción + próxima fecha (seguimiento) |
| Observaciones | Campo libre para notas |
| Empresa contratada | Permite registro de servicios externos |
| Responsable | Campo para auditoría |

### Diferencias Clave

| Aspecto | FosaSeptica | Fumigacion | TanqueAgua | MantenimientoEquipo |
|---------|-------------|------------|------------|---------------------|
| Frecuencia | Semestral | Trimestral | Semestral | Mensual |
| Relaciones activas | ❌ | ❌ | ❌ | ✅ Personal |
| Campos JSON | ❌ | ❌ | ❌ | ✅ equipo, productos_limpieza |
| Supervisores | 1 | 1 | 2 | 2 |
| Productos químicos | ❌ | ✅ | ✅ | ✅ |
| Cantidad producto | ❌ | ✅ decimal | ❌ | ❌ |

---

## 🗄️ TABLAS DE BASE DE DATOS

### Tabla: `control_fosa_septica`

```sql
CREATE TABLE control_fosa_septica (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    fecha_limpieza DATE NOT NULL,
    tipo_fosa VARCHAR(100) NOT NULL,
    responsable VARCHAR(255) NOT NULL,
    detalle_trabajo TEXT NOT NULL,
    empresa_contratada VARCHAR(255) NULL,
    proxima_limpieza DATE NOT NULL,
    observaciones TEXT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,

    INDEX idx_fecha_limpieza (fecha_limpieza),
    INDEX idx_proxima_limpieza (proxima_limpieza)
);
```

**Datos de ejemplo**:
```
| id | fecha_limpieza | tipo_fosa  | responsable | detalle_trabajo                    | proxima_limpieza |
|----|----------------|------------|-------------|------------------------------------|------------------|
| 1  | 2025-06-01     | Principal  | Juan Pérez  | Limpieza completa con extracción   | 2025-12-01       |
| 2  | 2025-06-15     | Secundaria | María López | Mantenimiento preventivo           | 2025-12-15       |
```

---

### Tabla: `control_fumigacion`

```sql
CREATE TABLE control_fumigacion (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    fecha_fumigacion DATE NOT NULL,
    area_fumigada VARCHAR(255) NOT NULL,
    producto_utilizado VARCHAR(255) NOT NULL,
    cantidad_producto DECIMAL(10,2) NOT NULL,
    responsable VARCHAR(255) NOT NULL,
    empresa_contratada VARCHAR(255) NULL,
    proxima_fumigacion DATE NOT NULL,
    observaciones TEXT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,

    INDEX idx_fecha_fumigacion (fecha_fumigacion),
    INDEX idx_proxima_fumigacion (proxima_fumigacion)
);
```

**Datos de ejemplo**:
```
| id | fecha_fumigacion | area_fumigada        | producto_utilizado | cantidad_producto | proxima_fumigacion |
|----|------------------|----------------------|--------------------|-------------------|--------------------|
| 1  | 2025-09-01       | Bodega de insumos    | Cipermetrina 10%   | 2.50              | 2025-12-01         |
| 2  | 2025-09-15       | Área de producción   | Permetrina 25%     | 1.75              | 2025-12-15         |
```

---

### Tabla: `control_tanques_agua`

```sql
CREATE TABLE control_tanques_agua (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    fecha_limpieza DATE NOT NULL,
    nombre_tanque VARCHAR(100) NOT NULL,
    capacidad_litros DECIMAL(10,2) NOT NULL,
    procedimiento_limpieza TEXT NOT NULL,
    productos_desinfeccion TEXT NOT NULL,
    responsable VARCHAR(255) NOT NULL,
    supervisado_por VARCHAR(255) NOT NULL,
    proxima_limpieza DATE NOT NULL,
    observaciones TEXT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,

    INDEX idx_fecha_limpieza (fecha_limpieza),
    INDEX idx_proxima_limpieza (proxima_limpieza),
    INDEX idx_nombre_tanque (nombre_tanque)
);
```

**Datos de ejemplo**:
```
| id | fecha_limpieza | nombre_tanque      | capacidad_litros | responsable | supervisado_por | proxima_limpieza |
|----|----------------|--------------------|------------------|-------------|-----------------|------------------|
| 1  | 2025-06-01     | Tanque Principal 1 | 10000.00         | Carlos Ruiz | Ana Torres      | 2025-12-01       |
| 2  | 2025-06-15     | Tanque Reserva     | 5000.00          | Juan Pérez  | Ana Torres      | 2025-12-15       |
```

---

### Tabla: `control_mantenimiento_equipos`

```sql
CREATE TABLE control_mantenimiento_equipos (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    fecha DATE NOT NULL,
    equipo JSON NOT NULL COMMENT 'Array de equipos mantenidos',
    id_personal BIGINT UNSIGNED NOT NULL,
    detalle_mantenimiento TEXT NOT NULL,
    productos_limpieza JSON NOT NULL COMMENT 'Array de productos usados',
    proxima_fecha DATE NOT NULL,
    realizado_por VARCHAR(255) NULL,
    supervisado_por VARCHAR(255) NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,

    FOREIGN KEY (id_personal) REFERENCES personal(id) ON DELETE RESTRICT,

    INDEX idx_fecha (fecha),
    INDEX idx_proxima_fecha (proxima_fecha),
    INDEX idx_id_personal (id_personal)
);
```

**Datos de ejemplo**:
```
| id | fecha      | equipo                               | id_personal | productos_limpieza                          | proxima_fecha |
|----|------------|--------------------------------------|-------------|---------------------------------------------|---------------|
| 1  | 2025-12-01 | ["Envasadora","Filtros","Bombas"]    | 5           | ["Desengrasante","Alcohol","Jabón neutro"]  | 2026-01-01    |
| 2  | 2025-12-05 | ["Ozono","Tanque de almacenamiento"] | 8           | ["Cloro","Detergente industrial"]           | 2026-01-05    |
```

**Nota sobre JSON**:
- MySQL 5.7+ soporta tipo JSON nativo
- Laravel convierte automáticamente con cast `'array'`
- Permite flexibilidad (número variable de equipos/productos)

---

## 🔄 FLUJOS DE TRABAJO

### Flujo 1: Registro de Limpieza de Fosa Séptica

```
[Usuario accede a formulario]
    ↓
1. Selecciona tipo de fosa: "Principal"
2. Selecciona fecha limpieza: "2025-12-01"
3. Ingresa responsable: "Juan Pérez" (⚠️ texto libre)
4. Describe trabajo: "Limpieza completa con extracción"
5. Opcional: Empresa contratada
6. Sistema calcula próxima limpieza: +6 meses
7. Observaciones opcionales
    ↓
[FosaSepticaController::store()]
    ↓
8. Validación:
   - Fecha limpieza no puede ser futura
   - Responsable requerido
   - Detalle trabajo requerido
   - Próxima limpieza debe ser posterior a fecha limpieza
    ↓
9. Si validación OK:
   FosaSeptica::create([...])
    ↓
10. Registro guardado en BD
11. Redirección con mensaje de éxito
    ↓
12. Sistema muestra alerta si próxima limpieza < 30 días
```

**Código del controller**:
```php
public function store(Request $request)
{
    $validated = $request->validate([
        'fecha_limpieza' => 'required|date|before_or_equal:today',
        'tipo_fosa' => 'required|string|max:100',
        'responsable' => 'required|string|max:255',
        'detalle_trabajo' => 'required|string',
        'empresa_contratada' => 'nullable|string|max:255',
        'proxima_limpieza' => 'required|date|after:fecha_limpieza',
        'observaciones' => 'nullable|string',
    ]);

    FosaSeptica::create($validated);

    return redirect()->route('control.fosa-septica.index')
        ->with('success', 'Limpieza de fosa séptica registrada correctamente');
}
```

---

### Flujo 2: Verificación de Fumigaciones Vencidas (Comando Programado)

```
[Comando ejecutado diariamente vía Cron]
    ↓
[App\Console\Commands\VerificarFumigacionesVencidas]
    ↓
1. Consultar fumigaciones con próxima_fumigacion <= HOY + 7 días
    ↓
[Fumigacion::where('proxima_fumigacion', '<=', now()->addDays(7))->get()]
    ↓
2. Si hay fumigaciones próximas:
     ↓
   2.1. Agrupar por responsable
   2.2. Generar notificación por email
   2.3. Enviar a admin y responsable
     ↓
3. Si hay fumigaciones vencidas (proxima_fumigacion < HOY):
     ↓
   3.1. Enviar alerta urgente
   3.2. Agregar a reporte de pendientes
     ↓
4. Log de resultados en storage/logs/fumigaciones.log
```

**Código del comando**:
```php
// app/Console/Commands/VerificarFumigacionesVencidas.php
class VerificarFumigacionesVencidas extends Command
{
    protected $signature = 'fumigaciones:verificar';

    public function handle()
    {
        // Fumigaciones próximas (7 días)
        $proximas = Fumigacion::where('proxima_fumigacion', '<=', now()->addDays(7))
            ->where('proxima_fumigacion', '>', now())
            ->get();

        foreach ($proximas as $fumigacion) {
            Mail::to('admin@aguacolegial.com')->send(
                new FumigacionProximaMail($fumigacion)
            );
        }

        // Fumigaciones vencidas
        $vencidas = Fumigacion::where('proxima_fumigacion', '<', now())->get();

        if ($vencidas->count() > 0) {
            Mail::to('admin@aguacolegial.com')->send(
                new FumigacionVencidaUrgenteMail($vencidas)
            );
        }

        $this->info("Verificación completada: {$proximas->count()} próximas, {$vencidas->count()} vencidas");
    }
}

// app/Console/Kernel.php
protected function schedule(Schedule $schedule)
{
    $schedule->command('fumigaciones:verificar')->daily();
}
```

---

### Flujo 3: Registro de Mantenimiento de Equipos con Relación Personal

```
[Usuario accede a formulario]
    ↓
1. Selecciona fecha: "2025-12-01"
2. Selecciona equipos (checkboxes):
   ☑ Envasadora automática
   ☑ Filtros de carbón
   ☑ Bomba principal
3. Selecciona responsable del dropdown: "Juan Pérez" (id=5) ✅ FK
4. Selecciona productos de limpieza (checkboxes):
   ☑ Desengrasante industrial
   ☑ Alcohol isopropílico
5. Sistema calcula próxima fecha: +30 días
6. Ingresa realizado_por: "Carlos Ruiz" (⚠️ texto libre)
7. Ingresa supervisado_por: "Ana Torres" (⚠️ texto libre)
    ↓
[MantenimientoController::store()]
    ↓
8. Validación:
   - Al menos 1 equipo seleccionado
   - id_personal existe en tabla personal
   - Al menos 1 producto de limpieza
    ↓
9. Generar detalle_mantenimiento automáticamente:
   "Mantenimiento de: Envasadora automática, Filtros de carbón, Bomba principal.
    Productos usados: Desengrasante industrial, Alcohol isopropílico."
    ↓
10. MantenimientoEquipo::create([
       'equipo' => ['Envasadora automática', 'Filtros de carbón', 'Bomba principal'],
       'id_personal' => 5, // FK
       'productos_limpieza' => ['Desengrasante industrial', 'Alcohol isopropílico'],
       ...
    ])
    ↓
11. Registro guardado (arrays convertidos a JSON automáticamente)
12. Redirección con éxito
```

**Código del controller**:
```php
public function store(Request $request)
{
    $validated = $request->validate([
        'fecha' => 'required|date|before_or_equal:today',
        'equipo' => 'required|array|min:1',
        'equipo.*' => 'required|string',
        'id_personal' => 'required|exists:personal,id',
        'productos_limpieza' => 'required|array|min:1',
        'productos_limpieza.*' => 'required|string',
        'proxima_fecha' => 'required|date|after:fecha',
        'realizado_por' => 'nullable|string|max:255',
        'supervisado_por' => 'nullable|string|max:255',
    ]);

    // Generar detalle automático
    $equipos = implode(', ', $validated['equipo']);
    $productos = implode(', ', $validated['productos_limpieza']);
    $validated['detalle_mantenimiento'] = "Mantenimiento de: {$equipos}. Productos usados: {$productos}.";

    MantenimientoEquipo::create($validated);

    return redirect()->route('control.mantenimiento.index')
        ->with('success', 'Mantenimiento registrado correctamente');
}
```

---

### Flujo 4: Reporte de Próximas Actividades de Control Sanitario

```
[Usuario accede a dashboard de control sanitario]
    ↓
[DashboardController::controlSanitario()]
    ↓
1. Consultar todas las actividades próximas (30 días):
     ↓
   1.1. FosaSeptica::where('proxima_limpieza', '<=', now()->addDays(30))->get()
   1.2. Fumigacion::where('proxima_fumigacion', '<=', now()->addDays(30))->get()
   1.3. TanqueAgua::where('proxima_limpieza', '<=', now()->addDays(30))->get()
   1.4. MantenimientoEquipo::where('proxima_fecha', '<=', now()->addDays(30))->get()
     ↓
2. Agrupar por tipo y urgencia:
   - Vencidas (rojo): fecha < HOY
   - Próximas (amarillo): fecha <= HOY + 7
   - Programadas (verde): fecha <= HOY + 30
     ↓
3. Ordenar por fecha ascendente
     ↓
4. Renderizar vista con tabla consolidada:
   | Tipo         | Descripción           | Fecha Programada | Estado    |
   |--------------|----------------------|------------------|-----------|
   | Fumigación   | Bodega de insumos    | 2025-11-28       | VENCIDA   |
   | Tanque       | Tanque Principal 1   | 2025-12-03       | PRÓXIMA   |
   | Mantenimiento| Envasadora, Filtros  | 2025-12-15       | PROGRAMADA|
   | Fosa Séptica | Principal            | 2025-12-20       | PROGRAMADA|
```

**Código del controller**:
```php
public function controlSanitario()
{
    $hoy = now();
    $limite = now()->addDays(30);

    // Consultar todas las actividades
    $fosas = FosaSeptica::whereBetween('proxima_limpieza', [$hoy, $limite])->get();
    $fumigaciones = Fumigacion::whereBetween('proxima_fumigacion', [$hoy, $limite])->get();
    $tanques = TanqueAgua::whereBetween('proxima_limpieza', [$hoy, $limite])->get();
    $mantenimientos = MantenimientoEquipo::whereBetween('proxima_fecha', [$hoy, $limite])
        ->with('personal')
        ->get();

    // Consolidar en array
    $actividades = collect();

    foreach ($fosas as $fosa) {
        $actividades->push([
            'tipo' => 'Fosa Séptica',
            'descripcion' => $fosa->tipo_fosa,
            'fecha' => $fosa->proxima_limpieza,
            'responsable' => $fosa->responsable,
            'estado' => $this->calcularEstado($fosa->proxima_limpieza)
        ]);
    }

    // Similar para fumigaciones, tanques, mantenimientos...

    // Ordenar por fecha
    $actividades = $actividades->sortBy('fecha');

    return view('admin.control-sanitario', [
        'actividades' => $actividades,
        'vencidas' => $actividades->where('estado', 'vencida')->count(),
        'proximas' => $actividades->where('estado', 'proxima')->count(),
    ]);
}

private function calcularEstado($fecha)
{
    if ($fecha < now()) return 'vencida';
    if ($fecha <= now()->addDays(7)) return 'proxima';
    return 'programada';
}
```

---

## ✅ TODOS Y MEJORAS FUTURAS

### TODO 1: Migrar campos de texto a Foreign Keys (PRIORITARIO)

**Afecta a**: FosaSeptica, Fumigacion, TanqueAgua

**Problema**:
- Campos `responsable`, `supervisado_por`, `realizado_por` son strings
- No hay validación de existencia
- Duplicación de nombres (ej: "Juan Perez" vs "Juan Pérez")
- No se puede acceder a datos completos del personal

**Solución**:
```sql
-- 1. Agregar columnas de FK (nullable inicialmente)
ALTER TABLE control_fosa_septica
ADD COLUMN responsable_id BIGINT UNSIGNED NULL AFTER responsable,
ADD FOREIGN KEY (responsable_id) REFERENCES personal(id);

ALTER TABLE control_fumigacion
ADD COLUMN responsable_id BIGINT UNSIGNED NULL AFTER responsable,
ADD FOREIGN KEY (responsable_id) REFERENCES personal(id);

ALTER TABLE control_tanques_agua
ADD COLUMN responsable_id BIGINT UNSIGNED NULL AFTER responsable,
ADD COLUMN supervisado_por_id BIGINT UNSIGNED NULL AFTER supervisado_por,
ADD FOREIGN KEY (responsable_id) REFERENCES personal(id),
ADD FOREIGN KEY (supervisado_por_id) REFERENCES personal(id);

ALTER TABLE control_mantenimiento_equipos
ADD COLUMN realizado_por_id BIGINT UNSIGNED NULL AFTER realizado_por,
ADD COLUMN supervisado_por_id BIGINT UNSIGNED NULL AFTER supervisado_por,
ADD FOREIGN KEY (realizado_por_id) REFERENCES personal(id),
ADD FOREIGN KEY (supervisado_por_id) REFERENCES personal(id);

-- 2. Migrar datos (script PHP)
// Buscar personal por nombre y asignar ID
FosaSeptica::chunk(100, function($limpiezas) {
    foreach ($limpiezas as $limpieza) {
        $personal = Personal::whereRaw(
            "CONCAT(nombres, ' ', apellidos) LIKE ?",
            ['%' . $limpieza->responsable . '%']
        )->first();

        if ($personal) {
            $limpieza->responsable_id = $personal->id;
            $limpieza->save();
        } else {
            // Log para revisión manual
            Log::warning("No se encontró personal: {$limpieza->responsable}");
        }
    }
});

-- 3. Eliminar columnas antiguas (después de verificar)
ALTER TABLE control_fosa_septica DROP COLUMN responsable;
ALTER TABLE control_fumigacion DROP COLUMN responsable;
ALTER TABLE control_tanques_agua DROP COLUMN responsable, DROP COLUMN supervisado_por;
ALTER TABLE control_mantenimiento_equipos DROP COLUMN realizado_por, DROP COLUMN supervisado_por;

-- 4. Actualizar modelos (descomentar relaciones)
```

**Beneficios**:
- Validación automática de existencia
- Acceso a datos completos del personal
- Integridad referencial
- Previene errores de tipeo

---

### TODO 2: Implementar alertas automáticas

**Problema**: No hay notificaciones proactivas de actividades vencidas/próximas.

**Solución**:
```php
// app/Console/Commands/VerificarActividadesSanitarias.php
class VerificarActividadesSanitarias extends Command
{
    protected $signature = 'sanitario:verificar';

    public function handle()
    {
        $this->verificarFosaSeptica();
        $this->verificarFumigaciones();
        $this->verificarTanques();
        $this->verificarMantenimientos();
    }

    private function verificarFosaSeptica()
    {
        // Vencidas
        $vencidas = FosaSeptica::where('proxima_limpieza', '<', now())->get();
        if ($vencidas->count() > 0) {
            Mail::to('admin@aguacolegial.com')->send(
                new AlertaFosaSepticaVencida($vencidas)
            );
        }

        // Próximas (7 días)
        $proximas = FosaSeptica::whereBetween('proxima_limpieza', [
            now(),
            now()->addDays(7)
        ])->get();

        if ($proximas->count() > 0) {
            Mail::to('admin@aguacolegial.com')->send(
                new AlertaFosaSepticaProxima($proximas)
            );
        }
    }

    // Similar para otros modelos...
}

// app/Console/Kernel.php
protected function schedule(Schedule $schedule)
{
    $schedule->command('sanitario:verificar')->dailyAt('07:00');
}
```

---

### TODO 3: Implementar historial de cambios

**Problema**: No hay auditoría de modificaciones.

**Solución**: Usar `spatie/laravel-activitylog`:

```php
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class FosaSeptica extends Model
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['fecha_limpieza', 'tipo_fosa', 'responsable', 'proxima_limpieza'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }
}

// Ver historial
$limpieza = FosaSeptica::find(1);
$activities = Activity::forSubject($limpieza)->get();

foreach ($activities as $activity) {
    echo "{$activity->created_at}: {$activity->description}";
    // "2025-12-01 10:30: actualizado - próxima limpieza cambió de 2025-12-15 a 2025-12-20"
}
```

---

### TODO 4: Agregar campos de costo

**Problema**: No se rastrea el costo de las actividades.

**Solución**:
```sql
ALTER TABLE control_fosa_septica ADD COLUMN costo DECIMAL(10,2) NULL AFTER empresa_contratada;
ALTER TABLE control_fumigacion ADD COLUMN costo DECIMAL(10,2) NULL AFTER empresa_contratada;
ALTER TABLE control_tanques_agua ADD COLUMN costo DECIMAL(10,2) NULL AFTER productos_desinfeccion;
ALTER TABLE control_mantenimiento_equipos ADD COLUMN costo DECIMAL(10,2) NULL AFTER productos_limpieza;
```

```php
// Modelos
protected $fillable = [
    // ... campos existentes
    'costo',
];

protected $casts = [
    'costo' => 'decimal:2',
];

// Reportes de costos
$costoMensual = FosaSeptica::whereMonth('fecha_limpieza', 12)
    ->sum('costo')
    +
    Fumigacion::whereMonth('fecha_fumigacion', 12)
    ->sum('costo')
    +
    TanqueAgua::whereMonth('fecha_limpieza', 12)
    ->sum('costo')
    +
    MantenimientoEquipo::whereMonth('fecha', 12)
    ->sum('costo');

echo "Costo total de control sanitario en diciembre: $" . number_format($costoMensual, 2);
```

---

### TODO 5: Generar reportes PDF de certificación

**Problema**: No hay reportes formales para auditorías sanitarias.

**Solución**: Usar `barryvdh/laravel-dompdf`:

```php
use Barryvdh\DomPDF\Facade\Pdf;

// Controller
public function reporteAnual(Request $request)
{
    $año = $request->año ?? date('Y');

    $fosas = FosaSeptica::whereYear('fecha_limpieza', $año)->get();
    $fumigaciones = Fumigacion::whereYear('fecha_fumigacion', $año)->get();
    $tanques = TanqueAgua::whereYear('fecha_limpieza', $año)->get();
    $mantenimientos = MantenimientoEquipo::with('personal')
        ->whereYear('fecha', $año)
        ->get();

    $pdf = Pdf::loadView('reportes.control-sanitario-anual', [
        'año' => $año,
        'fosas' => $fosas,
        'fumigaciones' => $fumigaciones,
        'tanques' => $tanques,
        'mantenimientos' => $mantenimientos
    ]);

    return $pdf->download("control-sanitario-{$año}.pdf");
}
```

---

### TODO 6: Implementar vista de calendario

**Problema**: Difícil visualizar todas las actividades programadas.

**Solución**: Usar FullCalendar.js:

```php
// Controller
public function calendario()
{
    $eventos = [];

    // Fosa séptica
    FosaSeptica::all()->each(function($fosa) use (&$eventos) {
        $eventos[] = [
            'title' => 'Limpieza Fosa: ' . $fosa->tipo_fosa,
            'start' => $fosa->proxima_limpieza->toDateString(),
            'color' => '#f39c12',
            'url' => route('control.fosa-septica.show', $fosa)
        ];
    });

    // Similar para otros modelos...

    return view('control.calendario', ['eventos' => $eventos]);
}
```

```html
<!-- resources/views/control/calendario.blade.php -->
<div id="calendar"></div>

<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/index.global.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendar = new FullCalendar.Calendar(document.getElementById('calendar'), {
        initialView: 'dayGridMonth',
        locale: 'es',
        events: @json($eventos)
    });
    calendar.render();
});
</script>
```

---

## 🎯 CONCLUSIÓN

### Modelos de Control Sanitario - Importancia Crítica

**Responsabilidades**:
- Cumplimiento de BPM (Buenas Prácticas de Manufactura)
- Certificación sanitaria para producción de agua purificada
- Rastreo de actividades de limpieza y mantenimiento
- Prevención de contaminación

**Importancia crítica**:
- Requeridos por normativa sanitaria
- Auditorías gubernamentales verifican estos registros
- Sin ellos, no se puede operar legalmente

**Características comunes**:
- Todos tienen fecha de ejecución y próxima fecha
- Todos permiten observaciones
- Todos rastrean responsables (con TODOs de mejora)
- Todos siguen patrón similar de diseño

**Estado actual**:
- FosaSeptica: Sin relaciones, TODOs pendientes
- Fumigacion: Sin relaciones, TODOs pendientes
- TanqueAgua: Sin relaciones, TODOs pendientes (2 supervisores)
- MantenimientoEquipo: ✅ Con relación Personal, usa JSON para flexibilidad

**Mejoras prioritarias**:
1. Migrar campos string a FKs (urgente para integridad)
2. Implementar alertas automáticas (proactividad)
3. Agregar campos de costo (tracking financiero)
4. Generar reportes PDF (auditorías)
5. Vista de calendario (planificación visual)
6. Historial de cambios (auditoría completa)

---

**Documentado por**: Claude (Anthropic)
**Fecha**: 2 de Diciembre de 2025
**Sistema**: Agua Colegial v1.0
**Archivo**: 11-Modelos-Control-Sanitario.md
