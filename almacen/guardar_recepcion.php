<?php

include("../config/db.php");

$pedido_id = $_POST['pedido_id'];
$cantidad_recibida = intval($_POST['cantidad_recibida']);

$stmt = $conexion->prepare("
SELECT *
FROM pedidos_proveedor
WHERE id=?
");

$stmt->execute([$pedido_id]);

$pedido = $stmt->fetch();

$estado = "pendiente";

if($cantidad_recibida >= $pedido['cantidad_solicitada']){

    $estado = "recibido";

}else{

    $estado = "parcial";
}

/* ACTUALIZAR PEDIDO */

$update = $conexion->prepare("
UPDATE pedidos_proveedor
SET
cantidad_recibida=?,
estado=?,
fecha_recepcion=NOW()
WHERE id=?
");

$update->execute([
$cantidad_recibida,
$estado,
$pedido_id
]);

/* ACTUALIZAR STOCK */

$stock = $conexion->prepare("
UPDATE productos
SET stock_almacen = stock_almacen + ?
WHERE id=?
");

$stock->execute([
$cantidad_recibida,
$pedido['producto_id']
]);

header("Location: pedidos_proveedor.php");