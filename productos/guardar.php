<?php
session_start();
include("../config/db.php");

if(!isset($_SESSION['usuario'])){
    header("Location: ../auth/login.php");
    exit();
}

/* DATOS */

$codigo_barras = $_POST['codigo_barras'];
$nombre = $_POST['nombre'];
$proveedor_id = $_POST['proveedor_id'];
$marca = $_POST['marca'];
$precio_compra = $_POST['precio_compra'];
$precio_venta = $_POST['precio_venta'];
$stock = $_POST['stock'];
$categoria_id = $_POST['categoria_id'];
$contenido_medida = $_POST['contenido_medida'];
$unidad_medida = $_POST['unidad_medida'];

/* GUARDAR PRODUCTO */

$stmt = $conexion->prepare("
INSERT INTO productos (

    codigo_barras,
    nombre,
    proveedor_id,
    marca,
    contenido_medida,
    unidad_medida,
    precio_compra,
    precio_venta,
    stock,
    categoria_id

) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
");

$stmt->execute([
    $codigo_barras,
    $nombre,
    $proveedor_id,
    $marca,
    $contenido_medida,
    $unidad_medida,
    $precio_compra,
    $precio_venta,
    $stock,
    $categoria_id
]);

/* ID PRODUCTO */

$producto_id = $conexion->lastInsertId();

/* =========================
MOVIMIENTO INVENTARIO
========================= */

$mov = $conexion->prepare("
INSERT INTO movimientos_inventario
(
    producto_id,
    tipo,
    motivo,
    cantidad,
    stock_anterior,
    stock_nuevo,
    referencia_id,
    referencia_tabla
)
VALUES (?,?,?,?,?,?,?,?)
");

$mov->execute([

    $producto_id,
    'entrada',
    'inventario_inicial',
    $stock,
    0,
    $stock,
    $producto_id,
    'productos'

]);

/* REDIRECCION */

header("Location: index.php");
exit();
?>