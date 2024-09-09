<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "controlsalidabd";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
} else {
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Obtener los datos del formulario
        $id = $_POST['id'];
        $nombre = $_POST['nombre'];
        $correo = $_POST['correo'];
        $rol = $_POST['rol'];
        $pass = $_POST['pass'];

        // Preparar la consulta SQL
        $stmt = $conn->prepare("INSERT INTO usuario (id, nombre, correo, rol, pass) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $id, $nombre, $correo, $rol, $pass);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            // Redirigir a index.html si la inserción es exitosa
            header("Location: ../frontend/registro.html");
            exit();
        } else {
            // Manejar el error de inserción
            echo "Error: " . $stmt->error;
        }

        // Cerrar la declaración
        $stmt->close();
    }

    // Cerrar la conexión
    $conn->close();
}
?>
