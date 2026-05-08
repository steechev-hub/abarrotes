<?php
include("../config/db.php");

$data = json_decode(file_get_contents("php://input"), true);

$total = 0;

foreach($data['productos'] as $p){

    $total += $p['precio_venta'] * $p['cantidad'];
}

$recibido = $data['recibido'];
$cambio = $recibido - $total;

/* GUARDAR VENTA */

$stmt = $conexion->prepare("
INSERT INTO ventas(total, recibido, cambio)
VALUES(?,?,?)
");

$stmt->execute([
    $total,
    $recibido,
    $cambio
]);

$venta_id = $conexion->lastInsertId();

/* GUARDAR DETALLE */

foreach($data['productos'] as $p){

    $subtotal =
        $p['precio_venta'] * $p['cantidad'];

    $stmt = $conexion->prepare("
    INSERT INTO detalle_venta
    (venta_id, producto_id, cantidad, precio, subtotal)
    VALUES(?,?,?,?,?)
    ");

    $stmt->execute([
        $venta_id,
        $p['id'],
        $p['cantidad'],
        $p['precio_venta'],
        $subtotal
    ]);

    /* DESCONTAR STOCK */

    $update = $conexion->prepare("
    UPDATE productos
    SET stock = stock - ?
    WHERE id = ?
    ");

    $update->execute([
        $p['cantidad'],
        $p['id']
    ]);

    /* STOCK ACTUAL */

    $stock_actual = $conexion->prepare("
    SELECT stock
    FROM productos
    WHERE id = ?
    ");

    $stock_actual->execute([$p['id']]);

    $actual = $stock_actual->fetch();

    $stock_nuevo = $actual['stock'];

    /* STOCK ANTERIOR */

    $stock_anterior =
        $stock_nuevo + $p['cantidad'];

    /* MOVIMIENTO */

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
    VALUES(?,?,?,?,?,?,?,?)
    ");

    $mov->execute([

        $p['id'],
        'salida',
        'Venta realizada',
        $p['cantidad'],
        $stock_anterior,
        $stock_nuevo,
        $venta_id,
        'ventas'
    ]);

}

/* RESPUESTA */

echo json_encode([
    "ok" => true,
    "venta_id" => $venta_id
]);