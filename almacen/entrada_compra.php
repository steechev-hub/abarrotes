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
<title>Entrada por compra</title>

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
    margin-bottom:20px;
}

label{
    display:block;
    margin-bottom:8px;
    font-weight:bold;
    color:#0d3b66;
}

select,
input{
    width:100%;
    padding:14px;
    border:1px solid #ddd;
    border-radius:12px;
    outline:none;
}

.btn{
    width:100%;
    background:#28c76f;
    color:white;
    border:none;
    padding:15px;
    border-radius:12px;
    font-size:16px;
    font-weight:bold;
    cursor:pointer;
}

.info{
    background:#f4f9ff;
    padding:15px;
    border-radius:15px;
    margin-bottom:20px;
}

</style>
</head>
<body>

<?php include("../includes/sidebar.php"); ?>

<div class="main">

    <?php include("../includes/topbar.php"); ?>

    <div class="form-container">

        <h2 style="margin-bottom:25px;">
        📥 Entrada por compra
        </h2>

        <div class="info">
            Esta operación aumentará el stock del producto
            y registrará el movimiento en inventario.
        </div>

        <form action="guardar_entrada.php" method="POST">
            <div class="form-group">
                <label>📦 Producto</label>
                <select name="producto_id" required>

                    <option value="">
                        Seleccionar producto
                    </option>

                    <?php foreach($productos as $p): ?>

                        <option value="<?php echo $p['id']; ?>">
                            <?php echo $p['nombre']; ?>

                            (Stock actual:
                            <?php echo $p['stock_almacen']; ?>)
                        </option>

                    <?php endforeach; ?>

                </select>

            </div>

            <div class="form-group">
                <label>📥 Cantidad entrada</label>
                    <input
                        type="number"
                        name="cantidad"
                        min="1"
                        required>
            </div>

            <div class="form-group">
                <label>📝 Observación</label>
                    <input
                        type="text"
                        name="observacion"
                        placeholder="Compra proveedor, ajuste, etc">
            </div>
                <button class="btn">💾 Guardar entrada</button>
        </form>

    </div>

</div>

</body>
</html>