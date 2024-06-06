<?php
session_start();
include '../includes/conexion.php';
include '../includes/header.php';

if (!isset($_SESSION['correo'])) {
    header('Location: login.php');
    exit;
}

$correo = $_SESSION['correo'];
$query_usuario = "SELECT id FROM usuarios WHERE correo = ?";
$stmt_usuario = $conexion->prepare($query_usuario);
$stmt_usuario->bind_param("s", $correo);
$stmt_usuario->execute();
$result_usuario = $stmt_usuario->get_result();

if ($result_usuario->num_rows > 0) {
    $row_usuario = $result_usuario->fetch_assoc();
    $usuario_id = $row_usuario['id'];
    $_SESSION['usuario_id'] = $usuario_id; 
} else {
    echo "Error: No se encontró el usuario en la base de datos.";
    exit;
}
$stmt_usuario->close();

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <link rel="stylesheet" href="../css/inicio.css">
    <title>Event App</title>
</head>
<body>
<center>
<div class="container mt-6"> 
    <div class="row justify-content-center">
        <div class="evento-cont">
        <div class="col-md-8">
        <?php 
        $user_id = $_SESSION['usuario_id'];
        $sql = "SELECT e.id, e.nombre_evento, e.localidad, e.hora, e.descripcion, p.latitud, p.longitud
                FROM eventos e
                JOIN unidos u ON e.id = u.event_id
                JOIN puntos p ON e.puntos_id = p.id
                WHERE u.user_id = ?
                ORDER BY e.id DESC";

        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        echo "<h1>Eventos a los que te has unido</h1>";

        while ($row = $result->fetch_assoc()) {
            echo "<div class='evento'>";
            echo "<h4>" . htmlspecialchars($row['nombre_evento']) . "</h4>";
            echo "<p>Localidad: " . htmlspecialchars($row['localidad']) . "</p>";
            echo "<p>Hora: " . htmlspecialchars($row['hora']) . "</p>";
            echo "<p>Descripción: " . htmlspecialchars($row['descripcion']) . "</p>";

            echo '<div id="map-' . $row["id"] . '" class="map-evento" style="height:200px;"></div>';
            echo '<button class="btn btn-primary" onclick="salirEvento(' . $row['id'] . ')">Salir del evento</button>';
            echo "</div><br>";

            echo '<script>
            var map' . $row["id"] . ' = L.map("map-' . $row["id"] . '").setView([' . $row["latitud"] . ', ' . $row["longitud"] . '], 13);
            L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
                attribution: "&copy; <a href=\"https://www.openstreetmap.org/copyright\">OpenStreetMap</a> contributors"
            }).addTo(map' . $row["id"] . ');
            L.marker([' . $row["latitud"] . ', ' . $row["longitud"] . ']).addTo(map' . $row["id"] . ');
          </script>';

        }

        $stmt->close();
        $conexion->close();
        ?>

        </div>
    </div>
    </div>
</div>
</center>
<script>
    function salirEvento(eventoId) {
        if (confirm('¿Estás seguro de que quieres salir de este evento?')) {
            window.location.href = '../php/salir_evento.php?event_id=' + eventoId;
        }
    }
</script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
</body>
</html>
