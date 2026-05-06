<?php include("../config/db.php"); ?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Punto de Venta</title>

<style>
body {
    font-family: Arial;
    background: #f4f6f9;
    margin: 0;
}

.container {
    display: flex;
    height: 100vh;
}

.left {
    width: 65%;
    padding: 20px;
}

.right {
    width: 35%;
    background: #1e1e2f;
    color: white;
    padding: 20px;
}

input {
    width: 100%;
    padding: 15px;
    font-size: 18px;
}

table {
    width: 100%;
    margin-top: 20px;
    border-collapse: collapse;
}

table th, table td {
    padding: 10px;
    border-bottom: 1px solid #ddd;
}

.total {
    font-size: 28px;
    margin-top: 20px;
}

.btn {
    padding: 15px;
    width: 100%;
    border: none;
    font-size: 18px;
    margin-top: 10px;
    cursor: pointer;
}

.pagar {
    background: #28a745;
    color: white;
}

.cancelar {
    background: #dc3545;
    color: white;
}
</style>
</head>

<body>

<div class="container">

    <!-- IZQUIERDA -->
    <div class="left">
        <h2>Escanear producto</h2>

        <input type="text" id="codigo" placeholder="Escanea código de barras" autofocus>

        <table id="tabla">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Precio</th>
                    <th>Cant</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

    <!-- DERECHA -->
    <div class="right">
        <h2>Total</h2>
        <div class="total" id="total">$0.00</div>

        <button class="btn pagar" onclick="pagar()">Pagar</button>
        <button class="btn cancelar" onclick="cancelar()">Cancelar</button>
    </div>

</div>

<script>
let carrito = [];

document.getElementById("codigo").addEventListener("keypress", function(e){
    if(e.key === "Enter"){
        let codigo = this.value;
        this.value = "";

        fetch("buscar_producto.php?codigo=" + codigo)
        .then(res => res.json())
        .then(data => {

            if(data.error){
                alert("Producto no encontrado");
                return;
            }

            agregarProducto(data);
        });
    }
});

function agregarProducto(producto){
    let existente = carrito.find(p => p.id == producto.id);

    if(existente){
        existente.cantidad++;
    } else {
        producto.cantidad = 1;
        carrito.push(producto);
    }

    render();
}

function render(){
    let tbody = document.querySelector("#tabla tbody");
    tbody.innerHTML = "";

    let total = 0;

    carrito.forEach(p => {
        let subtotal = p.precio_venta * p.cantidad;
        total += subtotal;

        tbody.innerHTML += `
        <tr>
            <td>${p.nombre}</td>
            <td>$${p.precio_venta}</td>
            <td>${p.cantidad}</td>
            <td>$${subtotal.toFixed(2)}</td>
        </tr>
        `;
    });

    document.getElementById("total").innerText = "$" + total.toFixed(2);
}

function pagar(){
    fetch("procesar_venta.php", {
        method: "POST",
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify(carrito)
    })
    .then(res => res.text())
    .then(resp => {
        alert("Venta realizada");
        carrito = [];
        render();
    });
}

function cancelar(){
    carrito = [];
    render();
}
</script>

</body>
</html>