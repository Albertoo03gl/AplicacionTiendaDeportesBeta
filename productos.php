<?php

session_start();


$servername = "localhost";
$username = "root";
$password = "";
$dbname = "MaterialDeporte";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Error al conectar con la base de datos: " . $conn->connect_error);
}


$categoria = isset($_GET['categoria']) ? $_GET['categoria'] : '';


if ($categoria) {
    $sql = "SELECT p.id_producto, p.nombre, p.tipo, p.precio, p.cantidadStock, m.nombre_marca 
            FROM Producto p 
            JOIN Marca m ON p.id_marca = m.id_marca 
            WHERE p.tipo = '$categoria'";
} else {
    
    $sql = "SELECT p.id_producto, p.nombre, p.tipo, p.precio, p.cantidadStock, m.nombre_marca 
            FROM Producto p 
            JOIN Marca m ON p.id_marca = m.id_marca";
}

$resultado = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos Disponibles</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
    <div class="contenedorWebPrincipal">
        <h1>Productos Disponibles</h1>

        
        <h2>Categorías</h2>
        <ul>
            <li><a href="productos.php?categoria=Bicicleta">Bicicletas</a></li>
            <li><a href="productos.php?categoria=Ropa">Ropa</a></li>
            <li><a href="productos.php?categoria=Calzado">Calzado</a></li>
            <li><a href="productos.php?categoria=Guantes">Guantes de Boxeo</a></li>
            <li><a href="productos.php">Todos los productos</a></li>
        </ul>

        
        <h2>Productos de la categoría: <?php echo htmlspecialchars($categoria) ? htmlspecialchars($categoria) : 'Todos'; ?></h2>

        <table border="1">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Tipo</th>
                    <th>Precio (€)</th>
                    <th>Stock</th>
                    <th>Marca</th>
                    <th>Añadir al Carrito</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($resultado->num_rows > 0) {
                    while ($row = $resultado->fetch_assoc()) {
                        echo "<tr>
                                <td>" . htmlspecialchars($row['id_producto']) . "</td>
                                <td>" . htmlspecialchars($row['nombre']) . "</td>
                                <td>" . htmlspecialchars($row['tipo']) . "</td>
                                <td>" . htmlspecialchars($row['precio']) . "</td>
                                <td>" . htmlspecialchars($row['cantidadStock']) . "</td>
                                <td>" . htmlspecialchars($row['nombre_marca']) . "</td>
                                <td>
                                    <form action='gestionar_carrito.php' method='POST'>
                                        <input type='hidden' name='id_producto' value='" . $row['id_producto'] . "'>
                                        <input type='hidden' name='nombre' value='" . $row['nombre'] . "'>
                                        <input type='hidden' name='precio' value='" . $row['precio'] . "'>
                                        <input type='number' name='cantidad' min='1' max='" . $row['cantidadStock'] . "' value='1'>
                                        <button type='submit'>Añadir</button>
                                    </form>
                                </td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='7'>No hay productos disponibles en esta categoría.</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <br>
        <a href="ver_carrito.php" class="boton">Ver Carrito</a>
        <br><br>
        <a href="sesion_inicial.php" class="boton">Volver a la Bienvenida</a>
    </div>
</body>
</html>

<?php

$conn->close();
?>
