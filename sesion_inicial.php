<?php
session_start();

if (!isset($_SESSION["usuario"])) {
    header("Location: form_inicio_sesion.php");
    exit();
}

$correo = $_SESSION["usuario"];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenida a la Tienda</title>
    <link rel="stylesheet" href="estilos.css?v=<?php echo time();?>">
</head>
<body>
    <div class="contenedorWebPrincipal">
        <h1>¡Bienvenido a Albert`Sports <?php echo htmlspecialchars($correo); ?>!</h1>
        <p>Estamos encantados de tenerte aquí. Explora nuestros productos y ofertas exclusivas.</p>
        <br>
        <a href="tienda_productos.php" class="boton">Ver Productos</a>
        <br><br>
        <a href="cerrar_sesion.php" class="boton">Cerrar Sesión</a>
    </div>
</body>
</html>
