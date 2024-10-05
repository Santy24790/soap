<?php
function getConnection() {
    $host = 'localhost';
    $db = 'gestion_usuarios'; // Asegúrate de que sea la base de datos correcta
    $user = 'root'; // Cambia si tu usuario es diferente
    $pass = ''; // Cambia si tu contraseña es diferente

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
        return null;
    }
}
?>
