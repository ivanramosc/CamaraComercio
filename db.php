<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Datos de conexión a MySQL en Azure
$host = 'ivanbasededatos.mysql.database.azure.com';
$dbname = 'foliodb';
$username = 'Administrador';
$password = '2628Admin';
$port = 3306;

// Ruta del certificado SSL
$ssl_cert = __DIR__ . '/DigiCertGlobalRootCA.crt.pem';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;port=$port;charset=utf8", $username, $password, [
        PDO::MYSQL_ATTR_SSL_CA => $ssl_cert,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    echo "✅ Conexión exitosa";

    // Puedes colocar más lógica aquí
    // Por ejemplo, consultar una tabla de prueba:
    // $stmt = $pdo->query("SELECT * FROM tu_tabla LIMIT 1");
    // $row = $stmt->fetch();
    // var_dump($row);

} catch (PDOException $e) {
    die("❌ Error de conexión: " . $e->getMessage());
}
?>
