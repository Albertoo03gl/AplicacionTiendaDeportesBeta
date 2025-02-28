<?php
$xml = new DOMDocument();
$xml->load('conexion.xml');

if (!$xml->schemaValidate('conexion.xsd')) {
    die("El XML no es válido según el esquema XSD.");
}

$xpath = new DOMXPath($xml);

$host = $xpath->evaluate('string(//host)');
$baseDatos = $xpath->evaluate('string(//baseDatos)');
$user = $xpath->evaluate('string(//user)');
$password = $xpath->evaluate('string(//password)');

try {
    $db = new PDO("mysql:host=$host;dbname=$baseDatos", $user, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Conexión exitosa!";
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
?>
