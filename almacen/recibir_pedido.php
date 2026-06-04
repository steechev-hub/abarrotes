<?php
session_start();
include("../config/db.php");

if(!isset($_SESSION['usuario'])){
    header("Location: ../auth/login.php");
    exit();
}

$id = $_GET['id'];

$stmt = $conexion->prepare("
SELECT
pp.*,
p.nombre AS producto
FROM pedidos_proveedor pp
INNER JOIN productos p
ON pp.producto_id = p.id
WHERE pp.id=?
");

$stmt->execute([$id]);

$pedido = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Recibir Pedido</title>

<link rel="stylesheet" href="../assets/css/style.css">

<style>

.form-container{
    max-width:700px;
    margin:auto;
    background:white;
    padding:30px;
    border-radius:20px;
    box-shadow:0 5px 15px rgba(0,0,0,0.05);
}

h2{
    color:#0d3b66;
    margin-bottom:20px;
}

.info-box{
    background:#f4f9ff;
    border-left:5px solid #55ccf0;
    padding:15px;
    border-radius:12px;
    margin-bottom:20px;
}

.info-box p{
    margin:8px 0;
    color:#444;
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

input{
    width:100%;
    padding:14px;
    border:1px solid #ddd;
    border-radius:12px;
    outline:none;
    font-size:15px;
}

input:focus{
    border-color:#55ccf0;
}

.btn{
    background:#28c76f;
    color:white;
    border:none;
    padding:14px;
    border-radius:12px;
    width:100%;
    cursor:pointer;
    font-size:16px;
    font-weight:bold;
    transition:.2s;
}

.btn:hover{
    transform:translateY(-2px);
}

.btn-volver{
    display:inline-block;
    margin-bottom:20px;
    text-decoration:none;
    color:#0d3b66;
    font-weight:bold;
}

</style>

</head>

<body>

    <?php include("../includes/sidebar.php"); ?>
    <div class="main">

        <?php include("../includes/topbar.php"); ?>
        <div class="form-container">
                <a href="pedidos_proveedor.php" class="btn-volver">⬅ Volver a pedidos</a>

                <h2>📥 Recepción de Mercancía</h2>
                <div class="info-box">
                <p>
                    <strong>Producto:</strong>
                    <?= $pedido['producto']; ?>
                </p>
                <p>
                    <strong>Cantidad solicitada:</strong>
                    <?= $pedido['cantidad_solicitada']; ?>
                </p>
                <p>
                    <strong>Cantidad recibida anteriormente:</strong>
                    <?= $pedido['cantidad_recibida']; ?>
                </p>
                <p>
                    <strong>Estado:</strong>
                    <?= ucfirst($pedido['estado']); ?>
                </p>
            </div>

            <form action="guardar_recepcion.php" method="POST">
                    <input
                        type="hidden"
                        name="pedido_id"
                        value="<?= $pedido['id']; ?>">

                <div class="form-group">
                    <label>📦 Cantidad recibida</label>
                    <input
                        type="number"
                        name="cantidad_recibida"
                        min="1"
                        max="<?= $pedido['cantidad_solicitada']; ?>"
                        required>
                </div>

                    <button class="btn">💾 Guardar Recepción</button>
            </form>

        </div>

    </div>

</body>
</html>