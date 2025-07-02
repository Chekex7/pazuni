<?php
//usar
// config/database.php
//require_once 'config.php';


class Database {
    private static $instance = null;
    private $connection;
    
    // Configuración - actualiza con tus credenciales
    private $host = 'localhost';
    private $dbname = 'nombre_base_datos';
    private $username = 'tu_usuario';
    private $password = 'tu_contraseña_segura';
    
    private function __construct() {
        try {
            $this->connection = new PDO(
                "mysql:host={$this->host};dbname={$this->dbname};charset=utf8mb4",
                $this->username,
                $this->password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                    PDO::ATTR_PERSISTENT => false
                ]
            );
        } catch (PDOException $e) {
            // En producción, registra este error en un log
            error_log($e->getMessage());
            throw new Exception("Error al conectar con la base de datos");
        }
    }
    
    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new Database();
        }
        return self::$instance;
    }
    
    public function getConnection() {
        return $this->connection;
    }
    
    private function __clone() { }
    private function __wakeup() { }
}

function getDBConnection() {
    return Database::getInstance()->getConnection();
}
?>