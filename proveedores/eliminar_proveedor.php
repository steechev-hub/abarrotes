<?php
session_start();

include("../config/db.php");

/* =========================
   VALIDAR SESIÓN
========================= */

if(!isset($_SESSION['usuario'])){

    header("Location: ../auth/login.php");

    exit();
}

/* =========================
   VALIDAR ID
========================= */

if(!isset($_GET['id'])){

    header("Location: proveedores.php");

    exit();
}

$id = $_GET['id'];

/* =========================
   DESACTIVAR PROVEEDOR
========================= */

$sql = "

UPDATE proveedores

SET activo = 0

WHERE id = ?

";

$stmt = $conexion->prepare($sql);

$stmt->execute([$id]);

/* =========================
   REDIRECCIONAR
========================= */

header("Location: proveedores.php");

exit();
?>