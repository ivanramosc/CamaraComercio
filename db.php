<?php
$host = 'ivanbasededatos.mysql.database.azure.com';
$dbname = 'foliodb';
$username = 'Administrador';
$password = '2628Admin';
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
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>
