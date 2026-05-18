<?php
session_start();
include("../config/db.php");

if(!isset($_SESSION['usuario'])){
    header("Location: ../auth/login.php");
    exit();
}

$productos = $conexion->query("
SELECT *
FROM productos
ORDER BY nombre ASC
")->fetchAll();

?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Agregar movimiento</title>

<link rel="stylesheet" href="../assets/css/style.css">

<style>

.form-container{
    background:white;
    padding:30px;
    border-radius:20px;
    max-width:700px;
    box-shadow:0 5px 15px rgba(0,0,0,0.05);
}

.form-container h2{
    margin-bottom:20px;
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
    border-radius:12px;
    border:1px solid #ddd;
    outline:none;
    font-size:15px;
}

input:focus,
select:focus{
    border-color:#55ccf0;
    box-shadow:0 0 8px rgba(85,204,240,0.2);
}

.btn{
    background:#55ccf0;
    color:#0d3b66;
    border:none;
    padding:14px 20px;
    border-radius:12px;
    cursor:pointer;
    font-weight:bold;
    font-size:16px;
    width:100%;
    transition:.2s;
}

.btn:hover{
    background:#39b7dc;
}

.info-box{
    background:#eef7ff;
    border-left:5px solid #55ccf0;
    padding:15px;
    border-radius:12px;
    margin-bottom:20px;
    color:#0d3b66;
}

</style>
</head>

<body>

<?php include("../includes/sidebar.php"); ?>

<div class="main">

<?php include("../includes/topbar.php"); ?>

<div class="form-container">

<h2>📦 Agregar movimiento de inventario</h2>

<div class="info-box">

✅ Usa este módulo para:

<br><br>

• Entradas por cortesía  
• Salidas por cortesía  
• Mermas  
• Ajustes manuales  

</div>

<form action="guardar_movimiento.php" method="POST">

    <!-- PRODUCTO -->

    <div class="form-group">

        <label>Producto</label>

        <select name="producto_id" required>

            <option value="">
                Seleccionar producto
            </option>

            <?php foreach($productos as $p): ?>

            <option value="<?php echo $p['id']; ?>">

                <?php echo $p['nombre']; ?>

                (Stock:
                <?php echo $p['stock']; ?>)

            </option>

            <?php endforeach; ?>

        </select>

    </div>

    <!-- TIPO -->

    <div class="form-group">

        <label>Tipo de movimiento</label>

        <select name="tipo" id="tipo" required>

            <option value="">
                Seleccionar
            </option>

            <option value="entrada">
                📥 Entrada
            </option>

            <option value="salida">
                📤 Salida
            </option>

            <option value="merma">
                ⚠️ Merma
            </option>

            <option value="ajuste">
                ⚙️ Ajuste
            </option>

        </select>

    </div>

    <!-- MOTIVO -->

    <div class="form-group">

        <label>Motivo</label>

        <select name="motivo" required>

            <option value="">
                Seleccionar motivo
            </option>

            <option value="Compra proveedor">
                Compra proveedor
            </option>

            <option value="Entrada cortesia">
                Entrada cortesía
            </option>

            <option value="Venta realizada">
                Venta realizada
            </option>

            <option value="Salida cortesia">
                Salida cortesía
            </option>

            <option value="Producto dañado">
                Producto dañado
            </option>

            <option value="Ajuste manual">
                Ajuste manual
            </option>

        </select>

    </div>

    <!-- CANTIDAD -->

    <div class="form-group">

        <label>Cantidad</label>

        <input
        type="number"
        name="cantidad"
        min="1"
        required>

    </div>

    <!-- BOTON -->

    <button class="btn">

        💾 Guardar movimiento

    </button>

</form>

</div>

</div>

</body>
</html>