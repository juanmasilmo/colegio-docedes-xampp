<?php
require_once '../../includes/session.php';
require_once '../../includes/database.php';
require_once '../../includes/auth.php';

// Verificar si el usuario está logueado y es contador
Auth::checkRole(Auth::ROLE_CONTADOR);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Panel de Contador</title>
</head>

<body>
    <h1>Panel de Contador</h1>
    <p>Bienvenido, <?php echo htmlspecialchars($_SESSION['user']['username']); ?></p>

    <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
        <div style="background-color: #dff0d8; color: #3c763d; padding: 10px; margin: 10px 0; border-radius: 4px;">
            Proveedor creado exitosamente.
        </div>
    <?php endif; ?>

    <h2>Menú de Contabilidad</h2>
    <ul>
        <li><a href="transacciones/crear.php">Cargar Nueva Transacción</a></li>
        <li><a href="proveedores/crear.php">Crear Nuevo Proveedor</a></li>
        <li><a href="transacciones/listar.php">Ver Transacciones</a></li>
        <li><a href="../logout.php">Cerrar Sesión</a></li>
    </ul>
</body>

</html>