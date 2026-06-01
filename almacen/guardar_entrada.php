<?php
session_start();
include("../config/db.php");

if(!isset($_SESSION['usuario'])){
    header("Location: ../auth/login.php");
    exit();
}

$producto_id = $_POST['producto_id'];
$cantidad = $_POST['cantidad'];
$observacion = $_POST['observacion'];

/* STOCK ACTUAL */

$stmt = $conexion->prepare("
SELECT stock_almacen
FROM productos
WHERE id = ?
");

$stmt->execute([$producto_id]);

$producto = $stmt->fetch();

$stock_anterior = $producto['stock_almacen'];

$stock_nuevo =
    $stock_anterior + $cantidad;

/* ACTUALIZAR STOCK */

$update = $conexion->prepare("
UPDATE productos
SET stock_almacen = ?
WHERE id = ?
");

$update->execute([
    $stock_nuevo,
    $producto_id
]);

/* MOVIMIENTO INVENTARIO */

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
    'entrada_compra',
    $cantidad,
    $stock_anterior,
    $stock_nuevo,
    $producto_id,
    'productos'

]);

header("Location: index.php");
exit();
?>