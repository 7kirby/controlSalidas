<?php
include '../backend/connection.php'; // Ajusta la ruta según tu estructura de directorios

// Consulta para obtener los datos
$sql = "SELECT * FROM usuario"; 
$result = $conn->query($sql);

// Verificar si la consulta fue exitosa
if (!$result) {
    die("Error en la consulta: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="read.css"> <!-- Asegúrate de que tu CSS tenga los estilos deseados -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Usuarios</title>
    
</head>
<body>
    <h1>Datos de la Base de Datos</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Correo</th>
                <th>Rol</th>
                <th>Contraseña</th>
                <th>Acciones</th> <!-- Nueva columna para acciones -->
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                // Salida de cada fila
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row["id"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["nombre"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["correo"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["rol"]) . "</td>";
                    echo "<td>Oculto</td>"; // No mostrar la contraseña
                    echo "<td class='action-buttons'>"; // Columna de acciones
                    echo "<button onclick=\"location.href='actualizar.php?id=" . htmlspecialchars($row['pass']) . "'\">Actualizar</button>"; // Botón de actualizar
                    echo "<button onclick=\"if(confirm('¿Estás seguro de que quieres borrar?')) location.href='borrar.php?id=" . htmlspecialchars($row['id']) . "'\">Borrar</button>"; // Botón de borrar
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='6' class='no-data'>No hay datos disponibles</td></tr>";
            }
            ?>
        </tbody>
    </table>
</body>
</html>

<?php
$conn->close(); // Cerrar conexión
?>