<?php
include 'connection.php'; // Incluir la conexión a la base de datos

// Define la contraseña fija para el administrador
define('ADMIN_PASSWORD', 'TuContraseñaFija123'); // Cambia esto por la contraseña deseada

if ($_SERVER["REQUEST_METHOD"] == 'POST') {
    // Obtener los datos del formulario
    $id = $_POST['id'];
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $num_telefono = $_POST['num_telefono'];
    $rol = $_POST['rol'];
    $pass = $_POST['pass'] ?? $_POST['admin_pass']; // Usa la contraseña adecuada

    // Verificar que la contraseña sea requerida para administrador
    if ($rol === 'administrador') {
        // Comprobar si la contraseña ingresada coincide con la contraseña fija
        if ($pass !== ADMIN_PASSWORD) {
            echo "Contraseña incorrecta para el rol de administrador.";
            exit();
        }
    }

    // Hashear la contraseña
    $hashed_pass = password_hash($pass, PASSWORD_DEFAULT);
   
    // Preparar la consulta SQL para insertar en la tabla de usuario
    $stmt = $conn->prepare("INSERT INTO usuario (id, nombre, correo, num_telefono, rol, pass) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $id, $nombre, $correo, $num_telefono, $rol, $hashed_pass);
    
    // Ejecutar la inserción en la tabla usuario
    if ($stmt->execute()) {
        // Manejar datos adicionales si el rol es estudiante
        if ($rol === 'estudiante') {
            $grado = $_POST['grado'] ?? '';
            $grupo = $_POST['grupo'] ?? '';
            $acudiente1 = $_POST['acudiente1'] ?? '';
            $acudiente2 = $_POST['acudiente2'] ?? '';
            $nu_acudiente = $_POST['nu_acudiente'] ?? '';

            // Verificar que grado, grupo y acudientes no estén vacíos
            if (!empty($grado) && !empty($grupo)) {
                // Inserción en la tabla de estudiante_grado_grupo
                $stmtGradoGrupo = $conn->prepare("INSERT INTO estudiante_grado_grupo (id, grado, grupo, acudiente1, acudiente2, nu_acudiente) VALUES (?, ?, ?, ?, ?, ?)");
                $stmtGradoGrupo->bind_param("ssssss", $id, $grado, $grupo, $acudiente1, $acudiente2, $nu_acudiente);
                $stmtGradoGrupo->execute();
                $stmtGradoGrupo->close();
            }
        }
        
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
