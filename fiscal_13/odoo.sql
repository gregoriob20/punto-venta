-- phpMyAdmin SQL Dump
-- version 3.3.9
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 16-12-2021 a las 04:03:18
-- Versión del servidor: 5.5.8
-- Versión de PHP: 5.3.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `odoo`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pos_order`
--

CREATE TABLE IF NOT EXISTS `pos_order` (
  `fact_id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` varchar(255) NOT NULL,
  `pos_reference` varchar(255) NOT NULL,
  `cedula` varchar(255) NOT NULL,
  `cliente` varchar(255) NOT NULL,
  `telefono` varchar(255) NOT NULL,
  `usuario` varchar(255) NOT NULL,
  `state` varchar(255) NOT NULL,
  `nb_caja` varchar(255) NOT NULL,
  `direccion` text,
  `compania` varchar(255) DEFAULT NULL,
  `status_local` varchar(255) NOT NULL DEFAULT 'borrador',
  `tasa_dia` varchar(255) NOT NULL,
  `fact_afect` varchar(255) NOT NULL,
  PRIMARY KEY (`fact_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Volcar la base de datos para la tabla `pos_order`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pos_order_line`
--

CREATE TABLE IF NOT EXISTS `pos_order_line` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `producto` varchar(512) DEFAULT NULL,
  `line_order_id` int(11) NOT NULL,
  `order_id` varchar(255) NOT NULL,
  `price_unit` double(17,2) NOT NULL,
  `cantidad` double(17,3) NOT NULL,
  `sub_total` double(17,2) NOT NULL,
  `tipo_doc` int(11) NOT NULL,
  `valor_alicuota` double(17,3) NOT NULL,
  `sub_total_incl` double(17,2) NOT NULL,
  `ocultar` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Volcar la base de datos para la tabla `pos_order_line`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pos_secuencia_nc`
--

CREATE TABLE IF NOT EXISTS `pos_secuencia_nc` (
  `nc_id` int(11) NOT NULL AUTO_INCREMENT,
  `pos_order_id` int(11) NOT NULL,
  PRIMARY KEY (`nc_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Volcar la base de datos para la tabla `pos_secuencia_nc`
--

