-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 03-12-2025 a las 04:53:17
-- Versión del servidor: 10.4.28-MariaDB
-- Versión de PHP: 8.2.4
CREATE DATABASE IF NOT EXISTS `emergenciascuautitlan` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `emergenciascuautitlan`;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";
/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */
;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */
;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */
;
/*!40101 SET NAMES utf8mb4 */
;
--
-- Base de datos: `emergenciascuautitlan`
--
-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `tblbomberos`
--
CREATE TABLE `tblbomberos` (
    `Id` int(5) NOT NULL,
    `NombreVictima` varchar(100) NOT NULL,
    `EdadVictima` int(2) NOT NULL,
    `Evento` varchar(60) NOT NULL,
    `LugarEvento` text NOT NULL,
    `NumeroTelEmergencia` int(10) NOT NULL,
    `Correo` varchar(20) NOT NULL,
    `DireccionVictima` text NOT NULL,
    `DescripcionEvento` text NOT NULL,
    `Estatus` ENUM('Pendiente', 'En proceso', 'Atendido') DEFAULT 'Pendiente',
    `Fecha_reporte` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;
-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `tblcentral`
--
CREATE TABLE `tblcentral` (
    `Id` int(5) NOT NULL,
    `CentralReportes` varchar(120) NOT NULL,
    `Departamentos` varchar(50) NOT NULL,
    `NombreVictima` varchar(100) NOT NULL,
    `EdadVictima` int(2) NOT NULL,
    `NumeroTelEmergencia` int(10) NOT NULL,
    `DireccionVictima` text NOT NULL,
    `Evento` varchar(20) NOT NULL,
    `DescripcionEvento` text NOT NULL,
    `Prioridad` varchar(10) NOT NULL,
    `Estatus` ENUM('Pendiente', 'En proceso', 'Atendido') DEFAULT 'Pendiente',
    `Fecha_reporte` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;
-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `tblmedico`
--
CREATE TABLE `tblmedico` (
    `Id` int(5) NOT NULL,
    `NombreVictima` varchar(100) NOT NULL,
    `EdadVictima` int(2) NOT NULL,
    `Evento` varchar(60) NOT NULL,
    `LugarEvento` text NOT NULL,
    `NumeroTelEmergencia` int(10) NOT NULL,
    `Correo` varchar(60) NOT NULL,
    `DireccionVictima` varchar(200) NOT NULL,
    `DescripcionEvento` text NOT NULL,
    `Estatus` ENUM('Pendiente', 'En proceso', 'Atendido') DEFAULT 'Pendiente',
    `Fecha_reporte` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;
-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `tblpolicias`
--
CREATE TABLE `tblpolicias` (
    `Id` int(5) NOT NULL,
    `NombreVictima` varchar(100) NOT NULL,
    `EdadVictima` int(2) NOT NULL,
    `Evento` varchar(60) NOT NULL,
    `LugarEvento` text NOT NULL,
    `NumeroTelEmergencia` int(10) NOT NULL,
    `Correo` varchar(60) NOT NULL,
    `DireccionVictima` text NOT NULL,
    `DescripcionEvento` text NOT NULL,
    `Estatus` ENUM('Pendiente', 'En proceso', 'Atendido') DEFAULT 'Pendiente',
    `Fecha_reporte` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;
-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `tblusuarios`
--
CREATE TABLE `tblusuarios` (
    `Id` int(11) NOT NULL,
    `nombre_completo` varchar(100) NOT NULL,
    `username` varchar(50) NOT NULL,
    `password` varchar(255) NOT NULL,
    `rol` enum('admin', 'bomberos', 'policia', 'medico') NOT NULL,
    `email` varchar(100) NOT NULL,
    `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
    `estatus` enum('activo', 'inactivo') NOT NULL DEFAULT 'activo'
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_general_ci;
-- --------------------------------------------------------
--
-- Datos de ejemplo
INSERT INTO tblbomberos (
        NombreVictima,
        EdadVictima,
        Evento,
        LugarEvento,
        NumeroTelEmergencia,
        DireccionVictima,
        DescripcionEvento
    )
VALUES (
        'Juan Pérez',
        35,
        'Incendio',
        'Casa habitación',
        '5512345678',
        'Av. Principal #123',
        'Incendio en cocina por aceite'
    ),
    (
        'María López',
        28,
        'Fuga gas',
        'Restaurante',
        '5598765432',
        'Calle Secundaria #45',
        'Olor a gas fuerte en el local'
    ),
    (
        'Carlos García',
        42,
        'Rescate animal',
        'Árbol del parque',
        '5532145698',
        'Parque Central',
        'Gato atrapado en árbol alto'
    ),
    (
        'Ana Martínez',
        50,
        'Choque',
        'Avenida Reforma',
        '5547852369',
        'Av. Reforma y 5 de Mayo',
        'Choque entre dos vehículos'
    );
-- --------------------------------------------------------
--
-- Volcado de datos para la tabla `tblusuarios`
--
INSERT INTO `tblusuarios` (
        `nombre_completo`,
        `username`,
        `password`,
        `rol`,
        `email`
    )
VALUES (
        'Amezcua Sagrero, Sergio Daniel',
        'sergio.amezcua',
        '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        'medico',
        'sergio@emergencias.com'
    ),
    (
        'Lujano Valeriano, Dulce Odalys',
        'dulce.lujano',
        '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        'policia',
        'dulce@emergencias.com'
    ),
    (
        'Cruz Vergara, Laura Rocxana',
        'laura.cruz',
        '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        'admin',
        'laura@emergencias.com'
    ),
    (
        'Vargas Almanza, Gerardo Enrique',
        'gerardo.vargas',
        '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        'bomberos',
        'gerardo@emergencias.com'
    );
--
-- Índices para tablas volcadas
--
--
-- Indices de la tabla `tblbomberos`
--
ALTER TABLE `tblbomberos`
ADD PRIMARY KEY (`Id`);
--
-- Indices de la tabla `tblcentral`
--
ALTER TABLE `tblcentral`
ADD PRIMARY KEY (`Id`);
--
-- Indices de la tabla `tblmedico`
--
ALTER TABLE `tblmedico`
ADD PRIMARY KEY (`Id`);
--
-- Indices de la tabla `tblpolicias`
--
ALTER TABLE `tblpolicias`
ADD PRIMARY KEY (`Id`);
--
-- Indices de la tabla `tblusuarios`
--
ALTER TABLE `tblusuarios`
ADD PRIMARY KEY (`Id`),
    ADD UNIQUE KEY `username` (`username`),
    ADD UNIQUE KEY `email` (`email`);
--
-- AUTO_INCREMENT de las tablas volcadas
--
--
-- AUTO_INCREMENT de la tabla `tblbomberos`
--
ALTER TABLE `tblbomberos`
MODIFY `Id` int(5) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `tblcentral`
--
ALTER TABLE `tblcentral`
MODIFY `Id` int(5) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `tblmedico`
--
ALTER TABLE `tblmedico`
MODIFY `Id` int(5) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `tblpolicias`
--
ALTER TABLE `tblpolicias`
MODIFY `Id` int(5) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `tblusuarios`
--
ALTER TABLE `tblusuarios`
MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */
;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */
;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */
;