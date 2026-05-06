<?php
include("../config/db.php");

$codigo = $_GET['codigo'];

$stmt = $conexion->prepare("SELECT * FROM productos WHERE codigo_barras = ?");
$stmt->execute([$codigo]);

$producto = $stmt->fetch();

if($producto){
    echo json_encode($producto);
} else {
    echo json_encode(["error" => "Producto no encontrado"]);
}