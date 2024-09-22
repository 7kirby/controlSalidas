<?php
include 'connection.php'; // Incluir la conexión a la base de datos

if ($_SERVER["REQUEST_METHOD"] == 'POST') {
    // Obtener los datos del formulario
    $id = $_POST['id'];
    $pass = $_POST['pass'];

    // Preparar la consulta SQL para obtener el hash de la contraseña
    $stmt = $conn->prepare("SELECT pass FROM usuario WHERE id = ?");
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($hashed_pass);
        $stmt->fetch();

        // Verificar la contraseña
        if (password_verify($pass, $hashed_pass)) {
            // Contraseña correcta, redirigir a la página de inicio
            header("Location: ../frontend/index.html");
            exit();
        } else {
            // Contraseña incorrecta
            echo "Contraseña incorrecta";
        }
    } else {
        // Usuario no encontrado
        echo "Usuario no encontrado";
    }

    $stmt->close();
}
?>
