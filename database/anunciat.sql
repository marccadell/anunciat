-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 18-02-2025 a las 16:02:42
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
-- Base de datos: `anunciat`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `administradores`
--

CREATE TABLE `administradores` (
  `id_admin` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `foto_perfil` varchar(255) DEFAULT NULL,
  `fecha_registro` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `administradores`
--

INSERT INTO `administradores` (`id_admin`, `nombre`, `apellido`, `email`, `password`, `foto_perfil`, `fecha_registro`) VALUES
(1, 'admin', 'admin', 'admin@admin.com', '827ccb0eea8a706c4c34a16891f84e7b', 'assets/imgs/dummie/administrador.png', '2025-02-09 17:37:04');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `anuncios`
--

CREATE TABLE `anuncios` (
  `id_anuncio` int(11) NOT NULL,
  `id_estudiante` int(11) NOT NULL,
  `id_categoria` int(11) NOT NULL,
  `titulo` varchar(150) NOT NULL,
  `descripcion` text NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `fecha_publicacion` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `anuncios`
--

INSERT INTO `anuncios` (`id_anuncio`, `id_estudiante`, `id_categoria`, `titulo`, `descripcion`, `precio`, `fecha_publicacion`) VALUES
(1, 1, 1, 'test', 'test', 20.00, '2025-02-09 17:39:38');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `id_categoria` int(11) NOT NULL,
  `id_admin` int(11) NOT NULL,
  `nombre_categoria` varchar(100) NOT NULL,
  `fecha_modificacion` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`id_categoria`, `id_admin`, `nombre_categoria`, `fecha_modificacion`) VALUES
(1, 1, 'Libros', '2025-02-09 17:39:19');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estudiantes`
--

CREATE TABLE `estudiantes` (
  `id_estudiante` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `escuela` varchar(100) DEFAULT NULL,
  `ubicacion` varchar(255) DEFAULT NULL,
  `telefono` varchar(15) DEFAULT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `genero` enum('Masculino','Femenino','Otro') DEFAULT NULL,
  `foto_perfil` varchar(255) DEFAULT NULL,
  `fecha_registro` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estudiantes`
--

INSERT INTO `estudiantes` (`id_estudiante`, `nombre`, `apellido`, `email`, `password`, `escuela`, `ubicacion`, `telefono`, `fecha_nacimiento`, `genero`, `foto_perfil`, `fecha_registro`) VALUES
(1, 'Marc', 'Adell', 'madell@gmail.com', '827ccb0eea8a706c4c34a16891f84e7b', 'Ilerna', 'Barcelona', '747793766', '2000-01-22', 'Masculino', 'assets/imgs/dummie/estudiante.png', '2025-02-08 14:49:36');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `familias`
--

CREATE TABLE `familias` (
  `id_familia` int(11) NOT NULL,
  `id_anuncio` int(11) NOT NULL,
  `id_categoria` int(11) NOT NULL,
  `id_estudiante` int(11) NOT NULL,
  `tag1` varchar(100) NOT NULL,
  `tag2` varchar(100) DEFAULT NULL,
  `tag3` varchar(100) DEFAULT NULL,
  `tag4` varchar(100) DEFAULT NULL,
  `tag5` varchar(100) DEFAULT NULL,
  `estado_producto` enum('Nuevo','Excelente','Bueno','Regular','Mal Estado') NOT NULL DEFAULT 'Bueno',
  `fecha_creacion` datetime NOT NULL DEFAULT current_timestamp(),
  `tipo_producto` enum('Electrónica','Ropa','Libros','Muebles','Electrodomésticos','Deportes y Ocio','Instrumentos Musicales','Coches y Motos','Juguetes','Hogar y Jardín','Arte y Coleccionismo','Belleza y Salud','Herramientas','Mascotas y Accesorios','Material de Oficina','Cine, Música y Series','Videojuegos y Consolas','Cámaras y Fotografía','Relojes y Joyas','Bicicletas','Servicios','Inmuebles','Viajes','Moda y Accesorios','Otros') NOT NULL DEFAULT 'Otros'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `favoritos`
--

CREATE TABLE `favoritos` (
  `id_favorito` int(11) NOT NULL,
  `id_estudiante` int(11) NOT NULL,
  `id_anuncio` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `favoritos`
--

INSERT INTO `favoritos` (`id_favorito`, `id_estudiante`, `id_anuncio`) VALUES
(5, 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `filtros`
--

CREATE TABLE `filtros` (
  `id_filtro` int(11) NOT NULL,
  `nombre_filtro` varchar(100) NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  `valor` varchar(100) NOT NULL,
  `tipo_filtro` enum('precio','categoria','estado','fecha','ubicacion','otro') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `imagenes_anuncios`
--

CREATE TABLE `imagenes_anuncios` (
  `id_imagen` int(11) NOT NULL,
  `id_anuncio` int(11) NOT NULL,
  `ruta_imagen` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `imagenes_anuncios`
--

INSERT INTO `imagenes_anuncios` (`id_imagen`, `id_anuncio`, `ruta_imagen`) VALUES
(1, 1, 'assets/imgs/ads/67a8da4a34f39.jpg');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mensajes`
--

CREATE TABLE `mensajes` (
  `id_mensaje` int(11) NOT NULL,
  `id_remitente` int(11) NOT NULL,
  `id_destinatario` int(11) NOT NULL,
  `mensaje` text NOT NULL,
  `fecha_envio` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notificaciones`
--

CREATE TABLE `notificaciones` (
  `id_notificacion` int(11) NOT NULL,
  `id_estudiante` int(11) NOT NULL,
  `mensaje` varchar(255) NOT NULL,
  `leido` tinyint(1) NOT NULL DEFAULT 0,
  `fecha_notificacion` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `transacciones`
--

CREATE TABLE `transacciones` (
  `id_transaccion` int(11) NOT NULL,
  `id_anuncio` int(11) NOT NULL,
  `id_comprador` int(11) NOT NULL,
  `fecha_transaccion` datetime NOT NULL DEFAULT current_timestamp(),
  `monto` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `valoraciones`
--

CREATE TABLE `valoraciones` (
  `id_valoracion` int(11) NOT NULL,
  `id_estudiante_valorador` int(11) NOT NULL,
  `id_estudiante_valorado` int(11) NOT NULL,
  `puntuacion` int(11) NOT NULL CHECK (`puntuacion` between 1 and 5),
  `comentario` text DEFAULT NULL,
  `fecha_valoracion` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `administradores`
--
ALTER TABLE `administradores`
  ADD PRIMARY KEY (`id_admin`),
  ADD UNIQUE KEY `email_UNIQUE` (`email`);

--
-- Indices de la tabla `anuncios`
--
ALTER TABLE `anuncios`
  ADD PRIMARY KEY (`id_anuncio`),
  ADD KEY `id_estudiante` (`id_estudiante`),
  ADD KEY `id_categoria` (`id_categoria`);

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id_categoria`),
  ADD KEY `id_admin` (`id_admin`);

--
-- Indices de la tabla `estudiantes`
--
ALTER TABLE `estudiantes`
  ADD PRIMARY KEY (`id_estudiante`),
  ADD UNIQUE KEY `email_UNIQUE` (`email`);

--
-- Indices de la tabla `familias`
--
ALTER TABLE `familias`
  ADD PRIMARY KEY (`id_familia`),
  ADD KEY `id_anuncio` (`id_anuncio`),
  ADD KEY `id_categoria` (`id_categoria`),
  ADD KEY `fk_familias_estudiante` (`id_estudiante`);

--
-- Indices de la tabla `favoritos`
--
ALTER TABLE `favoritos`
  ADD PRIMARY KEY (`id_favorito`),
  ADD KEY `id_estudiante` (`id_estudiante`),
  ADD KEY `id_anuncio` (`id_anuncio`);

--
-- Indices de la tabla `filtros`
--
ALTER TABLE `filtros`
  ADD PRIMARY KEY (`id_filtro`);

--
-- Indices de la tabla `imagenes_anuncios`
--
ALTER TABLE `imagenes_anuncios`
  ADD PRIMARY KEY (`id_imagen`),
  ADD KEY `id_anuncio` (`id_anuncio`);

--
-- Indices de la tabla `mensajes`
--
ALTER TABLE `mensajes`
  ADD PRIMARY KEY (`id_mensaje`),
  ADD KEY `id_remitente` (`id_remitente`),
  ADD KEY `id_destinatario` (`id_destinatario`);

--
-- Indices de la tabla `notificaciones`
--
ALTER TABLE `notificaciones`
  ADD PRIMARY KEY (`id_notificacion`),
  ADD KEY `id_estudiante` (`id_estudiante`);

--
-- Indices de la tabla `transacciones`
--
ALTER TABLE `transacciones`
  ADD PRIMARY KEY (`id_transaccion`),
  ADD KEY `id_anuncio` (`id_anuncio`),
  ADD KEY `id_comprador` (`id_comprador`);

--
-- Indices de la tabla `valoraciones`
--
ALTER TABLE `valoraciones`
  ADD PRIMARY KEY (`id_valoracion`),
  ADD KEY `id_estudiante_valorador` (`id_estudiante_valorador`),
  ADD KEY `id_estudiante_valorado` (`id_estudiante_valorado`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `administradores`
--
ALTER TABLE `administradores`
  MODIFY `id_admin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `anuncios`
--
ALTER TABLE `anuncios`
  MODIFY `id_anuncio` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id_categoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `estudiantes`
--
ALTER TABLE `estudiantes`
  MODIFY `id_estudiante` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `familias`
--
ALTER TABLE `familias`
  MODIFY `id_familia` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `favoritos`
--
ALTER TABLE `favoritos`
  MODIFY `id_favorito` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `filtros`
--
ALTER TABLE `filtros`
  MODIFY `id_filtro` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `imagenes_anuncios`
--
ALTER TABLE `imagenes_anuncios`
  MODIFY `id_imagen` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `mensajes`
--
ALTER TABLE `mensajes`
  MODIFY `id_mensaje` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `notificaciones`
--
ALTER TABLE `notificaciones`
  MODIFY `id_notificacion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `transacciones`
--
ALTER TABLE `transacciones`
  MODIFY `id_transaccion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `valoraciones`
--
ALTER TABLE `valoraciones`
  MODIFY `id_valoracion` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `anuncios`
--
ALTER TABLE `anuncios`
  ADD CONSTRAINT `anuncios_ibfk_1` FOREIGN KEY (`id_estudiante`) REFERENCES `estudiantes` (`id_estudiante`),
  ADD CONSTRAINT `anuncios_ibfk_2` FOREIGN KEY (`id_categoria`) REFERENCES `categorias` (`id_categoria`);

--
-- Filtros para la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD CONSTRAINT `categorias_ibfk_1` FOREIGN KEY (`id_admin`) REFERENCES `administradores` (`id_admin`);

--
-- Filtros para la tabla `familias`
--
ALTER TABLE `familias`
  ADD CONSTRAINT `familias_ibfk_1` FOREIGN KEY (`id_anuncio`) REFERENCES `anuncios` (`id_anuncio`) ON DELETE CASCADE,
  ADD CONSTRAINT `familias_ibfk_2` FOREIGN KEY (`id_categoria`) REFERENCES `categorias` (`id_categoria`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_familias_estudiante` FOREIGN KEY (`id_estudiante`) REFERENCES `estudiantes` (`id_estudiante`) ON DELETE CASCADE;

--
-- Filtros para la tabla `favoritos`
--
ALTER TABLE `favoritos`
  ADD CONSTRAINT `favoritos_ibfk_1` FOREIGN KEY (`id_estudiante`) REFERENCES `estudiantes` (`id_estudiante`),
  ADD CONSTRAINT `favoritos_ibfk_2` FOREIGN KEY (`id_anuncio`) REFERENCES `anuncios` (`id_anuncio`);

--
-- Filtros para la tabla `imagenes_anuncios`
--
ALTER TABLE `imagenes_anuncios`
  ADD CONSTRAINT `imagenes_anuncios_ibfk_1` FOREIGN KEY (`id_anuncio`) REFERENCES `anuncios` (`id_anuncio`);

--
-- Filtros para la tabla `mensajes`
--
ALTER TABLE `mensajes`
  ADD CONSTRAINT `mensajes_ibfk_1` FOREIGN KEY (`id_remitente`) REFERENCES `estudiantes` (`id_estudiante`),
  ADD CONSTRAINT `mensajes_ibfk_2` FOREIGN KEY (`id_destinatario`) REFERENCES `estudiantes` (`id_estudiante`);

--
-- Filtros para la tabla `notificaciones`
--
ALTER TABLE `notificaciones`
  ADD CONSTRAINT `notificaciones_ibfk_1` FOREIGN KEY (`id_estudiante`) REFERENCES `estudiantes` (`id_estudiante`);

--
-- Filtros para la tabla `transacciones`
--
ALTER TABLE `transacciones`
  ADD CONSTRAINT `transacciones_ibfk_1` FOREIGN KEY (`id_anuncio`) REFERENCES `anuncios` (`id_anuncio`),
  ADD CONSTRAINT `transacciones_ibfk_2` FOREIGN KEY (`id_comprador`) REFERENCES `estudiantes` (`id_estudiante`);

--
-- Filtros para la tabla `valoraciones`
--
ALTER TABLE `valoraciones`
  ADD CONSTRAINT `valoraciones_ibfk_1` FOREIGN KEY (`id_estudiante_valorador`) REFERENCES `estudiantes` (`id_estudiante`),
  ADD CONSTRAINT `valoraciones_ibfk_2` FOREIGN KEY (`id_estudiante_valorado`) REFERENCES `estudiantes` (`id_estudiante`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
