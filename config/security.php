
<?php
//PAZUNIIIIIIIIIIIIIIIIIIIIIIIIIIIIII<-------PAZUNI-------->PAZUNIIIIIIIIIIIIIIIIIIIIIIIIIIIIIII
function sanitizeInput($data) {
    return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
}

function generateCSRFToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verifyCSRFToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

function startSecureSession() {
    ini_set('session.cookie_httponly', 1);
    ini_set('session.cookie_secure', 1); // Solo en HTTPS
    ini_set('session.use_strict_mode', 1);
    session_start();
    
    // Regenerar ID de sesión periódicamente
    if (!isset($_SESSION['created'])) {
        $_SESSION['created'] = time();
    } elseif (time() - $_SESSION['created'] > 1800) {
        session_regenerate_id(true);
        $_SESSION['created'] = time();
    }
}
?>