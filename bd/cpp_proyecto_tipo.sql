-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 09-07-2018 a las 18:15:53
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
-- Estructura de tabla para la tabla `cpp_proyecto_tipo`
--

CREATE TABLE `cpp_proyecto_tipo` (
  `id` int(11) NOT NULL,
  `id_proyecto_empresa` int(11) NOT NULL,
  `tipo` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `cpp_proyecto_tipo`
--

INSERT INTO `cpp_proyecto_tipo` (`id`, `id_proyecto_empresa`, `tipo`) VALUES
(1, 1, 'Diseño HFC-Relevamiento'),
(2, 1, 'Diseño HFC-Diseño'),
(3, 1, 'Diseño HFC-Diseño F.O.'),
(4, 1, 'Traslado de Redes'),
(5, 1, 'Inmobiliario'),
(6, 3, 'Inmobiliario'),
(7, 3, 'Despliegue FTTX - Relevamiento'),
(8, 3, 'Despliegue FTTX - Diseño'),
(9, 3, 'Empresas Movistar'),
(10, 2, 'Dibujo'),
(11, 2, 'Relevamiento'),
(12, 2, 'Factibilidad'),
(13, 4, 'Dibujo'),
(14, 4, 'Relevamiento'),
(15, 4, 'Factibilidad'),
(16, 5, 'Dibujo'),
(17, 5, 'Relevamiento'),
(18, 5, 'Factibilidad');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `cpp_proyecto_tipo`
--
ALTER TABLE `cpp_proyecto_tipo`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `cpp_proyecto_tipo`
--
ALTER TABLE `cpp_proyecto_tipo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
