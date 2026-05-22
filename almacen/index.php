<?php
session_start();
include("../config/db.php");

if(!isset($_SESSION['usuario'])){
    header("Location: ../auth/login.php");
    exit();
}

/* PRODUCTOS ALMACEN */

$sql = "
SELECT
productos.*,
categorias.nombre AS categoria
FROM productos
LEFT JOIN categorias
ON productos.categoria_id = categorias.id
ORDER BY productos.id DESC
";

$stmt = $conexion->query($sql);
$productos = $stmt->fetchAll();

/* STOCK BAJO */

$stmt_stock = $conexion->query("
SELECT COUNT(*) AS total
FROM productos
WHERE stock <= 5
");

$stock_bajo = $stmt_stock->fetch()['total'];

?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Almacén</title>

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
    text-decoration:none;
    border-radius:12px;
    font-weight:bold;
    transition:.2s;
}

.btn:hover{
    background:#39b7dc;
}

table{
    width:100%;
    border-collapse:collapse;
}

table th{
    background:#0d6efd;
    color:white;
    padding:14px;
}

table td{
    padding:14px;
    border-bottom:1px solid #eee;
    text-align:center;
}

.stock{
    font-weight:bold;
    padding:6px 10px;
    border-radius:8px;
    display:inline-block;
    min-width:60px;
}

.bajo{
    background:#ffebee;
    color:#d32f2f;
}

.medio{
    background:#fff8e1;
    color:#f57c00;
}

.normal{
    background:#e8f5e9;
    color:#2e7d32;
}

.search-box{
    margin-bottom:20px;
}

.search-box input{
    width:100%;
    padding:14px;
    border-radius:12px;
    border:1px solid #ddd;
    outline:none;
    font-size:15px;
}

.search-box input:focus{
    border-color:#55ccf0;
}

.alerta-stock{
    background:#fff3cd;
    color:#856404;
    padding:15px;
    border-radius:15px;
    margin-bottom:20px;
    font-weight:bold;
    border-left:6px solid #ffc107;
}

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

</style>
</head>

<body>

<?php include("../includes/sidebar.php"); ?>

<div class="main">

<?php include("../includes/topbar.php"); ?>

<!-- TARJETAS -->

<div class="cards">

    <div class="card">

        <p>📦 Productos</p>

        <h2><?php echo count($productos); ?></h2>

    </div>

    <div class="card">

        <p>⚠️ Stock bajo</p>

        <h2><?php echo $stock_bajo; ?></h2>

    </div>

    <div class="card">

        <p>📥 Entradas</p>

        <h2>
            <?php

            $entradas = $conexion->query("
            SELECT IFNULL(SUM(cantidad),0) total
            FROM movimientos_inventario
            WHERE tipo='entrada'
            ")->fetch()['total'];

            echo $entradas;

            ?>
        </h2>

    </div>

    <div class="card">

        <p>📤 Salidas</p>

        <h2>
            <?php

            $salidas = $conexion->query("
            SELECT IFNULL(SUM(cantidad),0) total
            FROM movimientos_inventario
            WHERE tipo='salida'
            ")->fetch()['total'];

            echo $salidas;

            ?>
        </h2>

    </div>

</div>

<!-- ALERTA -->

<?php if($stock_bajo > 0): ?>

<div class="alerta-stock">

⚠️ Hay <?php echo $stock_bajo; ?> productos con stock bajo

</div>

<?php endif; ?>

<!-- TABLA -->

<div class="table-container">

<div class="search-box">

<input
type="text"
id="buscar"
placeholder="🔍 Buscar producto...">

</div>

<div class="top-actions">

<h2>🏬 Almacén</h2>

<div style="display:flex; gap:10px;">

<a href="crear.php" class="btn">
➕ Nuevo producto
</a>

<a href="../inventario/index.php" class="btn">
📊 Inventario
</a>

<a href="entrada_compra.php" class="btn">
📥 Entrada compra
</a>

<a href="entrada_cortesia.php" class="btn">
📥 Entrada cortesia
</a>

<a href="../compras/index.php" class="btn">
📥 Compras
</a>

</div>

</div>

<table>

<thead>

<tr>

<th>Código</th>
<th>Producto</th>
<th>Categoría</th>
<th>Compra</th>
<th>Venta</th>
<th>Stock</th>
<th>Acciones</th>

</tr>

</thead>

<tbody id="tabla-productos">

<?php foreach($productos as $p): ?>

<?php

$clase = 'normal';

if($p['stock'] <= 5){
    $clase = 'bajo';
}
elseif($p['stock'] <= 10){
    $clase = 'medio';
}

?>

<tr>

<td><?php echo $p['codigo_barras']; ?></td>

<td><?php echo $p['nombre']; ?></td>

<td><?php echo $p['categoria']; ?></td>

<td>
$<?php echo number_format($p['precio_compra'],2); ?>
</td>

<td>
$<?php echo number_format($p['precio_venta'],2); ?>
</td>

<td>

<span class="stock <?php echo $clase; ?>">

<?php echo $p['stock']; ?>

</span>

</td>

<td>

<a href="../productos/editar.php?id=<?php echo $p['id']; ?>">
✏️
</a>

<a
href="../productos/eliminar.php?id=<?php echo $p['id']; ?>"
onclick="return confirm('¿Eliminar producto?')">
🗑️
</a>

</td>

</tr>

<?php endforeach; ?>

</tbody>

</table>

</div>

</div>

<script>

document.getElementById("buscar")
.addEventListener("keyup", function(){

    let valor = this.value.toLowerCase();

    let filas =
        document.querySelectorAll("#tabla-productos tr");

    filas.forEach(fila => {

        let texto =
            fila.innerText.toLowerCase();

        fila.style.display =
            texto.includes(valor)
            ? ""
            : "none";
    });

});

</script>

</body>
</html>