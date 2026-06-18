<?php
session_start();

if(!isset($_SESSION['usuario'])){
    header("Location: auth/login.php");
    exit();
}

include("config/db.php");

/* VENTAS DEL DÍA */

$stmt = $conexion->prepare("
SELECT SUM(total) AS total
FROM ventas
WHERE DATE(fecha)=CURDATE()
");

$stmt->execute();

$venta = $stmt->fetch();

$ventas_hoy = $venta['total'] ?? 0;

$fecha_actual = date("d/m/Y");
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Dashboard</title>

<link rel="stylesheet" href="assets/css/style.css">

<style>

.dashboard{
    display:flex;
    justify-content:center;
    align-items:center;
    min-height:85vh;
}

.panel-central{
    background:white;
    width:100%;
    max-width:900px;
    border-radius:25px;
    padding:40px;
    text-align:center;
    box-shadow:0 10px 25px rgba(0,0,0,0.08);
}

.logo{
    max-width:350px;
    max-height:250px;
    object-fit:contain;
    margin-bottom:25px;
}

.titulo{
    font-size:32px;
    color:#0d3b66;
    margin-bottom:10px;
}

.subtitulo{
    color:#777;
    margin-bottom:30px;
}

.info-grid{
    display:grid;
    grid-template-columns:repeat(3,1fr);
    gap:20px;
    margin-top:30px;
}

.info-card{
    background:#f4f9ff;
    border-radius:18px;
    padding:25px;
    transition:.3s;
}

.info-card:hover{
    transform:translateY(-4px);
}

.info-card h3{
    margin:0;
    color:#0d3b66;
    font-size:18px;
}

.info-card p{
    margin-top:10px;
    font-size:20px;
    font-weight:bold;
    color:#333;
}

.bienvenida{
    margin-top:20px;
    font-size:18px;
    color:#555;
}

@media(max-width:768px){

    .info-grid{
        grid-template-columns:1fr;
    }

    .logo{
        max-width:250px;
    }
}

</style>

</head>

<body>

<?php include("includes/sidebar.php"); ?>

<div class="main">

    <?php include("includes/topbar.php"); ?>

    <div class="dashboard">

        <div class="panel-central">

            <!-- LOGO -->

            <img
            src="assets/img/logo.png"
            class="logo"
            alt="Logo">

            <h1 class="titulo">
                SURTETE
            </h1>

            <div class="bienvenida">
                Bienvenido(a) al sistema
            </div>

            <div class="info-grid">

                <div class="info-card">
                    <h3>👤 Usuario conectado</h3>
                    <p>
                        <?php echo $_SESSION['usuario']; ?>
                    </p>
                </div>

                <div class="info-card">
                    <h3>📅 Fecha actual</h3>
                    <p>
                        <?php echo $fecha_actual; ?>
                    </p>
                </div>

                <div class="info-card">
                    <h3>💰 Ventas del día</h3>
                    <p>
                        $<?php echo number_format($ventas_hoy,2); ?>
                    </p>
                </div>

            </div>

        </div>

    </div>

</div>

</body>
</html>