<?php
session_start();
include("../config/db.php");

if(!isset($_SESSION['usuario'])){
    header("Location: ../auth/login.php");
    exit();
}

/* SOLO ADMIN */

if($_SESSION['rol'] != 'admin'){
    die("Acceso denegado");
}

/* VALIDAR ID */

if(!isset($_GET['id'])){
    header("Location: index.php");
    exit();
}

$id = $_GET['id'];

/* EVITAR ELIMINARSE A SI MISMO */

$stmt = $conexion->prepare("
SELECT *
FROM usuarios
WHERE id = ?
");

$stmt->execute([$id]);

$usuario = $stmt->fetch();

if(!$usuario){
    header("Location: index.php");
    exit();
}

if($usuario['usuario'] == $_SESSION['usuario']){
    die("No puedes eliminar tu propio usuario");
}

/* ELIMINAR */

$delete = $conexion->prepare("
DELETE FROM usuarios
WHERE id = ?
");

$delete->execute([$id]);

header("Location: index.php");
exit();
?>