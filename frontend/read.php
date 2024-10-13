<?php
include '../backend/connection.php';

// Consulta SQL para obtener datos de usuario y estudiante
$sql = "SELECT u.*, eg.grado, eg.grupo, eg.acudiente1, eg.acudiente2, eg.nu_acudiente 
        FROM usuario u 
        LEFT JOIN estudiante_grado_grupo eg ON u.id = eg.id";
$result = $conn->query($sql);

if (!$result) {
    die("Error en la consulta: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="read.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Usuarios</title>
</head>
<body>
<header>
    <h1>Datos de la Base de Datos</h1>
    <nav>
        <ul>
            <li><a href="registro.html">Volver</a></li>
        </ul>
    </nav>
</header>
<main>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Teléfono</th>
                <th>Correo</th>
                <th>Rol</th>
                <th>Grado</th>
                <th>Grupo</th>
                <th>Acudiente 1</th>
                <th>Acudiente 2</th>
                <th>Número de Acudiente</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php foreach ($result as $row): ?>
                    <tr>
                        <td><?= htmlspecialchars($row["id"]) ?></td>
                        <td><?= htmlspecialchars($row["nombre"]) ?></td>
                        <td><?= htmlspecialchars($row["num_telefono"]) ?></td>
                        <td><?= htmlspecialchars($row["correo"]) ?></td>
                        <td><?= htmlspecialchars($row["rol"]) ?></td>
                        <td><?= htmlspecialchars($row["rol"] === 'estudiante' ? $row["grado"] : '') ?></td>
                        <td><?= htmlspecialchars($row["rol"] === 'estudiante' ? $row["grupo"] : '') ?></td>
                        <td><?= htmlspecialchars($row["rol"] === 'estudiante' ? $row["acudiente1"] : '') ?></td>
                        <td><?= htmlspecialchars($row["rol"] === 'estudiante' ? $row["acudiente2"] : '') ?></td>
                        <td><?= htmlspecialchars($row["rol"] === 'estudiante' ? $row["nu_acudiente"] : '') ?></td>
                        <td>
                            <button onclick="location.href='../backend/actualizar.php?id=<?= htmlspecialchars($row['id']) ?>'">Actualizar</button>
                            <button onclick="if(confirm('¿Estás seguro de que quieres borrar?')) location.href='../backend/borrar.php?id=<?= htmlspecialchars($row['id']) ?>'">Borrar</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan='11'>No hay datos disponibles</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</main>
</body>
</html>

<?php $conn->close(); ?>
