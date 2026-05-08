<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

include("../config/db.php");

$data = json_decode(file_get_contents("php://input"), true);

$proveedor_id = $data['proveedor_id'];

$productos = $data['productos'];

$total = 0;

/* CALCULAR TOTAL */

foreach($productos as $p){

    $total += $p['cantidad'] * $p['precio'];
}

/* GUARDAR COMPRA */

$stmt = $conexion->prepare("
INSERT INTO compras
(
    proveedor_id,
    fecha,
    total,
    estado
)
VALUES
(
    ?, NOW(), ?, 'recibido'
)
");

$stmt->execute([
    $proveedor_id,
    $total
]);

$compra_id = $conexion->lastInsertId();

/* RECORRER PRODUCTOS */

foreach($productos as $p){

    $subtotal =
        $p['cantidad'] * $p['precio'];

    /* GUARDAR DETALLE */

    $detalle = $conexion->prepare("
    INSERT INTO detalle_compra
    (
        compra_id,
        producto_id,
        cantidad,
        precio,
        subtotal,
        fecha_caducidad
    )
    VALUES(?,?,?,?,?,?)
    ");

    $detalle->execute([

        $compra_id,
        $p['producto_id'],
        $p['cantidad'],
        $p['precio'],
        $subtotal,
        $p['caducidad']

    ]);

    /* CREAR LOTE */

    $lote = $conexion->prepare("
    INSERT INTO lotes
    (
        producto_id,
        lote,
        fecha_caducidad,
        stock,
        costo
    )
    VALUES(?,?,?,?,?)
    ");

    $lote->execute([

        $p['producto_id'],
        $p['lote'],
        $p['caducidad'],
        $p['cantidad'],
        $p['precio']

    ]);

    $lote_id = $conexion->lastInsertId();

    /* STOCK ACTUAL */

    $stockActual = $conexion->prepare("
    SELECT stock
    FROM productos
    WHERE id = ?
    ");

    $stockActual->execute([
        $p['producto_id']
    ]);

    $actual = $stockActual->fetch();

    $stock_anterior = $actual['stock'];

    $stock_nuevo =
        $stock_anterior + $p['cantidad'];

    /* ACTUALIZAR STOCK */

    $update = $conexion->prepare("
    UPDATE productos
    SET stock = stock + ?
    WHERE id = ?
    ");

    $update->execute([

        $p['cantidad'],
        $p['producto_id']

    ]);

    /* MOVIMIENTO INVENTARIO */

    $mov = $conexion->prepare("
    INSERT INTO movimientos_inventario
    (
        producto_id,
        lote_id,
        tipo,
        motivo,
        cantidad,
        stock_anterior,
        stock_nuevo,
        referencia_id,
        referencia_tabla
    )
    VALUES(?,?,?,?,?,?,?,?,?)
    ");

    $mov->execute([

        $p['producto_id'],
        $lote_id,
        'entrada',
        'Compra proveedor',
        $p['cantidad'],
        $stock_anterior,
        $stock_nuevo,
        $compra_id,
        'compras'

    ]);

}

/* RESPUESTA */

echo json_encode([
    "ok" => true,
    "compra_id" => $compra_id
]);