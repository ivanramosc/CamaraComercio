<?php
$host = 'localhost';  // O la IP de tu servidor MySQL
$dbname = 'foliodb';  // El nombre de tu base de datos
$username = 'root';   // Tu usuario de MySQL
$password = '';       // Tu contraseña de MySQL

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Para mostrar errores de SQL
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}


?>
