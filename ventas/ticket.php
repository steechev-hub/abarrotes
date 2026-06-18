<?php
include("../config/db.php");

$id = $_GET['id'];

$stmt = $conexion->prepare("
SELECT *
FROM ventas
WHERE id = ?
");

$stmt->execute([$id]);

$venta = $stmt->fetch();

$detalle = $conexion->prepare("
SELECT
detalle_venta.*,
productos.nombre,
productos.contenido_medida,
productos.unidad_medida
FROM detalle_venta
INNER JOIN productos
ON detalle_venta.producto_id = productos.id
WHERE venta_id = ?
");

$detalle->execute([$id]);

$productos = $detalle->fetchAll();

$config = $conexion->query("
SELECT *
FROM configuracion_ticket
LIMIT 1
")->fetch();

$cajero = [
    'nombre' => 'Sin cajero'
];

if(!empty($venta['usuario_id'])){

    $usuario = $conexion->prepare("
    SELECT id,nombre,rol
    FROM usuarios
    WHERE id = ?
    ");

    $usuario->execute([
        $venta['usuario_id']
    ]);

    $resultado = $usuario->fetch();

    if($resultado){

        if($resultado['rol'] == 'administrador'){

            $resultado['nombre'] = 'PV1';

        }else{

            $resultado['nombre'] =
                'PV' . ($resultado['id'] + 1);

        }

        $cajero = $resultado;
    }
}

/* FOLIO DIARIO */
/* FOLIO IGUAL AL HISTORIAL */

$fechaVenta = date('Y-m-d', strtotime($venta['fecha']));

$stmtFolios = $conexion->prepare("
SELECT id, fecha
FROM ventas
WHERE DATE(fecha)=?
ORDER BY fecha ASC
");

$stmtFolios->execute([$fechaVenta]);

$ventasDia = $stmtFolios->fetchAll();

$consecutivo = 1;
$folio = "";

foreach($ventasDia as $v){

    if($v['id'] == $venta['id']){

        $folio = "ST" .
        date('dmy', strtotime($venta['fecha'])) .
        "/" .
        str_pad($consecutivo, 3, "0", STR_PAD_LEFT);

        break;
    }

    $consecutivo++;
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Ticket</title>

<style>

body{
    font-family:Arial,sans-serif;
    width:58mm;
    margin:auto;
    padding:5px;
    color:#000;
    font-size:14px;
}

.logo{
    text-align:center;
    margin-bottom:10px;
}

.logo img{
    width:160px;
}

.negocio{
    text-align:center;
    font-size:22px;
    font-weight:bold;
    margin-bottom:5px;
}

.folio{
    text-align:center;
    font-size:18px;
    font-weight:bold;
    margin:8px 0;
}

.info{
    text-align:center;
    font-size:13px;
    line-height:1.4;
}

hr{
    border:none;
    border-top:2px dashed #000;
    margin:8px 0;
}

table{
    width:100%;
    border-collapse:collapse;
    font-size:13px;
}

td{
    padding:2px 0;
}

.producto{
    font-weight:bold;
}

.precio{
    font-size:12px;
}

.total-box{
    margin-top:10px;
}

.total-line{
    display:flex;
    justify-content:space-between;
    margin:4px 0;
    font-size:15px;
}

.total-final{
    font-size:22px;
    font-weight:bold;
}

.footer{
    text-align:center;
    font-size:13px;
    margin-top:10px;
}

</style>
</head>

<body onload="window.print()">

<?php if($config['logo'] != ''): ?>

    <div class="logo">
        <img src="../uploads/<?php echo $config['logo']; ?>">
    </div>
<?php endif; ?>
    <div class="folio">
        FOLIO: <?php echo $folio; ?>
    </div>
<div class="info">

    <?php echo $config['direccion']; ?>
    <br> TEL: <?php echo $config['telefono']; ?>
    <br>Fecha: <?php echo date('d/m/Y H:i', strtotime($venta['fecha'])); ?>

</div>

<?php if($config['mostrar_cajero']): ?>

    <div class="info">
        CAJERO: <?php echo $cajero['nombre']; ?>
    </div>

<?php endif; ?>

<hr>
<table>
    <?php foreach($productos as $p): ?>

        <tr>
            <td colspan="2" class="producto">
                <?php echo $p['nombre']; ?>
                <?php if(
                    !empty($p['contenido_medida']) &&
                    !empty($p['unidad_medida'])
                ): ?>
                    (
                    <?php echo $p['contenido_medida']; ?>
                    <?php echo $p['unidad_medida']; ?>
                    )
                <?php endif; ?>
            </td>
        </tr>

        <tr>
            <td class="precio">
                $ <?php echo number_format($p['precio'],2); ?>
                x <?php echo $p['cantidad']; ?>
            </td>

            <td align="right">
                $ <?php echo number_format($p['subtotal'],2); ?>
            </td>
        </tr>

    <?php endforeach; ?>

</table>

<hr>

<div class="total-box">
    <div class="total-line total-final">
        <span>TOTAL</span>
        <span>
            $<?php echo number_format($venta['total'],2); ?>
        </span>
    </div>

    <div class="total-line">
        <span>RECIBIDO</span>
        <span>
            $<?php echo number_format($venta['recibido'],2); ?>
        </span>
    </div>
    <div class="total-line">
        <span>CAMBIO</span>
        <span>
            $<?php echo number_format($venta['cambio'],2); ?>
        </span>
    </div>
</div>

<hr>

<div class="footer">
    <?php echo nl2br($config['mensaje_final']); ?>
</div>

</body>
</html>