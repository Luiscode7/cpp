-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 09-07-2018 a las 18:16:45
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
-- Estructura de tabla para la tabla `cpp`
--

CREATE TABLE `cpp` (
  `id` int(11) NOT NULL,
  `id_actividad` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_supervisor` int(11) NOT NULL,
  `id_digitador` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `cantidad` int(11) NOT NULL,
  `proyecto_descripcion` varchar(200) NOT NULL,
  `estado` char(1) NOT NULL,
  `comentarios` varchar(300) NOT NULL,
  `fecha_aprobacion` date NOT NULL,
  `fecha_digitacion` date NOT NULL,
  `ultima_actualizacion` varchar(300) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `cpp`
--

INSERT INTO `cpp` (`id`, `id_actividad`, `id_usuario`, `id_supervisor`, `id_digitador`, `fecha`, `cantidad`, `proyecto_descripcion`, `estado`, `comentarios`, `fecha_aprobacion`, `fecha_digitacion`, `ultima_actualizacion`) VALUES
(5, 32, 432, 0, 432, '2018-07-09', 1, '', '0', '', '0000-00-00', '2018-07-09', '2018-07-09 12:15:32 | Ricardo Hernández'),
(6, 10, 432, 0, 432, '2018-07-04', 1, '', '0', '', '0000-00-00', '2018-07-09', '2018-07-09 12:15:16 | Ricardo Hernández');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `cpp`
--
ALTER TABLE `cpp`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `cpp`
--
ALTER TABLE `cpp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
