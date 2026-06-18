<?php

include("../config/db.php");

$pedido_id = $_POST['pedido_id'];
$cantidad_recibida = intval($_POST['cantidad_recibida']);

$stmt = $conexion->prepare("
SELECT *
FROM pedidos_proveedor
WHERE id = ?
");

$stmt->execute([$pedido_id]);

$pedido = $stmt->fetch();

if(!$pedido){
    die("Pedido no encontrado");
}

/* NO PERMITIR RECIBIR DOS VECES */

if($pedido['estado'] == 'recibido'){
    die("Este pedido ya fue recibido anteriormente.");
}

/* SUMAR LO YA RECIBIDO */

$nuevoTotal =
    $pedido['cantidad_recibida'] + $cantidad_recibida;

/* VALIDAR QUE NO EXCEDA */

if($nuevoTotal > $pedido['cantidad_solicitada']){

    die("La cantidad recibida supera la cantidad solicitada.");

}

/* DEFINIR ESTADO */

if($nuevoTotal >= $pedido['cantidad_solicitada']){

    $estado = "recibido";

}else{

    $estado = "parcial";

}

/* ACTUALIZAR PEDIDO */

$update = $conexion->prepare("
UPDATE pedidos_proveedor
SET
    cantidad_recibida = ?,
    estado = ?,
    fecha_recepcion = NOW()
WHERE id = ?
");

$update->execute([
    $nuevoTotal,
    $estado,
    $pedido_id
]);

/* ENTRADA A ALMACÉN */

$stock = $conexion->prepare("
UPDATE productos
SET stock_almacen = stock_almacen + ?
WHERE id = ?
");

$stock->execute([
    $cantidad_recibida,
    $pedido['producto_id']
]);

/* MOVIMIENTO DE INVENTARIO */

$mov = $conexion->prepare("
INSERT INTO movimientos_inventario
(
    producto_id,
    tipo,
    motivo,
    cantidad,
    referencia_id,
    referencia_tabla,
    fecha
)
VALUES
(
    ?, 'entrada', 'Recepción pedido proveedor',
    ?, ?, 'pedidos_proveedor', NOW()
)
");

$mov->execute([
    $pedido['producto_id'],
    $cantidad_recibida,
    $pedido_id
]);

header("Location: pedidos_proveedor.php");
exit();