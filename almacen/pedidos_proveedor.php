<?php
session_start();
include("../config/db.php");

if(!isset($_SESSION['usuario'])){
    header("Location: ../auth/login.php");
    exit();
}

$proveedores = $conexion->query("
SELECT *
FROM proveedores
ORDER BY nombre_empresa
")->fetchAll();

$productos = $conexion->query("
SELECT *
FROM productos
ORDER BY nombre
")->fetchAll();

$pedidos = $conexion->query("
SELECT
pp.*,
pr.nombre_empresa AS proveedor,
p.nombre AS producto
FROM pedidos_proveedor pp
INNER JOIN proveedores pr
ON pp.proveedor_id = pr.id
INNER JOIN productos p
ON pp.producto_id = p.id
ORDER BY pp.id DESC
")->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Pedidos a proveedor</title>

<link rel="stylesheet" href="../assets/css/style.css">

<style>

.form-box{
    background:white;
    padding:20px;
    border-radius:20px;
    margin-bottom:20px;
}

input,select,textarea{
    width:100%;
    padding:12px;
    border:1px solid #ddd;
    border-radius:10px;
    margin-bottom:10px;
}

.btn{
    background:#55ccf0;
    color:#0d3b66;
    border:none;
    padding:12px 18px;
    border-radius:10px;
    cursor:pointer;
    font-weight:bold;
}

table{
    width:100%;
    border-collapse:collapse;
    background:white;
}

th{
    background:#0d6efd;
    color:white;
    padding:12px;
}

td{
    padding:12px;
    border-bottom:1px solid #eee;
    text-align:center;
}

</style>

</head>

<body>

    <?php include("../includes/sidebar.php"); ?>

    <div class="main">

        <?php include("../includes/topbar.php"); ?>

            <div class="form-box">
            <h2>📋 Nuevo Pedido</h2>
            <form action="guardar_pedido.php" method="POST">
                <select name="proveedor_id" required>
                    <option value="">Seleccionar proveedor</option>
                    <?php foreach($proveedores as $p): ?>
                        <option value="<?= $p['id'] ?>">
                            <?= $p['nombre_empresa'] ?></option>
                    <?php endforeach; ?>
                </select>

                <select name="producto_id" required>
                    <option value="">Seleccionar producto</option>
                    <?php foreach($productos as $p): ?>
                        <option value="<?= $p['id'] ?>">
                        <?= $p['nombre'] ?></option>

                    <?php endforeach; ?>
                </select>
                <input
                    type="number"
                    name="cantidad_solicitada"
                    placeholder="Cantidad solicitada"
                    required>
                <textarea
                    name="observaciones"
                    placeholder="Observaciones"></textarea>
                <button class="btn">💾 Guardar Pedido</button>
            </form>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Proveedor</th>
                    <th>Producto</th>
                    <th>Solicitado</th>
                    <th>Recibido</th>
                    <th>Estado</th>
                    <th>Acción</th>
                </tr>
            </thead>
            <tbody>

                <?php foreach($pedidos as $p): ?>
                    <tr>
                            <td><?= $p['proveedor'] ?></td>
                            <td><?= $p['producto'] ?></td>
                            <td><?= $p['cantidad_solicitada'] ?></td>
                            <td><?= $p['cantidad_recibida'] ?></td>
                            <td><?= ucfirst($p['estado']) ?></td>
                        <td>
                            <?php if($p['estado'] != 'recibido'): ?>
                            <a href="recibir_pedido.php?id=<?= $p['id'] ?>">📥 Recibir</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>