<?php
session_start(); // Importante: iniciar la sesión al principio del archivo
require_once '../includes/session.php';
require_once '../includes/database.php';

// Si el formulario fue enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre_usuario = $_POST['nombre_usuario'];
    $password = $_POST['password'];

    // Consulta para obtener los datos del usuario
    $sql = "SELECT u.*, r.nombre_rol 
            FROM usuarios u 
            JOIN roles r ON u.rol_id = r.id 
            WHERE u.nombre_usuario = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $nombre_usuario);
    $stmt->execute();
    $result = $stmt->get_result();
    $usuario = $result->fetch_assoc();

    if ($usuario && password_verify($password, $usuario['password'])) {
        // Almacenar datos en la sesión
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['nombre_usuario'] = $usuario['nombre_usuario'];
        $_SESSION['rol'] = $usuario['nombre_rol'];
        $_SESSION['rol_id'] = $usuario['rol_id'];

        // Redirigir según el rol
        if ($_SESSION['rol'] === 'admin') {
            header("Location: admin/dashboard.php");
        } else {
            header("Location: user/dashboard.php");
        }
        exit();
    } else {
        $error = "Usuario o contraseña incorrectos";
    }

    $stmt->close();
    $conn->close();
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