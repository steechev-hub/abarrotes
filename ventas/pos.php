<?php include("../config/db.php"); ?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Punto de Venta</title>

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
}

body{
    font-family:'Segoe UI',sans-serif;
    background:#eef4ff;
    overflow:hidden;
}

/* CONTENEDOR */

.container{
    display:flex;
    height:100vh;
}

/* IZQUIERDA */

.left{
    width:70%;
    padding:25px;
    overflow:auto;
}

/* HEADER */

.topbar{
    background:white;
    padding:20px;
    border-radius:20px;
    margin-bottom:20px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    box-shadow:0 5px 15px rgba(0,0,0,0.05);
}

.topbar h2{
    color:#0d3b66;
}

.fecha{
    color:#666;
    font-weight:bold;
}

/* ESCANEO */

.scan-box{
    background:white;
    padding:20px;
    border-radius:20px;
    box-shadow:0 5px 15px rgba(0,0,0,0.05);
    margin-bottom:20px;
}

#codigo{
    width:100%;
    padding:18px;
    border-radius:15px;
    border:2px solid #55ccf0;
    font-size:22px;
    outline:none;
    transition:.2s;
}

#codigo:focus{
    border-color:#0d6efd;
    box-shadow:0 0 10px rgba(13,110,253,0.2);
}

/* TABLA */

.table-box{
    background:white;
    padding:20px;
    border-radius:20px;
    box-shadow:0 5px 15px rgba(0,0,0,0.05);
}

table{
    width:100%;
    border-collapse:collapse;
}

table th{
    background:#55ccf0;
    color:#0d3b66;
    padding:15px;
    font-size:15px;
}

table td{
    padding:14px;
    border-bottom:1px solid #eee;
}

/* DERECHA */

.right{
    width:30%;
    background:#0d3b66;
    color:white;
    padding:25px;
    display:flex;
    flex-direction:column;
}

/* TOTAL */

.total-box{
    background:white;
    color:#0d3b66;
    padding:25px;
    border-radius:20px;
    text-align:center;
}

.total-box h1{
    font-size:50px;
}

/* INPUTS */

.input-group{
    margin-top:20px;
}

.input-group label{
    display:block;
    margin-bottom:8px;
    font-weight:bold;
}

#recibido{
    width:100%;
    padding:15px;
    border:none;
    border-radius:15px;
    font-size:22px;
    outline:none;
}

/* CAMBIO */

.cambio{
    margin-top:20px;
    background:white;
    color:#0d6efd;
    padding:20px;
    border-radius:20px;
    text-align:center;
}

.cambio h1{
    font-size:42px;
}

/* BOTONES */

.botones{
    margin-top:20px;
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:10px;
}

.btn{
    border:none;
    padding:15px;
    border-radius:15px;
    font-size:16px;
    cursor:pointer;
    font-weight:bold;
    transition:.2s;
}

.btn:hover{
    transform:translateY(-2px);
}

.pagar{
    background:#28c76f;
    color:white;
}

.cancelar{
    background:#ea5455;
    color:white;
}

.ticket{
    background:#55ccf0;
    color:#0d3b66;
}

.buscar{
    background:#ffffff;
    color:#0d3b66;
}

/* ACCIONES RAPIDAS */

.quick-actions{
    margin-top:25px;
}

.quick-actions h3{
    margin-bottom:15px;
}

.quick-grid{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:10px;
}

.quick-btn{
    background:rgba(255,255,255,0.1);
    border:none;
    color:white;
    padding:20px;
    border-radius:15px;
    cursor:pointer;
    font-size:15px;
    transition:.2s;
}

.quick-btn:hover{
    background:rgba(255,255,255,0.2);
}

/* SCROLL */

.left::-webkit-scrollbar{
    width:8px;
}

.left::-webkit-scrollbar-thumb{
    background:#55ccf0;
    border-radius:10px;
}

</style>
</head>

<body>

<div class="container">

    <!-- IZQUIERDA -->

    <div class="left">

        <div class="topbar">

            <h2>🛒 Punto de Venta</h2>

            <div class="fecha">
                <?php echo date("d/m/Y H:i"); ?>
            </div>

        </div>

        <div class="scan-box">

            <input
            type="text"
            id="codigo"
            placeholder="🔍 Escanea o escribe código de barras"
            autofocus
            autocomplete="off">

        </div>

        <div class="table-box">

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

    </div>

    <!-- DERECHA -->

    <div class="right">

        <div class="total-box">

            <p>TOTAL</p>

            <h1 id="total">$0.00</h1>

        </div>

        <div class="input-group">

            <label>💵 Dinero recibido</label>

            <input
            type="number"
            id="recibido"
            placeholder="0.00"
            step="0.01">

        </div>

        <div class="cambio">

            <p>💰 Cambio</p>

            <h1 id="cambio">$0.00</h1>

        </div>

        <!-- BOTONES -->

        <div class="botones">

            <button
            class="btn pagar"
            onclick="pagar()">
            💳 Cobrar
            </button>

            <button
            class="btn cancelar"
            onclick="cancelar()">
            ❌ Cancelar
            </button>

            <button
            class="btn ticket">
            🧾 Tickets
            </button>

            <button
            class="btn buscar">
            🔎 Buscar
            </button>

        </div>

        <!-- ACCIONES -->

        <div class="quick-actions">

            <h3>⚡ Acciones rápidas</h3>

            <div class="quick-grid">

                <button class="quick-btn">
                    📦 Inventario
                </button>

                <button class="quick-btn">
                    🚚 Compras
                </button>

                <button class="quick-btn">
                    📊 Reportes
                </button>

                <button class="quick-btn">
                    💰 Caja
                </button>

            </div>

        </div>

    </div>

</div>
/* TU JAVASCRIPT ACTUAL VA AQUÍ */
/* NO BORRES TU LOGICA */
<script>

let carrito = [];
let totalGeneral = 0;

const codigoInput =
    document.getElementById("codigo");

/* FOCUS */

codigoInput.focus();

/* =========================
ESCANEO RAPIDO
========================= */

codigoInput.addEventListener("keypress", function(e){

    if(e.key === "Enter"){

        let codigo = this.value.trim();

        if(codigo == ""){
            return;
        }

        this.value = "";

        fetch("buscar_producto.php?codigo=" + codigo)

        .then(res => res.json())

        .then(data => {

            if(data.error){

                alert("❌ Producto no encontrado");

                return;
            }

            document
            .getElementById("beep")
            .play();

            agregarProducto(data);

            codigoInput.focus();

        })

        .catch(error => {

            console.log(error);

            alert("Error al buscar producto");

        });

    }

});

/* =========================
AGREGAR PRODUCTO
========================= */

function agregarProducto(producto){

    let existente =
        carrito.find(p => p.id == producto.id);

    if(existente){

        existente.cantidad++;

    }else{

        producto.cantidad = 1;

        carrito.push(producto);
    }

    render();

}

/* =========================
RENDER TABLA
========================= */

function render(){

    let tbody =
        document.querySelector("#tabla tbody");

    tbody.innerHTML = "";

    let total = 0;

    carrito.forEach((p,index) => {

        let subtotal =
            p.precio_venta * p.cantidad;

        total += subtotal;

        tbody.innerHTML += `
        <tr>

            <td>${p.nombre}</td>

            <td>
                $${parseFloat(
                    p.precio_venta
                ).toFixed(2)}
            </td>

            <td>

                <button onclick="restar(${index})">
                ➖
                </button>

                ${p.cantidad}

                <button onclick="sumar(${index})">
                ➕
                </button>

            </td>

            <td>
                $${subtotal.toFixed(2)}
            </td>

        </tr>
        `;
    });

    totalGeneral = total;

    document.getElementById("total")
    .innerHTML =
        "$" + total.toFixed(2);

    calcularCambio();

}

/* =========================
SUMAR
========================= */

function sumar(index){

    carrito[index].cantidad++;

    render();

}

/* =========================
RESTAR
========================= */

function restar(index){

    carrito[index].cantidad--;

    if(carrito[index].cantidad <= 0){

        carrito.splice(index,1);

    }

    render();

}

/* =========================
CAMBIO
========================= */

document.getElementById("recibido")
.addEventListener("input", calcularCambio);

function calcularCambio(){

    let recibido =
        parseFloat(
            document.getElementById("recibido").value
        ) || 0;

    let cambio =
        recibido - totalGeneral;

    if(cambio < 0){

        document.getElementById("cambio")
        .innerHTML =
        "Falta $" +
        Math.abs(cambio).toFixed(2);

        document.getElementById("cambio")
        .style.color = "red";

    }else{

        document.getElementById("cambio")
        .innerHTML =
        "$" + cambio.toFixed(2);

        document.getElementById("cambio")
        .style.color = "#28c76f";

    }

}

/* =========================
PAGAR
========================= */

function pagar(){

    if(carrito.length <= 0){

        alert("❌ No hay productos");

        return;

    }

    let recibido =
        parseFloat(
            document.getElementById("recibido").value
        ) || 0;

    if(recibido < totalGeneral){

        alert("❌ Dinero insuficiente");

        return;

    }

    fetch("procesar_venta.php", {

        method:"POST",

        headers:{
            "Content-Type":"application/json"
        },

        body: JSON.stringify({

            productos: carrito,
            recibido: recibido

        })

    })

    .then(res => res.json())

    .then(resp => {

        if(resp.ok){

            alert("✅ Venta realizada");

            /* ABRIR TICKET */

            window.open(
                "ticket.php?id=" + resp.venta_id,
                "_blank"
            );

            carrito = [];

            render();

            document
            .getElementById("recibido")
            .value = "";

            document
            .getElementById("cambio")
            .innerHTML = "$0.00";

            codigoInput.focus();

        }else{

            alert("Error al guardar venta");

        }

    })

    .catch(error => {

        console.log(error);

        alert("Error servidor");

    });

}

/* =========================
CANCELAR
========================= */

function cancelar(){

    carrito = [];

    render();

    document
    .getElementById("recibido")
    .value = "";

    document
    .getElementById("cambio")
    .innerHTML = "$0.00";

}

/* =========================
BOTON TICKETS
========================= */

document.querySelector(".ticket")
.addEventListener("click", () => {

    window.location.href =
    "../ventas/historial_tickets.php";

});

/* =========================
BOTON BUSCAR
========================= */

document.querySelector(".buscar")
.addEventListener("click", () => {

    let codigo =
        prompt("Buscar código:");

    if(codigo){

        document
        .getElementById("codigo")
        .value = codigo;

        let evento =
            new KeyboardEvent("keypress", {
                key:"Enter"
            });

        codigoInput.dispatchEvent(evento);

    }

});

/* =========================
BOTONES RAPIDOS
========================= */

let quickBtns =
    document.querySelectorAll(".quick-btn");

quickBtns[0].onclick = () => {
    window.location.href =
    "../productos/";
};

quickBtns[1].onclick = () => {
    window.location.href =
    "../compras/";
};

quickBtns[2].onclick = () => {
    window.location.href =
    "../reportes/";
};

quickBtns[3].onclick = () => {
    window.location.href =
    "../caja/";
};

</script>

<audio id="beep" preload="auto">
    <source src="../assets/beep.mp3" type="audio/mpeg">
</audio>

</body>
</html>