<?php

session_start();

include("../config/db.php");

if(!isset($_SESSION['usuario'])){
    header("Location: ../auth/login.php");
    exit();
}

/* =========================
   FILTROS
========================= */

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

/* =========================
   CONSULTA
========================= */

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

$ventas = $conexion->query($sql)->fetchAll(PDO::FETCH_ASSOC);

/* =========================
   TOTALES
========================= */

$totalVentas = 0;
$totalCostos = 0;
$totalUtilidad = 0;

?>

<style>

.resultado-card{
    background:white;
    padding:25px;
    border-radius:20px;
    margin-top:20px;
    box-shadow:0 5px 15px rgba(0,0,0,0.05);
}

.botones-reportes{
    display:flex;
    gap:15px;
    margin-bottom:20px;
    flex-wrap:wrap;
}

table{
    width:100%;
    border-collapse:collapse;
    margin-top:20px;
}

table th{
    background:#0d6efd;
    color:white;
    padding:14px;
    text-align:center;
    font-size:16px;
}

table td{
    border:1px solid #eee;
    padding:12px;
    text-align:center;
    font-size:15px;
}

table tr:hover{
    background:#f8f9fa;
}

.btn{
    background:#198754;
    color:white;
    padding:12px 18px;
    border-radius:10px;
    text-decoration:none;
    font-weight:bold;
    display:inline-block;
}

.btn_pdf{
    background:#C24830;
    color:white;
    padding:12px 18px;
    border-radius:10px;
    text-decoration:none;
    font-weight:bold;
    display:inline-block;
}

.resumen{
    margin-top:25px;
    background:#f8f9fa;
    padding:20px;
    border-radius:15px;
    font-size:20px;
}

.resumen p{
    margin:10px 0;
}

.sin-resultados{
    text-align:center;
    padding:30px;
    font-size:18px;
    color:#777;
}

</style>

<div class="resultado-card">

<div class="botones-reportes">

<a class="btn"

href="generar_excel.php?tipo=<?php echo $tipo; ?>&fecha=<?php echo $fecha; ?>">

📥 Descargar Excel

</a>

<a class="btn_pdf"

href="generar_pdf.php?tipo=<?php echo $tipo; ?>&fecha=<?php echo $fecha; ?>">

📄 Descargar PDF

</a>

</div>

<?php if(count($ventas) > 0): ?>

<table>

<tr>

<th>Código</th>

<th>Producto</th>

<th>Cantidad</th>

<th>Costo U.</th>

<th>PVP.</th>

<th>Total PVP</th>

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

<td>
<?php echo $v['codigo_barras']; ?>
</td>

<td>
<?php echo $v['nombre']; ?>
</td>

<td>
<?php echo $v['cantidad']; ?>
</td>

<td>
$<?php echo number_format($v['precio_compra'],2); ?>
</td>

<td>
$<?php echo number_format($v['precio_venta'],2); ?>
</td>

<td>
$<?php echo number_format($v['total_venta'],2); ?>
</td>

<td>
$<?php echo number_format($v['total_costo'],2); ?>
</td>

<td>

<b>

$<?php echo number_format($v['utilidad'],2); ?>

</b>

</td>

<td>
<?php echo $v['fecha']; ?>
</td>

</tr>

<?php endforeach; ?>

</table>

<div class="resumen">

<p>
💰 Total PVP:
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

<?php else: ?>

<div class="sin-resultados">

No se encontraron ventas para la fecha seleccionada.

</div>

<?php endif; ?>

</div>