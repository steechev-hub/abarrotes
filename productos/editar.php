<?php
session_start();
include("../config/db.php");

if(!isset($_SESSION['usuario'])){
    header("Location: ../auth/login.php");
    exit();
}

if(!isset($_GET['id'])){
    header("Location: index.php");
    exit();
}

$id = $_GET['id'];

$stmt = $conexion->prepare("
SELECT *
FROM productos
WHERE id = ?
");

$stmt->execute([$id]);

$producto = $stmt->fetch();

if(!$producto){
    header("Location: index.php");
    exit();
}

$categorias = $conexion->query("
SELECT *
FROM categorias
ORDER BY nombre ASC
")->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Editar producto</title>

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
    color:#0d3b66;
    font-weight:bold;
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
    border:none;
    padding:14px 20px;
    border-radius:12px;
    cursor:pointer;
    font-weight:bold;
    color:#0d3b66;
    width:100%;
    font-size:16px;
    transition:.2s;
}

.btn:hover{
    background:#3bb8db;
    transform:translateY(-2px);
}

/* GRID */

.grid-2{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:15px;
}

/* SUGERENCIAS */

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

</style>
</head>
<body>

<?php include("../includes/sidebar.php"); ?>

<div class="main">

<?php include("../includes/topbar.php"); ?>

<div class="form-container">

<h2 style="margin-bottom:25px; color:#0d3b66;">

✏️ Editar producto

</h2>

<form action="actualizar.php" method="POST">

    <input
    type="hidden"
    name="id"
    value="<?php echo $producto['id']; ?>">

    <!-- CODIGO -->

    <div class="form-group">

        <label>📦 Código de barras</label>

        <input
        type="text"
        name="codigo_barras"
        value="<?php echo $producto['codigo_barras']; ?>"
        required>

    </div>

    <!-- NOMBRE -->

    <div class="form-group">

        <label>🛒 Nombre del producto</label>

        <input
        type="text"
        name="nombre"
        value="<?php echo $producto['nombre']; ?>"
        required>

    </div>

    <!-- MEDIDA -->

<div class="grid-2">

    <div class="form-group">

        <label>📏 Contenido</label>

        <input
        type="number"
        step="0.01"
        name="contenido_medida"
        value="<?php echo $producto['contenido_medida']; ?>"
        placeholder="Ejemplo: 600">

    </div>

    <div class="form-group">

            <label>🧪 Unidad de medida</label>

            <select name="unidad_medida">

                <option value="">
                    Seleccionar
                </option>

                <option
                value="ml"
                <?php echo ($producto['unidad_medida'] == 'ml') ? 'selected' : ''; ?>>
                Mililitros (ml)
                </option>

                <option
                value="L"
                <?php echo ($producto['unidad_medida'] == 'L') ? 'selected' : ''; ?>>
                Litros (L)
                </option>

                <option
                value="g"
                <?php echo ($producto['unidad_medida'] == 'g') ? 'selected' : ''; ?>>
                Gramos (g)
                </option>

                <option
                value="kg"
                <?php echo ($producto['unidad_medida'] == 'kg') ? 'selected' : ''; ?>>
                Kilogramos (kg)
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
            value="<?php echo $producto['precio_compra']; ?>"
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
            value="<?php echo $producto['precio_venta']; ?>"
            required>

        </div>

    </div>

    <!-- SUGERENCIAS -->

    <div class="sugerencias">

        <h3>📈 Precios sugeridos</h3>

        <div class="grid-precios">

            <!-- 25 -->

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

            <!-- 30 -->

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

        <label>📦 Stock</label>

        <input
        type="number"
        name="stock"
        value="<?php echo $producto['stock']; ?>"
        required>

    </div>

    <!-- CATEGORIA -->

    <div class="form-group">

        <label>🗂️ Categoría</label>

        <select name="categoria_id" required>

            <?php foreach($categorias as $c): ?>

                <option
                value="<?php echo $c['id']; ?>"
                <?php echo ($producto['categoria_id'] == $c['id']) ? 'selected' : ''; ?>>

                    <?php echo $c['nombre']; ?>

                </option>

            <?php endforeach; ?>

        </select>

    </div>

    <!-- BOTON -->

    <button class="btn">

        💾 Actualizar producto

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

/* CALCULAR AL ABRIR */

calcularPrecios();

</script>

</body>
</html>