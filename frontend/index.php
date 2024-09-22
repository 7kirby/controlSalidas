<?php
include 'connection.php'; // Incluir la conexión a la base de datos

$sql = "SELECT * FROM usuario";
$result = $conn->query($sql);

if ($result->num_rows > 0) {

  // Imprimir cada fila
  while($row = $result->fetch_assoc()) {
    echo "<tr><td>" . $row["id"]. "</td><td>" . $row["nombre"]. "</td><td>" . $row["correo"]. "</td><td>" . $row["pass"]. "</td></tr>";
  }
} else {
  echo "0 results";
}
$conn->close();
?>
