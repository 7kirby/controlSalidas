<?php
include 'connection.php'; // Incluir la conexión a la base de datos

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $id = $_POST['id'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Verificar si las contraseñas coinciden
    if ($new_password !== $confirm_password) {
        echo "Las contraseñas no coinciden.";
        exit();
    }

    // Preparar la consulta SQL para verificar que el usuario existe
    $stmt = $conn->prepare("SELECT id FROM usuario WHERE id = ?");
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // El usuario existe, actualizar la contraseña

        // Hashear la nueva contraseña
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        // Preparar la consulta para actualizar la contraseña   
        $update_stmt = $conn->prepare("UPDATE usuario SET pass = ? WHERE id = ?");
        $update_stmt->bind_param("ss", $hashed_password, $id);

        // Ejecutar la consulta
        if ($update_stmt->execute()) {
            header("Location: ../frontend/registro.html");
        } else {
            header("Location: ../frontend/recuperarpass.html");
        }

        $update_stmt->close();
    } else {
        echo "Usuario no encontrado.";
    }

    // Cerrar la declaración
    $stmt->close();
}
?>
