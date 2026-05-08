<?php
session_start();
include("../config/db.php");

if(!isset($_SESSION['usuario'])){
    header("Location: ../auth/login.php");
    exit();
}

/* =========================
   OBTENER ID
========================= */

if(!isset($_GET['id'])){
    header("Location: index.php");
    exit();
}

$id = $_GET['id'];

/* =========================
   OBTENER PROVEEDOR
========================= */

$sql = "SELECT * FROM proveedores WHERE id = ?";
$stmt = $conexion->prepare($sql);
$stmt->execute([$id]);

$proveedor = $stmt->fetch(PDO::FETCH_ASSOC);

if(!$proveedor){
    echo "Proveedor no encontrado";
    exit();
}

/* =========================
   ACTUALIZAR
========================= */

if($_SERVER['REQUEST_METHOD'] == 'POST'){

    $nombre = $_POST['nombre'];
    $telefono = $_POST['telefono'];
    $email = $_POST['email'];
    $direccion = $_POST['direccion'];

    $update = "UPDATE proveedores 
               SET nombre = ?, telefono = ?, email = ?, direccion = ?
               WHERE id = ?";

    $stmt = $conexion->prepare($update);

    $stmt->execute([
        $nombre,
        $telefono,
        $email,
        $direccion,
        $id
    ]);

    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Editar proveedor</title>

<link rel="stylesheet" href="../assets/css/style.css">

<style>

.form-container{
    background:white;
    padding:30px;
    border-radius:20px;
    box-shadow:0 5px 15px rgba(0,0,0,0.05);
    max-width:600px;
}

form{
    display:flex;
    flex-direction:column;
}

label{
    margin-top:15px;
    margin-bottom:5px;
    font-weight:bold;
}

input,
textarea{
    padding:12px;
    border:1px solid #ddd;
    border-radius:10px;
    font-size:16px;
}

.btn{
    margin-top:20px;
    background:#0d6efd;
    color:white;
    border:none;
    padding:14px;
    border-radius:10px;
    font-size:16px;
    cursor:pointer;
}

.btn:hover{
    opacity:0.9;
}

</style>
</head>

<body>

<?php include("../includes/sidebar.php"); ?>

<div class="main">

<?php include("../includes/topbar.php"); ?>

<div class="form-container">

<h2>✏️ Editar proveedor</h2>

<form method="POST">

<label>Nombre</label>
<input type="text" 
       name="nombre"
       value="<?php echo $proveedor['nombre']; ?>"
       required>

<label>Teléfono</label>
<input type="text"
       name="telefono"
       value="<?php echo $proveedor['telefono']; ?>">

<label>Email</label>
<input type="email"
       name="email"
       value="<?php echo $proveedor['email']; ?>">

<label>Dirección</label>
<textarea name="direccion"><?php echo $proveedor['direccion']; ?></textarea>

<button type="submit" class="btn">
    Guardar cambios
</button>

</form>

</div>

</div>

</body>
</html>