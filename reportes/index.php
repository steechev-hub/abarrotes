<?php
session_start();
include("../config/db.php");

if(!isset($_SESSION['usuario'])){
    header("Location: ../auth/login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Reportes</title>

<link rel="stylesheet" href="../assets/css/style.css">

<style>

.card{
    background:white;
    padding:25px;
    border-radius:20px;
    box-shadow:0 5px 15px rgba(0,0,0,0.05);
}

.filtros{
    display:grid;
    grid-template-columns:repeat(4,1fr);
    gap:15px;
    margin-bottom:20px;
}

input,
select,
button{
    padding:14px;
    border-radius:10px;
    border:1px solid #ddd;
}

.btn{
    background:#0d6efd;
    color:white;
    border:none;
    cursor:pointer;
    font-weight:bold;
}

table{
    width:100%;
    border-collapse:collapse;
    margin-top:20px;
}

table th{
    background:#0d6efd;
    color:white;
    padding:12px;
}

table td{
    padding:12px;
    border-bottom:1px solid #eee;
    text-align:center;
}

.total{
    margin-top:20px;
    font-size:22px;
    font-weight:bold;
    color:#0d6efd;
}

</style>
</head>
<body>

<?php include("../includes/sidebar.php"); ?>

<div class="main">

<?php include("../includes/topbar.php"); ?>

<div class="card">

<h2>📊 Reportes de Ventas</h2>

<div class="filtros">

<select id="tipo">

<option value="dia">Día</option>
<option value="semana">Semana</option>
<option value="mes">Mes</option>
<option value="anio">Año</option>

</select>

<input type="date" id="fecha">

<button class="btn" onclick="cargarReporte()">

🔍 Cargar

</button>



</div>

<div id="resultado"></div>

</div>

</div>

<script>

function cargarReporte(){

    let tipo =
        document.getElementById("tipo").value;

    let fecha =
        document.getElementById("fecha").value;

    fetch(`reporte_ventas.php?tipo=${tipo}&fecha=${fecha}`)

    .then(res => res.text())

    .then(data => {

        document.getElementById("resultado")
        .innerHTML = data;

    });

}

function descargarExcel(){

    let tipo =
        document.getElementById("tipo").value;

    let fecha =
        document.getElementById("fecha").value;

    window.location =
        `generar_excel.php?tipo=${tipo}&fecha=${fecha}`;

}

</script>

</body>
</html>