<?php
session_start();
include("../config/db.php");


$usuario = $_POST['usuario'];
$password = md5($_POST['password']);

$stmt = $conexion->prepare("SELECT * FROM usuarios WHERE usuario = ? AND password = ?");
$stmt->execute([$usuario, $password]);

$user = $stmt->fetch();

if($user){
    $_SESSION['usuario'] = $user['usuario'];
    $_SESSION['rol'] = $user['rol'];

    header("Location: ../index.php");
} else {
    echo "Datos incorrectos";
}