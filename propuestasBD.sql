-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 05-01-2025 a las 00:22:24
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
-- Base de datos: `bdik_propuestas`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `propuestas`
--

CREATE TABLE `propuestas` (
  `idPropuestas` int(12) NOT NULL,
  `tipoServicio` varchar(200) NOT NULL,
  `modalidad` varchar(200) NOT NULL,
  `numeroSucursales` int(5) NOT NULL,
  `numeroUsuarios` int(10) NOT NULL,
  `tiempo` varchar(200) NOT NULL,
  `idUsuario` int(11) DEFAULT NULL,
  `idClientes` int(11) DEFAULT NULL,
  `detallePropuesta` varchar(5000) NOT NULL,
  `archivoPropuesta` varchar(255) DEFAULT NULL,
  `validaPropuesta` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `propuestas`
--

INSERT INTO `propuestas` (`idPropuestas`, `tipoServicio`, `modalidad`, `numeroSucursales`, `numeroUsuarios`, `tiempo`, `idUsuario`, `idClientes`, `detallePropuesta`, `archivoPropuesta`, `validaPropuesta`) VALUES
(2, 'Orfeo - Capacitación', 'Remoto', 5, 5, 'Un Mes', 5, 13, 'Propuesta básica', NULL, 'Corregir Propuesta'),
(3, 'Orfeo - Desarrollo', 'Remoto', 123, 3, 'Seis Meses', 5, 13, 'juam', NULL, 'Aprobar Propuesta'),
(4, 'Gestión documental', 'Remoto', 32, 3, 'Diez Meses', 5, 13, '2323', NULL, 'Corregir Propuesta'),
(5, 'Orfeo - Implantación', 'Remoto', 4, 2, 'Cuatro Meses', 4, 13, 'Este es el deatalle', NULL, 'Corregir Propuesta');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `propuestas`
--
ALTER TABLE `propuestas`
  ADD PRIMARY KEY (`idPropuestas`),
  ADD KEY `fk_usuario` (`idUsuario`),
  ADD KEY `fk_cliente` (`idClientes`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `propuestas`
--
ALTER TABLE `propuestas`
  MODIFY `idPropuestas` int(12) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `propuestas`
--
ALTER TABLE `propuestas`
  ADD CONSTRAINT `fk_cliente` FOREIGN KEY (`idClientes`) REFERENCES `clientes` (`idClientes`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_usuario` FOREIGN KEY (`idUsuario`) REFERENCES `usuarios` (`idUsuario`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
