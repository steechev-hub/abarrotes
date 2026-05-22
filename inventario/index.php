<?php
session_start();
include("../config/db.php");

if(!isset($_SESSION['usuario'])){
    header("Location: ../auth/login.php");
    exit();
}

/* FILTROS */

$filtro = "";

if(isset($_GET['tipo']) && $_GET['tipo'] != ''){

    $tipo = $_GET['tipo'];

    $filtro = " WHERE movimientos_inventario.tipo = '$tipo' ";
}

/* CONSULTA */

$sql = "
SELECT
movimientos_inventario.*,
productos.nombre AS producto
FROM movimientos_inventario
INNER JOIN productos
ON movimientos_inventario.producto_id = productos.id
$filtro
ORDER BY movimientos_inventario.id DESC
";

$stmt = $conexion->query($sql);
$movimientos = $stmt->fetchAll();

/* TOTALES */

$entradas = $conexion->query("
SELECT IFNULL(SUM(cantidad),0) total
FROM movimientos_inventario
WHERE tipo = 'entrada'
")->fetch()['total'];

$salidas = $conexion->query("
SELECT IFNULL(SUM(cantidad),0) total
FROM movimientos_inventario
WHERE tipo = 'salida'
")->fetch()['total'];

$mermas = $conexion->query("
SELECT IFNULL(SUM(cantidad),0) total
FROM movimientos_inventario
WHERE tipo = 'merma'
")->fetch()['total'];

$ajustes = $conexion->query("
SELECT IFNULL(SUM(cantidad),0) total
FROM movimientos_inventario
WHERE tipo = 'ajuste'
")->fetch()['total'];

?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Inventario</title>

<link rel="stylesheet" href="../assets/css/style.css">

<style>

.cards{
    display:grid;
    grid-template-columns:repeat(4,1fr);
    gap:15px;
    margin-bottom:20px;
}

.card{
    background:white;
    padding:20px;
    border-radius:20px;
    box-shadow:0 5px 15px rgba(0,0,0,0.05);
}

.card h2{
    margin-top:10px;
    color:#0d3b66;
}

.card p{
    color:#777;
    font-weight:bold;
}

.entrada{
    border-left:6px solid #28c76f;
}

.salida{
    border-left:6px solid #ea5455;
}

.merma{
    border-left:6px solid #ff9f43;
}

.ajuste{
    border-left:6px solid #7367f0;
}

.table-container{
    background:white;
    padding:20px;
    border-radius:20px;
    box-shadow:0 5px 15px rgba(0,0,0,0.05);
}

.top-actions{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:20px;
}

.btn{
    background:#55ccf0;
    color:#0d3b66;
    padding:12px 18px;
    text-decoration:none;
    border-radius:10px;
    font-weight:bold;
}

.filtros{
    display:flex;
    gap:10px;
}

.filtros a{
    padding:10px 15px;
    border-radius:10px;
    text-decoration:none;
    background:#eef4ff;
    color:#0d3b66;
    font-weight:bold;
}

table{
    width:100%;
    border-collapse:collapse;
}

table th{
    background:#0d6efd;
    color:white;
    padding:12px;
}

table td{
    padding:12px;
    border-bottom:1px solid #eee;
    text-align:center;
}

.tipo{
    padding:6px 10px;
    border-radius:8px;
    color:white;
    font-weight:bold;
}

.tipo-entrada{
    background:#28c76f;
}

.tipo-salida{
    background:#ea5455;
}

.tipo-merma{
    background:#ff9f43;
}

.tipo-ajuste{
    background:#7367f0;
}

</style>
</head>

<body>

<?php include("../includes/sidebar.php"); ?>

<div class="main">

<?php include("../includes/topbar.php"); ?>

<!-- TARJETAS -->

<div class="cards">

    <div class="card entrada">

        <p>📥 Entradas</p>

        <h2><?php echo $entradas; ?></h2>

    </div>

    <div class="card salida">

        <p>📤 Salidas</p>

        <h2><?php echo $salidas; ?></h2>

    </div>

    <div class="card merma">

        <p>⚠️ Mermas</p>

        <h2><?php echo $mermas; ?></h2>

    </div>


</div>

<!-- TABLA -->

<div class="table-container">

<div class="top-actions">

<h2>📊 Movimientos de inventario</h2>

<a href="../almacen/index.php" class="btn">
← Volver a almacen
</a>

</div>

<!-- FILTROS -->

<div class="filtros" style="margin-bottom:20px;">

<a href="index.php">Todos</a>

<a href="?tipo=entrada">Entradas</a>

<a href="?tipo=salida">Salidas</a>

<a href="?tipo=merma">Mermas</a>

</div>

<table>

<thead>

<tr>

<th>Producto</th>
<th>Tipo</th>
<th>Motivo</th>
<th>Cantidad</th>
<th>Stock anterior</th>
<th>Stock nuevo</th>
<th>Fecha</th>

</tr>

</thead>

<tbody>

<?php foreach($movimientos as $m): ?>

<?php

$clase = "tipo-ajuste";

if($m['tipo'] == 'entrada'){
    $clase = "tipo-entrada";
}

if($m['tipo'] == 'salida'){
    $clase = "tipo-salida";
}

if($m['tipo'] == 'merma'){
    $clase = "tipo-merma";
}

?>

<tr>


<td><?php echo $m['producto']; ?></td>

<td>

<span class="tipo <?php echo $clase; ?>">

<?php echo strtoupper($m['tipo']); ?>

</span>

</td>

<td><?php echo $m['motivo']; ?></td>

<td><?php echo $m['cantidad']; ?></td>

<td><?php echo $m['stock_anterior']; ?></td>

<td><?php echo $m['stock_nuevo']; ?></td>

<td>

<?php echo date("d/m/Y H:i", strtotime($m['fecha'])); ?>

</td>

</tr>

<?php endforeach; ?>

</tbody>

</table>

</div>

</div>

</body>
</html>