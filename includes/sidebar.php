<div class="sidebar">

    <div class="logo">
        SURTETE
    </div>

    <div class="menu">
        <a href="/surtete/index.php">🏠 Inicio</a>
        <a href="/surtete/ventas/pos.php">🛒 Ventas</a>
        <a href="/surtete/productos/index.php">📦 Productos</a>
        <a href="/surtete/proveedores/proveedores.php">🚚 Proveedores</a>
        <a href="/surtete/compras/crear.php">📥 Compras</a>
        <a href="/surtete/compras/index.php">📥 Cuentas por pagar</a>
        <a href="/surtete/almacen/index.php">📊 Almacen</a>
        <a href="/surtete/inventario/index.php">📊 Inventario</a>
        <a href="/surtete/inventario/agregar_movimiento.php">📊 Movimientos de inventario</a>
        <a href="#">📊 Reportes</a>
        <a href="/surtete/ventas/historial_tickets.php">🧾 Historial de tickets</a>
        

        <?php if($_SESSION['rol'] == 'admin'): ?>
            <a href="#">⚙️ Configuración</a>
        <?php endif; ?>
        
        <a href="/surtete/auth/logout.php">🚪 Cerrar sesión</a>
    </div>

</div>