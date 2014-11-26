-- phpMyAdmin SQL Dump
-- version 4.2.7.1
-- http://www.phpmyadmin.net
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 26-11-2014 a las 00:41:53
-- Versión del servidor: 5.6.20
-- Versión de PHP: 5.5.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sys_files`
--

CREATE TABLE IF NOT EXISTS `sys_files` (
`id` int(11) NOT NULL,
  `mime_type` varchar(45) NOT NULL,
  `content` blob NOT NULL,
  `size` int(11) DEFAULT NULL,
  `name` varchar(45) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Repository of System Files' AUTO_INCREMENT=0;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `sys_files`
--
ALTER TABLE `sys_files`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `sys_files`
--
ALTER TABLE `sys_files`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=0;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;