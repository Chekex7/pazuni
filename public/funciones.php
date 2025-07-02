
<?php
//PAZUNIIIIIIIIIIIIIIIIIIIIIIIIIIIIII<-------PAZUNI-------->PAZUNIIIIIIIIIIIIIIIIIIIIIIIIIIIIIII
// Función para limpiar y sanitizar datos
function limpiarEntrada($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Función para verificar fortaleza de contraseña
function esContrasenaSegura($password) {
    // Mínimo 8 caracteres, al menos una mayúscula, una minúscula y un número
    return preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/', $password);
}

// Función para generar token CSRF
function generarTokenCSRF() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

// Función para verificar token CSRF
function verificarTokenCSRF($token) {
    if (!empty($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token)) {
        return true;
    }
    return false;
}
?>