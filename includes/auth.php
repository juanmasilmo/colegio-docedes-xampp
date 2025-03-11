<?php
require_once __DIR__ . '/database.php';
require_once __DIR__ . '/functions.php';

class Auth
{
    public static function login($username, $password)
    {
        global $conn;

        // Usar sanitize_input para limpiar el username
        $username = sanitize_input($username);

        $stmt = $conn->prepare("SELECT * FROM usuarios WHERE nombre_usuario = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user && password_verify($password, $user['password'])) {
            // Regenerar ID de sesión para prevenir fijación
            session_regenerate_id(true);

            $_SESSION['user'] = [
                'id' => $user['id'],
                'username' => $user['nombre_usuario'],
                'rol_id' => $user['rol_id'],
                'csrf_token' => generateCSRFToken()
            ];

            return true;
        }

        return false;
    }
    /*public static function checkRole($requiredRole, $redirect = true)
    {
        if (!isset($_SESSION['user']) || $_SESSION['user']['rol_id'] !== $requiredRole) {
            if ($redirect) {
                header("Location: ../login.php");
                exit();
            }
            return false;
        }
        return true;
    }*/

    // Podemos añadir constantes para los roles para mejor legibilidad
    const ROLE_ADMIN = 1;
    const ROLE_AUDITOR = 2;
    const ROLE_CONTADOR = 3;

    public static function checkRole($requiredRole)
    {
        // Usar is_logged_in para verificar la sesión
        if (!is_logged_in() || $_SESSION['user']['rol_id'] != $requiredRole) {
            return false;
        }
        return true;
    }

    public static function logout()
    {
        $_SESSION = [];
        session_destroy();
    }
}
