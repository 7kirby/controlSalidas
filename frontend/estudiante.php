<!DOCTYPE html>
<html>

<head>
  <title>Perfil del Estudiante</title>
  <meta charset="utf-8">
  <link rel="stylesheet" href="estudiante.css">
  <script>
    function mostrarCamposSalidaDeCampo() {
        var motivoSelect = document.getElementById("motivo");
        var camposSalida = document.getElementById("campos-salida");
        var nombreSalida = document.getElementById("nombre_salida");
        var ubicacion = document.getElementById("ubicacion");

        if (motivoSelect.value === "salida_de_campo") {
            camposSalida.style.display = "block";
            // Hacer que los campos sean obligatorios
            nombreSalida.setAttribute("required", true);
            ubicacion.setAttribute("required", true);
        } else {
            camposSalida.style.display = "none";
            // Remover el atributo required de los campos
            nombreSalida.removeAttribute("required");
            ubicacion.removeAttribute("required");
        }
    }
  </script>
</head>

<body>
    <header>
        <?php
        session_start(); // Iniciar la sesión

        // Verificar si la sesión está activa
        if (isset($_SESSION['nombre'])) {
            $nombre_usuario = htmlspecialchars($_SESSION['nombre']);
        } else {
            $nombre_usuario = "Usuario no encontrado";
        }

        // Verificar si la sesión está activa
        if (!isset($_SESSION['id'])) {
            echo "<p>No has iniciado sesión. Por favor, inicie sesión.</p>";
            exit();
        }

        $user_id = $_SESSION['id']; // ID del estudiante en la sesión

        include '../backend/connection.php'; // Incluir archivo de conexión

        // Consulta para obtener el grado y grupo del estudiante
        $sql = "SELECT eg.grado, eg.grupo 
                FROM estudiante_grado_grupo eg 
                WHERE eg.id = ?";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        // Obtener grado y grupo
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $grado = htmlspecialchars($row["grado"]);
            $grupo = htmlspecialchars($row["grupo"]);
        } else {
            $grado = "No disponible";
            $grupo = "No disponible";
        }

        $stmt->close();
        ?>
        <h2><?php echo "Bienvenido <br>$nombre_usuario"; ?></h2>
        <h3><?php echo "Grado: $grado | Grupo: $grupo"; ?></h3>
        
        <nav>
            <ul>
                <li><a href="index.html">Inicio</a></li>
                <li><a href="registro.html">Cerrar sesión</a></li>
                <li><a href="../backend/salidas_estudiante.php">Salidas</a></li>
            </ul>
        </nav>
    </header>

    <table>
        <form method="POST"> 
            <h1> Salida</h1>
            <!-- Campo de DNI, visible pero no editable -->
            <input type="number" placeholder="DNI" name="id" value="<?php echo $user_id; ?>" readonly required /><br><br>
            <input type="date" placeholder="Fecha" name="fecha" required /><br><br>
            <input type="time" placeholder="Hora" name="hora" required /><br><br>
            <select id="motivo" name="motivo" class="selector" onchange="mostrarCamposSalidaDeCampo()" required>
                <option value="" selected disabled>Motivo</option>
                <option value="malestar">Malestar</option>
                <option value="cita_medica">Cita Médica</option>
                <option value="salida_de_campo">Salida de campo</option>
            </select><br><br>
            
            <div id="campos-salida" style="display:none;">
                <h3>Detalles de la Salida de Campo</h3>
                <input type="text" id="nombre_salida" placeholder="Nombre de la salida" name="nombre_salida" /><br><br>
                <input type="text" id="ubicacion" placeholder="Ubicación" name="ubicacion" /><br><br>
            </div>

            <button>Enviar</button>
        </form>

        <?php
        if ($_SERVER["REQUEST_METHOD"] == 'POST') {
            // Obtener los datos del formulario
            $id = $_POST['id']; // El ID ahora se toma del campo que está predefinido
            $fecha = $_POST['fecha'];
            $hora = $_POST['hora'];
            $motivo = $_POST['motivo'];
            $nombre_salida = isset($_POST['nombre_salida']) ? $_POST['nombre_salida'] : '';
            $ubicacion = isset($_POST['ubicacion']) ? $_POST['ubicacion'] : '';

            // Verificar que el motivo no esté vacío
            if (empty($motivo)) {
                echo "<p style='color:red;'>Por favor, selecciona un motivo.</p>";
            } else {
                // Preparar la consulta SQL
                $stmt = $conn->prepare("INSERT INTO salidas (id, fecha, hora, motivo, nombre_salida, ubicacion) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("isssss", $id, $fecha, $hora, $motivo, $nombre_salida, $ubicacion);

                // Ejecutar la inserción
                if ($stmt->execute()) {
                    // Redirigir a la misma página para evitar reenvío de datos
                    header("Location: " . $_SERVER['PHP_SELF']);
                    exit();
                } else {
                    echo "Error al insertar registro: " . $stmt->error;
                }

                // Cerrar la declaración
                $stmt->close();
            }
        }
        ?>
    </table>

</body>

</html>














