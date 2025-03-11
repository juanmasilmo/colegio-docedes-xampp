<?php
require_once '../../includes/session.php';
require_once '../../includes/database.php';
require_once '../../includes/auth.php';

// Verificar si el usuario está logueado y es admin
if (!isset($_SESSION['user']) || $_SESSION['user']['rol_id'] !== 1) {
    header("Location: ../login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Panel de Administración</title>
</head>

<body>
    <h1>Panel de Administración</h1>
    <p>Bienvenido, <?php echo htmlspecialchars($_SESSION['user']['username']); ?></p>

    <h2>Menú de Administración</h2>
    <ul>
        <li><a href="../crear-usuario.php">Crear Nuevo Usuario</a></li>
        <li><a href="gestionar-usuarios.php">Gestionar Usuarios</a></li>
        <li><a href="logout.php">Cerrar Sesión</a></li>
    </ul>
</body>

</html>