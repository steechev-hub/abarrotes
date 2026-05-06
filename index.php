<?php
session_start();

if(!isset($_SESSION['usuario'])){
    header("Location: auth/login.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Dashboard</title>

<link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<?php include("includes/sidebar.php"); ?>

<div class="main">

    <?php include("includes/topbar.php"); ?>

    <div class="pos-btn" onclick="location.href='../ventas/pos.php'">
        🛒 ABRIR CAJA
    </div>

    <div class="cards">

        <div class="card">
            <h3>📦 Productos</h3>
            <p>Administrar inventario</p>
        </div>

        <div class="card">
            <h3>🚚 Proveedores</h3>
            <p>Control de proveedores</p>
        </div>

        <div class="card">
            <h3>📥 Compras</h3>
            <p>Entradas de mercancía</p>
        </div>

        <div class="card">
            <h3>📊 Reportes</h3>
            <p>Ventas y estadísticas</p>
        </div>

    </div>

</div>

</body>
</html>