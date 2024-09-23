<?php
include '../backend/connection.php';

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
    $rol = $_POST['rol'];

    $updateSql = "UPDATE usuario SET nombre = ?, correo = ?, rol = ? WHERE id = ?";
    $updateStmt = $conn->prepare($updateSql);
    $updateStmt->bind_param("sssi", $nombre, $correo, $rol, $id);

    if ($updateStmt->execute()) {
        header("Location: ../frontend/read.php"); // Redirigir a la lista después de actualizar
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
    <link rel="stylesheet" href="../frontend/update.css"> <!-- Ajusta según tus estilos -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualizar usuario</title>
</head>
<header>
      <nav>
        <ul>
          <li><a href="../frontend/index.html">Inicio</a></li>
          <li><a href="../frontend/read.php">CRUD</a></li>
        </ul>
      </nav>
    </header>
<body>
    <form action="" method="POST">
        <input type="text" name="nombre" placeholder="Nombre" value="<?php echo htmlspecialchars($user['nombre']); ?>" required>
        <input type="email" name="correo" placeholder="Correo" value="<?php echo htmlspecialchars($user['correo']); ?>" required>
        <!-- <input type="email" name="numero" placeholder="Numero celular" value="<?php echo htmlspecialchars($user['numero']); ?>" required> -->
        <select name="rol" required>
            <option value="administrador" <?php echo ($user['rol'] == 'administrador') ? 'selected' : ''; ?>>Administrador</option>
            <option value="estudiante" <?php echo ($user['rol'] == 'estudiante') ? 'selected' : ''; ?>>Estudiante</option>
        </select>
        <button type="submit">Actualizar</button>
    </form>
</body>
</html>
