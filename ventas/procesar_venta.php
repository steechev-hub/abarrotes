<?php
session_start();

header('Content-Type: application/json');

error_reporting(E_ALL);
ini_set('display_errors', 1);

include("../config/db.php");

$data = json_decode(file_get_contents("php://input"), true);

$total = 0;

/* CALCULAR TOTAL */

foreach($data['productos'] as $p){

    $total += $p['precio_venta'] * $p['cantidad'];
}

$recibido = $data['recibido'];
$cambio = $recibido - $total;

/* =========================
GUARDAR VENTA
========================= */

$stmt = $conexion->prepare("
INSERT INTO ventas
(
    total,
    recibido,
    cambio,
    usuario_id
)
VALUES(?,?,?,?)
");

$stmt->execute([

    $total,
    $recibido,
    $cambio,
    $_SESSION['id']

]);

$venta_id = $conexion->lastInsertId();

/* =========================
GUARDAR DETALLE
========================= */

foreach($data['productos'] as $p){

    $subtotal =
        $p['precio_venta'] * $p['cantidad'];

    /* DETALLE VENTA */

    $detalle = $conexion->prepare("
    INSERT INTO detalle_venta
    (
        venta_id,
        producto_id,
        cantidad,
        precio,
        subtotal
    )
    VALUES(?,?,?,?,?)
    ");

    $detalle->execute([

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

    /* OBTENER LOTES */

    $lotes = $conexion->prepare("
    SELECT *
    FROM lotes
    WHERE producto_id = ?
    AND stock_piso > 0
    ORDER BY fecha_caducidad ASC
    ");

    $lotes->execute([
        $p['id']
    ]);

    $lotes = $lotes->fetchAll();

    /* STOCK ACTUAL PRODUCTO */

    $stockActual = $conexion->prepare("
    SELECT stock_piso
    FROM productos
    WHERE id = ?
    ");

    $stockActual->execute([
        $p['id']
    ]);

    $actual = $stockActual->fetch();

    $stock_anterior = $actual['stock_piso'];

    /* RECORRER LOTES */

    foreach($lotes as $lote){

        if($cantidad_restante <= 0){
            break;
        }

        $stock_lote = $lote['stock_piso'];

        /* DESCONTAR */

        $descontar =
            min($cantidad_restante, $stock_lote);

        /* NUEVO STOCK LOTE */

        $nuevo_stock_lote =
            $stock_lote - $descontar;

        /* ACTUALIZAR LOTE */

        $updateLote = $conexion->prepare("
        UPDATE lotes
        SET stock_piso = ?
        WHERE id = ?
        ");

        $updateLote->execute([

            $nuevo_stock_lote,
            $lote['id']

        ]);

        /* RESTAR PENDIENTE */

        $cantidad_restante -= $descontar;
    }

    /* NUEVO STOCK GENERAL */

    $stock_nuevo =
        $stock_anterior - $p['cantidad'];

    /* ACTUALIZAR STOCK PRODUCTO */

    $updateProducto = $conexion->prepare("
    UPDATE productos
    SET stock_piso = ?
    WHERE id = ?
    ");

    $updateProducto->execute([

        $stock_nuevo,
        $p['id']

    ]);

    /* MOVIMIENTO INVENTARIO */

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

/* =========================
RESPUESTA
========================= */

echo json_encode([

    "ok" => true,
    "venta_id" => $venta_id

]);
?>