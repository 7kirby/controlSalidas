<!DOCTYPE html>
<html>

<head>
  <title>Perfil del Estudiante</title>
  <meta charset="utf-8">
  <link rel="stylesheet" href="estudiante.css">
</head>

<body>
    <header>
        <h1>Perfil de estudiante</h1>
        <nav>
            <ul>
                <li><a href="index.html">Inicio</a></li>
            </ul>
        </nav>
    </header>

    <table>
        <?php
        include '../backend/connection.php'; // Incluir archivo de conexión
        include '../backend/login.php';
        session_start();

        // Verificar si la sesión está activa
        if (!isset($_SESSION['id'])) {
            echo "<tr><td>No has iniciado sesión. Por favor, inicie sesión.</td></tr>";
            exit();
        }

        $user_id = $_SESSION['id'];

        // Consulta para obtener el nombre del usuario
        $sql = "SELECT u.nombre, eg.grado, eg.grupo 
                FROM usuario u 
                JOIN estudiante_grado_grupo eg ON u.id = eg.usuario_id 
                WHERE u.id = ?";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        // Mostrar el nombre y datos del estudiante
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<tr><td>Nombre: " . htmlspecialchars($row["nombre"]) . "</td></tr>";
                echo "<tr><td>Grado: " . htmlspecialchars($row["grado"]) . "</td></tr>";
                echo "<tr><td>Grupo: " . htmlspecialchars($row["grupo"]) . "</td></tr>";
            }
        } else {
            echo "<tr><td>No se encontraron resultados.</td></tr>";
        }

        $stmt->close();
        $conn->close();
        ?>
    </table>
</body>

</html>





