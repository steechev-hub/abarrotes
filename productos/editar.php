<?php
session_start();
include("../config/db.php");

if(!isset($_GET['id'])){
    header("Location: index.php");
    exit();
}

$id = $_GET['id'];

$stmt = $conexion->prepare("SELECT * FROM productos WHERE id = ?");
$stmt->execute([$id]);

$producto = $stmt->fetch();

if(!$producto){
    header("Location: index.php");
    exit();
}

$categorias = $conexion->query("SELECT * FROM categorias")->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Editar producto</title>

<link rel="stylesheet" href="../assets/css/style.css">

<style>

.form-container{
    background:white;
    padding:30px;
    border-radius:20px;
    max-width:700px;
    box-shadow:0 5px 15px rgba(0,0,0,0.05);
}

.form-group{
    margin-bottom:15px;
}

label{
    display:block;
    margin-bottom:5px;
    color:#0d3b66;
    font-weight:bold;
}

input, select{
    width:100%;
    padding:12px;
    border:1px solid #ddd;
    border-radius:10px;
    outline:none;
}

input:focus, select:focus{
    border-color:#55ccf0;
}

.btn{
    background:#55ccf0;
    border:none;
    padding:12px 20px;
    border-radius:10px;
    cursor:pointer;
    font-weight:bold;
    color:#0d3b66;
}

.btn:hover{
    background:#3bb8db;
}

</style>
</head>
<body>

<?php include("../includes/sidebar.php"); ?>

<div class="main">

<?php include("../includes/topbar.php"); ?>

<div class="form-container">

<h2>✏️ Editar producto</h2>

<form action="actualizar.php" method="POST">

    <input type="hidden" name="id" value="<?php echo $producto['id']; ?>">

    <div class="form-group">
        <label>Código de barras</label>
        <input type="text"
               name="codigo_barras"
               value="<?php echo $producto['codigo_barras']; ?>"
               required>
    </div>

    <div class="form-group">
        <label>Nombre</label>
        <input type="text"
               name="nombre"
               value="<?php echo $producto['nombre']; ?>"
               required>
    </div>

    <div class="form-group">
        <label>Precio compra</label>
        <input type="number"
               step="0.01"
               name="precio_compra"
               value="<?php echo $producto['precio_compra']; ?>"
               required>
    </div>

    <div class="form-group">
        <label>Precio venta</label>
        <input type="number"
               step="0.01"
               name="precio_venta"
               value="<?php echo $producto['precio_venta']; ?>"
               required>
    </div>

    <div class="form-group">
        <label>Stock</label>
        <input type="number"
               name="stock"
               value="<?php echo $producto['stock']; ?>"
               required>
    </div>

    <div class="form-group">

        <label>Categoría</label>

        <select name="categoria_id">

            <?php foreach($categorias as $c): ?>

                <option value="<?php echo $c['id']; ?>"
                    <?php echo ($producto['categoria_id'] == $c['id']) ? 'selected' : ''; ?>>

                    <?php echo $c['nombre']; ?>

                </option>

            <?php endforeach; ?>

        </select>

    </div>

    <button class="btn">
        Actualizar producto
    </button>

</form>

</div>

</div>

</body>
</html>