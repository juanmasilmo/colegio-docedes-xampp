<?php
// db.php
require_once 'config.php';

class Database
{
    private static $instance = null;
    private $connection;

    private function __construct()
    {
        $this->connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
        if ($this->connection->connect_error) {
            die("Error de conexión: " . $this->connection->connect_error);
        }
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance->connection;
    }

    // Método para iniciar sesión y almacenar datos
    public static function iniciarSesion($usuario)
    {
        session_start();
        $_SESSION['user'] = [
            'id' => $usuario['id'],
            'nombre_usuario' => $usuario['nombre_usuario'],
            'rol_id' => $usuario['rol_id']
        ];
    }

    // Método para verificar si hay una sesión activa
    public static function verificarSesion()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        return isset($_SESSION['user']);
    }

    // Método para obtener el rol del usuario
    public static function getRolUsuario()
    {
        if (self::verificarSesion()) {
            return $_SESSION['user']['rol_id'];
        }
        return null;
    }

    // Método para cerrar sesión
    public static function cerrarSesion()
    {
        session_start();
        session_unset();
        session_destroy();
    }
}
