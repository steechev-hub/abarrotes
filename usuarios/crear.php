<?php
session_start();

if(!isset($_SESSION['usuario'])){
    header("Location: ../auth/login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Nuevo usuario</title>

<link rel="stylesheet" href="../assets/css/style.css">

<style>

.form-container{
    background:white;
    padding:30px;
    border-radius:20px;
    max-width:700px;
    box-shadow:0 5px 15px rgba(0,0,0,0.05);
}

.form-container h2{
    margin-bottom:25px;
    color:#0d3b66;
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
select{
    width:100%;
    padding:14px;
    border-radius:12px;
    border:1px solid #ddd;
    outline:none;
    font-size:15px;
    transition:.2s;
}

input:focus,
select:focus{
    border-color:#55ccf0;
    box-shadow:0 0 10px rgba(85,204,240,0.2);
}

.grid-2{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:15px;
}

.btn{
    background:#55ccf0;
    color:#0d3b66;
    border:none;
    padding:15px;
    border-radius:12px;
    cursor:pointer;
    font-weight:bold;
    width:100%;
    font-size:16px;
    transition:.2s;
}

.btn:hover{
    background:#39b7dc;
    transform:translateY(-2px);
}

.info-box{
    background:#eef7ff;
    border-left:5px solid #55ccf0;
    padding:15px;
    border-radius:12px;
    margin-bottom:25px;
    color:#0d3b66;
}

.password-box{
    position:relative;
}

.toggle-pass{
    position:absolute;
    right:15px;
    top:50%;
    transform:translateY(-50%);
    cursor:pointer;
    font-size:18px;
}

</style>
</head>

<body>

<?php include("../includes/sidebar.php"); ?>

<div class="main">

<?php include("../includes/topbar.php"); ?>

<div class="form-container">

<h2>👤 Nuevo usuario</h2>

<div class="info-box">

✅ Desde este módulo puedes crear:

<br><br>

• Administradores  
• Cajeros  

</div>

<form action="guardar.php" method="POST">

    <!-- NOMBRE -->

    <div class="form-group">

        <label>👤 Nombre completo</label>

        <input
        type="text"
        name="nombre"
        required>

    </div>

    <!-- GRID -->

    <div class="grid-2">

        <!-- USUARIO -->

        <div class="form-group">

            <label>🧑 Usuario</label>

            <input
            type="text"
            name="usuario"
            autocomplete="off"
            required>

        </div>

        <!-- ROL -->

        <div class="form-group">

            <label>🛡️ Rol</label>

            <select name="rol" required>

                <option value="">
                    Seleccionar
                </option>

                <option value="admin">
                    Administrador
                </option>

                <option value="cajero">
                    Cajero
                </option>

            </select>

        </div>

    </div>

    <!-- PASSWORD -->

    <div class="form-group">

        <label>🔒 Contraseña</label>

        <div class="password-box">

            <input
            type="password"
            name="password"
            id="password"
            required>

            <span
            class="toggle-pass"
            onclick="togglePassword()">

            👁️

            </span>

        </div>

    </div>

    <!-- BOTON -->

    <button class="btn">

        💾 Guardar usuario

    </button>

</form>

</div>

</div>

<script>

function togglePassword(){

    let input =
        document.getElementById("password");

    if(input.type === "password"){

        input.type = "text";

    }else{

        input.type = "password";

    }

}

</script>

</body>
</html>