<?php

session_start();

include("../config/db.php");

require '../vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

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

/* =========================
   HTML PDF
========================= */

$html = '

<style>

body{
    font-family: Arial;
    font-size:12px;
}

h1{
    text-align:center;
    color:#0d6efd;
}

table{
    width:100%;
    border-collapse:collapse;
    margin-top:20px;
}

th{
    background:#0d6efd;
    color:white;
    padding:8px;
    border:1px solid #000;
}

td{
    border:1px solid #000;
    padding:6px;
    text-align:center;
}

.resumen{
    margin-top:20px;
    font-size:14px;
}

</style>

<h1>Reporte de Ventas</h1>

<p>
<b>Tipo:</b> '. strtoupper($tipo) .'
<br>
<b>Fecha:</b> '. $fecha .'
</p>

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

';

foreach($ventas as $v){

    $totalVentas += $v['total_venta'];

    $totalCostos += $v['total_costo'];

    $totalUtilidad += $v['utilidad'];

    $html .= '

    <tr>

    <td>'.$v['codigo_barras'].'</td>

    <td>'.$v['nombre'].'</td>

    <td>'.$v['cantidad'].'</td>

    <td>$'.number_format($v['precio_compra'],2).'</td>

    <td>$'.number_format($v['precio_venta'],2).'</td>

    <td>$'.number_format($v['total_venta'],2).'</td>

    <td>$'.number_format($v['total_costo'],2).'</td>

    <td>
    <b>$'.number_format($v['utilidad'],2).'</b>
    </td>

    <td>'.$v['fecha'].'</td>

    </tr>

    ';
}

$html .= '

</table>

<div class="resumen">



</div>

';

/* =========================
   GENERAR PDF
========================= */

$options = new Options();

$options->set('isRemoteEnabled', true);

$dompdf = new Dompdf($options);

$dompdf->loadHtml($html);

$dompdf->setPaper('letter', 'landscape');

$dompdf->render();

$dompdf->stream(
    "reporte_ventas.pdf",
    ["Attachment" => true]
);

exit();

?>