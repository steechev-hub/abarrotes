<?php
include("../config/db.php");

if(isset($_GET['id'])){

    $stmt = $conexion->prepare("
        DELETE FROM productos
        WHERE id = ?
    ");

    $stmt->execute([$_GET['id']]);
}

header("Location: index.php");