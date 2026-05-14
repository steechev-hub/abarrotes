<?php
session_start();
include("../config/db.php");

if($_SERVER['REQUEST_METHOD'] == 'POST'){

    $nombre = $_POST['nombre'];

    $sql = "INSERT INTO categorias(nombre)
            VALUES(?)";

    $stmt = $conexion->prepare($sql);
    $stmt->execute([$nombre]);

    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Nueva categoría</title>

<link rel="stylesheet" href="../assets/css/style.css">

<style>

.form-container{
    background:white;
    padding:30px;
    border-radius:20px;
    max-width:500px;
}

input{
    width:100%;
    padding:12px;
    border-radius:10px;
    border:1px solid #ddd;
    margin-top:10px;
}

.btn{
    margin-top:20px;
    background:#0d6efd;
    color:white;
    border:none;
    padding:12px;
    border-radius:10px;
    cursor:pointer;
}

</style>
</head>

<body>

<?php include("../includes/sidebar.php"); ?>

<div class="main">

<?php include("../includes/topbar.php"); ?>

<div class="form-container">

<h2>➕ Nueva categoría</h2>

<form method="POST">

<label>Nombre</label>

<input type="text" name="nombre" required>

<button type="submit" class="btn">
    Guardar categoría
</button>

</form>

</div>

</div>

</body>
</html>