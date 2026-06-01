<?php
session_start();
include("../config/db.php");

if(!isset($_SESSION['usuario'])){
    header("Location: ../auth/login.php");
    exit();
}

/* PRODUCTOS */

$productos = $conexion->query("
SELECT *
FROM productos
ORDER BY nombre ASC
")->fetchAll();

/* GUARDAR */

if($_SERVER['REQUEST_METHOD'] == 'POST'){

    $producto_id = $_POST['producto_id'];
    $cantidad = intval($_POST['cantidad']);
    $motivo = trim($_POST['motivo']);

    /* OBTENER STOCK ACTUAL */

    $stmt = $conexion->prepare("
    SELECT stock_almacen
    FROM productos
    WHERE id = ?
    ");

    $stmt->execute([$producto_id]);

    $producto = $stmt->fetch();

    if($producto){

        $nuevo_stock =
            $producto['stock_almacen'] + $cantidad;

        /* ACTUALIZAR STOCK */

        $update = $conexion->prepare("
        UPDATE productos
        SET stock_almacen =  ?
        WHERE id = ?
        ");

        $update->execute([
            $nuevo_stock,
            $producto_id
        ]);

        /* REGISTRAR MOVIMIENTO */

        $mov = $conexion->prepare("
        INSERT INTO movimientos_inventario
        (
            producto_id,
            tipo,
            motivo,
            cantidad,
            fecha
        )
        VALUES (?,?,?,?,NOW())
        ");

        $mov->execute([
            $producto_id,
            'entrada_cortesia',
            $motivo,
            $cantidad
        ]);

        header("Location: index.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Entrada por cortesía</title>

<link rel="stylesheet" href="../assets/css/style.css">

<style>

.form-container{
    background:white;
    padding:30px;
    border-radius:20px;
    max-width:700px;
    box-shadow:0 5px 15px rgba(0,0,0,0.05);
}

.form-group{
    margin-bottom:20px;
}

label{
    display:block;
    margin-bottom:8px;
    font-weight:bold;
    color:#0d3b66;
}

input,
select,
textarea{
    width:100%;
    padding:14px;
    border:1px solid #ddd;
    border-radius:12px;
    outline:none;
    font-size:15px;
}

input:focus,
select:focus,
textarea:focus{
    border-color:#55ccf0;
}

.btn{
    width:100%;
    background:#28c76f;
    color:white;
    border:none;
    padding:15px;
    border-radius:12px;
    font-size:16px;
    font-weight:bold;
    cursor:pointer;
    transition:.2s;
}

.btn:hover{
    transform:translateY(-2px);
}

.info{
    background:#e8f5e9;
    color:#2e7d32;
    padding:15px;
    border-radius:12px;
    margin-bottom:20px;
    font-weight:bold;
}

</style>
</head>

<body>

<?php include("../includes/sidebar.php"); ?>

<div class="main">

<?php include("../includes/topbar.php"); ?>

<div class="form-container">

<h2 style="margin-bottom:20px; color:#0d3b66;">
🎁 Entrada por cortesía
</h2>

<div class="info">

Esta opción aumenta el stock sin generar compra.

</div>

<form method="POST">

    <div class="form-group">

        <label>📦 Producto</label>

        <select name="producto_id" required>

            <option value="">
                Seleccionar producto
            </option>

            <?php foreach($productos as $p): ?>

                <option value="<?php echo $p['id']; ?>">

                    <?php echo $p['nombre']; ?>

                </option>

            <?php endforeach; ?>

        </select>

    </div>

    <div class="form-group">

        <label>➕ Cantidad</label>

        <input
        type="number"
        name="cantidad"
        min="1"
        required>

    </div>

    <div class="form-group">

        <label>📝 Motivo</label>

        <textarea
        name="motivo"
        rows="4"
        placeholder="Ejemplo: producto regalado por proveedor"
        required></textarea>

    </div>

    <button class="btn">

        💾 Guardar entrada

    </button>

</form>

</div>

</div>

</body>
</html>