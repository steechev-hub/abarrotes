<?php
include("../config/db.php");

$fecha_inicio = $_GET['inicio'] ?? date("Y-m-d");
$fecha_fin = $_GET['fin'] ?? date("Y-m-d");

$sql = "
SELECT
    ventas.*,
    usuarios.nombre AS usuario
FROM ventas
LEFT JOIN usuarios
ON ventas.usuario_id = usuarios.id
WHERE DATE(ventas.fecha)
BETWEEN ? AND ?
ORDER BY ventas.id DESC
";

$stmt = $conexion->prepare($sql);

$stmt->execute([
    $fecha_inicio,
    $fecha_fin
]);

$ventas = $stmt->fetchAll();

/* TOTAL DEL DIA */

$totalDia = 0;

foreach($ventas as $v){

    $totalDia += $v['total'];
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Historial de Tickets</title>

<style>

body{
    font-family:'Segoe UI';
    background:#eef4ff;
    margin:0;
    padding:20px;
}

.card{
    background:white;
    padding:25px;
    border-radius:20px;
    box-shadow:0 5px 15px rgba(0,0,0,.05);
}

h2{
    color:#0d3b66;
    margin-bottom:20px;
}

.filtros{
    display:flex;
    gap:10px;
    margin-bottom:20px;
}

input{
    padding:12px;
    border-radius:10px;
    border:1px solid #ddd;
}

.btn{
    border:none;
    padding:12px 18px;
    border-radius:10px;
    cursor:pointer;
    font-weight:bold;
}

.buscar{
    background:#55ccf0;
    color:#0d3b66;
}

.imprimir{
    background:#28c76f;
    color:white;
}

.detalle{
    background:#0d6efd;
    color:white;
}

.eliminar{
    background:#ea5455;
    color:white;
}

.total{
    background:#0d3b66;
    color:white;
    padding:20px;
    border-radius:15px;
    margin-bottom:20px;
    font-size:24px;
}

table{
    width:100%;
    border-collapse:collapse;
}

table th{
    background:#55ccf0;
    color:#0d3b66;
    padding:14px;
}

table td{
    padding:14px;
    border-bottom:1px solid #eee;
}

tr:hover{
    background:#f8fbff;
}
.btn-back{
    display:inline-block;
    margin-top:25px;
    background:#6c757d;
    color:white;
    text-decoration:none;
    padding:10px 10px;
    border-radius:10px;
    font-weight:bold;
    transition:0.3s;
}

.btn-back:hover{
    background:#5a6268;
}

</style>
</head>
<body>

<div class="card">

<h2>🧾 Historial de Tickets</h2>

<div class="total">
    💰 Total vendido:
    $<?php echo number_format($totalDia,2); ?>
</div>

<form method="GET">

<div class="filtros">

    <input
    type="date"
    name="inicio"
    value="<?php echo $fecha_inicio; ?>">

    <input
    type="date"
    name="fin"
    value="<?php echo $fecha_fin; ?>">

    <button class="btn buscar">
        🔎 Buscar
    </button>
    <a href="../index.php" class="btn-back">
                    ⬅ Regresar al menú principal
</a>

</div>

</form>

<table>

<thead>

<tr>
    <th>Folio</th>
    <th>Fecha</th>
    <th>Total</th>
    <th>Recibido</th>
    <th>Cambio</th>
    <th>Método</th>
    <th>Usuario</th>
    <th>Acciones</th>
</tr>

</thead>

<tbody>

<?php foreach($ventas as $v): ?>

<tr>

<td>
    #<?php echo $v['id']; ?>
</td>

<td>
    <?php echo $v['fecha']; ?>
</td>

<td>
    $<?php echo number_format($v['total'],2); ?>
</td>

<td>
    $<?php echo number_format($v['recibido'],2); ?>
</td>

<td>
    $<?php echo number_format($v['cambio'],2); ?>
</td>

<td>
    <?php echo $v['metodo_pago']; ?>
</td>

<td>
    <?php echo $v['usuario']; ?>
</td>

<td>

<a
href="ticket.php?id=<?php echo $v['id']; ?>"
target="_blank">

<button class="btn imprimir">
🖨 Reimprimir
</button>

</a>

<a
href="detalle_ticket.php?id=<?php echo $v['id']; ?>">

<button class="btn detalle">
👁 Ver
</button>

</a>

</td>

</tr>

<?php endforeach; ?>

</tbody>

</table>

</div>

</body>
</html>