<?php
session_start();
include("../config/db.php");

if(!isset($_SESSION['usuario'])){
    header("Location: ../auth/login.php");
    exit();
}

/* OBTENER USUARIOS */

$stmt = $conexion->query("
SELECT *
FROM usuarios
ORDER BY id DESC
");

$usuarios = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Usuarios</title>

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

.rol{
    padding:6px 12px;
    border-radius:8px;
    color:white;
    font-weight:bold;
}

.admin{
    background:#28c76f;
}

.cajero{
    background:#ff9f43;
}

.estado{
    padding:6px 12px;
    border-radius:8px;
    font-weight:bold;
}

.activo{
    background:#e8f5e9;
    color:#2e7d32;
}

.inactivo{
    background:#ffebee;
    color:#d32f2f;
}

.acciones a{
    text-decoration:none;
    margin:0 5px;
    font-size:18px;
}

</style>
</head>

<body>

<?php include("../includes/sidebar.php"); ?>

<div class="main">

<?php include("../includes/topbar.php"); ?>

<div class="table-container">

    <!-- BUSCADOR -->

    <div class="search-box">

        <input
        type="text"
        id="buscar"
        placeholder="🔍 Buscar usuario...">

    </div>

    <!-- HEADER -->

    <div class="top-actions">

        <h2>👥 Usuarios</h2>

        <a href="crear.php" class="btn">
            ➕ Nuevo usuario
        </a>

    </div>

    <!-- TABLA -->

    <table>

        <thead>

            <tr>

                <th>ID</th>
                <th>Nombre</th>
                <th>Usuario</th>
                <th>Rol</th>
                <th>Estado</th>
                <th>Acciones</th>

            </tr>

        </thead>

        <tbody id="tabla-usuarios">

        <?php foreach($usuarios as $u): ?>

        <?php

        $claseRol =
            ($u['rol'] == 'admin')
            ? 'admin'
            : 'cajero';

        $claseEstado =
            ($u['activo'] == 1)
            ? 'activo'
            : 'inactivo';

        ?>

        <tr>

            <td>
                <?php echo $u['id']; ?>
            </td>

            <td>
                <?php echo $u['nombre']; ?>
            </td>

            <td>
                <?php echo $u['usuario']; ?>
            </td>

            <td>

                <span class="rol <?php echo $claseRol; ?>">

                    <?php echo strtoupper($u['rol']); ?>

                </span>

            </td>

            <td>

                <span class="estado <?php echo $claseEstado; ?>">

                    <?php
                    echo ($u['activo'] == 1)
                    ? 'ACTIVO'
                    : 'INACTIVO';
                    ?>

                </span>

            </td>

            <td class="acciones">

                <a href="editar.php?id=<?php echo $u['id']; ?>">
                    ✏️
                </a>

                <a
                href="eliminar.php?id=<?php echo $u['id']; ?>"
                onclick="return confirm('¿Eliminar usuario?')">

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

/* BUSCADOR */

document.getElementById("buscar")
.addEventListener("keyup", function(){

    let valor =
        this.value.toLowerCase();

    let filas =
        document.querySelectorAll("#tabla-usuarios tr");

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