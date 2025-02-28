<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'c:/xampp/composer/vendor/phpmailer/phpmailer/src/Exception.php';
require 'c:/xampp/composer/vendor/phpmailer/phpmailer/src/PHPMailer.php';
require 'c:/xampp/composer/vendor/phpmailer/phpmailer/src/SMTP.php';
require 'c:/xampp/composer/vendor/autoload.php';

session_start();

if (isset($_SESSION['carrito']) && count($_SESSION['carrito']) > 0) {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "MaterialDeporte";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Error de conexión: " . $conn->connect_error);
    }

    $usuario = $_SESSION["usuario"];

    $sql = "
        SELECT id_cliente AS id_usuario, correo 
        FROM CLIENTES 
        WHERE correo = ?";  // Usamos 'correo' como identificador único en CLIENTES
    
    $consulta = $conn->prepare($sql);
    $consulta->bind_param("s", $usuario);
    $consulta->execute();
    $resultado = $consulta->get_result();
    
    if ($resultado->num_rows  > 0) {
        $fila = $resultado->fetch_assoc();
        $id_usuario = $fila['id_usuario'];  // Ahora apunta a id_cliente
        $email_usuario = $fila['correo'];  // Obtenemos el correo del cliente
    } else {
        echo "Cliente no encontrado.";
        exit();
    }

    // Calcular total del pedido
    $total = 0;
    foreach ($_SESSION['carrito'] as $producto) {
        $total += $producto['precio'] * $producto['cantidad'];
    }
    
    $sql_pedido = "INSERT INTO Pedidos (id_cliente, total) VALUES (?, ?)";
    $consulta_pedido = $conn->prepare($sql_pedido);
    $consulta_pedido->bind_param("id", $id_usuario, $total);
    $consulta_pedido->execute();

    $id_pedido = $consulta_pedido->insert_id;

    foreach ($_SESSION['carrito'] as $id_producto => $producto) {
        $precio = $producto['precio'];
        $cantidad = $producto['cantidad'];

        $sql_detalle = "INSERT INTO DetallePedidos (id_pedido, id_producto, cantidad, precio_unitario, subtotal) 
                        VALUES (?, ?, ?, ?, ?)";
        $subtotal = $precio * $cantidad;
        $consulta_detalle = $conn->prepare($sql_detalle);
        $consulta_detalle->bind_param("iiidd", $id_pedido, $id_producto, $cantidad, $precio, $subtotal);
        $consulta_detalle->execute();
    }

    //Vaciar el carrito de compras
    unset($_SESSION['carrito']);

    //Enviar el correo de confirmación
    enviarCorreoConfirmacion($email_usuario, $total, $id_pedido, $conn);

    $consulta->close();
    $consulta_pedido->close();
    $conn->close();

    header("Location: confirmacion_pedido.php");
    exit();
} else {
    header("Location: tienda_productos.php");
    exit();
}

function enviarCorreoConfirmacion($email_usuario, $total, $id_pedido, $conn) {
    $mail = new PHPMailer(true);

    try {
        //Configuración del SMTP
        $mail->isSMTP();
        $mail->SMTPDebug = 0; 
        $mail->SMTPAuth = true; 
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; 
        $mail->Host = "smtp.gmail.com"; 
        $mail->Port = 587; 
        $mail->Username = "dawiesventura@gmail.com"; 
        $mail->Password = "nnli oyoa game qmoc"; 

        // Remitente
        $mail->setFrom('dawiesventura@gmail.com', 'AlbertoSport'); 

        $sql_cliente = "
            SELECT correo, nombre_social, CIF, pais, direccion, codigo_postal
            FROM CLIENTES
            WHERE correo = ?";
        $consulta_cliente = $conn->prepare($sql_cliente);
        $consulta_cliente->bind_param("s", $email_usuario);
        $consulta_cliente->execute();
        $result_cliente = $consulta_cliente->get_result();

        if ($result_cliente->num_filas > 0) {
            $cliente = $result_cliente->fetch_assoc();
        } else {
            echo "Error: Cliente no encontrado.";
            return;
        }

        $sql_detalles = "
            SELECT p.nombre, dp.cantidad, dp.precio_unitario 
            FROM DetallePedidos dp
            INNER JOIN Producto p ON dp.id_producto = p.id_producto
            WHERE dp.id_pedido = ?";
        $consulta_detalles = $conn->prepare($sql_detalles);
        $consulta_detalles->bind_param("i", $id_pedido);
        $consulta_detalles->execute();
        $result_detalles = $consulta_detalles->get_result();

        $cuerpo = "
        <h1>Confirmación de Pedido</h1>
        <p>Gracias por tu compra. Tu pedido ha sido procesado con éxito.</p>
        <p><strong>Detalles del Pedido:</strong></p>
        <ul>
            <li><strong>Pedido ID:</strong> $id_pedido</li>
            <li><strong>Total:</strong> $$total</li>
        </ul>
        <p><strong>Datos del Cliente:</strong></p>
        <ul>
            <li><strong>Nombre Social:</strong> {$cliente['nombre_social']}</li>
            <li><strong>CIF:</strong> {$cliente['CIF']}</li>
            <li><strong>País:</strong> {$cliente['pais']}</li>
            <li><strong>Dirección:</strong> {$cliente['direccion']}</li>
            <li><strong>Código Postal:</strong> {$cliente['codigo_postal']}</li>
            <li><strong>Correo:</strong> {$cliente['correo']}</li>
        </ul>
        <p><strong>Productos solicitados:</strong></p>
        <ul>";

        while ($fila = $result_detalles->fetch_assoc()) {
            $cuerpo .= "<li>{$fila['cantidad']} x {$fila['nombre']} - \$" . number_format($fila['precio_unitario'], 2) . "</li>";
        }

        $cuerpo .= "</ul>";
        $cuerpo .= "<p>Estaremos procesando tu pedido y te avisaremos cuando se envíe.</p>";

        $mail->msgHTML($cuerpo);

        // Destinatario
        $mail->addAddress($email_usuario, $cliente['nombre_social']);

        //Envia el correo
        if ($mail->send()) {
            echo "Correo enviado con éxito a $email_usuario";
        } else {
            echo "Error al enviar el correo: " . $mail->ErrorInfo;
        }
    } catch (Exception $e) {
        echo "Excepción capturada: " . $e->getMessage();
    }
}


?>