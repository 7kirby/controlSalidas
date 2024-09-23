<?php
include 'connection.php';

// Verificar que se haya recibido el ID
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Consulta para borrar el usuario
    $sql = "DELETE FROM usuario WHERE id = ?";
    
    // Preparar la consulta
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id); // Asegúrate de que el ID sea un entero

    // Ejecutar la consulta
    if ($stmt->execute()) {
        // Redirigir a la página de listado de usuarios después de borrar
        header("Location: ../frontend/read.php?mensaje=Registro borrado con éxito");
        exit();
    } else {
        echo "Error al borrar el registro: " . $conn->error;
    }

    // Cerrar la declaración
    $stmt->close();
} else {
    echo "No se ha proporcionado un ID válido.";
}

// Cerrar conexión
$conn->close();
?>
