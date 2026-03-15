-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 21-07-2025 a las 08:02:05
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
-- Base de datos: `tienda`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `admins`
--

INSERT INTO `admins` (`id`, `nombre`, `email`, `password`, `creado_en`) VALUES
(1, 'Luis', 'luis@tienda.com', '$2y$10$Vg5HDnw679NmHk/0HPepWeTOvB/yuKbczqnvbON12CuF7Kloi4ZYi', '2025-06-12 09:49:38'),
(2, 'Admin', 'admin@tienda.com', '$2y$10$o9X2BgfD3wxU6fYJQEaFKu3PFvnqeCgVXxpf.u/KeLhXKn4oFMHX6', '2025-06-12 09:49:38'),
(3, 'Jeremy', 'jeremy@gmail.com', '$2y$10$KqZupQue0zKVDlJvC.VS0OaqX3cF90jMTOg/8xOu6OCU/t4d0Pcom', '2025-06-12 09:53:16');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`id`, `nombre`, `descripcion`) VALUES
(1, 'Zapatillas', 'Zapatillas deportivas de alta calidad'),
(2, 'Camisetas', 'Camisetas para entrenamiento y uso diario'),
(3, 'Accesorios', 'Bolsos, gorras, botellas y más');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_pedido`
--

CREATE TABLE `detalle_pedido` (
  `id` int(11) NOT NULL,
  `pedido_id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `detalle_pedido`
--

INSERT INTO `detalle_pedido` (`id`, `pedido_id`, `producto_id`, `cantidad`, `precio`) VALUES
(1, 1, 14, 1, 299.00),
(2, 2, 15, 1, 299.00),
(3, 3, 14, 1, 299.00),
(4, 4, 14, 1, 299.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estados`
--

CREATE TABLE `estados` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `estados`
--

INSERT INTO `estados` (`id`, `nombre`) VALUES
(1, 'Pendiente'),
(2, 'En camino'),
(3, 'En Proceso'),
(4, 'Entregado'),
(5, 'Rechazado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historial_cambios_clave`
--

CREATE TABLE `historial_cambios_clave` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `fecha_cambio` datetime DEFAULT current_timestamp(),
  `admin_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

CREATE TABLE `pedidos` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `metodo_pago` varchar(50) NOT NULL,
  `fecha` datetime DEFAULT current_timestamp(),
  `estado` varchar(50) DEFAULT 'Pendiente',
  `estado_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `pedidos`
--

INSERT INTO `pedidos` (`id`, `usuario_id`, `total`, `metodo_pago`, `fecha`, `estado`, `estado_id`) VALUES
(2, 1, 299.00, 'Efectivo', '2025-07-15 12:00:19', 'Entregado', 4),
(3, 1, 299.00, 'Efectivo', '2025-07-15 12:17:01', 'Entregado', 4),
(4, 6, 299.00, 'Efectivo', '2025-07-18 15:08:26', 'En camino', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(150) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `precio` decimal(10,2) NOT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `categoria_id` int(11) DEFAULT NULL,
  `stock` int(11) DEFAULT 0,
  `categoria` varchar(50) DEFAULT NULL,
  `promocion` tinyint(1) DEFAULT 0,
  `precio_promocion` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `nombre`, `descripcion`, `precio`, `imagen`, `categoria_id`, `stock`, `categoria`, `promocion`, `precio_promocion`) VALUES
(14, 'Nike Interact Run', 'Listos para irnos de running', 299.00, '1749915919_running.png', NULL, 10, 'Calzado', 1, 199.00),
(15, 'Nike Air Force One', 'Siempre a la altura de la moda', 299.00, '1749923076_jaja.jpg', NULL, 10, 'Calzado', 0, NULL),
(16, 'Camiseta Overzise Basico Negro', 'Un color casual para cualquier ocasion', 100.00, '1749923248_polo.png', NULL, 10, 'Ropa', 0, NULL),
(17, 'Nike Air Force One White', 'Lindas zapatillas casuales', 499.00, '1753076801_af1.webp', NULL, 10, 'Calzado', 1, 459.00),
(18, 'Camiseta Barcelona 2025/2026', 'La mejor camiseta de futbol de todas', 80.00, '1753077034_barca.webp', NULL, 10, 'Deportivo', 0, NULL),
(19, 'Camiseta Real Madrid 2025/2026', 'Una camiseta muy hermosa', 75.00, '1753077067_real.webp', NULL, 10, 'Deportivo', 0, NULL),
(20, 'Polo', 'Polito casual para cualquier evento', 50.00, '1753077109_fua.webp', NULL, 10, 'Ropa', 0, NULL),
(21, 'Short', 'Shortcito para las epocas de verano', 25.00, '1753077136_short.avif', NULL, 10, 'Ropa', 0, NULL),
(22, 'Pantalon Cargo', 'Un pantalon que combina con todo', 150.00, '1753077166_panta.png', NULL, 10, 'Ropa', 0, NULL),
(23, 'Licra Compresora', 'Precisa para los que les gusta usar algo bajo la camiseta', 55.00, '1753077201_licra.jpg', NULL, 10, 'Deportivo', 0, NULL),
(24, 'Buzo Completo', 'Buzo deportivo completo para cualquier actividad fisica', 200.00, '1753077253_buzo.webp', NULL, 10, 'Deportivo', 1, 150.00),
(25, 'Casaca North Face', 'Para estos tiempos de frio la mejor casaca', 1450.00, '1753077290_north.webp', NULL, 15, 'Ropa', 1, 1200.00),
(26, 'Cinta de capitan', 'Para el mejorcito del equipo', 10.00, '1753077331_cinta.jpg', NULL, 5, 'Accesorio', 0, NULL),
(27, 'Guantes Adidas', 'Para cubrirse las manos del frio', 55.00, '1753077365_guantes.avif', NULL, 10, 'Accesorio', 0, NULL),
(28, 'Platos Entrenamiento', 'Para empezar el entrenamiento desde ya..!!', 8.00, '1753077434_plato.jpg', NULL, 10, 'Otros', 0, NULL),
(29, 'Mancuernas 5 KG', 'Es hora de desarrollar esos musculos', 25.00, '1753077479_mancuernas.jpg', NULL, 10, 'Otros', 0, NULL),
(30, 'Joma Top Flex Rojas', 'Lindas zapatillas para las pichangas del barrio', 320.00, '1753077516_joma.webp', NULL, 12, 'Deportivo', 1, 300.00),
(31, 'Joma Top Flex', 'Siempre con los mejores colores en los deportes', 350.00, '1753077551_joma-flex.jpg', NULL, 14, 'Deportivo', 1, 320.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `restablecimientos`
--

CREATE TABLE `restablecimientos` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `fecha_restablecimiento` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id`, `nombre`) VALUES
(1, 'admin'),
(5, 'almacenero'),
(2, 'cliente'),
(3, 'inventario'),
(4, 'ventas');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombres` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `rol` enum('admin','almacenero','inventario','ventas','cliente') NOT NULL,
  `creado_en` datetime DEFAULT current_timestamp(),
  `foto` varchar(255) DEFAULT 'default.png'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombres`, `email`, `password`, `rol`, `creado_en`, `foto`) VALUES
(1, 'Carlos Almacenero', 'carlosalmacenero1@correo.com', '$2y$10$QAEiKfTtAetMsznUw3P..O9PfEAp9zG0duYZ9iLUOY1VKuyoi8Sje', 'almacenero', '2025-07-17 15:57:38', 'default.png'),
(2, 'Laura Inventario', 'laurainventario2@correo.com', '$2y$10$QAEiKfTtAetMsznUw3P..O9PfEAp9zG0duYZ9iLUOY1VKuyoi8Sje', 'inventario', '2025-07-17 15:57:38', 'default.png'),
(3, 'Pedro Ventas', 'pedroventas3@correo.com', '$2y$10$QAEiKfTtAetMsznUw3P..O9PfEAp9zG0duYZ9iLUOY1VKuyoi8Sje', 'ventas', '2025-07-17 15:57:38', 'default.png'),
(4, 'Luis Solorzano', 'luis@correo.com', '$2y$10$QAEiKfTtAetMsznUw3P..O9PfEAp9zG0duYZ9iLUOY1VKuyoi8Sje', 'admin', '2025-07-17 15:57:38', 'default.png'),
(5, 'María Cliente', 'maríacliente5@correo.com', '$2y$10$QAEiKfTtAetMsznUw3P..O9PfEAp9zG0duYZ9iLUOY1VKuyoi8Sje', 'cliente', '2025-07-17 15:57:38', 'default.png'),
(6, 'Leonardo', 'leo@gmail.com', '$2y$10$gFPu.24DItgnmrtCv8IQruMh1B3wm666c8vqV5pAA1X7fGdh/pGg.', 'cliente', '2025-07-17 18:05:29', 'perfil_6.png'),
(7, 'jere', 'jere@gmail.com', '$2y$10$LYXB/KG3.xfAGEf8.fD.IO1N21jyXj0F.uz9D5t1sOICIsk4vJTR2', 'almacenero', '2025-07-17 18:29:16', 'default.png'),
(8, 'Luis Solorzano', 'luis@gmail.com', '$2y$10$286UDAhDt.Y2cwGtWp.uDe.AMqp6.6B5.DWDYkkYez4IhVwdokGwG', 'admin', '2025-07-17 19:04:17', 'default.png');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `vistas`
--

CREATE TABLE `vistas` (
  `id` int(11) NOT NULL,
  `pagina` varchar(255) NOT NULL,
  `vistas` int(11) DEFAULT 1,
  `ultima_visita` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `vistas`
--

INSERT INTO `vistas` (`id`, `pagina`, `vistas`, `ultima_visita`) VALUES
(1, 'index.php', 452, '2025-07-21 06:00:47'),
(2, 'producto.php', 29, '2025-07-21 06:01:22'),
(3, 'nosotros.php', 2, '2025-07-18 01:44:38');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `detalle_pedido`
--
ALTER TABLE `detalle_pedido`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `estados`
--
ALTER TABLE `estados`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `historial_cambios_clave`
--
ALTER TABLE `historial_cambios_clave`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- Indices de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `categoria_id` (`categoria_id`);

--
-- Indices de la tabla `restablecimientos`
--
ALTER TABLE `restablecimientos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indices de la tabla `vistas`
--
ALTER TABLE `vistas`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `detalle_pedido`
--
ALTER TABLE `detalle_pedido`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `estados`
--
ALTER TABLE `estados`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `historial_cambios_clave`
--
ALTER TABLE `historial_cambios_clave`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT de la tabla `restablecimientos`
--
ALTER TABLE `restablecimientos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `vistas`
--
ALTER TABLE `vistas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `historial_cambios_clave`
--
ALTER TABLE `historial_cambios_clave`
  ADD CONSTRAINT `historial_cambios_clave_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `historial_cambios_clave_ibfk_2` FOREIGN KEY (`admin_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `productos_ibfk_1` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`);

--
-- Filtros para la tabla `restablecimientos`
--
ALTER TABLE `restablecimientos`
  ADD CONSTRAINT `restablecimientos_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
