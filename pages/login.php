<?php
require_once '../includes/session.php';
require_once '../includes/database.php';
require_once '../includes/auth.php';

// Obtener la conexión usando el patrón Singleton
$conn = Database::getInstance();

// Si el formulario fue enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre_usuario = $_POST['nombre_usuario'];
    $password = $_POST['password'];

    if (Auth::login($nombre_usuario, $password)) {
        // Redirigir según el rol usando switch
        switch ($_SESSION['user']['rol_id']) {
            case 1: // Admin
                header("Location: admin/dashboard.php");
                break;
            case 2: // Auditor
                header("Location: auditor/dashboard.php");
                break;
            case 3: // Contador
                header("Location: contador/dashboard.php");
                break;
            default:
                header("Location: user/dashboard.php");
                break;
        }
        exit();
    } else {
        $error = "Usuario o contraseña incorrectos";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Página de Login</title>
</head>

<body>
    <h1>Iniciar Sesión</h1>

    <!-- Mostrar error si existe -->
    <?php if (isset($error)) : ?>
        <p style="color:red;"><?php echo $error; ?></p>
    <?php endif; ?>

    <!-- Formulario de login -->
    <form method="POST" action="">
        <label for="nombre_usuario">Usuario:</label>
        <input type="text" name="nombre_usuario" required><br><br>

        <label for="password">Contraseña:</label>
        <input type="password" name="password" required><br><br>

        <button type="submit">Iniciar Sesión</button>
    </form>

    <!-- Mensaje de éxito al volver de crear usuario -->
    <?php if (isset($_GET['success'])) : ?>
        <p style="color:green;">Usuario creado correctamente. ¡Ahora inicia sesión!</p>
    <?php endif; ?>

    <hr>

    <!-- Botón para crear un nuevo usuario -->
    <a href="crear-usuario.php">Crear nuevo usuario</a>
</body>

</html>