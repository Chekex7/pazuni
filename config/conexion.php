<?php
$host = 'db.be-mons1.bengt.wasmernet.com';    // Dirección del servidor MySQL
$port = 3306;          // Puerto (por defecto es 3306)
$dbname = 'paz777'; // Nombre de la base de datos
$username = 'root482be0b57d23800071619955035b'; // Nombre de usuario
$password = '0686482b-e0b5-7ed6-8000-76e50aa2c93b'; // Contraseña

try {
    $conn = new PDO("mysql:host=$host;port=$port;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Conexión exitosa";
} catch(PDOException $e) {
    echo "Error de conexión: " . $e->getMessage();
}


/*
$host = 'localhost';    // Dirección del servidor MySQL
$port = 3306;          // Puerto (por defecto es 3306)
$dbname = 'paz'; // Nombre de la base de datos
$username = 'root'; // Nombre de usuario
$password = ''; // Contraseña

try {
    $conn = new PDO("mysql:host=$host;port=$port;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Conexión exitosa";
} catch(PDOException $e) {
    echo "Error de conexión: " . $e->getMessage();
}*/

?>
