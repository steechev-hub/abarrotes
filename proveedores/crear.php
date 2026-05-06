<?php
session_start();
include("../config/db.php");

var_dump($_POST);


if($_SERVER['REQUEST_METHOD'] == 'POST'){

    $nombre = $_POST['nombre'];
    $telefono = $_POST['telefono'];
    $email = $_POST['email'];
    $direccion = $_POST['direccion'];

    $sql = "INSERT INTO proveedores (nombre, telefono, email, direccion)
            VALUES (?, ?, ?, ?)";

    $stmt = $conexion->prepare($sql);
    $stmt->execute([$nombre, $telefono, $email, $direccion]);

    header("Location: proveedores.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Nuevo proveedor</title>
<link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>

<?php include("../includes/sidebar.php"); ?>
<div class="main">
<?php include("../includes/topbar.php"); ?>

<div class="form-container">

<h2>➕ Nuevo proveedor</h2>

<form method="POST">

<label>Nombre</label>
<input type="text" name="nombre" required>

<label>Teléfono</label>
<input type="text" name="telefono">

<label>Email</label>
<input type="email" name="email">

<label>Dirección</label>
<textarea name="direccion"></textarea>

<button type="submit" class="btn">Guardar</button>

</form>

</div>
</div>

</body>
</html>