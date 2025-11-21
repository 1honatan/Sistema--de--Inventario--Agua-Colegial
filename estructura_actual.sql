-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: agua_colegial_bd
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `alertas_stock`
--

DROP TABLE IF EXISTS `alertas_stock`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `alertas_stock` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Identificador único de la alerta',
  `id_producto` bigint(20) unsigned NOT NULL COMMENT 'Producto con alerta (FK a productos)',
  `cantidad_minima` int(10) unsigned NOT NULL COMMENT 'Cantidad mínima configurada que generó la alerta',
  `cantidad_actual` int(10) unsigned DEFAULT NULL COMMENT 'Cantidad actual en stock al momento de generar la alerta',
  `estado_alerta` enum('activa','atendida','ignorada') NOT NULL DEFAULT 'activa' COMMENT 'Estado de la alerta (activa, atendida o ignorada)',
  `nivel_urgencia` enum('baja','media','alta','critica') NOT NULL DEFAULT 'media' COMMENT 'Nivel de urgencia según el stock restante',
  `fecha_alerta` datetime NOT NULL COMMENT 'Fecha y hora en que se generó la alerta',
  `fecha_atencion` datetime DEFAULT NULL COMMENT 'Fecha y hora en que se atendió la alerta',
  `observaciones` text DEFAULT NULL COMMENT 'Observaciones o acciones tomadas sobre la alerta',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'Fecha de creación del registro',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'Fecha de última actualización',
  PRIMARY KEY (`id`),
  KEY `idx_alertas_id_producto` (`id_producto`),
  KEY `idx_alertas_estado` (`estado_alerta`),
  KEY `idx_alertas_nivel` (`nivel_urgencia`),
  KEY `idx_alertas_fecha` (`fecha_alerta`),
  KEY `idx_alertas_producto_estado` (`id_producto`,`estado_alerta`),
  CONSTRAINT `fk_alertas_producto` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Sistema de alertas de stock bajo o crítico';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `asistencias_semanales`
--

DROP TABLE IF EXISTS `asistencias_semanales`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `asistencias_semanales` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `personal_id` bigint(20) unsigned NOT NULL,
  `fecha` date NOT NULL,
  `dia_semana` varchar(20) NOT NULL,
  `entrada_hora` time NOT NULL,
  `salida_hora` time DEFAULT NULL,
  `observaciones` text DEFAULT NULL,
  `estado` enum('presente','ausente','permiso','tardanza') NOT NULL DEFAULT 'presente',
  `registrado_por` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_fecha` (`fecha`),
  KEY `idx_personal_fecha` (`personal_id`,`fecha`),
  KEY `fk_asistencias_semanales_registrado` (`registrado_por`),
  CONSTRAINT `fk_asistencias_semanales_personal` FOREIGN KEY (`personal_id`) REFERENCES `personal` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_asistencias_semanales_registrado` FOREIGN KEY (`registrado_por`) REFERENCES `personal` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `control_fosa_septica`
--

DROP TABLE IF EXISTS `control_fosa_septica`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `control_fosa_septica` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `fecha_limpieza` date NOT NULL,
  `responsable` varchar(255) NOT NULL,
  `detalle_trabajo` text DEFAULT NULL,
  `empresa_contratada` varchar(255) DEFAULT NULL,
  `proxima_limpieza` date DEFAULT NULL,
  `observaciones` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `control_fumigacion`
--

DROP TABLE IF EXISTS `control_fumigacion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `control_fumigacion` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `fecha_fumigacion` date NOT NULL,
  `area_fumigada` varchar(255) NOT NULL,
  `producto_utilizado` varchar(255) NOT NULL,
  `cantidad_producto` decimal(10,2) NOT NULL,
  `responsable` varchar(255) NOT NULL,
  `empresa_contratada` varchar(255) DEFAULT NULL,
  `proxima_fumigacion` date DEFAULT NULL,
  `observaciones` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `control_insumos`
--

DROP TABLE IF EXISTS `control_insumos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `control_insumos` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `fecha` date NOT NULL,
  `nombre_insumo` varchar(255) NOT NULL,
  `cantidad` decimal(10,2) NOT NULL,
  `unidad_medida` varchar(255) NOT NULL,
  `stock_actual` decimal(10,2) NOT NULL,
  `stock_minimo` decimal(10,2) NOT NULL DEFAULT 0.00,
  `proveedor` varchar(255) DEFAULT NULL,
  `observaciones` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `control_mantenimiento_equipos`
--

DROP TABLE IF EXISTS `control_mantenimiento_equipos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `control_mantenimiento_equipos` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `fecha` date NOT NULL,
  `equipo` varchar(255) NOT NULL,
  `id_personal` bigint(20) unsigned DEFAULT NULL,
  `detalle_mantenimiento` text NOT NULL,
  `productos_limpieza` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`productos_limpieza`)),
  `proxima_fecha` date DEFAULT NULL,
  `realizado_por` varchar(255) NOT NULL,
  `supervisado_por` varchar(255) DEFAULT NULL,
  `observaciones` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_personal` (`id_personal`),
  CONSTRAINT `control_mantenimiento_equipos_ibfk_1` FOREIGN KEY (`id_personal`) REFERENCES `personal` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `control_produccion_diaria`
--

DROP TABLE IF EXISTS `control_produccion_diaria`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `control_produccion_diaria` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `fecha` date NOT NULL,
  `responsable` varchar(255) DEFAULT NULL,
  `turno` varchar(255) DEFAULT NULL,
  `preparacion` varchar(255) DEFAULT NULL,
  `rollos_material` int(11) NOT NULL DEFAULT 0,
  `gasto_material` decimal(10,2) NOT NULL DEFAULT 0.00,
  `observaciones` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `control_produccion_materiales`
--

DROP TABLE IF EXISTS `control_produccion_materiales`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `control_produccion_materiales` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `produccion_id` bigint(20) unsigned NOT NULL,
  `nombre_material` varchar(255) NOT NULL,
  `cantidad` decimal(10,2) NOT NULL DEFAULT 0.00,
  `unidad_medida` varchar(255) NOT NULL DEFAULT 'kg',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `control_produccion_materiales_produccion_id_foreign` (`produccion_id`),
  CONSTRAINT `control_produccion_materiales_produccion_id_foreign` FOREIGN KEY (`produccion_id`) REFERENCES `control_produccion_diaria` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `control_produccion_preparaciones`
--

DROP TABLE IF EXISTS `control_produccion_preparaciones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `control_produccion_preparaciones` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `produccion_id` bigint(20) unsigned NOT NULL,
  `nombre_preparacion` varchar(255) NOT NULL,
  `cantidad` int(11) NOT NULL DEFAULT 0,
  `unidad_medida` varchar(255) NOT NULL DEFAULT 'unidades',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `control_produccion_preparaciones_produccion_id_foreign` (`produccion_id`),
  CONSTRAINT `control_produccion_preparaciones_produccion_id_foreign` FOREIGN KEY (`produccion_id`) REFERENCES `control_produccion_diaria` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `control_produccion_productos`
--

DROP TABLE IF EXISTS `control_produccion_productos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `control_produccion_productos` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `produccion_id` bigint(20) unsigned NOT NULL,
  `producto_id` bigint(20) unsigned NOT NULL,
  `cantidad` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `control_produccion_productos_produccion_id_foreign` (`produccion_id`),
  KEY `control_produccion_productos_producto_id_foreign` (`producto_id`),
  CONSTRAINT `control_produccion_productos_produccion_id_foreign` FOREIGN KEY (`produccion_id`) REFERENCES `control_produccion_diaria` (`id`) ON DELETE CASCADE,
  CONSTRAINT `control_produccion_productos_producto_id_foreign` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `control_salidas_productos`
--

DROP TABLE IF EXISTS `control_salidas_productos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `control_salidas_productos` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nombre_distribuidor` varchar(255) NOT NULL,
  `vehiculo_placa` varchar(255) DEFAULT NULL,
  `fecha` date NOT NULL,
  `lunes` int(11) NOT NULL DEFAULT 0,
  `martes` int(11) NOT NULL DEFAULT 0,
  `miercoles` int(11) NOT NULL DEFAULT 0,
  `jueves` int(11) NOT NULL DEFAULT 0,
  `viernes` int(11) NOT NULL DEFAULT 0,
  `sabado` int(11) NOT NULL DEFAULT 0,
  `domingo` int(11) NOT NULL DEFAULT 0,
  `retornos` int(11) NOT NULL DEFAULT 0,
  `botellones` int(11) NOT NULL DEFAULT 0,
  `bolo_grande` int(11) NOT NULL DEFAULT 0,
  `bolo_peque├▒o` int(11) NOT NULL DEFAULT 0,
  `gelatina` int(11) NOT NULL DEFAULT 0,
  `agua_saborizada` int(11) NOT NULL DEFAULT 0,
  `agua_limon` int(11) NOT NULL DEFAULT 0,
  `agua_natural` int(11) NOT NULL DEFAULT 0,
  `hielo` int(11) NOT NULL DEFAULT 0,
  `dispenser` int(11) NOT NULL DEFAULT 0,
  `choreados` int(11) NOT NULL DEFAULT 0,
  `hora_llegada` time DEFAULT NULL,
  `observaciones` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `control_tanques_agua`
--

DROP TABLE IF EXISTS `control_tanques_agua`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `control_tanques_agua` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `fecha_limpieza` date NOT NULL,
  `nombre_tanque` varchar(255) NOT NULL,
  `capacidad_litros` decimal(10,2) DEFAULT NULL,
  `procedimiento_limpieza` text DEFAULT NULL,
  `productos_desinfeccion` text DEFAULT NULL,
  `responsable` varchar(255) NOT NULL,
  `supervisado_por` varchar(255) DEFAULT NULL,
  `proxima_limpieza` date DEFAULT NULL,
  `observaciones` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uuid` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `historial_reportes`
--

DROP TABLE IF EXISTS `historial_reportes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `historial_reportes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Identificador único del reporte',
  `tipo` varchar(255) NOT NULL COMMENT 'Tipo de reporte generado (inventario, despachos, ventas, etc.)',
  `fecha_inicio` date DEFAULT NULL COMMENT 'Fecha inicio del período del reporte',
  `fecha_fin` date DEFAULT NULL COMMENT 'Fecha fin del período del reporte',
  `id_usuario` bigint(20) unsigned NOT NULL COMMENT 'Usuario que generó el reporte (FK a usuarios)',
  `formato` varchar(50) NOT NULL DEFAULT 'pdf' COMMENT 'Formato de salida del reporte (pdf, excel, csv)',
  `filtros` text DEFAULT NULL COMMENT 'Filtros aplicados al reporte (puede ser JSON)',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'Fecha de generación del reporte',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'Fecha de última actualización',
  PRIMARY KEY (`id`),
  KEY `idx_reportes_tipo` (`tipo`),
  KEY `idx_reportes_id_usuario` (`id_usuario`),
  KEY `idx_reportes_fecha_creacion` (`created_at`),
  KEY `idx_reportes_periodo` (`fecha_inicio`,`fecha_fin`),
  CONSTRAINT `fk_reportes_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Registro de reportes generados por usuarios';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `inventario`
--

DROP TABLE IF EXISTS `inventario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `inventario` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Identificador único del movimiento de inventario',
  `id_producto` bigint(20) unsigned NOT NULL COMMENT 'Producto afectado por el movimiento (FK a productos)',
  `tipo_movimiento` enum('entrada','salida') NOT NULL COMMENT 'Tipo de movimiento: entrada (incrementa) o salida (decrementa)',
  `cantidad` int(10) unsigned NOT NULL COMMENT 'Cantidad de unidades del movimiento (siempre positiva)',
  `origen` varchar(200) DEFAULT NULL COMMENT 'Origen del movimiento (ej: Producción, Proveedor, Ajuste)',
  `destino` varchar(200) DEFAULT NULL COMMENT 'Destino del movimiento (ej: Almacén, Cliente, Merma)',
  `referencia` varchar(100) DEFAULT NULL COMMENT 'Número de referencia o documento asociado',
  `id_usuario` bigint(20) unsigned DEFAULT NULL COMMENT 'Usuario que registró el movimiento (FK a usuarios)',
  `fecha_movimiento` datetime NOT NULL COMMENT 'Fecha y hora del movimiento de inventario',
  `observacion` text DEFAULT NULL COMMENT 'Observaciones o notas adicionales del movimiento',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'Fecha de creación del registro',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'Fecha de última actualización',
  PRIMARY KEY (`id`),
  KEY `idx_inventario_id_producto` (`id_producto`),
  KEY `idx_inventario_tipo_movimiento` (`tipo_movimiento`),
  KEY `idx_inventario_fecha_movimiento` (`fecha_movimiento`),
  KEY `idx_inventario_id_usuario` (`id_usuario`),
  KEY `idx_inventario_producto_fecha` (`id_producto`,`fecha_movimiento`),
  KEY `idx_inventario_producto_tipo` (`id_producto`,`tipo_movimiento`),
  CONSTRAINT `fk_inventario_producto` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_inventario_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Registro de movimientos de inventario (entradas y salidas)';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL COMMENT 'Email del usuario que solicit¾ el reseteo',
  `token` varchar(255) NOT NULL COMMENT 'Token de verificaci¾n',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'Fecha de creaci¾n del token',
  PRIMARY KEY (`email`),
  KEY `idx_password_reset_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tokens para recuperaci¾n de contrase±as';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `personal`
--

DROP TABLE IF EXISTS `personal`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `personal` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Identificador único del empleado',
  `nombre_completo` varchar(255) NOT NULL COMMENT 'Nombre completo del empleado',
  `email` varchar(255) NOT NULL COMMENT 'Correo electrónico del empleado',
  `telefono` varchar(20) DEFAULT NULL COMMENT 'Número de teléfono de contacto',
  `cargo` varchar(100) NOT NULL COMMENT 'Cargo o puesto de trabajo del empleado',
  `area` varchar(100) NOT NULL COMMENT 'Área o departamento (producción, inventario, despacho, etc.)',
  `es_chofer` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Indica si el empleado es chofer (puede hacer despachos)',
  `estado` enum('activo','inactivo') NOT NULL DEFAULT 'activo' COMMENT 'Estado laboral del empleado',
  `tiene_acceso` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Indica si tiene acceso al sistema (tendrá usuario)',
  `id_usuario` bigint(20) unsigned DEFAULT NULL COMMENT 'Usuario asociado (FK a usuarios, opcional)',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'Fecha de creación del registro',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'Fecha de última actualización',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_personal_email` (`email`),
  KEY `idx_personal_cargo` (`cargo`),
  KEY `idx_personal_area` (`area`),
  KEY `idx_personal_estado` (`estado`),
  KEY `idx_personal_es_chofer` (`es_chofer`),
  KEY `idx_personal_id_usuario` (`id_usuario`),
  CONSTRAINT `fk_personal_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Registro de empleados de la empresa';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Identificador ·nico del token',
  `tokenable_type` varchar(255) NOT NULL COMMENT 'Tipo de modelo asociado (morphs)',
  `tokenable_id` bigint(20) unsigned NOT NULL COMMENT 'ID del modelo asociado (morphs)',
  `name` text NOT NULL COMMENT 'Nombre del token',
  `token` varchar(64) NOT NULL COMMENT 'Token ·nico',
  `abilities` text DEFAULT NULL COMMENT 'Permisos del token (JSON)',
  `last_used_at` timestamp NULL DEFAULT NULL COMMENT '┌ltima vez que se us¾ el token',
  `expires_at` timestamp NULL DEFAULT NULL COMMENT 'Fecha de expiraci¾n del token',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'Fecha de creaci¾n',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'Fecha de actualizaci¾n',
  PRIMARY KEY (`id`),
  UNIQUE KEY `token` (`token`),
  KEY `idx_pat_tokenable` (`tokenable_type`,`tokenable_id`),
  KEY `idx_pat_expires` (`expires_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Tokens de acceso personal para API (Sanctum)';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `produccion`
--

DROP TABLE IF EXISTS `produccion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `produccion` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Identificador ·nico del registro de producci¾n',
  `id_producto` bigint(20) unsigned NOT NULL COMMENT 'Producto fabricado (FK a productos)',
  `id_personal` bigint(20) unsigned NOT NULL COMMENT 'Personal responsable de la producci¾n (FK a personal)',
  `lote` varchar(100) NOT NULL COMMENT 'C¾digo ·nico de lote (PROD-YYYYMMDD-NNNN)',
  `cantidad` int(10) unsigned NOT NULL COMMENT 'Cantidad producida',
  `fecha_produccion` date NOT NULL COMMENT 'Fecha de producci¾n del lote',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'Fecha de creaci¾n del registro',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'Fecha de ·ltima actualizaci¾n',
  PRIMARY KEY (`id`),
  UNIQUE KEY `lote` (`lote`),
  KEY `idx_produccion_lote` (`lote`),
  KEY `idx_produccion_fecha` (`fecha_produccion`),
  KEY `idx_produccion_producto` (`id_producto`),
  KEY `idx_produccion_fecha_producto` (`fecha_produccion`,`id_producto`),
  KEY `fk_produccion_personal` (`id_personal`),
  CONSTRAINT `fk_produccion_personal` FOREIGN KEY (`id_personal`) REFERENCES `personal` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_produccion_producto` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Registro de lotes de producci¾n diaria';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `productos`
--

DROP TABLE IF EXISTS `productos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `productos` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Identificador único del producto',
  `nombre` varchar(100) NOT NULL COMMENT 'Nombre del producto (ej: Botellón 20L, Bolsa 500ml)',
  `tipo` varchar(100) DEFAULT NULL,
  `imagen` varchar(255) DEFAULT NULL COMMENT 'Ruta de la imagen del producto',
  `id_tipo_producto` bigint(20) unsigned DEFAULT NULL,
  `unidad_medida` varchar(50) NOT NULL COMMENT 'Unidad de medida (unidad, litro, kilogramo, etc.)',
  `estado` enum('activo','inactivo') NOT NULL DEFAULT 'activo' COMMENT 'Estado del producto (activo o inactivo)',
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'Fecha de registro del producto en el sistema',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'Fecha de creación del registro',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'Fecha de última actualización',
  PRIMARY KEY (`id`),
  KEY `idx_productos_nombre` (`nombre`),
  KEY `idx_productos_tipo` (`tipo`),
  KEY `idx_productos_estado` (`estado`),
  KEY `idx_productos_id_tipo` (`id_tipo_producto`),
  KEY `idx_productos_tipo_estado` (`tipo`,`estado`),
  KEY `idx_productos_unidad_medida` (`unidad_medida`),
  CONSTRAINT `fk_productos_tipo` FOREIGN KEY (`id_tipo_producto`) REFERENCES `tipos_producto` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Catálogo maestro de productos disponibles';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `roles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Identificador único del rol',
  `nombre` varchar(100) NOT NULL COMMENT 'Nombre del rol (admin, inventario, despacho, etc.)',
  `observacion` text DEFAULT NULL COMMENT 'Descripción del rol y sus permisos',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'Fecha de creación del registro',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'Fecha de última actualización',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_roles_nombre` (`nombre`),
  KEY `idx_roles_nombre` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Catálogo de roles y permisos del sistema';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tipos_producto`
--

DROP TABLE IF EXISTS `tipos_producto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tipos_producto` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Identificador único del tipo de producto',
  `nombre` varchar(100) NOT NULL COMMENT 'Nombre del tipo (Botellón, Bolsa, Saborizada, etc.)',
  `codigo` varchar(20) NOT NULL COMMENT 'Código único del tipo de producto',
  `descripcion` text DEFAULT NULL COMMENT 'Descripción detallada del tipo de producto',
  `estado` enum('activo','inactivo') NOT NULL DEFAULT 'activo' COMMENT 'Estado del tipo de producto',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'Fecha de creación del registro',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'Fecha de última actualización',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_tipos_producto_nombre` (`nombre`),
  UNIQUE KEY `uk_tipos_producto_codigo` (`codigo`),
  KEY `idx_tipos_producto_estado` (`estado`),
  KEY `idx_tipos_producto_codigo` (`codigo`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Catálogo de tipos de productos';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usuarios` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Identificador único del usuario',
  `nombre` varchar(100) NOT NULL COMMENT 'Nombre completo del usuario',
  `email` varchar(100) NOT NULL COMMENT 'Correo electrónico para login (único)',
  `password` varchar(255) NOT NULL COMMENT 'Contraseña hasheada con bcrypt',
  `id_rol` bigint(20) unsigned NOT NULL COMMENT 'Rol del usuario (FK a roles)',
  `estado` enum('activo','inactivo') NOT NULL DEFAULT 'activo' COMMENT 'Estado del usuario en el sistema',
  `ultimo_acceso` timestamp NULL DEFAULT NULL COMMENT 'Fecha y hora del último acceso al sistema',
  `remember_token` varchar(100) DEFAULT NULL COMMENT 'Token para recordar sesión (Laravel)',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'Fecha de creación del usuario',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'Fecha de última actualización',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `idx_usuarios_email` (`email`),
  KEY `idx_usuarios_estado` (`estado`),
  KEY `idx_usuarios_id_rol` (`id_rol`),
  KEY `idx_usuarios_ultimo_acceso` (`ultimo_acceso`),
  CONSTRAINT `fk_usuarios_rol` FOREIGN KEY (`id_rol`) REFERENCES `roles` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Usuarios del sistema con credenciales de acceso';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Temporary table structure for view `v_stock_actual`
--

DROP TABLE IF EXISTS `v_stock_actual`;
/*!50001 DROP VIEW IF EXISTS `v_stock_actual`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `v_stock_actual` AS SELECT
 1 AS `id`,
  1 AS `nombre`,
  1 AS `tipo`,
  1 AS `unidad_medida`,
  1 AS `stock_actual`,
  1 AS `estado` */;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `vehiculos`
--

DROP TABLE IF EXISTS `vehiculos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vehiculos` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT 'Identificador único del vehículo',
  `placa` varchar(20) NOT NULL COMMENT 'Placa o matrícula del vehículo (única)',
  `modelo` varchar(100) NOT NULL COMMENT 'Modelo del vehículo',
  `marca` varchar(100) DEFAULT NULL,
  `capacidad` int(10) unsigned NOT NULL COMMENT 'Capacidad de carga en unidades de producto',
  `observacion` text DEFAULT NULL,
  `estado` enum('activo','mantenimiento','inactivo') NOT NULL DEFAULT 'activo',
  `created_at` timestamp NULL DEFAULT NULL COMMENT 'Fecha de creación del registro',
  `updated_at` timestamp NULL DEFAULT NULL COMMENT 'Fecha de última actualización',
  PRIMARY KEY (`id`),
  UNIQUE KEY `placa` (`placa`),
  KEY `idx_vehiculos_placa` (`placa`),
  KEY `idx_vehiculos_estado` (`estado`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Catálogo de vehículos de la empresa';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping events for database 'agua_colegial_bd'
--

--
-- Dumping routines for database 'agua_colegial_bd'
--

--
-- Final view structure for view `v_stock_actual`
--

/*!50001 DROP VIEW IF EXISTS `v_stock_actual`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `v_stock_actual` AS select `p`.`id` AS `id`,`p`.`nombre` AS `nombre`,`p`.`tipo` AS `tipo`,`p`.`unidad_medida` AS `unidad_medida`,coalesce(sum(case when `i`.`tipo_movimiento` = 'entrada' then `i`.`cantidad` when `i`.`tipo_movimiento` = 'salida' then -`i`.`cantidad` else 0 end),0) AS `stock_actual`,`p`.`estado` AS `estado` from (`productos` `p` left join `inventario` `i` on(`p`.`id` = `i`.`id_producto`)) where `p`.`estado` = 'activo' group by `p`.`id`,`p`.`nombre`,`p`.`tipo`,`p`.`unidad_medida`,`p`.`estado` */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-11-14  9:46:37
