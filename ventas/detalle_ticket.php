<?php
include("../config/db.php");

$id = $_GET['id'];

$venta = $conexion->prepare("
SELECT *
FROM ventas
WHERE id = ?
");

$venta->execute([$id]);

$venta = $venta->fetch();

$detalle = $conexion->prepare("
SELECT
    detalle_venta.*,
    productos.nombre
FROM detalle_venta
INNER JOIN productos
ON detalle_venta.producto_id = productos.id
WHERE venta_id = ?
");

$detalle->execute([$id]);

$productos = $detalle->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Detalle Ticket</title>

<style>

body{
    font-family:'Segoe UI';
    background:#eef4ff;
    padding:20px;
}

.card{
    background:white;
    padding:25px;
    border-radius:20px;
}

table{
    width:100%;
    border-collapse:collapse;
    margin-top:20px;
}

table th{
    background:#55ccf0;
    padding:12px;
}

table td{
    padding:12px;
    border-bottom:1px solid #eee;
}

.total{
    margin-top:20px;
    font-size:24px;
    font-weight:bold;
}

</style>
</head>
<body>

<div class="card">

<h2>
🧾 Ticket #<?php echo $venta['id']; ?>
</h2>

<p>
Fecha:
<?php echo $venta['fecha']; ?>
</p>

<hr>

<table>

<thead>

<tr>
    <th>Producto</th>
    <th>Cantidad</th>
    <th>Precio</th>
    <th>Subtotal</th>
</tr>

</thead>

<tbody>

<?php foreach($productos as $p): ?>

<tr>

<td>
    <?php echo $p['nombre']; ?>
</td>

<td>
    <?php echo $p['cantidad']; ?>
</td>

<td>
    $<?php echo number_format($p['precio'],2); ?>
</td>

<td>
    $<?php echo number_format($p['subtotal'],2); ?>
</td>

</tr>

<?php endforeach; ?>

</tbody>

</table>

<div class="total">

TOTAL:
$<?php echo number_format($venta['total'],2); ?>

</div>

</div>

</body>
</html>