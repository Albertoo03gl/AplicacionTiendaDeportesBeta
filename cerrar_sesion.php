<?php
session_start();

//variables de sesión
session_unset();

// Destruir la sesión
session_destroy();

header("Location: form_inicio_sesion.html");
exit();
?>
