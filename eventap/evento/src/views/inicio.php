<?php
session_start();
include '../includes/conexion.php';
include '../includes/header.php';

if (!isset($_SESSION['correo'])) {
    header('Location: index.php');
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
<html lang="en">
<head>
    <title>inicio</title>
    <link rel="stylesheet" href="../CSS/inicio1.css">
    <link rel="stylesheet" href="../CSS/variables.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <link rel="stylesheet" href="../CSS/variables.css"> <!-- Archivo de variables CSS -->
    <link rel="stylesheet" href="../CSS/inicio.css"> <!-- Archivo de variables CSS -->

</head>
<body>

    <div class="cont">
        <div class="lateral">
        <button id="light-theme-btn">Tema Claro</button>
<button id="dark-theme-btn">Tema Oscuro</button>
<button id="custom-theme-btn">Tema Personalizado</button>
<label class="switch">
    <input type="checkbox" id="theme-switch">
    <span class="slider"></span>
</label>

   
        

        </div>
        <div class="cont-event">
            <?php 
            $query = "SELECT e.id, e.nombre_evento, e.capacidad, e.localidad, e.hora, e.descripcion, p.latitud, p.longitud, u.usuario, u.foto
            FROM eventos AS e
            INNER JOIN puntos AS p ON e.puntos_id = p.id
            INNER JOIN usuarios AS u ON e.usuario_id = u.id
            ORDER BY e.id DESC";

            $result = $conexion->query($query);

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $foto_perfil = !empty($row["foto"]) ? $row["foto"] : '../img/foto.png'; 
                    echo '<div class="evento">
                            <div class="superior">
                                <a href="#" class="btnusuario">
                                    <img src="' . $foto_perfil  .  '" class="usuariofoto rounded-circle" <p class="nombreusuario">' . $row["usuario"] . '</p>
                                    
                                    
                                </a>
                            </div>
                            <div class="inferior">';
                    
                    echo '<div id="map-' . $row["id"] . '" class="map-evento"></div>';
                    echo '<p class="eventtitulo">' . $row["nombre_evento"] . '</p>';
                    echo '<p class="texto1"><i class="bi bi-geo-alt-fill"></i>' . $row["localidad"] . '</p>';
                    echo '<p class="texto1"><i class="bi bi-clock-fill"></i> ' . $row["hora"] . '</p>';
                    echo '<div class="descripcion"><p class="texto">Descripción: ' . $row["descripcion"] . '</p></div>';
                    echo '<button class="unirse" class="btn btn-unirse" onclick="unirseEvento(' . $row["id"] . ')">Unirse</button>';


                    echo '<script>
                            var map' . $row["id"] . ' = L.map("map-' . $row["id"] . '").setView([' . $row["latitud"] . ', ' . $row["longitud"] . '], 13);
                            L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
                                attribution: "&copy; <a href=\"https://www.openstreetmap.org/copyright\">OpenStreetMap</a> contributors"
                            }).addTo(map' . $row["id"] . ');
                            L.marker([' . $row["latitud"] . ', ' . $row["longitud"] . ']).addTo(map' . $row["id"] . ');
                          </script>';

                    echo "</div></div>";
                }
            } else {
                echo "No se encontraron eventos.";
            }

            $conexion->close();
            ?>
        </div>
    </div>
</body>
</html>
 <script src="../js/tema.js"></script> 


<script>
function unirseEvento(eventoId) {
    var form = document.createElement('form');
    form.setAttribute('method', 'post');
    form.setAttribute('action', '../php/unirse_evento.php');
    form.style.display = 'none';

    var eventoIdInput = document.createElement('input');
    eventoIdInput.setAttribute('type', 'hidden');
    eventoIdInput.setAttribute('name', 'evento_id');
    eventoIdInput.setAttribute('value', eventoId);
    form.appendChild(eventoIdInput);

    document.body.appendChild(form);

    form.submit();
}
</script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
