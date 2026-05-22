<?php
session_start();

/* VALIDAR LOGIN */

if(!isset($_SESSION['usuario'])){

    header("Location: ../auth/login.php");
    exit();
}

/* FUNCION PERMISOS */

function verificarPermiso($rolesPermitidos){

    if(
        !in_array(
            $_SESSION['rol'],
            $rolesPermitidos
        )
    ){

        echo "
        <script>
            alert('❌ No tienes permisos');
            window.location='../index.php';
        </script>
        ";

        exit();
    }
}
?>