-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 19-05-2026 a las 21:57:07
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
-- Base de datos: `pos_abarrotes`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `abonos_compra`
--

CREATE TABLE `abonos_compra` (
  `id` int(11) NOT NULL,
  `compra_id` int(11) NOT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `monto` decimal(10,2) NOT NULL,
  `metodo_pago` varchar(50) DEFAULT 'efectivo',
  `referencia` varchar(100) DEFAULT NULL,
  `comentario` text DEFAULT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `abonos_compra`
--

INSERT INTO `abonos_compra` (`id`, `compra_id`, `usuario_id`, `monto`, `metodo_pago`, `referencia`, `comentario`, `fecha`) VALUES
(1, 4, NULL, 40.00, 'Efectivo', NULL, 'se pago casi la mitad de la compra', '2026-05-15 03:28:58'),
(2, 3, NULL, 35.00, 'Efectivo', NULL, 'se hizo un pago medio', '2026-05-15 03:34:30');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `caja`
--

CREATE TABLE `caja` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `fecha_apertura` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_cierre` timestamp NULL DEFAULT NULL,
  `monto_inicial` decimal(10,2) DEFAULT NULL,
  `monto_final` decimal(10,2) DEFAULT NULL,
  `estado` enum('abierta','cerrada') DEFAULT 'abierta'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`id`, `nombre`) VALUES
(1, 'Lacteos');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `compras`
--

CREATE TABLE `compras` (
  `id` int(11) NOT NULL,
  `proveedor_id` int(11) NOT NULL,
  `fecha` datetime DEFAULT current_timestamp(),
  `total` decimal(10,2) NOT NULL,
  `estado` enum('pendiente','recibido','pagado') DEFAULT 'pendiente',
  `subtotal` decimal(10,2) DEFAULT 0.00,
  `descuento` decimal(10,2) DEFAULT 0.00,
  `iva` decimal(10,2) DEFAULT 0.00,
  `pagado` decimal(10,2) DEFAULT 0.00,
  `saldo` decimal(10,2) DEFAULT 0.00,
  `tipo_pago` enum('contado','credito') DEFAULT 'contado',
  `estado_pago` enum('pendiente','parcial','pagado') DEFAULT 'pendiente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `compras`
--

INSERT INTO `compras` (`id`, `proveedor_id`, `fecha`, `total`, `estado`, `subtotal`, `descuento`, `iva`, `pagado`, `saldo`, `tipo_pago`, `estado_pago`) VALUES
(1, 7, '2026-05-08 14:27:35', 336.00, 'recibido', 0.00, 0.00, 0.00, 0.00, 0.00, 'contado', 'pendiente'),
(2, 1, '2026-05-08 14:45:58', 150.00, 'recibido', 0.00, 0.00, 0.00, 0.00, 150.00, 'contado', 'pendiente'),
(3, 1, '2026-05-08 15:05:07', 75.00, 'recibido', 0.00, 0.00, 0.00, 35.00, 40.00, 'contado', 'parcial'),
(4, 1, '2026-05-09 10:38:09', 75.00, 'recibido', 0.00, 0.00, 0.00, 40.00, 35.00, 'contado', 'parcial'),
(5, 7, '2026-05-09 10:39:10', 112.00, 'recibido', 0.00, 0.00, 0.00, 112.00, 0.00, 'contado', 'pagado'),
(6, 1, '2026-05-09 18:36:28', 30.00, 'recibido', 0.00, 0.00, 0.00, 30.00, 0.00, 'contado', 'pagado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `configuracion_ticket`
--

CREATE TABLE `configuracion_ticket` (
  `id` int(11) NOT NULL,
  `nombre_negocio` varchar(150) DEFAULT NULL,
  `telefono` varchar(50) DEFAULT NULL,
  `direccion` text DEFAULT NULL,
  `facebook` varchar(150) DEFAULT NULL,
  `mensaje_final` text DEFAULT NULL,
  `actualizado` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `logo` varchar(255) DEFAULT NULL,
  `mostrar_cajero` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `configuracion_ticket`
--

INSERT INTO `configuracion_ticket` (`id`, `nombre_negocio`, `telefono`, `direccion`, `facebook`, `mensaje_final`, `actualizado`, `logo`, `mostrar_cajero`) VALUES
(1, 'SURTETE', '9610000000', 'Tapachula, Chiapas', 'Facebook: SURTETE', '¡GRACIAS POR SU COMPRA!', '2026-05-19 19:23:52', NULL, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_compra`
--

CREATE TABLE `detalle_compra` (
  `id` int(11) NOT NULL,
  `compra_id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `fecha_caducidad` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `detalle_compra`
--

INSERT INTO `detalle_compra` (`id`, `compra_id`, `producto_id`, `cantidad`, `precio`, `subtotal`, `fecha_caducidad`) VALUES
(1, 1, 1, 12, 28.00, 336.00, '2026-05-08'),
(2, 2, 2, 10, 15.00, 150.00, '2026-05-30'),
(3, 3, 2, 5, 15.00, 75.00, '2026-05-30'),
(4, 4, 2, 5, 15.00, 75.00, '2026-09-25'),
(5, 5, 1, 4, 28.00, 112.00, '2026-11-27'),
(6, 6, 2, 2, 15.00, 30.00, '2026-09-30');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_pedido`
--

CREATE TABLE `detalle_pedido` (
  `id` int(11) NOT NULL,
  `pedido_id` int(11) DEFAULT NULL,
  `producto_id` int(11) DEFAULT NULL,
  `cantidad_pedida` int(11) DEFAULT NULL,
  `cantidad_recibida` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_venta`
--

CREATE TABLE `detalle_venta` (
  `id` int(11) NOT NULL,
  `venta_id` int(11) DEFAULT NULL,
  `producto_id` int(11) DEFAULT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `precio` decimal(10,2) DEFAULT NULL,
  `subtotal` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `detalle_venta`
--

INSERT INTO `detalle_venta` (`id`, `venta_id`, `producto_id`, `cantidad`, `precio`, `subtotal`) VALUES
(1, 1, 2, 1, 20.00, 20.00),
(2, 2, 1, 1, 31.00, 31.00),
(3, 3, 1, 2, 31.00, 62.00),
(4, 4, 1, 2, 31.00, 62.00),
(5, 5, 1, 1, 31.00, 31.00),
(6, 6, 4, 1, 31.00, 31.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lotes`
--

CREATE TABLE `lotes` (
  `id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `lote` varchar(100) DEFAULT NULL,
  `fecha_caducidad` date DEFAULT NULL,
  `stock` int(11) DEFAULT 0,
  `costo` decimal(10,2) DEFAULT NULL,
  `fecha_ingreso` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `lotes`
--

INSERT INTO `lotes` (`id`, `producto_id`, `lote`, `fecha_caducidad`, `stock`, `costo`, `fecha_ingreso`) VALUES
(1, 1, 'A001', '2026-05-08', 6, 28.00, '2026-05-08 20:27:35'),
(2, 2, 'A002', '2026-05-30', 9, 15.00, '2026-05-08 20:45:58'),
(3, 2, 'A002', '2026-05-30', 5, 15.00, '2026-05-08 21:05:07'),
(4, 2, 'A003', '2026-09-25', 5, 15.00, '2026-05-09 16:38:09'),
(5, 1, 'A004', '2026-11-27', 4, 28.00, '2026-05-09 16:39:10'),
(6, 2, 'A005', '2026-09-30', 2, 15.00, '2026-05-10 00:36:28');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mermas`
--

CREATE TABLE `mermas` (
  `id` int(11) NOT NULL,
  `producto_id` int(11) DEFAULT NULL,
  `lote_id` int(11) DEFAULT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `motivo` varchar(255) DEFAULT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `movimientos_inventario`
--

CREATE TABLE `movimientos_inventario` (
  `id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `lote_id` int(11) DEFAULT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `tipo` enum('entrada','salida','ajuste','merma','devolucion') NOT NULL,
  `motivo` varchar(255) DEFAULT NULL,
  `cantidad` int(11) NOT NULL,
  `stock_anterior` int(11) NOT NULL,
  `stock_nuevo` int(11) NOT NULL,
  `referencia_id` int(11) DEFAULT NULL,
  `referencia_tabla` varchar(50) DEFAULT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `movimientos_inventario`
--

INSERT INTO `movimientos_inventario` (`id`, `producto_id`, `lote_id`, `usuario_id`, `tipo`, `motivo`, `cantidad`, `stock_anterior`, `stock_nuevo`, `referencia_id`, `referencia_tabla`, `fecha`) VALUES
(1, 1, 1, NULL, 'entrada', 'Compra proveedor', 12, 10, 22, 1, 'compras', '2026-05-08 20:27:35'),
(2, 2, 2, NULL, 'entrada', 'Compra proveedor', 10, 10, 20, 2, 'compras', '2026-05-08 20:45:59'),
(3, 2, 3, NULL, 'entrada', 'Compra proveedor', 5, 20, 25, 3, 'compras', '2026-05-08 21:05:07'),
(4, 2, NULL, NULL, 'salida', 'Venta realizada', 1, 25, 24, 1, 'ventas', '2026-05-09 16:36:53'),
(5, 2, 4, NULL, 'entrada', 'Compra proveedor', 5, 24, 29, 4, 'compras', '2026-05-09 16:38:09'),
(6, 1, 5, NULL, 'entrada', 'Compra proveedor', 4, 22, 26, 5, 'compras', '2026-05-09 16:39:10'),
(7, 1, NULL, NULL, 'salida', 'Venta realizada', 1, 26, 25, 2, 'ventas', '2026-05-09 16:43:25'),
(8, 1, NULL, NULL, 'salida', 'Venta realizada', 2, 25, 23, 3, 'ventas', '2026-05-09 17:00:03'),
(9, 1, NULL, NULL, 'salida', 'Venta realizada', 2, 23, 21, 4, 'ventas', '2026-05-10 00:32:29'),
(10, 2, 6, NULL, 'entrada', 'Compra proveedor', 2, 29, 31, 6, 'compras', '2026-05-10 00:36:28'),
(11, 1, NULL, NULL, 'salida', 'Venta realizada', 1, 21, 20, 5, 'ventas', '2026-05-10 00:51:42'),
(12, 2, NULL, NULL, 'merma', 'Producto dañado', 3, 31, 28, NULL, NULL, '2026-05-18 20:59:27'),
(13, 1, NULL, NULL, 'entrada', 'entrada_compra', 5, 20, 25, 1, 'productos', '2026-05-18 22:20:02'),
(14, 3, NULL, NULL, 'merma', 'Producto dañado', 3, 31, 28, NULL, NULL, '2026-05-18 23:50:22'),
(15, 4, NULL, NULL, 'entrada', 'inventario_inicial', 3, 0, 3, 4, 'productos', '2026-05-19 17:23:17'),
(16, 4, NULL, NULL, 'salida', 'Venta realizada', 1, 3, 2, 6, 'ventas', '2026-05-19 17:59:01');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pagos_proveedor`
--

CREATE TABLE `pagos_proveedor` (
  `id` int(11) NOT NULL,
  `compra_id` int(11) NOT NULL,
  `proveedor_id` int(11) NOT NULL,
  `monto` decimal(10,2) NOT NULL,
  `metodo_pago` varchar(50) DEFAULT NULL,
  `referencia` varchar(100) DEFAULT NULL,
  `observaciones` text DEFAULT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pagos_proveedor`
--

INSERT INTO `pagos_proveedor` (`id`, `compra_id`, `proveedor_id`, `monto`, `metodo_pago`, `referencia`, `observaciones`, `fecha`) VALUES
(1, 5, 7, 112.00, 'efectivo', NULL, 'Pago inicial compra', '2026-05-09 16:39:10'),
(2, 6, 1, 30.00, 'efectivo', NULL, 'Pago inicial compra', '2026-05-10 00:36:28');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedidos`
--

CREATE TABLE `pedidos` (
  `id` int(11) NOT NULL,
  `proveedor_id` int(11) DEFAULT NULL,
  `fecha` datetime DEFAULT current_timestamp(),
  `estado` enum('pendiente','parcial','completo') DEFAULT 'pendiente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` int(11) NOT NULL,
  `codigo_barras` varchar(50) DEFAULT NULL,
  `nombre` varchar(150) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `precio_compra` decimal(10,2) DEFAULT NULL,
  `precio_venta` decimal(10,2) DEFAULT NULL,
  `stock` int(11) DEFAULT 0,
  `categoria_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `codigo_barras`, `nombre`, `descripcion`, `precio_compra`, `precio_venta`, `stock`, `categoria_id`) VALUES
(1, '987654321', 'Lala deslactosada', NULL, 25.00, 31.00, 25, 1),
(2, '9876543210', 'Papas adobadas', NULL, 15.00, 20.00, 28, NULL),
(3, '12345', 'Nutrileche', NULL, 25.00, 31.00, 28, 1),
(4, '7406102010344', 'Lala deslactosada', NULL, 25.00, 31.00, 2, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedores`
--

CREATE TABLE `proveedores` (
  `id` int(11) NOT NULL,
  `nombre_empresa` varchar(150) NOT NULL,
  `empresa_telefono` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `empresa_direccion` text DEFAULT NULL,
  `redes_sociales` text DEFAULT NULL,
  `vendedor_nombre` varchar(150) DEFAULT NULL,
  `vendedor_telefono` varchar(20) DEFAULT NULL,
  `gerente_nombre` varchar(150) DEFAULT NULL,
  `gerente_telefono` varchar(20) DEFAULT NULL,
  `activo` tinyint(1) NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `proveedores`
--

INSERT INTO `proveedores` (`id`, `nombre_empresa`, `empresa_telefono`, `email`, `empresa_direccion`, `redes_sociales`, `vendedor_nombre`, `vendedor_telefono`, `gerente_nombre`, `gerente_telefono`, `activo`, `fecha_creacion`) VALUES
(1, 'SABRITAS', NULL, 'sabritas@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, 0, '2026-05-06 23:28:27'),
(2, 'SABRITAS', NULL, 'sabritas@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, 0, '2026-05-06 23:31:06'),
(3, 'SABRITAS', NULL, 'sabritas@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, 0, '2026-05-06 23:32:38'),
(4, 'scasd', NULL, 'sabritas@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, 0, '2026-05-06 23:37:31'),
(5, 'sdasdsad', NULL, 'sabritas@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, 0, '2026-05-06 23:39:36'),
(6, 'asjkas', NULL, 'sabritas@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, 0, '2026-05-07 02:01:23'),
(7, 'Bimbo', NULL, 'hgsttwb@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, 0, '2026-05-08 19:59:58');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `usuario` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `rol` enum('admin','cajero') DEFAULT 'cajero'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `usuario`, `password`, `rol`) VALUES
(1, 'Administrador', 'admin', '81dc9bdb52d04dc20036dbd8313ed055', 'admin');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ventas`
--

CREATE TABLE `ventas` (
  `id` int(11) NOT NULL,
  `fecha` datetime DEFAULT current_timestamp(),
  `total` decimal(10,2) DEFAULT NULL,
  `recibido` decimal(10,2) DEFAULT 0.00,
  `cambio` decimal(10,2) DEFAULT 0.00,
  `usuario_id` int(11) DEFAULT NULL,
  `metodo_pago` varchar(50) DEFAULT 'efectivo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ventas`
--

INSERT INTO `ventas` (`id`, `fecha`, `total`, `recibido`, `cambio`, `usuario_id`, `metodo_pago`) VALUES
(1, '2026-05-09 10:36:53', 20.00, 50.00, 30.00, NULL, 'efectivo'),
(2, '2026-05-09 10:43:25', 31.00, 50.00, 19.00, NULL, 'efectivo'),
(3, '2026-05-09 11:00:03', 62.00, 100.00, 38.00, NULL, 'efectivo'),
(4, '2026-05-09 18:32:29', 62.00, 100.00, 38.00, NULL, 'efectivo'),
(5, '2026-05-09 18:51:42', 31.00, 100.00, 69.00, NULL, 'efectivo'),
(6, '2026-05-19 11:59:01', 31.00, 50.00, 19.00, NULL, 'efectivo');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `abonos_compra`
--
ALTER TABLE `abonos_compra`
  ADD PRIMARY KEY (`id`),
  ADD KEY `compra_id` (`compra_id`);

--
-- Indices de la tabla `caja`
--
ALTER TABLE `caja`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `compras`
--
ALTER TABLE `compras`
  ADD PRIMARY KEY (`id`),
  ADD KEY `proveedor_id` (`proveedor_id`);

--
-- Indices de la tabla `configuracion_ticket`
--
ALTER TABLE `configuracion_ticket`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `detalle_compra`
--
ALTER TABLE `detalle_compra`
  ADD PRIMARY KEY (`id`),
  ADD KEY `compra_id` (`compra_id`),
  ADD KEY `producto_id` (`producto_id`);

--
-- Indices de la tabla `detalle_pedido`
--
ALTER TABLE `detalle_pedido`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pedido_id` (`pedido_id`),
  ADD KEY `producto_id` (`producto_id`);

--
-- Indices de la tabla `detalle_venta`
--
ALTER TABLE `detalle_venta`
  ADD PRIMARY KEY (`id`),
  ADD KEY `venta_id` (`venta_id`),
  ADD KEY `producto_id` (`producto_id`);

--
-- Indices de la tabla `lotes`
--
ALTER TABLE `lotes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_producto` (`producto_id`);

--
-- Indices de la tabla `mermas`
--
ALTER TABLE `mermas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `movimientos_inventario`
--
ALTER TABLE `movimientos_inventario`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lote_id` (`lote_id`),
  ADD KEY `usuario_id` (`usuario_id`),
  ADD KEY `fk_mov_producto` (`producto_id`);

--
-- Indices de la tabla `pagos_proveedor`
--
ALTER TABLE `pagos_proveedor`
  ADD PRIMARY KEY (`id`),
  ADD KEY `compra_id` (`compra_id`),
  ADD KEY `proveedor_id` (`proveedor_id`);

--
-- Indices de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `proveedor_id` (`proveedor_id`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `codigo_barras` (`codigo_barras`),
  ADD KEY `categoria_id` (`categoria_id`),
  ADD KEY `idx_codigo_barras` (`codigo_barras`);

--
-- Indices de la tabla `proveedores`
--
ALTER TABLE `proveedores`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usuario` (`usuario`);

--
-- Indices de la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_fecha` (`fecha`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `abonos_compra`
--
ALTER TABLE `abonos_compra`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `caja`
--
ALTER TABLE `caja`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `compras`
--
ALTER TABLE `compras`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `configuracion_ticket`
--
ALTER TABLE `configuracion_ticket`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `detalle_compra`
--
ALTER TABLE `detalle_compra`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `detalle_pedido`
--
ALTER TABLE `detalle_pedido`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `detalle_venta`
--
ALTER TABLE `detalle_venta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `lotes`
--
ALTER TABLE `lotes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `mermas`
--
ALTER TABLE `mermas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `movimientos_inventario`
--
ALTER TABLE `movimientos_inventario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `pagos_proveedor`
--
ALTER TABLE `pagos_proveedor`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `proveedores`
--
ALTER TABLE `proveedores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `ventas`
--
ALTER TABLE `ventas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `abonos_compra`
--
ALTER TABLE `abonos_compra`
  ADD CONSTRAINT `abonos_compra_ibfk_1` FOREIGN KEY (`compra_id`) REFERENCES `compras` (`id`);

--
-- Filtros para la tabla `compras`
--
ALTER TABLE `compras`
  ADD CONSTRAINT `compras_ibfk_1` FOREIGN KEY (`proveedor_id`) REFERENCES `proveedores` (`id`),
  ADD CONSTRAINT `fk_proveedor` FOREIGN KEY (`proveedor_id`) REFERENCES `proveedores` (`id`);

--
-- Filtros para la tabla `detalle_compra`
--
ALTER TABLE `detalle_compra`
  ADD CONSTRAINT `detalle_compra_ibfk_1` FOREIGN KEY (`compra_id`) REFERENCES `compras` (`id`),
  ADD CONSTRAINT `detalle_compra_ibfk_2` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`),
  ADD CONSTRAINT `detalle_compra_ibfk_3` FOREIGN KEY (`compra_id`) REFERENCES `compras` (`id`),
  ADD CONSTRAINT `detalle_compra_ibfk_4` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`),
  ADD CONSTRAINT `fk_detalle_compra` FOREIGN KEY (`compra_id`) REFERENCES `compras` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `detalle_pedido`
--
ALTER TABLE `detalle_pedido`
  ADD CONSTRAINT `detalle_pedido_ibfk_1` FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`id`),
  ADD CONSTRAINT `detalle_pedido_ibfk_2` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`);

--
-- Filtros para la tabla `detalle_venta`
--
ALTER TABLE `detalle_venta`
  ADD CONSTRAINT `detalle_venta_ibfk_1` FOREIGN KEY (`venta_id`) REFERENCES `ventas` (`id`),
  ADD CONSTRAINT `detalle_venta_ibfk_2` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`),
  ADD CONSTRAINT `fk_detalle_venta` FOREIGN KEY (`venta_id`) REFERENCES `ventas` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `lotes`
--
ALTER TABLE `lotes`
  ADD CONSTRAINT `fk_lotes_producto` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `lotes_ibfk_1` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`);

--
-- Filtros para la tabla `movimientos_inventario`
--
ALTER TABLE `movimientos_inventario`
  ADD CONSTRAINT `fk_mov_producto` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`),
  ADD CONSTRAINT `movimientos_inventario_ibfk_1` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`),
  ADD CONSTRAINT `movimientos_inventario_ibfk_2` FOREIGN KEY (`lote_id`) REFERENCES `lotes` (`id`),
  ADD CONSTRAINT `movimientos_inventario_ibfk_3` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `pagos_proveedor`
--
ALTER TABLE `pagos_proveedor`
  ADD CONSTRAINT `pagos_proveedor_ibfk_1` FOREIGN KEY (`compra_id`) REFERENCES `compras` (`id`),
  ADD CONSTRAINT `pagos_proveedor_ibfk_2` FOREIGN KEY (`proveedor_id`) REFERENCES `proveedores` (`id`);

--
-- Filtros para la tabla `pedidos`
--
ALTER TABLE `pedidos`
  ADD CONSTRAINT `pedidos_ibfk_1` FOREIGN KEY (`proveedor_id`) REFERENCES `proveedores` (`id`);

--
-- Filtros para la tabla `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `productos_ibfk_1` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
