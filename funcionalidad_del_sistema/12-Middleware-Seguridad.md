# 12. MIDDLEWARE DE SEGURIDAD

## 📋 ÍNDICE DE CONTENIDO

1. [CheckRole.php - Control de Roles](#checkrolephp)
2. [RestrictIpAddress.php - Restricción por IP](#restrictipaddressphp)
3. [ValidateRequestIntegrity.php - Validación de Peticiones](#validaterequestintegrityphp)
4. [Resumen de Funcionalidades](#resumen)
5. [Flujos de Trabajo](#flujos)
6. [Configuración](#configuracion)
7. [TODOs y Mejoras Futuras](#todos)

---

## 🎯 PROPÓSITO GENERAL

Este documento explica **línea por línea** tres middleware críticos de seguridad en `app/Http/Middleware/`:

1. **CheckRole.php**: Control de acceso basado en roles
2. **RestrictIpAddress.php**: Restricción de acceso por dirección IP
3. **ValidateRequestIntegrity.php**: Validación y sanitización de peticiones HTTP

**¿Por qué son críticos?**
Los middleware son la **primera línea de defensa** del sistema:
- Se ejecutan ANTES de que la petición llegue al controlador
- Previenen accesos no autorizados
- Detectan y bloquean ataques (SQL injection, XSS, etc.)
- Protegen la integridad de los datos

---

# CHECKROLE.PHP

**Ubicación**: `app/Http/Middleware/CheckRole.php`
**Líneas totales**: 64
**Complejidad**: Baja-Media
**Propósito**: Verificar roles de usuario y controlar acceso a módulos

---

## 📖 EXPLICACIÓN LÍNEA POR LÍNEA

### 🟢 SECCIÓN 1: DECLARACIONES Y NAMESPACE (Líneas 1-10)

```php
<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
```
**¿Qué hace?** Importa clases necesarias para el middleware.
**¿De dónde sale?** Framework Laravel.
**¿Para qué sirve?**
- `Closure`: Representa la siguiente acción en la cadena de middleware
- `Request`: Objeto con datos de la petición HTTP
- `Response`: Objeto de respuesta HTTP

**Nota**: `declare(strict_types=1)` activa tipado estricto.

---

### 🟢 SECCIÓN 2: DOCUMENTACIÓN (Líneas 11-17)

```php
/**
 * Middleware para verificar roles de usuario.
 *
 * Uso:
 * Route::middleware(['auth', 'role:admin'])->group(...);
 * Route::middleware(['auth', 'role:admin,produccion'])->group(...);
 */
```
**¿Qué hace?** Documenta cómo usar el middleware.
**¿De dónde sale?** Documentación PHPDoc.
**¿Para qué sirve?** Guiar a desarrolladores sobre el uso correcto.

**Ejemplos de uso**:
```php
// Ruta solo para admin
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [DashboardController::class, 'index']);
});

// Ruta para admin O producción
Route::middleware(['auth', 'role:admin,produccion'])->group(function () {
    Route::resource('/control/produccion', ProduccionController::class);
});

// Ruta para múltiples roles
Route::middleware(['auth', 'role:admin,inventario,despacho'])->group(function () {
    Route::get('/reportes', [ReporteController::class, 'index']);
});
```

---

### 🟢 SECCIÓN 3: MÉTODO HANDLE (Líneas 18-27)

```php
class CheckRole
{
    /**
     * Manejar una solicitud entrante.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles  Roles permitidos (admin, produccion, inventario)
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
```
**¿Qué hace?** Método principal del middleware.
**¿De dónde sale?** Patrón Pipeline de Laravel.
**¿Para qué sirve?** Interceptar y validar peticiones antes del controlador.

**Parámetros explicados**:
- `$request`: Petición HTTP actual
- `$next`: Siguiente middleware/controlador en la cadena
- `...$roles`: Operador variádico - acepta múltiples roles como parámetros

**Ejemplo de parámetros variádicos**:
```php
// En ruta: role:admin,produccion
// Laravel llama: handle($request, $next, 'admin', 'produccion')
// $roles = ['admin', 'produccion']

// En ruta: role:admin
// Laravel llama: handle($request, $next, 'admin')
// $roles = ['admin']
```

---

### 🟢 SECCIÓN 4: VERIFICACIÓN DE AUTENTICACIÓN (Líneas 28-32)

```php
        // Verificar que el usuario esté autenticado
        if (!auth()->check()) {
            return redirect()->route('login')
                ->with('error', 'Debe iniciar sesión para acceder a esta página');
        }
```
**¿Qué hace?** Verifica si hay un usuario logueado.
**¿De dónde sale?** Helper `auth()` de Laravel.
**¿Para qué sirve?** Primera barrera - sin login no hay acceso.

**¿Qué es `auth()->check()`?**
- Retorna `true` si hay usuario en sesión
- Retorna `false` si no hay sesión activa

**Flujo**:
```
Usuario intenta acceder a /control/produccion
    ↓
Middleware CheckRole intercepta
    ↓
¿Hay usuario logueado? (auth()->check())
    ↓ NO
Redirigir a /login con mensaje de error
```

**Ejemplo**:
```php
// Sin login:
GET /control/produccion
→ Redirige a /login con mensaje "Debe iniciar sesión..."

// Con login:
GET /control/produccion
→ Continúa a siguiente validación
```

---

### 🟢 SECCIÓN 5: OBTENER USUARIO Y VERIFICAR ESTADO (Líneas 34-41)

```php
        $usuario = auth()->user();

        // Verificar que el usuario esté activo
        if ($usuario->estado !== 'activo') {
            auth()->logout();
            return redirect()->route('login')
                ->with('error', 'Su cuenta está inactiva. Contacte al administrador');
        }
```
**¿Qué hace?** Obtiene usuario y verifica si está activo.
**¿De dónde sale?** Helper `auth()->user()` retorna modelo Usuario.
**¿Para qué sirve?** Prevenir acceso de usuarios desactivados.

**¿Qué es `auth()->user()`?**
- Retorna instancia del modelo Usuario logueado
- Tiene acceso a todos los métodos y propiedades del modelo

**¿Por qué hacer logout?**
- Usuario desactivado no debe mantener sesión activa
- Forzar nuevo login si es reactivado
- Seguridad: evitar sesiones zombie

**Ejemplo**:
```php
// Usuario activo:
$usuario->estado = 'activo'
→ Continúa a siguiente validación

// Usuario inactivo:
$usuario->estado = 'inactivo'
→ Logout forzado
→ Redirige a /login con mensaje "Su cuenta está inactiva..."

// Caso de uso real:
// Admin desactiva a empleado despedido
// Empleado ya tenía sesión abierta en su computadora
// Al siguiente request, middleware lo expulsa
```

---

### 🟢 SECCIÓN 6: VERIFICAR ROL ASIGNADO (Líneas 43-46)

```php
        // Verificar que el usuario tenga un rol asignado
        if (!$usuario->rol) {
            abort(403, 'Usuario sin rol asignado');
        }
```
**¿Qué hace?** Verifica que usuario tenga rol.
**¿De dónde sale?** Relación `rol()` en modelo Usuario.
**¿Para qué sirve?** Prevenir acceso de usuarios sin rol.

**¿Qué es `abort(403)`?**
- Lanza excepción HTTP 403 Forbidden
- Detiene ejecución inmediatamente
- Muestra página de error 403

**Diferencia entre `redirect()` y `abort()`**:
```php
// redirect(): Error esperado, usuario puede resolverlo
return redirect()->route('login')->with('error', 'Inicie sesión');

// abort(): Error de configuración, requiere intervención admin
abort(403, 'Usuario sin rol asignado');
```

**Ejemplo**:
```php
// Usuario con rol:
$usuario->rol->nombre = 'produccion'
→ Continúa a siguiente validación

// Usuario sin rol (error de BD):
$usuario->rol = null
→ abort(403, 'Usuario sin rol asignado')
→ Página de error 403 con mensaje

// Caso de uso real:
// Admin crea usuario pero olvida asignar rol
// Usuario intenta acceder al sistema
// Middleware lo bloquea y notifica el error
```

---

### 🟢 SECCIÓN 7: OBTENER ROL Y VERIFICAR ADMIN (Líneas 48-54)

```php
        // Verificar que el usuario tenga uno de los roles permitidos
        $rolUsuario = $usuario->rol->nombre;

        // El administrador siempre tiene acceso a todos los módulos
        if ($rolUsuario === 'admin') {
            return $next($request);
        }
```
**¿Qué hace?** Obtiene nombre del rol y da acceso total a admin.
**¿De dónde sale?** Modelo Rol relacionado con Usuario.
**¿Para qué sirve?** Admin tiene acceso a TODO sin restricciones.

**¿Qué es `$next($request)`?**
- Pasa la petición al siguiente middleware/controlador
- Significa "esta petición está aprobada, continúa"

**Flujo para admin**:
```
Usuario: admin@aguacolegial.com (rol: admin)
    ↓
Intenta acceder a CUALQUIER ruta
    ↓
Middleware: ¿Es admin? SÍ
    ↓
$next($request) → Acceso garantizado
```

**Ejemplo**:
```php
// Admin accediendo a cualquier módulo:
$usuario->rol->nombre = 'admin'

// Ruta: /control/produccion (requiere role:produccion)
→ Admin pasa sin verificar rol específico

// Ruta: /admin/configuracion (requiere role:admin)
→ Admin pasa

// Ruta: /inventario/reportes (requiere role:inventario)
→ Admin pasa

// Caso de uso real:
// Admin necesita ayudar a supervisor de producción
// Puede entrar a módulo de producción sin cambiar su rol
// Tiene visibilidad completa del sistema
```

---

### 🟢 SECCIÓN 8: VERIFICAR ROLES ESPECÍFICOS (Líneas 56-62)

```php
        // Verificar roles específicos para otros usuarios
        if (!in_array($rolUsuario, $roles, true)) {
            abort(403, 'No tiene permisos para acceder a este módulo');
        }

        return $next($request);
    }
}
```
**¿Qué hace?** Verifica si rol del usuario está en roles permitidos.
**¿De dónde sale?** Función `in_array()` de PHP.
**¿Para qué sirve?** Control granular de acceso por rol.

**¿Qué es `in_array($rolUsuario, $roles, true)`?**
- Busca `$rolUsuario` en array `$roles`
- Tercer parámetro `true` = comparación estricta (===)
- Retorna `true` si el rol está en la lista

**Ejemplo detallado**:
```php
// Ruta: Route::middleware(['role:produccion,inventario'])

// Usuario con rol 'produccion':
$rolUsuario = 'produccion'
$roles = ['produccion', 'inventario']
in_array('produccion', ['produccion', 'inventario'], true) → true
→ Acceso permitido, return $next($request)

// Usuario con rol 'despacho':
$rolUsuario = 'despacho'
$roles = ['produccion', 'inventario']
in_array('despacho', ['produccion', 'inventario'], true) → false
→ abort(403, 'No tiene permisos...')

// Usuario con rol 'admin':
$rolUsuario = 'admin'
→ Ya pasó en línea 52, nunca llega aquí
```

**Flujo completo del middleware**:
```
1. ¿Usuario logueado? NO → Redirigir a login
2. ¿Usuario logueado? SÍ → Continuar
3. ¿Usuario activo? NO → Logout y redirigir
4. ¿Usuario activo? SÍ → Continuar
5. ¿Usuario tiene rol? NO → abort(403)
6. ¿Usuario tiene rol? SÍ → Continuar
7. ¿Rol es admin? SÍ → $next(request) ✅
8. ¿Rol es admin? NO → Verificar rol específico
9. ¿Rol en lista permitida? SÍ → $next(request) ✅
10. ¿Rol en lista permitida? NO → abort(403) ❌
```

---

# RESTRICTIPADDRESS.PHP

**Ubicación**: `app/Http/Middleware/RestrictIpAddress.php`
**Líneas totales**: 98
**Complejidad**: Media
**Propósito**: Restringir acceso al sistema por dirección IP

---

## 📖 EXPLICACIÓN LÍNEA POR LÍNEA

### 🟢 SECCIÓN 1: DOCUMENTACIÓN Y CLASE (Líneas 1-23)

```php
<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware para restringir acceso por dirección IP.
 *
 * Solo permite acceso desde IPs autorizadas configuradas en .env
 */
class RestrictIpAddress
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
```
**¿Qué hace?** Define clase del middleware.
**¿De dónde sale?** Laravel Middleware structure.
**¿Para qué sirve?** Bloquear accesos desde IPs no autorizadas.

**¿Por qué restringir por IP?**
- Limitar acceso a red local de la empresa
- Prevenir accesos remotos no autorizados
- Cumplir políticas de seguridad corporativa
- Reducir superficie de ataque

---

### 🟢 SECCIÓN 2: OBTENER IPS PERMITIDAS (Líneas 25-29)

```php
        // Obtener IPs permitidas desde .env (separadas por comas)
        $allowedIps = explode(',', env('ALLOWED_IPS', ''));

        // Limpiar espacios en blanco
        $allowedIps = array_map('trim', $allowedIps);
```
**¿Qué hace?** Lee IPs permitidas desde archivo `.env`.
**¿De dónde sale?** Helper `env()` de Laravel.
**¿Para qué sirve?** Configuración centralizada de IPs autorizadas.

**¿Qué es `env('ALLOWED_IPS', '')`?**
- Lee variable `ALLOWED_IPS` del archivo `.env`
- Segundo parámetro `''` es valor por defecto si no existe

**¿Qué hace `explode(',', ...)`?**
- Convierte string separado por comas en array
- Ejemplo: `"192.168.1.10,192.168.1.20"` → `['192.168.1.10', '192.168.1.20']`

**¿Qué hace `array_map('trim', ...)`?**
- Aplica función `trim()` a cada elemento del array
- Elimina espacios en blanco al inicio/final
- Ejemplo: `['192.168.1.10 ', ' 192.168.1.20']` → `['192.168.1.10', '192.168.1.20']`

**Configuración en .env**:
```env
# Archivo .env
ALLOWED_IPS=192.168.1.10,192.168.1.20,192.168.1.30
```

**Ejemplo completo**:
```php
// .env: ALLOWED_IPS=192.168.1.10, 192.168.1.20 , 192.168.1.30

// Paso 1: env('ALLOWED_IPS', '')
// Retorna: "192.168.1.10, 192.168.1.20 , 192.168.1.30"

// Paso 2: explode(',', ...)
// Retorna: ['192.168.1.10', ' 192.168.1.20 ', ' 192.168.1.30']

// Paso 3: array_map('trim', ...)
// Retorna: ['192.168.1.10', '192.168.1.20', '192.168.1.30']
```

---

### 🟢 SECCIÓN 3: OBTENER IP DEL CLIENTE (Líneas 31-32)

```php
        // Obtener la IP del cliente
        $clientIp = $request->ip();
```
**¿Qué hace?** Obtiene dirección IP del cliente que hace la petición.
**¿De dónde sale?** Método `ip()` del objeto Request.
**¿Para qué sirve?** Identificar de dónde viene la conexión.

**¿Cómo obtiene Laravel la IP?**
Laravel busca la IP en este orden:
1. Header `X-Forwarded-For` (si hay proxy/load balancer)
2. Header `X-Real-IP` (si hay proxy)
3. `$_SERVER['REMOTE_ADDR']` (IP directa)

**Ejemplo**:
```php
// Conexión directa:
$request->ip() → "192.168.1.50"

// Detrás de proxy (Cloudflare, Nginx):
$request->ip() → "203.0.113.45" (IP real del usuario)
// NO retorna IP del proxy

// Conexión local:
$request->ip() → "127.0.0.1" o "::1"
```

---

### 🟢 SECCIÓN 4: VALIDACIÓN PARA DESARROLLO (Líneas 34-37)

```php
        // Si no hay IPs configuradas, permitir acceso (para desarrollo local)
        if (empty($allowedIps[0])) {
            return $next($request);
        }
```
**¿Qué hace?** Permite acceso si no hay IPs configuradas.
**¿De dónde sale?** Lógica de seguridad.
**¿Para qué sirve?** Facilitar desarrollo local sin configurar IPs.

**¿Por qué `empty($allowedIps[0])`?**
- `explode(',', '')` retorna `['']` (array con string vacío)
- `empty($allowedIps[0])` verifica si primer elemento está vacío
- Si está vacío → no hay restricción configurada

**Casos de uso**:
```php
// .env SIN configuración:
// ALLOWED_IPS=
$allowedIps = ['']
empty($allowedIps[0]) → true
→ $next($request) (acceso permitido)

// .env CON configuración:
// ALLOWED_IPS=192.168.1.10
$allowedIps = ['192.168.1.10']
empty($allowedIps[0]) → false
→ Continúa a verificación de IP

// Caso real:
// Desarrollador en laptop personal
// No tiene IP configurada en .env local
// Middleware permite acceso para no bloquear desarrollo
```

---

### 🟢 SECCIÓN 5: IPS LOCALES Y VERIFICACIÓN (Líneas 39-45)

```php
        // Verificar si la IP está en la lista permitida
        // También permitir localhost y IPs de red local
        $localIps = ['127.0.0.1', '::1', 'localhost'];

        if (in_array($clientIp, array_merge($allowedIps, $localIps))) {
            return $next($request);
        }
```
**¿Qué hace?** Verifica si IP del cliente está en lista permitida o es local.
**¿De dónde sale?** Lógica de validación.
**¿Para qué sirve?** Permitir acceso desde IPs autorizadas + localhost.

**¿Qué son las IPs locales?**
- `127.0.0.1`: Localhost IPv4
- `::1`: Localhost IPv6
- `localhost`: Nombre de host local

**¿Qué hace `array_merge($allowedIps, $localIps)`?**
- Combina dos arrays en uno
- Ejemplo: `['192.168.1.10']` + `['127.0.0.1', '::1']` = `['192.168.1.10', '127.0.0.1', '::1']`

**Ejemplo completo**:
```php
// Configuración: ALLOWED_IPS=192.168.1.10,192.168.1.20
$allowedIps = ['192.168.1.10', '192.168.1.20']
$localIps = ['127.0.0.1', '::1', 'localhost']

// array_merge:
$merged = ['192.168.1.10', '192.168.1.20', '127.0.0.1', '::1', 'localhost']

// Cliente desde 192.168.1.10:
$clientIp = '192.168.1.10'
in_array('192.168.1.10', $merged) → true
→ $next($request) ✅

// Cliente desde localhost:
$clientIp = '127.0.0.1'
in_array('127.0.0.1', $merged) → true
→ $next($request) ✅

// Cliente desde internet:
$clientIp = '203.0.113.45'
in_array('203.0.113.45', $merged) → false
→ Continúa a siguiente verificación
```

---

### 🟢 SECCIÓN 6: VERIFICAR RED LOCAL (Líneas 47-50)

```php
        // Verificar si es una IP de red local (192.168.x.x, 10.x.x.x)
        if ($this->isLocalNetwork($clientIp)) {
            return $next($request);
        }
```
**¿Qué hace?** Permite acceso desde redes privadas.
**¿De dónde sale?** Método privado `isLocalNetwork()`.
**¿Para qué sirve?** Permitir toda la red local de la empresa sin listar cada IP.

**¿Por qué verificar red local?**
- Empresas usan DHCP (IPs dinámicas en red local)
- Imposible listar todas las IPs de empleados
- Más fácil: permitir toda la red `192.168.x.x`

**Ejemplo**:
```php
// IP de red local:
$clientIp = '192.168.1.75'
$this->isLocalNetwork('192.168.1.75') → true
→ $next($request) ✅

// IP de internet:
$clientIp = '203.0.113.45'
$this->isLocalNetwork('203.0.113.45') → false
→ Continúa a log y bloqueo
```

---

### 🟢 SECCIÓN 7: LOG Y BLOQUEO (Líneas 52-60)

```php
        // Registrar intento de acceso no autorizado
        \Log::warning('Intento de acceso no autorizado desde IP: ' . $clientIp, [
            'url' => $request->fullUrl(),
            'user_agent' => $request->userAgent(),
        ]);

        // Bloquear acceso
        abort(403, 'Acceso denegado. Su dirección IP no está autorizada.');
    }
```
**¿Qué hace?** Registra intento sospechoso y bloquea acceso.
**¿De dónde sale?** Facade `Log` de Laravel.
**¿Para qué sirve?** Auditoría de seguridad y prevención.

**¿Qué es `\Log::warning()`?**
- Escribe en log de Laravel (storage/logs/laravel.log)
- Nivel `warning`: evento sospechoso pero no crítico
- Segundo parámetro: contexto adicional (array)

**¿Qué datos se registran?**
```php
[
    'message' => 'Intento de acceso no autorizado desde IP: 203.0.113.45',
    'url' => 'https://aguacolegial.com/admin/dashboard',
    'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)...'
]
```

**Ejemplo de log**:
```
[2025-12-02 10:30:15] local.WARNING: Intento de acceso no autorizado desde IP: 203.0.113.45
{"url":"https://aguacolegial.com/admin/dashboard","user_agent":"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36"}
```

**¿Por qué es importante el log?**
- Detectar intentos de ataque
- Identificar patrones sospechosos
- Evidencia para investigaciones
- Configurar firewall para bloquear IPs atacantes

---

### 🟢 SECCIÓN 8: MÉTODO PRIVADO isLocalNetwork (Líneas 62-97)

```php
    /**
     * Verificar si la IP pertenece a una red local.
     */
    private function isLocalNetwork(string $ip): bool
    {
        // Verificar rangos de red local
        $localRanges = [
            '192.168.',  // Clase C privada
            '10.',       // Clase A privada
            '172.16.',   // Clase B privada (parte)
            '172.17.',
            '172.18.',
            '172.19.',
            '172.20.',
            '172.21.',
            '172.22.',
            '172.23.',
            '172.24.',
            '172.25.',
            '172.26.',
            '172.27.',
            '172.28.',
            '172.29.',
            '172.30.',
            '172.31.',
        ];

        foreach ($localRanges as $range) {
            if (str_starts_with($ip, $range)) {
                return true;
            }
        }

        return false;
    }
}
```
**¿Qué hace?** Verifica si IP pertenece a redes privadas (RFC 1918).
**¿De dónde sale?** Estándar RFC 1918 de IANA.
**¿Para qué sirve?** Detectar IPs de redes locales.

**Rangos de IPs privadas (RFC 1918)**:
- `10.0.0.0` - `10.255.255.255` (16,777,216 IPs)
- `172.16.0.0` - `172.31.255.255` (1,048,576 IPs)
- `192.168.0.0` - `192.168.255.255` (65,536 IPs)

**¿Qué hace `str_starts_with($ip, $range)`?**
- Verifica si `$ip` comienza con `$range`
- Función de PHP 8+
- Ejemplo: `str_starts_with('192.168.1.10', '192.168.')` → `true`

**Ejemplo completo**:
```php
// IP de red local clase C:
isLocalNetwork('192.168.1.50') → true
// Coincide con '192.168.'

// IP de red local clase A:
isLocalNetwork('10.50.100.200') → true
// Coincide con '10.'

// IP de red local clase B:
isLocalNetwork('172.20.5.10') → true
// Coincide con '172.20.'

// IP de internet:
isLocalNetwork('203.0.113.45') → false
// No coincide con ningún rango

// IP de servidor Google:
isLocalNetwork('8.8.8.8') → false
// No coincide con ningún rango
```

**Casos de uso real**:
```php
// Escenario 1: Empresa con red 192.168.1.x
// Empleado A: 192.168.1.10 → Acceso permitido
// Empleado B: 192.168.1.50 → Acceso permitido
// Empleado C: 192.168.1.200 → Acceso permitido

// Escenario 2: Atacante desde internet
// IP: 45.33.32.156 → Acceso denegado + log

// Escenario 3: Empleado trabajando desde casa
// IP: 203.0.113.45 → Acceso denegado
// Solución: Agregar IP a ALLOWED_IPS en .env
```

---

# VALIDATEREQUESTINTEGRITY.PHP

**Ubicación**: `app/Http/Middleware/ValidateRequestIntegrity.php`
**Líneas totales**: 172
**Complejidad**: Alta
**Propósito**: Validar y sanitizar peticiones HTTP contra ataques

---

## 📖 EXPLICACIÓN LÍNEA POR LÍNEA

### 🟢 SECCIÓN 1: DOCUMENTACIÓN Y HANDLE (Líneas 1-31)

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware para validar la integridad de las peticiones HTTP.
 *
 * Previene la inyección de datos corruptos o maliciosos mediante validación
 * estricta de todos los datos de entrada antes de procesarlos.
 */
class ValidateRequestIntegrity
{
    /**
     * Manejar una petición entrante.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Validar todos los inputs de la petición
        $this->validateRequestData($request);

        // Sanitizar los datos de entrada
        $this->sanitizeInput($request);

        return $next($request);
    }
```
**¿Qué hace?** Valida y sanitiza datos de entrada.
**¿De dónde sale?** Patrón de defensa en profundidad.
**¿Para qué sirve?** Prevenir SQL injection, XSS, y otros ataques.

**Flujo del middleware**:
```
1. validateRequestData() → Detecta patrones maliciosos
2. sanitizeInput() → Limpia datos de entrada
3. $next($request) → Pasa petición limpia al controlador
```

**Nota**: NO tiene `declare(strict_types=1)` (código legacy).

---

### 🟢 SECCIÓN 2: MÉTODO validateRequestData (Líneas 33-70)

```php
    /**
     * Validar los datos de la petición.
     *
     * @param  Request  $request
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function validateRequestData(Request $request): void
    {
        $allInput = $request->all();

        foreach ($allInput as $key => $value) {
            // Validar caracteres nulos
            if (is_string($value) && strpos($value, "\0") !== false) {
                abort(400, "Datos inválidos detectados: caracteres nulos en {$key}");
            }

            // Validar SQL injection básica
            if (is_string($value) && $this->containsSQLInjection($value)) {
                \Log::warning("Posible intento de SQL Injection detectado", [
                    'field' => $key,
                    'value' => $value,
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent()
                ]);
                abort(400, "Datos inválidos detectados en {$key}");
            }

            // Validar longitud excesiva (prevenir DoS)
            if (is_string($value) && strlen($value) > 65535) {
                abort(400, "Datos demasiado largos en {$key}");
            }

            // Validar arrays recursivamente
            if (is_array($value)) {
                $this->validateArray($value, $key);
            }
        }
    }
```
**¿Qué hace?** Valida cada input de la petición.
**¿De dónde sale?** Lógica de seguridad.
**¿Para qué sirve?** Detectar ataques ANTES de procesarlos.

**Validaciones implementadas**:

#### 1. Caracteres Nulos (`\0`)
```php
if (is_string($value) && strpos($value, "\0") !== false) {
    abort(400, "Datos inválidos detectados: caracteres nulos en {$key}");
}
```
**¿Por qué es peligroso `\0`?**
- Termina strings en lenguajes como C
- Puede truncar consultas SQL
- Bypass de filtros de seguridad

**Ejemplo de ataque**:
```php
// Atacante envía:
$input = "admin\0' OR '1'='1";

// Sin validación:
// SQL: SELECT * FROM users WHERE username = 'admin' OR '1'='1'
// ✅ Ataque exitoso: acceso a todos los usuarios

// Con validación:
strpos("admin\0' OR '1'='1", "\0") !== false → true
abort(400, "Datos inválidos...")
// ❌ Ataque bloqueado
```

#### 2. SQL Injection
```php
if (is_string($value) && $this->containsSQLInjection($value)) {
    \Log::warning("Posible intento de SQL Injection detectado", [...]);
    abort(400, "Datos inválidos detectados en {$key}");
}
```
**¿Qué detecta?**
- Patrones de SQL como `UNION SELECT`, `DROP TABLE`
- Comentarios SQL (`--`, `#`, `/**/`)
- Condiciones maliciosas (`OR 1=1`)

**Ejemplo de log**:
```
[2025-12-02 10:45:30] local.WARNING: Posible intento de SQL Injection detectado
{
    "field":"username",
    "value":"admin' OR '1'='1",
    "ip":"203.0.113.45",
    "user_agent":"Mozilla/5.0..."
}
```

#### 3. Longitud Excesiva (DoS)
```php
if (is_string($value) && strlen($value) > 65535) {
    abort(400, "Datos demasiado largos en {$key}");
}
```
**¿Por qué limitar longitud?**
- Prevenir Denial of Service (DoS)
- Evitar consumo excesivo de memoria
- Proteger BD de datos gigantes

**Ejemplo de ataque DoS**:
```php
// Atacante envía campo de 10 MB:
POST /control/produccion
observaciones = "A" × 10,000,000

// Sin validación:
// PHP consume 10 MB de RAM por request
// 100 requests simultáneos = 1 GB RAM
// Servidor se queda sin memoria

// Con validación:
strlen("AAA...") > 65535 → true
abort(400, "Datos demasiado largos...")
// Ataque bloqueado antes de consumir recursos
```

#### 4. Arrays Recursivos
```php
if (is_array($value)) {
    $this->validateArray($value, $key);
}
```
**¿Por qué validar arrays?**
- Formularios pueden enviar arrays
- Arrays anidados también necesitan validación
- Atacantes pueden inyectar en arrays

---

### 🟢 SECCIÓN 3: MÉTODO validateArray (Líneas 72-99)

```php
    /**
     * Validar arrays recursivamente.
     *
     * @param  array  $array
     * @param  string  $prefix
     */
    protected function validateArray(array $array, string $prefix): void
    {
        foreach ($array as $key => $value) {
            $fullKey = $prefix . '.' . $key;

            if (is_string($value) && strpos($value, "\0") !== false) {
                abort(400, "Datos inválidos detectados: caracteres nulos en {$fullKey}");
            }

            if (is_string($value) && $this->containsSQLInjection($value)) {
                \Log::warning("Posible intento de SQL Injection detectado", [
                    'field' => $fullKey,
                    'value' => $value
                ]);
                abort(400, "Datos inválidos detectados en {$fullKey}");
            }

            if (is_array($value)) {
                $this->validateArray($value, $fullKey);
            }
        }
    }
```
**¿Qué hace?** Valida arrays de forma recursiva.
**¿De dónde sale?** Recursividad para arrays anidados.
**¿Para qué sirve?** Validar formularios complejos con arrays.

**Ejemplo de array anidado**:
```php
// Formulario de producción:
POST /control/produccion
productos[0][nombre] = "Botellones"
productos[0][cantidad] = "500"
productos[1][nombre] = "Agua natural"
productos[1][cantidad] = "300' OR '1'='1"

// Validación:
// Nivel 1: productos (array)
//   → validateArray(productos, 'productos')
// Nivel 2: productos[0] (array)
//   → validateArray(productos[0], 'productos.0')
// Nivel 3: productos[0][nombre] (string)
//   → Validar "Botellones" ✅
// Nivel 3: productos[0][cantidad] (string)
//   → Validar "500" ✅
// Nivel 2: productos[1] (array)
//   → validateArray(productos[1], 'productos.1')
// Nivel 3: productos[1][cantidad] (string)
//   → Detectar "300' OR '1'='1" ❌
//   → abort(400, "Datos inválidos en productos.1.cantidad")
```

**¿Qué es `$fullKey`?**
- Construye path completo del campo
- Facilita debugging
- Ejemplo: `productos.1.cantidad`

---

### 🟢 SECCIÓN 4: MÉTODO containsSQLInjection (Líneas 101-129)

```php
    /**
     * Detectar patrones comunes de SQL injection.
     *
     * @param  string  $value
     * @return bool
     */
    protected function containsSQLInjection(string $value): bool
    {
        $patterns = [
            '/(\bunion\b.*\bselect\b)/i',
            '/(\bselect\b.*\bfrom\b.*\bwhere\b)/i',
            '/(\bdrop\b.*\btable\b)/i',
            '/(\binsert\b.*\binto\b.*\bvalues\b)/i',
            '/(\bdelete\b.*\bfrom\b)/i',
            '/(\bexec\b.*\()/i',
            '/(\bexecute\b.*\()/i',
            '/(--|\#|\/\*)/',
            '/(\bor\b.*=.*)/i',
            '/(\band\b.*=.*)/i',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $value)) {
                return true;
            }
        }

        return false;
    }
```
**¿Qué hace?** Detecta patrones de SQL injection con regex.
**¿De dónde sale?** OWASP Top 10 (ataques comunes).
**¿Para qué sirve?** Primera línea de defensa contra SQL injection.

**Patrones explicados**:

#### 1. UNION SELECT
```regex
/(\bunion\b.*\bselect\b)/i
```
**Detecta**: `UNION SELECT`, `union select`
**Ejemplo de ataque**:
```sql
-- Atacante envía:
' UNION SELECT username, password FROM users--

-- SQL resultante:
SELECT * FROM productos WHERE nombre = '' UNION SELECT username, password FROM users--'
```

#### 2. SELECT FROM WHERE
```regex
/(\bselect\b.*\bfrom\b.*\bwhere\b)/i
```
**Detecta**: `SELECT * FROM users WHERE...`
**Ejemplo**: Atacante intenta extraer datos completos.

#### 3. DROP TABLE
```regex
/(\bdrop\b.*\btable\b)/i
```
**Detecta**: `DROP TABLE users`, `drop table`
**Ejemplo de ataque**:
```sql
-- Atacante envía:
'; DROP TABLE usuarios--

-- SQL resultante:
UPDATE personal SET nombre = ''; DROP TABLE usuarios--' WHERE id = 1
```

#### 4. Comentarios SQL
```regex
/(--|\#|\/\*)/
```
**Detecta**: `--`, `#`, `/*`
**¿Por qué son peligrosos?**
- Comentan resto de la query
- Ignoran comillas de cierre
- Bypass de validaciones

**Ejemplo**:
```sql
-- Atacante envía:
admin'--

-- SQL resultante:
SELECT * FROM usuarios WHERE username = 'admin'--' AND password = 'xxx'
-- Todo después de -- es comentario
-- Password no se valida
```

#### 5. OR/AND con Igualdad
```regex
/(\bor\b.*=.*)/i
/(\band\b.*=.*)/i
```
**Detecta**: `OR 1=1`, `AND 1=1`
**Ejemplo de ataque**:
```sql
-- Atacante envía:
' OR '1'='1

-- SQL resultante:
SELECT * FROM usuarios WHERE username = '' OR '1'='1' AND password = 'xxx'
-- Siempre true, retorna todos los usuarios
```

**IMPORTANTE**: Esta validación es **básica**, NO reemplaza:
- Prepared statements (la verdadera defensa)
- Validación de formularios
- ORM (Eloquent) que usa prepared statements

---

### 🟢 SECCIÓN 5: MÉTODOS DE SANITIZACIÓN (Líneas 131-171)

```php
    /**
     * Sanitizar los datos de entrada.
     *
     * @param  Request  $request
     */
    protected function sanitizeInput(Request $request): void
    {
        $sanitized = [];

        foreach ($request->all() as $key => $value) {
            $sanitized[$key] = $this->sanitizeValue($value);
        }

        $request->merge($sanitized);
    }

    /**
     * Sanitizar un valor individual.
     *
     * @param  mixed  $value
     * @return mixed
     */
    protected function sanitizeValue($value)
    {
        if (is_string($value)) {
            // Remover caracteres de control
            $value = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u', '', $value);

            // Trim espacios en blanco
            $value = trim($value);

            return $value;
        }

        if (is_array($value)) {
            return array_map([$this, 'sanitizeValue'], $value);
        }

        return $value;
    }
}
```
**¿Qué hace?** Limpia y normaliza datos de entrada.
**¿De dónde sale?** Principio de sanitización de datos.
**¿Para qué sirve?** Eliminar caracteres peligrosos sin rechazar petición.

**Diferencia entre Validación y Sanitización**:
```
Validación: Rechaza datos maliciosos (abort)
Sanitización: Limpia datos sospechosos (transforma)
```

**¿Qué hace `preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/u', '', $value)`?**
- Elimina caracteres de control ASCII
- Mantiene solo caracteres imprimibles
- Previene inyección de caracteres especiales

**Caracteres removidos**:
- `\x00` - `\x08`: Null, bell, backspace, etc.
- `\x0B`: Vertical tab
- `\x0C`: Form feed
- `\x0E` - `\x1F`: Caracteres de control
- `\x7F`: Delete

**Ejemplo**:
```php
// Input malicioso:
$value = "admin\x00\x01\x02test"

// Sanitización:
preg_replace('/[\x00-\x08...]/u', '', $value) → "admintest"
trim("admintest") → "admintest"

// Input normal:
$value = "  Juan Pérez  "
trim($value) → "Juan Pérez"
```

**¿Qué hace `$request->merge($sanitized)`?**
- Reemplaza datos originales con datos sanitizados
- Controlador recibe datos limpios
- Transparente para el desarrollador

**Flujo completo**:
```
1. Request llega: ["nombre" => "  Juan\x00  ", "edad" => "30"]
2. validateRequestData(): Detecta \x00, pero...
3. sanitizeInput(): Limpia datos
4. Request modificado: ["nombre" => "Juan", "edad" => "30"]
5. Controlador recibe: ["nombre" => "Juan", "edad" => "30"]
```

---

## 📊 RESUMEN DE FUNCIONALIDADES

| Middleware | Propósito | Bloquea | Registra |
|------------|-----------|---------|----------|
| CheckRole | Control de acceso por roles | Usuario sin rol/permiso | ❌ No |
| RestrictIpAddress | Restricción por IP | IP no autorizada | ✅ Sí |
| ValidateRequestIntegrity | Validación de peticiones | Ataques SQL/XSS/DoS | ✅ Sí |

### Capas de Seguridad

```
1. RestrictIpAddress → ¿IP permitida?
   ↓ NO → abort(403) + log
   ↓ SÍ
2. CheckRole → ¿Usuario tiene permiso?
   ↓ NO → abort(403)
   ↓ SÍ
3. ValidateRequestIntegrity → ¿Datos válidos?
   ↓ NO → abort(400) + log
   ↓ SÍ
4. Controlador → Procesa petición
```

---

## 🔄 FLUJOS DE TRABAJO

### Flujo 1: Acceso Autorizado Exitoso

```
Usuario: juan@aguacolegial.com (rol: produccion)
IP: 192.168.1.50
Request: POST /control/produccion

1. RestrictIpAddress:
   ✅ IP 192.168.1.50 es red local → $next()

2. CheckRole (role:produccion):
   ✅ Usuario logueado
   ✅ Usuario activo
   ✅ Rol asignado: produccion
   ✅ Rol en lista: [produccion] → $next()

3. ValidateRequestIntegrity:
   ✅ Sin caracteres nulos
   ✅ Sin SQL injection
   ✅ Longitud normal
   ✅ Datos sanitizados → $next()

4. ProduccionDiariaController::store()
   ✅ Procesa petición
```

---

### Flujo 2: Bloqueo por IP No Autorizada

```
Usuario: Atacante desde internet
IP: 45.33.32.156
Request: GET /admin/dashboard

1. RestrictIpAddress:
   ❌ IP no en ALLOWED_IPS
   ❌ IP no es localhost
   ❌ IP no es red local
   → Log: "Intento de acceso no autorizado desde 45.33.32.156"
   → abort(403, "Acceso denegado...")

[Petición detenida - no llega a CheckRole ni controlador]
```

---

### Flujo 3: Bloqueo por Rol Insuficiente

```
Usuario: maria@aguacolegial.com (rol: inventario)
IP: 192.168.1.60
Request: GET /control/produccion

1. RestrictIpAddress:
   ✅ IP 192.168.1.60 es red local → $next()

2. CheckRole (role:produccion):
   ✅ Usuario logueado
   ✅ Usuario activo
   ✅ Rol asignado: inventario
   ❌ Rol NO en lista: [produccion]
   → abort(403, "No tiene permisos...")

[Petición detenida - no llega a ValidateRequestIntegrity]
```

---

### Flujo 4: Bloqueo por SQL Injection

```
Usuario: admin@aguacolegial.com (rol: admin)
IP: 192.168.1.10
Request: POST /control/empleados
nombre = "Juan' OR '1'='1"

1. RestrictIpAddress:
   ✅ IP en ALLOWED_IPS → $next()

2. CheckRole:
   ✅ Usuario admin → $next()

3. ValidateRequestIntegrity:
   ✅ Sin caracteres nulos
   ❌ SQL injection detectado en "nombre"
   → Log: "Posible intento de SQL Injection..."
   → abort(400, "Datos inválidos en nombre")

[Petición detenida - no llega al controlador]
```

---

## ⚙️ CONFIGURACIÓN

### Registrar Middleware

**Archivo**: `app/Http/Kernel.php`

```php
protected $routeMiddleware = [
    // ... otros middleware
    'role' => \App\Http\Middleware\CheckRole::class,
    'ip.restrict' => \App\Http\Middleware\RestrictIpAddress::class,
    'validate.integrity' => \App\Http\Middleware\ValidateRequestIntegrity::class,
];
```

### Usar en Rutas

**Archivo**: `routes/web.php`

```php
// CheckRole: Solo admin
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [DashboardController::class, 'index']);
    Route::resource('/admin/usuarios', UsuarioController::class);
});

// CheckRole: Admin o producción
Route::middleware(['auth', 'role:admin,produccion'])->group(function () {
    Route::resource('/control/produccion', ProduccionController::class);
});

// RestrictIpAddress: Solo IPs autorizadas
Route::middleware(['ip.restrict'])->group(function () {
    Route::get('/admin/configuracion', [ConfigController::class, 'index']);
    Route::post('/admin/backup', [BackupController::class, 'create']);
});

// ValidateRequestIntegrity: Todas las rutas POST/PUT/DELETE
Route::middleware(['validate.integrity'])->group(function () {
    Route::post('/control/*', function () {});
    Route::put('/control/*', function () {});
    Route::delete('/control/*', function () {});
});

// Combinación de múltiples middleware
Route::middleware(['ip.restrict', 'auth', 'role:admin', 'validate.integrity'])->group(function () {
    Route::post('/admin/configuracion', [ConfigController::class, 'update']);
});
```

### Configurar IPs Permitidas

**Archivo**: `.env`

```env
# IPs permitidas (separadas por comas)
ALLOWED_IPS=192.168.1.10,192.168.1.20,192.168.1.30

# O permitir toda una red (configurar en middleware)
# No hace falta listar IPs individuales de 192.168.x.x
# El middleware ya permite redes locales
```

### Middleware Global

**Si quieres aplicar a TODAS las rutas**:

```php
// app/Http/Kernel.php
protected $middleware = [
    // ... otros middleware
    \App\Http\Middleware\ValidateRequestIntegrity::class,
];
```

---

## ✅ TODOS Y MEJORAS FUTURAS

### TODO 1: Rate Limiting por IP

**Problema**: Sin protección contra ataques de fuerza bruta.
**Solución**: Implementar rate limiting en RestrictIpAddress.

```php
// app/Http/Middleware/RestrictIpAddress.php
use Illuminate\Support\Facades\Cache;

public function handle(Request $request, Closure $next): Response
{
    $clientIp = $request->ip();
    $key = 'rate_limit:' . $clientIp;

    // Incrementar contador
    $attempts = Cache::get($key, 0);
    Cache::put($key, $attempts + 1, now()->addMinutes(15));

    // Bloquear si excede límite
    if ($attempts > 100) { // 100 requests en 15 minutos
        \Log::warning("Rate limit excedido para IP: {$clientIp}");
        abort(429, 'Demasiadas peticiones. Intente más tarde.');
    }

    // ... resto del código
}
```

---

### TODO 2: Whitelist de URLs sin Validación

**Problema**: ValidateRequestIntegrity puede causar falsos positivos.
**Solución**: Excluir rutas específicas.

```php
// app/Http/Middleware/ValidateRequestIntegrity.php
public function handle(Request $request, Closure $next): Response
{
    // URLs excluidas de validación
    $excludedUrls = [
        '/api/webhook', // Webhooks externos pueden tener datos especiales
        '/logs/debug',  // Logs pueden contener SQL para debugging
    ];

    if (in_array($request->path(), $excludedUrls)) {
        return $next($request);
    }

    // Validar normalmente
    $this->validateRequestData($request);
    $this->sanitizeInput($request);

    return $next($request);
}
```

---

### TODO 3: Notificaciones de Seguridad

**Problema**: Admin no es notificado de intentos sospechosos en tiempo real.
**Solución**: Enviar email/SMS cuando se detectan ataques.

```php
// app/Http/Middleware/ValidateRequestIntegrity.php
if ($this->containsSQLInjection($value)) {
    \Log::warning("Posible intento de SQL Injection detectado", [...]);

    // Enviar notificación a admin
    \Notification::send(
        User::role('admin')->get(),
        new SecurityAlertNotification([
            'type' => 'SQL Injection',
            'ip' => $request->ip(),
            'url' => $request->fullUrl(),
            'field' => $key,
            'value' => $value
        ])
    );

    abort(400, "Datos inválidos detectados en {$key}");
}
```

---

### TODO 4: Dashboard de Seguridad

**Problema**: Difícil visualizar intentos de ataque.
**Solución**: Crear dashboard de seguridad.

```php
// app/Http/Controllers/Admin/SecurityController.php
public function dashboard()
{
    // Leer logs de seguridad
    $logFile = storage_path('logs/laravel.log');
    $logs = File::get($logFile);

    // Parsear intentos de SQL injection
    preg_match_all('/SQL Injection detectado/', $logs, $sqlInjectionAttempts);

    // Parsear IPs bloqueadas
    preg_match_all('/Intento de acceso no autorizado desde IP: (.+)/', $logs, $blockedIps);

    return view('admin.security-dashboard', [
        'sqlInjectionAttempts' => count($sqlInjectionAttempts[0]),
        'blockedIps' => collect($blockedIps[1])->unique(),
        'lastAttempts' => $this->parseLastAttempts($logs)
    ]);
}
```

---

### TODO 5: Verificación de User-Agent

**Problema**: Bots maliciosos no son detectados.
**Solución**: Bloquear user-agents sospechosos.

```php
// app/Http/Middleware/RestrictIpAddress.php
public function handle(Request $request, Closure $next): Response
{
    // Verificar user-agent
    $userAgent = $request->userAgent();

    $bannedUserAgents = [
        'sqlmap', // Scanner de SQL injection
        'nikto',  // Scanner de vulnerabilidades
        'masscan', // Scanner de puertos
        'nmap',
    ];

    foreach ($bannedUserAgents as $banned) {
        if (str_contains(strtolower($userAgent), $banned)) {
            \Log::warning("User-Agent sospechoso bloqueado", [
                'user_agent' => $userAgent,
                'ip' => $request->ip()
            ]);
            abort(403, 'Acceso denegado');
        }
    }

    // ... resto del código
}
```

---

### TODO 6: Protección contra XSS

**Problema**: ValidateRequestIntegrity no valida XSS.
**Solución**: Agregar detección de scripts maliciosos.

```php
// app/Http/Middleware/ValidateRequestIntegrity.php
protected function containsXSS(string $value): bool
{
    $patterns = [
        '/<script\b[^>]*>/i',
        '/<\/script>/i',
        '/javascript:/i',
        '/onerror\s*=/i',
        '/onload\s*=/i',
        '/<iframe\b[^>]*>/i',
    ];

    foreach ($patterns as $pattern) {
        if (preg_match($pattern, $value)) {
            return true;
        }
    }

    return false;
}

// Usar en validateRequestData:
if (is_string($value) && $this->containsXSS($value)) {
    \Log::warning("Posible intento de XSS detectado", [...]);
    abort(400, "Datos inválidos detectados en {$key}");
}
```

---

## 🎯 CONCLUSIÓN

### Middleware de Seguridad - Importancia Crítica

**Responsabilidades**:
- Primera línea de defensa del sistema
- Control de acceso basado en roles y permisos
- Restricción de acceso por dirección IP
- Detección y bloqueo de ataques (SQL injection, XSS, DoS)
- Validación y sanitización de datos de entrada

**Importancia crítica**:
- Se ejecutan ANTES de los controladores
- Previenen accesos no autorizados
- Protegen contra vulnerabilidades OWASP Top 10
- Generan logs para auditoría de seguridad

**Orden de ejecución recomendado**:
```
1. RestrictIpAddress (bloquear IPs maliciosas)
2. Auth (verificar autenticación)
3. CheckRole (verificar permisos)
4. ValidateRequestIntegrity (validar datos)
5. Controlador
```

**Estado actual**:
- ✅ CheckRole: Completo y funcional
- ✅ RestrictIpAddress: Completo con soporte de redes locales
- ✅ ValidateRequestIntegrity: Básico pero efectivo

**Mejoras prioritarias**:
1. Rate limiting por IP (urgente)
2. Notificaciones de seguridad en tiempo real
3. Dashboard de monitoreo de ataques
4. Detección de XSS
5. Whitelist de URLs para evitar falsos positivos

---

**Documentado por**: Claude (Anthropic)
**Fecha**: 2 de Diciembre de 2025
**Sistema**: Agua Colegial v1.0
**Archivo**: 12-Middleware-Seguridad.md
