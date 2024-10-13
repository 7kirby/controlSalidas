<?php
include 'connection.php';
session_start();

// Verificar que se haya recibido el ID y que sea un entero
if (isset($_GET['id']) && filter_var($_GET['id'], FILTER_VALIDATE_INT)) {
    $id = intval($_GET['id']);

    // Eliminar registros dependientes
    $deleteGradoGrupoSql = "DELETE FROM estudiante_grado_grupo WHERE id = ?";
    $deleteGradoGrupoStmt = $conn->prepare($deleteGradoGrupoSql);
    $deleteGradoGrupoStmt->bind_param("i", $id);
    $deleteGradoGrupoStmt->execute();
    $deleteGradoGrupoStmt->close();

    // Consulta para borrar el usuario
    $sql = "DELETE FROM usuario WHERE id = ?";
    $stmt = $conn->prepare($sql);
    
    if ($stmt) {
        $stmt->bind_param("i", $id);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            $_SESSION['mensaje'] = "Registro borrado con éxito";
        } else {
            $_SESSION['error'] = "Error al borrar el registro: " . $stmt->error;
        }

        $stmt->close();
    } else {
        $_SESSION['error'] = "Error al preparar la consulta: " . $conn->error;
    }
} else {
    $_SESSION['error'] = "No se ha proporcionado un ID válido.";
}

// Cerrar conexión
$conn->close();
header("Location: ../frontend/read.php");
exit();
?>

