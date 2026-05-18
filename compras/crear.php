<?php
include("../config/db.php");

$proveedores = $conexion->query("
SELECT *
FROM proveedores
ORDER BY nombre_empresa
")->fetchAll();

$productos = $conexion->query("
SELECT *
FROM productos
ORDER BY nombre
")->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Nueva Compra</title>

<style>

body{
    font-family:Arial;
    background:#f4f6f9;
    margin:0;
}

.container{
    padding:20px;
}

.card{
    background:white;
    padding:20px;
    border-radius:20px;
    box-shadow:0 5px 15px rgba(0,0,0,0.05);
}

select,
input{
    width:100%;
    padding:12px;
    margin-top:10px;
    margin-bottom:15px;
    border-radius:10px;
    border:1px solid #ddd;
}

table{
    width:100%;
    border-collapse:collapse;
    margin-top:20px;
}

table th{
    background:#0d6efd;
    color:white;
    padding:10px;
}

table td{
    padding:10px;
    border-bottom:1px solid #eee;
}

.btn{
    background:#55ccf0;
    color:#0d3b66;
    border:none;
    padding:12px 20px;
    border-radius:10px;
    cursor:pointer;
    font-weight:bold;
}

.total{
    margin-top:20px;
    font-size:22px;
    font-weight:bold;
}

.btn-back{
    display:inline-block;
    margin-top:25px;
    background:#6c757d;
    color:white;
    text-decoration:none;
    padding:10px 10px;
    border-radius:10px;
    font-weight:bold;
    transition:0.3s;
}

.btn-back:hover{
    background:#5a6268;
}

</style>
</head>
<body>

<div class="container">

<div class="card">

<h2>📦 Nueva Compra</h2>

<label>Proveedor</label>

<select id="proveedor">

<option value="">Seleccionar</option>

<?php foreach($proveedores as $p): ?>

<option value="<?php echo $p['id']; ?>">

<?php echo $p['nombre_empresa']; ?>

</option>

<?php endforeach; ?>

</select>

<hr><br>

<h3>💰 Pago</h3>

<label>Tipo de pago</label>

<select id="tipo_pago">

    <option value="contado">
        Contado
    </option>

    <option value="credito">
        Crédito
    </option>

</select>

<label>💵 Pago inicial</label>

<input
type="number"
id="pagado"
placeholder="0.00"
step="0.01"
value="0">

<hr><br>

<h3>Agregar Producto</h3>

<select id="producto">

<option value="">Seleccionar producto</option>

<?php foreach($productos as $p): ?>

<option
value="<?php echo $p['id']; ?>"
data-nombre="<?php echo $p['nombre']; ?>"
>

<?php echo $p['nombre']; ?>

</option>

<?php endforeach; ?>

</select>

<input type="number" id="cantidad" placeholder="Cantidad">

<input type="number" id="precio" placeholder="Costo compra">

<input type="text" id="lote" placeholder="Lote">

<input type="date" id="caducidad">

<button class="btn" onclick="agregarProducto()">

➕ Agregar

</button>

<table id="tabla">

<thead>

<tr>
<th>Producto</th>
<th>Cantidad</th>
<th>Costo</th>
<th>Lote</th>
<th>Caducidad</th>
<th>Subtotal</th>
</tr>

</thead>

<tbody></tbody>

</table>

<div class="total" id="total">

TOTAL: $0.00

</div>

<br>

<button class="btn" onclick="guardarCompra()">

💾 Guardar Compra

</button>

<a href="../index.php" class="btn-back">
                    ⬅ Regresar al menú principal
</a>

</div>

</div>

<script>

let compra = [];

function agregarProducto(){

    let select =
        document.getElementById("producto");

    let producto_id = select.value;

    let nombre =
        select.options[select.selectedIndex]
        .dataset.nombre;

    let cantidad =
        document.getElementById("cantidad").value;

    let precio =
        document.getElementById("precio").value;

    let lote =
        document.getElementById("lote").value;

    let caducidad =
        document.getElementById("caducidad").value;

    if(
        !producto_id ||
        !cantidad ||
        !precio
    ){
        alert("Completa datos");
        return;
    }

    compra.push({
        producto_id,
        nombre,
        cantidad,
        precio,
        lote,
        caducidad
    });

    render();

}

function render(){

    let tbody =
        document.querySelector("#tabla tbody");

    tbody.innerHTML = "";

    let total = 0;

    compra.forEach(p => {

        let subtotal =
            p.cantidad * p.precio;

        total += subtotal;

        tbody.innerHTML += `
        <tr>
            <td>${p.nombre}</td>
            <td>${p.cantidad}</td>
            <td>$${p.precio}</td>
            <td>${p.lote}</td>
            <td>${p.caducidad}</td>
            <td>$${subtotal.toFixed(2)}</td>
        </tr>
        `;
    });

    document.getElementById("total")
    .innerHTML =
        "TOTAL: $" + total.toFixed(2);

}

function guardarCompra(){

    let proveedor_id =
        document.getElementById("proveedor").value;

    let tipo_pago =
        document.getElementById("tipo_pago").value;

    let pagado =
        parseFloat(
            document.getElementById("pagado").value
        ) || 0;

    if(!proveedor_id){

        alert("Selecciona proveedor");

        return;
    }

    if(compra.length === 0){

        alert("No hay productos");

        return;
    }

    fetch("guardar_compra.php", {

        method:"POST",

        headers:{
            "Content-Type":"application/json"
        },

        body: JSON.stringify({

            proveedor_id,
            tipo_pago,
            pagado,
            productos: compra

        })

    })

    .then(res => res.json())

    .then(resp => {

        if(resp.ok){

            alert("Compra guardada correctamente");

            location.reload();

        } else {

            alert("Error al guardar");

        }

    })

    .catch(error => {

        console.error(error);

        alert("Error del sistema");

    });

}

</script>

</body>
</html>