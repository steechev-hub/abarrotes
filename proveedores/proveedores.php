<?php
session_start();
include("../config/db.php");

if(!isset($_SESSION['usuario'])){
    header("Location: ../auth/login.php");
    exit();
}

$sql = "

SELECT *

FROM proveedores

WHERE activo = 1

ORDER BY id DESC

";

$stmt = $conexion->query($sql);
$proveedores = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Proveedores</title>
<link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>

<?php include("../includes/sidebar.php"); ?>
<div class="main">
<?php include("../includes/topbar.php"); ?>

<div class="table-container">

<div class="top-actions">
    <h2>🚚 Proveedores</h2>

    <a href="crear.php" class="btn">
        ➕ Nuevo proveedor
    </a>
</div>

<div class="table-responsive">

    <table>
            <thead>

                <tr>

                    <th>Empresa</th>
                    <th>Marca</th>
                    <th>Tel. Empresa</th>
                    <th>Email</th>
                    <th>Dirección</th>
                    <th>Redes</th>
                    <th>Vendedor</th>
                    <th>Tel. Vendedor</th>
                    <th>Gerente</th>
                    <th>Tel. Gerente</th>
                    <th>Acciones</th>

                </tr>

            </thead>

                <tbody>

                <?php foreach($proveedores as $p): ?>

                    <tr>
                        <td><?php echo $p['nombre_empresa']; ?></td>
                        <td><?php echo $p['marca']; ?></td>
                        <td><?php echo $p['empresa_telefono']; ?></td>
                        <td><?php echo $p['email']; ?></td>
                        <td><?php echo $p['empresa_direccion']; ?></td>
                        <td><?php echo $p['redes_sociales']; ?></td>
                        <td><?php echo $p['vendedor_nombre']; ?></td>
                        <td><?php echo $p['vendedor_telefono']; ?></td>
                        <td><?php echo $p['gerente_nombre']; ?></td>
                        <td><?php echo $p['gerente_telefono']; ?></td>
                        <td class="acciones">
                            <a href="editar_proveedor.php?id=<?php echo $p['id']; ?>">✏️</a>
                            <a href="eliminar_proveedor.php?id=<?php echo $p['id']; ?>" onclick="return confirm('¿Eliminar proveedor?')"> 🗑️</a>

                        </td>

                    </tr>

                <?php endforeach; ?>

                </tbody>
            </table>
            

</div>
</div>
</body>
</html>

<style>

table{
    min-width:1600px;
}

    .table-container{
    background:#fff;
    padding:25px;
    border-radius:20px;
    box-shadow:0 5px 15px rgba(0,0,0,0.05);
}

.table-responsive{
    overflow-x:auto;
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
    transition:0.3s;
}

.btn:hover{
    opacity:0.9;
}

table{
    width:100%;
    border-collapse:collapse;
    overflow:hidden;
    border-radius:15px;
}

table thead{
    background:linear-gradient(90deg,#6a11cb,#2575fc);
    color:white;
}

table th{
    padding:15px;
    text-align:center;
    font-size:18px;
}

table td{
    padding:15px;
    text-align:center;
    border-bottom:1px solid #eee;
    font-size:16px;
}

table tbody tr:hover{
    background:#f5f9ff;
}

.acciones a{
    text-decoration:none;
    font-size:20px;
    margin:0 5px;
}
</style>
