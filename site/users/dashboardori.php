<?php
require_once __DIR__ . '/includess/auth_funtions.php';
requireLogin();

// Iniciar sesión si no está iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificar consistencia de la sesión
if ($_SESSION['ip_address'] !== $_SERVER['REMOTE_ADDR'] || 
    $_SESSION['user_agent'] !== $_SERVER['HTTP_USER_AGENT']) {
    session_unset();
    session_destroy();
    header("Location: login.php?error=session_invalid");
    exit();
}

// Obtener información del usuario
$db = getDBConnection();
$stmt = $db->prepare("SELECT user, email, fecha_registro, ultimo_acceso FROM login WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

if (!$user) {
    session_unset();
    session_destroy();
    header("Location: login.php?error=user_not_found");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Control</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">Mi Aplicación</a>
            <div class="navbar-nav ms-auto">
                <span class="navbar-text me-3">Hola, <?php echo escapeHtml($user['user']); ?></span>
                <a class="nav-link" href="logout.php">Cerrar Sesión</a>
            </div>
        </div>
    </nav>
    
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <div class="card">
                    <div class="card-header">
                        <h4>Información de tu cuenta</h4>
                    </div>
                    <div class="card-body">
                        <p><strong>Usuario:</strong> <?php echo escapeHtml($user['user']); ?></p>
                        <p><strong>Email:</strong> <?php echo escapeHtml($user['email']); ?></p>
                        <p><strong>Fecha de registro:</strong> <?php echo escapeHtml($user['fecha_registro']); ?></p>
                        <p><strong>Último acceso:</strong> <?php echo escapeHtml($user['ultimo_acceso'] ?? 'Nunca'); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>