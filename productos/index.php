<?php
session_start();
include("../config/db.php");

if(!isset($_SESSION['usuario'])){
    header("Location: ../auth/login.php");
    exit();
}


$sql = "
SELECT productos.*, categorias.nombre AS categoria
FROM productos
LEFT JOIN categorias 
ON productos.categoria_id = categorias.id
ORDER BY productos.id DESC
";

$stmt = $conexion->query($sql);
$productos = $stmt->fetchAll();
$stmt_stock = $conexion->query("
    SELECT COUNT(*) AS total
    FROM productos
    WHERE stock <= 5
");

$caducados = $conexion->query("
SELECT
productos.nombre,
lotes.fecha_caducidad,
lotes.stock
FROM lotes
INNER JOIN productos
ON lotes.producto_id = productos.id
WHERE lotes.fecha_caducidad <= DATE_ADD(NOW(), INTERVAL 30 DAY)
AND lotes.stock > 0
ORDER BY lotes.fecha_caducidad ASC
")->fetchAll();

$stock_bajo = $stmt_stock->fetch()['total'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Productos</title>

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
    border-radius:10px;
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

.acciones a{
    text-decoration:none;
    margin-right:10px;
}

.stock{
    font-weight:bold;
}

.stock{
    font-weight:bold;
    padding:6px 10px;
    border-radius:8px;
    display:inline-block;
    min-width:50px;
    text-align:center;
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
    font-size:15px;
    outline:none;
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
.card-alerta{
    background:white;
    padding:20px;
    border-radius:20px;
    margin-bottom:20px;
    box-shadow:0 5px 15px rgba(0,0,0,0.05);
    border-left:6px solid #ff9800;
}

.card-alerta h3{
    color:#ff9800;
    margin-bottom:10px;
}

.card-alerta p{
    margin:8px 0;
    color:#555;
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

<?php include("../includes/topbar.php"); ?>

<?php if($stock_bajo > 0): ?>

<div class="alerta-stock">

    ⚠️ Hay <?php echo $stock_bajo; ?> productos con stock bajo

</div>

<?php

$criticos = $conexion->query("
SELECT nombre, stock
FROM productos
WHERE stock <= 5
LIMIT 5
")->fetchAll();


$categorias = $conexion->query("
SELECT * FROM categorias
ORDER BY nombre ASC
")->fetchAll();
?>

<?php if(count($criticos) > 0): ?>

<div class="card-alerta">

    <h3>⚠️ Productos por terminarse</h3>

    <br>

    <?php foreach($criticos as $c): ?>

        <p>
            • <?php echo $c['nombre']; ?>
            (<?php echo $c['stock']; ?>)
        </p>

    <?php endforeach; ?>

</div>

<?php endif; ?>

<?php endif; ?>

<div class="table-container">

    <div class="search-box">

        <input type="text"
            id="buscar"
            placeholder="🔍 Buscar producto o código...">

    </div>

    <div class="top-actions">

    <h2>📦 Productos</h2>

    <div style="display:flex; gap:10px;">

        <a href="../categorias/index.php" class="btn">
            🗂️ Categorías
        </a>

        <a href="../almacen/index.php" class="btn">
            🏪 Ir a almacén
        </a>

    </div>

</div>

    <table>

        <thead>
            <tr>
                <th>ID</th>
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

            <tr>

                <td><?php echo $p['id']; ?></td>
                <td><?php echo $p['codigo_barras']; ?></td>
                <td><?php echo $p['nombre']; ?></td>
                <td><?php echo $p['categoria']; ?></td>
                <td>$<?php echo $p['precio_compra']; ?></td>
                <td>$<?php echo $p['precio_venta']; ?></td>
                <?php

                $clase = 'normal';

                if($p['stock'] <= 5){
                    $clase = 'bajo';
                }
                elseif($p['stock'] <= 10){
                    $clase = 'medio';
                }

                ?>

                <td class="stock <?php echo $clase; ?>">
                    <?php echo $p['stock']; ?>
                </td>

                <td class="acciones">
                    <a href="editar.php?id=<?php echo $p['id']; ?>">✏️</a>
                    <a href="eliminar.php?id=<?php echo $p['id']; ?>"onclick="return confirm('¿Eliminar producto?')">🗑️</a>
                </td>

            </tr>

        <?php endforeach; ?>

        <?php if(count($caducados) > 0): ?>

            <div class="card-alerta">

            <h3>⚠️ Productos próximos a caducar</h3>

            <?php foreach($caducados as $c): ?>

            <p>

            • <?php echo $c['nombre']; ?>

            | Caduca:
            <?php echo $c['fecha_caducidad']; ?>

            | Stock:
            <?php echo $c['stock']; ?>

            </p>

            <?php endforeach; ?>

            </div>

            <?php endif; ?>

        </tbody>

    </table>

</div>

</div>


<script>

document.getElementById("buscar").addEventListener("keyup", function(){

    let valor = this.value;

    fetch("buscar.php?texto=" + valor)
    .then(res => res.text())
    .then(data => {
        document.getElementById("tabla-productos").innerHTML = data;
    });

});

</script>
</body>
</html>