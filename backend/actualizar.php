<?php
include 'connection.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    // Obtener los datos del usuario
    $sql = "SELECT * FROM usuario WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
    } else {
        die("Usuario no encontrado.");
    }
} else {
    die("ID no proporcionado.");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Actualizar los datos
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $num_telefono = $_POST['num_telefono'];
    $rol = $_POST['rol'];
    $grado = $_POST['grado'] ?? '';
    $grupo = $_POST['grupo'] ?? '';
    $acudiente1 = $_POST['acudiente1'] ?? '';
    $acudiente2 = $_POST['acudiente2'] ?? '';
    $nu_acudiente = $_POST['nu_acudiente'] ?? '';

    // Actualizar datos básicos
    $updateSql = "UPDATE usuario SET nombre = ?, correo = ?, num_telefono = ?, rol = ? WHERE id = ?";
    $updateStmt = $conn->prepare($updateSql);
    $updateStmt->bind_param("ssssi", $nombre, $correo, $num_telefono, $rol, $id);

    if ($updateStmt->execute()) {
        // Actualizar en la tabla de estudiante_grado_grupo si es estudiante
        if ($rol === 'estudiante') {
            $updateGradoGrupoSql = "UPDATE estudiante_grado_grupo SET grado=?, grupo=?, acudiente1=?, acudiente2=?, nu_acudiente=? WHERE id=?";
            $updateGradoGrupoStmt = $conn->prepare($updateGradoGrupoSql);
            $updateGradoGrupoStmt->bind_param("ssssss", $grado, $grupo, $acudiente1, $acudiente2, $nu_acudiente, $id);
            $updateGradoGrupoStmt->execute();
            $updateGradoGrupoStmt->close();
        }
        header("Location: ../frontend/read.php");
        exit();
    } else {
        echo "Error al actualizar el registro: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../frontend/update.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualizar usuario</title>
    <script>
        function mostrarFormularioEstudiante() {
            const rolSelect = document.querySelector('select[name="rol"]');
            const formularioEstudiante = document.getElementById('formularioEstudiante');
            const inputsEstudiante = formularioEstudiante.querySelectorAll('input');

            if (rolSelect.value === 'estudiante') {
                formularioEstudiante.style.display = 'block';
                inputsEstudiante.forEach(input => {
                    input.required = true;
                });
            } else {
                formularioEstudiante.style.display = 'none';
                inputsEstudiante.forEach(input => {
                    input.required = false;
                    input.value = ''; // Limpiar valores
                });
            }
        }
        
        document.addEventListener("DOMContentLoaded", mostrarFormularioEstudiante);
    </script>
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="../frontend/index.html">Inicio</a></li>
                <li><a href="../frontend/read.php">Volver</a></li>
            </ul>
        </nav>
    </header>
    <form action="" method="POST">
        <input type="text" name="nombre" placeholder="Nombre" value="<?php echo htmlspecialchars($user['nombre']); ?>" required>
        <input type="email" name="correo" placeholder="Correo" value="<?php echo htmlspecialchars($user['correo']); ?>" required>
        <input type="text" name="num_telefono" placeholder="Número de Teléfono" value="<?php echo htmlspecialchars($user['num_telefono']); ?>" required>
        
        <select name="rol" required onchange="mostrarFormularioEstudiante()">
            <option value="administrador" <?php echo ($user['rol'] == 'administrador') ? 'selected' : ''; ?>>Administrador</option>
            <option value="estudiante" <?php echo ($user['rol'] == 'estudiante') ? 'selected' : ''; ?>>Estudiante</option>
        </select>

        <div id="formularioEstudiante" style="display: <?= $user['rol'] === 'estudiante' ? 'block' : 'none' ?>;">
            <input type="text" placeholder="Grado" name="grado" value="<?= htmlspecialchars($user['grado'] ?? '') ?>" required />
            <input type="text" placeholder="Grupo" name="grupo" value="<?= htmlspecialchars($user['grupo'] ?? '') ?>" required />
            <input type="text" placeholder="Acudiente 1" name="acudiente1" value="<?= htmlspecialchars($user['acudiente1'] ?? '') ?>" required />
            <input type="text" placeholder="Acudiente 2" name="acudiente2" value="<?= htmlspecialchars($user['acudiente2'] ?? '') ?>" required />
            <input type="text" placeholder="Número de Acudiente" name="nu_acudiente" value="<?= htmlspecialchars($user['nu_acudiente'] ?? '') ?>" required />
        </div>

        <button type="submit">Actualizar</button>
    </form>
</body>
</html>

