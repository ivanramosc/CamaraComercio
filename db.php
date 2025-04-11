<?php
$host = 'ivanbasededatos.mysql.database.azure.com'; // Hostname del servidor MySQL en Azure
$dbname = 'foliodb';                                // Nombre de tu base de datos
$username = 'Administrador@ivanbasededatos';        // Usuario con @nombreServidor
$password = '2628Admin';                   // Tu contraseña real
$port = 3306;

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Mostrar errores
    echo "✅ ¡Conexión exitosa!";
} catch (PDOException $e) {
    die("❌ Error de conexión: " . $e->getMessage());
}
?>
