<?php
session_start();
include("../config/db.php");

$categorias = $conexion->query("SELECT * FROM categorias")->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Nuevo producto</title>

<link rel="stylesheet" href="../assets/css/style.css">

<style>

.form-container{
    background:white;
    padding:30px;
    border-radius:20px;
    max-width:700px;
}

.form-group{
    margin-bottom:15px;
}

input, select{
    width:100%;
    padding:12px;
    border:1px solid #ddd;
    border-radius:10px;
}

.btn{
    background:#55ccf0;
    border:none;
    padding:12px 20px;
    border-radius:10px;
    cursor:pointer;
    font-weight:bold;
}

</style>
</head>
<body>

<?php include("../includes/sidebar.php"); ?>

<div class="main">

<?php include("../includes/topbar.php"); 

$categorias = $conexion->query("
SELECT * FROM categorias
ORDER BY nombre ASC
")->fetchAll();
?>

<div class="form-container">

<h2>➕ Nuevo producto</h2>



<form action="guardar.php" method="POST">

    <div class="form-group">
        <label>Código de barras</label>
        <input type="text" name="codigo_barras" required>
    </div>

    <div class="form-group">
        <label>Nombre</label>
        <input type="text" name="nombre" required>
    </div>

    <div class="form-group">
        <label>Precio compra</label>
        <input type="number" step="0.01" name="precio_compra" required>
    </div>

    <div class="form-group">
        <label>Precio venta</label>
        <input type="number" step="0.01" name="precio_venta" required>
    </div>

    <div class="form-group">
        <label>Stock</label>
        <input type="number" name="stock" required>
    </div>

    <div class="form-group">
        <label>Categoría</label>

        <label>Categoría</label>

<select name="categoria_id" required>

    <option value="">
        Seleccionar categoría
    </option>

    <?php foreach($categorias as $c): ?>

        <option value="<?php echo $c['id']; ?>">

            <?php echo $c['nombre']; ?>

        </option>

    <?php endforeach; ?>

</select>
    </div>

    <button class="btn">
        Guardar producto
    </button>

</form>

</div>

</div>

</body>
</html>