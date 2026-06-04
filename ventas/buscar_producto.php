<?php

header('Content-Type: application/json');

include("../config/db.php");

$busqueda = $_GET['codigo'] ?? '';

$stmt = $conexion->prepare("
SELECT *
FROM productos
WHERE codigo_barras = ?
OR nombre LIKE ?
LIMIT 1
");

$stmt->execute([
    $busqueda,
    "%".$busqueda."%"
]);


$producto = $stmt->fetch();

if($producto){
    echo json_encode($producto);
}else{
    echo json_encode([
        "error" => "Producto no encontrado"
    ]);
}