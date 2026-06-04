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
    header("Location: proveedores.php");
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
    $nombre = $_POST['nombre_empresa'];
    $empresa_telefono = $_POST['empresa_telefono'];
    $email = $_POST['email'];
    $empresa_direccion = $_POST['empresa_direccion'];
    $redes_sociales = $_POST['redes_sociales'];
    $vendedor_nombre = $_POST['vendedor_nombre'];
    $vendedor_telefono = $_POST['vendedor_telefono'];
    $gerente_nombre = $_POST['gerente_nombre'];
    $gerente_telefono = $_POST['gerente_telefono'];
    $update = "

    UPDATE proveedores SET
        nombre = ?,
        empresa_telefono = ?,
        email = ?,
        empresa_direccion = ?,
        redes_sociales = ?,
        vendedor_nombre = ?,
        vendedor_telefono = ?,
        gerente_nombre = ?,
        gerente_telefono = ?
    WHERE id = ?
    ";

    $stmt = $conexion->prepare($update);
    $stmt->execute([
        $nombre,
        $empresa_telefono,
        $email,
        $empresa_direccion,
        $redes_sociales,
        $vendedor_nombre,
        $vendedor_telefono,
        $gerente_nombre,
        $gerente_telefono,
        $id
    ]);
    header("Location: proveedores.php");
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

            <h3>🏢 Información de la empresa</h3>
            <label>Nombre de la empresa</label>
                <input
                type="text"
                name="nombre"
                value="<?php echo $proveedor['nombre_empresa']; ?>"
                required>

            <label>Teléfono de la empresa</label>
                <input
                type="text"
                name="empresa_telefono"
                value="<?php echo $proveedor['empresa_telefono']; ?>">

            <label>Email</label>
                <input
                type="email"
                name="email"
                value="<?php echo $proveedor['email']; ?>">

            <label>Dirección de la empresa</label>
                <textarea name="empresa_direccion"><?php echo $proveedor['empresa_direccion']; ?></textarea>

            <label>Redes sociales</label>
                <textarea name="redes_sociales"><?php echo $proveedor['redes_sociales']; ?></textarea>

            <hr>
            <h3>👨‍💼 Información del vendedor</h3>
            <label>Nombre del vendedor</label>
                <input
                type="text"
                name="vendedor_nombre"
                value="<?php echo $proveedor['vendedor_nombre']; ?>">

            <label>Teléfono del vendedor</label>
                <input
                type="text"
                name="vendedor_telefono"
                value="<?php echo $proveedor['vendedor_telefono']; ?>">

            <hr>
            <h3>👔 Información del gerente</h3>
            <label>Nombre del gerente</label>
                <input
                type="text"
                name="gerente_nombre"
                value="<?php echo $proveedor['gerente_nombre']; ?>">

            <label>Teléfono del gerente</label>
                <input
                type="text"
                name="gerente_telefono"
                value="<?php echo $proveedor['gerente_telefono']; ?>">

                    <button type="submit" class="btn">Guardar cambios</button>
            </form>
        </div>
    </div>
</body>
</html>