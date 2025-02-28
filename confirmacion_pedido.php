<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmación de Pedido</title>
    <link rel="stylesheet" href="estilos.css">
</head>
<body>
    <div class="contenedorWebPrincipal">
        <h1>¡Gracias por tu pedido!</h1>
        <p>Tu pedido ha sido realizado con éxito. Te notificaremos cuando esté listo para ser enviado.</p>
        <br>
        <a href="tienda_productos.php" class="boton">Seguir Comprando</a>
        <br><br>
        <a href="sesion_inicial.php" class="boton">Volver a la Bienvenida</a>
    </div>
</body>
</html>
