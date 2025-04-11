<?php
$host = 'ivanbasededatos.mysql.database.azure.com';
$dbname = 'foliodb';
$username = 'Administrador@ivanbasededatos';
$password = 'tu-contraseña-aquí';
$port = 3306;

// Ruta al certificado SSL que descargaste
$ssl_cert = __DIR__ . '/DigiCertGlobalRootCA.crt.pem';

$options = [
    PDO::MYSQL_ATTR_SSL_CA => $ssl_cert,
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
];

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname", $username, $password, $options);
    echo "✅ ¡Conexión segura exitosa!";
} catch (PDOException $e) {
    die("❌ Error de conexión: " . $e->getMessage());
}
?>
