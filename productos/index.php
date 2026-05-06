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

.bajo{
    color:red;
}

.normal{
    color:green;
}

</style>
</head>
<body>

<?php include("../includes/sidebar.php"); ?>

<div class="main">

<?php include("../includes/topbar.php"); ?>

<div class="table-container">

    <div class="top-actions">
        <h2>📦 Productos</h2>

        <a href="crear.php" class="btn">
            ➕ Nuevo producto
        </a>
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

        <tbody>

        <?php foreach($productos as $p): ?>

            <tr>

                <td><?php echo $p['id']; ?></td>

                <td>
                    <?php echo $p['codigo_barras']; ?>
                </td>

                <td>
                    <?php echo $p['nombre']; ?>
                </td>

                <td>
                    <?php echo $p['categoria']; ?>
                </td>

                <td>
                    $<?php echo $p['precio_compra']; ?>
                </td>

                <td>
                    $<?php echo $p['precio_venta']; ?>
                </td>

                <td class="stock <?php echo ($p['stock'] <= 5) ? 'bajo' : 'normal'; ?>">
                    <?php echo $p['stock']; ?>
                </td>

                <td class="acciones">

                    <a href="editar.php?id=<?php echo $p['id']; ?>">
                        ✏️
                    </a>

                    <a href="eliminar.php?id=<?php echo $p['id']; ?>"
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

</body>
</html>