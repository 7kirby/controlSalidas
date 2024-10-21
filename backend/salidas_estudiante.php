<?php
include 'connection.php'; // Incluir la conexión a la base de datos

session_start(); // Iniciar la sesión

// Verificar si la sesión está activa
if (!isset($_SESSION['id'])) {
    echo "<p>No has iniciado sesión. Por favor, inicie sesión.</p>";
    exit();
}

// Obtener el ID del usuario de la sesión
$user_id = $_SESSION['id'];

// Consulta SQL para obtener datos de salidas del usuario actual
$sql_salidas = "SELECT s.*, u.nombre FROM salidas s 
                LEFT JOIN usuario u ON s.id = u.id 
                WHERE s.id = ?";
$stmt = $conn->prepare($sql_salidas);
$stmt->bind_param("i", $user_id); // Asumiendo que id es un número entero
$stmt->execute();
$result_salidas = $stmt->get_result();

if (!$result_salidas) {
    die("Error en la consulta de salidas: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../frontend/read.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Salidas</title>
</head>
<body>
<header>
    <h1>Datos de Salidas</h1>
    <nav>
        <ul>
            <li><a href="../frontend/estudiante.php">Volver</a></li>
        </ul>
    </nav>
</header>
<main>
    <h2>Listado de Salidas</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Fecha</th>
                <th>Hora</th>
                <th>Motivo</th>
                <th>Nombre de la Salida</th>
                <th>Ubicación</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result_salidas->num_rows > 0): ?>
                <?php foreach ($result_salidas as $row): ?>
                    <tr>
                        <td><?= htmlspecialchars($row["id"]) ?></td>
                        <td><?= htmlspecialchars($row["nombre"]) ?></td>
                        <td><?= htmlspecialchars($row["fecha"]) ?></td>
                        <td><?= htmlspecialchars($row["hora"]) ?></td>
                        <td><?= htmlspecialchars($row["motivo"]) ?></td>
                        <td><?= htmlspecialchars($row["nombre_salida"]) ?></td>
                        <td><?= htmlspecialchars($row["ubicacion"]) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan='8'>No hay salidas registradas</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</main>
</body>
</html>

<?php 
$stmt->close();
$conn->close(); 
?>

