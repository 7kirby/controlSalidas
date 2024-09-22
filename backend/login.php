<?php
include 'connection.php'; // Incluir la conexión a la base de datos

if ($_SERVER["REQUEST_METHOD"] == 'POST') {
    // Obtener los datos del formulario
    $id = $_POST['id'];
    $pass = $_POST['pass'];

    // Preparar la consulta SQL para obtener el hash de la contraseña y el rol
    $stmt = $conn->prepare("SELECT pass, rol FROM usuario WHERE id = ?");
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($hashed_pass, $rol);
        $stmt->fetch();

        // Verificar la contraseña
        if (password_verify($pass, $hashed_pass)) {
            // Contraseña correcta, redirigir según el rol
            switch ($rol) {
                case 'administrador':
                    header("Location: ../frontend/admin.html");
                    break;
                case 'estudiante':
                    echo "Bienvenido";
                    break;
                default:
                    header("Location: ../frontend/error.html"); // Redirigir a una página de error si el rol no es válido
            }
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
