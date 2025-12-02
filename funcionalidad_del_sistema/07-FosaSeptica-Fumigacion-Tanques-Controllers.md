# Controllers de Control Sanitario - Documentación Detallada

**Archivos**:
- `app/Http/Controllers/Control/FosaSepticaController.php`
- `app/Http/Controllers/Control/FumigacionController.php`
- `app/Http/Controllers/Control/TanquesController.php`

## Propósito General
Estos tres controladores gestionan registros de mantenimiento sanitario obligatorios en una planta de producción de agua purificada. Son requeridos por normas de salud (BPM - Buenas Prácticas de Manufactura) y regulaciones sanitarias.

---

# 1. FosaSepticaController

**Ubicación**: `app/Http/Controllers/Control/FosaSepticaController.php`
**Propósito**: Registrar limpiezas periódicas de fosa séptica (sistema de drenaje)

## Línea por Línea

### Líneas 1-9: Imports
```php
<?php

namespace App\Http\Controllers\Control;

use App\Http\Controllers\Controller;
use App\Models\Control\FosaSeptica;
use App\Models\Personal;
use Illuminate\Http\Request;
```

**¿Qué hace?**
- Importa Controller base de Laravel
- Importa modelo FosaSeptica para interactuar con tabla `control_fosa_septica`
- Importa modelo Personal para lista de empleados

**¿De dónde sale?**
- Laravel framework

**¿Para qué sirve?**
- Permite usar las clases en el controlador

---

### Líneas 12-16: Método index() - Listar registros
```php
public function index()
{
    $registros = FosaSeptica::orderBy('fecha_limpieza', 'desc')->paginate(15);
    return view('control.fosa-septica.index', compact('registros'));
}
```

**¿Qué hace línea por línea?**

**Línea 14**:
- `FosaSeptica::` - Llama al modelo FosaSeptica
- `orderBy('fecha_limpieza', 'desc')` - Ordena por fecha de limpieza, más recientes primero
- `paginate(15)` - Divide en páginas de 15 registros

**¿De dónde sale?**
- Tabla `control_fosa_septica` en base de datos MySQL/MariaDB

**¿Para qué sirve?**
- Mostrar historial de todas las limpiezas de fosa séptica
- Verificar cumplimiento de calendario de limpieza
- Auditorías sanitarias

**Línea 15**:
- `view('control.fosa-septica.index')` - Carga vista Blade en `resources/views/control/fosa-septica/index.blade.php`
- `compact('registros')` - Convierte variable $registros en array para la vista

**¿Cómo se comunica?**
```
Usuario → Navegador → Ruta web → index() → Base de datos
                                         ↓
                           Vista Blade ← $registros
```

---

### Líneas 18-22: Método create() - Formulario
```php
public function create()
{
    $personal = Personal::where('estado', 'activo')->orderBy('nombre_completo')->get();
    return view('control.fosa-septica.create', compact('personal'));
}
```

**¿Qué hace línea por línea?**

**Línea 20**:
- `Personal::` - Modelo Personal
- `where('estado', 'activo')` - Filtra solo empleados activos (no inactivos/despedidos)
- `orderBy('nombre_completo')` - Ordena alfabéticamente
- `get()` - Ejecuta query y retorna colección

**¿De dónde sale?**
- Tabla `personal` con campos: id, nombre_completo, estado, cargo, etc.

**¿Para qué sirve?**
- Prellenar select dropdown con lista de empleados activos
- Asignar responsable de la limpieza

**Línea 21**:
- Muestra formulario HTML en `resources/views/control/fosa-septica/create.blade.php`

**¿Cómo se comunica?**
```
Usuario click "Nuevo Registro" → create() → Consulta Personal
                                          ↓
                            Vista con formulario + lista personal
```

---

### Líneas 24-40: Método store() - Guardar registro
```php
public function store(Request $request)
{
    $validated = $request->validate([
        'fecha_limpieza' => 'required|date',
        'tipo_fosa' => 'required|string|max:255',
        'responsable' => 'required|string|max:255',
        'detalle_trabajo' => 'required|string',
        'empresa_contratada' => 'required|string|max:255',
        'proxima_limpieza' => 'required|date|after:fecha_limpieza',
        'observaciones' => 'nullable|string',
    ]);

    FosaSeptica::create($validated);

    return redirect()->route('control.fosa-septica.index')
        ->with('success', 'Registro de limpieza creado exitosamente.');
}
```

**¿Qué hace línea por línea?**

**Líneas 26-34: Validación**

**Línea 27**: `'fecha_limpieza' => 'required|date'`
- Campo obligatorio (required)
- Debe ser fecha válida (YYYY-MM-DD)
- **De dónde sale**: Input type="date" del formulario
- **Para qué**: Saber cuándo se hizo la limpieza

**Línea 28**: `'tipo_fosa' => 'required|string|max:255'`
- Tipo de fosa (ej: "Fosa Séptica Principal", "Trampa de Grasas")
- Máximo 255 caracteres
- **De dónde sale**: Input text o select del formulario
- **Para qué**: Identificar qué fosa se limpió (puede haber varias)

**Línea 29**: `'responsable' => 'required|string|max:255'`
- Nombre del empleado responsable
- **De dónde sale**: Select con lista de personal activo
- **Para qué**: Asignar responsabilidad, auditoría

**Línea 30**: `'detalle_trabajo' => 'required|string'`
- Descripción del trabajo realizado (textarea)
- **De dónde sale**: Textarea del formulario
- **Para qué**: Detallar qué se hizo (ej: "Extracción de lodos, lavado con agua a presión, desinfección con cloro")
- **Importante**: Sin límite de caracteres para detalles extensos

**Línea 31**: `'empresa_contratada' => 'required|string|max:255'`
- Nombre de empresa externa que realizó el trabajo
- **De dónde sale**: Input text
- **Para qué**: Normalmente fosas sépticas las limpia empresa especializada con camión cisterna
- **Importante**: Dato para facturación y auditoría

**Línea 32**: `'proxima_limpieza' => 'required|date|after:fecha_limpieza'`
- Fecha programada de próxima limpieza
- `after:fecha_limpieza` - Debe ser POSTERIOR a fecha actual de limpieza
- **De dónde sale**: Input date
- **Para qué**: Planificar mantenimiento preventivo (ej: cada 6 meses)
- **Validación**: No puede ser antes de la fecha actual

**Línea 33**: `'observaciones' => 'nullable|string'`
- Opcional (nullable)
- **De dónde sale**: Textarea opcional
- **Para qué**: Notas adicionales, problemas encontrados

**Línea 36**: `FosaSeptica::create($validated);`
- Inserta nuevo registro en tabla `control_fosa_septica`
- Eloquent ORM crea INSERT SQL automáticamente
- **¿Cómo?**: `INSERT INTO control_fosa_septica (fecha_limpieza, tipo_fosa, ...) VALUES (?, ?, ...)`

**¿De dónde sale la tabla?**
- Migración: `database/migrations/XXXX_create_control_fosa_septica_table.php`
- Creada con `php artisan migrate`

**Líneas 38-39**: Redirección
- `redirect()->route('control.fosa-septica.index')` - Vuelve a lista principal
- `with('success', ...)` - Mensaje flash de éxito (se muestra una vez)

**¿Cómo se comunica?**
```
Usuario llena formulario → Submit POST → store()
                                          ↓
                                     Valida datos
                                          ↓
                                   ¿Válido? → No → Vuelve con errores
                                      ↓ Sí
                                  Inserta BD
                                      ↓
                          Redirige a index con mensaje éxito
```

---

### Líneas 42-46: Método edit() - Formulario edición
```php
public function edit(FosaSeptica $fosa)
{
    $personal = Personal::where('estado', 'activo')->orderBy('nombre_completo')->get();
    return view('control.fosa-septica.edit', compact('fosa', 'personal'));
}
```

**¿Qué hace línea por línea?**

**Línea 42**: `FosaSeptica $fosa`
- **Route Model Binding**: Laravel busca automáticamente el registro por ID
- **¿Cómo?**: URL `/control/fosa-septica/5/edit` → Laravel busca registro con id=5
- Si no existe → Error 404

**Línea 44**: Obtiene personal activo (igual que create)

**Línea 45**: Muestra formulario prellenado
- `compact('fosa', 'personal')` - Envía objeto $fosa con datos actuales Y lista de personal

**¿De dónde sale el objeto $fosa?**
```
URL: /control/fosa-septica/5/edit
                           ↓ (ID)
         Laravel busca en tabla control_fosa_septica WHERE id=5
                           ↓
            Retorna objeto con: fecha_limpieza, tipo_fosa, etc.
                           ↓
                  Vista prellenada con esos valores
```

---

### Líneas 48-64: Método update() - Actualizar
```php
public function update(Request $request, FosaSeptica $fosa)
{
    $validated = $request->validate([
        'fecha_limpieza' => 'required|date',
        'tipo_fosa' => 'required|string|max:255',
        'responsable' => 'required|string|max:255',
        'detalle_trabajo' => 'required|string',
        'empresa_contratada' => 'required|string|max:255',
        'proxima_limpieza' => 'required|date|after:fecha_limpieza',
        'observaciones' => 'nullable|string',
    ]);

    $fosa->update($validated);

    return redirect()->route('control.fosa-septica.index')
        ->with('success', 'Registro de limpieza actualizado exitosamente.');
}
```

**¿Qué hace?**
- Validación idéntica a store()
- **Línea 60**: `$fosa->update($validated)` - Actualiza registro existente
  - SQL generado: `UPDATE control_fosa_septica SET fecha_limpieza=?, tipo_fosa=?, ... WHERE id=?`

**Diferencia con store()**:
- `create()` = INSERT (nuevo registro)
- `update()` = UPDATE (modifica existente)

---

### Líneas 66-72: Método destroy() - Eliminar
```php
public function destroy(FosaSeptica $fosa)
{
    $fosa->delete();

    return redirect()->route('control.fosa-septica.index')
        ->with('success', 'Registro de limpieza eliminado exitosamente.');
}
```

**¿Qué hace línea por línea?**

**Línea 68**: `$fosa->delete();`
- **Hard delete**: Elimina permanentemente el registro
- SQL: `DELETE FROM control_fosa_septica WHERE id=?`
- **Importante**: No es soft delete, se pierde el histórico

**¿De dónde viene la petición?**
```
Vista index → Botón "Eliminar" → Formulario DELETE → destroy()
                                                        ↓
                                            Elimina de base de datos
```

---

## Tabla de Base de Datos: control_fosa_septica

```sql
CREATE TABLE control_fosa_septica (
    id INT PRIMARY KEY AUTO_INCREMENT,
    fecha_limpieza DATE NOT NULL,
    tipo_fosa VARCHAR(255) NOT NULL,
    responsable VARCHAR(255) NOT NULL,
    detalle_trabajo TEXT NOT NULL,
    empresa_contratada VARCHAR(255) NOT NULL,
    proxima_limpieza DATE NOT NULL,
    observaciones TEXT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

**¿Para qué sirve cada campo?**
- **id**: Identificador único auto-incremental
- **fecha_limpieza**: Cuándo se realizó la limpieza
- **tipo_fosa**: Qué fosa (puede haber varias en la planta)
- **responsable**: Quién supervisó (empleado interno)
- **detalle_trabajo**: Qué se hizo exactamente
- **empresa_contratada**: Empresa externa que realizó trabajo
- **proxima_limpieza**: Cuándo toca la siguiente
- **observaciones**: Notas adicionales
- **created_at/updated_at**: Laravel timestamps automáticos

---

# 2. FumigacionController

**Ubicación**: `app/Http/Controllers/Control/FumigacionController.php`
**Propósito**: Registrar fumigaciones (control de plagas)

## Diferencias con FosaSepticaController

Estructura idéntica pero con campos diferentes:

### Campos específicos de Fumigación:

**Línea 27**: `'fecha_fumigacion' => 'required|date'`
- Fecha de la fumigación

**Línea 28**: `'area_fumigada' => 'required|string|max:255'`
- Área fumigada (ej: "Almacén", "Área de Producción", "Baños")
- **Para qué**: Una planta puede fumigar por zonas

**Línea 29**: `'producto_utilizado' => 'required|string|max:255'`
- Nombre del insecticida/raticida usado
- **Importante**: Debe ser producto aprobado para uso en industria alimentaria

**Línea 30**: `'cantidad_producto' => 'required|numeric|min:0'`
- Cantidad de producto en ml/litros/kg
- **Para qué**: Control de inventario de productos químicos

**Línea 31**: `'responsable' => 'required|string|max:255'`
- Quién supervisó

**Línea 32**: `'empresa_contratada' => 'nullable|string|max:255'`
- Opcional: puede ser empresa externa o personal interno
- **nullable** porque puede ser fumigación interna

**Línea 33**: `'proxima_fumigacion' => 'nullable|date'`
- Opcional: próxima fecha programada
- **nullable** porque puede ser fumigación reactiva (por plaga detectada)

**Línea 34**: `'observaciones' => 'nullable|string'`
- Notas: plagas encontradas, zonas afectadas, etc.

## Tabla: control_fumigaciones

```sql
CREATE TABLE control_fumigaciones (
    id INT PRIMARY KEY AUTO_INCREMENT,
    fecha_fumigacion DATE NOT NULL,
    area_fumigada VARCHAR(255) NOT NULL,
    producto_utilizado VARCHAR(255) NOT NULL,
    cantidad_producto DECIMAL(10,2) NOT NULL,
    responsable VARCHAR(255) NOT NULL,
    empresa_contratada VARCHAR(255) NULL,
    proxima_fumigacion DATE NULL,
    observaciones TEXT NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

**¿Para qué sirve este registro?**
- Cumplir normativa sanitaria (BPM exige control de plagas)
- Auditorías de salubridad
- Planificar fumigaciones preventivas (cada 3-6 meses)
- Rastrear productos químicos usados
- Evidencia para certificaciones (ISO 22000, HACCP)

---

# 3. TanquesController

**Ubicación**: `app/Http/Controllers/Control/TanquesController.php`
**Propósito**: Registrar limpiezas de tanques de agua

## Diferencias clave:

### Tiene método show() adicional (líneas 44-47)
```php
public function show(TanqueAgua $tanque)
{
    return view('control.tanques.show', compact('tanque'));
}
```

**¿Qué hace?**
- Muestra detalles completos de UN registro específico
- Vista para ver histórico detallado de limpieza

**¿Por qué tiene show() y otros no?**
- Los tanques son críticos (almacenan agua a purificar)
- Requiere más detalle para auditorías

### Campos específicos de Tanques:

**Línea 27**: `'fecha_limpieza' => 'required|date'`
- Fecha de la limpieza

**Línea 28**: `'nombre_tanque' => 'required|string|max:255'`
- Identificador del tanque (ej: "Tanque Principal 10,000L", "Tanque Secundario")
- **Importante**: Puede haber múltiples tanques

**Línea 29**: `'capacidad_litros' => 'nullable|numeric|min:0'`
- Capacidad en litros del tanque
- **nullable**: Puede no especificarse si ya está registrado

**Línea 30**: `'procedimiento_limpieza' => 'nullable|string'`
- Paso a paso de cómo se limpió (textarea)
- **Ejemplo**: "1. Vaciado completo 2. Lavado con detergente 3. Enjuague 4. Desinfección con cloro 5. Enjuague final"

**Línea 31**: `'productos_desinfeccion' => 'nullable|string'`
- Productos usados (ej: "Cloro 5%, Detergente Industrial")

**Línea 32**: `'responsable' => 'required|string|max:255'`
- Quién realizó la limpieza

**Línea 33**: `'supervisado_por' => 'nullable|string|max:255'`
- Quién supervisó (puede ser jefe de producción)
- **nullable**: Si el responsable es el jefe, no necesita supervisor

**Línea 34**: `'proxima_limpieza' => 'nullable|date'`
- Cuándo toca limpiar nuevamente
- **Frecuencia típica**: Cada 6 meses o según uso

**Línea 35**: `'observaciones' => 'nullable|string'`
- Notas: sedimentos encontrados, estado del tanque, reparaciones necesarias

## Tabla: control_tanques_agua

```sql
CREATE TABLE control_tanques_agua (
    id INT PRIMARY KEY AUTO_INCREMENT,
    fecha_limpieza DATE NOT NULL,
    nombre_tanque VARCHAR(255) NOT NULL,
    capacidad_litros DECIMAL(10,2) NULL,
    procedimiento_limpieza TEXT NULL,
    productos_desinfeccion TEXT NULL,
    responsable VARCHAR(255) NOT NULL,
    supervisado_por VARCHAR(255) NULL,
    proxima_limpieza DATE NULL,
    observaciones TEXT NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

**¿Por qué es crítico este registro?**
- Agua almacenada debe estar en tanques limpios
- Evita contaminación bacteriana
- Normativa sanitaria exige limpieza periódica
- Certificaciones de calidad requieren evidencia documental

---

## Comunicación entre componentes (los 3 controladores)

```
┌─────────────────────────────────────────────────────┐
│                  NAVEGADOR                           │
│  (Usuario hace click en "Fosa Séptica")            │
└────────────────────┬────────────────────────────────┘
                     │ HTTP GET
                     ↓
┌─────────────────────────────────────────────────────┐
│              RUTAS (routes/web.php)                  │
│  Route::resource('control/fosa-septica',            │
│                  FosaSepticaController::class)       │
└────────────────────┬────────────────────────────────┘
                     │ Llama al controlador
                     ↓
┌─────────────────────────────────────────────────────┐
│         FosaSepticaController::index()               │
│  1. Consulta modelo FosaSeptica                      │
│  2. Hace query a base de datos                       │
└────────────────────┬────────────────────────────────┘
                     │ Query SQL
                     ↓
┌─────────────────────────────────────────────────────┐
│        BASE DE DATOS (MySQL/MariaDB)                 │
│  Tabla: control_fosa_septica                         │
│  SELECT * FROM control_fosa_septica                  │
│  ORDER BY fecha_limpieza DESC                        │
└────────────────────┬────────────────────────────────┘
                     │ Retorna datos
                     ↓
┌─────────────────────────────────────────────────────┐
│         MODELO FosaSeptica                           │
│  Convierte datos SQL en objetos PHP                  │
└────────────────────┬────────────────────────────────┘
                     │ Colección de objetos
                     ↓
┌─────────────────────────────────────────────────────┐
│         CONTROLADOR (continúa)                       │
│  Pasa datos a la vista                               │
└────────────────────┬────────────────────────────────┘
                     │
                     ↓
┌─────────────────────────────────────────────────────┐
│   VISTA (resources/views/control/fosa-septica/      │
│         index.blade.php)                             │
│  1. Recibe variable $registros                       │
│  2. Genera HTML con los datos                        │
│  3. Foreach para listar cada registro                │
└────────────────────┬────────────────────────────────┘
                     │ HTML generado
                     ↓
┌─────────────────────────────────────────────────────┐
│              NAVEGADOR                               │
│  Muestra tabla con registros de limpiezas           │
└─────────────────────────────────────────────────────┘
```

---

## ¿Por qué existen estos 3 controladores?

### Normativa Sanitaria (BPM - Buenas Prácticas de Manufactura)

En México y Latinoamérica, las plantas de producción de alimentos/bebidas DEBEN:

1. **Fosa Séptica**:
   - Limpieza periódica obligatoria
   - Evita malos olores y contaminación
   - Regulado por COFEPRIS (México) o equivalente

2. **Fumigación**:
   - Control de plagas (insectos, roedores)
   - Prevención de contaminación de productos
   - Certificado de fumigación requerido

3. **Tanques de Agua**:
   - Agua es materia prima principal
   - Tanques limpios = agua segura
   - Inspecciones sanitarias verifican estos registros

### Auditorías y Certificaciones

Estos registros son EVIDENCIA para:
- Auditorías de COFEPRIS
- Certificaciones ISO 22000 (inocuidad alimentaria)
- HACCP (Análisis de Peligros)
- Renovación de licencias sanitarias

**Sin estos registros → Multas o cierre de planta**

---

## Resumen Comparativo

| Característica | FosaSeptica | Fumigacion | Tanques |
|---------------|-------------|------------|---------|
| Tabla BD | control_fosa_septica | control_fumigaciones | control_tanques_agua |
| Campo principal | fecha_limpieza | fecha_fumigacion | fecha_limpieza |
| Tiene show() | ❌ No | ❌ No | ✅ Sí |
| Empresa externa | ✅ Requerida | ⚠️ Opcional | ❌ No aplica |
| Próxima fecha | ✅ Requerida | ⚠️ Opcional | ⚠️ Opcional |
| Crítico para | Drenaje | Control plagas | Agua limpia |
| Frecuencia típica | 6 meses | 3 meses | 6 meses |

---

## Mejoras Recomendadas

### 1. Alertas automáticas:
```php
// En AppServiceProvider o Job diario
$proximasLimpiezas = FosaSeptica::whereDate('proxima_limpieza', '<=', now()->addDays(7))->get();
// Enviar email al administrador
```

### 2. Dashboard de mantenimiento:
Widget mostrando:
- ✅ Limpiezas al día
- ⚠️ Próximas en 7 días
- ❌ Vencidas

### 3. Exportar a PDF:
Para auditorías, botón "Generar certificado" que cree PDF con:
- Fecha de limpieza
- Responsable
- Empresa
- Firma digital

### 4. Fotos de evidencia:
Campo para subir fotos antes/después de limpieza

### 5. Soft delete:
Cambiar hard delete por soft delete para mantener histórico

---

**Creado para**: Defensa técnica del sistema
**Fecha**: 2 de Diciembre de 2025
**Archivos documentados**: 3 controladores de control sanitario
