<?php

include("../config/db.php");

header("Content-Type: application/vnd.ms-excel");

header("Content-Disposition: attachment; filename=reporte.xls");

$tipo = $_GET['tipo'];
$fecha = $_GET['fecha'];

$where = "";

if($tipo == 'dia'){

    $where = "DATE(fecha) = '$fecha'";
}

elseif($tipo == 'mes'){

    $where = "MONTH(fecha)=MONTH('$fecha')
              AND YEAR(fecha)=YEAR('$fecha')";
}

elseif($tipo == 'anio'){

    $where = "YEAR(fecha)=YEAR('$fecha')";
}

elseif($tipo == 'semana'){

    $where = "YEARWEEK(fecha,1)=YEARWEEK('$fecha',1)";
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

FROM detalle_ventas dv

INNER JOIN productos p
ON dv.producto_id = p.id

INNER JOIN ventas v
ON dv.venta_id = v.id

WHERE dv.venta_id IS NOT NULL
AND $where

ORDER BY v.fecha DESC

";

$ventas = $conexion->query($sql)->fetchAll();

echo "

<table border='1'>

<tr>

<th>ID</th>
<th>Fecha</th>
<th>Total</th>
<th>Método</th>

</tr>

";

foreach($ventas as $v){

    echo "

    <tr>

    <td>".$v['id']."</td>

    <td>".$v['fecha']."</td>

    <td>".$v['total']."</td>

    <td>".$v['metodo_pago']."</td>

    </tr>

    ";
}

echo "</table>";