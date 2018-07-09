-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 09-07-2018 a las 18:16:13
-- Versión del servidor: 10.1.25-MariaDB
-- Versión de PHP: 5.6.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `km1`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cpp_actividades`
--

CREATE TABLE `cpp_actividades` (
  `id` int(11) NOT NULL,
  `id_proyecto_tipo` int(11) NOT NULL,
  `actividad` varchar(200) NOT NULL,
  `unidad` varchar(200) NOT NULL,
  `valor` int(11) NOT NULL,
  `porcentaje` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `cpp_actividades`
--

INSERT INTO `cpp_actividades` (`id`, `id_proyecto_tipo`, `actividad`, `unidad`, `valor`, `porcentaje`) VALUES
(1, 1, 'Levantamiento – HP', 'HP', 1, 100),
(2, 1, 'Dibujo', 'HP', 1, 100),
(3, 1, 'Control Calidad  HP', 'HP', 1, 100),
(4, 2, 'Segmentación', 'HP', 1, 100),
(5, 2, 'Conglomerado', 'HH', 1, 100),
(6, 2, 'Diseño', 'HP', 1, 100),
(7, 2, 'Control calidad', 'HH', 1, 100),
(8, 3, 'Preparación información', 'HH', 1, 100),
(9, 3, 'Relevamiento', 'HP', 1, 100),
(10, 3, 'Diseño FO', 'MTS', 1, 100),
(11, 3, 'Control Calidad', 'HH', 1, 100),
(12, 4, 'Informe Retiro informacion', 'HH', 1, 100),
(13, 4, 'Informe Desarrollo Plano y Carta', 'HH', 1, 100),
(14, 4, 'Informe Control Calidad', 'HH', 1, 100),
(15, 4, 'Presupuesto Retiro informacion', 'HH', 1, 100),
(16, 4, 'Presupuesto Desarrollo Prepoyecto', 'HH', 1, 100),
(17, 4, 'Presupuesto Control Calidad', 'HH', 1, 100),
(18, 4, 'Proyecto Retiro informacion', 'HH', 1, 100),
(19, 4, 'Proyecto Preparación información', 'HH', 1, 100),
(20, 4, 'Proyecto Relevamiento', 'MTS', 1, 100),
(21, 4, 'Proyecto Diseño FO CU', 'MTS', 1, 100),
(22, 4, 'Proyecto Diseño HFC', 'HP', 1, 100),
(23, 4, 'Proyecto Control Calidad', 'HH', 1, 100),
(24, 4, 'BTS Retiro informacion', 'HH', 1, 100),
(25, 4, 'BTS Preparación información', 'HH', 1, 100),
(26, 4, 'BTS Relevamiento', 'MTS', 1, 100),
(27, 4, 'BTS Diseño FO', 'MTS', 1, 100),
(28, 4, 'BTS Control Calidad', 'HH', 1, 100),
(29, 5, 'Retiro informacion', 'HH', 1, 100),
(30, 5, 'Preparación información', 'HH', 1, 100),
(31, 5, 'Relevamiento', 'HP', 1, 100),
(32, 5, 'Diseño VERTICAL', 'HP', 1, 100),
(33, 5, 'Control Calidad', 'HH', 1, 100),
(34, 6, 'Preparación información', 'HH', 1, 100),
(35, 6, 'Relevamiento', 'HP', 1, 100),
(36, 6, 'Diseño VERTICAL', 'HP', 1, 100),
(37, 6, 'Control Calidad', 'HH', 1, 100),
(38, 7, 'Levantamiento – HP', 'HP', 1, 100),
(39, 7, 'Dibujo', 'HP', 1, 100),
(40, 7, 'Control Calidad  HP', 'HP', 1, 100),
(41, 8, 'Macro DISEÑO', 'HP', 1, 100),
(42, 8, 'Conglomerado', 'HH', 1, 100),
(43, 8, 'Diseño', 'HP', 1, 100),
(44, 8, 'Certificado de numero', 'HP', 1, 100),
(45, 8, 'Permisos', 'HP', 1, 100),
(46, 8, 'Control calidad', 'HH', 1, 100),
(47, 9, 'Preparación información', 'HH', 1, 100),
(48, 9, 'Relevamiento', 'HP', 1, 100),
(49, 9, 'Diseño FTTX', 'HP', 1, 100),
(50, 9, 'Control Calidad', 'HH', 1, 100),
(51, 10, 'Dibujo cartografico', 'HP', 1, 100),
(52, 10, 'Dibujo proyecto horizontal', 'HP', 1, 100),
(53, 10, 'Dibujo proyecto vertical', 'HP', 1, 100),
(54, 11, 'Relevamiento cartografico', 'HP', 1, 100),
(55, 11, 'Relevamiento ruta F.O.', 'MTS', 1, 100),
(56, 11, 'Relevamiento proyecto horizontal ', 'HP', 1, 100),
(57, 11, 'Relevamiento proyecto vertical', 'HP', 1, 100),
(58, 11, 'Survey de proyectos', 'UNIDAD', 1, 100),
(59, 12, 'Factibilidad visita', 'UNIDAD', 1, 100),
(60, 12, 'Factibilidad ingeniería de detalle', 'MTS', 1, 100),
(61, 13, 'Dibujo cartografico', 'HP', 1, 100),
(62, 13, 'Dibujo proyecto horizontal', 'HP', 1, 100),
(63, 13, 'Dibujo proyecto vertical', 'HP', 1, 100),
(64, 14, 'Relevamiento cartografico', 'HP', 1, 100),
(65, 14, 'Relevamiento ruta F.O.', 'MTS', 1, 100),
(66, 14, 'Relevamiento proyecto horizontal ', 'HP', 1, 100),
(67, 14, 'Relevamiento proyecto vertical', 'HP', 1, 100),
(68, 14, 'Survey de proyectos', 'UNIDAD', 1, 100),
(69, 15, 'Factibilidad visita', 'UNIDAD', 1, 100),
(70, 15, 'Factibilidad ingeniería de detalle', 'MTS', 1, 100),
(71, 16, 'Dibujo cartografico', 'HP', 1, 100),
(72, 16, 'Dibujo proyecto horizontal', 'HP', 1, 100),
(73, 16, 'Dibujo proyecto vertical', 'HP', 1, 100),
(74, 17, 'Relevamiento cartografico', 'HP', 1, 100),
(75, 17, 'Relevamiento ruta F.O.', 'METROS', 1, 100),
(76, 17, 'Relevamiento proyecto horizontal ', 'HP', 1, 100),
(77, 17, 'Relevamiento proyecto vertical', 'HP', 1, 100),
(78, 17, 'Survey de proyectos', 'UNIDAD', 1, 100),
(79, 18, 'Factibilidad visita', 'UNIDAD', 1, 100),
(80, 18, 'Factibilidad ingeniería de detalle', 'MTS', 1, 100);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `cpp_actividades`
--
ALTER TABLE `cpp_actividades`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `cpp_actividades`
--
ALTER TABLE `cpp_actividades`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
