-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 04-12-2025 a las 23:02:20
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `agua_colegial_bd_new`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `admin_asignaciones`
--

CREATE TABLE `admin_asignaciones` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_personal` bigint(20) UNSIGNED NOT NULL,
  `tipo_asignacion` enum('chofer','responsable_vehiculo','mantenimiento','produccion','fumigacion','tanques','fosa_septica','insumos','supervisor','otro') NOT NULL COMMENT 'Tipo de tarea asignada',
  `modulo` varchar(255) DEFAULT NULL COMMENT 'Módulo del sistema',
  `id_referencia` bigint(20) UNSIGNED DEFAULT NULL COMMENT 'ID del registro relacionado',
  `fecha_inicio` date NOT NULL COMMENT 'Fecha de inicio de asignación',
  `fecha_fin` date DEFAULT NULL COMMENT 'Fecha fin (null = indefinida)',
  `estado` enum('activa','suspendida','finalizada') NOT NULL DEFAULT 'activa',
  `descripcion` text DEFAULT NULL COMMENT 'Descripción de la asignación',
  `observaciones` text DEFAULT NULL,
  `asignado_por` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `admin_historial_asignaciones`
--

CREATE TABLE `admin_historial_asignaciones` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_asignacion` bigint(20) UNSIGNED NOT NULL,
  `accion` varchar(255) NOT NULL COMMENT 'creada, modificada, suspendida, finalizada',
  `detalles` text DEFAULT NULL,
  `realizado_por` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `alertas_stock`
--

CREATE TABLE `alertas_stock` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_producto` bigint(20) UNSIGNED NOT NULL,
  `cantidad_minima` int(10) UNSIGNED NOT NULL COMMENT 'Cantidad mínima de stock para generar alerta',
  `cantidad_actual` int(10) UNSIGNED DEFAULT NULL COMMENT 'Cantidad actual en stock al generar la alerta',
  `estado_alerta` enum('activa','atendida','ignorada') NOT NULL DEFAULT 'activa' COMMENT 'Estado de la alerta',
  `nivel_urgencia` enum('baja','media','alta','critica') NOT NULL DEFAULT 'media' COMMENT 'Nivel de urgencia de la alerta',
  `fecha_alerta` datetime NOT NULL COMMENT 'Fecha y hora en que se generó la alerta',
  `fecha_atencion` datetime DEFAULT NULL COMMENT 'Fecha y hora en que se atendió la alerta',
  `observaciones` text DEFAULT NULL COMMENT 'Observaciones sobre la alerta',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asistencias_semanales`
--

CREATE TABLE `asistencias_semanales` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `personal_id` bigint(20) UNSIGNED NOT NULL,
  `fecha` date NOT NULL,
  `dia_semana` varchar(20) NOT NULL,
  `entrada_hora` time NOT NULL,
  `salida_hora` time DEFAULT NULL,
  `observaciones` text DEFAULT NULL,
  `estado` enum('presente','ausente','permiso','tardanza') NOT NULL DEFAULT 'presente',
  `registrado_por` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `asistencias_semanales`
--

INSERT INTO `asistencias_semanales` (`id`, `personal_id`, `fecha`, `dia_semana`, `entrada_hora`, `salida_hora`, `observaciones`, `estado`, `registrado_por`, `created_at`, `updated_at`) VALUES
(1, 6, '2025-11-01', 'S├íbado', '06:00:00', '13:00:00', NULL, 'presente', NULL, NULL, NULL),
(2, 7, '2025-11-01', 'S├íbado', '06:05:00', '14:00:00', NULL, 'presente', NULL, NULL, NULL),
(3, 8, '2025-11-01', 'S├íbado', '06:00:00', '13:30:00', NULL, 'presente', NULL, NULL, NULL),
(4, 9, '2025-11-01', 'S├íbado', '06:00:00', '13:00:00', NULL, 'presente', NULL, NULL, NULL),
(5, 10, '2025-11-01', 'S├íbado', '06:10:00', '14:00:00', NULL, 'presente', NULL, NULL, NULL),
(6, 11, '2025-11-01', 'S├íbado', '06:00:00', '13:00:00', NULL, 'presente', NULL, NULL, NULL),
(7, 12, '2025-11-01', 'S├íbado', '06:00:00', '13:30:00', NULL, 'presente', NULL, NULL, NULL),
(8, 13, '2025-11-01', 'S├íbado', '06:05:00', '14:00:00', NULL, 'presente', NULL, NULL, NULL),
(9, 14, '2025-11-01', 'S├íbado', '06:00:00', '13:00:00', NULL, 'presente', NULL, NULL, NULL),
(10, 15, '2025-11-01', 'S├íbado', '06:00:00', '13:00:00', NULL, 'presente', NULL, NULL, NULL),
(11, 16, '2025-11-01', 'S├íbado', '06:10:00', '14:00:00', NULL, 'presente', NULL, NULL, NULL),
(12, 6, '2025-11-02', 'Domingo', '06:00:00', NULL, NULL, 'ausente', NULL, NULL, NULL),
(13, 7, '2025-11-02', 'Domingo', '06:00:00', NULL, NULL, 'ausente', NULL, NULL, NULL),
(14, 8, '2025-11-02', 'Domingo', '06:00:00', NULL, NULL, 'ausente', NULL, NULL, NULL),
(15, 9, '2025-11-02', 'Domingo', '06:00:00', NULL, NULL, 'ausente', NULL, NULL, NULL),
(16, 10, '2025-11-02', 'Domingo', '06:00:00', NULL, NULL, 'ausente', NULL, NULL, NULL),
(17, 11, '2025-11-02', 'Domingo', '06:00:00', NULL, NULL, 'ausente', NULL, NULL, NULL),
(18, 12, '2025-11-02', 'Domingo', '06:00:00', NULL, NULL, 'ausente', NULL, NULL, NULL),
(19, 13, '2025-11-02', 'Domingo', '06:00:00', NULL, NULL, 'ausente', NULL, NULL, NULL),
(20, 14, '2025-11-02', 'Domingo', '06:00:00', NULL, NULL, 'ausente', NULL, NULL, NULL),
(21, 15, '2025-11-02', 'Domingo', '06:00:00', NULL, NULL, 'ausente', NULL, NULL, NULL),
(22, 16, '2025-11-02', 'Domingo', '06:00:00', NULL, NULL, 'ausente', NULL, NULL, NULL),
(23, 6, '2025-11-03', 'Lunes', '06:00:00', '16:00:00', NULL, 'presente', NULL, NULL, NULL),
(24, 7, '2025-11-03', 'Lunes', '06:00:00', '18:00:00', NULL, 'presente', NULL, NULL, NULL),
(25, 8, '2025-11-03', 'Lunes', '06:20:00', '18:00:00', NULL, 'tardanza', NULL, NULL, NULL),
(26, 9, '2025-11-03', 'Lunes', '06:00:00', '16:00:00', NULL, 'presente', NULL, NULL, NULL),
(27, 10, '2025-11-03', 'Lunes', '06:00:00', '16:00:00', NULL, 'presente', NULL, NULL, NULL),
(28, 11, '2025-11-03', 'Lunes', '06:00:00', '18:00:00', NULL, 'presente', NULL, NULL, NULL),
(29, 12, '2025-11-03', 'Lunes', '06:00:00', '16:00:00', NULL, 'presente', NULL, NULL, NULL),
(30, 13, '2025-11-03', 'Lunes', '06:00:00', '18:00:00', NULL, 'presente', NULL, NULL, NULL),
(31, 14, '2025-11-03', 'Lunes', '06:15:00', '18:00:00', NULL, 'tardanza', NULL, NULL, NULL),
(32, 15, '2025-11-03', 'Lunes', '06:00:00', '16:00:00', NULL, 'presente', NULL, NULL, NULL),
(33, 16, '2025-11-03', 'Lunes', '06:00:00', '18:00:00', NULL, 'presente', NULL, NULL, NULL),
(34, 6, '2025-11-04', 'Martes', '06:00:00', '18:00:00', NULL, 'presente', NULL, NULL, NULL),
(35, 7, '2025-11-04', 'Martes', '06:00:00', '16:00:00', NULL, 'presente', NULL, NULL, NULL),
(36, 8, '2025-11-04', 'Martes', '06:00:00', '18:00:00', NULL, 'presente', NULL, NULL, NULL),
(37, 9, '2025-11-04', 'Martes', '06:00:00', '16:00:00', NULL, 'presente', NULL, NULL, NULL),
(38, 10, '2025-11-04', 'Martes', '06:00:00', '16:00:00', NULL, 'presente', NULL, NULL, NULL),
(39, 11, '2025-11-04', 'Martes', '06:00:00', '16:00:00', NULL, 'presente', NULL, NULL, NULL),
(40, 12, '2025-11-04', 'Martes', '06:00:00', '18:00:00', NULL, 'presente', NULL, NULL, NULL),
(41, 13, '2025-11-04', 'Martes', '06:00:00', '16:00:00', NULL, 'presente', NULL, NULL, NULL),
(42, 14, '2025-11-04', 'Martes', '06:00:00', '16:00:00', NULL, 'presente', NULL, NULL, NULL),
(43, 15, '2025-11-04', 'Martes', '06:00:00', '18:00:00', NULL, 'presente', NULL, NULL, NULL),
(44, 16, '2025-11-04', 'Martes', '06:00:00', '16:00:00', NULL, 'presente', NULL, NULL, NULL),
(45, 6, '2025-11-05', 'Mi├®rcoles', '06:00:00', '16:00:00', NULL, 'presente', NULL, NULL, NULL),
(46, 7, '2025-11-05', 'Mi├®rcoles', '06:00:00', '18:00:00', NULL, 'presente', NULL, NULL, NULL),
(47, 8, '2025-11-05', 'Mi├®rcoles', '06:00:00', '16:00:00', NULL, 'presente', NULL, NULL, NULL),
(48, 9, '2025-11-05', 'Mi├®rcoles', '06:00:00', '18:00:00', NULL, 'presente', NULL, NULL, NULL),
(49, 10, '2025-11-05', 'Mi├®rcoles', '06:00:00', '16:00:00', NULL, 'presente', NULL, NULL, NULL),
(50, 11, '2025-11-05', 'Mi├®rcoles', '06:00:00', '16:00:00', NULL, 'presente', NULL, NULL, NULL),
(51, 12, '2025-11-05', 'Mi├®rcoles', '06:00:00', '18:00:00', NULL, 'presente', NULL, NULL, NULL),
(52, 13, '2025-11-05', 'Mi├®rcoles', '06:15:00', '18:00:00', NULL, 'tardanza', NULL, NULL, NULL),
(53, 14, '2025-11-05', 'Mi├®rcoles', '06:00:00', '18:00:00', NULL, 'presente', NULL, NULL, NULL),
(54, 15, '2025-11-05', 'Mi├®rcoles', '06:00:00', '16:00:00', NULL, 'presente', NULL, NULL, NULL),
(55, 16, '2025-11-05', 'Mi├®rcoles', '06:00:00', '18:00:00', NULL, 'presente', NULL, NULL, NULL),
(56, 6, '2025-11-06', 'Jueves', '06:00:00', '18:00:00', NULL, 'presente', NULL, NULL, NULL),
(57, 7, '2025-11-06', 'Jueves', '06:00:00', '16:00:00', NULL, 'presente', NULL, NULL, NULL),
(58, 8, '2025-11-06', 'Jueves', '06:00:00', '18:00:00', NULL, 'presente', NULL, NULL, NULL),
(59, 9, '2025-11-06', 'Jueves', '06:00:00', '16:00:00', NULL, 'presente', NULL, NULL, NULL),
(60, 10, '2025-11-06', 'Jueves', '06:00:00', '16:00:00', NULL, 'presente', NULL, NULL, NULL),
(61, 11, '2025-11-06', 'Jueves', '06:00:00', '18:00:00', NULL, 'presente', NULL, NULL, NULL),
(62, 12, '2025-11-06', 'Jueves', '06:00:00', '16:00:00', NULL, 'presente', NULL, NULL, NULL),
(63, 13, '2025-11-06', 'Jueves', '06:00:00', '16:00:00', NULL, 'presente', NULL, NULL, NULL),
(64, 14, '2025-11-06', 'Jueves', '06:00:00', '18:00:00', NULL, 'presente', NULL, NULL, NULL),
(65, 15, '2025-11-06', 'Jueves', '06:00:00', '16:00:00', NULL, 'presente', NULL, NULL, NULL),
(66, 16, '2025-11-06', 'Jueves', '06:00:00', '18:00:00', NULL, 'presente', NULL, NULL, NULL),
(67, 6, '2025-11-07', 'Viernes', '06:00:00', '16:00:00', NULL, 'presente', NULL, NULL, NULL),
(68, 7, '2025-11-07', 'Viernes', '06:00:00', '18:00:00', NULL, 'presente', NULL, NULL, NULL),
(69, 8, '2025-11-07', 'Viernes', '06:00:00', '16:00:00', NULL, 'presente', NULL, NULL, NULL),
(70, 9, '2025-11-07', 'Viernes', '06:00:00', '16:00:00', NULL, 'presente', NULL, NULL, NULL),
(71, 10, '2025-11-07', 'Viernes', '06:00:00', '18:00:00', NULL, 'presente', NULL, NULL, NULL),
(72, 11, '2025-11-07', 'Viernes', '06:00:00', '16:00:00', NULL, 'presente', NULL, NULL, NULL),
(73, 12, '2025-11-07', 'Viernes', '06:00:00', '18:00:00', NULL, 'presente', NULL, NULL, NULL),
(74, 13, '2025-11-07', 'Viernes', '06:00:00', '16:00:00', NULL, 'presente', NULL, NULL, NULL),
(75, 14, '2025-11-07', 'Viernes', '06:00:00', '16:00:00', NULL, 'presente', NULL, NULL, NULL),
(76, 15, '2025-11-07', 'Viernes', '06:00:00', '18:00:00', NULL, 'presente', NULL, NULL, NULL),
(77, 16, '2025-11-07', 'Viernes', '06:00:00', '16:00:00', NULL, 'presente', NULL, NULL, NULL),
(78, 6, '2025-11-08', 'S├íbado', '06:00:00', '14:00:00', NULL, 'presente', NULL, NULL, NULL),
(79, 7, '2025-11-08', 'S├íbado', '06:00:00', '13:00:00', NULL, 'presente', NULL, NULL, NULL),
(80, 8, '2025-11-08', 'S├íbado', '06:00:00', '13:30:00', NULL, 'presente', NULL, NULL, NULL),
(81, 9, '2025-11-08', 'S├íbado', '06:00:00', '14:00:00', NULL, 'presente', NULL, NULL, NULL),
(82, 10, '2025-11-08', 'S├íbado', '06:00:00', '13:00:00', NULL, 'presente', NULL, NULL, NULL),
(83, 11, '2025-11-08', 'S├íbado', '06:00:00', '13:00:00', NULL, 'presente', NULL, NULL, NULL),
(84, 12, '2025-11-08', 'S├íbado', '06:00:00', '14:00:00', NULL, 'presente', NULL, NULL, NULL),
(85, 13, '2025-11-08', 'S├íbado', '06:00:00', '13:30:00', NULL, 'presente', NULL, NULL, NULL),
(86, 14, '2025-11-08', 'S├íbado', '06:00:00', '13:00:00', NULL, 'presente', NULL, NULL, NULL),
(87, 15, '2025-11-08', 'S├íbado', '06:00:00', '14:00:00', NULL, 'presente', NULL, NULL, NULL),
(88, 16, '2025-11-08', 'S├íbado', '06:00:00', '13:00:00', NULL, 'presente', NULL, NULL, NULL),
(89, 6, '2025-11-09', 'Domingo', '06:00:00', NULL, NULL, 'ausente', NULL, NULL, NULL),
(90, 7, '2025-11-09', 'Domingo', '06:00:00', NULL, NULL, 'ausente', NULL, NULL, NULL),
(91, 8, '2025-11-09', 'Domingo', '06:00:00', NULL, NULL, 'ausente', NULL, NULL, NULL),
(92, 9, '2025-11-09', 'Domingo', '06:00:00', NULL, NULL, 'ausente', NULL, NULL, NULL),
(93, 10, '2025-11-09', 'Domingo', '06:00:00', NULL, NULL, 'ausente', NULL, NULL, NULL),
(94, 11, '2025-11-09', 'Domingo', '06:00:00', NULL, NULL, 'ausente', NULL, NULL, NULL),
(95, 12, '2025-11-09', 'Domingo', '06:00:00', NULL, NULL, 'ausente', NULL, NULL, NULL),
(96, 13, '2025-11-09', 'Domingo', '06:00:00', NULL, NULL, 'ausente', NULL, NULL, NULL),
(97, 14, '2025-11-09', 'Domingo', '06:00:00', NULL, NULL, 'ausente', NULL, NULL, NULL),
(98, 15, '2025-11-09', 'Domingo', '06:00:00', NULL, NULL, 'ausente', NULL, NULL, NULL),
(99, 16, '2025-11-09', 'Domingo', '06:00:00', NULL, NULL, 'ausente', NULL, NULL, NULL),
(100, 6, '2025-11-10', 'Lunes', '06:00:00', '18:00:00', NULL, 'presente', NULL, NULL, NULL),
(101, 7, '2025-11-10', 'Lunes', '06:00:00', '16:00:00', NULL, 'presente', NULL, NULL, NULL),
(102, 8, '2025-11-10', 'Lunes', '06:00:00', '18:00:00', NULL, 'presente', NULL, NULL, NULL),
(103, 9, '2025-11-10', 'Lunes', '06:00:00', '16:00:00', NULL, 'presente', NULL, NULL, NULL),
(104, 10, '2025-11-10', 'Lunes', '06:00:00', '16:00:00', NULL, 'presente', NULL, NULL, NULL),
(105, 11, '2025-11-10', 'Lunes', '06:00:00', '18:00:00', NULL, 'presente', NULL, NULL, NULL),
(106, 12, '2025-11-10', 'Lunes', '06:00:00', '16:00:00', NULL, 'presente', NULL, NULL, NULL),
(107, 13, '2025-11-10', 'Lunes', '06:00:00', '18:00:00', NULL, 'presente', NULL, NULL, NULL),
(108, 14, '2025-11-10', 'Lunes', '06:00:00', '16:00:00', NULL, 'presente', NULL, NULL, NULL),
(109, 15, '2025-11-10', 'Lunes', '06:00:00', '16:00:00', NULL, 'presente', NULL, NULL, NULL),
(110, 16, '2025-11-10', 'Lunes', '06:00:00', '18:00:00', NULL, 'presente', NULL, NULL, NULL),
(111, 6, '2025-11-11', 'Martes', '06:00:00', '16:00:00', NULL, 'presente', NULL, NULL, NULL),
(112, 7, '2025-11-11', 'Martes', '06:00:00', '18:00:00', NULL, 'presente', NULL, NULL, NULL),
(113, 8, '2025-11-11', 'Martes', '06:00:00', '16:00:00', NULL, 'presente', NULL, NULL, NULL),
(114, 9, '2025-11-11', 'Martes', '06:00:00', '18:00:00', NULL, 'presente', NULL, NULL, NULL),
(115, 10, '2025-11-11', 'Martes', '06:00:00', '16:00:00', NULL, 'presente', NULL, NULL, NULL),
(116, 11, '2025-11-11', 'Martes', '06:00:00', '16:00:00', NULL, 'presente', NULL, NULL, NULL),
(117, 12, '2025-11-11', 'Martes', '06:00:00', '18:00:00', NULL, 'presente', NULL, NULL, NULL),
(118, 13, '2025-11-11', 'Martes', '06:00:00', '16:00:00', NULL, 'presente', NULL, NULL, NULL),
(119, 14, '2025-11-11', 'Martes', '06:00:00', '16:00:00', NULL, 'presente', NULL, NULL, NULL),
(120, 15, '2025-11-11', 'Martes', '06:00:00', '18:00:00', NULL, 'presente', NULL, NULL, NULL),
(121, 16, '2025-11-11', 'Martes', '06:20:00', '18:00:00', NULL, 'tardanza', NULL, NULL, NULL),
(122, 6, '2025-11-12', 'Mi├®rcoles', '06:00:00', '18:00:00', NULL, 'presente', NULL, NULL, NULL),
(123, 7, '2025-11-12', 'Mi├®rcoles', '06:00:00', '16:00:00', NULL, 'presente', NULL, NULL, NULL),
(124, 8, '2025-11-12', 'Mi├®rcoles', '06:00:00', '18:00:00', NULL, 'presente', NULL, NULL, NULL),
(125, 9, '2025-11-12', 'Mi├®rcoles', '06:00:00', '16:00:00', NULL, 'presente', NULL, NULL, NULL),
(126, 10, '2025-11-12', 'Mi├®rcoles', '06:00:00', '16:00:00', NULL, 'presente', NULL, NULL, NULL),
(127, 11, '2025-11-12', 'Mi├®rcoles', '06:00:00', '18:00:00', NULL, 'presente', NULL, NULL, NULL),
(128, 12, '2025-11-12', 'Mi├®rcoles', '06:00:00', '16:00:00', NULL, 'presente', NULL, NULL, NULL),
(129, 13, '2025-11-12', 'Mi├®rcoles', '06:00:00', '16:00:00', NULL, 'presente', NULL, NULL, NULL),
(130, 14, '2025-11-12', 'Mi├®rcoles', '06:00:00', '18:00:00', NULL, 'presente', NULL, NULL, NULL),
(131, 15, '2025-11-12', 'Mi├®rcoles', '06:00:00', '16:00:00', NULL, 'presente', NULL, NULL, NULL),
(132, 16, '2025-11-12', 'Mi├®rcoles', '06:00:00', '18:00:00', NULL, 'presente', NULL, NULL, NULL),
(133, 6, '2025-11-13', 'Jueves', '06:00:00', '16:00:00', NULL, 'presente', NULL, NULL, NULL),
(134, 7, '2025-11-13', 'Jueves', '06:00:00', '18:00:00', NULL, 'presente', NULL, NULL, NULL),
(135, 8, '2025-11-13', 'Jueves', '06:00:00', '16:00:00', NULL, 'presente', NULL, NULL, NULL),
(136, 9, '2025-11-13', 'Jueves', '06:00:00', '16:00:00', NULL, 'presente', NULL, NULL, NULL),
(137, 10, '2025-11-13', 'Jueves', '06:00:00', '18:00:00', NULL, 'presente', NULL, NULL, NULL),
(138, 11, '2025-11-13', 'Jueves', '06:00:00', '16:00:00', NULL, 'presente', NULL, NULL, NULL),
(139, 12, '2025-11-13', 'Jueves', '06:00:00', '18:00:00', NULL, 'presente', NULL, NULL, NULL),
(140, 13, '2025-11-13', 'Jueves', '06:00:00', '16:00:00', NULL, 'presente', NULL, NULL, NULL),
(141, 14, '2025-11-13', 'Jueves', '06:00:00', '16:00:00', NULL, 'presente', NULL, NULL, NULL),
(142, 15, '2025-11-13', 'Jueves', '06:00:00', '18:00:00', NULL, 'presente', NULL, NULL, NULL),
(143, 16, '2025-11-13', 'Jueves', '06:00:00', '16:00:00', NULL, 'presente', NULL, NULL, NULL),
(144, 6, '2025-11-14', 'Viernes', '06:00:00', '18:00:00', NULL, 'presente', NULL, NULL, NULL),
(145, 7, '2025-11-14', 'Viernes', '06:00:00', '16:00:00', NULL, 'presente', NULL, NULL, NULL),
(146, 8, '2025-11-14', 'Viernes', '06:00:00', '18:00:00', NULL, 'presente', NULL, NULL, NULL),
(147, 9, '2025-11-14', 'Viernes', '06:00:00', '16:00:00', NULL, 'presente', NULL, NULL, NULL),
(148, 10, '2025-11-14', 'Viernes', '06:00:00', '16:00:00', NULL, 'presente', NULL, NULL, NULL),
(149, 11, '2025-11-14', 'Viernes', '06:00:00', '18:00:00', NULL, 'presente', NULL, NULL, NULL),
(150, 12, '2025-11-14', 'Viernes', '06:00:00', '16:00:00', NULL, 'presente', NULL, NULL, NULL),
(151, 13, '2025-11-14', 'Viernes', '06:00:00', '16:00:00', NULL, 'presente', NULL, NULL, NULL),
(152, 14, '2025-11-14', 'Viernes', '06:00:00', '18:00:00', NULL, 'presente', NULL, NULL, NULL),
(153, 15, '2025-11-14', 'Viernes', '06:00:00', '16:00:00', NULL, 'presente', NULL, NULL, NULL),
(154, 16, '2025-11-14', 'Viernes', '06:00:00', '18:00:00', NULL, 'presente', NULL, NULL, NULL),
(155, 6, '2025-11-15', 'S├íbado', '06:00:00', '13:00:00', NULL, 'presente', NULL, NULL, NULL),
(156, 7, '2025-11-15', 'S├íbado', '06:00:00', '14:00:00', NULL, 'presente', NULL, NULL, NULL),
(157, 8, '2025-11-15', 'S├íbado', '06:00:00', '13:30:00', NULL, 'presente', NULL, NULL, NULL),
(158, 9, '2025-11-15', 'S├íbado', '06:00:00', '13:00:00', NULL, 'presente', NULL, NULL, NULL),
(159, 10, '2025-11-15', 'S├íbado', '06:00:00', '14:00:00', NULL, 'presente', NULL, NULL, NULL),
(160, 11, '2025-11-15', 'S├íbado', '06:00:00', '13:00:00', NULL, 'presente', NULL, NULL, NULL),
(161, 12, '2025-11-15', 'S├íbado', '06:00:00', '13:30:00', NULL, 'presente', NULL, NULL, NULL),
(162, 13, '2025-11-15', 'S├íbado', '06:00:00', '14:00:00', NULL, 'presente', NULL, NULL, NULL),
(163, 14, '2025-11-15', 'S├íbado', '06:00:00', '13:00:00', NULL, 'presente', NULL, NULL, NULL),
(164, 15, '2025-11-15', 'S├íbado', '06:00:00', '13:00:00', NULL, 'presente', NULL, NULL, NULL),
(165, 16, '2025-11-15', 'S├íbado', '06:00:00', '14:00:00', NULL, 'presente', NULL, NULL, NULL),
(166, 6, '2025-11-16', 'Domingo', '06:00:00', NULL, NULL, 'ausente', NULL, NULL, NULL),
(167, 7, '2025-11-16', 'Domingo', '06:00:00', NULL, NULL, 'ausente', NULL, NULL, NULL),
(168, 8, '2025-11-16', 'Domingo', '06:00:00', NULL, NULL, 'ausente', NULL, NULL, NULL),
(169, 9, '2025-11-16', 'Domingo', '06:00:00', NULL, NULL, 'ausente', NULL, NULL, NULL),
(170, 10, '2025-11-16', 'Domingo', '06:00:00', NULL, NULL, 'ausente', NULL, NULL, NULL),
(171, 11, '2025-11-16', 'Domingo', '06:00:00', NULL, NULL, 'ausente', NULL, NULL, NULL),
(172, 12, '2025-11-16', 'Domingo', '06:00:00', NULL, NULL, 'ausente', NULL, NULL, NULL),
(173, 13, '2025-11-16', 'Domingo', '06:00:00', NULL, NULL, 'ausente', NULL, NULL, NULL),
(174, 14, '2025-11-16', 'Domingo', '06:00:00', NULL, NULL, 'ausente', NULL, NULL, NULL),
(175, 15, '2025-11-16', 'Domingo', '06:00:00', NULL, NULL, 'ausente', NULL, NULL, NULL),
(176, 16, '2025-11-16', 'Domingo', '06:00:00', NULL, NULL, 'ausente', NULL, NULL, NULL),
(177, 6, '2025-11-17', 'Lunes', '06:00:00', '16:00:00', NULL, 'presente', NULL, NULL, NULL),
(178, 7, '2025-11-17', 'Lunes', '06:00:00', '18:00:00', NULL, 'presente', NULL, NULL, NULL),
(179, 8, '2025-11-17', 'Lunes', '06:00:00', '16:00:00', NULL, 'presente', NULL, NULL, NULL),
(180, 9, '2025-11-17', 'Lunes', '06:00:00', '16:00:00', NULL, 'presente', NULL, NULL, NULL),
(181, 10, '2025-11-17', 'Lunes', '06:00:00', '18:00:00', NULL, 'presente', NULL, NULL, NULL),
(182, 11, '2025-11-17', 'Lunes', '06:00:00', '16:00:00', NULL, 'presente', NULL, NULL, NULL),
(183, 12, '2025-11-17', 'Lunes', '06:00:00', '18:00:00', NULL, 'presente', NULL, NULL, NULL),
(184, 13, '2025-11-17', 'Lunes', '06:00:00', '16:00:00', NULL, 'presente', NULL, NULL, NULL),
(185, 14, '2025-11-17', 'Lunes', '06:00:00', '16:00:00', NULL, 'presente', NULL, NULL, NULL),
(186, 15, '2025-11-17', 'Lunes', '06:00:00', '18:00:00', NULL, 'presente', NULL, NULL, NULL),
(187, 16, '2025-11-17', 'Lunes', '06:00:00', '16:00:00', NULL, 'presente', NULL, NULL, NULL),
(188, 6, '2025-11-18', 'Martes', '06:00:00', '18:00:00', NULL, 'presente', NULL, NULL, NULL),
(189, 7, '2025-11-18', 'Martes', '06:00:00', '16:00:00', NULL, 'presente', NULL, NULL, NULL),
(190, 8, '2025-11-18', 'Martes', '06:00:00', '18:00:00', NULL, 'presente', NULL, NULL, NULL),
(191, 9, '2025-11-18', 'Martes', '06:00:00', '16:00:00', NULL, 'presente', NULL, NULL, NULL),
(192, 10, '2025-11-18', 'Martes', '06:00:00', '16:00:00', NULL, 'presente', NULL, NULL, NULL),
(193, 11, '2025-11-18', 'Martes', '06:00:00', '18:00:00', NULL, 'presente', NULL, NULL, NULL),
(194, 12, '2025-11-18', 'Martes', '06:00:00', '16:00:00', NULL, 'presente', NULL, NULL, NULL),
(195, 13, '2025-11-18', 'Martes', '06:00:00', '16:00:00', NULL, 'presente', NULL, NULL, NULL),
(196, 14, '2025-11-18', 'Martes', '06:00:00', '18:00:00', NULL, 'presente', NULL, NULL, NULL),
(197, 15, '2025-11-18', 'Martes', '06:00:00', '16:00:00', NULL, 'presente', NULL, NULL, NULL),
(198, 16, '2025-11-18', 'Martes', '06:00:00', '18:00:00', NULL, 'presente', NULL, NULL, NULL),
(199, 6, '2025-11-19', 'Mi├®rcoles', '06:00:00', '16:00:00', NULL, 'presente', NULL, NULL, NULL),
(200, 7, '2025-11-19', 'Mi├®rcoles', '06:00:00', '18:00:00', NULL, 'presente', NULL, NULL, NULL),
(201, 8, '2025-11-19', 'Mi├®rcoles', '06:00:00', '16:00:00', NULL, 'presente', NULL, NULL, NULL),
(202, 9, '2025-11-19', 'Mi├®rcoles', '06:00:00', '18:00:00', NULL, 'presente', NULL, NULL, NULL),
(203, 10, '2025-11-19', 'Mi├®rcoles', '06:00:00', '16:00:00', NULL, 'presente', NULL, NULL, NULL),
(204, 11, '2025-11-19', 'Mi├®rcoles', '06:00:00', '16:00:00', NULL, 'presente', NULL, NULL, NULL),
(205, 12, '2025-11-19', 'Mi├®rcoles', '06:00:00', '18:00:00', NULL, 'presente', NULL, NULL, NULL),
(206, 13, '2025-11-19', 'Mi├®rcoles', '06:00:00', '16:00:00', NULL, 'presente', NULL, NULL, NULL),
(207, 14, '2025-11-19', 'Mi├®rcoles', '06:00:00', '16:00:00', NULL, 'presente', NULL, NULL, NULL),
(208, 15, '2025-11-19', 'Mi├®rcoles', '06:00:00', '18:00:00', NULL, 'presente', NULL, NULL, NULL),
(209, 16, '2025-11-19', 'Mi├®rcoles', '06:00:00', '16:00:00', NULL, 'presente', NULL, NULL, NULL),
(210, 6, '2025-11-20', 'Jueves', '06:00:00', '18:00:00', NULL, 'presente', NULL, NULL, NULL),
(211, 7, '2025-11-20', 'Jueves', '06:00:00', '16:00:00', NULL, 'presente', NULL, NULL, NULL),
(212, 8, '2025-11-20', 'Jueves', '06:00:00', '18:00:00', NULL, 'presente', NULL, NULL, NULL),
(213, 9, '2025-11-20', 'Jueves', '06:00:00', '16:00:00', NULL, 'presente', NULL, NULL, NULL),
(214, 10, '2025-11-20', 'Jueves', '06:00:00', '16:00:00', NULL, 'presente', NULL, NULL, NULL),
(215, 11, '2025-11-20', 'Jueves', '06:00:00', '18:00:00', NULL, 'presente', NULL, NULL, NULL),
(216, 12, '2025-11-20', 'Jueves', '06:00:00', '16:00:00', NULL, 'presente', NULL, NULL, NULL),
(217, 13, '2025-11-20', 'Jueves', '06:00:00', '16:00:00', NULL, 'presente', NULL, NULL, NULL),
(218, 14, '2025-11-20', 'Jueves', '06:00:00', '18:00:00', NULL, 'presente', NULL, NULL, NULL),
(219, 15, '2025-11-20', 'Jueves', '06:00:00', '16:00:00', NULL, 'presente', NULL, NULL, NULL),
(220, 16, '2025-11-20', 'Jueves', '06:00:00', '18:00:00', NULL, 'presente', NULL, NULL, NULL),
(221, 6, '2025-11-21', 'Viernes', '06:00:00', '16:00:00', NULL, 'presente', NULL, NULL, NULL),
(222, 7, '2025-11-21', 'Viernes', '06:00:00', '18:00:00', NULL, 'presente', NULL, NULL, NULL),
(223, 8, '2025-11-21', 'Viernes', '06:00:00', '16:00:00', NULL, 'presente', NULL, NULL, NULL),
(224, 9, '2025-11-21', 'Viernes', '06:00:00', '16:00:00', NULL, 'presente', NULL, NULL, NULL),
(225, 10, '2025-11-21', 'Viernes', '06:00:00', '18:00:00', NULL, 'presente', NULL, NULL, NULL),
(226, 11, '2025-11-21', 'Viernes', '06:00:00', '16:00:00', NULL, 'presente', NULL, NULL, NULL),
(227, 12, '2025-11-21', 'Viernes', '06:00:00', '18:00:00', NULL, 'presente', NULL, NULL, NULL),
(228, 13, '2025-11-21', 'Viernes', '06:00:00', '16:00:00', NULL, 'presente', NULL, NULL, NULL),
(229, 14, '2025-11-21', 'Viernes', '06:00:00', '16:00:00', NULL, 'presente', NULL, NULL, NULL),
(230, 15, '2025-11-21', 'Viernes', '06:00:00', '18:00:00', NULL, 'presente', NULL, NULL, NULL),
(231, 16, '2025-11-21', 'Viernes', '06:00:00', '16:00:00', NULL, 'presente', NULL, NULL, NULL),
(232, 6, '2025-11-22', 'S├íbado', '06:00:00', '14:00:00', NULL, 'presente', NULL, NULL, NULL),
(233, 7, '2025-11-22', 'S├íbado', '06:00:00', '13:00:00', NULL, 'presente', NULL, NULL, NULL),
(234, 8, '2025-11-22', 'S├íbado', '06:00:00', '13:30:00', NULL, 'presente', NULL, NULL, NULL),
(235, 9, '2025-11-22', 'S├íbado', '06:00:00', '14:00:00', NULL, 'presente', NULL, NULL, NULL),
(236, 10, '2025-11-22', 'S├íbado', '06:00:00', '13:00:00', NULL, 'presente', NULL, NULL, NULL),
(237, 11, '2025-11-22', 'S├íbado', '06:00:00', '13:00:00', NULL, 'presente', NULL, NULL, NULL),
(238, 12, '2025-11-22', 'S├íbado', '06:00:00', '14:00:00', NULL, 'presente', NULL, NULL, NULL),
(239, 13, '2025-11-22', 'S├íbado', '06:00:00', '13:30:00', NULL, 'presente', NULL, NULL, NULL),
(240, 14, '2025-11-22', 'S├íbado', '06:00:00', '13:00:00', NULL, 'presente', NULL, NULL, NULL),
(241, 15, '2025-11-22', 'S├íbado', '06:00:00', '14:00:00', NULL, 'presente', NULL, NULL, NULL),
(242, 16, '2025-11-22', 'S├íbado', '06:00:00', '13:00:00', NULL, 'presente', NULL, NULL, NULL),
(243, 6, '2025-11-23', 'Domingo', '06:00:00', NULL, NULL, 'ausente', NULL, NULL, NULL),
(244, 7, '2025-11-23', 'Domingo', '06:00:00', NULL, NULL, 'ausente', NULL, NULL, NULL),
(245, 8, '2025-11-23', 'Domingo', '06:00:00', NULL, NULL, 'ausente', NULL, NULL, NULL),
(246, 9, '2025-11-23', 'Domingo', '06:00:00', NULL, NULL, 'ausente', NULL, NULL, NULL),
(247, 10, '2025-11-23', 'Domingo', '06:00:00', NULL, NULL, 'ausente', NULL, NULL, NULL),
(248, 11, '2025-11-23', 'Domingo', '06:00:00', NULL, NULL, 'ausente', NULL, NULL, NULL),
(249, 12, '2025-11-23', 'Domingo', '06:00:00', NULL, NULL, 'ausente', NULL, NULL, NULL),
(250, 13, '2025-11-23', 'Domingo', '06:00:00', NULL, NULL, 'ausente', NULL, NULL, NULL),
(251, 14, '2025-11-23', 'Domingo', '06:00:00', NULL, NULL, 'ausente', NULL, NULL, NULL),
(252, 15, '2025-11-23', 'Domingo', '06:00:00', NULL, NULL, 'ausente', NULL, NULL, NULL),
(253, 16, '2025-11-23', 'Domingo', '06:00:00', NULL, NULL, 'ausente', NULL, NULL, NULL),
(254, 6, '2025-11-24', 'Lunes', '06:00:00', '18:00:00', NULL, 'presente', NULL, NULL, NULL),
(255, 7, '2025-11-24', 'Lunes', '06:00:00', '16:00:00', NULL, 'presente', NULL, NULL, NULL),
(256, 8, '2025-11-24', 'Lunes', '06:00:00', '18:00:00', NULL, 'presente', NULL, NULL, NULL),
(257, 9, '2025-11-24', 'Lunes', '06:00:00', '16:00:00', NULL, 'presente', NULL, NULL, NULL),
(258, 10, '2025-11-24', 'Lunes', '06:00:00', '16:00:00', NULL, 'presente', NULL, NULL, NULL),
(259, 11, '2025-11-24', 'Lunes', '06:00:00', '18:00:00', NULL, 'presente', NULL, NULL, NULL),
(260, 12, '2025-11-24', 'Lunes', '06:00:00', '16:00:00', NULL, 'presente', NULL, NULL, NULL),
(261, 13, '2025-11-24', 'Lunes', '06:00:00', '18:00:00', NULL, 'presente', NULL, NULL, NULL),
(262, 14, '2025-11-24', 'Lunes', '06:00:00', '16:00:00', NULL, 'presente', NULL, NULL, NULL),
(263, 15, '2025-11-24', 'Lunes', '06:00:00', '16:00:00', NULL, 'presente', NULL, NULL, NULL),
(264, 16, '2025-11-24', 'Lunes', '06:00:00', '18:00:00', NULL, 'presente', NULL, NULL, NULL),
(265, 6, '2025-11-25', 'Martes', '06:00:00', '16:00:00', NULL, 'presente', NULL, NULL, NULL),
(266, 7, '2025-11-25', 'Martes', '06:00:00', '18:00:00', NULL, 'presente', NULL, NULL, NULL),
(267, 8, '2025-11-25', 'Martes', '06:00:00', '16:00:00', NULL, 'presente', NULL, NULL, NULL),
(268, 9, '2025-11-25', 'Martes', '06:00:00', '18:00:00', NULL, 'presente', NULL, NULL, NULL),
(269, 10, '2025-11-25', 'Martes', '06:00:00', '16:00:00', NULL, 'presente', NULL, NULL, NULL),
(270, 11, '2025-11-25', 'Martes', '06:00:00', '16:00:00', NULL, 'presente', NULL, NULL, NULL),
(271, 12, '2025-11-25', 'Martes', '06:00:00', '18:00:00', NULL, 'presente', NULL, NULL, NULL),
(272, 13, '2025-11-25', 'Martes', '06:00:00', '16:00:00', NULL, 'presente', NULL, NULL, NULL),
(273, 14, '2025-11-25', 'Martes', '06:00:00', '16:00:00', NULL, 'presente', NULL, NULL, NULL),
(274, 15, '2025-11-25', 'Martes', '06:00:00', '18:00:00', NULL, 'presente', NULL, NULL, NULL),
(275, 16, '2025-11-25', 'Martes', '06:00:00', '16:00:00', NULL, 'presente', NULL, NULL, NULL),
(277, 12, '2025-12-01', 'Lunes', '00:18:00', '00:19:00', 'Entrada registrada automáticamente', 'presente', NULL, '2025-12-01 04:18:55', '2025-12-01 04:19:06'),
(278, 12, '2025-12-01', 'Lunes', '00:19:00', NULL, NULL, 'tardanza', NULL, '2025-12-01 04:19:58', '2025-12-01 04:19:58');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `control_fosa_septica`
--

CREATE TABLE `control_fosa_septica` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `fecha_limpieza` date NOT NULL,
  `id_responsable` bigint(20) UNSIGNED DEFAULT NULL,
  `tipo_fosa` varchar(255) DEFAULT NULL,
  `responsable_texto` varchar(255) NOT NULL,
  `detalle_trabajo` text DEFAULT NULL,
  `empresa_contratada` varchar(255) DEFAULT NULL,
  `proxima_limpieza` date DEFAULT NULL,
  `observaciones` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `control_fosa_septica`
--

INSERT INTO `control_fosa_septica` (`id`, `fecha_limpieza`, `id_responsable`, `tipo_fosa`, `responsable_texto`, `detalle_trabajo`, `empresa_contratada`, `proxima_limpieza`, `observaciones`, `created_at`, `updated_at`) VALUES
(1, '2025-11-27', NULL, 'fosa principal cerrada', 'Lucia Cruz Farfan', 'Limpieza y Retiro', 'Servicio Master Bolivia SRL', '2026-04-27', NULL, '2025-11-27 06:54:33', '2025-11-27 06:54:33');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `control_fumigacion`
--

CREATE TABLE `control_fumigacion` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `fecha_fumigacion` date NOT NULL,
  `area_fumigada` varchar(255) NOT NULL,
  `producto_utilizado` varchar(255) NOT NULL,
  `cantidad_producto` decimal(10,2) NOT NULL,
  `id_responsable` bigint(20) UNSIGNED DEFAULT NULL,
  `responsable_texto` varchar(255) NOT NULL,
  `empresa_contratada` varchar(255) DEFAULT NULL,
  `proxima_fumigacion` date DEFAULT NULL,
  `observaciones` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `control_fumigacion`
--

INSERT INTO `control_fumigacion` (`id`, `fecha_fumigacion`, `area_fumigada`, `producto_utilizado`, `cantidad_producto`, `id_responsable`, `responsable_texto`, `empresa_contratada`, `proxima_fumigacion`, `observaciones`, `created_at`, `updated_at`) VALUES
(1, '2025-11-27', 'PLanta general', 'sipertrin y Icon 5 ec', 55.00, NULL, 'Lucia Cruz Farfan', 'Power mip', '2025-12-26', NULL, '2025-11-27 06:59:47', '2025-11-27 06:59:47');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `control_insumos`
--

CREATE TABLE `control_insumos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `fecha` date NOT NULL,
  `producto_insumo` varchar(255) DEFAULT NULL,
  `cantidad` decimal(10,2) NOT NULL,
  `unidad_medida` varchar(255) NOT NULL,
  `numero_lote` varchar(100) DEFAULT NULL,
  `fecha_vencimiento` date DEFAULT NULL,
  `id_responsable` bigint(20) UNSIGNED DEFAULT NULL,
  `responsable_texto` varchar(255) DEFAULT NULL,
  `stock_actual` decimal(10,2) NOT NULL DEFAULT 0.00,
  `stock_minimo` decimal(10,2) NOT NULL DEFAULT 0.00,
  `proveedor` varchar(255) DEFAULT NULL,
  `observaciones` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `control_insumos`
--

INSERT INTO `control_insumos` (`id`, `fecha`, `producto_insumo`, `cantidad`, `unidad_medida`, `numero_lote`, `fecha_vencimiento`, `id_responsable`, `responsable_texto`, `stock_actual`, `stock_minimo`, `proveedor`, `observaciones`, `created_at`, `updated_at`) VALUES
(1, '2025-11-27', 'Besuato de sodio', 3.00, 'kg', '1c5422', '2027-07-26', NULL, 'Lucia Cruz Farfan', 3.00, 0.00, 'Maprial', NULL, '2025-11-27 06:47:14', '2025-11-27 06:47:14');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `control_mantenimiento_equipos`
--

CREATE TABLE `control_mantenimiento_equipos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `fecha` date NOT NULL,
  `id_personal` bigint(20) UNSIGNED DEFAULT NULL,
  `equipo` varchar(255) NOT NULL,
  `detalle_mantenimiento` text NOT NULL,
  `productos_limpieza` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`productos_limpieza`)),
  `proxima_fecha` date DEFAULT NULL,
  `realizado_por` varchar(255) NOT NULL,
  `supervisado_por` varchar(255) DEFAULT NULL,
  `observaciones` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `control_mantenimiento_equipos`
--

INSERT INTO `control_mantenimiento_equipos` (`id`, `fecha`, `id_personal`, `equipo`, `detalle_mantenimiento`, `productos_limpieza`, `proxima_fecha`, `realizado_por`, `supervisado_por`, `observaciones`, `created_at`, `updated_at`) VALUES
(1, '2025-11-30', 11, '[\"M\\u00e1quina de Lim\\u00f3n y Sabor\",\"M\\u00e1quina de Bolos\"]', 'Equipos: Máquina de Limón y Sabor, Máquina de Bolos | Productos: Alcohol al 70%, Desengrasante', '[\"Alcohol al 70%\",\"Desengrasante\"]', '2025-12-06', 'Anderson  Aguilar', 'Lucia Cruz Farfan', NULL, '2025-12-01 02:42:07', '2025-12-01 02:42:07');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `control_produccion_diaria`
--

CREATE TABLE `control_produccion_diaria` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `fecha` date NOT NULL,
  `responsable` varchar(255) DEFAULT NULL,
  `turno` varchar(255) DEFAULT NULL,
  `preparacion` varchar(255) DEFAULT NULL,
  `rollos_material` int(11) NOT NULL DEFAULT 0,
  `gasto_material` decimal(10,2) NOT NULL DEFAULT 0.00,
  `observaciones` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `control_produccion_diaria`
--

INSERT INTO `control_produccion_diaria` (`id`, `fecha`, `responsable`, `turno`, `preparacion`, `rollos_material`, `gasto_material`, `observaciones`, `created_at`, `updated_at`) VALUES
(37, '2025-11-01', 'Lidia canon', 'Diurno', 'Normal', 0, 0.00, NULL, '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(38, '2025-11-03', 'Ana Guitierrez', 'Diurno', 'Normal', 0, 0.00, NULL, '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(39, '2025-11-04', 'Lidia canon', 'Diurno', 'Normal', 0, 0.00, NULL, '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(40, '2025-11-05', 'Ana Guitierrez', 'Diurno', 'Normal', 0, 0.00, NULL, '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(41, '2025-11-06', 'Anderson Aguilar', 'Diurno', 'Normal', 0, 0.00, NULL, '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(42, '2025-11-07', 'Helen Aguilar', 'Diurno', 'Normal', 0, 0.00, NULL, '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(43, '2025-11-08', 'Ana Guitierrez', 'Diurno', 'Normal', 0, 0.00, NULL, '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(44, '2025-11-10', 'Anderson Aguilar', 'Diurno', 'Normal', 0, 0.00, NULL, '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(45, '2025-11-11', 'Helen Aguilar', 'Diurno', 'Normal', 0, 0.00, NULL, '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(46, '2025-11-12', 'Helen Aguilar', 'Diurno', 'Normal', 0, 0.00, NULL, '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(47, '2025-11-13', 'Lidia canon', 'Diurno', 'Normal', 0, 0.00, NULL, '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(48, '2025-11-14', 'Lidia canon', 'Diurno', 'Normal', 0, 0.00, NULL, '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(49, '2025-11-15', 'Helen Aguilar', 'Diurno', 'Normal', 0, 0.00, NULL, '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(50, '2025-11-17', 'Helen Aguilar', 'Diurno', 'Normal', 0, 0.00, NULL, '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(51, '2025-11-18', 'Lidia canon', 'Diurno', 'Normal', 0, 0.00, NULL, '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(52, '2025-11-19', 'Lidia canon', 'Diurno', 'Normal', 0, 0.00, NULL, '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(53, '2025-11-20', 'Anderson Aguilar', 'Diurno', 'Normal', 0, 0.00, NULL, '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(54, '2025-11-21', 'Helen Aguilar', 'Diurno', 'Normal', 0, 0.00, NULL, '2025-11-22 00:07:21', '2025-11-22 00:37:12'),
(55, '2025-11-22', 'Helen Aguilar', NULL, NULL, 0, 250.00, 'Producci├│n sabatina - jornada reducida', '2025-11-26 02:35:45', '2025-11-26 02:35:45'),
(56, '2025-11-22', 'Helen Aguilar', NULL, NULL, 0, 250.00, 'Producci├│n sabatina - jornada reducida', '2025-11-26 02:36:11', '2025-11-26 02:36:11'),
(57, '2025-11-22', 'Helen Aguilar', NULL, NULL, 0, 250.00, 'Producci├│n sabatina - jornada reducida', '2025-11-26 02:36:45', '2025-11-26 02:36:45'),
(58, '2025-11-22', 'Helen Aguilar', NULL, NULL, 0, 250.00, 'Producción sabat ina', '2025-11-26 06:40:12', '2025-11-26 06:40:12'),
(59, '2025-11-24', 'Anderson  Aguilar', NULL, NULL, 0, 450.00, 'Inicio de semana - producción normal', '2025-11-26 06:42:55', '2025-11-26 06:42:55'),
(60, '2025-11-25', 'Lidia canon', NULL, NULL, 0, 480.00, 'Producción martes - demanda alta', '2025-11-26 06:43:29', '2025-11-26 06:43:29'),
(61, '2025-11-26', 'Helen Aguilar', 'Inventario Inicial', NULL, 0, 0.00, 'Inventario inicial del sistema', '2025-11-26 18:39:43', '2025-11-27 01:30:10'),
(62, '2025-11-27', 'Anderson  Aguilar', NULL, NULL, 0, 0.00, NULL, '2025-11-28 02:17:50', '2025-11-28 02:17:50'),
(63, '2025-11-28', 'Helen Aguilar', NULL, NULL, 0, 0.00, NULL, '2025-11-28 14:47:24', '2025-11-28 14:47:24'),
(66, '2025-12-01', 'Ana Gutierrez - Encargado de Producción', NULL, NULL, 0, 0.00, 'Producción normal - Temporada regular', '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(67, '2025-12-02', 'Anderson Aguilar - Encargado de Producción', NULL, NULL, 0, 0.00, 'Producción normal - Temporada regular', '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(68, '2025-12-03', 'Helen Aguilar - Operador de Producción', NULL, NULL, 0, 0.00, 'Producción normal - Temporada regular', '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(69, '2025-12-04', 'Lidia Canon - Encargado de Producción', NULL, NULL, 0, 0.00, 'Producción normal - Temporada regular', '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(70, '2025-12-05', 'Ana Gutierrez - Encargado de Producción', NULL, NULL, 0, 0.00, 'Producción normal - Temporada regular', '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(71, '2025-12-06', 'Anderson Aguilar - Encargado de Producción', NULL, NULL, 0, 0.00, 'Producción normal - Temporada regular', '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(73, '2025-12-08', 'Lidia Canon - Encargado de Producción', NULL, NULL, 0, 0.00, 'Producción normal - Temporada regular', '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(74, '2025-12-09', 'Ana Gutierrez - Encargado de Producción', NULL, NULL, 0, 0.00, 'Producción normal - Temporada regular', '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(75, '2025-12-10', 'Anderson Aguilar - Encargado de Producción', NULL, NULL, 0, 0.00, 'Producción normal - Temporada regular', '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(76, '2025-12-11', 'Helen Aguilar - Operador de Producción', NULL, NULL, 0, 0.00, 'Producción normal - Temporada regular', '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(77, '2025-12-12', 'Lidia Canon - Encargado de Producción', NULL, NULL, 0, 0.00, 'Producción normal - Temporada regular', '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(78, '2025-12-13', 'Ana Gutierrez - Encargado de Producción', NULL, NULL, 0, 0.00, 'Producción normal - Temporada regular', '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(80, '2025-12-15', 'Helen Aguilar - Operador de Producción', NULL, NULL, 0, 0.00, 'Producción normal - Temporada regular', '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(81, '2025-12-16', 'Lidia Canon - Encargado de Producción', NULL, NULL, 0, 0.00, 'Producción normal - Temporada regular', '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(82, '2025-12-17', 'Ana Gutierrez - Encargado de Producción', NULL, NULL, 0, 0.00, 'Producción normal - Temporada regular', '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(83, '2025-12-18', 'Anderson Aguilar - Encargado de Producción', NULL, NULL, 0, 0.00, 'Producción normal - Temporada regular', '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(84, '2025-12-19', 'Helen Aguilar - Operador de Producción', NULL, NULL, 0, 0.00, 'Producción normal - Temporada regular', '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(85, '2025-12-20', 'Lidia Canon - Encargado de Producción', NULL, NULL, 0, 0.00, 'Producción reducida por temporada de fin de año - Baja demanda', '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(87, '2025-12-22', 'Anderson Aguilar - Encargado de Producción', NULL, NULL, 0, 0.00, 'Producción reducida por temporada de fin de año - Baja demanda', '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(88, '2025-12-23', 'Helen Aguilar - Operador de Producción', NULL, NULL, 0, 0.00, 'Producción reducida por temporada de fin de año - Baja demanda', '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(89, '2025-12-26', 'Lidia Canon - Encargado de Producción', NULL, NULL, 0, 0.00, 'Producción reducida por temporada de fin de año - Baja demanda', '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(90, '2025-12-27', 'Ana Gutierrez - Encargado de Producción', NULL, NULL, 0, 0.00, 'Producción reducida por temporada de fin de año - Baja demanda', '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(92, '2025-12-29', 'Helen Aguilar - Operador de Producción', NULL, NULL, 0, 0.00, 'Producción reducida por temporada de fin de año - Baja demanda', '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(93, '2025-12-30', 'Lidia Canon - Encargado de Producción', NULL, NULL, 0, 0.00, 'Producción reducida por temporada de fin de año - Baja demanda', '2025-12-04 14:40:43', '2025-12-04 14:40:43');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `control_produccion_materiales`
--

CREATE TABLE `control_produccion_materiales` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `produccion_id` bigint(20) UNSIGNED NOT NULL,
  `nombre_material` varchar(255) NOT NULL,
  `cantidad` decimal(10,2) NOT NULL DEFAULT 0.00,
  `unidad_medida` varchar(255) NOT NULL DEFAULT 'kg',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `control_produccion_materiales`
--

INSERT INTO `control_produccion_materiales` (`id`, `produccion_id`, `nombre_material`, `cantidad`, `unidad_medida`, `created_at`, `updated_at`) VALUES
(1, 37, 'Bolsas de empaquete', 38.00, 'kg', '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(2, 37, 'Botellón de 20L', 200.00, 'kg', '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(3, 37, 'Etiquetas para botellones', 200.00, 'kg', '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(4, 38, 'Bolsas de empaquete', 39.00, 'kg', '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(5, 38, 'Botellón de 20L', 200.00, 'kg', '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(6, 38, 'Etiquetas para botellones', 200.00, 'kg', '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(7, 39, 'Bolsas de empaquete', 40.00, 'kg', '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(8, 39, 'Botellón de 20L', 200.00, 'kg', '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(9, 39, 'Etiquetas para botellones', 200.00, 'kg', '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(10, 40, 'Bolsas de empaquete', 37.00, 'kg', '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(11, 40, 'Botellón de 20L', 200.00, 'kg', '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(12, 40, 'Etiquetas para botellones', 200.00, 'kg', '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(13, 41, 'Bolsas de empaquete', 38.00, 'kg', '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(14, 41, 'Botellón de 20L', 200.00, 'kg', '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(15, 41, 'Etiquetas para botellones', 200.00, 'kg', '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(16, 42, 'Bolsas de empaquete', 38.00, 'kg', '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(17, 42, 'Botellón de 20L', 200.00, 'kg', '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(18, 42, 'Etiquetas para botellones', 200.00, 'kg', '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(19, 43, 'Bolsas de empaquete', 36.00, 'kg', '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(20, 43, 'Botellón de 20L', 200.00, 'kg', '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(21, 43, 'Etiquetas para botellones', 200.00, 'kg', '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(22, 44, 'Bolsas de empaquete', 37.00, 'kg', '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(23, 44, 'Botellón de 20L', 200.00, 'kg', '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(24, 44, 'Etiquetas para botellones', 200.00, 'kg', '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(25, 45, 'Bolsas de empaquete', 37.00, 'kg', '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(26, 45, 'Botellón de 20L', 200.00, 'kg', '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(27, 45, 'Etiquetas para botellones', 200.00, 'kg', '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(28, 46, 'Bolsas de empaquete', 37.00, 'kg', '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(29, 46, 'Botellón de 20L', 200.00, 'kg', '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(30, 46, 'Etiquetas para botellones', 200.00, 'kg', '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(31, 47, 'Bolsas de empaquete', 36.00, 'kg', '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(32, 47, 'Botellón de 20L', 200.00, 'kg', '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(33, 47, 'Etiquetas para botellones', 200.00, 'kg', '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(34, 48, 'Bolsas de empaquete', 39.00, 'kg', '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(35, 48, 'Botellón de 20L', 200.00, 'kg', '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(36, 48, 'Etiquetas para botellones', 200.00, 'kg', '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(37, 49, 'Bolsas de empaquete', 36.00, 'kg', '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(38, 49, 'Botellón de 20L', 200.00, 'kg', '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(39, 49, 'Etiquetas para botellones', 200.00, 'kg', '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(40, 50, 'Bolsas de empaquete', 36.00, 'kg', '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(41, 50, 'Botellón de 20L', 200.00, 'kg', '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(42, 50, 'Etiquetas para botellones', 200.00, 'kg', '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(43, 51, 'Bolsas de empaquete', 38.00, 'kg', '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(44, 51, 'Botellón de 20L', 200.00, 'kg', '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(45, 51, 'Etiquetas para botellones', 200.00, 'kg', '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(46, 52, 'Bolsas de empaquete', 37.00, 'kg', '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(47, 52, 'Botellón de 20L', 200.00, 'kg', '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(48, 52, 'Etiquetas para botellones', 200.00, 'kg', '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(49, 53, 'Bolsas de empaquete', 36.00, 'kg', '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(50, 53, 'Botellón de 20L', 200.00, 'kg', '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(51, 53, 'Etiquetas para botellones', 200.00, 'kg', '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(55, 54, 'Etiquetas para botellones', 200.00, 'kg', '2025-11-22 00:37:13', '2025-11-22 00:37:13'),
(56, 62, 'Bolsa para empaquetar', 10.00, 'kg', '2025-11-28 02:17:50', '2025-11-28 02:17:50'),
(57, 63, 'Etiquetas para botellones', 10.00, 'kg', '2025-11-28 14:47:24', '2025-11-28 14:47:24'),
(58, 66, 'Etiquetas para Botellones', 147.00, 'unidades', '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(59, 66, 'Bolsas para Empaquetar (100 unidades c/u)', 32.00, 'bolsas', '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(60, 67, 'Etiquetas para Botellones', 176.00, 'unidades', '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(61, 67, 'Bolsas para Empaquetar (100 unidades c/u)', 28.00, 'bolsas', '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(62, 68, 'Etiquetas para Botellones', 177.00, 'unidades', '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(63, 68, 'Bolsas para Empaquetar (100 unidades c/u)', 32.00, 'bolsas', '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(64, 69, 'Etiquetas para Botellones', 161.00, 'unidades', '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(65, 69, 'Bolsas para Empaquetar (100 unidades c/u)', 29.00, 'bolsas', '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(66, 70, 'Etiquetas para Botellones', 125.00, 'unidades', '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(67, 70, 'Bolsas para Empaquetar (100 unidades c/u)', 33.00, 'bolsas', '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(68, 71, 'Etiquetas para Botellones', 159.00, 'unidades', '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(69, 71, 'Bolsas para Empaquetar (100 unidades c/u)', 29.00, 'bolsas', '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(72, 73, 'Etiquetas para Botellones', 120.00, 'unidades', '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(73, 73, 'Bolsas para Empaquetar (100 unidades c/u)', 29.00, 'bolsas', '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(74, 74, 'Etiquetas para Botellones', 146.00, 'unidades', '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(75, 74, 'Bolsas para Empaquetar (100 unidades c/u)', 30.00, 'bolsas', '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(76, 75, 'Etiquetas para Botellones', 138.00, 'unidades', '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(77, 75, 'Bolsas para Empaquetar (100 unidades c/u)', 35.00, 'bolsas', '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(78, 76, 'Etiquetas para Botellones', 167.00, 'unidades', '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(79, 76, 'Bolsas para Empaquetar (100 unidades c/u)', 30.00, 'bolsas', '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(80, 77, 'Etiquetas para Botellones', 126.00, 'unidades', '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(81, 77, 'Bolsas para Empaquetar (100 unidades c/u)', 28.00, 'bolsas', '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(82, 78, 'Etiquetas para Botellones', 144.00, 'unidades', '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(83, 78, 'Bolsas para Empaquetar (100 unidades c/u)', 28.00, 'bolsas', '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(86, 80, 'Etiquetas para Botellones', 140.00, 'unidades', '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(87, 80, 'Bolsas para Empaquetar (100 unidades c/u)', 28.00, 'bolsas', '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(88, 81, 'Etiquetas para Botellones', 165.00, 'unidades', '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(89, 81, 'Bolsas para Empaquetar (100 unidades c/u)', 28.00, 'bolsas', '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(90, 82, 'Etiquetas para Botellones', 177.00, 'unidades', '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(91, 82, 'Bolsas para Empaquetar (100 unidades c/u)', 30.00, 'bolsas', '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(92, 83, 'Etiquetas para Botellones', 179.00, 'unidades', '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(93, 83, 'Bolsas para Empaquetar (100 unidades c/u)', 31.00, 'bolsas', '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(94, 84, 'Etiquetas para Botellones', 159.00, 'unidades', '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(95, 84, 'Bolsas para Empaquetar (100 unidades c/u)', 31.00, 'bolsas', '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(96, 85, 'Etiquetas para Botellones', 128.00, 'unidades', '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(97, 85, 'Bolsas para Empaquetar (100 unidades c/u)', 21.00, 'bolsas', '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(100, 87, 'Etiquetas para Botellones', 128.00, 'unidades', '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(101, 87, 'Bolsas para Empaquetar (100 unidades c/u)', 24.00, 'bolsas', '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(102, 88, 'Etiquetas para Botellones', 125.00, 'unidades', '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(103, 88, 'Bolsas para Empaquetar (100 unidades c/u)', 25.00, 'bolsas', '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(104, 89, 'Etiquetas para Botellones', 123.00, 'unidades', '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(105, 89, 'Bolsas para Empaquetar (100 unidades c/u)', 24.00, 'bolsas', '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(106, 90, 'Etiquetas para Botellones', 90.00, 'unidades', '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(107, 90, 'Bolsas para Empaquetar (100 unidades c/u)', 24.00, 'bolsas', '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(110, 92, 'Etiquetas para Botellones', 111.00, 'unidades', '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(111, 92, 'Bolsas para Empaquetar (100 unidades c/u)', 23.00, 'bolsas', '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(112, 93, 'Etiquetas para Botellones', 114.00, 'unidades', '2025-12-04 14:40:44', '2025-12-04 14:40:44'),
(113, 93, 'Bolsas para Empaquetar (100 unidades c/u)', 22.00, 'bolsas', '2025-12-04 14:40:44', '2025-12-04 14:40:44');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `control_produccion_preparaciones`
--

CREATE TABLE `control_produccion_preparaciones` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `produccion_id` bigint(20) UNSIGNED NOT NULL,
  `nombre_preparacion` varchar(255) NOT NULL,
  `cantidad` int(11) NOT NULL DEFAULT 0,
  `unidad_medida` varchar(255) NOT NULL DEFAULT 'unidades',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `control_produccion_productos`
--

CREATE TABLE `control_produccion_productos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `produccion_id` bigint(20) UNSIGNED NOT NULL,
  `producto_id` bigint(20) UNSIGNED NOT NULL,
  `cantidad` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `control_produccion_productos`
--

INSERT INTO `control_produccion_productos` (`id`, `produccion_id`, `producto_id`, `cantidad`, `created_at`, `updated_at`) VALUES
(1, 37, 12, 1275, '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(2, 37, 4, 1248, '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(3, 37, 9, 200, '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(4, 37, 10, 200, '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(5, 37, 6, 214, '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(6, 38, 12, 1259, '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(7, 38, 4, 1277, '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(8, 38, 9, 200, '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(9, 38, 10, 200, '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(10, 38, 6, 201, '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(11, 39, 12, 1293, '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(12, 39, 4, 1250, '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(13, 39, 9, 200, '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(14, 39, 10, 200, '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(15, 39, 6, 227, '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(16, 40, 12, 1276, '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(17, 40, 4, 1228, '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(18, 40, 9, 200, '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(19, 40, 10, 200, '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(20, 40, 6, 245, '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(21, 41, 12, 1256, '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(22, 41, 4, 1220, '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(23, 41, 9, 200, '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(24, 41, 10, 200, '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(25, 41, 6, 206, '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(26, 42, 12, 1254, '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(27, 42, 4, 1242, '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(28, 42, 9, 200, '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(29, 42, 10, 200, '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(30, 42, 6, 234, '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(31, 43, 12, 1212, '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(32, 43, 4, 1257, '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(33, 43, 9, 200, '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(34, 43, 10, 200, '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(35, 43, 6, 211, '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(36, 44, 12, 1300, '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(37, 44, 4, 1248, '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(38, 44, 9, 200, '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(39, 44, 10, 200, '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(40, 44, 6, 203, '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(41, 45, 12, 1242, '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(42, 45, 4, 1252, '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(43, 45, 9, 200, '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(44, 45, 10, 200, '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(45, 45, 6, 208, '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(46, 46, 12, 1234, '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(47, 46, 4, 1300, '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(48, 46, 9, 200, '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(49, 46, 10, 200, '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(50, 46, 6, 228, '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(51, 47, 12, 1266, '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(52, 47, 4, 1290, '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(53, 47, 9, 200, '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(54, 47, 10, 200, '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(55, 47, 6, 209, '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(56, 48, 12, 1270, '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(57, 48, 4, 1296, '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(58, 48, 9, 200, '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(59, 48, 10, 200, '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(60, 48, 6, 245, '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(61, 49, 12, 1226, '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(62, 49, 4, 1251, '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(63, 49, 9, 200, '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(64, 49, 10, 200, '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(65, 49, 6, 213, '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(66, 50, 12, 1282, '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(67, 50, 4, 1238, '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(68, 50, 9, 200, '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(69, 50, 10, 200, '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(70, 50, 6, 248, '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(71, 51, 12, 1205, '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(72, 51, 4, 1285, '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(73, 51, 9, 200, '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(74, 51, 10, 200, '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(75, 51, 6, 212, '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(76, 52, 12, 1250, '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(77, 52, 4, 1231, '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(78, 52, 9, 200, '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(79, 52, 10, 200, '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(80, 52, 6, 220, '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(81, 53, 12, 1211, '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(82, 53, 4, 1248, '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(83, 53, 9, 200, '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(84, 53, 10, 200, '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(85, 53, 6, 218, '2025-11-22 00:07:21', '2025-11-22 00:07:21'),
(91, 37, 3, 1291, '2025-11-21 20:09:26', '2025-11-21 20:09:26'),
(92, 38, 3, 1243, '2025-11-21 20:09:26', '2025-11-21 20:09:26'),
(93, 39, 3, 1243, '2025-11-21 20:09:26', '2025-11-21 20:09:26'),
(94, 40, 3, 1285, '2025-11-21 20:09:26', '2025-11-21 20:09:26'),
(95, 41, 3, 1299, '2025-11-21 20:09:26', '2025-11-21 20:09:26'),
(96, 42, 3, 1237, '2025-11-21 20:09:26', '2025-11-21 20:09:26'),
(97, 43, 3, 1292, '2025-11-21 20:09:26', '2025-11-21 20:09:26'),
(98, 44, 3, 1246, '2025-11-21 20:09:26', '2025-11-21 20:09:26'),
(99, 45, 3, 1256, '2025-11-21 20:09:26', '2025-11-21 20:09:26'),
(100, 46, 3, 1244, '2025-11-21 20:09:26', '2025-11-21 20:09:26'),
(101, 47, 3, 1253, '2025-11-21 20:09:26', '2025-11-21 20:09:26'),
(102, 48, 3, 1233, '2025-11-21 20:09:26', '2025-11-21 20:09:26'),
(103, 49, 3, 1207, '2025-11-21 20:09:26', '2025-11-21 20:09:26'),
(104, 50, 3, 1234, '2025-11-21 20:09:26', '2025-11-21 20:09:26'),
(105, 51, 3, 1252, '2025-11-21 20:09:26', '2025-11-21 20:09:26'),
(106, 52, 3, 1258, '2025-11-21 20:09:26', '2025-11-21 20:09:26'),
(107, 53, 3, 1234, '2025-11-21 20:09:26', '2025-11-21 20:09:26'),
(122, 37, 1, 150, '2025-11-21 20:15:05', '2025-11-21 20:15:05'),
(123, 38, 1, 150, '2025-11-21 20:15:05', '2025-11-21 20:15:05'),
(124, 39, 1, 150, '2025-11-21 20:15:05', '2025-11-21 20:15:05'),
(125, 40, 1, 150, '2025-11-21 20:15:05', '2025-11-21 20:15:05'),
(126, 41, 1, 150, '2025-11-21 20:15:05', '2025-11-21 20:15:05'),
(127, 42, 1, 150, '2025-11-21 20:15:05', '2025-11-21 20:15:05'),
(128, 43, 1, 150, '2025-11-21 20:15:05', '2025-11-21 20:15:05'),
(129, 44, 1, 150, '2025-11-21 20:15:05', '2025-11-21 20:15:05'),
(130, 45, 1, 150, '2025-11-21 20:15:05', '2025-11-21 20:15:05'),
(131, 46, 1, 150, '2025-11-21 20:15:05', '2025-11-21 20:15:05'),
(132, 47, 1, 150, '2025-11-21 20:15:05', '2025-11-21 20:15:05'),
(133, 48, 1, 150, '2025-11-21 20:15:05', '2025-11-21 20:15:05'),
(134, 49, 1, 150, '2025-11-21 20:15:05', '2025-11-21 20:15:05'),
(135, 50, 1, 150, '2025-11-21 20:15:05', '2025-11-21 20:15:05'),
(136, 51, 1, 150, '2025-11-21 20:15:05', '2025-11-21 20:15:05'),
(137, 52, 1, 150, '2025-11-21 20:15:05', '2025-11-21 20:15:05'),
(138, 53, 1, 150, '2025-11-21 20:15:05', '2025-11-21 20:15:05'),
(153, 37, 8, 100, '2025-11-21 20:15:05', '2025-11-21 20:15:05'),
(154, 38, 8, 100, '2025-11-21 20:15:05', '2025-11-21 20:15:05'),
(155, 39, 8, 100, '2025-11-21 20:15:05', '2025-11-21 20:15:05'),
(156, 40, 8, 100, '2025-11-21 20:15:05', '2025-11-21 20:15:05'),
(157, 41, 8, 100, '2025-11-21 20:15:05', '2025-11-21 20:15:05'),
(158, 42, 8, 100, '2025-11-21 20:15:05', '2025-11-21 20:15:05'),
(159, 43, 8, 100, '2025-11-21 20:15:05', '2025-11-21 20:15:05'),
(160, 44, 8, 100, '2025-11-21 20:15:05', '2025-11-21 20:15:05'),
(161, 45, 8, 100, '2025-11-21 20:15:05', '2025-11-21 20:15:05'),
(162, 46, 8, 100, '2025-11-21 20:15:05', '2025-11-21 20:15:05'),
(163, 47, 8, 100, '2025-11-21 20:15:05', '2025-11-21 20:15:05'),
(164, 48, 8, 100, '2025-11-21 20:15:05', '2025-11-21 20:15:05'),
(165, 49, 8, 100, '2025-11-21 20:15:05', '2025-11-21 20:15:05'),
(166, 50, 8, 100, '2025-11-21 20:15:05', '2025-11-21 20:15:05'),
(167, 51, 8, 100, '2025-11-21 20:15:05', '2025-11-21 20:15:05'),
(168, 52, 8, 100, '2025-11-21 20:15:05', '2025-11-21 20:15:05'),
(169, 53, 8, 100, '2025-11-21 20:15:05', '2025-11-21 20:15:05'),
(184, 37, 11, 90, '2025-11-21 20:15:05', '2025-11-21 20:15:05'),
(185, 38, 11, 90, '2025-11-21 20:15:05', '2025-11-21 20:15:05'),
(186, 39, 11, 90, '2025-11-21 20:15:05', '2025-11-21 20:15:05'),
(187, 40, 11, 90, '2025-11-21 20:15:05', '2025-11-21 20:15:05'),
(188, 41, 11, 90, '2025-11-21 20:15:05', '2025-11-21 20:15:05'),
(189, 42, 11, 90, '2025-11-21 20:15:05', '2025-11-21 20:15:05'),
(190, 43, 11, 90, '2025-11-21 20:15:05', '2025-11-21 20:15:05'),
(191, 44, 11, 90, '2025-11-21 20:15:05', '2025-11-21 20:15:05'),
(192, 45, 11, 90, '2025-11-21 20:15:05', '2025-11-21 20:15:05'),
(193, 46, 11, 90, '2025-11-21 20:15:05', '2025-11-21 20:15:05'),
(194, 47, 11, 90, '2025-11-21 20:15:05', '2025-11-21 20:15:05'),
(195, 48, 11, 90, '2025-11-21 20:15:05', '2025-11-21 20:15:05'),
(196, 49, 11, 90, '2025-11-21 20:15:05', '2025-11-21 20:15:05'),
(197, 50, 11, 90, '2025-11-21 20:15:05', '2025-11-21 20:15:05'),
(198, 51, 11, 90, '2025-11-21 20:15:05', '2025-11-21 20:15:05'),
(199, 52, 11, 90, '2025-11-21 20:15:05', '2025-11-21 20:15:05'),
(200, 53, 11, 90, '2025-11-21 20:15:05', '2025-11-21 20:15:05'),
(215, 54, 12, 1268, '2025-11-22 00:37:13', '2025-11-22 00:37:13'),
(216, 54, 4, 1234, '2025-11-22 00:37:13', '2025-11-22 00:37:13'),
(217, 54, 9, 200, '2025-11-22 00:37:13', '2025-11-22 00:37:13'),
(218, 54, 10, 200, '2025-11-22 00:37:13', '2025-11-22 00:37:13'),
(219, 54, 6, 220, '2025-11-22 00:37:13', '2025-11-22 00:37:13'),
(220, 54, 3, 1297, '2025-11-22 00:37:13', '2025-11-22 00:37:13'),
(221, 54, 1, 150, '2025-11-22 00:37:13', '2025-11-22 00:37:13'),
(222, 54, 11, 50, '2025-11-22 00:37:13', '2025-11-22 00:37:13'),
(223, 54, 8, 90, '2025-11-22 00:37:13', '2025-11-22 00:37:13'),
(224, 56, 1, 45, '2025-11-26 02:36:11', '2025-11-26 02:36:11'),
(225, 56, 3, 120, '2025-11-26 02:36:11', '2025-11-26 02:36:11'),
(226, 56, 4, 85, '2025-11-26 02:36:11', '2025-11-26 02:36:11'),
(227, 56, 9, 200, '2025-11-26 02:36:11', '2025-11-26 02:36:11'),
(228, 56, 10, 150, '2025-11-26 02:36:11', '2025-11-26 02:36:11'),
(229, 57, 1, 45, '2025-11-26 02:36:45', '2025-11-26 02:36:45'),
(230, 57, 3, 120, '2025-11-26 02:36:45', '2025-11-26 02:36:45'),
(231, 57, 4, 85, '2025-11-26 02:36:45', '2025-11-26 02:36:45'),
(232, 57, 9, 200, '2025-11-26 02:36:45', '2025-11-26 02:36:45'),
(233, 57, 10, 150, '2025-11-26 02:36:45', '2025-11-26 02:36:45'),
(234, 58, 1, 45, '2025-11-26 06:40:12', '2025-11-26 06:40:12'),
(235, 58, 3, 120, '2025-11-26 06:40:12', '2025-11-26 06:40:12'),
(236, 58, 4, 85, '2025-11-26 06:40:12', '2025-11-26 06:40:12'),
(237, 58, 9, 200, '2025-11-26 06:40:12', '2025-11-26 06:40:12'),
(238, 58, 10, 150, '2025-11-26 06:40:12', '2025-11-26 06:40:12'),
(239, 59, 1, 65, '2025-11-26 06:42:55', '2025-11-26 06:42:55'),
(240, 59, 3, 180, '2025-11-26 06:42:55', '2025-11-26 06:42:55'),
(241, 59, 4, 140, '2025-11-26 06:42:55', '2025-11-26 06:42:55'),
(242, 59, 6, 75, '2025-11-26 06:42:55', '2025-11-26 06:42:55'),
(243, 59, 8, 90, '2025-11-26 06:42:55', '2025-11-26 06:42:55'),
(244, 59, 9, 280, '2025-11-26 06:42:55', '2025-11-26 06:42:55'),
(245, 59, 10, 220, '2025-11-26 06:42:55', '2025-11-26 06:42:55'),
(246, 59, 12, 95, '2025-11-26 06:42:55', '2025-11-26 06:42:55'),
(247, 60, 1, 70, '2025-11-26 06:43:29', '2025-11-26 06:43:29'),
(248, 60, 3, 195, '2025-11-26 06:43:29', '2025-11-26 06:43:29'),
(249, 60, 4, 155, '2025-11-26 06:43:29', '2025-11-26 06:43:29'),
(250, 60, 6, 85, '2025-11-26 06:43:29', '2025-11-26 06:43:29'),
(251, 60, 8, 100, '2025-11-26 06:43:29', '2025-11-26 06:43:29'),
(252, 60, 9, 300, '2025-11-26 06:43:29', '2025-11-26 06:43:29'),
(253, 60, 10, 240, '2025-11-26 06:43:29', '2025-11-26 06:43:29'),
(254, 60, 11, 50, '2025-11-26 06:43:29', '2025-11-26 06:43:29'),
(255, 60, 12, 105, '2025-11-26 06:43:29', '2025-11-26 06:43:29'),
(265, 61, 12, 1200, '2025-11-27 01:30:10', '2025-11-27 01:30:10'),
(266, 61, 3, 1300, '2025-11-27 01:30:10', '2025-11-27 01:30:10'),
(267, 61, 4, 1200, '2025-11-27 01:30:10', '2025-11-27 01:30:10'),
(268, 61, 9, 700, '2025-11-27 01:30:10', '2025-11-27 01:30:10'),
(269, 61, 10, 550, '2025-11-27 01:30:10', '2025-11-27 01:30:10'),
(270, 61, 1, 100, '2025-11-27 01:30:10', '2025-11-27 01:30:10'),
(271, 61, 11, 80, '2025-11-27 01:30:10', '2025-11-27 01:30:10'),
(272, 61, 6, 300, '2025-11-27 01:30:10', '2025-11-27 01:30:10'),
(273, 61, 8, 80, '2025-11-27 01:30:10', '2025-11-27 01:30:10'),
(274, 62, 8, 10, '2025-11-28 02:17:50', '2025-11-28 02:17:50'),
(275, 63, 1, 10, '2025-11-28 14:47:24', '2025-11-28 14:47:24'),
(277, 66, 1, 147, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(278, 66, 3, 1019, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(279, 66, 4, 1170, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(280, 66, 6, 218, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(281, 66, 8, 79, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(282, 66, 9, 472, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(283, 66, 10, 339, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(284, 66, 11, 76, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(285, 66, 12, 950, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(286, 67, 1, 176, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(287, 67, 3, 1082, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(288, 67, 4, 850, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(289, 67, 6, 198, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(290, 67, 8, 57, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(291, 67, 9, 360, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(292, 67, 10, 270, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(293, 67, 11, 92, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(294, 67, 12, 860, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(295, 68, 1, 177, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(296, 68, 3, 1124, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(297, 68, 4, 1080, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(298, 68, 6, 192, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(299, 68, 8, 67, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(300, 68, 9, 432, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(301, 68, 10, 330, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(302, 68, 11, 93, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(303, 68, 12, 990, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(304, 69, 1, 161, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(305, 69, 3, 998, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(306, 69, 4, 870, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(307, 69, 6, 198, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(308, 69, 8, 70, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(309, 69, 9, 352, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(310, 69, 10, 348, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(311, 69, 11, 77, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(312, 69, 12, 950, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(313, 70, 1, 125, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(314, 70, 3, 1040, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(315, 70, 4, 1090, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(316, 70, 6, 208, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(317, 70, 8, 81, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(318, 70, 9, 440, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(319, 70, 10, 270, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(320, 70, 11, 86, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(321, 70, 12, 1160, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(322, 71, 1, 159, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(323, 71, 3, 987, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(324, 71, 4, 820, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(325, 71, 6, 234, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(326, 71, 8, 71, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(327, 71, 9, 384, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(328, 71, 10, 255, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(329, 71, 11, 89, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(330, 71, 12, 1050, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(340, 73, 1, 120, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(341, 73, 3, 1134, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(342, 73, 4, 830, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(343, 73, 6, 222, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(344, 73, 8, 78, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(345, 73, 9, 408, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(346, 73, 10, 288, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(347, 73, 11, 80, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(348, 73, 12, 890, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(349, 74, 1, 146, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(350, 74, 3, 1113, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(351, 74, 4, 820, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(352, 74, 6, 230, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(353, 74, 8, 74, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(354, 74, 9, 324, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(355, 74, 10, 255, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(356, 74, 11, 73, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(357, 74, 12, 970, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(358, 75, 1, 138, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(359, 75, 3, 1218, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(360, 75, 4, 1040, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(361, 75, 6, 206, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(362, 75, 8, 72, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(363, 75, 9, 392, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(364, 75, 10, 306, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(365, 75, 11, 108, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(366, 75, 12, 1170, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(367, 76, 1, 167, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(368, 76, 3, 1197, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(369, 76, 4, 850, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(370, 76, 6, 224, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(371, 76, 8, 83, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(372, 76, 9, 356, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(373, 76, 10, 279, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(374, 76, 11, 76, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(375, 76, 12, 870, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(376, 77, 1, 126, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(377, 77, 3, 861, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(378, 77, 4, 910, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(379, 77, 6, 162, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(380, 77, 8, 70, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(381, 77, 9, 440, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(382, 77, 10, 357, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(383, 77, 11, 80, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(384, 77, 12, 1000, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(385, 78, 1, 144, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(386, 78, 3, 1082, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(387, 78, 4, 830, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(388, 78, 6, 216, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(389, 78, 8, 74, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(390, 78, 9, 404, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(391, 78, 10, 348, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(392, 78, 11, 90, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(393, 78, 12, 800, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(403, 80, 1, 140, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(404, 80, 3, 882, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(405, 80, 4, 920, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(406, 80, 6, 228, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(407, 80, 8, 57, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(408, 80, 9, 344, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(409, 80, 10, 306, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(410, 80, 11, 92, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(411, 80, 12, 920, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(412, 81, 1, 165, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(413, 81, 3, 903, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(414, 81, 4, 910, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(415, 81, 6, 214, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(416, 81, 8, 71, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(417, 81, 9, 396, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(418, 81, 10, 327, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(419, 81, 11, 91, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(420, 81, 12, 890, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(421, 82, 1, 177, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(422, 82, 3, 1166, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(423, 82, 4, 880, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(424, 82, 6, 228, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(425, 82, 8, 69, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(426, 82, 9, 456, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(427, 82, 10, 330, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(428, 82, 11, 101, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(429, 82, 12, 870, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(430, 83, 1, 179, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(431, 83, 3, 966, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(432, 83, 4, 910, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(433, 83, 6, 196, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(434, 83, 8, 67, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(435, 83, 9, 440, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(436, 83, 10, 327, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(437, 83, 11, 99, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(438, 83, 12, 1160, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(439, 84, 1, 159, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(440, 84, 3, 1050, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(441, 84, 4, 950, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(442, 84, 6, 208, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(443, 84, 8, 81, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(444, 84, 9, 440, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(445, 84, 10, 282, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(446, 84, 11, 103, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(447, 84, 12, 1050, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(448, 85, 1, 128, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(449, 85, 3, 693, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(450, 85, 4, 780, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(451, 85, 6, 180, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(452, 85, 8, 60, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(453, 85, 9, 344, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(454, 85, 10, 246, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(455, 85, 11, 77, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(456, 85, 12, 610, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(466, 87, 1, 128, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(467, 87, 3, 725, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(468, 87, 4, 900, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(469, 87, 6, 124, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(470, 87, 8, 56, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(471, 87, 9, 328, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(472, 87, 10, 204, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(473, 87, 11, 66, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(474, 87, 12, 710, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(475, 88, 1, 125, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(476, 88, 3, 872, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(477, 88, 4, 640, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(478, 88, 6, 154, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(479, 88, 8, 43, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(480, 88, 9, 332, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(481, 88, 10, 261, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(482, 88, 11, 71, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(483, 88, 12, 890, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(484, 89, 1, 123, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(485, 89, 3, 735, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(486, 89, 4, 900, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(487, 89, 6, 180, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(488, 89, 8, 61, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(489, 89, 9, 324, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(490, 89, 10, 210, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(491, 89, 11, 77, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(492, 89, 12, 680, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(493, 90, 1, 90, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(494, 90, 3, 777, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(495, 90, 4, 850, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(496, 90, 6, 126, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(497, 90, 8, 59, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(498, 90, 9, 276, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(499, 90, 10, 231, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(500, 90, 11, 73, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(501, 90, 12, 730, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(511, 92, 1, 111, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(512, 92, 3, 746, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(513, 92, 4, 740, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(514, 92, 6, 142, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(515, 92, 8, 43, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(516, 92, 9, 324, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(517, 92, 10, 231, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(518, 92, 11, 60, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(519, 92, 12, 730, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(520, 93, 1, 114, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(521, 93, 3, 641, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(522, 93, 4, 860, '2025-12-04 14:40:43', '2025-12-04 14:40:43'),
(523, 93, 6, 166, '2025-12-04 14:40:44', '2025-12-04 14:40:44'),
(524, 93, 8, 43, '2025-12-04 14:40:44', '2025-12-04 14:40:44'),
(525, 93, 9, 240, '2025-12-04 14:40:44', '2025-12-04 14:40:44'),
(526, 93, 10, 198, '2025-12-04 14:40:44', '2025-12-04 14:40:44'),
(527, 93, 11, 59, '2025-12-04 14:40:44', '2025-12-04 14:40:44'),
(528, 93, 12, 680, '2025-12-04 14:40:44', '2025-12-04 14:40:44');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `control_salidas_productos`
--

CREATE TABLE `control_salidas_productos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nombre_distribuidor` varchar(255) NOT NULL,
  `chofer` varchar(255) DEFAULT NULL,
  `tipo_salida` varchar(50) DEFAULT 'Despacho Interno',
  `nombre_cliente` varchar(255) DEFAULT NULL,
  `direccion_entrega` varchar(255) DEFAULT NULL,
  `telefono_cliente` varchar(20) DEFAULT NULL,
  `responsable` varchar(255) DEFAULT NULL,
  `vehiculo_placa` varchar(255) DEFAULT NULL,
  `id_chofer` bigint(20) UNSIGNED DEFAULT NULL,
  `id_responsable_salida` bigint(20) UNSIGNED DEFAULT NULL,
  `fecha` date NOT NULL,
  `lunes` int(11) NOT NULL DEFAULT 0,
  `martes` int(11) NOT NULL DEFAULT 0,
  `miercoles` int(11) NOT NULL DEFAULT 0,
  `jueves` int(11) NOT NULL DEFAULT 0,
  `viernes` int(11) NOT NULL DEFAULT 0,
  `sabado` int(11) NOT NULL DEFAULT 0,
  `domingo` int(11) NOT NULL DEFAULT 0,
  `retornos` int(11) NOT NULL DEFAULT 0,
  `retorno_botellones` int(11) NOT NULL DEFAULT 0,
  `retorno_bolo_grande` int(11) NOT NULL DEFAULT 0,
  `retorno_bolo_pequeno` int(11) NOT NULL DEFAULT 0,
  `retorno_gelatina` int(11) NOT NULL DEFAULT 0,
  `retorno_agua_saborizada` int(11) NOT NULL DEFAULT 0,
  `retorno_agua_limon` int(11) NOT NULL DEFAULT 0,
  `retorno_agua_natural` int(11) NOT NULL DEFAULT 0,
  `retorno_hielo` int(11) NOT NULL DEFAULT 0,
  `retorno_dispenser` int(11) NOT NULL DEFAULT 0,
  `botellones` int(11) NOT NULL DEFAULT 0,
  `bolo_grande` int(11) NOT NULL DEFAULT 0,
  `bolo_pequeño` int(11) NOT NULL DEFAULT 0,
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
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `control_salidas_productos`
--

INSERT INTO `control_salidas_productos` (`id`, `nombre_distribuidor`, `chofer`, `tipo_salida`, `nombre_cliente`, `direccion_entrega`, `telefono_cliente`, `responsable`, `vehiculo_placa`, `id_chofer`, `id_responsable_salida`, `fecha`, `lunes`, `martes`, `miercoles`, `jueves`, `viernes`, `sabado`, `domingo`, `retornos`, `retorno_botellones`, `retorno_bolo_grande`, `retorno_bolo_pequeno`, `retorno_gelatina`, `retorno_agua_saborizada`, `retorno_agua_limon`, `retorno_agua_natural`, `retorno_hielo`, `retorno_dispenser`, `botellones`, `bolo_grande`, `bolo_pequeño`, `gelatina`, `agua_saborizada`, `agua_limon`, `agua_natural`, `hielo`, `dispenser`, `choreados`, `hora_llegada`, `observaciones`, `created_at`, `updated_at`) VALUES
(76, 'deybi aguilar', NULL, 'Despacho Interno', NULL, NULL, NULL, NULL, '1234ABC', NULL, NULL, '2025-11-01', 0, 0, 0, 0, 0, 0, 0, 47, 1, 1, 3, 3, 5, 20, 10, 0, 4, 50, 282, 36, 42, 4120, 5020, 4040, 31, 0, 30, '00:58:00', 'Despacho Interno - Generado automáticamente', '2025-11-22 01:10:41', '2025-11-22 01:10:41'),
(77, 'Sergio Aguilar', NULL, 'Despacho Interno', NULL, NULL, NULL, NULL, '5678DEF', NULL, NULL, '2025-11-01', 0, 0, 0, 0, 0, 0, 0, 47, 1, 1, 3, 3, 5, 20, 10, 0, 4, 48, 303, 36, 38, 5690, 4560, 5330, 28, 0, 30, '23:56:00', 'Despacho Interno - Generado automáticamente', '2025-11-22 01:10:41', '2025-11-22 01:10:41'),
(78, 'Jasmani Aguilar', NULL, 'Despacho Interno', NULL, NULL, NULL, NULL, '9012GHI', NULL, NULL, '2025-11-01', 0, 0, 0, 0, 0, 0, 0, 47, 1, 1, 3, 3, 5, 20, 10, 0, 4, 40, 313, 43, 37, 5800, 5810, 5230, 26, 0, 30, '20:52:00', 'Despacho Interno - Generado automáticamente', '2025-11-22 01:10:41', '2025-11-22 01:10:41'),
(79, 'deybi aguilar', NULL, 'Despacho Interno', NULL, NULL, NULL, NULL, '1234ABC', NULL, NULL, '2025-11-03', 0, 0, 0, 0, 0, 0, 0, 47, 1, 1, 3, 3, 5, 20, 10, 0, 4, 45, 300, 38, 40, 5730, 4340, 5300, 35, 0, 30, '18:52:00', 'Despacho Interno - Generado automáticamente', '2025-11-22 01:10:41', '2025-11-22 01:10:41'),
(80, 'Sergio Aguilar', NULL, 'Despacho Interno', NULL, NULL, NULL, NULL, '5678DEF', NULL, NULL, '2025-11-03', 0, 0, 0, 0, 0, 0, 0, 47, 1, 1, 3, 3, 5, 20, 10, 0, 4, 40, 282, 45, 42, 5590, 5120, 4090, 26, 0, 30, '00:25:00', 'Despacho Interno - Generado automáticamente', '2025-11-22 01:10:41', '2025-11-22 01:10:41'),
(81, 'Jasmani Aguilar', NULL, 'Despacho Interno', NULL, NULL, NULL, NULL, '9012GHI', NULL, NULL, '2025-11-03', 0, 0, 0, 0, 0, 0, 0, 47, 1, 1, 3, 3, 5, 20, 10, 0, 4, 41, 291, 38, 35, 4450, 4060, 4550, 34, 0, 30, '00:48:00', 'Despacho Interno - Generado automáticamente', '2025-11-22 01:10:41', '2025-11-22 01:10:41'),
(82, 'deybi aguilar', NULL, 'Despacho Interno', NULL, NULL, NULL, NULL, '1234ABC', NULL, NULL, '2025-11-04', 0, 0, 0, 0, 0, 0, 0, 47, 1, 1, 3, 3, 5, 20, 10, 0, 4, 40, 317, 37, 41, 4650, 5710, 4510, 35, 0, 30, '23:32:00', 'Despacho Interno - Generado automáticamente', '2025-11-22 01:10:41', '2025-11-22 01:10:41'),
(83, 'Sergio Aguilar', NULL, 'Despacho Interno', NULL, NULL, NULL, NULL, '5678DEF', NULL, NULL, '2025-11-04', 0, 0, 0, 0, 0, 0, 0, 47, 1, 1, 3, 3, 5, 20, 10, 0, 4, 45, 283, 44, 45, 5580, 5660, 4870, 35, 0, 30, '20:30:00', 'Despacho Interno - Generado automáticamente', '2025-11-22 01:10:41', '2025-11-22 01:10:41'),
(84, 'Jasmani Aguilar', NULL, 'Despacho Interno', NULL, NULL, NULL, NULL, '9012GHI', NULL, NULL, '2025-11-04', 0, 0, 0, 0, 0, 0, 0, 47, 1, 1, 3, 3, 5, 20, 10, 0, 4, 44, 295, 37, 37, 5690, 4810, 4370, 25, 0, 30, '19:07:00', 'Despacho Interno - Generado automáticamente', '2025-11-22 01:10:41', '2025-11-22 01:10:41'),
(85, 'deybi aguilar', NULL, 'Despacho Interno', NULL, NULL, NULL, NULL, '1234ABC', NULL, NULL, '2025-11-05', 0, 0, 0, 0, 0, 0, 0, 47, 1, 1, 3, 3, 5, 20, 10, 0, 4, 42, 306, 35, 39, 4420, 5670, 4080, 34, 0, 30, '00:56:00', 'Despacho Interno - Generado automáticamente', '2025-11-22 01:10:41', '2025-11-22 01:10:41'),
(86, 'Sergio Aguilar', NULL, 'Despacho Interno', NULL, NULL, NULL, NULL, '5678DEF', NULL, NULL, '2025-11-05', 0, 0, 0, 0, 0, 0, 0, 47, 1, 1, 3, 3, 5, 20, 10, 0, 4, 43, 292, 38, 37, 5060, 5060, 5100, 33, 0, 30, '18:56:00', 'Despacho Interno - Generado automáticamente', '2025-11-22 01:10:41', '2025-11-22 01:10:41'),
(87, 'Jasmani Aguilar', NULL, 'Despacho Interno', NULL, NULL, NULL, NULL, '9012GHI', NULL, NULL, '2025-11-05', 0, 0, 0, 0, 0, 0, 0, 47, 1, 1, 3, 3, 5, 20, 10, 0, 4, 40, 285, 45, 43, 4770, 4920, 4180, 33, 0, 30, '21:23:00', 'Despacho Interno - Generado automáticamente', '2025-11-22 01:10:41', '2025-11-22 01:10:41'),
(88, 'deybi aguilar', NULL, 'Despacho Interno', NULL, NULL, NULL, NULL, '1234ABC', NULL, NULL, '2025-11-06', 0, 0, 0, 0, 0, 0, 0, 47, 1, 1, 3, 3, 5, 20, 10, 0, 4, 41, 303, 36, 37, 5360, 5470, 4500, 34, 0, 30, '00:11:00', 'Despacho Interno - Generado automáticamente', '2025-11-22 01:10:41', '2025-11-22 01:10:41'),
(89, 'Sergio Aguilar', NULL, 'Despacho Interno', NULL, NULL, NULL, NULL, '5678DEF', NULL, NULL, '2025-11-06', 0, 0, 0, 0, 0, 0, 0, 47, 1, 1, 3, 3, 5, 20, 10, 0, 4, 42, 318, 36, 39, 4210, 5500, 5720, 34, 0, 30, '00:49:00', 'Despacho Interno - Generado automáticamente', '2025-11-22 01:10:41', '2025-11-22 01:10:41'),
(90, 'Jasmani Aguilar', NULL, 'Despacho Interno', NULL, NULL, NULL, NULL, '9012GHI', NULL, NULL, '2025-11-06', 0, 0, 0, 0, 0, 0, 0, 47, 1, 1, 3, 3, 5, 20, 10, 0, 4, 47, 313, 45, 45, 5450, 5360, 4950, 25, 0, 30, '18:31:00', 'Despacho Interno - Generado automáticamente', '2025-11-22 01:10:41', '2025-11-22 01:10:41'),
(91, 'deybi aguilar', NULL, 'Despacho Interno', NULL, NULL, NULL, NULL, '1234ABC', NULL, NULL, '2025-11-07', 0, 0, 0, 0, 0, 0, 0, 47, 1, 1, 3, 3, 5, 20, 10, 0, 4, 41, 318, 39, 39, 5290, 4480, 4160, 29, 0, 30, '21:37:00', 'Despacho Interno - Generado automáticamente', '2025-11-22 01:10:41', '2025-11-22 01:10:41'),
(92, 'Sergio Aguilar', NULL, 'Despacho Interno', NULL, NULL, NULL, NULL, '5678DEF', NULL, NULL, '2025-11-07', 0, 0, 0, 0, 0, 0, 0, 47, 1, 1, 3, 3, 5, 20, 10, 0, 4, 41, 296, 37, 45, 4200, 4680, 4170, 33, 0, 30, '21:48:00', 'Despacho Interno - Generado automáticamente', '2025-11-22 01:10:41', '2025-11-22 01:10:41'),
(93, 'Jasmani Aguilar', NULL, 'Despacho Interno', NULL, NULL, NULL, NULL, '9012GHI', NULL, NULL, '2025-11-07', 0, 0, 0, 0, 0, 0, 0, 47, 1, 1, 3, 3, 5, 20, 10, 0, 4, 44, 293, 44, 40, 4150, 5030, 5280, 33, 0, 30, '00:50:00', 'Despacho Interno - Generado automáticamente', '2025-11-22 01:10:41', '2025-11-22 01:10:41'),
(94, 'deybi aguilar', NULL, 'Despacho Interno', NULL, NULL, NULL, NULL, '1234ABC', NULL, NULL, '2025-11-08', 0, 0, 0, 0, 0, 0, 0, 47, 1, 1, 3, 3, 5, 20, 10, 0, 4, 42, 310, 42, 39, 5750, 5130, 4100, 28, 0, 30, '00:25:00', 'Despacho Interno - Generado automáticamente', '2025-11-22 01:10:41', '2025-11-22 01:10:41'),
(95, 'Sergio Aguilar', NULL, 'Despacho Interno', NULL, NULL, NULL, NULL, '5678DEF', NULL, NULL, '2025-11-08', 0, 0, 0, 0, 0, 0, 0, 47, 1, 1, 3, 3, 5, 20, 10, 0, 4, 40, 298, 40, 44, 5220, 5480, 5610, 31, 0, 30, '23:52:00', 'Despacho Interno - Generado automáticamente', '2025-11-22 01:10:41', '2025-11-22 01:10:41'),
(96, 'Jasmani Aguilar', NULL, 'Despacho Interno', NULL, NULL, NULL, NULL, '9012GHI', NULL, NULL, '2025-11-08', 0, 0, 0, 0, 0, 0, 0, 47, 1, 1, 3, 3, 5, 20, 10, 0, 4, 44, 304, 37, 44, 5840, 5690, 5130, 27, 0, 30, '20:52:00', 'Despacho Interno - Generado automáticamente', '2025-11-22 01:10:41', '2025-11-22 01:10:41'),
(97, 'deybi aguilar', NULL, 'Despacho Interno', NULL, NULL, NULL, NULL, '1234ABC', NULL, NULL, '2025-11-10', 0, 0, 0, 0, 0, 0, 0, 47, 1, 1, 3, 3, 5, 20, 10, 0, 4, 40, 319, 44, 43, 4320, 5880, 5750, 27, 0, 30, '18:32:00', 'Despacho Interno - Generado automáticamente', '2025-11-22 01:10:41', '2025-11-22 01:10:41'),
(98, 'Sergio Aguilar', NULL, 'Despacho Interno', NULL, NULL, NULL, NULL, '5678DEF', NULL, NULL, '2025-11-10', 0, 0, 0, 0, 0, 0, 0, 47, 1, 1, 3, 3, 5, 20, 10, 0, 4, 46, 284, 45, 45, 4270, 4940, 5320, 27, 0, 30, '20:03:00', 'Despacho Interno - Generado automáticamente', '2025-11-22 01:10:41', '2025-11-22 01:10:41'),
(99, 'Jasmani Aguilar', NULL, 'Despacho Interno', NULL, NULL, NULL, NULL, '9012GHI', NULL, NULL, '2025-11-10', 0, 0, 0, 0, 0, 0, 0, 47, 1, 1, 3, 3, 5, 20, 10, 0, 4, 48, 318, 36, 41, 4220, 4270, 4260, 35, 0, 30, '00:14:00', 'Despacho Interno - Generado automáticamente', '2025-11-22 01:10:41', '2025-11-22 01:10:41'),
(100, 'deybi aguilar', NULL, 'Despacho Interno', NULL, NULL, NULL, NULL, '1234ABC', NULL, NULL, '2025-11-11', 0, 0, 0, 0, 0, 0, 0, 47, 1, 1, 3, 3, 5, 20, 10, 0, 4, 50, 312, 35, 41, 5350, 5250, 5770, 25, 0, 30, '23:58:00', 'Despacho Interno - Generado automáticamente', '2025-11-22 01:10:41', '2025-11-22 01:10:41'),
(101, 'Sergio Aguilar', NULL, 'Despacho Interno', NULL, NULL, NULL, NULL, '5678DEF', NULL, NULL, '2025-11-11', 0, 0, 0, 0, 0, 0, 0, 47, 1, 1, 3, 3, 5, 20, 10, 0, 4, 43, 303, 37, 42, 5210, 5360, 4930, 27, 0, 30, '19:44:00', 'Despacho Interno - Generado automáticamente', '2025-11-22 01:10:41', '2025-11-22 01:10:41'),
(102, 'Jasmani Aguilar', NULL, 'Despacho Interno', NULL, NULL, NULL, NULL, '9012GHI', NULL, NULL, '2025-11-11', 0, 0, 0, 0, 0, 0, 0, 47, 1, 1, 3, 3, 5, 20, 10, 0, 4, 48, 283, 37, 39, 5830, 4310, 5430, 25, 0, 30, '23:03:00', 'Despacho Interno - Generado automáticamente', '2025-11-22 01:10:41', '2025-11-22 01:10:41'),
(103, 'deybi aguilar', NULL, 'Despacho Interno', NULL, NULL, NULL, NULL, '1234ABC', NULL, NULL, '2025-11-12', 0, 0, 0, 0, 0, 0, 0, 47, 1, 1, 3, 3, 5, 20, 10, 0, 4, 50, 296, 41, 38, 4310, 5110, 4110, 31, 0, 30, '00:33:00', 'Despacho Interno - Generado automáticamente', '2025-11-22 01:10:41', '2025-11-22 01:10:41'),
(104, 'Sergio Aguilar', NULL, 'Despacho Interno', NULL, NULL, NULL, NULL, '5678DEF', NULL, NULL, '2025-11-12', 0, 0, 0, 0, 0, 0, 0, 47, 1, 1, 3, 3, 5, 20, 10, 0, 4, 40, 287, 42, 41, 5020, 4790, 4520, 29, 0, 30, '20:55:00', 'Despacho Interno - Generado automáticamente', '2025-11-22 01:10:41', '2025-11-22 01:10:41'),
(105, 'Jasmani Aguilar', NULL, 'Despacho Interno', NULL, NULL, NULL, NULL, '9012GHI', NULL, NULL, '2025-11-12', 0, 0, 0, 0, 0, 0, 0, 47, 1, 1, 3, 3, 5, 20, 10, 0, 4, 41, 291, 38, 39, 4590, 5720, 5720, 26, 0, 30, '22:32:00', 'Despacho Interno - Generado automáticamente', '2025-11-22 01:10:41', '2025-11-22 01:10:41'),
(106, 'deybi aguilar', NULL, 'Despacho Interno', NULL, NULL, NULL, NULL, '1234ABC', NULL, NULL, '2025-11-13', 0, 0, 0, 0, 0, 0, 0, 47, 1, 1, 3, 3, 5, 20, 10, 0, 4, 43, 294, 41, 45, 4000, 5930, 5660, 32, 0, 30, '23:32:00', 'Despacho Interno - Generado automáticamente', '2025-11-22 01:10:41', '2025-11-22 01:10:41'),
(107, 'Sergio Aguilar', NULL, 'Despacho Interno', NULL, NULL, NULL, NULL, '5678DEF', NULL, NULL, '2025-11-13', 0, 0, 0, 0, 0, 0, 0, 47, 1, 1, 3, 3, 5, 20, 10, 0, 4, 45, 290, 43, 45, 5060, 4750, 5720, 34, 0, 30, '23:15:00', 'Despacho Interno - Generado automáticamente', '2025-11-22 01:10:41', '2025-11-22 01:10:41'),
(108, 'Jasmani Aguilar', NULL, 'Despacho Interno', NULL, NULL, NULL, NULL, '9012GHI', NULL, NULL, '2025-11-13', 0, 0, 0, 0, 0, 0, 0, 47, 1, 1, 3, 3, 5, 20, 10, 0, 4, 42, 287, 36, 39, 4020, 5670, 4870, 25, 0, 30, '20:13:00', 'Despacho Interno - Generado automáticamente', '2025-11-22 01:10:41', '2025-11-22 01:10:41'),
(109, 'deybi aguilar', NULL, 'Despacho Interno', NULL, NULL, NULL, NULL, '1234ABC', NULL, NULL, '2025-11-14', 0, 0, 0, 0, 0, 0, 0, 47, 1, 1, 3, 3, 5, 20, 10, 0, 4, 45, 317, 35, 45, 5320, 5280, 5910, 29, 0, 30, '21:46:00', 'Despacho Interno - Generado automáticamente', '2025-11-22 01:10:41', '2025-11-22 01:10:41'),
(110, 'Sergio Aguilar', NULL, 'Despacho Interno', NULL, NULL, NULL, NULL, '5678DEF', NULL, NULL, '2025-11-14', 0, 0, 0, 0, 0, 0, 0, 47, 1, 1, 3, 3, 5, 20, 10, 0, 4, 50, 287, 45, 40, 4040, 5130, 4370, 33, 0, 30, '21:03:00', 'Despacho Interno - Generado automáticamente', '2025-11-22 01:10:41', '2025-11-22 01:10:41'),
(111, 'Jasmani Aguilar', NULL, 'Despacho Interno', NULL, NULL, NULL, NULL, '9012GHI', NULL, NULL, '2025-11-14', 0, 0, 0, 0, 0, 0, 0, 47, 1, 1, 3, 3, 5, 20, 10, 0, 4, 43, 285, 44, 40, 4110, 4860, 4280, 27, 0, 30, '19:11:00', 'Despacho Interno - Generado automáticamente', '2025-11-22 01:10:41', '2025-11-22 01:10:41'),
(112, 'deybi aguilar', NULL, 'Despacho Interno', NULL, NULL, NULL, NULL, '1234ABC', NULL, NULL, '2025-11-15', 0, 0, 0, 0, 0, 0, 0, 47, 1, 1, 3, 3, 5, 20, 10, 0, 4, 47, 304, 35, 43, 5050, 5960, 5720, 28, 0, 30, '18:58:00', 'Despacho Interno - Generado automáticamente', '2025-11-22 01:10:41', '2025-11-22 01:10:41'),
(113, 'Sergio Aguilar', NULL, 'Despacho Interno', NULL, NULL, NULL, NULL, '5678DEF', NULL, NULL, '2025-11-15', 0, 0, 0, 0, 0, 0, 0, 47, 1, 1, 3, 3, 5, 20, 10, 0, 4, 43, 296, 35, 39, 4550, 5980, 5570, 29, 0, 30, '22:45:00', 'Despacho Interno - Generado automáticamente', '2025-11-22 01:10:41', '2025-11-22 01:10:41'),
(114, 'Jasmani Aguilar', NULL, 'Despacho Interno', NULL, NULL, NULL, NULL, '9012GHI', NULL, NULL, '2025-11-15', 0, 0, 0, 0, 0, 0, 0, 47, 1, 1, 3, 3, 5, 20, 10, 0, 4, 41, 292, 41, 44, 5070, 5270, 5630, 26, 0, 30, '18:16:00', 'Despacho Interno - Generado automáticamente', '2025-11-22 01:10:41', '2025-11-22 01:10:41'),
(115, 'deybi aguilar', NULL, 'Despacho Interno', NULL, NULL, NULL, NULL, '1234ABC', NULL, NULL, '2025-11-17', 0, 0, 0, 0, 0, 0, 0, 47, 1, 1, 3, 3, 5, 20, 10, 0, 4, 41, 309, 40, 42, 5540, 5090, 6000, 31, 0, 30, '18:52:00', 'Despacho Interno - Generado automáticamente', '2025-11-22 01:10:41', '2025-11-22 01:10:41'),
(116, 'Sergio Aguilar', NULL, 'Despacho Interno', NULL, NULL, NULL, NULL, '5678DEF', NULL, NULL, '2025-11-17', 0, 0, 0, 0, 0, 0, 0, 47, 1, 1, 3, 3, 5, 20, 10, 0, 4, 48, 285, 40, 44, 4120, 5430, 5930, 29, 0, 30, '22:36:00', 'Despacho Interno - Generado automáticamente', '2025-11-22 01:10:41', '2025-11-22 01:10:41'),
(117, 'Jasmani Aguilar', NULL, 'Despacho Interno', NULL, NULL, NULL, NULL, '9012GHI', NULL, NULL, '2025-11-17', 0, 0, 0, 0, 0, 0, 0, 47, 1, 1, 3, 3, 5, 20, 10, 0, 4, 41, 312, 44, 44, 5630, 5910, 5020, 29, 0, 30, '22:54:00', 'Despacho Interno - Generado automáticamente', '2025-11-22 01:10:41', '2025-11-22 01:10:41'),
(118, 'deybi aguilar', NULL, 'Despacho Interno', NULL, NULL, NULL, NULL, '1234ABC', NULL, NULL, '2025-11-18', 0, 0, 0, 0, 0, 0, 0, 47, 1, 1, 3, 3, 5, 20, 10, 0, 4, 48, 285, 42, 40, 5640, 4030, 5550, 32, 0, 30, '20:54:00', 'Despacho Interno - Generado automáticamente', '2025-11-22 01:10:41', '2025-11-22 01:10:41'),
(119, 'Sergio Aguilar', NULL, 'Despacho Interno', NULL, NULL, NULL, NULL, '5678DEF', NULL, NULL, '2025-11-18', 0, 0, 0, 0, 0, 0, 0, 47, 1, 1, 3, 3, 5, 20, 10, 0, 4, 43, 295, 39, 38, 5300, 5270, 5350, 27, 0, 30, '00:12:00', 'Despacho Interno - Generado automáticamente', '2025-11-22 01:10:41', '2025-11-22 01:10:41'),
(120, 'Jasmani Aguilar', NULL, 'Despacho Interno', NULL, NULL, NULL, NULL, '9012GHI', NULL, NULL, '2025-11-18', 0, 0, 0, 0, 0, 0, 0, 47, 1, 1, 3, 3, 5, 20, 10, 0, 4, 41, 292, 42, 38, 4640, 4150, 4140, 34, 0, 30, '18:04:00', 'Despacho Interno - Generado automáticamente', '2025-11-22 01:10:41', '2025-11-22 01:10:41'),
(121, 'nano alvarez', 'deybi aguilar', 'Despacho Interno', NULL, NULL, NULL, NULL, '1234ABC', NULL, NULL, '2025-11-19', 0, 0, 0, 0, 0, 0, 0, 47, 1, 1, 3, 3, 5, 20, 10, 0, 4, 42, 301, 43, 44, 5900, 4630, 4890, 30, 0, 30, '18:05:00', 'Despacho Interno - Generado automáticamente', '2025-11-22 01:10:41', '2025-11-22 01:10:41'),
(122, 'joel buendia', 'Jasmani Aguilar', 'Despacho Interno', NULL, NULL, NULL, NULL, '5678DEF', NULL, NULL, '2025-11-19', 0, 0, 0, 0, 0, 0, 0, 47, 1, 1, 3, 3, 5, 20, 10, 0, 4, 48, 290, 38, 39, 5880, 4240, 4110, 26, 0, 30, '23:29:00', 'Despacho Interno - Generado automáticamente', '2025-11-22 01:10:41', '2025-11-22 01:10:41'),
(123, 'Antinio rodriguez', 'Jhonatan matias pizzo aguilar', 'Despacho Interno', NULL, NULL, NULL, NULL, '9012GHI', NULL, NULL, '2025-11-19', 0, 0, 0, 0, 0, 0, 0, 47, 1, 1, 3, 3, 5, 20, 10, 0, 4, 43, 304, 41, 42, 4400, 4040, 4490, 27, 0, 30, '19:37:00', 'Despacho Interno - Generado automáticamente', '2025-11-22 01:10:41', '2025-11-22 01:10:41'),
(124, 'nano alvarez', 'Sergio Aguilar', 'Despacho Interno', NULL, NULL, NULL, NULL, '1234ABC', NULL, NULL, '2025-11-20', 0, 0, 0, 0, 0, 0, 0, 47, 1, 1, 3, 3, 5, 20, 10, 0, 4, 41, 289, 38, 38, 5100, 5030, 5030, 35, 0, 30, '23:08:00', 'Despacho Interno - Generado automáticamente', '2025-11-22 01:10:41', '2025-11-22 01:10:41'),
(125, 'joel buendia', 'deybi aguilar', 'Despacho Interno', NULL, NULL, NULL, NULL, '5678DEF', NULL, NULL, '2025-11-20', 0, 0, 0, 0, 0, 0, 0, 47, 1, 1, 3, 3, 5, 20, 10, 0, 4, 41, 282, 37, 36, 5950, 4170, 5630, 28, 0, 30, '23:54:00', 'Despacho Interno - Generado automáticamente', '2025-11-22 01:10:41', '2025-11-22 01:10:41'),
(126, 'Antinio rodriguez', 'Jasmani Aguilar', 'Despacho Interno', NULL, NULL, NULL, NULL, '9012GHI', NULL, NULL, '2025-11-20', 0, 0, 0, 0, 0, 0, 0, 47, 1, 1, 3, 3, 5, 20, 10, 0, 4, 45, 289, 42, 43, 4680, 4930, 4650, 30, 0, 30, '20:23:00', 'Despacho Interno - Generado automáticamente', '2025-11-22 01:10:41', '2025-11-22 01:10:41'),
(127, 'nano alvarez', 'Jhonatan matias pizzo aguilar', 'Despacho Interno', NULL, NULL, NULL, NULL, '1234ABC', NULL, NULL, '2025-11-21', 0, 0, 0, 0, 0, 0, 0, 47, 1, 1, 3, 3, 5, 20, 10, 0, 4, 40, 302, 43, 44, 4160, 5460, 5090, 33, 0, 30, '21:33:00', 'Despacho Interno - Generado automáticamente', '2025-11-22 01:10:41', '2025-11-22 01:10:41'),
(128, 'joel buendia', 'Sergio Aguilar', 'Despacho Interno', NULL, NULL, NULL, NULL, '5678DEF', NULL, NULL, '2025-11-21', 0, 0, 0, 0, 0, 0, 0, 47, 1, 1, 3, 3, 5, 20, 10, 0, 4, 42, 318, 42, 40, 4410, 4100, 4500, 34, 0, 30, '22:57:00', 'Despacho Interno - Generado automáticamente', '2025-11-22 01:10:41', '2025-11-22 01:10:41'),
(129, 'Antinio rodriguez', 'deybi aguilar', 'Despacho Interno', NULL, NULL, NULL, NULL, '9012GHI', NULL, NULL, '2025-11-21', 0, 0, 0, 0, 0, 0, 0, 47, 1, 1, 3, 3, 5, 20, 10, 0, 4, 45, 302, 41, 45, 4130, 5110, 4600, 27, 0, 30, '20:27:00', 'Despacho Interno - Generado automáticamente', '2025-11-22 01:10:41', '2025-11-22 01:10:41'),
(130, 'nano alvarez', 'Jasmani Aguilar', 'Despacho Interno', NULL, NULL, NULL, NULL, '1869KLBs', NULL, NULL, '2025-11-21', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 10, 0, 0, 10, 0, 5, 5, 0, 10, 0, '18:47:00', NULL, '2025-11-22 01:47:29', '2025-11-22 01:47:29'),
(131, 'joel buendia', 'Jhonatan matias pizzo aguilar', 'Despacho Interno', NULL, NULL, NULL, NULL, '1869KLBs', NULL, NULL, '2025-11-21', 0, 0, 0, 0, 0, 0, 0, 1, 0, 0, 0, 0, 0, 1, 0, 0, 0, 2, 10, 5, 5, 10, 10, 10, 1, 2, 0, '19:54:00', NULL, '2025-11-22 01:55:22', '2025-11-22 01:55:22'),
(132, 'Antinio rodriguez', 'Sergio Aguilar', 'Despacho Interno', NULL, NULL, NULL, NULL, 'P-1234', NULL, NULL, '2025-11-24', 0, 0, 0, 0, 0, 0, 0, 133, 18, 30, 25, 8, 15, 10, 22, 5, 0, 30, 120, 90, 20, 50, 35, 70, 25, 0, 0, '18:30:00', 'Despacho lunes', '2025-11-26 06:44:33', '2025-11-26 06:44:33'),
(133, 'nano alvarez', 'deybi aguilar', 'Despacho Interno', NULL, NULL, NULL, NULL, 'P-5678', NULL, NULL, '2025-11-24', 0, 0, 0, 0, 0, 0, 0, 95, 12, 25, 20, 5, 12, 8, 10, 3, 0, 25, 95, 75, 15, 40, 28, 55, 18, 0, 0, '19:15:00', 'Despacho martes', NULL, NULL),
(134, 'joel buendia', 'Jasmani Aguilar', 'Despacho Interno', NULL, NULL, NULL, NULL, 'P-9012', NULL, NULL, '2025-11-24', 0, 0, 0, 0, 0, 0, 0, 110, 15, 28, 22, 10, 18, 5, 9, 3, 0, 28, 110, 85, 22, 55, 30, 60, 20, 0, 0, '20:00:00', 'Despacho miercoles', NULL, NULL),
(135, 'Antinio rodriguez', 'Jhonatan matias pizzo aguilar', 'Despacho Interno', NULL, NULL, NULL, NULL, 'P-3456', NULL, NULL, '2025-11-24', 0, 0, 0, 0, 0, 0, 0, 88, 10, 22, 18, 7, 10, 12, 7, 2, 0, 22, 88, 70, 18, 45, 32, 50, 15, 0, 0, '21:30:00', 'Despacho jueves', NULL, NULL),
(136, 'nano alvarez', 'Sergio Aguilar', 'Despacho Interno', NULL, NULL, NULL, NULL, 'P-1234', NULL, NULL, '2025-11-25', 0, 0, 0, 0, 0, 0, 0, 115, 20, 32, 28, 6, 12, 8, 7, 2, 0, 35, 125, 95, 18, 48, 32, 65, 22, 0, 0, '19:00:00', 'Despacho viernes', NULL, NULL),
(137, 'joel buendia', 'deybi aguilar', 'Despacho Interno', NULL, NULL, NULL, NULL, 'P-5678', NULL, NULL, '2025-11-25', 0, 0, 0, 0, 0, 0, 0, 92, 14, 26, 19, 8, 9, 6, 8, 2, 0, 27, 100, 78, 20, 42, 26, 58, 19, 0, 0, '20:30:00', 'Despacho sabado', NULL, NULL),
(138, 'Antinio rodriguez', 'Jasmani Aguilar', 'Despacho Interno', NULL, NULL, NULL, NULL, 'P-9012', NULL, NULL, '2025-11-25', 0, 0, 0, 0, 0, 0, 0, 105, 16, 30, 24, 9, 11, 7, 6, 2, 0, 32, 115, 88, 24, 52, 29, 62, 21, 0, 0, '22:00:00', 'Despacho lunes 25', NULL, NULL),
(139, 'nano alvarez', 'Jhonatan matias pizzo aguilar', 'Despacho Interno', NULL, NULL, NULL, NULL, 'P-3456', NULL, NULL, '2025-11-25', 0, 0, 0, 0, 0, 0, 0, 98, 13, 27, 21, 6, 14, 9, 5, 3, 0, 26, 105, 82, 19, 47, 31, 54, 17, 0, 0, '22:45:00', 'Despacho martes 25', NULL, NULL),
(140, 'nano alvarez', 'deybi aguilar', 'Despacho Interno', NULL, NULL, NULL, NULL, 'P-1234', NULL, NULL, '2025-11-26', 0, 0, 0, 0, 0, 0, 0, 250, 20, 50, 30, 15, 50, 35, 45, 3, 2, 70, 300, 200, 80, 450, 500, 400, 15, 10, 0, '19:30:00', 'Despacho auto 1', '2025-11-26 18:49:19', '2025-11-26 18:49:19'),
(141, 'joel buendia', 'Jasmani Aguilar', 'Despacho Externo', NULL, NULL, NULL, NULL, 'AUTO-002', NULL, NULL, '2025-11-26', 0, 0, 0, 0, 0, 0, 0, 18, 18, 45, 25, 12, 45, 30, 40, 2, 1, 45, 280, 180, 70, 420, 480, 380, 12, 8, 0, '21:15:00', 'Salida Auto 2', '2025-11-26 18:54:30', '2025-11-26 18:54:30'),
(142, 'Antinio rodriguez', 'Jhonatan matias pizzo aguilar', 'Despacho Externo', NULL, NULL, NULL, NULL, 'AUTO-003', NULL, NULL, '2025-11-26', 0, 0, 0, 0, 0, 0, 0, 15, 15, 40, 20, 10, 40, 25, 35, 1, 0, 20, 200, 160, 65, 400, 250, 350, 10, 5, 0, '23:30:00', 'Salida Auto 3', '2025-11-26 18:55:14', '2025-11-26 18:55:14'),
(143, 'Restaurante katu mayu', 'Jhonatan matias pizzo aguilar', 'Pedido Cliente', 'Restaurante katu mayu', 'tiquipaya   av.agua potable', '42255522', NULL, '1869KLBs', NULL, NULL, '2025-11-26', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 20, 0, 10, 0, 5, 0, '16:26:00', NULL, '2025-11-26 19:01:19', '2025-11-26 19:27:41'),
(144, 'joel buendia', 'Sergio Aguilar', 'Pedido Cliente', 'Doña rosa', 'tiquipaya', '76221881', NULL, '9012GHI', NULL, NULL, '2025-11-27', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 5, 50, 50, 50, 200, 100, 300, 10, 1, 0, '00:00:00', NULL, '2025-11-27 08:01:11', '2025-11-27 08:01:11'),
(145, 'joel buendia', 'deybi aguilar', 'Despacho Interno', NULL, NULL, NULL, NULL, '1234ABC', NULL, NULL, '2025-11-28', 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 10, 20, 20, 10, 10, 100, 200, 0, 5, 0, NULL, NULL, '2025-11-28 14:50:09', '2025-11-28 14:50:09'),
(174, 'Antonio Rodriguez', 'deybi aguilar', 'Despacho Interno', NULL, NULL, NULL, NULL, '1234ABC', NULL, NULL, '2025-12-01', 0, 0, 0, 0, 0, 0, 0, 154, 4, 7, 20, 14, 14, 34, 58, 0, 3, 41, 212, 191, 198, 448, 361, 520, 44, 10, 0, '22:18:00', 'Despacho diario - Vendidos: 1871 unidades - Retornos: 154', '2025-12-04 15:26:54', '2025-12-04 15:26:54'),
(175, 'joel buendia', 'Sergio Aguilar', 'Despacho Interno', NULL, NULL, NULL, NULL, '5678DEF', NULL, NULL, '2025-12-01', 0, 0, 0, 0, 0, 0, 0, 143, 11, 14, 4, 18, 33, 16, 40, 7, 0, 40, 219, 198, 183, 405, 351, 631, 43, 11, 0, '19:37:00', 'Despacho diario - Vendidos: 1938 unidades - Retornos: 143', '2025-12-04 15:26:54', '2025-12-04 15:26:54'),
(176, 'nano alvarez', 'Jasmani Aguilar', 'Despacho Interno', NULL, NULL, NULL, NULL, '9012GHI', NULL, NULL, '2025-12-01', 0, 0, 0, 0, 0, 0, 0, 160, 3, 17, 23, 28, 5, 44, 31, 9, 0, 48, 186, 220, 188, 407, 416, 562, 43, 11, 0, '19:03:00', 'Despacho diario - Vendidos: 1921 unidades - Retornos: 160', '2025-12-04 15:26:54', '2025-12-04 15:26:54'),
(177, 'Antonio Rodriguez', 'deybi aguilar', 'Despacho Interno', NULL, NULL, NULL, NULL, '1234ABC', NULL, NULL, '2025-12-02', 0, 0, 0, 0, 0, 0, 0, 212, 2, 23, 13, 18, 36, 44, 72, 3, 1, 40, 198, 181, 180, 401, 399, 589, 38, 10, 0, '18:21:00', 'Despacho diario - Vendidos: 1824 unidades - Retornos: 212', '2025-12-04 15:26:54', '2025-12-04 15:26:54'),
(178, 'joel buendia', 'Sergio Aguilar', 'Despacho Interno', NULL, NULL, NULL, NULL, '5678DEF', NULL, NULL, '2025-12-02', 0, 0, 0, 0, 0, 0, 0, 85, 2, 9, 5, 7, 0, 1, 59, 0, 2, 43, 194, 182, 182, 446, 383, 641, 45, 10, 0, '22:34:00', 'Despacho diario - Vendidos: 2041 unidades - Retornos: 85', '2025-12-04 15:26:54', '2025-12-04 15:26:54'),
(179, 'nano alvarez', 'Jasmani Aguilar', 'Despacho Interno', NULL, NULL, NULL, NULL, '9012GHI', NULL, NULL, '2025-12-02', 0, 0, 0, 0, 0, 0, 0, 108, 4, 9, 23, 1, 21, 17, 32, 1, 0, 50, 198, 194, 201, 419, 353, 537, 45, 9, 0, '18:29:00', 'Despacho diario - Vendidos: 1898 unidades - Retornos: 108', '2025-12-04 15:26:54', '2025-12-04 15:26:54'),
(180, 'Antonio Rodriguez', 'deybi aguilar', 'Despacho Interno', NULL, NULL, NULL, NULL, '1234ABC', NULL, NULL, '2025-12-03', 0, 0, 0, 0, 0, 0, 0, 139, 13, 18, 15, 23, 10, 1, 58, 0, 1, 48, 196, 183, 192, 408, 412, 514, 37, 12, 0, '23:49:00', 'Despacho diario - Vendidos: 1863 unidades - Retornos: 139', '2025-12-04 15:26:54', '2025-12-04 15:26:54'),
(181, 'joel buendia', 'Sergio Aguilar', 'Despacho Interno', NULL, NULL, NULL, NULL, '5678DEF', NULL, NULL, '2025-12-03', 0, 0, 0, 0, 0, 0, 0, 86, 6, 8, 17, 2, 14, 29, 4, 5, 1, 44, 204, 201, 189, 389, 355, 584, 35, 12, 0, '22:02:00', 'Despacho diario - Vendidos: 1927 unidades - Retornos: 86', '2025-12-04 15:26:54', '2025-12-04 15:26:54'),
(182, 'nano alvarez', 'Jasmani Aguilar', 'Despacho Interno', NULL, NULL, NULL, NULL, '9012GHI', NULL, NULL, '2025-12-03', 0, 0, 0, 0, 0, 0, 0, 203, 1, 21, 16, 25, 20, 43, 72, 2, 3, 46, 190, 218, 190, 389, 426, 640, 43, 12, 0, '21:55:00', 'Despacho diario - Vendidos: 1951 unidades - Retornos: 203', '2025-12-04 15:26:54', '2025-12-04 15:26:54'),
(183, 'Antonio Rodriguez', 'deybi aguilar', 'Despacho Interno', NULL, NULL, NULL, NULL, '1234ABC', NULL, NULL, '2025-12-04', 0, 0, 0, 0, 0, 0, 0, 137, 0, 0, 7, 2, 31, 27, 59, 9, 2, 42, 209, 210, 192, 415, 368, 646, 41, 9, 0, '22:19:00', 'Despacho diario - Vendidos: 1995 unidades - Retornos: 137', '2025-12-04 15:26:54', '2025-12-04 15:26:54'),
(184, 'joel buendia', 'Sergio Aguilar', 'Despacho Interno', NULL, NULL, NULL, NULL, '5678DEF', NULL, NULL, '2025-12-04', 0, 0, 0, 0, 0, 0, 0, 139, 8, 2, 27, 23, 24, 1, 44, 8, 2, 45, 218, 200, 187, 414, 361, 542, 38, 9, 0, '19:55:00', 'Despacho diario - Vendidos: 1875 unidades - Retornos: 139', '2025-12-04 15:26:54', '2025-12-04 15:26:54'),
(185, 'nano alvarez', 'Jasmani Aguilar', 'Despacho Interno', NULL, NULL, NULL, NULL, '9012GHI', NULL, NULL, '2025-12-04', 0, 0, 0, 0, 0, 0, 0, 88, 3, 9, 3, 21, 11, 4, 34, 3, 0, 41, 218, 187, 217, 410, 431, 553, 37, 11, 0, '19:22:00', 'Despacho diario - Vendidos: 2017 unidades - Retornos: 88', '2025-12-04 15:26:54', '2025-12-04 15:26:54'),
(186, 'Antonio Rodriguez', 'deybi aguilar', 'Despacho Interno', NULL, NULL, NULL, NULL, '1234ABC', NULL, NULL, '2025-12-05', 0, 0, 0, 0, 0, 0, 0, 196, 1, 29, 28, 15, 43, 39, 30, 10, 1, 44, 200, 213, 184, 441, 364, 639, 41, 11, 0, '21:22:00', 'Despacho diario - Vendidos: 1941 unidades - Retornos: 196', '2025-12-04 15:26:54', '2025-12-04 15:26:54'),
(187, 'joel buendia', 'Sergio Aguilar', 'Despacho Interno', NULL, NULL, NULL, NULL, '5678DEF', NULL, NULL, '2025-12-05', 0, 0, 0, 0, 0, 0, 0, 186, 4, 12, 24, 26, 16, 35, 68, 1, 0, 49, 214, 208, 183, 378, 417, 517, 38, 12, 0, '20:00:00', 'Despacho diario - Vendidos: 1830 unidades - Retornos: 186', '2025-12-04 15:26:54', '2025-12-04 15:26:54'),
(188, 'nano alvarez', 'Jasmani Aguilar', 'Despacho Interno', NULL, NULL, NULL, NULL, '9012GHI', NULL, NULL, '2025-12-05', 0, 0, 0, 0, 0, 0, 0, 201, 8, 26, 26, 25, 3, 44, 60, 6, 3, 44, 219, 197, 188, 366, 447, 572, 38, 11, 0, '22:25:00', 'Despacho diario - Vendidos: 1881 unidades - Retornos: 201', '2025-12-04 15:26:54', '2025-12-04 15:26:54'),
(189, 'Antonio Rodriguez', 'deybi aguilar', 'Despacho Interno', NULL, NULL, NULL, NULL, '1234ABC', NULL, NULL, '2025-12-06', 0, 0, 0, 0, 0, 0, 0, 106, 12, 24, 19, 4, 16, 12, 16, 2, 1, 44, 204, 183, 214, 427, 415, 530, 40, 12, 0, '22:18:00', 'Despacho diario - Vendidos: 1963 unidades - Retornos: 106', '2025-12-04 15:26:54', '2025-12-04 15:26:54'),
(190, 'joel buendia', 'Sergio Aguilar', 'Despacho Interno', NULL, NULL, NULL, NULL, '5678DEF', NULL, NULL, '2025-12-06', 0, 0, 0, 0, 0, 0, 0, 125, 9, 12, 21, 11, 2, 0, 59, 9, 2, 43, 190, 194, 196, 410, 449, 636, 45, 8, 0, '22:18:00', 'Despacho diario - Vendidos: 2046 unidades - Retornos: 125', '2025-12-04 15:26:54', '2025-12-04 15:26:54'),
(191, 'nano alvarez', 'Jasmani Aguilar', 'Despacho Interno', NULL, NULL, NULL, NULL, '9012GHI', NULL, NULL, '2025-12-06', 0, 0, 0, 0, 0, 0, 0, 186, 1, 13, 10, 18, 30, 28, 79, 4, 3, 45, 180, 200, 197, 417, 355, 622, 37, 12, 0, '18:56:00', 'Despacho diario - Vendidos: 1879 unidades - Retornos: 186', '2025-12-04 15:26:54', '2025-12-04 15:26:54'),
(192, 'Antonio Rodriguez', 'deybi aguilar', 'Despacho Interno', NULL, NULL, NULL, NULL, '1234ABC', NULL, NULL, '2025-12-08', 0, 0, 0, 0, 0, 0, 0, 215, 12, 19, 23, 18, 46, 14, 80, 3, 0, 41, 199, 212, 195, 449, 360, 563, 35, 9, 0, '20:32:00', 'Despacho diario - Vendidos: 1848 unidades - Retornos: 215', '2025-12-04 15:26:54', '2025-12-04 15:26:54'),
(193, 'joel buendia', 'Sergio Aguilar', 'Despacho Interno', NULL, NULL, NULL, NULL, '5678DEF', NULL, NULL, '2025-12-08', 0, 0, 0, 0, 0, 0, 0, 118, 6, 25, 29, 10, 17, 26, 5, 0, 0, 45, 206, 194, 209, 400, 383, 507, 45, 9, 0, '19:32:00', 'Despacho diario - Vendidos: 1880 unidades - Retornos: 118', '2025-12-04 15:26:54', '2025-12-04 15:26:54'),
(194, 'nano alvarez', 'Jasmani Aguilar', 'Despacho Interno', NULL, NULL, NULL, NULL, '9012GHI', NULL, NULL, '2025-12-08', 0, 0, 0, 0, 0, 0, 0, 111, 4, 8, 6, 10, 21, 31, 26, 3, 2, 44, 207, 180, 185, 377, 429, 528, 45, 11, 0, '21:47:00', 'Despacho diario - Vendidos: 1895 unidades - Retornos: 111', '2025-12-04 15:26:54', '2025-12-04 15:26:54'),
(195, 'Antonio Rodriguez', 'deybi aguilar', 'Despacho Interno', NULL, NULL, NULL, NULL, '1234ABC', NULL, NULL, '2025-12-09', 0, 0, 0, 0, 0, 0, 0, 176, 8, 23, 1, 22, 12, 49, 53, 7, 1, 41, 205, 206, 209, 364, 429, 503, 38, 12, 0, '23:13:00', 'Despacho diario - Vendidos: 1831 unidades - Retornos: 176', '2025-12-04 15:26:54', '2025-12-04 15:26:54'),
(196, 'joel buendia', 'Sergio Aguilar', 'Despacho Interno', NULL, NULL, NULL, NULL, '5678DEF', NULL, NULL, '2025-12-09', 0, 0, 0, 0, 0, 0, 0, 158, 3, 28, 2, 22, 14, 11, 75, 2, 1, 45, 198, 200, 184, 448, 360, 538, 40, 12, 0, '23:12:00', 'Despacho diario - Vendidos: 1867 unidades - Retornos: 158', '2025-12-04 15:26:54', '2025-12-04 15:26:54'),
(197, 'nano alvarez', 'Jasmani Aguilar', 'Despacho Interno', NULL, NULL, NULL, NULL, '9012GHI', NULL, NULL, '2025-12-09', 0, 0, 0, 0, 0, 0, 0, 198, 7, 16, 18, 28, 5, 39, 79, 5, 1, 42, 216, 186, 199, 403, 435, 574, 43, 9, 0, '20:58:00', 'Despacho diario - Vendidos: 1909 unidades - Retornos: 198', '2025-12-04 15:26:54', '2025-12-04 15:26:54'),
(198, 'Antonio Rodriguez', 'deybi aguilar', 'Despacho Interno', NULL, NULL, NULL, NULL, '1234ABC', NULL, NULL, '2025-12-10', 0, 0, 0, 0, 0, 0, 0, 131, 9, 10, 24, 25, 10, 20, 27, 6, 0, 48, 188, 199, 192, 360, 443, 620, 42, 8, 0, '23:07:00', 'Despacho diario - Vendidos: 1969 unidades - Retornos: 131', '2025-12-04 15:26:54', '2025-12-04 15:26:54'),
(199, 'joel buendia', 'Sergio Aguilar', 'Despacho Interno', NULL, NULL, NULL, NULL, '5678DEF', NULL, NULL, '2025-12-10', 0, 0, 0, 0, 0, 0, 0, 178, 5, 17, 11, 4, 48, 20, 65, 6, 2, 40, 194, 216, 219, 415, 399, 587, 43, 12, 0, '21:45:00', 'Despacho diario - Vendidos: 1947 unidades - Retornos: 178', '2025-12-04 15:26:54', '2025-12-04 15:26:54'),
(200, 'nano alvarez', 'Jasmani Aguilar', 'Despacho Interno', NULL, NULL, NULL, NULL, '9012GHI', NULL, NULL, '2025-12-10', 0, 0, 0, 0, 0, 0, 0, 161, 7, 7, 3, 9, 34, 39, 58, 3, 1, 47, 190, 195, 197, 385, 435, 555, 39, 10, 0, '18:43:00', 'Despacho diario - Vendidos: 1892 unidades - Retornos: 161', '2025-12-04 15:26:54', '2025-12-04 15:26:54'),
(201, 'Antonio Rodriguez', 'deybi aguilar', 'Despacho Interno', NULL, NULL, NULL, NULL, '1234ABC', NULL, NULL, '2025-12-11', 0, 0, 0, 0, 0, 0, 0, 132, 8, 7, 14, 16, 23, 1, 51, 10, 2, 46, 186, 197, 180, 429, 417, 613, 44, 9, 0, '18:25:00', 'Despacho diario - Vendidos: 1989 unidades - Retornos: 132', '2025-12-04 15:26:54', '2025-12-04 15:26:54'),
(202, 'joel buendia', 'Sergio Aguilar', 'Despacho Interno', NULL, NULL, NULL, NULL, '5678DEF', NULL, NULL, '2025-12-11', 0, 0, 0, 0, 0, 0, 0, 93, 8, 6, 2, 21, 35, 8, 7, 5, 1, 47, 188, 194, 208, 377, 410, 644, 40, 8, 0, '19:54:00', 'Despacho diario - Vendidos: 2023 unidades - Retornos: 93', '2025-12-04 15:26:54', '2025-12-04 15:26:54'),
(203, 'nano alvarez', 'Jasmani Aguilar', 'Despacho Interno', NULL, NULL, NULL, NULL, '9012GHI', NULL, NULL, '2025-12-11', 0, 0, 0, 0, 0, 0, 0, 141, 8, 4, 19, 6, 48, 20, 28, 8, 0, 46, 196, 199, 185, 441, 418, 543, 38, 9, 0, '20:37:00', 'Despacho diario - Vendidos: 1934 unidades - Retornos: 141', '2025-12-04 15:26:54', '2025-12-04 15:26:54'),
(204, 'Antonio Rodriguez', 'deybi aguilar', 'Despacho Interno', NULL, NULL, NULL, NULL, '1234ABC', NULL, NULL, '2025-12-12', 0, 0, 0, 0, 0, 0, 0, 147, 12, 21, 4, 3, 4, 27, 65, 9, 2, 49, 190, 183, 209, 387, 406, 540, 45, 10, 0, '19:05:00', 'Despacho diario - Vendidos: 1872 unidades - Retornos: 147', '2025-12-04 15:26:54', '2025-12-04 15:26:54'),
(205, 'joel buendia', 'Sergio Aguilar', 'Despacho Interno', NULL, NULL, NULL, NULL, '5678DEF', NULL, NULL, '2025-12-12', 0, 0, 0, 0, 0, 0, 0, 139, 8, 25, 10, 7, 20, 36, 30, 0, 3, 41, 185, 202, 181, 444, 362, 518, 43, 10, 0, '20:56:00', 'Despacho diario - Vendidos: 1847 unidades - Retornos: 139', '2025-12-04 15:26:54', '2025-12-04 15:26:54'),
(206, 'nano alvarez', 'Jasmani Aguilar', 'Despacho Interno', NULL, NULL, NULL, NULL, '9012GHI', NULL, NULL, '2025-12-12', 0, 0, 0, 0, 0, 0, 0, 185, 4, 0, 28, 25, 43, 35, 42, 7, 1, 44, 186, 189, 180, 441, 443, 648, 45, 12, 0, '21:55:00', 'Despacho diario - Vendidos: 2003 unidades - Retornos: 185', '2025-12-04 15:26:54', '2025-12-04 15:26:54'),
(207, 'Antonio Rodriguez', 'deybi aguilar', 'Despacho Interno', NULL, NULL, NULL, NULL, '1234ABC', NULL, NULL, '2025-12-13', 0, 0, 0, 0, 0, 0, 0, 123, 5, 23, 18, 15, 21, 27, 13, 0, 1, 50, 202, 191, 180, 427, 391, 509, 41, 8, 0, '22:24:00', 'Despacho diario - Vendidos: 1876 unidades - Retornos: 123', '2025-12-04 15:26:54', '2025-12-04 15:26:54'),
(208, 'joel buendia', 'Sergio Aguilar', 'Despacho Interno', NULL, NULL, NULL, NULL, '5678DEF', NULL, NULL, '2025-12-13', 0, 0, 0, 0, 0, 0, 0, 132, 7, 26, 8, 0, 2, 43, 34, 9, 3, 48, 202, 200, 191, 401, 417, 521, 37, 11, 0, '22:27:00', 'Despacho diario - Vendidos: 1896 unidades - Retornos: 132', '2025-12-04 15:26:54', '2025-12-04 15:26:54'),
(209, 'nano alvarez', 'Jasmani Aguilar', 'Despacho Interno', NULL, NULL, NULL, NULL, '9012GHI', NULL, NULL, '2025-12-13', 0, 0, 0, 0, 0, 0, 0, 159, 7, 14, 28, 12, 45, 37, 13, 1, 2, 49, 218, 187, 207, 446, 392, 548, 36, 8, 0, '22:42:00', 'Despacho diario - Vendidos: 1932 unidades - Retornos: 159', '2025-12-04 15:26:54', '2025-12-04 15:26:54'),
(210, 'Antonio Rodriguez', 'deybi aguilar', 'Despacho Interno', NULL, NULL, NULL, NULL, '1234ABC', NULL, NULL, '2025-12-15', 0, 0, 0, 0, 0, 0, 0, 195, 8, 23, 26, 7, 46, 3, 75, 7, 0, 43, 220, 217, 184, 416, 446, 639, 43, 12, 0, '19:43:00', 'Despacho diario - Vendidos: 2025 unidades - Retornos: 195', '2025-12-04 15:26:54', '2025-12-04 15:26:54'),
(211, 'joel buendia', 'Sergio Aguilar', 'Despacho Interno', NULL, NULL, NULL, NULL, '5678DEF', NULL, NULL, '2025-12-15', 0, 0, 0, 0, 0, 0, 0, 140, 13, 12, 19, 26, 27, 0, 39, 3, 1, 44, 220, 183, 180, 371, 405, 608, 36, 9, 0, '21:59:00', 'Despacho diario - Vendidos: 1916 unidades - Retornos: 140', '2025-12-04 15:26:54', '2025-12-04 15:26:54'),
(212, 'nano alvarez', 'Jasmani Aguilar', 'Despacho Interno', NULL, NULL, NULL, NULL, '9012GHI', NULL, NULL, '2025-12-15', 0, 0, 0, 0, 0, 0, 0, 153, 12, 20, 23, 16, 45, 12, 22, 1, 2, 41, 201, 180, 184, 439, 361, 545, 35, 12, 0, '21:01:00', 'Despacho diario - Vendidos: 1845 unidades - Retornos: 153', '2025-12-04 15:26:54', '2025-12-04 15:26:54'),
(213, 'Antonio Rodriguez', 'deybi aguilar', 'Despacho Interno', NULL, NULL, NULL, NULL, '1234ABC', NULL, NULL, '2025-12-16', 0, 0, 0, 0, 0, 0, 0, 128, 11, 16, 16, 4, 30, 19, 29, 2, 1, 44, 219, 191, 187, 385, 356, 595, 45, 10, 0, '21:36:00', 'Despacho diario - Vendidos: 1904 unidades - Retornos: 128', '2025-12-04 15:26:54', '2025-12-04 15:26:54'),
(214, 'joel buendia', 'Sergio Aguilar', 'Despacho Interno', NULL, NULL, NULL, NULL, '5678DEF', NULL, NULL, '2025-12-16', 0, 0, 0, 0, 0, 0, 0, 84, 6, 13, 1, 13, 5, 31, 10, 4, 1, 49, 205, 189, 197, 384, 372, 581, 37, 10, 0, '18:49:00', 'Despacho diario - Vendidos: 1940 unidades - Retornos: 84', '2025-12-04 15:26:54', '2025-12-04 15:26:54'),
(215, 'nano alvarez', 'Jasmani Aguilar', 'Despacho Interno', NULL, NULL, NULL, NULL, '9012GHI', NULL, NULL, '2025-12-16', 0, 0, 0, 0, 0, 0, 0, 120, 4, 0, 13, 27, 2, 16, 58, 0, 0, 43, 180, 199, 182, 448, 389, 613, 35, 8, 0, '22:16:00', 'Despacho diario - Vendidos: 1977 unidades - Retornos: 120', '2025-12-04 15:26:54', '2025-12-04 15:26:54'),
(216, 'Antonio Rodriguez', 'deybi aguilar', 'Despacho Interno', NULL, NULL, NULL, NULL, '1234ABC', NULL, NULL, '2025-12-17', 0, 0, 0, 0, 0, 0, 0, 99, 11, 5, 8, 1, 13, 32, 25, 1, 3, 41, 183, 189, 196, 372, 437, 643, 43, 11, 0, '19:32:00', 'Despacho diario - Vendidos: 2016 unidades - Retornos: 99', '2025-12-04 15:26:54', '2025-12-04 15:26:54'),
(217, 'joel buendia', 'Sergio Aguilar', 'Despacho Interno', NULL, NULL, NULL, NULL, '5678DEF', NULL, NULL, '2025-12-17', 0, 0, 0, 0, 0, 0, 0, 45, 2, 0, 28, 2, 1, 5, 1, 5, 1, 50, 215, 208, 206, 409, 359, 612, 37, 9, 0, '21:19:00', 'Despacho diario - Vendidos: 2060 unidades - Retornos: 45', '2025-12-04 15:26:54', '2025-12-04 15:26:54'),
(218, 'nano alvarez', 'Jasmani Aguilar', 'Despacho Interno', NULL, NULL, NULL, NULL, '9012GHI', NULL, NULL, '2025-12-17', 0, 0, 0, 0, 0, 0, 0, 107, 8, 24, 11, 21, 30, 0, 7, 6, 0, 41, 184, 209, 183, 418, 360, 537, 35, 8, 0, '19:48:00', 'Despacho diario - Vendidos: 1868 unidades - Retornos: 107', '2025-12-04 15:26:54', '2025-12-04 15:26:54'),
(219, 'Antonio Rodriguez', 'deybi aguilar', 'Despacho Interno', NULL, NULL, NULL, NULL, '1234ABC', NULL, NULL, '2025-12-18', 0, 0, 0, 0, 0, 0, 0, 134, 2, 3, 5, 24, 16, 29, 48, 7, 0, 41, 217, 206, 204, 383, 450, 561, 45, 8, 0, '23:18:00', 'Despacho diario - Vendidos: 1981 unidades - Retornos: 134', '2025-12-04 15:26:54', '2025-12-04 15:26:54'),
(220, 'joel buendia', 'Sergio Aguilar', 'Despacho Interno', NULL, NULL, NULL, NULL, '5678DEF', NULL, NULL, '2025-12-18', 0, 0, 0, 0, 0, 0, 0, 153, 6, 14, 22, 6, 33, 3, 61, 7, 1, 42, 182, 189, 201, 369, 376, 521, 38, 9, 0, '23:21:00', 'Despacho diario - Vendidos: 1774 unidades - Retornos: 153', '2025-12-04 15:26:54', '2025-12-04 15:26:54'),
(221, 'nano alvarez', 'Jasmani Aguilar', 'Despacho Interno', NULL, NULL, NULL, NULL, '9012GHI', NULL, NULL, '2025-12-18', 0, 0, 0, 0, 0, 0, 0, 151, 3, 19, 9, 4, 22, 44, 42, 6, 2, 43, 183, 207, 201, 436, 382, 505, 45, 11, 0, '18:46:00', 'Despacho diario - Vendidos: 1862 unidades - Retornos: 151', '2025-12-04 15:26:54', '2025-12-04 15:26:54'),
(222, 'Antonio Rodriguez', 'deybi aguilar', 'Despacho Interno', NULL, NULL, NULL, NULL, '1234ABC', NULL, NULL, '2025-12-19', 0, 0, 0, 0, 0, 0, 0, 141, 11, 24, 6, 29, 2, 20, 47, 2, 0, 49, 205, 194, 214, 438, 390, 637, 35, 8, 0, '23:41:00', 'Despacho diario - Vendidos: 2029 unidades - Retornos: 141', '2025-12-04 15:26:54', '2025-12-04 15:26:54'),
(223, 'joel buendia', 'Sergio Aguilar', 'Despacho Interno', NULL, NULL, NULL, NULL, '5678DEF', NULL, NULL, '2025-12-19', 0, 0, 0, 0, 0, 0, 0, 142, 12, 0, 5, 28, 29, 13, 47, 8, 0, 45, 207, 184, 188, 426, 389, 593, 44, 8, 0, '22:00:00', 'Despacho diario - Vendidos: 1942 unidades - Retornos: 142', '2025-12-04 15:26:54', '2025-12-04 15:26:54'),
(224, 'nano alvarez', 'Jasmani Aguilar', 'Despacho Interno', NULL, NULL, NULL, NULL, '9012GHI', NULL, NULL, '2025-12-19', 0, 0, 0, 0, 0, 0, 0, 96, 1, 11, 1, 2, 8, 37, 31, 2, 3, 43, 217, 183, 190, 362, 407, 624, 35, 10, 0, '23:07:00', 'Despacho diario - Vendidos: 1975 unidades - Retornos: 96', '2025-12-04 15:26:54', '2025-12-04 15:26:54'),
(225, 'Antonio Rodriguez', 'deybi aguilar', 'Despacho Interno', NULL, NULL, NULL, NULL, '1234ABC', NULL, NULL, '2025-12-20', 0, 0, 0, 0, 0, 0, 0, 144, 0, 18, 10, 20, 11, 19, 61, 2, 3, 43, 204, 209, 192, 417, 405, 522, 40, 11, 0, '18:43:00', 'Despacho diario - Vendidos: 1899 unidades - Retornos: 144', '2025-12-04 15:26:54', '2025-12-04 15:26:54'),
(226, 'joel buendia', 'Sergio Aguilar', 'Despacho Interno', NULL, NULL, NULL, NULL, '5678DEF', NULL, NULL, '2025-12-20', 0, 0, 0, 0, 0, 0, 0, 164, 10, 26, 9, 25, 34, 29, 26, 4, 1, 44, 182, 220, 203, 369, 399, 514, 43, 11, 0, '22:07:00', 'Despacho diario - Vendidos: 1821 unidades - Retornos: 164', '2025-12-04 15:26:54', '2025-12-04 15:26:54'),
(227, 'nano alvarez', 'Jasmani Aguilar', 'Despacho Interno', NULL, NULL, NULL, NULL, '9012GHI', NULL, NULL, '2025-12-20', 0, 0, 0, 0, 0, 0, 0, 108, 1, 11, 9, 0, 31, 21, 24, 9, 2, 41, 190, 209, 190, 361, 401, 537, 44, 11, 0, '23:38:00', 'Despacho diario - Vendidos: 1876 unidades - Retornos: 108', '2025-12-04 15:26:54', '2025-12-04 15:26:54'),
(228, 'Antonio Rodriguez', 'deybi aguilar', 'Despacho Interno', NULL, NULL, NULL, NULL, '1234ABC', NULL, NULL, '2025-12-22', 0, 0, 0, 0, 0, 0, 0, 139, 13, 3, 25, 28, 19, 2, 41, 8, 0, 46, 206, 196, 191, 405, 352, 646, 37, 8, 0, '20:25:00', 'Despacho diario - Vendidos: 1948 unidades - Retornos: 139', '2025-12-04 15:26:54', '2025-12-04 15:26:54'),
(229, 'joel buendia', 'Sergio Aguilar', 'Despacho Interno', NULL, NULL, NULL, NULL, '5678DEF', NULL, NULL, '2025-12-22', 0, 0, 0, 0, 0, 0, 0, 73, 5, 21, 3, 13, 2, 17, 4, 6, 2, 46, 219, 189, 197, 446, 365, 527, 40, 8, 0, '23:44:00', 'Despacho diario - Vendidos: 1964 unidades - Retornos: 73', '2025-12-04 15:26:54', '2025-12-04 15:26:54'),
(230, 'nano alvarez', 'Jasmani Aguilar', 'Despacho Interno', NULL, NULL, NULL, NULL, '9012GHI', NULL, NULL, '2025-12-22', 0, 0, 0, 0, 0, 0, 0, 80, 5, 1, 12, 9, 38, 4, 6, 3, 2, 48, 188, 204, 190, 392, 409, 553, 40, 8, 0, '19:42:00', 'Despacho diario - Vendidos: 1952 unidades - Retornos: 80', '2025-12-04 15:26:54', '2025-12-04 15:26:54'),
(231, 'Antonio Rodriguez', 'deybi aguilar', 'Despacho Interno', NULL, NULL, NULL, NULL, '1234ABC', NULL, NULL, '2025-12-23', 0, 0, 0, 0, 0, 0, 0, 113, 12, 1, 23, 16, 6, 18, 31, 4, 2, 43, 207, 208, 201, 415, 388, 570, 35, 9, 0, '20:53:00', 'Despacho diario - Vendidos: 1963 unidades - Retornos: 113', '2025-12-04 15:26:54', '2025-12-04 15:26:54'),
(232, 'joel buendia', 'Sergio Aguilar', 'Despacho Interno', NULL, NULL, NULL, NULL, '5678DEF', NULL, NULL, '2025-12-23', 0, 0, 0, 0, 0, 0, 0, 137, 6, 22, 16, 6, 42, 21, 11, 10, 3, 49, 202, 183, 220, 378, 397, 526, 41, 10, 0, '23:07:00', 'Despacho diario - Vendidos: 1869 unidades - Retornos: 137', '2025-12-04 15:26:54', '2025-12-04 15:26:54'),
(233, 'nano alvarez', 'Jasmani Aguilar', 'Despacho Interno', NULL, NULL, NULL, NULL, '9012GHI', NULL, NULL, '2025-12-23', 0, 0, 0, 0, 0, 0, 0, 146, 4, 5, 7, 17, 30, 40, 33, 9, 1, 41, 187, 193, 216, 425, 353, 620, 36, 11, 0, '19:33:00', 'Despacho diario - Vendidos: 1936 unidades - Retornos: 146', '2025-12-04 15:26:54', '2025-12-04 15:26:54'),
(234, 'Antonio Rodriguez', 'deybi aguilar', 'Despacho Interno', NULL, NULL, NULL, NULL, '1234ABC', NULL, NULL, '2025-12-26', 0, 0, 0, 0, 0, 0, 0, 94, 12, 18, 16, 7, 13, 14, 7, 7, 0, 42, 205, 192, 182, 445, 418, 604, 39, 8, 0, '22:12:00', 'Despacho diario - Vendidos: 2041 unidades - Retornos: 94', '2025-12-04 15:26:54', '2025-12-04 15:26:54'),
(235, 'joel buendia', 'Sergio Aguilar', 'Despacho Interno', NULL, NULL, NULL, NULL, '5678DEF', NULL, NULL, '2025-12-26', 0, 0, 0, 0, 0, 0, 0, 173, 12, 16, 13, 2, 37, 15, 67, 8, 3, 40, 214, 191, 184, 435, 426, 574, 35, 10, 0, '23:09:00', 'Despacho diario - Vendidos: 1936 unidades - Retornos: 173', '2025-12-04 15:26:54', '2025-12-04 15:26:54'),
(236, 'nano alvarez', 'Jasmani Aguilar', 'Despacho Interno', NULL, NULL, NULL, NULL, '9012GHI', NULL, NULL, '2025-12-26', 0, 0, 0, 0, 0, 0, 0, 155, 2, 5, 22, 22, 3, 41, 49, 9, 2, 48, 195, 198, 214, 421, 376, 555, 43, 10, 0, '20:09:00', 'Despacho diario - Vendidos: 1905 unidades - Retornos: 155', '2025-12-04 15:26:54', '2025-12-04 15:26:54'),
(237, 'Antonio Rodriguez', 'deybi aguilar', 'Despacho Interno', NULL, NULL, NULL, NULL, '1234ABC', NULL, NULL, '2025-12-27', 0, 0, 0, 0, 0, 0, 0, 135, 5, 9, 12, 13, 19, 14, 60, 2, 1, 43, 218, 208, 184, 409, 446, 571, 39, 8, 0, '22:02:00', 'Despacho diario - Vendidos: 1991 unidades - Retornos: 135', '2025-12-04 15:26:54', '2025-12-04 15:26:54'),
(238, 'joel buendia', 'Sergio Aguilar', 'Despacho Interno', NULL, NULL, NULL, NULL, '5678DEF', NULL, NULL, '2025-12-27', 0, 0, 0, 0, 0, 0, 0, 139, 5, 8, 0, 2, 38, 33, 52, 1, 0, 47, 195, 193, 192, 393, 381, 585, 36, 8, 0, '20:31:00', 'Despacho diario - Vendidos: 1891 unidades - Retornos: 139', '2025-12-04 15:26:54', '2025-12-04 15:26:54'),
(239, 'nano alvarez', 'Jasmani Aguilar', 'Despacho Interno', NULL, NULL, NULL, NULL, '9012GHI', NULL, NULL, '2025-12-27', 0, 0, 0, 0, 0, 0, 0, 131, 0, 10, 13, 5, 46, 4, 52, 1, 0, 42, 214, 215, 182, 435, 382, 534, 38, 12, 0, '18:55:00', 'Despacho diario - Vendidos: 1923 unidades - Retornos: 131', '2025-12-04 15:26:54', '2025-12-04 15:26:54'),
(240, 'Antonio Rodriguez', 'deybi aguilar', 'Despacho Interno', NULL, NULL, NULL, NULL, '1234ABC', NULL, NULL, '2025-12-29', 0, 0, 0, 0, 0, 0, 0, 90, 6, 19, 0, 2, 32, 1, 26, 3, 1, 42, 189, 204, 215, 388, 377, 537, 40, 9, 0, '22:34:00', 'Despacho diario - Vendidos: 1911 unidades - Retornos: 90', '2025-12-04 15:26:54', '2025-12-04 15:26:54'),
(241, 'joel buendia', 'Sergio Aguilar', 'Despacho Interno', NULL, NULL, NULL, NULL, '5678DEF', NULL, NULL, '2025-12-29', 0, 0, 0, 0, 0, 0, 0, 119, 7, 9, 5, 12, 40, 3, 39, 3, 1, 49, 195, 189, 214, 431, 381, 518, 38, 11, 0, '23:29:00', 'Despacho diario - Vendidos: 1907 unidades - Retornos: 119', '2025-12-04 15:26:54', '2025-12-04 15:26:54'),
(242, 'nano alvarez', 'Jasmani Aguilar', 'Despacho Interno', NULL, NULL, NULL, NULL, '9012GHI', NULL, NULL, '2025-12-29', 0, 0, 0, 0, 0, 0, 0, 134, 8, 26, 5, 21, 5, 11, 55, 1, 2, 45, 213, 215, 181, 442, 436, 526, 36, 8, 0, '23:12:00', 'Despacho diario - Vendidos: 1968 unidades - Retornos: 134', '2025-12-04 15:26:54', '2025-12-04 15:26:54'),
(243, 'Antonio Rodriguez', 'deybi aguilar', 'Despacho Interno', NULL, NULL, NULL, NULL, '1234ABC', NULL, NULL, '2025-12-30', 0, 0, 0, 0, 0, 0, 0, 158, 5, 6, 22, 21, 17, 20, 58, 7, 2, 42, 204, 195, 184, 407, 415, 502, 43, 9, 0, '22:56:00', 'Despacho diario - Vendidos: 1843 unidades - Retornos: 158', '2025-12-04 15:26:54', '2025-12-04 15:26:54'),
(244, 'joel buendia', 'Sergio Aguilar', 'Despacho Interno', NULL, NULL, NULL, NULL, '5678DEF', NULL, NULL, '2025-12-30', 0, 0, 0, 0, 0, 0, 0, 168, 6, 22, 3, 21, 43, 25, 42, 4, 2, 43, 212, 220, 211, 438, 408, 506, 41, 12, 0, '19:05:00', 'Despacho diario - Vendidos: 1923 unidades - Retornos: 168', '2025-12-04 15:26:54', '2025-12-04 15:26:54'),
(245, 'nano alvarez', 'Jasmani Aguilar', 'Despacho Interno', NULL, NULL, NULL, NULL, '9012GHI', NULL, NULL, '2025-12-30', 0, 0, 0, 0, 0, 0, 0, 89, 4, 0, 28, 16, 28, 0, 8, 3, 2, 41, 213, 191, 214, 358, 433, 539, 36, 10, 0, '18:06:00', 'Despacho diario - Vendidos: 1946 unidades - Retornos: 89', '2025-12-04 15:26:54', '2025-12-04 15:26:54');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `control_tanques_agua`
--

CREATE TABLE `control_tanques_agua` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `fecha_limpieza` date NOT NULL,
  `nombre_tanque` varchar(255) NOT NULL,
  `capacidad_litros` decimal(10,2) DEFAULT NULL,
  `procedimiento_limpieza` text DEFAULT NULL,
  `productos_desinfeccion` text DEFAULT NULL,
  `id_responsable` bigint(20) UNSIGNED DEFAULT NULL,
  `id_supervisor` bigint(20) UNSIGNED DEFAULT NULL,
  `responsable_texto` varchar(255) NOT NULL,
  `supervisor_texto` varchar(255) DEFAULT NULL,
  `proxima_limpieza` date DEFAULT NULL,
  `observaciones` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `control_tanques_agua`
--

INSERT INTO `control_tanques_agua` (`id`, `fecha_limpieza`, `nombre_tanque`, `capacidad_litros`, `procedimiento_limpieza`, `productos_desinfeccion`, `id_responsable`, `id_supervisor`, `responsable_texto`, `supervisor_texto`, `proxima_limpieza`, `observaciones`, `created_at`, `updated_at`) VALUES
(1, '2025-11-27', '3 tanques negros', 5000.00, 'limpieza y desifeccion', 'ace, lavandina', NULL, NULL, 'Anderson  Aguilar', 'Lucia Cruz Farfan', '2026-01-26', NULL, '2025-11-27 07:24:26', '2025-11-27 07:24:26');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historial_reportes`
--

CREATE TABLE `historial_reportes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tipo` varchar(255) NOT NULL,
  `fecha_inicio` date DEFAULT NULL,
  `fecha_fin` date DEFAULT NULL,
  `id_usuario` bigint(20) UNSIGNED NOT NULL,
  `formato` varchar(255) NOT NULL DEFAULT 'pdf',
  `filtros` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`filtros`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `historial_reportes`
--

INSERT INTO `historial_reportes` (`id`, `tipo`, `fecha_inicio`, `fecha_fin`, `id_usuario`, `formato`, `filtros`, `created_at`, `updated_at`) VALUES
(1, 'produccion', '2025-11-01', '2025-11-21', 1, 'pdf', '{\"id_producto\":null}', '2025-11-22 01:32:25', '2025-11-22 01:32:25'),
(2, 'produccion', '2025-11-01', '2025-11-21', 1, 'pdf', '{\"id_producto\":null}', '2025-11-22 01:38:53', '2025-11-22 01:38:53'),
(3, 'produccion', '2025-11-01', '2025-11-21', 1, 'pdf', '{\"id_producto\":null}', '2025-11-22 01:42:26', '2025-11-22 01:42:26'),
(4, 'inventario', NULL, NULL, 1, 'pdf', NULL, '2025-11-26 07:25:05', '2025-11-26 07:25:05'),
(5, 'inventario', NULL, NULL, 1, 'pdf', NULL, '2025-11-26 07:25:37', '2025-11-26 07:25:37'),
(6, 'inventario', NULL, NULL, 1, 'pdf', NULL, '2025-11-26 07:29:06', '2025-11-26 07:29:06'),
(7, 'inventario', NULL, NULL, 1, 'pdf', NULL, '2025-11-26 07:29:21', '2025-11-26 07:29:21'),
(8, 'inventario', NULL, NULL, 1, 'pdf', NULL, '2025-11-27 08:03:56', '2025-11-27 08:03:56'),
(9, 'inventario', NULL, NULL, 1, 'pdf', NULL, '2025-11-27 08:06:47', '2025-11-27 08:06:47'),
(10, 'inventario', NULL, NULL, 1, 'pdf', NULL, '2025-11-28 14:14:28', '2025-11-28 14:14:28'),
(11, 'inventario', NULL, NULL, 1, 'pdf', NULL, '2025-11-28 14:52:14', '2025-11-28 14:52:14'),
(12, 'inventario', NULL, NULL, 1, 'pdf', NULL, '2025-11-28 14:52:52', '2025-11-28 14:52:52'),
(13, 'produccion', '2025-11-27', '2025-12-04', 5, 'pdf', '{\"id_producto\":null}', '2025-12-04 14:24:02', '2025-12-04 14:24:02'),
(14, 'produccion', '2025-11-28', '2025-12-04', 5, 'pdf', '{\"id_producto\":null}', '2025-12-04 14:27:21', '2025-12-04 14:27:21'),
(15, 'inventario', NULL, NULL, 1, 'pdf', NULL, '2025-12-04 15:16:12', '2025-12-04 15:16:12'),
(16, 'produccion', '2025-12-01', '2025-12-04', 1, 'pdf', '{\"id_producto\":null}', '2025-12-04 15:31:09', '2025-12-04 15:31:09'),
(17, 'produccion', '2025-12-01', '2025-12-04', 1, 'pdf', '{\"id_producto\":null}', '2025-12-04 15:33:14', '2025-12-04 15:33:14'),
(18, 'produccion', '2025-12-01', '2025-12-04', 1, 'pdf', '{\"id_producto\":null}', '2025-12-04 15:34:15', '2025-12-04 15:34:15'),
(19, 'produccion', '2025-12-01', '2025-12-04', 1, 'pdf', '{\"id_producto\":null}', '2025-12-04 15:36:45', '2025-12-04 15:36:45'),
(20, 'produccion', '2025-12-01', '2025-12-04', 1, 'pdf', '{\"id_producto\":null}', '2025-12-04 15:37:12', '2025-12-04 15:37:12'),
(21, 'produccion', '2025-12-05', '2025-12-31', 1, 'pdf', '{\"id_producto\":null}', '2025-12-04 15:39:13', '2025-12-04 15:39:13'),
(22, 'inventario', NULL, NULL, 1, 'pdf', NULL, '2025-12-04 15:40:27', '2025-12-04 15:40:27'),
(23, 'produccion', '2025-12-01', '2025-12-04', 1, 'pdf', '{\"id_producto\":\"3\"}', '2025-12-04 15:41:40', '2025-12-04 15:41:40'),
(24, 'produccion', '2025-12-01', '2025-12-04', 1, 'pdf', '{\"id_producto\":\"3\"}', '2025-12-04 15:44:09', '2025-12-04 15:44:09'),
(25, 'inventario', NULL, NULL, 1, 'pdf', NULL, '2025-12-04 15:47:46', '2025-12-04 15:47:46'),
(26, 'inventario', '2025-12-01', '2025-12-31', 1, 'pdf', '{\"tipo_movimiento\":null}', '2025-12-04 15:52:02', '2025-12-04 15:52:02'),
(27, 'inventario_movimientos', '2025-12-01', '2025-12-04', 1, 'pdf', '{\"tipo_movimiento\":\"salida\"}', '2025-12-04 16:01:11', '2025-12-04 16:01:11');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inventario`
--

CREATE TABLE `inventario` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_producto` bigint(20) UNSIGNED NOT NULL,
  `tipo_movimiento` enum('entrada','salida') NOT NULL COMMENT 'Tipo de movimiento de inventario',
  `cantidad` int(10) UNSIGNED NOT NULL COMMENT 'Cantidad del movimiento',
  `origen` varchar(200) DEFAULT NULL COMMENT 'Origen del movimiento (ej: Producción, Proveedor, Ajuste)',
  `destino` varchar(200) DEFAULT NULL COMMENT 'Destino del movimiento (ej: Almacén, Cliente, Merma)',
  `referencia` varchar(100) DEFAULT NULL COMMENT 'Número de referencia o documento asociado',
  `id_usuario` bigint(20) UNSIGNED DEFAULT NULL,
  `fecha_movimiento` datetime NOT NULL COMMENT 'Fecha y hora del movimiento',
  `observacion` text DEFAULT NULL COMMENT 'Observaciones adicionales del movimiento',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `inventario`
--

INSERT INTO `inventario` (`id`, `id_producto`, `tipo_movimiento`, `cantidad`, `origen`, `destino`, `referencia`, `id_usuario`, `fecha_movimiento`, `observacion`, `created_at`, `updated_at`) VALUES
(1454, 1, 'entrada', 114, 'Producci¾n', NULL, 'PROD-93', NULL, '2025-12-30 08:00:00', 'Producci¾n diaria - 30/12/2025 - Lidia Canon', '2025-12-04 15:21:43', '2025-12-04 15:21:43'),
(1455, 3, 'entrada', 641, 'Producci¾n', NULL, 'PROD-93', NULL, '2025-12-30 08:00:00', 'Producci¾n diaria - 30/12/2025 - Lidia Canon', '2025-12-04 15:21:43', '2025-12-04 15:21:43'),
(1456, 4, 'entrada', 860, 'Producci¾n', NULL, 'PROD-93', NULL, '2025-12-30 08:00:00', 'Producci¾n diaria - 30/12/2025 - Lidia Canon', '2025-12-04 15:21:43', '2025-12-04 15:21:43'),
(1457, 6, 'entrada', 166, 'Producci¾n', NULL, 'PROD-93', NULL, '2025-12-30 08:00:00', 'Producci¾n diaria - 30/12/2025 - Lidia Canon', '2025-12-04 15:21:43', '2025-12-04 15:21:43'),
(1458, 8, 'entrada', 43, 'Producci¾n', NULL, 'PROD-93', NULL, '2025-12-30 08:00:00', 'Producci¾n diaria - 30/12/2025 - Lidia Canon', '2025-12-04 15:21:43', '2025-12-04 15:21:43'),
(1459, 9, 'entrada', 240, 'Producci¾n', NULL, 'PROD-93', NULL, '2025-12-30 08:00:00', 'Producci¾n diaria - 30/12/2025 - Lidia Canon', '2025-12-04 15:21:43', '2025-12-04 15:21:43'),
(1460, 10, 'entrada', 198, 'Producci¾n', NULL, 'PROD-93', NULL, '2025-12-30 08:00:00', 'Producci¾n diaria - 30/12/2025 - Lidia Canon', '2025-12-04 15:21:43', '2025-12-04 15:21:43'),
(1461, 11, 'entrada', 59, 'Producci¾n', NULL, 'PROD-93', NULL, '2025-12-30 08:00:00', 'Producci¾n diaria - 30/12/2025 - Lidia Canon', '2025-12-04 15:21:43', '2025-12-04 15:21:43'),
(1462, 12, 'entrada', 680, 'Producci¾n', NULL, 'PROD-93', NULL, '2025-12-30 08:00:00', 'Producci¾n diaria - 30/12/2025 - Lidia Canon', '2025-12-04 15:21:43', '2025-12-04 15:21:43'),
(1469, 1, 'entrada', 147, 'Producción Diaria', NULL, NULL, NULL, '2025-12-01 08:00:00', 'Entrada por producción del 01/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1470, 3, 'entrada', 1019, 'Producción Diaria', NULL, NULL, NULL, '2025-12-01 08:00:00', 'Entrada por producción del 01/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1471, 4, 'entrada', 1170, 'Producción Diaria', NULL, NULL, NULL, '2025-12-01 08:00:00', 'Entrada por producción del 01/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1472, 6, 'entrada', 218, 'Producción Diaria', NULL, NULL, NULL, '2025-12-01 08:00:00', 'Entrada por producción del 01/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1473, 8, 'entrada', 79, 'Producción Diaria', NULL, NULL, NULL, '2025-12-01 08:00:00', 'Entrada por producción del 01/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1474, 9, 'entrada', 472, 'Producción Diaria', NULL, NULL, NULL, '2025-12-01 08:00:00', 'Entrada por producción del 01/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1475, 10, 'entrada', 339, 'Producción Diaria', NULL, NULL, NULL, '2025-12-01 08:00:00', 'Entrada por producción del 01/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1476, 11, 'entrada', 76, 'Producción Diaria', NULL, NULL, NULL, '2025-12-01 08:00:00', 'Entrada por producción del 01/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1477, 12, 'entrada', 950, 'Producción Diaria', NULL, NULL, NULL, '2025-12-01 08:00:00', 'Entrada por producción del 01/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1478, 1, 'entrada', 176, 'Producción Diaria', NULL, NULL, NULL, '2025-12-02 08:00:00', 'Entrada por producción del 02/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1479, 3, 'entrada', 1082, 'Producción Diaria', NULL, NULL, NULL, '2025-12-02 08:00:00', 'Entrada por producción del 02/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1480, 4, 'entrada', 850, 'Producción Diaria', NULL, NULL, NULL, '2025-12-02 08:00:00', 'Entrada por producción del 02/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1481, 6, 'entrada', 198, 'Producción Diaria', NULL, NULL, NULL, '2025-12-02 08:00:00', 'Entrada por producción del 02/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1482, 8, 'entrada', 57, 'Producción Diaria', NULL, NULL, NULL, '2025-12-02 08:00:00', 'Entrada por producción del 02/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1483, 9, 'entrada', 360, 'Producción Diaria', NULL, NULL, NULL, '2025-12-02 08:00:00', 'Entrada por producción del 02/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1484, 10, 'entrada', 270, 'Producción Diaria', NULL, NULL, NULL, '2025-12-02 08:00:00', 'Entrada por producción del 02/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1485, 11, 'entrada', 92, 'Producción Diaria', NULL, NULL, NULL, '2025-12-02 08:00:00', 'Entrada por producción del 02/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1486, 12, 'entrada', 860, 'Producción Diaria', NULL, NULL, NULL, '2025-12-02 08:00:00', 'Entrada por producción del 02/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1487, 1, 'entrada', 177, 'Producción Diaria', NULL, NULL, NULL, '2025-12-03 08:00:00', 'Entrada por producción del 03/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1488, 3, 'entrada', 1124, 'Producción Diaria', NULL, NULL, NULL, '2025-12-03 08:00:00', 'Entrada por producción del 03/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1489, 4, 'entrada', 1080, 'Producción Diaria', NULL, NULL, NULL, '2025-12-03 08:00:00', 'Entrada por producción del 03/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1490, 6, 'entrada', 192, 'Producción Diaria', NULL, NULL, NULL, '2025-12-03 08:00:00', 'Entrada por producción del 03/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1491, 8, 'entrada', 67, 'Producción Diaria', NULL, NULL, NULL, '2025-12-03 08:00:00', 'Entrada por producción del 03/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1492, 9, 'entrada', 432, 'Producción Diaria', NULL, NULL, NULL, '2025-12-03 08:00:00', 'Entrada por producción del 03/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1493, 10, 'entrada', 330, 'Producción Diaria', NULL, NULL, NULL, '2025-12-03 08:00:00', 'Entrada por producción del 03/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1494, 11, 'entrada', 93, 'Producción Diaria', NULL, NULL, NULL, '2025-12-03 08:00:00', 'Entrada por producción del 03/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1495, 12, 'entrada', 990, 'Producción Diaria', NULL, NULL, NULL, '2025-12-03 08:00:00', 'Entrada por producción del 03/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1496, 1, 'entrada', 161, 'Producción Diaria', NULL, NULL, NULL, '2025-12-04 08:00:00', 'Entrada por producción del 04/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1497, 3, 'entrada', 998, 'Producción Diaria', NULL, NULL, NULL, '2025-12-04 08:00:00', 'Entrada por producción del 04/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1498, 4, 'entrada', 870, 'Producción Diaria', NULL, NULL, NULL, '2025-12-04 08:00:00', 'Entrada por producción del 04/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1499, 6, 'entrada', 198, 'Producción Diaria', NULL, NULL, NULL, '2025-12-04 08:00:00', 'Entrada por producción del 04/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1500, 8, 'entrada', 70, 'Producción Diaria', NULL, NULL, NULL, '2025-12-04 08:00:00', 'Entrada por producción del 04/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1501, 9, 'entrada', 352, 'Producción Diaria', NULL, NULL, NULL, '2025-12-04 08:00:00', 'Entrada por producción del 04/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1502, 10, 'entrada', 348, 'Producción Diaria', NULL, NULL, NULL, '2025-12-04 08:00:00', 'Entrada por producción del 04/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1503, 11, 'entrada', 77, 'Producción Diaria', NULL, NULL, NULL, '2025-12-04 08:00:00', 'Entrada por producción del 04/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1504, 12, 'entrada', 950, 'Producción Diaria', NULL, NULL, NULL, '2025-12-04 08:00:00', 'Entrada por producción del 04/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1505, 1, 'entrada', 125, 'Producción Diaria', NULL, NULL, NULL, '2025-12-05 08:00:00', 'Entrada por producción del 05/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1506, 3, 'entrada', 1040, 'Producción Diaria', NULL, NULL, NULL, '2025-12-05 08:00:00', 'Entrada por producción del 05/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1507, 4, 'entrada', 1090, 'Producción Diaria', NULL, NULL, NULL, '2025-12-05 08:00:00', 'Entrada por producción del 05/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1508, 6, 'entrada', 208, 'Producción Diaria', NULL, NULL, NULL, '2025-12-05 08:00:00', 'Entrada por producción del 05/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1509, 8, 'entrada', 81, 'Producción Diaria', NULL, NULL, NULL, '2025-12-05 08:00:00', 'Entrada por producción del 05/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1510, 9, 'entrada', 440, 'Producción Diaria', NULL, NULL, NULL, '2025-12-05 08:00:00', 'Entrada por producción del 05/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1511, 10, 'entrada', 270, 'Producción Diaria', NULL, NULL, NULL, '2025-12-05 08:00:00', 'Entrada por producción del 05/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1512, 11, 'entrada', 86, 'Producción Diaria', NULL, NULL, NULL, '2025-12-05 08:00:00', 'Entrada por producción del 05/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1513, 12, 'entrada', 1160, 'Producción Diaria', NULL, NULL, NULL, '2025-12-05 08:00:00', 'Entrada por producción del 05/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1514, 1, 'entrada', 159, 'Producción Diaria', NULL, NULL, NULL, '2025-12-06 08:00:00', 'Entrada por producción del 06/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1515, 3, 'entrada', 987, 'Producción Diaria', NULL, NULL, NULL, '2025-12-06 08:00:00', 'Entrada por producción del 06/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1516, 4, 'entrada', 820, 'Producción Diaria', NULL, NULL, NULL, '2025-12-06 08:00:00', 'Entrada por producción del 06/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1517, 6, 'entrada', 234, 'Producción Diaria', NULL, NULL, NULL, '2025-12-06 08:00:00', 'Entrada por producción del 06/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1518, 8, 'entrada', 71, 'Producción Diaria', NULL, NULL, NULL, '2025-12-06 08:00:00', 'Entrada por producción del 06/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1519, 9, 'entrada', 384, 'Producción Diaria', NULL, NULL, NULL, '2025-12-06 08:00:00', 'Entrada por producción del 06/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1520, 10, 'entrada', 255, 'Producción Diaria', NULL, NULL, NULL, '2025-12-06 08:00:00', 'Entrada por producción del 06/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1521, 11, 'entrada', 89, 'Producción Diaria', NULL, NULL, NULL, '2025-12-06 08:00:00', 'Entrada por producción del 06/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1522, 12, 'entrada', 1050, 'Producción Diaria', NULL, NULL, NULL, '2025-12-06 08:00:00', 'Entrada por producción del 06/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1523, 1, 'entrada', 120, 'Producción Diaria', NULL, NULL, NULL, '2025-12-08 08:00:00', 'Entrada por producción del 08/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1524, 3, 'entrada', 1134, 'Producción Diaria', NULL, NULL, NULL, '2025-12-08 08:00:00', 'Entrada por producción del 08/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1525, 4, 'entrada', 830, 'Producción Diaria', NULL, NULL, NULL, '2025-12-08 08:00:00', 'Entrada por producción del 08/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1526, 6, 'entrada', 222, 'Producción Diaria', NULL, NULL, NULL, '2025-12-08 08:00:00', 'Entrada por producción del 08/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1527, 8, 'entrada', 78, 'Producción Diaria', NULL, NULL, NULL, '2025-12-08 08:00:00', 'Entrada por producción del 08/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1528, 9, 'entrada', 408, 'Producción Diaria', NULL, NULL, NULL, '2025-12-08 08:00:00', 'Entrada por producción del 08/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1529, 10, 'entrada', 288, 'Producción Diaria', NULL, NULL, NULL, '2025-12-08 08:00:00', 'Entrada por producción del 08/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1530, 11, 'entrada', 80, 'Producción Diaria', NULL, NULL, NULL, '2025-12-08 08:00:00', 'Entrada por producción del 08/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1531, 12, 'entrada', 890, 'Producción Diaria', NULL, NULL, NULL, '2025-12-08 08:00:00', 'Entrada por producción del 08/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1532, 1, 'entrada', 146, 'Producción Diaria', NULL, NULL, NULL, '2025-12-09 08:00:00', 'Entrada por producción del 09/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1533, 3, 'entrada', 1113, 'Producción Diaria', NULL, NULL, NULL, '2025-12-09 08:00:00', 'Entrada por producción del 09/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1534, 4, 'entrada', 820, 'Producción Diaria', NULL, NULL, NULL, '2025-12-09 08:00:00', 'Entrada por producción del 09/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1535, 6, 'entrada', 230, 'Producción Diaria', NULL, NULL, NULL, '2025-12-09 08:00:00', 'Entrada por producción del 09/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1536, 8, 'entrada', 74, 'Producción Diaria', NULL, NULL, NULL, '2025-12-09 08:00:00', 'Entrada por producción del 09/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1537, 9, 'entrada', 324, 'Producción Diaria', NULL, NULL, NULL, '2025-12-09 08:00:00', 'Entrada por producción del 09/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1538, 10, 'entrada', 255, 'Producción Diaria', NULL, NULL, NULL, '2025-12-09 08:00:00', 'Entrada por producción del 09/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1539, 11, 'entrada', 73, 'Producción Diaria', NULL, NULL, NULL, '2025-12-09 08:00:00', 'Entrada por producción del 09/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1540, 12, 'entrada', 970, 'Producción Diaria', NULL, NULL, NULL, '2025-12-09 08:00:00', 'Entrada por producción del 09/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1541, 1, 'entrada', 138, 'Producción Diaria', NULL, NULL, NULL, '2025-12-10 08:00:00', 'Entrada por producción del 10/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1542, 3, 'entrada', 1218, 'Producción Diaria', NULL, NULL, NULL, '2025-12-10 08:00:00', 'Entrada por producción del 10/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1543, 4, 'entrada', 1040, 'Producción Diaria', NULL, NULL, NULL, '2025-12-10 08:00:00', 'Entrada por producción del 10/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1544, 6, 'entrada', 206, 'Producción Diaria', NULL, NULL, NULL, '2025-12-10 08:00:00', 'Entrada por producción del 10/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1545, 8, 'entrada', 72, 'Producción Diaria', NULL, NULL, NULL, '2025-12-10 08:00:00', 'Entrada por producción del 10/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1546, 9, 'entrada', 392, 'Producción Diaria', NULL, NULL, NULL, '2025-12-10 08:00:00', 'Entrada por producción del 10/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1547, 10, 'entrada', 306, 'Producción Diaria', NULL, NULL, NULL, '2025-12-10 08:00:00', 'Entrada por producción del 10/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1548, 11, 'entrada', 108, 'Producción Diaria', NULL, NULL, NULL, '2025-12-10 08:00:00', 'Entrada por producción del 10/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1549, 12, 'entrada', 1170, 'Producción Diaria', NULL, NULL, NULL, '2025-12-10 08:00:00', 'Entrada por producción del 10/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1550, 1, 'entrada', 167, 'Producción Diaria', NULL, NULL, NULL, '2025-12-11 08:00:00', 'Entrada por producción del 11/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1551, 3, 'entrada', 1197, 'Producción Diaria', NULL, NULL, NULL, '2025-12-11 08:00:00', 'Entrada por producción del 11/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1552, 4, 'entrada', 850, 'Producción Diaria', NULL, NULL, NULL, '2025-12-11 08:00:00', 'Entrada por producción del 11/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1553, 6, 'entrada', 224, 'Producción Diaria', NULL, NULL, NULL, '2025-12-11 08:00:00', 'Entrada por producción del 11/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1554, 8, 'entrada', 83, 'Producción Diaria', NULL, NULL, NULL, '2025-12-11 08:00:00', 'Entrada por producción del 11/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1555, 9, 'entrada', 356, 'Producción Diaria', NULL, NULL, NULL, '2025-12-11 08:00:00', 'Entrada por producción del 11/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1556, 10, 'entrada', 279, 'Producción Diaria', NULL, NULL, NULL, '2025-12-11 08:00:00', 'Entrada por producción del 11/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1557, 11, 'entrada', 76, 'Producción Diaria', NULL, NULL, NULL, '2025-12-11 08:00:00', 'Entrada por producción del 11/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1558, 12, 'entrada', 870, 'Producción Diaria', NULL, NULL, NULL, '2025-12-11 08:00:00', 'Entrada por producción del 11/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1559, 1, 'entrada', 126, 'Producción Diaria', NULL, NULL, NULL, '2025-12-12 08:00:00', 'Entrada por producción del 12/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1560, 3, 'entrada', 861, 'Producción Diaria', NULL, NULL, NULL, '2025-12-12 08:00:00', 'Entrada por producción del 12/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1561, 4, 'entrada', 910, 'Producción Diaria', NULL, NULL, NULL, '2025-12-12 08:00:00', 'Entrada por producción del 12/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1562, 6, 'entrada', 162, 'Producción Diaria', NULL, NULL, NULL, '2025-12-12 08:00:00', 'Entrada por producción del 12/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1563, 8, 'entrada', 70, 'Producción Diaria', NULL, NULL, NULL, '2025-12-12 08:00:00', 'Entrada por producción del 12/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1564, 9, 'entrada', 440, 'Producción Diaria', NULL, NULL, NULL, '2025-12-12 08:00:00', 'Entrada por producción del 12/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1565, 10, 'entrada', 357, 'Producción Diaria', NULL, NULL, NULL, '2025-12-12 08:00:00', 'Entrada por producción del 12/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1566, 11, 'entrada', 80, 'Producción Diaria', NULL, NULL, NULL, '2025-12-12 08:00:00', 'Entrada por producción del 12/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1567, 12, 'entrada', 1000, 'Producción Diaria', NULL, NULL, NULL, '2025-12-12 08:00:00', 'Entrada por producción del 12/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1568, 1, 'entrada', 144, 'Producción Diaria', NULL, NULL, NULL, '2025-12-13 08:00:00', 'Entrada por producción del 13/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1569, 3, 'entrada', 1082, 'Producción Diaria', NULL, NULL, NULL, '2025-12-13 08:00:00', 'Entrada por producción del 13/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1570, 4, 'entrada', 830, 'Producción Diaria', NULL, NULL, NULL, '2025-12-13 08:00:00', 'Entrada por producción del 13/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1571, 6, 'entrada', 216, 'Producción Diaria', NULL, NULL, NULL, '2025-12-13 08:00:00', 'Entrada por producción del 13/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1572, 8, 'entrada', 74, 'Producción Diaria', NULL, NULL, NULL, '2025-12-13 08:00:00', 'Entrada por producción del 13/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1573, 9, 'entrada', 404, 'Producción Diaria', NULL, NULL, NULL, '2025-12-13 08:00:00', 'Entrada por producción del 13/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1574, 10, 'entrada', 348, 'Producción Diaria', NULL, NULL, NULL, '2025-12-13 08:00:00', 'Entrada por producción del 13/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1575, 11, 'entrada', 90, 'Producción Diaria', NULL, NULL, NULL, '2025-12-13 08:00:00', 'Entrada por producción del 13/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1576, 12, 'entrada', 800, 'Producción Diaria', NULL, NULL, NULL, '2025-12-13 08:00:00', 'Entrada por producción del 13/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1577, 1, 'entrada', 140, 'Producción Diaria', NULL, NULL, NULL, '2025-12-15 08:00:00', 'Entrada por producción del 15/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1578, 3, 'entrada', 882, 'Producción Diaria', NULL, NULL, NULL, '2025-12-15 08:00:00', 'Entrada por producción del 15/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1579, 4, 'entrada', 920, 'Producción Diaria', NULL, NULL, NULL, '2025-12-15 08:00:00', 'Entrada por producción del 15/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1580, 6, 'entrada', 228, 'Producción Diaria', NULL, NULL, NULL, '2025-12-15 08:00:00', 'Entrada por producción del 15/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1581, 8, 'entrada', 57, 'Producción Diaria', NULL, NULL, NULL, '2025-12-15 08:00:00', 'Entrada por producción del 15/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1582, 9, 'entrada', 344, 'Producción Diaria', NULL, NULL, NULL, '2025-12-15 08:00:00', 'Entrada por producción del 15/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1583, 10, 'entrada', 306, 'Producción Diaria', NULL, NULL, NULL, '2025-12-15 08:00:00', 'Entrada por producción del 15/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1584, 11, 'entrada', 92, 'Producción Diaria', NULL, NULL, NULL, '2025-12-15 08:00:00', 'Entrada por producción del 15/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1585, 12, 'entrada', 920, 'Producción Diaria', NULL, NULL, NULL, '2025-12-15 08:00:00', 'Entrada por producción del 15/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1586, 1, 'entrada', 165, 'Producción Diaria', NULL, NULL, NULL, '2025-12-16 08:00:00', 'Entrada por producción del 16/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1587, 3, 'entrada', 903, 'Producción Diaria', NULL, NULL, NULL, '2025-12-16 08:00:00', 'Entrada por producción del 16/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1588, 4, 'entrada', 910, 'Producción Diaria', NULL, NULL, NULL, '2025-12-16 08:00:00', 'Entrada por producción del 16/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1589, 6, 'entrada', 214, 'Producción Diaria', NULL, NULL, NULL, '2025-12-16 08:00:00', 'Entrada por producción del 16/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1590, 8, 'entrada', 71, 'Producción Diaria', NULL, NULL, NULL, '2025-12-16 08:00:00', 'Entrada por producción del 16/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1591, 9, 'entrada', 396, 'Producción Diaria', NULL, NULL, NULL, '2025-12-16 08:00:00', 'Entrada por producción del 16/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1592, 10, 'entrada', 327, 'Producción Diaria', NULL, NULL, NULL, '2025-12-16 08:00:00', 'Entrada por producción del 16/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1593, 11, 'entrada', 91, 'Producción Diaria', NULL, NULL, NULL, '2025-12-16 08:00:00', 'Entrada por producción del 16/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1594, 12, 'entrada', 890, 'Producción Diaria', NULL, NULL, NULL, '2025-12-16 08:00:00', 'Entrada por producción del 16/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1595, 1, 'entrada', 177, 'Producción Diaria', NULL, NULL, NULL, '2025-12-17 08:00:00', 'Entrada por producción del 17/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1596, 3, 'entrada', 1166, 'Producción Diaria', NULL, NULL, NULL, '2025-12-17 08:00:00', 'Entrada por producción del 17/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1597, 4, 'entrada', 880, 'Producción Diaria', NULL, NULL, NULL, '2025-12-17 08:00:00', 'Entrada por producción del 17/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1598, 6, 'entrada', 228, 'Producción Diaria', NULL, NULL, NULL, '2025-12-17 08:00:00', 'Entrada por producción del 17/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1599, 8, 'entrada', 69, 'Producción Diaria', NULL, NULL, NULL, '2025-12-17 08:00:00', 'Entrada por producción del 17/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1600, 9, 'entrada', 456, 'Producción Diaria', NULL, NULL, NULL, '2025-12-17 08:00:00', 'Entrada por producción del 17/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1601, 10, 'entrada', 330, 'Producción Diaria', NULL, NULL, NULL, '2025-12-17 08:00:00', 'Entrada por producción del 17/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1602, 11, 'entrada', 101, 'Producción Diaria', NULL, NULL, NULL, '2025-12-17 08:00:00', 'Entrada por producción del 17/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1603, 12, 'entrada', 870, 'Producción Diaria', NULL, NULL, NULL, '2025-12-17 08:00:00', 'Entrada por producción del 17/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1604, 1, 'entrada', 179, 'Producción Diaria', NULL, NULL, NULL, '2025-12-18 08:00:00', 'Entrada por producción del 18/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1605, 3, 'entrada', 966, 'Producción Diaria', NULL, NULL, NULL, '2025-12-18 08:00:00', 'Entrada por producción del 18/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1606, 4, 'entrada', 910, 'Producción Diaria', NULL, NULL, NULL, '2025-12-18 08:00:00', 'Entrada por producción del 18/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1607, 6, 'entrada', 196, 'Producción Diaria', NULL, NULL, NULL, '2025-12-18 08:00:00', 'Entrada por producción del 18/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1608, 8, 'entrada', 67, 'Producción Diaria', NULL, NULL, NULL, '2025-12-18 08:00:00', 'Entrada por producción del 18/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1609, 9, 'entrada', 440, 'Producción Diaria', NULL, NULL, NULL, '2025-12-18 08:00:00', 'Entrada por producción del 18/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1610, 10, 'entrada', 327, 'Producción Diaria', NULL, NULL, NULL, '2025-12-18 08:00:00', 'Entrada por producción del 18/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1611, 11, 'entrada', 99, 'Producción Diaria', NULL, NULL, NULL, '2025-12-18 08:00:00', 'Entrada por producción del 18/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1612, 12, 'entrada', 1160, 'Producción Diaria', NULL, NULL, NULL, '2025-12-18 08:00:00', 'Entrada por producción del 18/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1613, 1, 'entrada', 159, 'Producción Diaria', NULL, NULL, NULL, '2025-12-19 08:00:00', 'Entrada por producción del 19/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1614, 3, 'entrada', 1050, 'Producción Diaria', NULL, NULL, NULL, '2025-12-19 08:00:00', 'Entrada por producción del 19/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1615, 4, 'entrada', 950, 'Producción Diaria', NULL, NULL, NULL, '2025-12-19 08:00:00', 'Entrada por producción del 19/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1616, 6, 'entrada', 208, 'Producción Diaria', NULL, NULL, NULL, '2025-12-19 08:00:00', 'Entrada por producción del 19/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1617, 8, 'entrada', 81, 'Producción Diaria', NULL, NULL, NULL, '2025-12-19 08:00:00', 'Entrada por producción del 19/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1618, 9, 'entrada', 440, 'Producción Diaria', NULL, NULL, NULL, '2025-12-19 08:00:00', 'Entrada por producción del 19/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1619, 10, 'entrada', 282, 'Producción Diaria', NULL, NULL, NULL, '2025-12-19 08:00:00', 'Entrada por producción del 19/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1620, 11, 'entrada', 103, 'Producción Diaria', NULL, NULL, NULL, '2025-12-19 08:00:00', 'Entrada por producción del 19/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1621, 12, 'entrada', 1050, 'Producción Diaria', NULL, NULL, NULL, '2025-12-19 08:00:00', 'Entrada por producción del 19/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1622, 1, 'entrada', 128, 'Producción Diaria', NULL, NULL, NULL, '2025-12-20 08:00:00', 'Entrada por producción del 20/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1623, 3, 'entrada', 693, 'Producción Diaria', NULL, NULL, NULL, '2025-12-20 08:00:00', 'Entrada por producción del 20/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1624, 4, 'entrada', 780, 'Producción Diaria', NULL, NULL, NULL, '2025-12-20 08:00:00', 'Entrada por producción del 20/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1625, 6, 'entrada', 180, 'Producción Diaria', NULL, NULL, NULL, '2025-12-20 08:00:00', 'Entrada por producción del 20/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1626, 8, 'entrada', 60, 'Producción Diaria', NULL, NULL, NULL, '2025-12-20 08:00:00', 'Entrada por producción del 20/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1627, 9, 'entrada', 344, 'Producción Diaria', NULL, NULL, NULL, '2025-12-20 08:00:00', 'Entrada por producción del 20/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1628, 10, 'entrada', 246, 'Producción Diaria', NULL, NULL, NULL, '2025-12-20 08:00:00', 'Entrada por producción del 20/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1629, 11, 'entrada', 77, 'Producción Diaria', NULL, NULL, NULL, '2025-12-20 08:00:00', 'Entrada por producción del 20/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1630, 12, 'entrada', 610, 'Producción Diaria', NULL, NULL, NULL, '2025-12-20 08:00:00', 'Entrada por producción del 20/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1631, 1, 'entrada', 128, 'Producción Diaria', NULL, NULL, NULL, '2025-12-22 08:00:00', 'Entrada por producción del 22/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1632, 3, 'entrada', 725, 'Producción Diaria', NULL, NULL, NULL, '2025-12-22 08:00:00', 'Entrada por producción del 22/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1633, 4, 'entrada', 900, 'Producción Diaria', NULL, NULL, NULL, '2025-12-22 08:00:00', 'Entrada por producción del 22/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1634, 6, 'entrada', 124, 'Producción Diaria', NULL, NULL, NULL, '2025-12-22 08:00:00', 'Entrada por producción del 22/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1635, 8, 'entrada', 56, 'Producción Diaria', NULL, NULL, NULL, '2025-12-22 08:00:00', 'Entrada por producción del 22/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1636, 9, 'entrada', 328, 'Producción Diaria', NULL, NULL, NULL, '2025-12-22 08:00:00', 'Entrada por producción del 22/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1637, 10, 'entrada', 204, 'Producción Diaria', NULL, NULL, NULL, '2025-12-22 08:00:00', 'Entrada por producción del 22/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1638, 11, 'entrada', 66, 'Producción Diaria', NULL, NULL, NULL, '2025-12-22 08:00:00', 'Entrada por producción del 22/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1639, 12, 'entrada', 710, 'Producción Diaria', NULL, NULL, NULL, '2025-12-22 08:00:00', 'Entrada por producción del 22/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1640, 1, 'entrada', 125, 'Producción Diaria', NULL, NULL, NULL, '2025-12-23 08:00:00', 'Entrada por producción del 23/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1641, 3, 'entrada', 872, 'Producción Diaria', NULL, NULL, NULL, '2025-12-23 08:00:00', 'Entrada por producción del 23/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1642, 4, 'entrada', 640, 'Producción Diaria', NULL, NULL, NULL, '2025-12-23 08:00:00', 'Entrada por producción del 23/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1643, 6, 'entrada', 154, 'Producción Diaria', NULL, NULL, NULL, '2025-12-23 08:00:00', 'Entrada por producción del 23/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1644, 8, 'entrada', 43, 'Producción Diaria', NULL, NULL, NULL, '2025-12-23 08:00:00', 'Entrada por producción del 23/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1645, 9, 'entrada', 332, 'Producción Diaria', NULL, NULL, NULL, '2025-12-23 08:00:00', 'Entrada por producción del 23/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1646, 10, 'entrada', 261, 'Producción Diaria', NULL, NULL, NULL, '2025-12-23 08:00:00', 'Entrada por producción del 23/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1647, 11, 'entrada', 71, 'Producción Diaria', NULL, NULL, NULL, '2025-12-23 08:00:00', 'Entrada por producción del 23/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1648, 12, 'entrada', 890, 'Producción Diaria', NULL, NULL, NULL, '2025-12-23 08:00:00', 'Entrada por producción del 23/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1649, 1, 'entrada', 123, 'Producción Diaria', NULL, NULL, NULL, '2025-12-26 08:00:00', 'Entrada por producción del 26/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1650, 3, 'entrada', 735, 'Producción Diaria', NULL, NULL, NULL, '2025-12-26 08:00:00', 'Entrada por producción del 26/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1651, 4, 'entrada', 900, 'Producción Diaria', NULL, NULL, NULL, '2025-12-26 08:00:00', 'Entrada por producción del 26/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1652, 6, 'entrada', 180, 'Producción Diaria', NULL, NULL, NULL, '2025-12-26 08:00:00', 'Entrada por producción del 26/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1653, 8, 'entrada', 61, 'Producción Diaria', NULL, NULL, NULL, '2025-12-26 08:00:00', 'Entrada por producción del 26/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1654, 9, 'entrada', 324, 'Producción Diaria', NULL, NULL, NULL, '2025-12-26 08:00:00', 'Entrada por producción del 26/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1655, 10, 'entrada', 210, 'Producción Diaria', NULL, NULL, NULL, '2025-12-26 08:00:00', 'Entrada por producción del 26/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1656, 11, 'entrada', 77, 'Producción Diaria', NULL, NULL, NULL, '2025-12-26 08:00:00', 'Entrada por producción del 26/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1657, 12, 'entrada', 680, 'Producción Diaria', NULL, NULL, NULL, '2025-12-26 08:00:00', 'Entrada por producción del 26/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1658, 1, 'entrada', 90, 'Producción Diaria', NULL, NULL, NULL, '2025-12-27 08:00:00', 'Entrada por producción del 27/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1659, 3, 'entrada', 777, 'Producción Diaria', NULL, NULL, NULL, '2025-12-27 08:00:00', 'Entrada por producción del 27/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1660, 4, 'entrada', 850, 'Producción Diaria', NULL, NULL, NULL, '2025-12-27 08:00:00', 'Entrada por producción del 27/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1661, 6, 'entrada', 126, 'Producción Diaria', NULL, NULL, NULL, '2025-12-27 08:00:00', 'Entrada por producción del 27/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1662, 8, 'entrada', 59, 'Producción Diaria', NULL, NULL, NULL, '2025-12-27 08:00:00', 'Entrada por producción del 27/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1663, 9, 'entrada', 276, 'Producción Diaria', NULL, NULL, NULL, '2025-12-27 08:00:00', 'Entrada por producción del 27/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1664, 10, 'entrada', 231, 'Producción Diaria', NULL, NULL, NULL, '2025-12-27 08:00:00', 'Entrada por producción del 27/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1665, 11, 'entrada', 73, 'Producción Diaria', NULL, NULL, NULL, '2025-12-27 08:00:00', 'Entrada por producción del 27/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1666, 12, 'entrada', 730, 'Producción Diaria', NULL, NULL, NULL, '2025-12-27 08:00:00', 'Entrada por producción del 27/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1667, 1, 'entrada', 111, 'Producción Diaria', NULL, NULL, NULL, '2025-12-29 08:00:00', 'Entrada por producción del 29/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1668, 3, 'entrada', 746, 'Producción Diaria', NULL, NULL, NULL, '2025-12-29 08:00:00', 'Entrada por producción del 29/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1669, 4, 'entrada', 740, 'Producción Diaria', NULL, NULL, NULL, '2025-12-29 08:00:00', 'Entrada por producción del 29/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1670, 6, 'entrada', 142, 'Producción Diaria', NULL, NULL, NULL, '2025-12-29 08:00:00', 'Entrada por producción del 29/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1671, 8, 'entrada', 43, 'Producción Diaria', NULL, NULL, NULL, '2025-12-29 08:00:00', 'Entrada por producción del 29/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1672, 9, 'entrada', 324, 'Producción Diaria', NULL, NULL, NULL, '2025-12-29 08:00:00', 'Entrada por producción del 29/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1673, 10, 'entrada', 231, 'Producción Diaria', NULL, NULL, NULL, '2025-12-29 08:00:00', 'Entrada por producción del 29/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1674, 11, 'entrada', 60, 'Producción Diaria', NULL, NULL, NULL, '2025-12-29 08:00:00', 'Entrada por producción del 29/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1675, 12, 'entrada', 730, 'Producción Diaria', NULL, NULL, NULL, '2025-12-29 08:00:00', 'Entrada por producción del 29/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1676, 1, 'entrada', 114, 'Producción Diaria', NULL, NULL, NULL, '2025-12-30 08:00:00', 'Entrada por producción del 30/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1677, 3, 'entrada', 641, 'Producción Diaria', NULL, NULL, NULL, '2025-12-30 08:00:00', 'Entrada por producción del 30/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1678, 4, 'entrada', 860, 'Producción Diaria', NULL, NULL, NULL, '2025-12-30 08:00:00', 'Entrada por producción del 30/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1679, 6, 'entrada', 166, 'Producción Diaria', NULL, NULL, NULL, '2025-12-30 08:00:00', 'Entrada por producción del 30/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1680, 8, 'entrada', 43, 'Producción Diaria', NULL, NULL, NULL, '2025-12-30 08:00:00', 'Entrada por producción del 30/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1681, 9, 'entrada', 240, 'Producción Diaria', NULL, NULL, NULL, '2025-12-30 08:00:00', 'Entrada por producción del 30/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1682, 10, 'entrada', 198, 'Producción Diaria', NULL, NULL, NULL, '2025-12-30 08:00:00', 'Entrada por producción del 30/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1683, 11, 'entrada', 59, 'Producción Diaria', NULL, NULL, NULL, '2025-12-30 08:00:00', 'Entrada por producción del 30/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59'),
(1684, 12, 'entrada', 680, 'Producción Diaria', NULL, NULL, NULL, '2025-12-30 08:00:00', 'Entrada por producción del 30/12/2025', '2025-12-04 19:30:59', '2025-12-04 19:30:59');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2024_01_01_000001_create_roles_table', 1),
(2, '2024_01_01_000002_create_usuarios_table', 1),
(3, '2024_01_01_000003_create_personal_table', 1),
(4, '2024_01_01_000004_create_productos_table', 1),
(5, '2024_01_01_000005_create_produccion_table', 1),
(6, '2024_01_01_000006_create_inventario_table', 1),
(7, '2024_01_01_000007_create_vehiculos_table', 1),
(8, '2024_01_01_000010_create_password_reset_tokens_table', 1),
(9, '2025_10_22_132516_create_cache_table', 1),
(10, '2025_10_22_132517_create_jobs_table', 1),
(11, '2025_10_22_132708_create_personal_access_tokens_table', 1),
(12, '2025_10_25_033425_create_tipos_producto_table', 1),
(13, '2025_10_25_033436_create_alertas_stock_table', 1),
(14, '2025_10_25_033448_add_trazabilidad_to_inventario_table', 1),
(15, '2025_10_28_193706_add_id_tipo_producto_to_productos_table', 1),
(16, '2025_10_29_150103_add_id_personal_to_usuarios_table', 1),
(17, '2025_10_30_154125_add_marca_to_vehiculos_table', 1),
(18, '2025_10_31_141807_add_imagen_to_productos_table', 1),
(19, '2025_11_02_235627_add_es_chofer_to_personal_table', 1),
(20, '2025_11_03_211942_create_historial_reportes_table', 1),
(21, '2025_11_06_112240_create_v_stock_actual_view', 1),
(22, '2025_11_10_125528_create_control_salidas_productos_table', 1),
(23, '2025_11_10_125535_create_control_produccion_diaria_table', 1),
(24, '2025_11_10_125537_create_control_mantenimiento_equipos_table', 1),
(25, '2025_11_10_125538_create_control_fosa_septica_table', 1),
(26, '2025_11_10_125540_create_control_insumos_table', 1),
(27, '2025_11_10_125542_create_control_fumigacion_table', 1),
(28, '2025_11_10_125543_create_control_tanques_agua_table', 1),
(29, '2025_11_10_184218_add_fields_to_control_produccion_diaria_table', 1),
(30, '2025_11_10_190550_make_tipo_nullable_in_productos_table', 1),
(31, '2025_11_12_142920_create_asistencias_semanales_table', 1),
(32, '2025_11_12_153548_add_foto_and_garantia_to_personal_table', 1),
(33, '2025_11_13_022330_add_responsable_to_vehiculos_table', 1),
(34, '2025_11_13_022716_add_vehiculo_to_control_salidas_productos_table', 1),
(35, '2025_11_19_010000_add_retornos_detalle_to_control_salidas_productos_table', 1),
(36, '2025_11_21_194050_add_missing_fields_to_personal_table', 2),
(37, '2025_11_26_145936_add_customer_fields_to_control_salidas_productos_table', 3),
(38, '2025_11_26_151028_add_responsable_to_control_salidas_productos_table', 4),
(39, '2025_11_30_223929_add_id_personal_to_control_mantenimiento_equipos_table', 5),
(40, '2025_11_30_224743_add_unidades_por_paquete_to_productos_table', 6),
(41, '2025_12_01_000536_remove_es_chofer_from_personal_table', 7),
(42, '2025_12_04_000001_add_missing_foreign_keys', 8),
(43, '2025_12_04_000002_add_remaining_foreign_keys', 9),
(44, '2025_12_04_000003_connect_all_tables_with_foreign_keys', 10),
(45, '2025_12_04_000004_create_admin_asignaciones_and_connect_insumos', 11);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `personal`
--

CREATE TABLE `personal` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nombre_completo` varchar(255) NOT NULL,
  `cedula` varchar(50) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `telefono` varchar(255) DEFAULT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `documento_garantia` varchar(255) DEFAULT NULL,
  `cargo` varchar(255) NOT NULL,
  `area` varchar(255) NOT NULL,
  `fecha_ingreso` date DEFAULT NULL,
  `salario` decimal(10,2) DEFAULT NULL,
  `observaciones` text DEFAULT NULL,
  `estado` enum('activo','inactivo') NOT NULL DEFAULT 'activo',
  `tiene_acceso` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `personal`
--

INSERT INTO `personal` (`id`, `nombre_completo`, `cedula`, `email`, `telefono`, `direccion`, `foto`, `documento_garantia`, `cargo`, `area`, `fecha_ingreso`, `salario`, `observaciones`, `estado`, `tiene_acceso`, `created_at`, `updated_at`) VALUES
(6, 'Jhonatan matias pizzo aguilar', NULL, 'jhonatan.matias.pizzo.aguilar@aguacolegial.com', '76988828', 'AV.ECOLOGICA', NULL, NULL, 'Chofer', 'Producción', '2025-11-21', NULL, NULL, 'activo', 0, '2025-11-21 23:42:30', '2025-11-21 23:42:30'),
(7, 'deybi aguilar', NULL, 'deybi.aguilar@aguacolegial.com', '76461759', 'tiquipaya', NULL, NULL, 'Chofer', 'Producción', '2025-11-21', NULL, NULL, 'activo', 0, '2025-11-21 23:44:23', '2025-11-21 23:44:23'),
(8, 'nano alvarez', NULL, 'nano.alvarez@aguacolegial.com', '64523434', 'cruce taquiña', NULL, NULL, 'Distribuidor', 'Producción', '2025-11-21', NULL, NULL, 'activo', 0, '2025-11-21 23:44:46', '2025-11-21 23:44:46'),
(9, 'Lidia canon', NULL, 'lidia.canon@aguacolegial.com', '75634534', NULL, NULL, NULL, 'Encargado de Producción', 'Producción', '2025-11-21', NULL, NULL, 'activo', 0, '2025-11-21 23:45:09', '2025-11-21 23:45:09'),
(10, 'Helen Aguilar', NULL, 'helen.aguilar@aguacolegial.com', '6435234', NULL, NULL, NULL, 'Operador de Producción', 'Producción', '2025-11-21', NULL, NULL, 'activo', 0, '2025-11-21 23:45:34', '2025-11-21 23:45:34'),
(11, 'Anderson  Aguilar', NULL, 'anderson..aguilar@aguacolegial.com', '7698882821', NULL, NULL, NULL, 'Encargado de Producción', 'Producción', '2025-11-21', NULL, NULL, 'activo', 1, '2025-11-21 23:45:59', '2025-11-28 01:35:45'),
(12, 'Ana Guitierrez', NULL, 'ana.guitierrez@aguacolegial.com', NULL, NULL, NULL, NULL, 'Encargado de Producción', 'Producción', '2025-11-21', NULL, NULL, 'activo', 0, '2025-11-21 23:46:27', '2025-11-21 23:46:27'),
(13, 'Sergio Aguilar', NULL, 'sergio.aguilar@aguacolegial.com', '7133342', 'tiquipaya  av reducto esquina cochabamba', NULL, NULL, 'Chofer', 'Producción', '2025-11-21', NULL, NULL, 'activo', 0, '2025-11-21 23:46:44', '2025-11-21 23:46:44'),
(14, 'Jasmani Aguilar', NULL, 'jasmani.aguilar@aguacolegial.com', '769888212', NULL, NULL, NULL, 'Chofer', 'Producción', '2025-11-21', NULL, NULL, 'activo', 0, '2025-11-21 23:47:07', '2025-11-21 23:47:07'),
(15, 'Lucia Cruz Farfan', NULL, 'lucia.cruz.farfan@aguacolegial.com', '77117092', 'tiquipaya av.aguas potables esquina civerman', NULL, NULL, 'Supervisor', 'Producción', '2025-11-21', NULL, NULL, 'activo', 0, '2025-11-21 23:47:48', '2025-11-21 23:47:48'),
(16, 'Antinio rodriguez', NULL, 'antinio.rodriguez@aguacolegial.com', '7775343', 'tiquipaya', NULL, NULL, 'Distribuidor', 'Producción', '2025-11-21', NULL, NULL, 'activo', 0, '2025-11-22 01:11:49', '2025-11-22 01:11:49'),
(17, 'joel buendia', NULL, 'joel.buendia@aguacolegial.com', '74512258', NULL, NULL, NULL, 'Distribuidor', 'Producción', '2025-11-26', NULL, NULL, 'activo', 0, '2025-11-26 06:57:34', '2025-11-26 06:57:34');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `tipo` varchar(50) DEFAULT NULL,
  `unidad_medida` varchar(20) DEFAULT 'Unidad',
  `unidades_por_paquete` int(11) DEFAULT NULL COMMENT 'Cantidad de unidades que contiene cada paquete/bolsa',
  `stock_minimo` int(11) DEFAULT 0,
  `imagen` varchar(255) DEFAULT NULL,
  `estado` enum('activo','inactivo') DEFAULT 'activo',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `nombre`, `descripcion`, `tipo`, `unidad_medida`, `unidades_por_paquete`, `stock_minimo`, `imagen`, `estado`, `created_at`, `updated_at`) VALUES
(1, 'Botellón 20 Litros', '20', 'General', 'litro', NULL, 5, NULL, 'activo', '2025-11-21 23:33:07', '2025-11-27 07:57:43'),
(3, 'Agua Natural', '10 unidad', 'General', 'bolsa', 10, 70, NULL, 'activo', '2025-11-21 23:33:07', '2025-12-01 03:00:47'),
(4, 'Agua Saborizada', '10 unidad', 'General', 'bolsa', 10, 70, NULL, 'activo', '2025-11-21 23:33:07', '2025-12-01 03:00:46'),
(6, 'Gelatina', '25 unidades', 'General', 'bolsa', 25, 10, NULL, 'activo', '2025-11-21 23:33:07', '2025-12-01 03:00:47'),
(8, 'Hielo en Bolsa 3 kg', '3kg', 'General', 'bolsa de hielo', NULL, 5, NULL, 'activo', '2025-11-21 23:33:07', '2025-11-27 07:59:22'),
(9, 'Bolo Grande', '50 unidades', 'General', 'bolsa', 50, 40, NULL, 'activo', '2025-11-21 23:33:07', '2025-12-01 03:00:47'),
(10, 'Bolo Pequeño', '25 unidades', 'General', 'bolsa', 25, 10, NULL, 'activo', '2025-11-21 23:33:07', '2025-12-01 03:00:47'),
(11, 'Dispenser Unidad', '1', 'General', 'unidad', NULL, 5, NULL, 'activo', '2025-11-21 23:48:16', '2025-11-27 07:58:19'),
(12, 'Agua De Limon', '10 unidad', 'General', 'bolsa', 10, 70, NULL, 'activo', '2025-11-22 00:06:23', '2025-12-01 03:00:47');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(100) NOT NULL COMMENT 'Nombre del rol (admin, produccion, inventario, despacho)',
  `observacion` text DEFAULT NULL COMMENT 'Descripción del rol y sus permisos',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id`, `nombre`, `observacion`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'Administrador del sistema', '2025-11-21 23:33:06', '2025-11-21 23:33:06'),
(2, 'produccion', 'Encargado de producción', '2025-11-21 23:33:06', '2025-11-21 23:33:06'),
(3, 'inventario', 'Encargado de inventario', '2025-11-21 23:33:06', '2025-11-21 23:33:06'),
(4, 'despacho', 'Encargado de despachos', '2025-11-21 23:33:06', '2025-11-21 23:33:06');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `personal_id` bigint(20) UNSIGNED DEFAULT NULL,
  `rol_id` bigint(20) UNSIGNED DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(100) NOT NULL COMMENT 'Nombre completo del usuario',
  `email` varchar(100) NOT NULL COMMENT 'Email para login',
  `password` varchar(255) NOT NULL COMMENT 'Contraseña hasheada con bcrypt',
  `id_rol` bigint(20) UNSIGNED NOT NULL,
  `id_personal` bigint(20) UNSIGNED DEFAULT NULL,
  `estado` enum('activo','inactivo') NOT NULL DEFAULT 'activo' COMMENT 'Estado del usuario',
  `ultimo_acceso` timestamp NULL DEFAULT NULL COMMENT 'Última vez que inició sesión',
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `email`, `password`, `id_rol`, `id_personal`, `estado`, `ultimo_acceso`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Administrador', 'admin@colegial.com', '$2y$12$vAKO/ESQU46SVvmOVLNkQOLRyOBUaq7X9HYCo8g2ApvfgQCu3M81.', 1, NULL, 'activo', '2025-12-04 20:48:07', NULL, '2025-11-21 23:33:06', '2025-12-04 20:48:07'),
(4, 'Usuario Despacho', 'despacho@colegial.com', '$2y$12$YuAJA5.XTTDI6xk7AzbkI.h7khO56LimbWuPStQ3ct6DWXh9rZZCq', 4, NULL, 'activo', NULL, NULL, '2025-11-21 23:33:07', '2025-11-21 23:33:07'),
(5, 'Anderson Aguilar', 'anderson.aguilar@aguacolegial.com', '$2y$12$Mpuu.bgtAvnLaEplxJ4OhOcdUWYLtN1oIlm.rrAUiu1ElJM9XKvB2', 2, 11, 'activo', '2025-12-04 14:23:13', 'hxR1GJW1sOZh5QRmUkuyWFxMpWjbMDIe31a6hPrdrlkcIYH7nefSjDN5Z7YW', '2025-11-28 01:35:45', '2025-12-04 14:23:13'),
(6, 'Helen Aguilar', 'helen@gmail.com', '$2y$12$DBqsnITlkIyX0kw8B3cgOOj/bqVpsteZXvft1J9zjjJlpf9.gAciq', 2, 10, 'activo', '2025-12-01 04:23:52', NULL, '2025-11-28 02:40:30', '2025-12-01 04:23:52');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vehiculos`
--

CREATE TABLE `vehiculos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `placa` varchar(10) NOT NULL COMMENT 'Placa del vehículo',
  `id_responsable` bigint(20) UNSIGNED DEFAULT NULL,
  `responsable_texto` varchar(255) DEFAULT NULL,
  `modelo` varchar(100) DEFAULT NULL COMMENT 'Modelo del vehículo',
  `marca` varchar(100) DEFAULT NULL COMMENT 'Marca del vehículo',
  `estado` enum('activo','mantenimiento','inactivo') NOT NULL DEFAULT 'activo' COMMENT 'Estado operativo del vehículo',
  `capacidad` int(10) UNSIGNED DEFAULT NULL COMMENT 'Capacidad de carga en unidades',
  `observacion` text DEFAULT NULL COMMENT 'Observaciones del vehículo',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `vehiculos`
--

INSERT INTO `vehiculos` (`id`, `placa`, `id_responsable`, `responsable_texto`, `modelo`, `marca`, `estado`, `capacidad`, `observacion`, `created_at`, `updated_at`) VALUES
(1, '1869KLBs', NULL, 'Jhonatan matias pizzo aguilar', 'XD', 'TOYOTA', 'inactivo', 700, NULL, '2025-11-22 00:39:26', '2025-11-28 14:14:07'),
(2, '1234ABC', NULL, 'deybi aguilar', 'Hilux', 'TOYOTA', 'activo', 700, NULL, '2025-11-21 20:57:03', '2025-11-21 20:57:03'),
(3, '5678DEF', NULL, 'Sergio Aguilar', 'D-MAX', 'ISUZU', 'mantenimiento', 650, NULL, '2025-11-21 20:57:03', '2025-11-26 19:36:38'),
(4, '9012GHI', NULL, 'nano alvarez', 'Frontier', 'NISSAN', 'activo', 600, NULL, '2025-11-21 20:57:03', '2025-11-21 20:57:03');

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `v_stock_actual`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `v_stock_actual` (
`id` bigint(20) unsigned
,`nombre` varchar(100)
,`descripcion` text
,`tipo` varchar(50)
,`unidad_medida` varchar(20)
,`estado` enum('activo','inactivo')
,`stock_actual` decimal(33,0)
);

-- --------------------------------------------------------

--
-- Estructura para la vista `v_stock_actual`
--
DROP TABLE IF EXISTS `v_stock_actual`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_stock_actual`  AS SELECT `p`.`id` AS `id`, `p`.`nombre` AS `nombre`, `p`.`descripcion` AS `descripcion`, `p`.`tipo` AS `tipo`, `p`.`unidad_medida` AS `unidad_medida`, `p`.`estado` AS `estado`, coalesce((select sum(`i`.`cantidad`) from `inventario` `i` where `i`.`id_producto` = `p`.`id` and `i`.`tipo_movimiento` = 'entrada'),0) - coalesce((select sum(`i`.`cantidad`) from `inventario` `i` where `i`.`id_producto` = `p`.`id` and `i`.`tipo_movimiento` = 'salida'),0) AS `stock_actual` FROM `productos` AS `p` ;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `admin_asignaciones`
--
ALTER TABLE `admin_asignaciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `admin_asignaciones_asignado_por_foreign` (`asignado_por`),
  ADD KEY `admin_asignaciones_id_personal_estado_index` (`id_personal`,`estado`),
  ADD KEY `admin_asignaciones_tipo_asignacion_estado_index` (`tipo_asignacion`,`estado`),
  ADD KEY `admin_asignaciones_modulo_id_referencia_index` (`modulo`,`id_referencia`);

--
-- Indices de la tabla `admin_historial_asignaciones`
--
ALTER TABLE `admin_historial_asignaciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `admin_historial_asignaciones_id_asignacion_foreign` (`id_asignacion`),
  ADD KEY `admin_historial_asignaciones_realizado_por_foreign` (`realizado_por`);

--
-- Indices de la tabla `alertas_stock`
--
ALTER TABLE `alertas_stock`
  ADD PRIMARY KEY (`id`),
  ADD KEY `alertas_stock_id_producto_index` (`id_producto`),
  ADD KEY `alertas_stock_estado_alerta_index` (`estado_alerta`),
  ADD KEY `alertas_stock_nivel_urgencia_index` (`nivel_urgencia`),
  ADD KEY `alertas_stock_fecha_alerta_index` (`fecha_alerta`),
  ADD KEY `alertas_stock_id_producto_estado_alerta_index` (`id_producto`,`estado_alerta`);

--
-- Indices de la tabla `asistencias_semanales`
--
ALTER TABLE `asistencias_semanales`
  ADD PRIMARY KEY (`id`),
  ADD KEY `asistencias_semanales_registrado_por_foreign` (`registrado_por`),
  ADD KEY `asistencias_semanales_fecha_index` (`fecha`),
  ADD KEY `asistencias_semanales_personal_id_fecha_index` (`personal_id`,`fecha`);

--
-- Indices de la tabla `control_fosa_septica`
--
ALTER TABLE `control_fosa_septica`
  ADD PRIMARY KEY (`id`),
  ADD KEY `control_fosa_septica_id_responsable_foreign` (`id_responsable`);

--
-- Indices de la tabla `control_fumigacion`
--
ALTER TABLE `control_fumigacion`
  ADD PRIMARY KEY (`id`),
  ADD KEY `control_fumigacion_id_responsable_foreign` (`id_responsable`);

--
-- Indices de la tabla `control_insumos`
--
ALTER TABLE `control_insumos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `control_insumos_id_responsable_foreign` (`id_responsable`);

--
-- Indices de la tabla `control_mantenimiento_equipos`
--
ALTER TABLE `control_mantenimiento_equipos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `control_mantenimiento_equipos_id_personal_foreign` (`id_personal`);

--
-- Indices de la tabla `control_produccion_diaria`
--
ALTER TABLE `control_produccion_diaria`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `control_produccion_materiales`
--
ALTER TABLE `control_produccion_materiales`
  ADD PRIMARY KEY (`id`),
  ADD KEY `control_produccion_materiales_produccion_id_foreign` (`produccion_id`);

--
-- Indices de la tabla `control_produccion_preparaciones`
--
ALTER TABLE `control_produccion_preparaciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `control_produccion_preparaciones_produccion_id_foreign` (`produccion_id`);

--
-- Indices de la tabla `control_produccion_productos`
--
ALTER TABLE `control_produccion_productos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `control_produccion_productos_produccion_id_foreign` (`produccion_id`),
  ADD KEY `control_produccion_productos_producto_id_foreign` (`producto_id`);

--
-- Indices de la tabla `control_salidas_productos`
--
ALTER TABLE `control_salidas_productos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `control_salidas_productos_id_chofer_foreign` (`id_chofer`),
  ADD KEY `control_salidas_productos_id_responsable_salida_foreign` (`id_responsable_salida`);

--
-- Indices de la tabla `control_tanques_agua`
--
ALTER TABLE `control_tanques_agua`
  ADD PRIMARY KEY (`id`),
  ADD KEY `control_tanques_agua_id_responsable_foreign` (`id_responsable`),
  ADD KEY `control_tanques_agua_id_supervisor_foreign` (`id_supervisor`);

--
-- Indices de la tabla `historial_reportes`
--
ALTER TABLE `historial_reportes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `historial_reportes_id_usuario_foreign` (`id_usuario`);

--
-- Indices de la tabla `inventario`
--
ALTER TABLE `inventario`
  ADD PRIMARY KEY (`id`),
  ADD KEY `inventario_id_producto_index` (`id_producto`),
  ADD KEY `inventario_tipo_movimiento_index` (`tipo_movimiento`),
  ADD KEY `inventario_fecha_movimiento_index` (`fecha_movimiento`),
  ADD KEY `inventario_id_producto_tipo_movimiento_index` (`id_producto`,`tipo_movimiento`),
  ADD KEY `inventario_id_usuario_foreign` (`id_usuario`),
  ADD KEY `inventario_origen_index` (`origen`);

--
-- Indices de la tabla `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indices de la tabla `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `personal`
--
ALTER TABLE `personal`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_email_unique` (`email`),
  ADD KEY `personal_email_index` (`email`),
  ADD KEY `personal_estado_index` (`estado`),
  ADD KEY `personal_cargo_index` (`cargo`),
  ADD KEY `personal_area_index` (`area`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_estado` (`estado`),
  ADD KEY `idx_tipo` (`tipo`),
  ADD KEY `idx_nombre` (`nombre`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_nombre_unique` (`nombre`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `personal_id` (`personal_id`),
  ADD KEY `rol_id` (`rol_id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usuarios_email_unique` (`email`),
  ADD KEY `usuarios_estado_index` (`estado`),
  ADD KEY `usuarios_id_rol_index` (`id_rol`),
  ADD KEY `usuarios_email_index` (`email`),
  ADD KEY `usuarios_id_personal_index` (`id_personal`);

--
-- Indices de la tabla `vehiculos`
--
ALTER TABLE `vehiculos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `vehiculos_placa_unique` (`placa`),
  ADD KEY `vehiculos_estado_index` (`estado`),
  ADD KEY `vehiculos_placa_index` (`placa`),
  ADD KEY `vehiculos_id_responsable_foreign` (`id_responsable`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `admin_asignaciones`
--
ALTER TABLE `admin_asignaciones`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `admin_historial_asignaciones`
--
ALTER TABLE `admin_historial_asignaciones`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `alertas_stock`
--
ALTER TABLE `alertas_stock`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `asistencias_semanales`
--
ALTER TABLE `asistencias_semanales`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=279;

--
-- AUTO_INCREMENT de la tabla `control_fosa_septica`
--
ALTER TABLE `control_fosa_septica`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `control_fumigacion`
--
ALTER TABLE `control_fumigacion`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `control_insumos`
--
ALTER TABLE `control_insumos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `control_mantenimiento_equipos`
--
ALTER TABLE `control_mantenimiento_equipos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `control_produccion_diaria`
--
ALTER TABLE `control_produccion_diaria`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=94;

--
-- AUTO_INCREMENT de la tabla `control_produccion_materiales`
--
ALTER TABLE `control_produccion_materiales`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=114;

--
-- AUTO_INCREMENT de la tabla `control_produccion_preparaciones`
--
ALTER TABLE `control_produccion_preparaciones`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `control_produccion_productos`
--
ALTER TABLE `control_produccion_productos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=529;

--
-- AUTO_INCREMENT de la tabla `control_salidas_productos`
--
ALTER TABLE `control_salidas_productos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=246;

--
-- AUTO_INCREMENT de la tabla `control_tanques_agua`
--
ALTER TABLE `control_tanques_agua`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `historial_reportes`
--
ALTER TABLE `historial_reportes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT de la tabla `inventario`
--
ALTER TABLE `inventario`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1685;

--
-- AUTO_INCREMENT de la tabla `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT de la tabla `personal`
--
ALTER TABLE `personal`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `vehiculos`
--
ALTER TABLE `vehiculos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `admin_asignaciones`
--
ALTER TABLE `admin_asignaciones`
  ADD CONSTRAINT `admin_asignaciones_asignado_por_foreign` FOREIGN KEY (`asignado_por`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `admin_asignaciones_id_personal_foreign` FOREIGN KEY (`id_personal`) REFERENCES `personal` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `admin_historial_asignaciones`
--
ALTER TABLE `admin_historial_asignaciones`
  ADD CONSTRAINT `admin_historial_asignaciones_id_asignacion_foreign` FOREIGN KEY (`id_asignacion`) REFERENCES `admin_asignaciones` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `admin_historial_asignaciones_realizado_por_foreign` FOREIGN KEY (`realizado_por`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `alertas_stock`
--
ALTER TABLE `alertas_stock`
  ADD CONSTRAINT `alertas_stock_id_producto_foreign` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `asistencias_semanales`
--
ALTER TABLE `asistencias_semanales`
  ADD CONSTRAINT `asistencias_semanales_personal_id_foreign` FOREIGN KEY (`personal_id`) REFERENCES `personal` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `asistencias_semanales_registrado_por_foreign` FOREIGN KEY (`registrado_por`) REFERENCES `personal` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `control_fosa_septica`
--
ALTER TABLE `control_fosa_septica`
  ADD CONSTRAINT `control_fosa_septica_id_responsable_foreign` FOREIGN KEY (`id_responsable`) REFERENCES `personal` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `control_fumigacion`
--
ALTER TABLE `control_fumigacion`
  ADD CONSTRAINT `control_fumigacion_id_responsable_foreign` FOREIGN KEY (`id_responsable`) REFERENCES `personal` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `control_insumos`
--
ALTER TABLE `control_insumos`
  ADD CONSTRAINT `control_insumos_id_responsable_foreign` FOREIGN KEY (`id_responsable`) REFERENCES `personal` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `control_mantenimiento_equipos`
--
ALTER TABLE `control_mantenimiento_equipos`
  ADD CONSTRAINT `control_mantenimiento_equipos_id_personal_foreign` FOREIGN KEY (`id_personal`) REFERENCES `personal` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `control_produccion_materiales`
--
ALTER TABLE `control_produccion_materiales`
  ADD CONSTRAINT `control_produccion_materiales_produccion_id_foreign` FOREIGN KEY (`produccion_id`) REFERENCES `control_produccion_diaria` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `control_produccion_preparaciones`
--
ALTER TABLE `control_produccion_preparaciones`
  ADD CONSTRAINT `control_produccion_preparaciones_produccion_id_foreign` FOREIGN KEY (`produccion_id`) REFERENCES `control_produccion_diaria` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `control_produccion_productos`
--
ALTER TABLE `control_produccion_productos`
  ADD CONSTRAINT `control_produccion_productos_produccion_id_foreign` FOREIGN KEY (`produccion_id`) REFERENCES `control_produccion_diaria` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `control_produccion_productos_producto_id_foreign` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `control_salidas_productos`
--
ALTER TABLE `control_salidas_productos`
  ADD CONSTRAINT `control_salidas_productos_id_chofer_foreign` FOREIGN KEY (`id_chofer`) REFERENCES `personal` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `control_salidas_productos_id_responsable_salida_foreign` FOREIGN KEY (`id_responsable_salida`) REFERENCES `personal` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `control_tanques_agua`
--
ALTER TABLE `control_tanques_agua`
  ADD CONSTRAINT `control_tanques_agua_id_responsable_foreign` FOREIGN KEY (`id_responsable`) REFERENCES `personal` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `control_tanques_agua_id_supervisor_foreign` FOREIGN KEY (`id_supervisor`) REFERENCES `personal` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `historial_reportes`
--
ALTER TABLE `historial_reportes`
  ADD CONSTRAINT `historial_reportes_id_usuario_foreign` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `inventario`
--
ALTER TABLE `inventario`
  ADD CONSTRAINT `inventario_id_usuario_foreign` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`personal_id`) REFERENCES `personal` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `users_ibfk_2` FOREIGN KEY (`rol_id`) REFERENCES `roles` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_id_personal_foreign` FOREIGN KEY (`id_personal`) REFERENCES `personal` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `usuarios_id_rol_foreign` FOREIGN KEY (`id_rol`) REFERENCES `roles` (`id`);

--
-- Filtros para la tabla `vehiculos`
--
ALTER TABLE `vehiculos`
  ADD CONSTRAINT `vehiculos_id_responsable_foreign` FOREIGN KEY (`id_responsable`) REFERENCES `personal` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
