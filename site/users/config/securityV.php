<?php
// config/security.php

// Configuración de seguridad
define('MAX_LOGIN_ATTEMPTS', 5);
define('LOGIN_ATTEMPTS_TIMEFRAME', 1 * 1); // 15 minutos (tenia 15 * 60)
define('PASSWORD_MIN_LENGTH', 12);
define('SESSION_TIMEOUT', 30 * 60); // 30 minutos de inactividad

// Funciones de seguridad

/**
 * Genera un token CSRF
 */
function generateCsrfToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Valida un token CSRF
 */
function validateCsrfToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Verifica si la contraseña cumple con los requisitos de seguridad
 */
function isPasswordStrong($password) {
    return strlen($password) >= PASSWORD_MIN_LENGTH
        && preg_match('/[A-Z]/', $password) // Al menos una mayúscula
        && preg_match('/[a-z]/', $password) // Al menos una minúscula
        && preg_match('/[0-9]/', $password) // Al menos un número
        && preg_match('/[^A-Za-z0-9]/', $password); // Al menos un carácter especial
}

/**
 * Escapa output para prevenir XSS
 */
function escapeHtml($data) {
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}