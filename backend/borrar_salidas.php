<?php
include '../backend/connection.php'; // Incluir la conexión a la base de datos

// Verificar si se ha pasado un ID en la URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Preparar la consulta SQL para eliminar la salida
    $stmt = $conn->prepare("DELETE FROM salidas WHERE id = ?");
    $stmt->bind_param("i", $id); // Asumiendo que el ID es un número entero

    // Ejecutar la consulta
    if ($stmt->execute()) {
        // No es necesario devolver un mensaje
        http_response_code(204); // Código de respuesta 204 No Content
    } else {
        http_response_code(500); // Error interno del servidor
    }

    // Cerrar la declaración
    $stmt->close();
} else {
    http_response_code(400); // Solicitud incorrecta
}

// Cerrar la conexión
$conn->close();
header("Location: ../frontend/salida.php");
exit();
?>

