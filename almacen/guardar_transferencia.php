<?php

include("../config/db.php");

$producto_id = $_POST['producto_id'];
$cantidad = intval($_POST['cantidad']);

$stmt = $conexion->prepare("
SELECT stock_almacen, stock_piso
FROM productos
WHERE id=?
");

$stmt->execute([$producto_id]);

$p = $stmt->fetch();

if($p['stock_almacen'] < $cantidad){

    die("No hay suficiente stock en almacén");

}

$nuevo_almacen =
$p['stock_almacen'] - $cantidad;

$nuevo_piso =
$p['stock_piso'] + $cantidad;

$update = $conexion->prepare("
UPDATE productos
SET
stock_almacen=?,
stock_piso=?
WHERE id=?
");

$update->execute([
    $nuevo_almacen,
    $nuevo_piso,
    $producto_id
]);

$mov = $conexion->prepare("
INSERT INTO movimientos_inventario
(
    producto_id,
    tipo,
    motivo,
    cantidad,
    stock_anterior,
    stock_nuevo,
    fecha
)
VALUES (?,?,?,?,?,?,NOW())
");

$mov->execute([
    $producto_id,
    'transferencia',
    'almacen_a_piso',
    $cantidad,
    $p['stock_almacen'],
    $nuevo_almacen
]);

header("Location:index.php");