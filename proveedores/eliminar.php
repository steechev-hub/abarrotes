<?php
include("../config/db.php");

$id = $_GET['id'];

$sql = "UPDATE proveedores SET activo = 0 WHERE id = ?";
$stmt = $conexion->prepare($sql);
$stmt->execute([$id]);

header("Location: index.php");