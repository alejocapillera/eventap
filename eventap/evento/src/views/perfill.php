<?php
session_start();
include '../includes/conexion.php';
include '../includes/header.php';

if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../../index.php');
    exit;
}

$usuario_id = $_SESSION['usuario_id'];

$query_usuario = "SELECT usuario, correo, foto, banner FROM usuarios WHERE id = ?";
$stmt_usuario = $conexion->prepare($query_usuario);

if ($stmt_usuario === false) {
    die("Error al preparar la consulta: " . $conexion->error);
}

$stmt_usuario->bind_param("i", $usuario_id);

if (!$stmt_usuario->execute()) {
    die("Error al ejecutar la consulta: " . $stmt_usuario->error);
}

$result_usuario = $stmt_usuario->get_result();

if ($result_usuario->num_rows > 0) {
    $row_usuario = $result_usuario->fetch_assoc();
    $nombre_usuario = $row_usuario['usuario'];
    $correo_usuario = $row_usuario['correo'];
    $foto_usuario = $row_usuario['foto'];
    $banner_usuario = $row_usuario['banner'];
} else {
    echo "Error: No se encontró el usuario en la base de datos.";
    exit;
}

$stmt_usuario->close();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <title>Document</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../CSS/variables.css">
     <link rel="stylesheet" href="../CSS/perfil.css">
</head>
<body>
    <div class="cont">
        <div class="superior">
            <?php if ($banner_usuario): ?>
            <div class="banner">
            <img src="<?php echo htmlspecialchars($banner_usuario); ?>" alt="Banner" class="img-fluid banner1 mb-3">
            </div>
            <?php endif; ?>
            <div class="bannerinferior">
            <?php if ($foto_usuario): ?>
                <div class="perfil">
                <img src="<?php echo htmlspecialchars($foto_usuario); ?>" alt="Foto de perfil" class="perfill profile-pic mb-3">
                </div>
                <?php endif; ?>
                <a href="#" class="editarperfil">editar perfil</a>
            </div>
            <p class="nickname">Alejocapo</p>
            <div class="seleccionar">
            <a id="publicados" class="seleccionar1">Publicados</a>
            <a id="unidos" class="seleccionar2">Unidos</a>
            </div>
        </div>
        <div class="inferior">
            <div class="publicadoss">
        <?php
        $query_eventos = "SELECT e.id, e.nombre_evento, e.localidad, e.capacidad, e.hora, e.descripcion, p.latitud, p.longitud, u.usuario, u.foto
            FROM eventos AS e
            INNER JOIN puntos AS p ON e.puntos_id = p.id
            INNER JOIN usuarios AS u ON e.usuario_id = u.id
            WHERE e.usuario_id = '$usuario_id'
            ORDER BY e.id DESC";

        $result_eventos = $conexion->query($query_eventos);

        if ($result_eventos->num_rows > 0) {
            while ($row_evento = $result_eventos->fetch_assoc()) {
                $foto_perfil = !empty($row_evento["foto"]) ? $row_evento["foto"] : '../img/foto.png'; 
                
                echo '<div class="evento">
                        <div class="superior">
                            <a href="#" class="btnusuario">
                                <img src="' . $foto_perfil  .  '" class="usuariofoto rounded-circle" <p class="nombreusuario">' . $row_evento["usuario"] . '</p>
                            </a>
                        </div>
                        <div class="inferior">';

                echo '<div id="map-' . $row_evento["id"] . '" class="map-evento"></div>';
                echo '<p class="eventtitulo">' . $row_evento["nombre_evento"] . '</p>';
                echo '<p class="texto1"><i class="bi bi-geo-alt-fill"></i>' . $row_evento["localidad"] . '</p>';
                echo '<p class="texto1"><i class="bi bi-clock-fill"></i> ' . $row_evento["hora"] . '</p>';
                echo '<div class="descripcion"><p class="texto">Descripción: ' . $row_evento["descripcion"] . '</p></div>';
                echo '<button class="btn btn-danger" onclick="eliminarEvento(' . $row_evento["id"] . ')">Eliminar</button>';
                echo '<a href="#" class="btn btn-primary" data-toggle="modal" data-target="#modalEditarEvento" data-evento-id="' . $row_evento['id'] . '" data-nombre-evento="' . $row_evento['nombre_evento'] . '" data-localidad="' . $row_evento['localidad'] . '" data-hora="' . $row_evento['hora'] . '" data-descripcion="' . $row_evento['descripcion'] . '" data-latitud="' . $row_evento['latitud'] . '" data-longitud="' . $row_evento['longitud'] . '">Editar</a>';

                echo '<script>
                        var map' . $row_evento["id"] . ' = L.map("map-' . $row_evento["id"] . '").setView([' . $row_evento["latitud"] . ', ' . $row_evento["longitud"] . '], 13);
                        L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
                            attribution: "&copy; <a href=\"https://www.openstreetmap.org/copyright\">OpenStreetMap</a> contributors"
                        }).addTo(map' . $row_evento["id"] . ');
                        L.marker([' . $row_evento["latitud"] . ', ' . $row_evento["longitud"] . ']).addTo(map' . $row_evento["id"] . ');
                      </script>';

                echo '</div></div>';
            }
        } else {
            echo "No se encontraron eventos creados por el usuario.";
        }
        ?>
    </div>
</div>
            </div>
            <div class="unidosss">
            </div>
        </div>
    </div>
    <script src="../js/tema.js"></script> 
 

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
    document.getElementById('publicados').addEventListener('click', function() {
    document.getElementById('publicadoss').style.display = 'block';
    document.getElementById('unidoss').style.display = 'none';
});

document.getElementById('unidos').addEventListener('click', function() {
    document.getElementById('publicadoss').style.display = 'none';
    document.getElementById('unidoss').style.display = 'block';
});
    </script>
</body>
</html>