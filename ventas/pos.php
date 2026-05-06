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
#codigo{
    width:100%;
    padding:18px;
    font-size:22px;
    border-radius:15px;
    border:2px solid #55ccf0;
    outline:none;
    transition:.2s;
    background:white;
}

#codigo:focus{
    border-color:#0d6efd;
    box-shadow:0 0 10px rgba(13,110,253,0.2);
}
.pago-box,
.cambio-box{
    margin-top:20px;
}

.pago-box label,
.cambio-box label{
    display:block;
    margin-bottom:8px;
    font-size:16px;
}

#recibido{
    width:100%;
    padding:15px;
    font-size:22px;
    border:none;
    border-radius:12px;
    outline:none;
}

#cambio{
    background:white;
    color:#0d6efd;
    padding:15px;
    border-radius:12px;
    font-size:28px;
    font-weight:bold;
    text-align:center;
}
</style>
</head>

<body>

<div class="container">

    <!-- IZQUIERDA -->
    <div class="left">
        <h2>Escanear producto</h2>

        <input type="text" id="codigo" placeholder="Escanea código de barras" autofocus autocomplete="off">

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

        <div class="pago-box">

        <label>💵 Dinero recibido</label>

        <input type="number"
            id="recibido"
            placeholder="0.00"
            step="0.01">

        </div>

        <div class="cambio-box">

            <label>💰 Cambio</label>

            <div id="cambio">$0.00</div>

        </div>

        <button class="btn pagar" onclick="pagar()">Pagar</button>
        <button class="btn cancelar" onclick="cancelar()">Cancelar</button>
    </div>

</div>

<script>

let carrito = [];
let totalGeneral = 0;

const codigoInput = document.getElementById("codigo");

codigoInput.focus();

/* SIEMPRE MANTENER FOCUS */
let escribiendoPago = false;

/* CUANDO ESCRIBE EN DINERO */
document.getElementById("recibido")
.addEventListener("focus", () => {

    escribiendoPago = true;

});

/* CUANDO SALE DEL INPUT */
document.getElementById("recibido")
.addEventListener("blur", () => {

    escribiendoPago = false;

    codigoInput.focus();

});

/* MANTENER FOCUS SOLO SI NO ESTA ESCRIBIENDO */
document.addEventListener("click", (e) => {

    if(!escribiendoPago &&
       e.target.id !== "recibido"){

        codigoInput.focus();
    }

});

/* ESCANEO RAPIDO */
codigoInput.addEventListener("keypress", function(e){

    if(e.key === "Enter"){

        let codigo = this.value.trim();

        if(codigo === ''){
            return;
        }

        this.value = "";

        fetch("buscar_producto.php?codigo=" + codigo)

        .then(res => res.json())

        .then(data => {

            if(data.error){

                alert("Producto no encontrado");

                return;
            }

            /* SONIDO */
            document.getElementById("beep").play();

            agregarProducto(data);

            /* REGRESAR FOCUS */
            codigoInput.focus();

        });

    }

});

/* AGREGAR PRODUCTOS */

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

/* RENDER */

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
            <td>$${parseFloat(p.precio_venta).toFixed(2)}</td>
            <td>${p.cantidad}</td>
            <td>$${subtotal.toFixed(2)}</td>
        </tr>
        `;
    });

    document.getElementById("total").innerText =
        "$" + total.toFixed(2);

    totalGeneral = total;

    calcularCambio();
}

/* CAMBIO AUTOMATICO */

document.getElementById("recibido")
.addEventListener("input", calcularCambio);

function calcularCambio(){

    let recibido =
        parseFloat(document.getElementById("recibido").value) || 0;

    let cambio = recibido - totalGeneral;

    if(cambio < 0){

        document.getElementById("cambio").innerHTML =
            "Falta $" + Math.abs(cambio).toFixed(2);

        document.getElementById("cambio").style.color = "red";

    } else {

        document.getElementById("cambio").innerHTML =
            "$" + cambio.toFixed(2);

        document.getElementById("cambio").style.color =
            "#0d6efd";
    }

}

/* PAGAR */

function pagar(){

    if(carrito.length === 0){

        alert("No hay productos");

        return;
    }

    let recibido =
        parseFloat(document.getElementById("recibido").value) || 0;

    if(recibido < totalGeneral){

        alert("Dinero insuficiente");

        return;
    }

    fetch("procesar_venta.php", {

        method: "POST",

        headers: {
            'Content-Type': 'application/json'
        },

        body: JSON.stringify({
            productos: carrito,
            recibido: recibido
        })

    })

    .then(res => res.json())

    .then(resp => {

        window.open(
            "ticket.php?id=" + resp.venta_id,
            "_blank"
        );

        carrito = [];

        render();

        document.getElementById("recibido").value = "";

        document.getElementById("cambio").innerHTML = "$0.00";

        codigoInput.focus();

    });

}

/* CANCELAR */

function cancelar(){

    carrito = [];

    render();

    document.getElementById("recibido").value = "";

    document.getElementById("cambio").innerHTML = "$0.00";

    codigoInput.focus();
}

</script>

<audio id="beep" preload="auto">
    <source src="../assets/beep.mp3" type="audio/mpeg">
</audio>

</body>
</html>