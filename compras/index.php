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
proveedores.nombre AS proveedor
FROM compras
INNER JOIN proveedores
ON compras.proveedor_id = proveedores.id
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

.estado{
    padding:6px 12px;
    border-radius:10px;
    color:white;
    font-weight:bold;
    font-size:13px;
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

.btn-abono{
    background:#0d6efd;
    color:white;
    padding:8px 12px;
    border-radius:8px;
    text-decoration:none;
    font-size:14px;
}

.resumen{
    display:grid;
    grid-template-columns:repeat(4,1fr);
    gap:15px;
    margin-bottom:20px;
}

.card-resumen{
    background:white;
    padding:20px;
    border-radius:15px;
    box-shadow:0 5px 15px rgba(0,0,0,0.05);
}

.card-resumen h3{
    margin:0;
    color:#666;
    font-size:15px;
}

.card-resumen h2{
    margin-top:10px;
    color:#0d6efd;
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
    <th>ID</th>
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
<?php echo $c['id']; ?>
</td>

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

<a class="btn-abono" href="abonar.php?id=<?php echo $c['id']; ?>"> 💵 Abonar </a>

</td>

</tr>

<?php endforeach; ?>

</tbody>

</table>

</div>

</div>

</body>
</html>