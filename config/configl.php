<?php
//PAZUNIIIIIIIIIIIIIIIIIIIIIIIIIIIIII<-------PAZUNI-------->PAZUNIIIIIIIIIIIIIIIIIIIIIIIIIIIIIII
$host = 'localhost';
$dbname = 'paz';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
} catch (PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
?>