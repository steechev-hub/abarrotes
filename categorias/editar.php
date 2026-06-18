<?php
session_start();
include("../config/db.php");

if(!isset($_SESSION['usuario'])){
    header("Location: ../auth/login.php");
    exit();
}

$id = $_GET['id'];

$stmt = $conexion->prepare("
SELECT * FROM categorias
WHERE id = ?
");
$stmt->execute([$id]);

$categoria = $stmt->fetch();

if($_SERVER['REQUEST_METHOD'] == 'POST'){

    $nombre = trim($_POST['nombre']);

    $update = $conexion->prepare("
    UPDATE categorias
    SET nombre = ?
    WHERE id = ?
    ");

    $update->execute([$nombre, $id]);

    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Editar Categoría</title>

<link rel="stylesheet" href="../assets/css/style.css">

<style>

.form-container{
    max-width:600px;
    margin:auto;
    background:#fff;
    padding:30px;
    border-radius:20px;
    box-shadow:0 5px 15px rgba(0,0,0,0.08);
}

.form-header{
    text-align:center;
    margin-bottom:25px;
}

.form-header h2{
    margin:0;
    color:#0d3b66;
}

.form-header p{
    color:#666;
    margin-top:5px;
}

.form-group{
    margin-bottom:20px;
}

.form-group label{
    display:block;
    margin-bottom:8px;
    font-weight:bold;
    color:#333;
}

.form-group input{
    width:100%;
    padding:12px;
    border:1px solid #ddd;
    border-radius:10px;
    font-size:15px;
    box-sizing:border-box;
}

.form-group input:focus{
    outline:none;
    border-color:#55ccf0;
    box-shadow:0 0 5px rgba(85,204,240,.4);
}

.btn-group{
    display:flex;
    gap:10px;
    justify-content:center;
    margin-top:25px;
}

.btn-save{
    background:#55ccf0;
    color:#0d3b66;
    border:none;
    padding:12px 25px;
    border-radius:10px;
    cursor:pointer;
    font-weight:bold;
    font-size:15px;
}

.btn-save:hover{
    background:#3fc0e8;
}

.btn-back{
    background:#6c757d;
    color:white;
    text-decoration:none;
    padding:12px 25px;
    border-radius:10px;
    font-weight:bold;
}

.btn-back:hover{
    background:#5a6268;
}

.icon{
    font-size:50px;
    margin-bottom:10px;
}

</style>

</head>
<body>

<?php include("../includes/sidebar.php"); ?>

<div class="main">

    <?php include("../includes/topbar.php"); ?>

    <div class="form-container">

        <div class="form-header">
            <div class="icon">✏️</div>
            <h2>Editar Categoría</h2>
            <p>Actualiza la información de la categoría seleccionada</p>

        </div>

        <form method="POST">

            <div class="form-group">
                <label>Nombre de la categoría</label>
                <input
                    type="text"
                    name="nombre"
                    value="<?php echo htmlspecialchars($categoria['nombre']); ?>"
                    required>

            </div>

            <div class="btn-group">
                <button type="submit" class="btn-save">💾 Guardar Cambios</button>
                <a href="index.php" class="btn-back">⬅ Cancelar</a>
            </div>

        </form>
    </div>
</div>

</body>
</html>