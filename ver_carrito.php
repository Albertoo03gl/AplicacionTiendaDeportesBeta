<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito de Compras</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
    <div class="contenedorWebPrincipal">
        <h1>Tu Carrito</h1>
        <?php
        if (isset($_SESSION['carrito']) && count($_SESSION['carrito']) > 0) {
            echo "<table border='1'>
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Precio (€)</th>
                            <th>Cantidad</th>
                            <th>Subtotal (€)</th>
                            <th>Eliminar</th>
                        </tr>
                    </thead>
                    <tbody>";
            $total = 0;
            foreach ($_SESSION['carrito'] as $id_producto => $producto) {
                $subtotal = $producto['precio'] * $producto['cantidad'];
                $total += $subtotal;
                echo "<tr>
                        <td>" . htmlspecialchars($producto['nombre']) . "</td>
                        <td>" . htmlspecialchars($producto['precio']) . "</td>
                        <td>" . htmlspecialchars($producto['cantidad']) . "</td>
                        <td>" . number_format($subtotal, 2) . "</td>
                        <td>
                            <form action='eliminar_del_carrito.php' method='POST'>
                                <input type='hidden' name='id_producto' value='" . $id_producto . "'>
                                <button type='submit'>Eliminar</button>
                            </form>
                        </td>
                      </tr>";
            }
            echo "</tbody>
                  </table>
                  <h3>Total: €" . number_format($total, 2) . "</h3>";
        } else {
            echo "<p>Tu carrito está vacío.</p>";
        }
        ?>
        
     
        <br>
        <form action="hacer_pedido.php" method="POST">
            <button type="submit" class="boton">Hacer Pedido</button>
        </form>
        
        <br><br>
        <a href="tienda_productos.php" class="boton">Seguir Comprando</a>
        <br><br>
        <a href="sesion_inicial.php" class="boton">Volver a la Bienvenida</a>
    </div>
</body>
</html>
