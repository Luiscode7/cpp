-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 09-07-2018 a las 16:32:10
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
-- Estructura de tabla para la tabla `cpp_proyecto_empresa`
--

CREATE TABLE `cpp_proyecto_empresa` (
  `id` int(11) NOT NULL,
  `proyecto_empresa` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `cpp_proyecto_empresa`
--

INSERT INTO `cpp_proyecto_empresa` (`id`, `proyecto_empresa`) VALUES
(1, 'CLARO CHILE'),
(2, 'VTR'),
(3, 'MOVISTAR'),
(4, 'ENTEL'),
(5, 'MUNDO PACIFICO');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `cpp_proyecto_empresa`
--
ALTER TABLE `cpp_proyecto_empresa`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `cpp_proyecto_empresa`
--
ALTER TABLE `cpp_proyecto_empresa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
