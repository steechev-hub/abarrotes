<?php
session_start();
include("../config/db.php");

if($_SERVER['REQUEST_METHOD'] == 'POST'){

    $nombre = $_POST['nombre'];
    $telefono = $_POST['telefono'];
    $email = $_POST['email'];
    $direccion = $_POST['direccion'];

    $empresa_direccion = $_POST['empresa_direccion'];
    $redes_sociales = $_POST['redes_sociales'];

    $vendedor_nombre = $_POST['vendedor_nombre'];
    $gerente_nombre = $_POST['gerente_nombre'];

    $vendedor_telefono = $_POST['vendedor_telefono'];
    $gerente_telefono = $_POST['gerente_telefono'];

    $empresa_telefono = $_POST['empresa_telefono'];

    $sql = "
    INSERT INTO proveedores (

        nombre,
        telefono,
        email,
        direccion,

        empresa_direccion,
        redes_sociales,

        vendedor_nombre,
        gerente_nombre,

        vendedor_telefono,
        gerente_telefono,

        empresa_telefono

    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ";

    $stmt = $conexion->prepare($sql);

    $stmt->execute([

        $nombre,
        $telefono,
        $email,
        $direccion,

        $empresa_direccion,
        $redes_sociales,

        $vendedor_nombre,
        $gerente_nombre,

        $vendedor_telefono,
        $gerente_telefono,

        $empresa_telefono

    ]);

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

<label>Nombre del proveedor</label>
<input type="text" name="nombre" required>

<label>Teléfono general</label>
<input type="text" name="telefono">

<label>Email</label>
<input type="email" name="email">

<label>Dirección</label>
<textarea name="direccion"></textarea>

<label>Dirección de la empresa</label>
<textarea name="empresa_direccion"></textarea>

<label>Redes sociales</label>
<textarea name="redes_sociales"
placeholder="Facebook, Instagram, WhatsApp..."></textarea>

<hr>

<h3>👨‍💼 Información del vendedor</h3>

<label>Nombre del vendedor</label>
<input type="text" name="vendedor_nombre">

<label>Teléfono del vendedor</label>
<input type="text" name="vendedor_telefono">

<hr>

<h3>👔 Información del gerente</h3>

<label>Nombre del gerente</label>
<input type="text" name="gerente_nombre">

<label>Teléfono del gerente</label>
<input type="text" name="gerente_telefono">

<hr>

<h3>🏢 Empresa</h3>

<label>Teléfono de la empresa</label>
<input type="text" name="empresa_telefono">

<button type="submit" class="btn">
    Guardar proveedor
</button>

</form>

</div>
</div>

</body>
</html>
<style>
    
    .form-container{
    background:white;
    padding:30px;
    border-radius:20px;
    box-shadow:0 5px 15px rgba(0,0,0,0.05);
    max-width:700px;
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
    font-size:15px;
}

textarea{
    min-height:80px;
}

hr{
    margin:25px 0;
    border:none;
    border-top:1px solid #eee;
}

h3{
    color:#0d6efd;
}

.btn{
    margin-top:25px;
    background:#55ccf0;
    color:#0d3b66;
    border:none;
    padding:14px;
    border-radius:12px;
    cursor:pointer;
    font-weight:bold;
}
</style>