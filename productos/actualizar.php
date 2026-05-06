<?php
include("../config/db.php");

$stmt = $conexion->prepare("
UPDATE productos
SET
codigo_barras = ?,
nombre = ?,
precio_compra = ?,
precio_venta = ?,
stock = ?,
categoria_id = ?
WHERE id = ?
");

$stmt->execute([
    $_POST['codigo_barras'],
    $_POST['nombre'],
    $_POST['precio_compra'],
    $_POST['precio_venta'],
    $_POST['stock'],
    $_POST['categoria_id'],
    $_POST['id']
]);

header("Location: index.php");