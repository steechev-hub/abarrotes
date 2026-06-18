<?php
session_start();
include("../config/db.php");

$id = $_GET['id'];

/* VALIDAR PRODUCTOS */

$validar = $conexion->prepare("
SELECT COUNT(*) total
FROM productos
WHERE categoria_id = ?
");

$validar->execute([$id]);

$total = $validar->fetch()['total'];

if($total > 0){

    echo "
    <script>
        alert('No se puede eliminar porque tiene productos asociados');
        window.location='index.php';
    </script>";
    exit();
}

/* ELIMINAR */

$delete = $conexion->prepare("
DELETE FROM categorias
WHERE id = ?
");

$delete->execute([$id]);

header("Location: index.php");
exit();