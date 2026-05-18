<?php
session_start();
include("../config/db.php");

if(!isset($_SESSION['usuario'])){
    header("Location: ../auth/login.php");
    exit();
}

/* DATOS */

$producto_id = $_POST['producto_id'];
$tipo = $_POST['tipo'];
$motivo = $_POST['motivo'];
$cantidad = intval($_POST['cantidad']);

/* OBTENER PRODUCTO */

$stmt = $conexion->prepare("
SELECT *
FROM productos
WHERE id = ?
");

$stmt->execute([$producto_id]);

$producto = $stmt->fetch();

if(!$producto){

    die("Producto no encontrado");
}

/* STOCK ACTUAL */

$stock_anterior = intval($producto['stock']);

/* CALCULAR NUEVO STOCK */

if($tipo == 'entrada'){

    $stock_nuevo =
        $stock_anterior + $cantidad;

}
elseif($tipo == 'salida' || $tipo == 'merma'){

    if($cantidad > $stock_anterior){

        die("Stock insuficiente");
    }

    $stock_nuevo =
        $stock_anterior - $cantidad;

}
elseif($tipo == 'ajuste'){

    $stock_nuevo = $cantidad;

}
else{

    die("Tipo inválido");
}

/* ACTUALIZAR STOCK */

$update = $conexion->prepare("
UPDATE productos
SET stock = ?
WHERE id = ?
");

$update->execute([

    $stock_nuevo,
    $producto_id

]);

/* GUARDAR MOVIMIENTO */

$mov = $conexion->prepare("
INSERT INTO movimientos_inventario
(
    producto_id,
    tipo,
    motivo,
    cantidad,
    stock_anterior,
    stock_nuevo
)
VALUES(?,?,?,?,?,?)
");

$mov->execute([

    $producto_id,
    $tipo,
    $motivo,
    $cantidad,
    $stock_anterior,
    $stock_nuevo

]);

/* REDIRECCION */

header("Location: index.php");

exit();
?>