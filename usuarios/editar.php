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

/* OBTENER USUARIO */

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
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Editar usuario</title>

<link rel="stylesheet" href="../assets/css/style.css">

<style>

.form-container{
    background:white;
    padding:30px;
    border-radius:20px;
    max-width:600px;
    box-shadow:0 5px 15px rgba(0,0,0,0.05);
}

.form-container h2{
    margin-bottom:25px;
    color:#0d3b66;
}

.form-group{
    margin-bottom:20px;
}

label{
    display:block;
    margin-bottom:8px;
    font-weight:bold;
    color:#0d3b66;
}

input,
select{
    width:100%;
    padding:14px;
    border:1px solid #ddd;
    border-radius:12px;
    outline:none;
    font-size:15px;
}

input:focus,
select:focus{
    border-color:#55ccf0;
}

.btn{
    background:#55ccf0;
    color:#0d3b66;
    border:none;
    width:100%;
    padding:15px;
    border-radius:12px;
    cursor:pointer;
    font-weight:bold;
    font-size:16px;
    transition:.2s;
}

.btn:hover{
    background:#39b7dc;
}

.info{
    background:#eef7ff;
    padding:15px;
    border-radius:12px;
    margin-bottom:20px;
    color:#0d3b66;
    border-left:5px solid #55ccf0;
}

</style>
</head>

<body>

<?php include("../includes/sidebar.php"); ?>

<div class="main">

<?php include("../includes/topbar.php"); ?>

<div class="form-container">

<h2>✏️ Editar usuario</h2>

<div class="info">

Si no deseas cambiar la contraseña,
deja el campo vacío.

</div>

<form action="actualizar.php" method="POST">

    <input
    type="hidden"
    name="id"
    value="<?php echo $usuario['id']; ?>">

    <!-- USUARIO -->

    <div class="form-group">

        <label>👤 Usuario</label>

        <input
        type="text"
        name="usuario"
        value="<?php echo $usuario['usuario']; ?>"
        required>

    </div>

    <!-- PASSWORD -->

    <div class="form-group">

        <label>🔒 Nueva contraseña</label>

        <input
        type="password"
        name="password"
        placeholder="Dejar vacío para no cambiar">

    </div>

    <!-- ROL -->

    <div class="form-group">

        <label>🛡️ Rol</label>

        <select name="rol" required>

            <option
            value="admin"
            <?php echo ($usuario['rol'] == 'admin') ? 'selected' : ''; ?>>
            Administrador
            </option>

            <option
            value="cajero"
            <?php echo ($usuario['rol'] == 'cajero') ? 'selected' : ''; ?>>
            Cajero
            </option>

        </select>

    </div>

    <button class="btn">

        💾 Actualizar usuario

    </button>

</form>

</div>

</div>

</body>
</html>