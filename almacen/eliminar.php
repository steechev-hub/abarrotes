<?php
include("../config/db.php");

if(isset($_GET['id'])){

    $stmt = $conexion->prepare("
        UPDATE productos
        SET activo = 0
        WHERE id = ?
    ");

    $stmt->execute([$_GET['id']]);
}

header("Location: index.php");
exit();