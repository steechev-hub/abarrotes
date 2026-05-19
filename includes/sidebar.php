<div class="sidebar" id="sidebar">

    <div class="toggle-btn" onclick="toggleSidebar()">
        ☰
    </div>

    <div class="logo">
        SURTETE
    </div>

    <div class="menu">

        <a href="/surtete/index.php">
            🏠 <span>Inicio</span>
        </a>

        <a href="/surtete/ventas/pos.php">
            🛒 <span>Ventas</span>
        </a>

        <a href="/surtete/productos/index.php">
            📦 <span>Productos</span>
        </a>

        <a href="/surtete/proveedores/proveedores.php">
            🚚 <span>Proveedores</span>
        </a>

        <a href="/surtete/compras/crear.php">
            📥 <span>Compras</span>
        </a>

        <a href="/surtete/compras/index.php">
            💳 <span>Cuentas por pagar</span>
        </a>

        <a href="/surtete/almacen/index.php">
            🏬 <span>Almacén</span>
        </a>

        <a href="/surtete/inventario/index.php">
            📊 <span>Movimientos de Inventario</span>
        </a>

        <a href="/surtete/inventario/agregar_movimiento.php">
            🔄 <span>Movimientos</span>
        </a>

        <a href="#">
            📈 <span>Reportes</span>
        </a>

        <a href="/surtete/ventas/historial_tickets.php">
            🧾 <span>Historial de Tickets</span>
        </a>

        <?php if($_SESSION['rol'] == 'admin'): ?>

            <a href="/surtete/configuracion/ticket.php">
                ⚙️ <span>Configuración</span>
            </a>

        <?php endif; ?>

        <a href="/surtete/auth/logout.php">
            🚪 <span>Cerrar sesión</span>
        </a>

    </div>

</div>

<style>

.sidebar{
    width:260px;
    height:100vh;
    background:#0d3b66;
    position:fixed;
    left:0;
    top:0;
    transition:.3s;
    overflow-y:auto;
    overflow-x:hidden;
    z-index:999;
}
.sidebar.closed{
    width:80px;
}

.logo{
    color:white;
    font-size:28px;
    font-weight:bold;
    text-align:center;
    padding:25px 10px;
}

.menu{
    display:flex;
    flex-direction:column;
    margin-top:10px;
}

.menu a{
    color:white;
    text-decoration:none;
    padding:16px 20px;
    transition:.2s;
    display:flex;
    align-items:center;
    gap:12px;
    font-size:15px;
}

.menu a:hover{
    background:rgba(255,255,255,0.12);
}

.menu a span{
    transition:.2s;
    white-space:nowrap;
}

.sidebar.closed .menu a span{
    display:none;
}

.sidebar.closed .logo{
    font-size:18px;
}

.toggle-btn{
    position:absolute;
    top:15px;
    right:15px;
    background:#55ccf0;
    color:#0d3b66;
    width:40px;
    height:40px;
    border-radius:10px;
    display:flex;
    align-items:center;
    justify-content:center;
    cursor:pointer;
    font-size:20px;
    font-weight:bold;
}

.main{
    margin-left:260px;
    transition:.3s;
    padding:20px;
}

.sidebar.closed ~ .main{
    margin-left:80px;
}
/* SCROLL PERSONALIZADO */

.sidebar::-webkit-scrollbar{
    width:8px;
}

.sidebar::-webkit-scrollbar-thumb{
    background:#55ccf0;
    border-radius:10px;
}

.sidebar::-webkit-scrollbar-track{
    background:transparent;
}

</style>

<script>

function toggleSidebar(){

    let sidebar =
        document.getElementById("sidebar");

    sidebar.classList.toggle("closed");

}

</script>