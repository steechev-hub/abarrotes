<?php
session_start();
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Login</title>

<style>
body {
    font-family: 'Segoe UI', sans-serif;
    background: #f4f8fb;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}

/* CONTENEDOR */
.login {
    background: white;
    padding: 40px 30px;
    border-radius: 20px;
    width: 320px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    text-align: center;
}

/* TITULO */
.login h2 {
    margin-bottom: 20px;
    color: #0d6efd;
}

/* INPUTS */
.input-group {
    position: relative;
    margin-bottom: 15px;
}

.input-group input {
    width: 100%;
    padding: 12px;
    border: 1px solid #ddd;
    border-radius: 10px;
    outline: none;
    transition: 0.3s;
}

.input-group input:focus {
    border-color: #55ccf0;
    box-shadow: 0 0 5px rgba(85,204,240,0.5);
}

/* BOTON */
button {
    width: 100%;
    padding: 12px;
    background: #55ccf0;
    border: none;
    border-radius: 10px;
    color: #0d3b66;
    font-size: 16px;
    cursor: pointer;
    transition: 0.3s;
    font-weight: bold;
}

button:hover {
    background: #3bb8db;
}

/* TEXTO EXTRA */
.extra {
    margin-top: 15px;
    font-size: 12px;
    color: #888;
}
</style>
</head>
<body>

<form class="login" action="procesar_login.php" method="POST">
    <h2>Iniciar sesión</h2>

    <div class="input-group">
        <input type="text" name="usuario" placeholder="Usuario" required>
    </div>

    <div class="input-group">
        <input type="password" name="password" placeholder="Contraseña" required>
    </div>

    <button>Entrar</button>

    <div class="extra">
        Surtete
    </div>
</form>

</body>
</html>