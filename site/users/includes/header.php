<?php
// Incluye la conexión a la base de datos
require_once 'config/config.php';

// Inicia la sesión (necesario para usar $_SESSION)
session_start();

// Verifica si el usuario está logueado
if (!isset($_SESSION['user_id'])) {
    // Redirige al login si no está autenticado
    header('Location: login.php');
    exit();
}

// Obtiene la conexión a la base de datos
$db = getDBConnection();

// Consulta para obtener los datos del usuario
try {
    $stmt = $db->prepare("SELECT user FROM logins WHERE id = :id");
    $stmt->bindParam(':id', $_SESSION['user_id'], PDO::PARAM_INT);
    $stmt->execute();
    $user = $stmt->fetch();
} catch (PDOException $e) {
    error_log($e->getMessage());
    // En caso de error, muestra un valor por defecto
    $user = ['user' => 'Usuario'];
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Responsive</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="wrapper">
        <header class="top-header">
            <div class="header-left">
                <button class="menu-toggle" id="menuToggle">
                    <i class="fas fa-bars"></i>
                </button>
                <h2>Panel de Control</h2>
            </div>
            <div class="header-right">
                <div class="user-profile">
                    <span class="username">Bienvenido: <?php echo htmlspecialchars($user['user'] ?? 'Usuario', ENT_QUOTES, 'UTF-8'); ?></span>
                    <i class="fas fa-user-circle fa-2x"></i>
                    <!-- <img src="https://i.pravatar.cc/40" alt="User" class="user-avatar"> -->

                    
                </div>
            </div>
        </header>