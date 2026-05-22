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

/* DATOS */

$id = $_POST['id'];
$usuario = $_POST['usuario'];
$password = $_POST['password'];
$rol = $_POST['rol'];

/* =========================
ACTUALIZAR SIN PASSWORD
========================= */

if(empty($password)){

    $stmt = $conexion->prepare("
    UPDATE usuarios
    SET
        usuario = ?,
        rol = ?
    WHERE id = ?
    ");

    $stmt->execute([
        $usuario,
        $rol,
        $id
    ]);

}else{

    /* ACTUALIZAR CON PASSWORD */

    $password = md5($password);

    $stmt = $conexion->prepare("
    UPDATE usuarios
    SET
        usuario = ?,
        password = ?,
        rol = ?
    WHERE id = ?
    ");

    $stmt->execute([
        $usuario,
        $password,
        $rol,
        $id
    ]);

}

/* REDIRECCION */

header("Location: index.php");
exit();
?>