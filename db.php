<?php
$host = 'ivanbasededatos.mysql.database.azure.com';  // O la IP de tu servidor MySQL
$dbname = 'foliodb';  // El nombre de tu base de datos
$username = 'Administrador';   // Tu usuario de MySQL
$password = '2628Admin';       // Tu contraseña de MySQL
$port = 3306;
// Ruta al certificado SSL que descargaste
$ssl_cert = __DIR__ . '/DigiCertGlobalRootCA.crt.pem';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Para mostrar errores de SQL
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}


?>


