<?php
require_once 'config/config.php';

// Verificar si el usuario está logueado
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit();
}

try {
    $conexion = conectarDB();
    $stmt = $conexion->prepare("SELECT email, fecha_registro, ultimo_acceso FROM usuarios WHERE id = ?");
    $stmt->execute([$_SESSION['usuario_id']]);
    $usuario = $stmt->fetch();
    
    if (!$usuario) {
        // Usuario no encontrado (puede haber sido eliminado)
        session_destroy();
        header('Location: login.php');
        exit();
    }
} catch (PDOException $e) {
    error_log('Error al cargar perfil: ' . $e->getMessage());
    die('Error al cargar el perfil');
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil</title>
</head>
<body>
    <h1>Bienvenido</h1>
    <p>Email: <?php echo htmlspecialchars($usuario['email']); ?></p>
    <p>Fecha de registro: <?php echo htmlspecialchars($usuario['fecha_registro']); ?></p>
    <p>Último acceso: <?php echo htmlspecialchars($usuario['ultimo_acceso'] ?? 'Nunca'); ?></p>
    
    <a href="logout.php">Cerrar sesión</a>
</body>
</html>