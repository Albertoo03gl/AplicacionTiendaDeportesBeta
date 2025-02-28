<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $servername = "localhost";
    $username = "root";
    $password = ""; 
    $dbname = "MaterialDeporte";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }

    $correo = $_POST["correo"];
    $contrasena = $_POST["contrasena"];

    //Consultamos  correo y contraseña de la base de datos
    $sql = "SELECT contrasenia FROM CLIENTES WHERE correo = ?";
    $consulta = $conn->prepare($sql);
    $consulta->bind_param("s", $correo); 
    $consulta->execute();
    $result = $consulta->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $dbPassword = $row['contrasenia']; 

        if ($contrasena === $dbPassword) { 
            session_start();
            $_SESSION["usuario"] = $correo;

            header("Location: sesion_inicial.php");
            exit;
        } else {
            $error = true;
        }
    } else {
        $error = true;
    }

    $consulta->close();
    $conn->close();
}

if (isset($error)) {
    echo "<br><p>¡Error! Verifica tu correo o contraseña e inténtalo de nuevo</p>";
}
?>