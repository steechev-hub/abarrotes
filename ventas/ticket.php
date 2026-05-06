<?php
include("../config/db.php");

$id = $_GET['id'];

$stmt = $conexion->prepare("
SELECT *
FROM ventas
WHERE id = ?
");

$stmt->execute([$id]);

$venta = $stmt->fetch();

$detalle = $conexion->prepare("
SELECT detalle_venta.*, productos.nombre
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
<title>Ticket</title>

<style>

body{
    font-family: monospace;
    width:300px;
    margin:auto;
}

h2{
    text-align:center;
}

table{
    width:100%;
    font-size:12px;
}

.total{
    margin-top:20px;
    font-size:16px;
}

.center{
    text-align:center;
}

</style>
</head>
<body onload="window.print()">

<h2>SURTETE</h2>

<p class="center">
Fecha:
<?php echo $venta['fecha']; ?>
</p>

<hr>

<table>

<?php foreach($productos as $p): ?>

<tr>
    <td>
        <?php echo $p['nombre']; ?>
        x<?php echo $p['cantidad']; ?>
    </td>

    <td align="right">
        $<?php echo number_format($p['subtotal'],2); ?>
    </td>
</tr>

<?php endforeach; ?>

</table>

<hr>

<div class="total">

<p>
TOTAL:
$<?php echo number_format($venta['total'],2); ?>
</p>

<p>
RECIBIDO:
$<?php echo number_format($venta['recibido'],2); ?>
</p>

<p>
CAMBIO:
$<?php echo number_format($venta['cambio'],2); ?>
</p>

</div>

<hr>

<p class="center">
¡GRACIAS POR SU COMPRA!
</p>

</body>
</html>