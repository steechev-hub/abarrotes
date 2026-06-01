<?php
session_start();
include("../config/db.php");

if(!isset($_SESSION['usuario'])){
    header("Location: ../auth/login.php");
    exit();
}

/* PRODUCTOS */

$productos = $conexion->query("
SELECT *
FROM productos
WHERE stock_almacen > 0
ORDER BY nombre ASC
")->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Transferir a Piso</title>

<link rel="stylesheet" href="../assets/css/style.css">

<style>

.form-container{
    background:white;
    max-width:700px;
    margin:auto;
    padding:30px;
    border-radius:20px;
    box-shadow:0 5px 15px rgba(0,0,0,.08);
}

.form-container h2{
    color:#0d3b66;
    margin-bottom:25px;
    text-align:center;
}

.info{
    background:#e3f2fd;
    color:#0d6efd;
    padding:15px;
    border-radius:12px;
    margin-bottom:20px;
    font-weight:bold;
}

.form-group{
    margin-bottom:20px;
}

.form-group label{
    display:block;
    margin-bottom:8px;
    font-weight:bold;
    color:#0d3b66;
}

.form-group select,
.form-group input{
    width:100%;
    padding:14px;
    border:1px solid #ddd;
    border-radius:12px;
    font-size:15px;
    outline:none;
}

.form-group select:focus,
.form-group input:focus{
    border-color:#55ccf0;
    box-shadow:0 0 10px rgba(85,204,240,.2);
}

.btn-transferir{
    width:100%;
    padding:15px;
    border:none;
    border-radius:12px;
    background:#0d6efd;
    color:white;
    font-size:16px;
    font-weight:bold;
    cursor:pointer;
    transition:.3s;
}

.btn-transferir:hover{
    background:#0b5ed7;
    transform:translateY(-2px);
}

.stock-info{
    margin-top:10px;
    padding:12px;
    border-radius:10px;
    background:#f8f9fa;
    color:#555;
    font-weight:bold;
}

</style>
</head>

<body>

<?php include("../includes/sidebar.php"); ?>

<div class="main">

<?php include("../includes/topbar.php"); ?>

<div class="form-container">

    <h2>🔄 Transferir productos a piso</h2>

    <div class="info">
        Selecciona un producto del almacén y la cantidad que deseas enviar al piso de venta.
    </div>

    <form action="guardar_transferencia.php" method="POST">

        <div class="form-group">

            <label>📦 Producto</label>

            <select name="producto_id" required>

                <option value="">
                    Seleccionar producto
                </option>

                <?php foreach($productos as $p): ?>

                    <option
                        value="<?= $p['id']; ?>">

                        <?= $p['nombre']; ?>
                        | Almacén: <?= $p['stock_almacen']; ?>
                        | Piso: <?= $p['stock_piso']; ?>

                    </option>

                <?php endforeach; ?>

            </select>

        </div>

        <div class="form-group">

            <label>📤 Cantidad a transferir</label>

            <input
                type="number"
                name="cantidad"
                min="1"
                required
                placeholder="Ingrese cantidad">

        </div>

        <button
            type="submit"
            class="btn-transferir">

            🔄 Transferir a Piso

        </button>

    </form>

</div>

</div>

</body>
</html>