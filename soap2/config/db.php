<?php
// config/db.php
function getConnection($database = 'gestion_usuarios') {
    $dsn = "mysql:host=127.0.0.1;port=3306;dbname=$database;charset=utf8";
    $username = 'root';
    $password = '';

    try {
        $pdo = new PDO($dsn, $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        die('Error de conexión: ' . $e->getMessage());
    }
}
?>
