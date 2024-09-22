<?php
include 'connection.php'; // Incluir la conexión a la base de datos

if ($_SERVER["REQUEST_METHOD"] == 'POST') {
    // Obtener los datos del formulario
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $rol = $_POST['rol'];
    $pass = $_POST['pass'];

    // Hashear la contraseña
    $hashed_pass = password_hash($pass, PASSWORD_DEFAULT);
   
    // Preparar la consulta SQL
    $stmt = $conn->prepare("INSERT INTO usuario (id, nombre, correo, rol, pass) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $id, $nombre, $correo, $rol, $hashed_pass);

    // Ejecutar la consulta
    if ($stmt->execute()) {
        // Redirigir a registro.html si la inserción es exitosa
        header("Location: ../frontend/registro.html");
        exit();
    } else {
        // Manejar el error de inserción
        echo "Error al registrar datos: " . $stmt->error;
    }

    // Cerrar la declaración
    $stmt->close();
}
?>
