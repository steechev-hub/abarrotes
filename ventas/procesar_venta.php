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

    /* =========================
    FIFO LOTES
    ========================= */

    $cantidad_restante = $p['cantidad'];

    /* OBTENER LOTES MAS VIEJOS */

    $lotes = $conexion->prepare("
    SELECT *
    FROM lotes
    WHERE producto_id = ?
    AND stock > 0
    ORDER BY fecha_caducidad ASC
    ");

    $lotes->execute([
        $p['id']
    ]);

    $lotes = $lotes->fetchAll();

    /* RECORRER LOTES */

    foreach($lotes as $lote){

        if($cantidad_restante <= 0){
            break;
        }

        $stock_lote = $lote['stock'];

        /* CUANTO DESCONTAR */

        $descontar =
            min($cantidad_restante, $stock_lote);

        /* NUEVO STOCK LOTE */

        $nuevo_stock_lote =
            $stock_lote - $descontar;

        /* ACTUALIZAR LOTE */

        $updateLote = $conexion->prepare("
        UPDATE lotes
        SET stock = ?
        WHERE id = ?
        ");

        $updateLote->execute([
            $nuevo_stock_lote,
            $lote['id']
        ]);

        /* STOCK GENERAL */

        $stockActual = $conexion->prepare("
        SELECT stock
        FROM productos
        WHERE id = ?
        ");

        $stockActual->execute([
            $p['id']
        ]);

        $actual = $stockActual->fetch();

        $stock_anterior = $actual['stock'];

        $stock_nuevo =
            $stock_anterior - $descontar;

        /* DESCONTAR STOCK GENERAL */

        $updateProducto = $conexion->prepare("
        UPDATE productos
        SET stock = stock - ?
        WHERE id = ?
        ");

        $updateProducto->execute([
            $descontar,
            $p['id']
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

            $p['id'],
            $lote['id'],
            'salida',
            'Venta FIFO',
            $descontar,
            $stock_anterior,
            $stock_nuevo,
            $venta_id,
            'ventas'

        ]);

        /* RESTAR PENDIENTE */

        $cantidad_restante -= $descontar;
    }

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