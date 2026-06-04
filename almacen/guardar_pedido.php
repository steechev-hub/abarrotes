<?php

include("../config/db.php");

$stmt = $conexion->prepare("
INSERT INTO pedidos_proveedor
(
proveedor_id,
producto_id,
cantidad_solicitada,
observaciones
)
VALUES (?,?,?,?)
");

$stmt->execute([

$_POST['proveedor_id'],
$_POST['producto_id'],
$_POST['cantidad_solicitada'],
$_POST['observaciones']

]);

header("Location: pedidos_proveedor.php");