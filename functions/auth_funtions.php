<?php
//PAZUNIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIII<-------PAZUNI-------->PAZUNIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIII
// includes/auth_functions.php

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/security.php';

/**
 * Registra un nuevo usuario
 */
function registerUser($username, $email, $password) {
    $db = getDBConnection();
    
    // Validaciones básicas
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        throw new Exception("El formato del email no es válido");
    }
    
    if (!isPasswordStrong($password)) {
        throw new Exception("La contraseña no cumple con los requisitos de seguridad");
    }
    
    // Hash de la contraseña
    $passwordHash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
    
    try {
        $stmt = $db->prepare("INSERT INTO login (user, email, password_hash) VALUES (?, ?, ?)");
        $stmt->execute([$username, $email, $passwordHash]);
        
        return true;
    } catch (PDOException $e) {
        // Manejo específico de errores de duplicado
        if ($e->errorInfo[1] === 1062) {
            $errorMessage = $e->getMessage();
            if (strpos($errorMessage, 'user') !== false) {
                throw new Exception("El nombre de usuario ya está en uso");
            } elseif (strpos($errorMessage, 'email') !== false) {
                throw new Exception("El correo electrónico ya está registrado");
            }
        }
        throw new Exception("Error al registrar el usuario: " . $e->getMessage());
    }
}

/**
 * Autentica a un usuario
 */
function authenticateUser($username, $password) {
    $db = getDBConnection();
    
    // Verificar intentos fallidos recientes
    if (isset($_SESSION['login_attempts']) && 
        $_SESSION['login_attempts'] >= MAX_LOGIN_ATTEMPTS && 
        time() - $_SESSION['last_login_attempt'] < LOGIN_ATTEMPTS_TIMEFRAME) {
        throw new Exception("Demasiados intentos fallidos. Por favor, espere antes de intentar nuevamente.");
    }
    
    // Obtener usuario de la base de datos
    $stmt = $db->prepare("SELECT id, user, password_hash, activo FROM login WHERE user = ? OR email = ? LIMIT 1");
    $stmt->execute([$username, $username]);
    $user = $stmt->fetch();
    
    if (!$user) {
        // Registrar intento fallido
        $_SESSION['login_attempts'] = ($_SESSION['login_attempts'] ?? 0) + 1;
        $_SESSION['last_login_attempt'] = time();
        throw new Exception("Usuario o contraseña incorrectos");
    }
    
    // Verificar si la cuenta está activa
    if (!$user['activo']) {
        throw new Exception("Esta cuenta ha sido desactivada");
    }
    
    // Verificar contraseña
    if (!password_verify($password, $user['password_hash'])) {
        // Registrar intento fallido
        $_SESSION['login_attempts'] = ($_SESSION['login_attempts'] ?? 0) + 1;
        $_SESSION['last_login_attempt'] = time();
        throw new Exception("Usuario o contraseña incorrectos");
    }
    
    // Verificar si la contraseña necesita ser rehashed
    if (password_needs_rehash($user['password_hash'], PASSWORD_BCRYPT, ['cost' => 12])) {
        $newHash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
        $stmt = $db->prepare("UPDATE login SET password_hash = ? WHERE id = ?");
        $stmt->execute([$newHash, $user['id']]);
    }
    
    // Actualizar último acceso
    $stmt = $db->prepare("UPDATE login SET ultimo_acceso = NOW() WHERE id = ?");
    $stmt->execute([$user['id']]);
    
    // Resetear intentos fallidos
    unset($_SESSION['login_attempts']);
    unset($_SESSION['last_login_attempt']);
    
    return $user;
}

/**
 * Verifica si el usuario está logueado
 */
function isUserLoggedIn() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    // Verificar timeout de sesión
    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > SESSION_TIMEOUT)) {
        session_unset();
        session_destroy();
        return false;
    }
    
    $_SESSION['last_activity'] = time(); // Actualizar tiempo de última actividad
    
    return isset($_SESSION['user_id']);
}

/**
 * Protege una página requiriendo autenticación
 */
function requireLogin() {
    if (!isUserLoggedIn()) {
        header("Location: login.php?redirect=" . urlencode($_SERVER['REQUEST_URI']));
        exit();
    }
}