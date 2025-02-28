<?php
session_start();

if (isset($_SESSION['usuario']) && isset($_COOKIE[$_SESSION['usuario']])) {
    setcookie($_SESSION['usuario'], "", time() - 3600, "/");
}

//Destruir todas las variables de sesión
session_unset();

//Destruir la sesión
session_destroy();

header("Location: form_inicio_sesion.html");
exit();
?>
