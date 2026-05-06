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
<title>POS Abarrotes</title>

<style>
body {
    margin: 0;
    font-family: 'Segoe UI', sans-serif;
    background: #f4f8fb;
}

/* SIDEBAR */
.sidebar {
    width: 230px;
    height: 100vh;
    background: #0d6efd; /* azul */
    color: white;
    position: fixed;
    display: flex;
    flex-direction: column;
}

.logo {
    background: #55ccf0; /* celeste */
    color: #0d3b66;
    font-weight: bold;
    text-align: center;
    padding: 20px;
    font-size: 20px;
}

.sidebar a {
    padding: 15px;
    text-decoration: none;
    color: white;
    font-size: 16px;
    transition: 0.2s;
}

.sidebar a:hover {
    background: rgba(255,255,255,0.2);
}

/* MAIN */
.main {
    margin-left: 230px;
    padding: 20px;
}

/* HEADER */
.header {
    background: white;
    padding: 15px 20px;
    border-left: 6px solid #55ccf0;
    border-radius: 10px;
    margin-bottom: 20px;
}

/* CARDS */
.cards {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 15px;
}

.card {
    background: white;
    border-radius: 15px;
    padding: 25px;
    text-align: center;
    cursor: pointer;
    border-bottom: 5px solid #0d6efd;
    transition: 0.2s;
}

.card:hover {
    transform: translateY(-5px);
}

.card i {
    font-size: 40px;
    color: #0d6efd;
}

/* BOTON GRANDE POS */
.pos-btn {
    margin-top: 20px;
    background: #55ccf0;
    color: #0d3b66;
    font-size: 22px;
    padding: 20px;
    text-align: center;
    border-radius: 15px;
    cursor: pointer;
    font-weight: bold;
}

.pos-btn:hover {
    background: #3bb8db;
}
</style>

</head>
<body>

<div class="sidebar">
    <div class="logo">TU TIENDA</div>

    <a href="ventas/pos.php">🛒 Ventas</a>
    <a href="#">📦 Productos</a>
    <a href="#">🚚 Proveedores</a>
    <a href="#">📥 Compras</a>
    <a href="#">📊 Reportes</a>
    <a href="#">⚙️ Configuración</a>
</div>

<div class="main">

    <div class="header">
        <h2>Panel Principal</h2>
        <p>Usuario: <?php echo $_SESSION['usuario']; ?> (<?php echo $_SESSION['rol']; ?>)</p>
    </div>

    <!-- BOTON PRINCIPAL -->
    <div class="pos-btn" onclick="location.href='ventas/pos.php'">
        🛒 ABRIR CAJA (VENTAS)
    </div>

    <div class="cards">

        <div class="card" onclick="location.href='ventas/pos.php'">
            <i>🛒</i>
            <h3>Ventas</h3>
            <p>Cobrar productos</p>
        </div>

        <div class="card">
            <i>📦</i>
            <h3>Productos</h3>
            <p>Inventario</p>
        </div>

        <div class="card">
            <i>🚚</i>
            <h3>Proveedores</h3>
            <p>Gestión</p>
        </div>

        <div class="card">
            <i>📥</i>
            <h3>Compras</h3>
            <p>Entradas</p>
        </div>

        <div class="card">
            <i>📊</i>
            <h3>Reportes</h3>
            <p>Estadísticas</p>
        </div>

        <div class="card">
            <i>💰</i>
            <h3>Caja</h3>
            <p>Control diario</p>
        </div>

        <div class="card">
            <i>🧾</i>
            <h3>Tickets</h3>
            <p>Historial</p>
        </div>

        <div class="card">
            <i>⚙️</i>
            <h3>Configuración</h3>
            <p>Sistema</p>
        </div>

    </div>

</div>

</body>
</html>