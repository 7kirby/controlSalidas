<?php
include '../backend/connection.php'; // Incluir la conexión a la base de datos

// Consulta SQL para obtener datos de salidas
$sql_salidas = "SELECT s.*, u.nombre FROM salidas s LEFT JOIN usuario u ON s.id = u.id";
$result_salidas = $conn->query($sql_salidas);

if (!$result_salidas) {
    die("Error en la consulta de salidas: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="read.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Salidas</title>
</head>
<body>
<header>
    <h1>Datos de Salidas</h1>
    <nav>
        <ul>
            <li><a href="index.html">Inicio</a></li>
            <li><a href="read.php">Volver</a></li>
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
                <th>Acciones</th>
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
                        <td>
                            <button onclick="if(confirm('¿Estás seguro de que quieres borrar?')) location.href='../backend/borrar_salidas.php?id=<?= htmlspecialchars($row['id']) ?>'">Borrar</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan='8'></td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</main>
</body>
</html>

<?php $conn->close(); ?>



