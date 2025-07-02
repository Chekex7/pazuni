<?php
// Iniciar sesión y verificar autenticación
session_start();

// Redirigir si no está logueado
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// Incluir conexión a la base de datos si es necesaria
require_once 'includes/db.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Control</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }
        .user-info {
            background-color: #f5f5f5;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .actions {
            margin-top: 20px;
        }
        .logout-btn {
            background-color: #f44336;
            color: white;
            padding: 8px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
        .logout-btn:hover {
            background-color: #d32f2f;
        }
    </style>
</head>
<body>
    <h1>Bienvenido al Panel de Control</h1>
    
    <div class="user-info">
        <h2>Información de tu cuenta</h2>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($_SESSION['usuario_email']); ?></p>
        <p><strong>ID de usuario:</strong> <?php echo htmlspecialchars($_SESSION['usuario_id']); ?></p>
    </div>
    
    <div class="actions">
        <a href="logout.php" class="logout-btn">Cerrar Sesión</a>
    </div>
</body>
</html>