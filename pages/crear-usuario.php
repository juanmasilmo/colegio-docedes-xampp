<?php
require_once '../includes/session.php';
require_once '../includes/database.php';

// Obtener la conexión
$conn = Database::getInstance();

// Verificar si ya existe algún usuario admin
$checkAdmin = $conn->query("SELECT COUNT(*) as count FROM usuarios u JOIN roles r ON u.rol_id = r.id WHERE r.nombre_rol = 'admin'");
$adminExists = $checkAdmin->fetch_assoc()['count'] > 0;

// Solo verificar el rol si ya existe un admin
if ($adminExists && isset($_SESSION['usuario_id']) && $_SESSION['rol'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// El resto del código de verificación de usuario logueado no es necesario
// si quieres permitir que cualquier persona cree un usuario

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recibir los datos del formulario
    $nombre         = $_POST['nombre'];
    $apellido       = $_POST['apellido'];
    $dni            = $_POST['dni'];
    $telefono       = $_POST['telefono'];
    $mail           = $_POST['mail'];
    $cuil           = $_POST['cuil'];
    $domicilio      = $_POST['domicilio'];
    $nombre_usuario = $_POST['nombre_usuario'];
    $password       = $_POST['password'];
    $rol_id         = $_POST['rol_id'];

    // Verificar si existe el DNI o CUIL en la tabla personas
    $sqlCheckPersona = "SELECT id FROM personas WHERE dni = ? OR cuil = ?";
    $stmtPersona = $conn->prepare($sqlCheckPersona);
    $stmtPersona->bind_param("ss", $dni, $cuil);
    $stmtPersona->execute();
    $resultPersona = $stmtPersona->get_result();

    // Verificar si existe el nombre de usuario en la tabla usuarios
    $sqlCheckUsuario = "SELECT id FROM usuarios WHERE nombre_usuario = ?";
    $stmtUsuario = $conn->prepare($sqlCheckUsuario);
    $stmtUsuario->bind_param("s", $nombre_usuario);
    $stmtUsuario->execute();
    $resultUsuario = $stmtUsuario->get_result();

    if ($resultPersona->num_rows > 0) {
        $error = "El DNI o CUIL ya está registrado en el sistema.";
    } elseif ($resultUsuario->num_rows > 0) {
        $error = "El nombre de usuario ya existe.";
    } else {
        // Insertar en la tabla personas
        $sqlInsertPersona = "
            INSERT INTO personas (nombre, apellido, dni, telefono, mail, cuil, domicilio)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ";
        $stmtInsertPersona = $conn->prepare($sqlInsertPersona);
        $stmtInsertPersona->bind_param("sssssss", $nombre, $apellido, $dni, $telefono, $mail, $cuil, $domicilio);

        if ($stmtInsertPersona->execute()) {
            $persona_id = $stmtInsertPersona->insert_id;

            // Hashear la contraseña
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);

            // Insertar en la tabla usuarios
            $sqlInsertUsuario = "
                INSERT INTO usuarios (nombre_usuario, password, persona_id, rol_id)
                VALUES (?, ?, ?, ?)
            ";
            $stmtInsertUsuario = $conn->prepare($sqlInsertUsuario);
            $stmtInsertUsuario->bind_param("ssii", $nombre_usuario, $passwordHash, $persona_id, $rol_id);

            if ($stmtInsertUsuario->execute()) {
                header("Location: login.php?success=1");
                exit();
            } else {
                $error = "Error al crear el usuario: " . $stmtInsertUsuario->error;
            }
        } else {
            $error = "Error al crear la persona: " . $stmtInsertPersona->error;
        }
    }

    // Cerrar conexiones
    $stmtPersona->close();
    $stmtUsuario->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Crear Usuario</title>
</head>

<body>
    <h1>Crear Usuario</h1>

    <?php if (isset($error)) : ?>
        <p style="color:red;"><?php echo $error; ?></p>
    <?php endif; ?>

    <!-- Formulario para crear usuario y la persona asociada -->
    <form method="POST" action="">
        <h2>Datos de la Persona</h2>
        <label for="nombre">Nombre:</label>
        <input type="text" name="nombre" required><br><br>

        <label for="apellido">Apellido:</label>
        <input type="text" name="apellido" required><br><br>

        <label for="dni">DNI:</label>
        <input type="text" name="dni" required><br><br>

        <label for="telefono">Teléfono:</label>
        <input type="text" name="telefono"><br><br>

        <label for="mail">Email:</label>
        <input type="email" name="mail"><br><br>

        <label for="cuil">CUIL:</label>
        <input type="text" name="cuil" required><br><br>

        <label for="domicilio">Domicilio:</label>
        <input type="text" name="domicilio"><br><br>

        <h2>Datos de Usuario</h2>

        <label for="nombre_usuario">Nombre de Usuario:</label>
        <input type="text" name="nombre_usuario" required><br><br>

        <label for="password">Contraseña:</label>
        <input type="password" name="password" required><br><br>

        <label for="rol_id">Rol:</label>
        <select name="rol_id" required>
            <option value="">Seleccione un rol</option>
            <?php
            // Listar TODOS los roles disponibles
            $sqlRoles = "SELECT id, nombre_rol FROM roles";
            $resultRoles = $conn->query($sqlRoles);
            while ($rowRole = $resultRoles->fetch_assoc()) {
                echo "<option value='" . $rowRole['id'] . "'>" . $rowRole['nombre_rol'] . "</option>";
            }
            ?>
        </select><br><br>

        <button type="submit">Crear Usuario</button>
    </form>

    <br>
    <a href="login.php">Volver al login</a>
</body>

</html>