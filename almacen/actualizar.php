<?php
include("../config/db.php");

$proveedor_id = $_POST['proveedor_id'];
$marca = $_POST['marca'];
$contenido_medida = $_POST['contenido_medida'];
$unidad_medida = $_POST['unidad_medida'];

$stmt = $conexion->prepare("
UPDATE productos SET
codigo_barras = ?,
nombre = ?,
proveedor_id = ?,
marca = ?,
contenido_medida = ?,
unidad_medida = ?,
precio_compra = ?,
precio_venta = ?,
stock_almacen = ?,
stock_piso = ?,
ubicacion = ?,
stock_minimo = ?,
stock_maximo = ?,
categoria_id = ?
WHERE id = ?
");

$stmt->execute([
    $_POST['codigo_barras'],
    $_POST['nombre'],
    $proveedor_id,
    $marca,
    $contenido_medida,
    $unidad_medida,
    $_POST['precio_compra'],
    $_POST['precio_venta'],
    $_POST['stock_almacen'],
    $_POST['stock_piso'],
    $_POST['ubicacion'],
    $_POST['stock_minimo'],
    $_POST['stock_maximo'],
    $_POST['categoria_id'],
    $_POST['id']
]);

header("Location: index.php");