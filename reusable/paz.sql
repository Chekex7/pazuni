-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 30-06-2025 a las 20:10:08
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
-- Base de datos: `paz`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `jdp`
--

CREATE TABLE `jdp` (
  `id` int(11) NOT NULL,
  `user` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `fecha_registro` datetime DEFAULT current_timestamp(),
  `ultimo_acceso` datetime DEFAULT NULL,
  `activo` tinyint(1) DEFAULT 0,
  `rol` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `jdp`
--

INSERT INTO `jdp` (`id`, `user`, `email`, `password_hash`, `fecha_registro`, `ultimo_acceso`, `activo`, `rol`) VALUES
(1, 'sebastian', 'sebastian@gmail.com', '$2y$12$72flIaUoIBDCBd0Whca./O/hOsGFvPTU/.tAp.0uuoNRqjZfBOfWK', '2025-06-28 01:52:56', '2025-06-28 02:18:03', 1, NULL),
(2, 'lucia', 'lucia@gmail.com', '$2y$12$n/2zgP905K95bRCrazB3HefZmxCkA.zEpREhNHdgXWIjuJeOkTXb.', '2025-06-28 02:20:43', NULL, 0, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `logins`
--

CREATE TABLE `logins` (
  `id` int(11) NOT NULL,
  `user` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `fecha_registro` datetime DEFAULT current_timestamp(),
  `ultimo_acceso` datetime DEFAULT NULL,
  `activo` tinyint(1) DEFAULT 1,
  `rol` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `perfilx`
--

CREATE TABLE `perfilx` (
  `Id_perfil` int(11) NOT NULL,
  `logins_id` int(11) DEFAULT NULL,
  `nombre` varchar(50) NOT NULL,
  `apellido` varchar(50) NOT NULL,
  `cedula` int(11) NOT NULL,
  `fecha_nac` date NOT NULL,
  `sexo` varchar(10) DEFAULT NULL,
  `direccion` text DEFAULT NULL,
  `numero_casa` varchar(20) DEFAULT NULL,
  `celular` varchar(15) DEFAULT NULL,
  `otro_celular` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `fecha_registro` datetime NOT NULL,
  `ultimo_acceso` datetime DEFAULT NULL,
  `activo` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `email`, `password_hash`, `fecha_registro`, `ultimo_acceso`, `activo`) VALUES
(1, 'leo@gmail.com', '3f3a72ee424c00865f3959fd78e395ce:$argon2id$v=19$m=65536,t=4,p=1$SWl1ajRLbXdzcVRIRHBhcg$akHWRAyuVEXqP3Ib62X7PJqVd+3l5KMZcyqr7fsEKLk', '2025-06-24 20:39:44', '2025-06-25 02:55:59', 1),
(2, 'gabi@gmail.com', 'e3825df85082530d98f682fb1f06fffb:$argon2id$v=19$m=65536,t=4,p=1$bFFoQVdLa0ZRS1NoVThNWg$jjPEi7ZQhY04h/OUvRvNRT8sjJhcl1SrVT20WRbq/7k', '2025-06-24 22:09:02', NULL, 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `jdp`
--
ALTER TABLE `jdp`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user` (`user`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indices de la tabla `logins`
--
ALTER TABLE `logins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user` (`user`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indices de la tabla `perfilx`
--
ALTER TABLE `perfilx`
  ADD PRIMARY KEY (`Id_perfil`),
  ADD UNIQUE KEY `cedula` (`cedula`),
  ADD KEY `logins_id` (`logins_id`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `jdp`
--
ALTER TABLE `jdp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `logins`
--
ALTER TABLE `logins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `perfilx`
--
ALTER TABLE `perfilx`
  MODIFY `Id_perfil` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `perfilx`
--
ALTER TABLE `perfilx`
  ADD CONSTRAINT `perfilx_ibfk_1` FOREIGN KEY (`logins_id`) REFERENCES `logins` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
