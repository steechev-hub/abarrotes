<?php
session_start();
include("../config/db.php");

if($_SESSION['rol'] == 'cajero'){
    header("Location: ../index.php");
    exit();
}

$config = $conexion->query("
SELECT *
FROM configuracion_ticket
LIMIT 1
")->fetch();

if($_SERVER['REQUEST_METHOD'] == 'POST'){

    $nombre_negocio = $_POST['nombre_negocio'];
    $telefono = $_POST['telefono'];
    $direccion = $_POST['direccion'];
    $facebook = $_POST['facebook'];
    $mensaje_final = $_POST['mensaje_final'];
    $mostrar_cajero =
    isset($_POST['mostrar_cajero'])
    ? 1 : 0;

    /* LOGO */

    $logo = $config['logo'];

    if(isset($_FILES['logo']) &&
    $_FILES['logo']['tmp_name'] != ''){

        $nombreLogo =
            time() . "_" .
            $_FILES['logo']['name'];

        move_uploaded_file(

            $_FILES['logo']['tmp_name'],

            "../uploads/" . $nombreLogo

        );

        $logo = $nombreLogo;
    }

    $sql = "
    UPDATE configuracion_ticket
    SET

        nombre_negocio = ?,
        telefono = ?,
        direccion = ?,
        facebook = ?,
        mensaje_final = ?,
        logo = ?,
        mostrar_cajero = ?

    WHERE id = ?
    ";

    $stmt = $conexion->prepare($sql);

    $stmt->execute([

        $nombre_negocio,
        $telefono,
        $direccion,
        $facebook,
        $mensaje_final,
        $logo,
        $mostrar_cajero,

        $config['id']

    ]);

    header("Location: ticket.php?ok=1");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Configuración Ticket</title>

<style>

body{
    font-family:Arial;
    background:#f4f6f9;
    padding:30px;
}

.card{
    background:white;
    max-width:700px;
    margin:auto;
    padding:30px;
    border-radius:20px;
    box-shadow:0 5px 15px rgba(0,0,0,0.05);
}

h2{
    color:#0d3b66;
    margin-bottom:25px;
}

label{
    display:block;
    margin-top:15px;
    margin-bottom:8px;
    font-weight:bold;
}

input,
textarea{
    width:100%;
    padding:14px;
    border:1px solid #ddd;
    border-radius:12px;
}

textarea{
    min-height:100px;
}

.btn{
    margin-top:25px;
    width:100%;
    background:#55ccf0;
    color:#0d3b66;
    border:none;
    padding:15px;
    border-radius:12px;
    font-weight:bold;
    cursor:pointer;
}

.alert{
    background:#d1e7dd;
    color:#0f5132;
    padding:15px;
    border-radius:12px;
    margin-bottom:20px;
}

</style>
</head>
<body>

<div class="card">

<h2>🧾 Configuración del Ticket</h2>

    <?php if(isset($_GET['ok'])): ?>

    <div class="alert"> ✅ Configuración actualizada</div>

    <?php endif; ?>

<form method="POST" enctype="multipart/form-data">
    <label>Nombre del negocio</label>
        <input
            type="text"
            name="nombre_negocio"
            value="<?php echo $config['nombre_negocio']; ?>">

    <label>Teléfono</label>
        <input
            type="text"
            name="telefono"
            value="<?php echo $config['telefono']; ?>">

    <label>Dirección</label>
        <textarea
            name="direccion"><?php echo $config['direccion']; ?></textarea>

    <label>Facebook / Redes</label>
        <input
            type="text"
            name="facebook"
            value="<?php echo $config['facebook']; ?>">

    <label>Logo del negocio</label>
        <input
            type="file"
            name="logo"
            accept="image/*">

    <?php if($config['logo'] != ''): ?>

    <br><br>
    <img
        src="../uploads/<?php echo $config['logo']; ?>"
        style="
        width:120px;
        border-radius:15px;
        ">

    <?php endif; ?>

    <br><br>

    <label>
        <input
            type="checkbox"
            name="mostrar_cajero"

    <?php
        if($config['mostrar_cajero']){
            echo "checked";
        }
    ?>

    >

        Mostrar nombre del cajero en ticket
        </label>

        <label>Mensaje final del ticket</label>

        <textarea
            name="mensaje_final"><?php echo $config['mensaje_final']; ?></textarea>

        <button class="btn">💾 Guardar configuración</button>

        <div class="back-container">
            <a href="../index.php" class="btn-back">⬅ Regresar al menú principal</a>
        </div>
</form>

</div>

</body>
</html>