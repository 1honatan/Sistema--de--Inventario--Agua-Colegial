-- ============================================================================
-- ESQUEMA PROFESIONAL - AGUA COLEGIAL
-- ============================================================================
-- Versión: 3.2 - Compatible con MariaDB
-- Fecha: 2025-11-04
-- ============================================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

DROP DATABASE IF EXISTS agua_colegial_bd;
CREATE DATABASE agua_colegial_bd
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE agua_colegial_bd;

-- ============================================================================
-- TABLA: roles
-- ============================================================================
CREATE TABLE roles (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY
        COMMENT 'Identificador único del rol',
    nombre VARCHAR(100) NOT NULL
        COMMENT 'Nombre del rol (admin, inventario, despacho, etc.)',
    observacion TEXT NULL
        COMMENT 'Descripción del rol y sus permisos',
    created_at TIMESTAMP NULL
        COMMENT 'Fecha de creación del registro',
    updated_at TIMESTAMP NULL
        COMMENT 'Fecha de última actualización',

    UNIQUE KEY uk_roles_nombre (nombre),
    INDEX idx_roles_nombre (nombre)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Catálogo de roles y permisos del sistema';

-- ============================================================================
-- TABLA: usuarios
-- ============================================================================
CREATE TABLE usuarios (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY
        COMMENT 'Identificador único del usuario',
    nombre VARCHAR(100) NOT NULL
        COMMENT 'Nombre completo del usuario',
    email VARCHAR(100) NOT NULL UNIQUE
        COMMENT 'Correo electrónico para login (único)',
    password VARCHAR(255) NOT NULL
        COMMENT 'Contraseña hasheada con bcrypt',
    id_rol BIGINT UNSIGNED NOT NULL
        COMMENT 'Rol del usuario (FK a roles)',
    estado ENUM('activo', 'inactivo') NOT NULL DEFAULT 'activo'
        COMMENT 'Estado del usuario en el sistema',
    ultimo_acceso TIMESTAMP NULL
        COMMENT 'Fecha y hora del último acceso al sistema',
    remember_token VARCHAR(100) NULL
        COMMENT 'Token para recordar sesión (Laravel)',
    created_at TIMESTAMP NULL
        COMMENT 'Fecha de creación del usuario',
    updated_at TIMESTAMP NULL
        COMMENT 'Fecha de última actualización',

    CONSTRAINT fk_usuarios_rol
        FOREIGN KEY (id_rol) REFERENCES roles(id)
        ON DELETE RESTRICT ON UPDATE CASCADE,

    INDEX idx_usuarios_email (email),
    INDEX idx_usuarios_estado (estado),
    INDEX idx_usuarios_id_rol (id_rol),
    INDEX idx_usuarios_ultimo_acceso (ultimo_acceso)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Usuarios del sistema con credenciales de acceso';

-- ============================================================================
-- TABLA: personal
-- ============================================================================
CREATE TABLE personal (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY
        COMMENT 'Identificador único del empleado',
    nombre_completo VARCHAR(255) NOT NULL
        COMMENT 'Nombre completo del empleado',
    email VARCHAR(255) NOT NULL
        COMMENT 'Correo electrónico del empleado',
    telefono VARCHAR(20) NULL
        COMMENT 'Número de teléfono de contacto',
    cargo VARCHAR(100) NOT NULL
        COMMENT 'Cargo o puesto de trabajo del empleado',
    area VARCHAR(100) NOT NULL
        COMMENT 'Área o departamento (producción, inventario, despacho, etc.)',
    es_chofer BOOLEAN NOT NULL DEFAULT FALSE
        COMMENT 'Indica si el empleado es chofer (puede hacer despachos)',
    estado ENUM('activo', 'inactivo') NOT NULL DEFAULT 'activo'
        COMMENT 'Estado laboral del empleado',
    tiene_acceso BOOLEAN NOT NULL DEFAULT FALSE
        COMMENT 'Indica si tiene acceso al sistema (tendrá usuario)',
    id_usuario BIGINT UNSIGNED NULL
        COMMENT 'Usuario asociado (FK a usuarios, opcional)',
    created_at TIMESTAMP NULL
        COMMENT 'Fecha de creación del registro',
    updated_at TIMESTAMP NULL
        COMMENT 'Fecha de última actualización',

    CONSTRAINT fk_personal_usuario
        FOREIGN KEY (id_usuario) REFERENCES usuarios(id)
        ON DELETE SET NULL ON UPDATE CASCADE,

    UNIQUE KEY uk_personal_email (email),
    INDEX idx_personal_cargo (cargo),
    INDEX idx_personal_area (area),
    INDEX idx_personal_estado (estado),
    INDEX idx_personal_es_chofer (es_chofer),
    INDEX idx_personal_id_usuario (id_usuario)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Registro de empleados de la empresa';

-- ============================================================================
-- TABLA: asistencias
-- ============================================================================
CREATE TABLE asistencias (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY
        COMMENT 'Identificador único del registro de asistencia',
    id_personal BIGINT UNSIGNED NOT NULL
        COMMENT 'Empleado que registra asistencia (FK a personal)',
    fecha DATE NOT NULL
        COMMENT 'Fecha del registro de asistencia',
    hora_entrada TIME NULL
        COMMENT 'Hora de entrada registrada',
    hora_salida TIME NULL
        COMMENT 'Hora de salida registrada',
    estado ENUM('entrada', 'salida', 'ausente') NOT NULL
        COMMENT 'Estado del registro (entrada, salida o ausente)',
    observaciones TEXT NULL
        COMMENT 'Observaciones o notas sobre la asistencia',
    created_at TIMESTAMP NULL
        COMMENT 'Fecha de creación del registro',
    updated_at TIMESTAMP NULL
        COMMENT 'Fecha de última actualización',

    CONSTRAINT fk_asistencias_personal
        FOREIGN KEY (id_personal) REFERENCES personal(id)
        ON DELETE CASCADE ON UPDATE CASCADE,

    INDEX idx_asistencias_fecha (fecha),
    INDEX idx_asistencias_id_personal (id_personal),
    INDEX idx_asistencias_estado (estado),
    INDEX idx_asistencias_personal_fecha (id_personal, fecha)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Control de asistencia y puntualidad del personal';

-- ============================================================================
-- TABLA: tipos_producto
-- ============================================================================
CREATE TABLE tipos_producto (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY
        COMMENT 'Identificador único del tipo de producto',
    nombre VARCHAR(100) NOT NULL
        COMMENT 'Nombre del tipo (Botellón, Bolsa, Saborizada, etc.)',
    codigo VARCHAR(20) NOT NULL
        COMMENT 'Código único del tipo de producto',
    descripcion TEXT NULL
        COMMENT 'Descripción detallada del tipo de producto',
    estado ENUM('activo', 'inactivo') NOT NULL DEFAULT 'activo'
        COMMENT 'Estado del tipo de producto',
    created_at TIMESTAMP NULL
        COMMENT 'Fecha de creación del registro',
    updated_at TIMESTAMP NULL
        COMMENT 'Fecha de última actualización',

    UNIQUE KEY uk_tipos_producto_nombre (nombre),
    UNIQUE KEY uk_tipos_producto_codigo (codigo),
    INDEX idx_tipos_producto_estado (estado),
    INDEX idx_tipos_producto_codigo (codigo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Catálogo de tipos de productos';

-- ============================================================================
-- TABLA: productos
-- ============================================================================
CREATE TABLE productos (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY
        COMMENT 'Identificador único del producto',
    nombre VARCHAR(100) NOT NULL
        COMMENT 'Nombre del producto (ej: Botellón 20L, Bolsa 500ml)',
    tipo VARCHAR(100) NOT NULL
        COMMENT 'Tipo de producto (botellón, bolsa, saborizada, etc.)',
    imagen VARCHAR(255) NULL
        COMMENT 'Ruta de la imagen del producto',
    id_tipo_producto BIGINT UNSIGNED NULL
        COMMENT 'Tipo de producto (FK a tipos_producto, opcional)',
    unidad_medida VARCHAR(50) NOT NULL
        COMMENT 'Unidad de medida (unidad, litro, kilogramo, etc.)',
    estado ENUM('activo', 'inactivo') NOT NULL DEFAULT 'activo'
        COMMENT 'Estado del producto (activo o inactivo)',
    fecha_registro TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
        COMMENT 'Fecha de registro del producto en el sistema',
    created_at TIMESTAMP NULL
        COMMENT 'Fecha de creación del registro',
    updated_at TIMESTAMP NULL
        COMMENT 'Fecha de última actualización',

    CONSTRAINT fk_productos_tipo
        FOREIGN KEY (id_tipo_producto) REFERENCES tipos_producto(id)
        ON DELETE SET NULL ON UPDATE CASCADE,

    INDEX idx_productos_nombre (nombre),
    INDEX idx_productos_tipo (tipo),
    INDEX idx_productos_estado (estado),
    INDEX idx_productos_id_tipo (id_tipo_producto),
    INDEX idx_productos_tipo_estado (tipo, estado),
    INDEX idx_productos_unidad_medida (unidad_medida)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Catálogo maestro de productos disponibles';

-- ============================================================================
-- TABLA: produccion
-- ============================================================================
CREATE TABLE produccion (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY
        COMMENT 'Identificador único del registro de producción',
    id_producto BIGINT UNSIGNED NOT NULL
        COMMENT 'Producto fabricado (FK a productos)',
    id_personal BIGINT UNSIGNED NOT NULL
        COMMENT 'Personal responsable de la producción (FK a personal)',
    lote VARCHAR(100) NOT NULL UNIQUE
        COMMENT 'Código único de lote (PROD-YYYYMMDD-NNNN)',
    cantidad INT UNSIGNED NOT NULL
        COMMENT 'Cantidad producida',
    fecha_produccion DATE NOT NULL
        COMMENT 'Fecha de producción del lote',
    created_at TIMESTAMP NULL
        COMMENT 'Fecha de creación del registro',
    updated_at TIMESTAMP NULL
        COMMENT 'Fecha de última actualización',

    CONSTRAINT fk_produccion_producto
        FOREIGN KEY (id_producto) REFERENCES productos(id)
        ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_produccion_personal
        FOREIGN KEY (id_personal) REFERENCES personal(id)
        ON DELETE RESTRICT ON UPDATE CASCADE,

    INDEX idx_produccion_lote (lote),
    INDEX idx_produccion_fecha (fecha_produccion),
    INDEX idx_produccion_producto (id_producto),
    INDEX idx_produccion_fecha_producto (fecha_produccion, id_producto)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Registro de lotes de producción diaria';

-- ============================================================================
-- TABLA: inventario
-- ============================================================================
CREATE TABLE inventario (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY
        COMMENT 'Identificador único del movimiento de inventario',
    id_producto BIGINT UNSIGNED NOT NULL
        COMMENT 'Producto afectado por el movimiento (FK a productos)',
    tipo_movimiento ENUM('entrada', 'salida') NOT NULL
        COMMENT 'Tipo de movimiento: entrada (incrementa) o salida (decrementa)',
    cantidad INT UNSIGNED NOT NULL
        COMMENT 'Cantidad de unidades del movimiento (siempre positiva)',
    origen VARCHAR(200) NULL
        COMMENT 'Origen del movimiento (ej: Producción, Proveedor, Ajuste)',
    destino VARCHAR(200) NULL
        COMMENT 'Destino del movimiento (ej: Almacén, Cliente, Merma)',
    referencia VARCHAR(100) NULL
        COMMENT 'Número de referencia o documento asociado',
    id_usuario BIGINT UNSIGNED NULL
        COMMENT 'Usuario que registró el movimiento (FK a usuarios)',
    fecha_movimiento DATETIME NOT NULL
        COMMENT 'Fecha y hora del movimiento de inventario',
    observacion TEXT NULL
        COMMENT 'Observaciones o notas adicionales del movimiento',
    created_at TIMESTAMP NULL
        COMMENT 'Fecha de creación del registro',
    updated_at TIMESTAMP NULL
        COMMENT 'Fecha de última actualización',

    CONSTRAINT fk_inventario_producto
        FOREIGN KEY (id_producto) REFERENCES productos(id)
        ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_inventario_usuario
        FOREIGN KEY (id_usuario) REFERENCES usuarios(id)
        ON DELETE SET NULL ON UPDATE CASCADE,

    INDEX idx_inventario_id_producto (id_producto),
    INDEX idx_inventario_tipo_movimiento (tipo_movimiento),
    INDEX idx_inventario_fecha_movimiento (fecha_movimiento),
    INDEX idx_inventario_id_usuario (id_usuario),
    INDEX idx_inventario_producto_fecha (id_producto, fecha_movimiento),
    INDEX idx_inventario_producto_tipo (id_producto, tipo_movimiento)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Registro de movimientos de inventario (entradas y salidas)';

-- ============================================================================
-- TABLA: alertas_stock
-- ============================================================================
CREATE TABLE alertas_stock (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY
        COMMENT 'Identificador único de la alerta',
    id_producto BIGINT UNSIGNED NOT NULL
        COMMENT 'Producto con alerta (FK a productos)',
    cantidad_minima INT UNSIGNED NOT NULL
        COMMENT 'Cantidad mínima configurada que generó la alerta',
    cantidad_actual INT UNSIGNED NULL
        COMMENT 'Cantidad actual en stock al momento de generar la alerta',
    estado_alerta ENUM('activa', 'atendida', 'ignorada') NOT NULL DEFAULT 'activa'
        COMMENT 'Estado de la alerta (activa, atendida o ignorada)',
    nivel_urgencia ENUM('baja', 'media', 'alta', 'critica') NOT NULL DEFAULT 'media'
        COMMENT 'Nivel de urgencia según el stock restante',
    fecha_alerta DATETIME NOT NULL
        COMMENT 'Fecha y hora en que se generó la alerta',
    fecha_atencion DATETIME NULL
        COMMENT 'Fecha y hora en que se atendió la alerta',
    observaciones TEXT NULL
        COMMENT 'Observaciones o acciones tomadas sobre la alerta',
    created_at TIMESTAMP NULL
        COMMENT 'Fecha de creación del registro',
    updated_at TIMESTAMP NULL
        COMMENT 'Fecha de última actualización',

    CONSTRAINT fk_alertas_producto
        FOREIGN KEY (id_producto) REFERENCES productos(id)
        ON DELETE CASCADE ON UPDATE CASCADE,

    INDEX idx_alertas_id_producto (id_producto),
    INDEX idx_alertas_estado (estado_alerta),
    INDEX idx_alertas_nivel (nivel_urgencia),
    INDEX idx_alertas_fecha (fecha_alerta),
    INDEX idx_alertas_producto_estado (id_producto, estado_alerta)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Sistema de alertas de stock bajo o crítico';

-- ============================================================================
-- TABLA: vehiculos
-- ============================================================================
CREATE TABLE vehiculos (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY
        COMMENT 'Identificador único del vehículo',
    placa VARCHAR(20) NOT NULL UNIQUE
        COMMENT 'Placa o matrícula del vehículo (única)',
    modelo VARCHAR(100) NOT NULL
        COMMENT 'Modelo del vehículo',
    capacidad INT UNSIGNED NOT NULL
        COMMENT 'Capacidad de carga en unidades de producto',
    estado ENUM('activo', 'inactivo') NOT NULL DEFAULT 'activo'
        COMMENT 'Estado operativo del vehículo',
    created_at TIMESTAMP NULL
        COMMENT 'Fecha de creación del registro',
    updated_at TIMESTAMP NULL
        COMMENT 'Fecha de última actualización',

    INDEX idx_vehiculos_placa (placa),
    INDEX idx_vehiculos_estado (estado)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Catálogo de vehículos de la empresa';

-- ============================================================================
-- TABLA: despachos
-- ============================================================================
CREATE TABLE despachos (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY
        COMMENT 'Identificador único del despacho',
    id_vehiculo BIGINT UNSIGNED NOT NULL
        COMMENT 'Vehículo utilizado (FK a vehiculos)',
    id_personal BIGINT UNSIGNED NOT NULL
        COMMENT 'Chofer responsable del despacho (FK a personal)',
    fecha_despacho DATE NOT NULL
        COMMENT 'Fecha del despacho',
    hora_salida TIME NOT NULL
        COMMENT 'Hora de salida del vehículo desde almacén',
    hora_retorno TIME NULL
        COMMENT 'Hora de retorno del vehículo al almacén',
    zona_destino VARCHAR(200) NULL
        COMMENT 'Zona o área de destino del despacho',
    estado ENUM('en_ruta', 'completado', 'cancelado') NOT NULL DEFAULT 'en_ruta'
        COMMENT 'Estado del despacho (en_ruta, completado o cancelado)',
    observacion TEXT NULL
        COMMENT 'Observaciones o notas del despacho',
    created_at TIMESTAMP NULL
        COMMENT 'Fecha de creación del registro',
    updated_at TIMESTAMP NULL
        COMMENT 'Fecha de última actualización',

    CONSTRAINT fk_despachos_vehiculo
        FOREIGN KEY (id_vehiculo) REFERENCES vehiculos(id)
        ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_despachos_personal
        FOREIGN KEY (id_personal) REFERENCES personal(id)
        ON DELETE RESTRICT ON UPDATE CASCADE,

    INDEX idx_despachos_fecha (fecha_despacho),
    INDEX idx_despachos_id_vehiculo (id_vehiculo),
    INDEX idx_despachos_id_personal (id_personal),
    INDEX idx_despachos_estado (estado),
    INDEX idx_despachos_fecha_estado (fecha_despacho, estado)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Registro de despachos de productos';

-- ============================================================================
-- TABLA: detalle_despacho
-- ============================================================================
CREATE TABLE detalle_despacho (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY
        COMMENT 'Identificador único del detalle',
    id_despacho BIGINT UNSIGNED NOT NULL
        COMMENT 'Despacho al que pertenece (FK a despachos)',
    id_producto BIGINT UNSIGNED NOT NULL
        COMMENT 'Producto despachado (FK a productos)',
    cantidad INT UNSIGNED NOT NULL
        COMMENT 'Cantidad despachada del producto',
    cantidad_entregada INT UNSIGNED NOT NULL DEFAULT 0
        COMMENT 'Cantidad efectivamente entregada al cliente',
    cantidad_devuelta INT UNSIGNED NOT NULL DEFAULT 0
        COMMENT 'Cantidad devuelta (no entregada)',
    created_at TIMESTAMP NULL
        COMMENT 'Fecha de creación del registro',
    updated_at TIMESTAMP NULL
        COMMENT 'Fecha de última actualización',

    CONSTRAINT fk_detalle_despacho
        FOREIGN KEY (id_despacho) REFERENCES despachos(id)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_detalle_producto
        FOREIGN KEY (id_producto) REFERENCES productos(id)
        ON DELETE RESTRICT ON UPDATE CASCADE,

    INDEX idx_detalle_id_despacho (id_despacho),
    INDEX idx_detalle_id_producto (id_producto),
    INDEX idx_detalle_despacho_producto (id_despacho, id_producto)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Detalle de productos despachados en cada despacho';

-- ============================================================================
-- TABLA: historial_reportes
-- ============================================================================
CREATE TABLE historial_reportes (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY
        COMMENT 'Identificador único del reporte',
    tipo VARCHAR(255) NOT NULL
        COMMENT 'Tipo de reporte generado (inventario, despachos, ventas, etc.)',
    fecha_inicio DATE NULL
        COMMENT 'Fecha inicio del período del reporte',
    fecha_fin DATE NULL
        COMMENT 'Fecha fin del período del reporte',
    id_usuario BIGINT UNSIGNED NOT NULL
        COMMENT 'Usuario que generó el reporte (FK a usuarios)',
    formato VARCHAR(50) NOT NULL DEFAULT 'pdf'
        COMMENT 'Formato de salida del reporte (pdf, excel, csv)',
    filtros TEXT NULL
        COMMENT 'Filtros aplicados al reporte (puede ser JSON)',
    created_at TIMESTAMP NULL
        COMMENT 'Fecha de generación del reporte',
    updated_at TIMESTAMP NULL
        COMMENT 'Fecha de última actualización',

    CONSTRAINT fk_reportes_usuario
        FOREIGN KEY (id_usuario) REFERENCES usuarios(id)
        ON DELETE CASCADE ON UPDATE CASCADE,

    INDEX idx_reportes_tipo (tipo),
    INDEX idx_reportes_id_usuario (id_usuario),
    INDEX idx_reportes_fecha_creacion (created_at),
    INDEX idx_reportes_periodo (fecha_inicio, fecha_fin)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Registro de reportes generados por usuarios';

-- ============================================================================
-- VISTAS PARA REPORTES
-- ============================================================================

-- Vista: Stock actual por producto
CREATE OR REPLACE VIEW v_stock_actual AS
SELECT
    p.id,
    p.nombre,
    p.tipo,
    p.unidad_medida,
    COALESCE(SUM(
        CASE
            WHEN i.tipo_movimiento = 'entrada' THEN i.cantidad
            WHEN i.tipo_movimiento = 'salida' THEN -i.cantidad
            ELSE 0
        END
    ), 0) AS stock_actual,
    p.estado
FROM productos p
LEFT JOIN inventario i ON p.id = i.id_producto
WHERE p.estado = 'activo'
GROUP BY p.id, p.nombre, p.tipo, p.unidad_medida, p.estado;

-- Vista: Despachos del día actual
CREATE OR REPLACE VIEW v_despachos_hoy AS
SELECT
    d.id,
    d.fecha_despacho,
    d.hora_salida,
    d.hora_retorno,
    v.placa AS vehiculo,
    per.nombre_completo AS chofer,
    d.zona_destino,
    d.estado,
    d.observacion
FROM despachos d
INNER JOIN vehiculos v ON d.id_vehiculo = v.id
INNER JOIN personal per ON d.id_personal = per.id
WHERE d.fecha_despacho = CURDATE()
ORDER BY d.hora_salida ASC;

-- Vista: Alertas activas
CREATE OR REPLACE VIEW v_alertas_activas AS
SELECT
    a.id,
    p.nombre AS producto,
    p.tipo,
    a.cantidad_actual,
    a.cantidad_minima,
    a.nivel_urgencia,
    a.fecha_alerta,
    DATEDIFF(NOW(), a.fecha_alerta) AS dias_alerta
FROM alertas_stock a
INNER JOIN productos p ON a.id_producto = p.id
WHERE a.estado_alerta = 'activa'
ORDER BY
    FIELD(a.nivel_urgencia, 'critica', 'alta', 'media', 'baja'),
    a.fecha_alerta ASC;

-- ============================================================================
-- TABLAS DEL SISTEMA LARAVEL
-- ============================================================================

-- Tabla: cache
CREATE TABLE cache (
    `key` VARCHAR(255) NOT NULL
        COMMENT 'Clave del elemento en cache',
    value MEDIUMTEXT NOT NULL
        COMMENT 'Valor almacenado en cache',
    expiration INT NOT NULL
        COMMENT 'Timestamp de expiración',
    PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Almacenamiento de cache de Laravel';

-- Tabla: cache_locks
CREATE TABLE cache_locks (
    `key` VARCHAR(255) NOT NULL
        COMMENT 'Clave del lock',
    owner VARCHAR(255) NOT NULL
        COMMENT 'Propietario del lock',
    expiration INT NOT NULL
        COMMENT 'Timestamp de expiración del lock',
    PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Locks de cache de Laravel';

-- Tabla: migrations
CREATE TABLE migrations (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY
        COMMENT 'ID de la migración',
    migration VARCHAR(255) NOT NULL
        COMMENT 'Nombre de la migración',
    batch INT NOT NULL
        COMMENT 'Número de lote de ejecución'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Control de migraciones de Laravel';

-- Tabla: sessions
CREATE TABLE sessions (
    id VARCHAR(255) NOT NULL
        COMMENT 'ID único de la sesión',
    user_id BIGINT UNSIGNED NULL
        COMMENT 'ID del usuario (opcional)',
    ip_address VARCHAR(45) NULL
        COMMENT 'Dirección IP del cliente',
    user_agent TEXT NULL
        COMMENT 'User agent del navegador',
    payload LONGTEXT NOT NULL
        COMMENT 'Datos de la sesión serializados',
    last_activity INT NOT NULL
        COMMENT 'Timestamp de última actividad',
    PRIMARY KEY (id),
    INDEX sessions_user_id_index (user_id),
    INDEX sessions_last_activity_index (last_activity)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Almacenamiento de sesiones de Laravel';

-- Tabla: jobs
CREATE TABLE jobs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY
        COMMENT 'ID único del trabajo',
    queue VARCHAR(255) NOT NULL
        COMMENT 'Nombre de la cola',
    payload LONGTEXT NOT NULL
        COMMENT 'Datos del trabajo serializados',
    attempts TINYINT UNSIGNED NOT NULL
        COMMENT 'Número de intentos ejecutados',
    reserved_at INT UNSIGNED NULL
        COMMENT 'Timestamp cuando se reservó',
    available_at INT UNSIGNED NOT NULL
        COMMENT 'Timestamp cuando estará disponible',
    created_at INT UNSIGNED NOT NULL
        COMMENT 'Timestamp de creación',
    INDEX jobs_queue_index (queue)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Cola de trabajos de Laravel';

-- Tabla: failed_jobs
CREATE TABLE failed_jobs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY
        COMMENT 'ID único del trabajo fallido',
    uuid VARCHAR(255) NOT NULL UNIQUE
        COMMENT 'UUID único del trabajo',
    connection TEXT NOT NULL
        COMMENT 'Nombre de la conexión',
    queue TEXT NOT NULL
        COMMENT 'Nombre de la cola',
    payload LONGTEXT NOT NULL
        COMMENT 'Datos del trabajo serializados',
    exception LONGTEXT NOT NULL
        COMMENT 'Excepción que causó el fallo',
    failed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        COMMENT 'Timestamp del fallo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Registro de trabajos fallidos de Laravel';

-- Tabla: password_reset_tokens
CREATE TABLE password_reset_tokens (
    email VARCHAR(255) NOT NULL PRIMARY KEY
        COMMENT 'Email del usuario que solicitó el reseteo',
    token VARCHAR(255) NOT NULL
        COMMENT 'Token de verificación',
    created_at TIMESTAMP NULL
        COMMENT 'Fecha de creación del token',

    INDEX idx_password_reset_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Tokens para recuperación de contraseñas';

-- Tabla: personal_access_tokens
CREATE TABLE personal_access_tokens (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY
        COMMENT 'Identificador único del token',
    tokenable_type VARCHAR(255) NOT NULL
        COMMENT 'Tipo de modelo asociado (morphs)',
    tokenable_id BIGINT UNSIGNED NOT NULL
        COMMENT 'ID del modelo asociado (morphs)',
    name TEXT NOT NULL
        COMMENT 'Nombre del token',
    token VARCHAR(64) NOT NULL UNIQUE
        COMMENT 'Token único',
    abilities TEXT NULL
        COMMENT 'Permisos del token (JSON)',
    last_used_at TIMESTAMP NULL
        COMMENT 'Última vez que se usó el token',
    expires_at TIMESTAMP NULL
        COMMENT 'Fecha de expiración del token',
    created_at TIMESTAMP NULL
        COMMENT 'Fecha de creación',
    updated_at TIMESTAMP NULL
        COMMENT 'Fecha de actualización',

    INDEX idx_pat_tokenable (tokenable_type, tokenable_id),
    INDEX idx_pat_expires (expires_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Tokens de acceso personal para API (Sanctum)';

-- ============================================================================
-- DATOS INICIALES (SEEDS)
-- ============================================================================

INSERT INTO roles (nombre, observacion, created_at, updated_at) VALUES
('admin', 'Administrador con acceso total al sistema', NOW(), NOW()),
('gerente', 'Gerente con permisos de supervisión y reportes', NOW(), NOW()),
('inventario', 'Encargado de gestión de inventario y productos', NOW(), NOW()),
('despacho', 'Encargado de despachos y distribución', NOW(), NOW()),
('operador', 'Operador con acceso limitado', NOW(), NOW());

-- Usuario administrador por defecto
-- Email: admin@colegial.com
-- Contraseña: admin123
INSERT INTO usuarios (nombre, email, password, id_rol, estado, created_at, updated_at) VALUES
('Administrador', 'admin@colegial.com', '$2y$12$RoNC8plDyyGh5Ua6M5cfn.16AflNya3VxX7aHwWRywYv0cu3C9Uh2', 1, 'activo', NOW(), NOW());

-- ============================================================================
-- ACTIVAR FOREIGN KEY CHECKS
-- ============================================================================

SET FOREIGN_KEY_CHECKS = 1;

-- ============================================================================
-- VERIFICACIÓN
-- ============================================================================

SELECT '✅ Base de datos creada exitosamente' AS status,
       (SELECT COUNT(*) FROM information_schema.TABLES
        WHERE TABLE_SCHEMA = 'agua_colegial_bd'
        AND TABLE_TYPE = 'BASE TABLE') AS total_tablas,
       (SELECT COUNT(*) FROM information_schema.KEY_COLUMN_USAGE
        WHERE TABLE_SCHEMA = 'agua_colegial_bd'
        AND REFERENCED_TABLE_NAME IS NOT NULL) AS total_foreign_keys,
       (SELECT COUNT(*) FROM information_schema.TABLES
        WHERE TABLE_SCHEMA = 'agua_colegial_bd'
        AND TABLE_TYPE = 'VIEW') AS total_vistas;
