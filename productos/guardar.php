<?php
include("../config/db.php");

$stmt = $conexion->prepare("
INSERT INTO productos
(codigo_barras,nombre,precio_compra,precio_venta,stock,categoria_id)
VALUES (?,?,?,?,?,?)
");

$stmt->execute([
    $_POST['codigo_barras'],
    $_POST['nombre'],
    $_POST['precio_compra'],
    $_POST['precio_venta'],
    $_POST['stock'],
    $_POST['categoria_id']
]);

header("Location: index.php");