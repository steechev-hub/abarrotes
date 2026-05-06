<?php
include("../config/db.php");

$texto = $_GET['texto'] ?? '';

$sql = "
SELECT productos.*, categorias.nombre AS categoria
FROM productos
LEFT JOIN categorias
ON productos.categoria_id = categorias.id
WHERE productos.nombre LIKE ?
OR productos.codigo_barras LIKE ?
ORDER BY productos.id DESC
";

$stmt = $conexion->prepare($sql);

$buscar = "%$texto%";

$stmt->execute([$buscar, $buscar]);

$productos = $stmt->fetchAll();

foreach($productos as $p):
?>

<tr>

    <td>
        <?php echo $p['codigo_barras']; ?>
    </td>

    <td>
        <?php echo $p['nombre']; ?>
    </td>

    <td>
        <?php echo $p['categoria']; ?>
    </td>

    <td>
        $<?php echo $p['precio_compra']; ?>
    </td>

    <td>
        $<?php echo $p['precio_venta']; ?>
    </td>

    <td class="stock <?php echo ($p['stock'] <= 5) ? 'bajo' : 'normal'; ?>">
        <?php echo $p['stock']; ?>
    </td>

    <td class="acciones">

        <a href="editar.php?id=<?php echo $p['id']; ?>">
            ✏️
        </a>

        <a href="eliminar.php?id=<?php echo $p['id']; ?>"
           onclick="return confirm('¿Eliminar producto?')">
            🗑️
        </a>

    </td>

</tr>

<?php endforeach; ?>