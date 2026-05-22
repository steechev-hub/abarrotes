<?php
session_start();
include("../config/db.php");

if(!isset($_SESSION['usuario'])){
    header("Location: ../auth/login.php");
    exit();
}

$sql = "
SELECT
compras.*,
proveedores.nombre_empresa AS proveedor
FROM compras
INNER JOIN proveedores
ON compras.proveedor_id = proveedores.id

WHERE compras.tipo_pago = 'credito'

ORDER BY compras.id DESC
";

$stmt = $conexion->query($sql);

$compras = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Compras</title>

<link rel="stylesheet"
href="../assets/css/style.css">

<style>
body{
    background:#f4f6f9;
    font-family:'Segoe UI', sans-serif;
}

/* CONTENEDOR TABLA */

.table-container{
    background:white;
    padding:25px;
    border-radius:25px;
    box-shadow:0 5px 15px rgba(0,0,0,0.05);
}

/* BOTONES */

.btn{
    background:#55ccf0;
    color:#0d3b66;
    padding:13px 20px;
    border-radius:12px;
    text-decoration:none;
    font-weight:bold;
    transition:0.3s;
}

.btn:hover{
    opacity:0.9;
    transform:translateY(-2px);
}

/* TOP */

.top-actions{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:25px;
}

/* TABLA */

table{
    width:100%;
    border-collapse:separate;
    border-spacing:0;
    overflow:hidden;
    border-radius:18px;
}

/* ENCABEZADO */

table th{
    background:linear-gradient(
        90deg,
        #7b1fd3,
        #2979ff
    );

    color:white;
    padding:16px;
    text-align:center;
    font-size:15px;
}

/* FILAS */

table td{
    padding:18px;
    text-align:center;
    border-bottom:1px solid #eee;
    background:white;
}

/* HOVER */

table tbody tr:hover{
    background:#f8fbff;
}

/* REDONDEAR */

table th:first-child{
    border-top-left-radius:18px;
}

table th:last-child{
    border-top-right-radius:18px;
}

/* ESTADOS */

.estado{
    padding:8px 14px;
    border-radius:12px;
    color:white;
    font-weight:bold;
    font-size:13px;
    display:inline-block;
    min-width:90px;
}

.pagado{
    background:#28a745;
}

.parcial{
    background:#ffc107;
    color:black;
}

.pendiente{
    background:#dc3545;
}

/* BOTON ABONO */

.btn-abono{
    background:#0d6efd;
    color:white;
    padding:10px 14px;
    border-radius:10px;
    text-decoration:none;
    font-size:14px;
    font-weight:bold;
    transition:0.3s;
}

.btn-abono:hover{
    background:#0056d2;
}

/* RESUMEN */

.resumen{
    display:grid;
    grid-template-columns:repeat(4,1fr);
    gap:20px;
    margin-bottom:25px;
}

/* TARJETAS */

.card-resumen{
    background:white;
    padding:25px;
    border-radius:20px;
    box-shadow:0 5px 15px rgba(0,0,0,0.05);
    transition:0.3s;
}

.card-resumen:hover{
    transform:translateY(-3px);
}

/* TITULOS */

.card-resumen h3{
    margin:0;
    color:#666;
    font-size:15px;
}

.card-resumen h2{
    margin-top:15px;
    color:#0d6efd;
    font-size:28px;
}

/* RESPONSIVE */

@media(max-width:1200px){

    .resumen{
        grid-template-columns:repeat(2,1fr);
    }

}

@media(max-width:700px){

    .resumen{
        grid-template-columns:1fr;
    }

    .top-actions{
        flex-direction:column;
        gap:15px;
    }

    table{
        font-size:13px;
    }

}

</style>

</head>
<body>

<?php include("../includes/sidebar.php"); ?>

<div class="main">

<?php include("../includes/topbar.php"); ?>

<?php

$totalCompras = 0;
$totalPagado = 0;
$totalSaldo = 0;
$pendientes = 0;

foreach($compras as $c){

    $totalCompras += $c['total'];
    $totalPagado += $c['pagado'];
    $totalSaldo += $c['saldo'];

    if($c['estado_pago'] != 'pagado'){
        $pendientes++;
    }
}
?>

<div class="resumen">

<div class="card-resumen">
    <h3>📦 Total Compras</h3>
    <h2>$<?php echo number_format($totalCompras,2); ?></h2>
</div>

<div class="card-resumen">
    <h3>💵 Total Pagado</h3>
    <h2>$<?php echo number_format($totalPagado,2); ?></h2>
</div>

<div class="card-resumen">
    <h3>💰 Saldo Pendiente</h3>
    <h2>$<?php echo number_format($totalSaldo,2); ?></h2>
</div>

<div class="card-resumen">
    <h3>⚠️ Compras Pendientes</h3>
    <h2><?php echo $pendientes; ?></h2>
</div>

</div>

<div class="table-container">

<div class="top-actions">

<h2>📦 Compras y Cuentas por Pagar</h2>

<a href="crear.php" class="btn">
➕ Nueva Compra
</a>

</div>

<table>

<thead>

<tr>
    <th>Proveedor</th>
    <th>Fecha</th>
    <th>Total</th>
    <th>Pagado</th>
    <th>Saldo</th>
    <th>Tipo</th>
    <th>Estado</th>
    <th>Acciones</th>
</tr>

</thead>

<tbody>

<?php foreach($compras as $c): ?>

<?php

$clase = 'pendiente';

if($c['estado_pago'] == 'pagado'){
    $clase = 'pagado';
}
elseif($c['estado_pago'] == 'parcial'){
    $clase = 'parcial';
}

?>

<tr>
<td>
<?php echo $c['proveedor']; ?>
</td>

<td>
<?php echo $c['fecha']; ?>
</td>

<td> $<?php echo number_format($c['total'],2); ?> </td>

<td> $<?php echo number_format($c['pagado'],2); ?> </td>

<td> $<?php echo number_format($c['saldo'],2); ?> </td>

<td>
<?php echo ucfirst($c['tipo_pago']); ?>
</td>

<td>

<span class="estado <?php echo $clase; ?>">

<?php echo ucfirst($c['estado_pago']); ?>

</span>

</td>

<td>

<?php if($c['estado_pago'] != 'pagado'): ?>

    <a
    class="btn-abono"
    href="abonar.php?id=<?php echo $c['id']; ?>">

    💵 Abonar

    </a>

<?php else: ?>

    <span style="
        color:#28a745;
        font-weight:bold;
    ">
        ✅ Liquidado
    </span>

<?php endif; ?>

</td>

</tr>

<?php endforeach; ?>

</tbody>

</table>

</div>

</div>

</body>
</html>