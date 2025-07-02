
<?php
//PAZUNIIIIIIIIIIIIIIIIIIIIIIIIIIIIII<-------PAZUNI-------->PAZUNIIIIIIIIIIIIIIIIIIIIIIIIIIIIIII
require_once 'configl.php';
require_once 'security.php';


function authenticateUser($email, $password) {
    global $pdo;
    
    $email = sanitizeInput($email);
    
    $stmt = $pdo->prepare("SELECT id, password_hash, rol FROM logins WHERE email = ? LIMIT 1");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user && password_verify($password, $user['password_hash'])) {
        // Contraseña correcta
        return [
            'id' => $user['id'],
            'rol' => $user['rol']
        ];
    }
    
    return false;
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function redirectBasedOnRole() {
    if (isLoggedIn()) {
        if ($_SESSION['user_rol'] == 0) {
            header("Location: ../site/users/dashboard.php");
        } else {
            header("Location: ../site/juez/dashboardj.php");
        }
        exit();
    }
}

function requireLogin() {
    if (!isLoggedIn()) {
        header("Location: login.php");
        exit();
    }
}

function requireAdmin() {
    requireLogin();
    if ($_SESSION['user_rol'] != 1) {
        header("Location: dashboard.php");
        exit();
    }
}
?>