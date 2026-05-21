<?php

session_start();

include("../config/db.php");

if(!isset($_SESSION['usuario'])){
    header("Location: ../auth/login.php");
    exit();
}

$tipo = $_GET['tipo'] ?? 'dia';

$fecha = $_GET['fecha'] ?? date('Y-m-d');

$where = "";

if($tipo == 'dia'){

    $where = "DATE(v.fecha) = '$fecha'";
}

elseif($tipo == 'semana'){

    $where = "YEARWEEK(v.fecha,1)=YEARWEEK('$fecha',1)";
}

elseif($tipo == 'mes'){

    $where = "MONTH(v.fecha)=MONTH('$fecha')
              AND YEAR(v.fecha)=YEAR('$fecha')";
}

elseif($tipo == 'anio'){

    $where = "YEAR(v.fecha)=YEAR('$fecha')";
}

$sql = "

SELECT

p.codigo_barras,
p.nombre,

dv.cantidad,

p.precio_compra,
p.precio_venta,

(dv.cantidad * p.precio_venta) AS total_venta,

(dv.cantidad * p.precio_compra) AS total_costo,

(
(dv.cantidad * p.precio_venta)
-
(dv.cantidad * p.precio_compra)
) AS utilidad,

v.fecha

FROM detalle_venta dv

INNER JOIN productos p
ON dv.producto_id = p.id

INNER JOIN ventas v
ON dv.venta_id = v.id

WHERE dv.venta_id IS NOT NULL
AND $where

ORDER BY v.fecha DESC

";

$ventas = $conexion->query($sql)->fetchAll();

$totalVentas = 0;
$totalCostos = 0;
$totalUtilidad = 0;

?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Reporte Ventas</title>

<style>

body{
    font-family:Arial;
    background:#f4f6f9;
    padding:20px;
}

.card{
    background:white;
    padding:20px;
    border-radius:15px;
}

table{
    width:100%;
    border-collapse:collapse;
    margin-top:20px;
}

th{
    background:#0d6efd;
    color:white;
    padding:12px;
}

td{
    border:1px solid #ddd;
    padding:10px;
    text-align:center;
}

.btn{
    background:#198754;
    color:white;
    padding:10px 15px;
    border-radius:10px;
    text-decoration:none;
}

.resumen{
    margin-top:20px;
    font-size:18px;
}

</style>

</head>

<body>

<div class="card">

<h2>📊 Reporte de Ventas</h2>

<form method="GET">

<input type="date" name="fecha"
value="<?php echo $fecha; ?>">

<select name="tipo">

<option value="dia">Día</option>

<option value="semana">Semana</option>

<option value="mes">Mes</option>

<option value="anio">Año</option>

</select>

<button type="submit">
Cargar
</button>

<a class="btn"

href="generar_excel.php?tipo=<?php echo $tipo; ?>&fecha=<?php echo $fecha; ?>">

📥 Descargar Excel

</a>

</form>

<table>

<tr>

<th>Código</th>
<th>Producto</th>
<th>Cantidad</th>

<th>Costo U.</th>
<th>Venta U.</th>

<th>Total Venta</th>
<th>Total Costo</th>

<th>Utilidad</th>

<th>Fecha</th>

</tr>

<?php foreach($ventas as $v): ?>

<?php

$totalVentas += $v['total_venta'];

$totalCostos += $v['total_costo'];

$totalUtilidad += $v['utilidad'];

?>

<tr>

<td><?php echo $v['codigo_barras']; ?></td>

<td><?php echo $v['nombre']; ?></td>

<td><?php echo $v['cantidad']; ?></td>

<td>$<?php echo number_format($v['precio_compra'],2); ?></td>

<td>$<?php echo number_format($v['precio_venta'],2); ?></td>

<td>$<?php echo number_format($v['total_venta'],2); ?></td>

<td>$<?php echo number_format($v['total_costo'],2); ?></td>

<td>

<b>

$<?php echo number_format($v['utilidad'],2); ?>

</b>

</td>

<td><?php echo $v['fecha']; ?></td>

</tr>

<?php endforeach; ?>

</table>

<div class="resumen">

<p>
💰 Total Ventas:
<b>$<?php echo number_format($totalVentas,2); ?></b>
</p>

<p>
📦 Total Costos:
<b>$<?php echo number_format($totalCostos,2); ?></b>
</p>

<p>
📈 Utilidad:
<b>$<?php echo number_format($totalUtilidad,2); ?></b>
</p>

</div>

</div>

</body>
</html>