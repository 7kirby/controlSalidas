<?php
// Conectar a la base de datos
include '../backend/connection.php';

// Obtener el ID del estudiante desde la URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0; // Cambia a 0 si no hay ID

// Consulta para obtener el nombre del estudiante
$sql = "SELECT nombre FROM usuario WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

$nombreEstudiante = ""; // Inicializar variable

if ($result->num_rows > 0) {
    $estudiante = $result->fetch_assoc();
    $nombreEstudiante = $estudiante['nombre']; // Acceder correctamente al nombre
} else {
    $nombreEstudiante = "Estudiante no encontrado"; // Mensaje si no se encuentra
}

$conn->close(); // Cerrar conexión
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <title>Estudiantes IERG</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="estudiante.css">
</head>
<body>
<header> <!-- Encabezado -->
    <h1>Perfil del Estudiante</h1>
    <nav>
        <ul>
            <li><a href="index.html">Inicio</a></li>
        </ul>
    </nav>
</header>
<main>
    <h2><?php echo htmlspecialchars($nombreEstudiante); ?></h2> <!-- Mostrar el nombre del estudiante -->
</main>
</body>
</html>

