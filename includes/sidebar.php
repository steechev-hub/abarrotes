<div class="sidebar">

    <div class="logo">
        SURTETE
    </div>

    <div class="menu">
        <a href="/surtete/index.php">🏠 Inicio</a>
        <a href="/surtete/ventas/pos.php">🛒 Ventas</a>
        <a href="/surtete/productos/index.php">📦 Productos</a>
        <a href="./proveedores/proveedores.php">🚚 Proveedores</a>
        <a href="#">📥 Compras</a>
        <a href="#">📊 Reportes</a>

        <?php if($_SESSION['rol'] == 'admin'): ?>
            <a href="#">⚙️ Configuración</a>
        <?php endif; ?>
        
        <a href="/surtete/auth/logout.php">🚪 Cerrar sesión</a>
    </div>

</div>