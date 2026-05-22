<?php
include("../config/db.php");

$stmt = $conexion->prepare("
UPDATE productos
SET
codigo_barras = ?,
nombre = ?,
contenido_medida = ?,
unidad_medida = ?,
precio_compra = ?,
precio_venta = ?,
stock = ?,
categoria_id = ?
WHERE id = ?
");

$stmt->execute([
    $_POST['codigo_barras'],
    $_POST['nombre'],
    $contenido_medida = $_POST['contenido_medida'],
    $unidad_medida = $_POST['unidad_medida'],
    $_POST['precio_compra'],
    $_POST['precio_venta'],
    $_POST['stock'],
    $_POST['categoria_id'],
    $_POST['id']
]);

header("Location: index.php");