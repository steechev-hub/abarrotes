<?php
session_start();
include("../config/db.php");

if(!isset($_SESSION['usuario'])){
    header("Location: ../auth/login.php");
    exit();
}

$categorias = $conexion->query("
SELECT * FROM categorias
ORDER BY nombre ASC
")->fetchAll();

$proveedores = $conexion->query("
SELECT *
FROM proveedores
WHERE activo = 1
ORDER BY nombre_empresa ASC
")->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Nuevo producto</title>

<link rel="stylesheet" href="../assets/css/style.css">

<style>

.form-container{
    background:white;
    padding:30px;
    border-radius:20px;
    max-width:750px;
    box-shadow:0 5px 15px rgba(0,0,0,0.05);
}

.form-group{
    margin-bottom:18px;
}

label{
    display:block;
    margin-bottom:8px;
    font-weight:bold;
    color:#0d3b66;
}

input,
select{
    width:100%;
    padding:14px;
    border:1px solid #ddd;
    border-radius:12px;
    outline:none;
    transition:.2s;
    font-size:15px;
}

input:focus,
select:focus{
    border-color:#55ccf0;
    box-shadow:0 0 10px rgba(85,204,240,0.2);
}

.btn{
    background:#55ccf0;
    color:#0d3b66;
    border:none;
    padding:14px 20px;
    border-radius:12px;
    cursor:pointer;
    font-weight:bold;
    width:100%;
    font-size:16px;
    transition:.2s;
}

.btn:hover{
    transform:translateY(-2px);
}

/* PRECIOS SUGERIDOS */

.sugerencias{
    background:#f4f9ff;
    border:1px solid #dbeafe;
    padding:20px;
    border-radius:15px;
    margin-bottom:20px;
}

.sugerencias h3{
    color:#0d3b66;
    margin-bottom:15px;
}

.grid-precios{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:15px;
}

.card-precio{
    background:white;
    padding:20px;
    border-radius:15px;
    text-align:center;
    box-shadow:0 3px 10px rgba(0,0,0,0.05);
}

.card-precio p{
    color:#666;
    margin-bottom:10px;
}

.card-precio h2{
    margin-bottom:15px;
}

.precio25{
    color:#28c76f;
}

.precio30{
    color:#0d6efd;
}

.btn-precio{
    border:none;
    padding:10px 15px;
    border-radius:10px;
    color:white;
    cursor:pointer;
    font-weight:bold;
}

.btn25{
    background:#28c76f;
}

.btn30{
    background:#0d6efd;
}

.grid-2{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:15px;
}
/* SCANNER */

.barcode-box{
    display:flex;
    gap:10px;
}

.barcode-box input{
    flex:1;
}

.btn-scan{
    background:#28c76f;
    color:white;
    border:none;
    padding:0 18px;
    border-radius:12px;
    cursor:pointer;
    font-weight:bold;
    min-width:130px;
}

#scanner-container{
    display:none;
    margin-top:20px;
    background:#000;
    border-radius:20px;
    overflow:hidden;
    position:relative;
}

#scanner-video{
    width:100%;
    min-height:320px;
}

#scanner-video{
    width:100%;
    min-height:320px;
    position:relative;
}

/* LINEA DE ESCANEO */

#scanner-video::after{
    content:"";
    position:absolute;
    left:10%;
    width:80%;
    height:3px;
    background:red;
    animation:scanline 2s infinite;
    z-index:999;
}

@keyframes scanline{

    0%{
        top:20%;
    }

    50%{
        top:80%;
    }

    100%{
        top:20%;
    }

}

.cerrar-scan{
    position:absolute;
    top:10px;
    right:10px;
    background:#ea5455;
    color:white;
    border:none;
    padding:10px 15px;
    border-radius:10px;
    cursor:pointer;
}

</style>
</head>
<body>

<?php include("../includes/sidebar.php"); ?>

<div class="main">

<?php include("../includes/topbar.php"); ?>

<div class="form-container">

<h2 style="margin-bottom:25px; color:#0d3b66;">
➕ Nuevo producto
</h2>

<form action="guardar.php" method="POST">

    <!-- CODIGO -->

    <div class="form-group">

        <label>📦 Código de barras</label>

        <div class="barcode-box">

            <input
            type="text"
            id="codigo_barras"
            name="codigo_barras"
            required>

            <button
            type="button"
            class="btn-scan"
            onclick="iniciarScanner()">

            📷 Escanear

            </button>

        </div>

    </div>

    <!-- CAMARA -->

    <div id="scanner-container">

        <div id="scanner-video"></div>

        <button
        type="button"
        class="cerrar-scan"
        onclick="detenerScanner()">

        ✖ Cerrar cámara

        </button>

    </div>

    <!-- NOMBRE -->

    <div class="form-group">

        <label>🛒 Nombre del producto</label>

        <input
        type="text"
        name="nombre"
        required>

    </div>

    <div class="grid-2">

        <div class="form-group">

            <label>🚚 Proveedor</label>

            <select
            name="proveedor_id"
            id="proveedor_id"
            onchange="cargarMarca()"
            required>

                <option value="">
                    Seleccionar proveedor
                </option>

                <?php foreach($proveedores as $p): ?>

                    <option
                    value="<?php echo $p['id']; ?>"
                    data-marca="<?php echo $p['marca']; ?>">

                        <?php echo $p['nombre_empresa']; ?>

                    </option>

                <?php endforeach; ?>

            </select>

        </div>

        <div class="form-group">

            <label>🏷️ Marca</label>

            <select
            name="marca"
            id="marca"
            required>

                <option value="">
                    Seleccionar marca
                </option>

            </select>

        </div>

    </div>

    <!-- MEDIDA -->

    <div class="grid-2">

        <div class="form-group">

            <label>📏 Contenido</label>

            <input
            type="number"
            step="0.01"
            name="contenido_medida"
            placeholder="Ejemplo: 600">

        </div>

        <div class="form-group">

            <label>🧪 Unidad de medida</label>

            <select name="unidad_medida">

                <option value="">
                    Seleccionar
                </option>

                <option value="g">
                    Gramos (g)
                </option>

                <option value="kg">
                    Kilogramos (kg)
                </option>

                <option value="ml">
                    Mililitros (ml)
                </option>

                <option value="L">
                    Litros (L)
                </option>

                <option value="mg">
                    Miligramos (mg)
                </option>

            </select>

        </div>

    </div>

    <!-- PRECIOS -->

    <div class="grid-2">

        <div class="form-group">

            <label>💰 Precio compra</label>

            <input
            type="number"
            step="0.01"
            id="precio_compra"
            name="precio_compra"
            oninput="calcularPrecios()"
            required>

        </div>

        <div class="form-group">

            <label>🏷️ Precio venta público</label>

            <input
            type="number"
            step="0.01"
            id="precio_venta"
            name="precio_venta"
            required>

        </div>

    </div>

    <!-- SUGERENCIAS -->

    <div class="sugerencias">

        <h3>📈 Precios sugeridos</h3>

        <div class="grid-precios">

            <!-- 25% -->

            <div class="card-precio">

                <p>Utilidad 25%</p>

                <h2
                id="precio25"
                class="precio25">
                $0.00
                </h2>

                <button
                type="button"
                class="btn-precio btn25"
                onclick="usar25()">
                Usar precio
                </button>

            </div>

            <!-- 30% -->

            <div class="card-precio">

                <p>Utilidad 30%</p>

                <h2
                id="precio30"
                class="precio30">
                $0.00
                </h2>

                <button
                type="button"
                class="btn-precio btn30"
                onclick="usar30()">
                Usar precio
                </button>

            </div>

        </div>

    </div>

    <!-- STOCK -->

    <div class="form-group">

        <label>📦 Stock inicial</label>

        <input
        type="number"
        name="stock"
        required>

    </div>

    <!-- CATEGORIA -->

    <div class="form-group">

        <label>🗂️ Categoría</label>

        <select name="categoria_id" required>

            <option value="">
                Seleccionar categoría
            </option>

            <?php foreach($categorias as $c): ?>

                <option value="<?php echo $c['id']; ?>">

                    <?php echo $c['nombre']; ?>

                </option>

            <?php endforeach; ?>

        </select>

    </div>

    <!-- BOTON -->

    <button class="btn">

        💾 Guardar producto

    </button>

</form>

</div>

</div>

<!-- SCRIPT -->

<script>

let precio25Global = 0;
let precio30Global = 0;

function calcularPrecios(){

    let compra =
        parseFloat(
            document.getElementById("precio_compra").value
        ) || 0;

    /* 25% */

    precio25Global =
        compra + (compra * 0.25);

    /* 30% */

    precio30Global =
        compra + (compra * 0.30);

    document.getElementById("precio25")
    .innerHTML =
        "$" + precio25Global.toFixed(2);

    document.getElementById("precio30")
    .innerHTML =
        "$" + precio30Global.toFixed(2);

}

/* USAR 25 */

function usar25(){

    document.getElementById("precio_venta")
    .value = precio25Global.toFixed(2);

}

/* USAR 30 */

function usar30(){

    document.getElementById("precio_venta")
    .value = precio30Global.toFixed(2);

}

</script>

<script>

let scanner = null;
let scannerActivo = false;

/* =========================
INICIAR SCANNER
========================= */

function iniciarScanner(){

    if(scannerActivo){

        detenerScanner();
        return;

    }

    document.getElementById("scanner-container")
    .style.display = "block";

    scanner =
        new Html5Qrcode("scanner-video");

    Html5Qrcode.getCameras()

    .then(cameras => {

        if(cameras && cameras.length){

            let camaraTrasera =
                cameras[0].id;

            /* BUSCAR CAMARA TRASERA */

            cameras.forEach(camera => {

                let nombre =
                    camera.label.toLowerCase();

                if(
                    nombre.includes("back") ||
                    nombre.includes("rear") ||
                    nombre.includes("environment") ||
                    nombre.includes("trasera")
                ){

                    camaraTrasera = camera.id;

                }

            });

            scanner.start(

                camaraTrasera,

                {

                    fps:15,

                    qrbox:{
                        width:300,
                        height:150
                    },

                    aspectRatio:1.777778,

                    formatsToSupport:[

                        Html5QrcodeSupportedFormats.EAN_13,
                        Html5QrcodeSupportedFormats.EAN_8,
                        Html5QrcodeSupportedFormats.UPC_A,
                        Html5QrcodeSupportedFormats.UPC_E,
                        Html5QrcodeSupportedFormats.CODE_128,
                        Html5QrcodeSupportedFormats.CODE_39

                    ]

                },

                function(decodedText){

                    /* PONER CODIGO */

                    document.getElementById("codigo_barras")
                    .value = decodedText;

                    /* VIBRACION */

                    if(navigator.vibrate){

                        navigator.vibrate(200);

                    }

                    /* CERRAR */

                    detenerScanner();

                },

                function(error){

                    /* IGNORAR */

                }

            );

            scannerActivo = true;

        }

    })

    .catch(err => {

        console.log(err);

        alert("No se pudo abrir la cámara");

    });

}

/* =========================
DETENER SCANNER
========================= */

function detenerScanner(){

    if(scanner){

        scanner.stop()

        .then(() => {

            scanner.clear();

            document.getElementById("scanner-container")
            .style.display = "none";

            scannerActivo = false;

        })

        .catch(err => {

            console.log(err);

        });

    }

}

</script>

<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>

<script>

function cargarMarca(){

    let proveedor =
        document.getElementById("proveedor_id");

    let opcion =
        proveedor.options[proveedor.selectedIndex];

    let marcas =
        opcion.getAttribute("data-marca");

    let selectMarca =
        document.getElementById("marca");

    /* LIMPIAR */

    selectMarca.innerHTML =
        '<option value="">Seleccionar marca</option>';

    if(marcas){

        let listaMarcas =
            marcas.split(",");

        listaMarcas.forEach(function(m){

            let marca = m.trim();

            let option =
                document.createElement("option");

            option.value = marca;

            option.text = marca;

            selectMarca.appendChild(option);

        });

    }

}

</script>

</body>
</html>