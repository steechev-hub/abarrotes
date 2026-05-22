<?php
session_start();
include("../config/db.php");

if(!isset($_SESSION['usuario'])){
    header("Location: ../auth/login.php");
    exit();
}

/* DATOS */

$nombre = trim($_POST['nombre']);
$usuario = trim($_POST['usuario']);
$password = trim($_POST['password']);
$rol = $_POST['rol'];

/* VALIDAR USUARIO REPETIDO */

$validar = $conexion->prepare("
SELECT id
FROM usuarios
WHERE usuario = ?
");

$validar->execute([$usuario]);

if($validar->rowCount() > 0){

    echo "
    <script>
        alert('❌ El usuario ya existe');
        window.location='crear.php';
    </script>
    ";

    exit();
}

/* ENCRIPTAR PASSWORD */

$passwordHash =
    password_hash($password, PASSWORD_DEFAULT);

/* GUARDAR USUARIO */

$stmt = $conexion->prepare("
INSERT INTO usuarios
(
    nombre,
    usuario,
    password,
    rol
)
VALUES (?,?,?,?)
");

$stmt->execute([

    $nombre,
    $usuario,
    $passwordHash,
    $rol

]);

/* REDIRECCION */

header("Location: index.php");
exit();
?>