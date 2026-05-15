<?php
session_start();
include("../config/db.php");

$id = $_GET['id'];

$stmt = $conexion->prepare("
SELECT *
FROM compras
WHERE id = ?
");

$stmt->execute([$id]);

$compra = $stmt->fetch();

if(!$compra){
    die("Compra no encontrada");
}

if($_SERVER['REQUEST_METHOD'] == 'POST'){

    $monto = $_POST['monto'];

    $metodo = $_POST['metodo'];

    $comentario = $_POST['comentario'];

    /*
    GUARDAR ABONO
    */

    $sql = "
    INSERT INTO abonos_compra (

        compra_id,
        monto,
        metodo_pago,
        comentario

    ) VALUES (?, ?, ?, ?)
    ";

    $stmt = $conexion->prepare($sql);

    $stmt->execute([

        $id,
        $monto,
        $metodo,
        $comentario

    ]);

    /*
    ACTUALIZAR COMPRA
    */

    $nuevoPagado =
        $compra['pagado'] + $monto;

    $nuevoSaldo =
        $compra['total'] - $nuevoPagado;

    $estado = 'pendiente';

    if($nuevoSaldo <= 0){

        $estado = 'pagado';

        $nuevoSaldo = 0;
    }
    elseif($nuevoPagado > 0){

        $estado = 'parcial';
    }

    $update = "
    UPDATE compras
    SET

    pagado = ?,
    saldo = ?,
    estado_pago = ?

    WHERE id = ?
    ";

    $stmt = $conexion->prepare($update);

    $stmt->execute([

        $nuevoPagado,
        $nuevoSaldo,
        $estado,
        $id

    ]);

    header("Location: index.php");

    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Abonar Compra</title>

<style>

body{
    font-family:'Segoe UI';
    background:#f4f6f9;
}

.card{
    background:white;
    max-width:600px;
    margin:40px auto;
    padding:30px;
    border-radius:20px;
    box-shadow:0 5px 15px rgba(0,0,0,0.05);
}

input,
select,
textarea{
    width:100%;
    padding:14px;
    margin-top:10px;
    margin-bottom:15px;
    border-radius:10px;
    border:1px solid #ddd;
}

.btn{
    background:#0d6efd;
    color:white;
    border:none;
    padding:14px;
    width:100%;
    border-radius:12px;
    font-weight:bold;
    cursor:pointer;
}

.info{
    background:#f8fbff;
    padding:15px;
    border-radius:15px;
    margin-bottom:20px;
}

</style>
</head>

<body>

<div class="card">

<h2>💵 Registrar Abono</h2>

<div class="info">

<p>
<b>Total:</b>
$<?php echo number_format($compra['total'],2); ?>
</p>

<p>
<b>Pagado:</b>
$<?php echo number_format($compra['pagado'],2); ?>
</p>

<p>
<b>Saldo:</b>
$<?php echo number_format($compra['saldo'],2); ?>
</p>

</div>

<form method="POST">

<label>Monto</label>

<input
type="number"
step="0.01"
name="monto"
required>

<label>Método de pago</label>

<select name="metodo">

<option>Efectivo</option>
<option>Transferencia</option>
<option>Tarjeta</option>

</select>

<label>Comentario</label>

<textarea name="comentario"></textarea>

<button class="btn">

Guardar Abono

</button>

</form>

</div>

</body>
</html>