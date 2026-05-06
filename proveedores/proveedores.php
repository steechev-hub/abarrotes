<?php
session_start();
include("../config/db.php");

if(!isset($_SESSION['usuario'])){
    header("Location: ../auth/login.php");
    exit();
}

$sql = "SELECT * FROM proveedores WHERE activo = 1 ORDER BY id DESC";
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

<table>
<thead>
<tr>
    <th>ID</th>
    <th>Nombre</th>
    <th>Teléfono</th>
    <th>Email</th>
    <th>Acciones</th>
</tr>
</thead>

<tbody>

<?php foreach($proveedores as $p): ?>

<tr>
    <td><?php echo $p['id']; ?></td>
    <td><?php echo $p['nombre']; ?></td>
    <td><?php echo $p['telefono']; ?></td>
    <td><?php echo $p['email']; ?></td>

    <td>
        <a href="editar.php?id=<?php echo $p['id']; ?>">✏️</a>
        <a href="eliminar.php?id=<?php echo $p['id']; ?>" 
           onclick="return confirm('¿Eliminar proveedor?')">🗑️</a>
    </td>
</tr>

<?php endforeach; ?>

</tbody>
</table>

</div>
</div>
</body>
</html>