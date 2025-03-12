<?php
require_once '../../../includes/session.php';
require_once '../../../includes/database.php';
require_once '../../../includes/auth.php';

// Verificar si el usuario está logueado y es contador
Auth::checkRole(Auth::ROLE_CONTADOR);

// Obtener la conexión
$conn = Database::getInstance();

// Obtener lista de localidades para el select
$localidades = $conn->query("SELECT id, nombre FROM localidades ORDER BY nombre");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Iniciar transacción
        $conn->begin_transaction();

        // Insertar primero en la tabla personas
        $sqlPersona = "INSERT INTO personas (nombre, apellido, dni, telefono, id_localidad, mail, cuil, observaciones, domicilio) 
                       VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmtPersona = $conn->prepare($sqlPersona);
        $stmtPersona->bind_param(
            "ssssissss",
            $_POST['nombre'],
            $_POST['apellido'],
            $_POST['dni'],
            $_POST['telefono'],
            $_POST['id_localidad'],
            $_POST['mail'],
            $_POST['cuil'],
            $_POST['observaciones_persona'],
            $_POST['domicilio']
        );

        if ($stmtPersona->execute()) {
            $persona_id = $conn->insert_id;

            // Insertar en la tabla proveedores
            $sqlProveedor = "INSERT INTO proveedores (persona_id, cuit, observaciones) VALUES (?, ?, ?)";
            $stmtProveedor = $conn->prepare($sqlProveedor);
            $stmtProveedor->bind_param(
                "iss",
                $persona_id,
                $_POST['cuit'],
                $_POST['observaciones_proveedor']
            );

            if ($stmtProveedor->execute()) {
                $conn->commit();
                header("Location: listar.php?success=1");
                exit();
            }
        }

        // Si llegamos aquí, algo falló
        throw new Exception("Error al guardar los datos");
    } catch (Exception $e) {
        $conn->rollback();
        $error = "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Crear Nuevo Proveedor</title>
    <script>
        function confirmarEnvio(event) {
            event.preventDefault();
            if (confirm('¿Está seguro que desea crear este proveedor?')) {
                event.target.submit();
            }
        }
    </script>
</head>

<body>
    <h1>Crear Nuevo Proveedor</h1>

    <?php if (isset($error)): ?>
        <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>

    <form method="POST" action="" onsubmit="confirmarEnvio(event)">
        <h2>Datos Personales</h2>

        <label for="nombre">Nombre:</label>
        <input type="text" name="nombre" required><br><br>

        <label for="apellido">Apellido:</label>
        <input type="text" name="apellido" required><br><br>

        <label for="dni">DNI:</label>
        <input type="text" name="dni" required><br><br>

        <label for="telefono">Teléfono:</label>
        <input type="text" name="telefono"><br><br>

        <label for="id_localidad">Localidad:</label>
        <select name="id_localidad" required>
            <option value="">Seleccione una localidad</option>
            <?php while ($localidad = $localidades->fetch_assoc()): ?>
                <option value="<?php echo $localidad['id']; ?>">
                    <?php echo htmlspecialchars($localidad['nombre']); ?>
                </option>
            <?php endwhile; ?>
        </select><br><br>

        <label for="mail">Email:</label>
        <input type="email" name="mail"><br><br>

        <label for="cuil">CUIL:</label>
        <input type="text" name="cuil" required><br><br>

        <label for="domicilio">Domicilio:</label>
        <input type="text" name="domicilio" required><br><br>

        <label for="observaciones_persona">Observaciones Personales:</label>
        <textarea name="observaciones_persona" rows="3"></textarea><br><br>

        <h2>Datos del Proveedor</h2>

        <label for="cuit">CUIT:</label>
        <input type="text" name="cuit" required><br><br>

        <label for="observaciones_proveedor">Observaciones del Proveedor:</label>
        <textarea name="observaciones_proveedor" rows="3"></textarea><br><br>

        <button type="submit">Guardar Proveedor</button>
    </form>

    <br>
    <a href="../dashboard.php">Volver al Dashboard</a>
</body>

</html>