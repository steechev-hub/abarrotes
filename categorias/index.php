<?php
session_start();
include("../config/db.php");

$sql = "SELECT * FROM categorias WHERE activo = 1 ORDER BY id DESC";
$stmt = $conexion->query($sql);
$categorias = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Categorías</title>

<link rel="stylesheet" href="../assets/css/style.css">

<style>

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
    border-radius:10px;
    text-decoration:none;
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
}

table th,
table td{
    text-align:center;
}

</style>
</head>

<body>

<?php include("../includes/sidebar.php"); ?>

<div class="main">

<?php include("../includes/topbar.php"); 
$categorias = $conexion->query("
SELECT * FROM categorias
ORDER BY nombre ASC
")->fetchAll();

?>

<div class="table-container">

<div class="top-actions">

    <h2>🗂️ Categorías</h2>

    <a href="crear.php" class="btn">
        ➕ Nueva categoría
    </a>

</div>

<table>

<thead>
<tr>
    <th>ID</th>
    <th>Nombre</th>
</tr>
</thead>

<tbody>

<?php foreach($categorias as $c): ?>

<tr>

    <td><?php echo $c['id']; ?></td>

    <td><?php echo $c['nombre']; ?></td>

</tr>

<?php endforeach; ?>

</tbody>

</table>

</div>

</div>

</body>
</html>