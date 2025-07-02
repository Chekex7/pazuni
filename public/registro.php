<?php
session_start();
require_once '../config/confip.php';
require_once '../config/csrf_token.php';

$errors = [];
$success = false;

// Verificar si el formulario fue enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validar token CSRF
    if (!isset($_POST['csrf_token']) || !validateCsrfToken($_POST['csrf_token'])) {
        $errors[] = "Error de seguridad (CSRF). Por favor, intenta nuevamente.";
    } else {
        // Recoger y sanitizar datos
        $user = filter_input(INPUT_POST, 'user', FILTER_SANITIZE_STRING);
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $password = $_POST['password'];
        $password_confirm = $_POST['password_confirm'];
        $nombre = filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_STRING);
        $apellido = filter_input(INPUT_POST, 'apellido', FILTER_SANITIZE_STRING);
        $cedula = filter_input(INPUT_POST, 'cedula', FILTER_SANITIZE_STRING);
        $fecha_nacimiento = filter_input(INPUT_POST, 'fecha_nacimiento', FILTER_SANITIZE_STRING);
        $sexo = filter_input(INPUT_POST, 'sexo', FILTER_SANITIZE_STRING);
        $direccion = filter_input(INPUT_POST, 'direccion', FILTER_SANITIZE_STRING);
        $numero_casa = filter_input(INPUT_POST, 'numero_casa', FILTER_SANITIZE_STRING);
        $celular = filter_input(INPUT_POST, 'celular', FILTER_SANITIZE_STRING);
        $otro_celular = filter_input(INPUT_POST, 'otro_celular', FILTER_SANITIZE_STRING);

        // Validaciones
        if (empty($user)) $errors[] = "El nombre de usuario es requerido";
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "El email no es válido";
        if (strlen($password) < 8) $errors[] = "La contraseña debe tener al menos 8 caracteres";
        if ($password !== $password_confirm) $errors[] = "Las contraseñas no coinciden";
        if (empty($nombre)) $errors[] = "El nombre es requerido";
        if (empty($apellido)) $errors[] = "El apellido es requerido";
        if (empty($cedula)) $errors[] = "La cédula es requerida";
        if (empty($fecha_nacimiento)) $errors[] = "La fecha de nacimiento es requerida";
        if (empty($sexo)) $errors[] = "El sexo es requerido";
        if (empty($direccion)) $errors[] = "La dirección es requerida";
        if (empty($celular)) $errors[] = "El número de celular es requerido";

        // Verificar si el usuario o email ya existen
        if (empty($errors)) {
            $stmt = $pdo->prepare("SELECT id FROM logins WHERE user = ? OR email = ?");
            $stmt->execute([$user, $email]);
            if ($stmt->rowCount() > 0) {
                $errors[] = "El nombre de usuario o email ya están registrados";
            }
        }

        // Si no hay errores, proceder con el registro
        if (empty($errors)) {
            try {
                $pdo->beginTransaction();

                // Hash de la contraseña
                $password_hash = password_hash($password, PASSWORD_BCRYPT);

                // Insertar en tabla logins
                $stmt = $pdo->prepare("INSERT INTO logins (user, email, password_hash) VALUES (?, ?, ?)");
                $stmt->execute([$user, $email, $password_hash]);
                $user_id = $pdo->lastInsertId();

                // Insertar en tabla perfilx
                $stmt = $pdo->prepare("INSERT INTO perfilx (logins_id, nombre, apellido, cedula, fecha_nac, sexo, direccion, numero_casa, celular, otro_celular) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$user_id, $nombre, $apellido, $cedula, $fecha_nacimiento, $sexo, $direccion, $numero_casa, $celular, $otro_celular]);

                $pdo->commit();
                $success = true;
            } catch (PDOException $e) {
                $pdo->rollBack();
                $errors[] = "Error al registrar: " . $e->getMessage();
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/registro.css">
    <title>Registro de Usuario</title>
    <style>
        .error { color: red; }
        .success { color: green; }
    </style>
</head>
<body>
    <h1>Registro de Usuario</h1>
    
    <?php if ($success): ?>
        <p class="success">¡Registro exitoso! Ahora puedes iniciar sesión.</p>
    <?php else: ?>
        <?php foreach ($errors as $error): ?>
            <p class="error"><?= htmlspecialchars($error) ?></p>
        <?php endforeach; ?>

        <form method="post">
            <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
            
            <h2>Datos de acceso</h2>
            <div>
                <label for="user">Nombre de usuario:</label>
                <input type="text" id="user" name="user" required value="<?= isset($_POST['user']) ? htmlspecialchars($_POST['user']) : '' ?>">
            </div>
            <div>
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>">
            </div>
            <div>
                <label for="password">Contraseña (mínimo 8 caracteres):</label>
                <input type="password" id="password" name="password" required minlength="8">
            </div>
            <div>
                <label for="password_confirm">Confirmar contraseña:</label>
                <input type="password" id="password_confirm" name="password_confirm" required minlength="8">
            </div>
            
            <h2>Datos personales</h2>
            <div>
                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" required value="<?= isset($_POST['nombre']) ? htmlspecialchars($_POST['nombre']) : '' ?>">
            </div>
            <div>
                <label for="apellido">Apellido:</label>
                <input type="text" id="apellido" name="apellido" required value="<?= isset($_POST['apellido']) ? htmlspecialchars($_POST['apellido']) : '' ?>">
            </div>
            <div>
                <label for="cedula">Cédula:</label>
                <input type="text" id="cedula" name="cedula" required value="<?= isset($_POST['cedula']) ? htmlspecialchars($_POST['cedula']) : '' ?>">
            </div>
            <div>
                <label for="fecha_nacimiento">Fecha de nacimiento:</label>
                <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" required value="<?= isset($_POST['fecha_nacimiento']) ? htmlspecialchars($_POST['fecha_nacimiento']) : '' ?>">
            </div>
            <div>
                <label for="sexo">Sexo:</label>
                <select id="sexo" name="sexo" required>
                    <option value="">Seleccione...</option>
                    <option value="M" <?= (isset($_POST['sexo']) && $_POST['sexo'] === 'M') ? 'selected' : '' ?>>Masculino</option>
                    <option value="F" <?= (isset($_POST['sexo']) && $_POST['sexo'] === 'F') ? 'selected' : '' ?>>Femenino</option>
                    <option value="O" <?= (isset($_POST['sexo']) && $_POST['sexo'] === 'O') ? 'selected' : '' ?>>Otro</option>
                </select>
            </div>
            <div>
                <label for="direccion">Dirección:</label>
                <input type="text" id="direccion" name="direccion" required value="<?= isset($_POST['direccion']) ? htmlspecialchars($_POST['direccion']) : '' ?>">
            </div>
            <div>
                <label for="numero_casa">Número de casa:</label>
                <input type="text" id="numero_casa" name="numero_casa" required value="<?= isset($_POST['numero_casa']) ? htmlspecialchars($_POST['numero_casa']) : '' ?>">
            </div>
            <div>
                <label for="celular">Celular:</label>
                <input type="tel" id="celular" name="celular" required value="<?= isset($_POST['celular']) ? htmlspecialchars($_POST['celular']) : '' ?>">
            </div>
            <div>
                <label for="otro_celular">Otro celular (opcional):</label>
                <input type="tel" id="otro_celular" name="otro_celular" value="<?= isset($_POST['otro_celular']) ? htmlspecialchars($_POST['otro_celular']) : '' ?>">
            </div>
            
            <button type="submit">Registrarse</button>
        </form>
    <?php endif; ?>
</body>
</html>