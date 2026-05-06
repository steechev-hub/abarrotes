<?php
include("../config/db.php");

$data = json_decode(file_get_contents("php://input"), true);

$total = 0;

foreach($data as $p){
    $total += $p['precio_venta'] * $p['cantidad'];
}

// Crear venta
$stmt = $conexion->prepare("INSERT INTO ventas (total) VALUES (?)");
$stmt->execute([$total]);

$venta_id = $conexion->lastInsertId();

// Insertar detalle
foreach($data as $p){

    $subtotal = $p['precio_venta'] * $p['cantidad'];

    $stmt = $conexion->prepare("
        INSERT INTO detalle_venta 
        (venta_id, producto_id, cantidad, precio, subtotal)
        VALUES (?, ?, ?, ?, ?)
    ");

    $stmt->execute([
        $venta_id,
        $p['id'],
        $p['cantidad'],
        $p['precio_venta'],
        $subtotal
    ]);

    // Descontar stock
    $stmt = $conexion->prepare("
        UPDATE productos 
        SET stock = stock - ? 
        WHERE id = ?
    ");

    $stmt->execute([$p['cantidad'], $p['id']]);
}

echo "OK";