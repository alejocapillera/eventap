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
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Perfil de Usuario</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
  <link href="https://cdn.jsdelivr.net/bootstrap-icons/1.8.1/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background-color: #23272a;
    }
    .cont {
            width: 100%;

        }
    .card {
      width: 130%;
      height: 100vh;
      background-color: #23272a;
      margin-top: -10%;
      margin-left: 35%;

    }
    .profile-container {
      background-color: #23272a;
      position: relative;
      padding: 0px;
    }
    .profile-pic {
      margin-top: -8%;
      width: 150px;
      height: 150px;
      object-fit: cover;
      border-radius: 50%;
      border: 5px solid #fff;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
    .banner {
      width: 100%;
      height: 300px;
      object-fit: cover;
    }
    .card-header {
      background-color: #007bff;
      color: white;
    }
    .btn-primary {
      background-color: #007bff;
      border: none;
    }
    .edit-btn {
      position: absolute;
      top: 10px;
      right: 10px;
      background-color: transparent;
      border: none;
      color: white;
    }   

.modal-body{
color:white;
background-color: #343a40;
}

.button {
  background-color: #DD2E44;
  color: white;
  border: none;
  margin-top: 4%;
  cursor: pointer;
}
.modal-content{
  background-color: #343a40;
}
.modal-dialog-scrollable{
    scrollbar-width: thin;
    scrollbar-color: #DD2E44 #343a40; 
}
.mostrar-event{
                background-color: #1a1c1f;
                color: white;
                width: 80%;
                height: auto; /* Ajuste automático para contenido variable */
                margin-top: 0;
                margin-bottom: 3%;
                margin-left: 40%;
            }
  </style>
</head>
<body>
<div class="container-fluid">
    <header class="d-flex align-items-center mb-3">
        <div class="card">
          <?php if ($banner_usuario): ?>
          <div class="form-group text-center">
            <img src="<?php echo htmlspecialchars($banner_usuario); ?>" alt="Banner" class="img-fluid banner mb-3">
          </div>
          <?php endif; ?>
          <div class="card-body text-center profile-container">
            <?php if ($foto_usuario): ?>
            <div class="form-group text-center">
              <img src="<?php echo htmlspecialchars($foto_usuario); ?>" alt="Foto de perfil" class="img-fluid profile-pic mb-3">
            </div>
            <?php endif; ?>
            <h5 class="card-title text-primary text-light fs-5"><?php echo htmlspecialchars($nombre_usuario); ?></h5>
            <button id="openEditarPerfilModalBtn" class="edit-btn"><i class="bi bi-pencil"></i></button>
    
          </div>
      </div>
    </div>
  </div>
  <!--MODAL EDITAR PERFIL PERRO -->
    <div class="modal fade " id="editarPerfilModal" tabindex="-1" role="dialog" aria-labelledby="editarPerfilModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-light" id="editarPerfilModalLabel">Editar Perfil</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editarPerfilForm" action="../php/actualizar_perfil.php" method="POST" enctype="multipart/form-data">
                        <div class="form-group text-center">
                            <?php if ($banner_usuario): ?>
                                <img src="<?php echo htmlspecialchars($banner_usuario); ?>" alt="Banner" class="img-fluid banner mb-3">
                            <?php endif; ?>
                            <input type="file" id="banner" name="banner" class="form-control-file ">
                        </div>
                        <div class="form-group text-center">
                            <?php if ($foto_usuario): ?>
                                <img src="<?php echo htmlspecialchars($foto_usuario); ?>" alt="Foto de perfil" class="img-fluid profile-pic mb-3">
                            <?php endif; ?>
                            <input type="file" id="foto" name="foto" class="form-control-file">
                        </div>
                        <div class="form-group">
                            <label for="nombre">Nombre de Usuario:</label>
                            <input type="text" id="nombre" name="nombre" class="form-control" value="<?php echo htmlspecialchars($nombre_usuario); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="correo">Correo Electrónico:</label>
                            <input type="email" id="correo" name="correo" class="form-control" value="<?php echo htmlspecialchars($correo_usuario); ?>" required>
                        </div>
                        <button type="submit" class="btn btn-danger btn-block">Guardar Cambios</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="evento-cont">

            <div class="col-md-8">
                <div class="">
                    <?php
                    $query_eventos = "SELECT e.id, e.nombre_evento, e.localidad, e.capacidad, e.hora, e.descripcion, p.latitud, p.longitud, u.usuario
                        FROM eventos AS e
                        INNER JOIN puntos AS p ON e.puntos_id = p.id
                        INNER JOIN usuarios AS u ON e.usuario_id = u.id
                        WHERE e.usuario_id = '$usuario_id'
                        ORDER BY e.id DESC";

                    $result_eventos = $conexion->query($query_eventos);

                    if ($result_eventos->num_rows > 0) {

                        while ($row_evento = $result_eventos->fetch_assoc()) {
                            echo '<div class="mostrar-event">';
                            echo "<h5 class='card-title'>Usuario: " . $row_evento["usuario"] . "</h5>";
                            echo "<p class='card-text'>Nombre del Evento: " . $row_evento["nombre_evento"] . "</p>";
                            echo "<p class='card-text'>Capacidad: " . $row_evento["capacidad"] . "</p>";
                            echo "<p class='card-text'>Localidad: " . $row_evento["localidad"] . "</p>";
                            echo "<p class='card-text'>Día y Hora: " . $row_evento["hora"] . "</p>";
                            echo "<p class='card-text'>Descripción: " . $row_evento["descripcion"] . "</p>";

                            echo '<div id="map-' . $row_evento["id"] . '" class="map-evento" style="height:200px;"></div>';

                            echo "<button class='btn btn-danger' onclick='eliminarEvento(" . $row_evento["id"] . ")'>Eliminar</button>";
                            echo "<a href='#' class='btn btn-primary' data-toggle='modal' data-target='#modalEditarEvento' data-evento-id='" . $row_evento['id'] . "' data-nombre-evento='" . $row_evento['nombre_evento'] . "' data-localidad='" . $row_evento['localidad'] . "' data-hora='" . $row_evento['hora'] . "' data-descripcion='" . $row_evento['descripcion'] . "' data-latitud='" . $row_evento['latitud'] . "' data-longitud='" . $row_evento['longitud'] . "'>Editar</a>";

                            echo '<script>
                                    var map' . $row_evento["id"] . ' = L.map("map-' . $row_evento["id"] . '").setView([' . $row_evento["latitud"] . ', ' . $row_evento["longitud"] . '], 13);
                                    L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
                                        attribution: "&copy; <a href=\"https://www.openstreetmap.org/copyright\">OpenStreetMap</a> contributors"
                                    }).addTo(map' . $row_evento["id"] . ');
                                    L.marker([' . $row_evento["latitud"] . ', ' . $row_evento["longitud"] . ']).addTo(map' . $row_evento["id"] . ');
                                  </script>';

                            echo '</div>';
                        }
                    } else {
                        echo "No se encontraron eventos creados por el usuario.";
                    }
                    ?>

                <div class="modal fade" id="modalEditarEvento" tabindex="-1" role="dialog" aria-labelledby="modalEditarEventoLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalEditarEventoLabel">Editar Evento</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form id="formEditarEvento" action="../php/actualizar_evento.php" method="post">
                                    <input type="hidden" id="evento_id" name="evento_id">
                                    <div class="form-group">
                                        <label for="nombre_evento">Nombre del Evento:</label>
                                        <input type="text" class="form-control" id="editar_nombre_evento" name="nombre_evento">
                                    </div>
                                    <div class="form-group ">
                                        <label for="capacidad">Capacidad:</label>
                                        <input type="number" name="capacidad" id="capacidad" class="form-control" min="15" max="100000000">
                                    </div>
                                    <div class="form-group">
                                        <label for="localidad">Localidad del Evento:</label>
                                        <input type="text" class="form-control" id="editar_localidad" name="localidad">
                                    </div>
                                    <div class="form-group">
                                        <label for="hora">Hora del Evento:</label>
                                        <input type="datetime-local" class="form-control" id="editar_hora" name="hora">
                                    </div>
                                    <div class="form-group">
                                        <label for="descripcion">Descripción del Evento:</label>
                                        <textarea class="form-control" id="editar_descripcion" name="descripcion"></textarea>
                                    </div>
                                    <div id="mapEditar" style="height: 300px;"></div>
                                    <input type="hidden" id="editar_latitud" name="latitud">
                                    <input type="hidden" id="editar_longitud" name="longitud">
                                    <div class="form-group text-center">
                                        <button type="submit" class="btn button">Actualizar</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>

function eliminarEvento(eventoId) {
    if (confirm("¿Estás seguro de que deseas eliminar este evento?")) {
        console.log("Enviando solicitud para eliminar evento ID:", eventoId);
        $.ajax({
            url: '../php/eliminar_evento.php',
            type: 'POST',
            data: { id: eventoId },
            success: function(response) {
                console.log("Respuesta del servidor:", response);
                if (response == 'success') {
                    alert('Evento eliminado con éxito.');
                    location.reload();
                } else {
                    alert('Error al eliminar el evento.');
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error("Error en la solicitud AJAX:", textStatus, errorThrown);
                alert('Error al eliminar el evento.');
            }
        });
    }
}

function cargarDatosEvento(evento_id, nombre_evento, capacidad, localidad, hora, descripcion, latitud, longitud) {
    $('#evento_id').val(evento_id);
    $('#editar_nombre_evento').val(nombre_evento);
    $('#editar_capacidad').val(capacidad);
    $('#editar_localidad').val(localidad);
    $('#editar_hora').val(hora);
    $('#editar_descripcion').val(descripcion);
    $('#editar_latitud').val(latitud);
    $('#editar_longitud').val(longitud);
}

$('#modalEditarEvento').on('show.bs.modal', function (event) {
    var enlace = $(event.relatedTarget);
    var evento_id = enlace.data('evento-id');
    var nombre_evento = enlace.data('nombre-evento');
    var capacidad = enlace.data('capacidad');
    var localidad = enlace.data('localidad');
    var hora = enlace.data('hora');
    var descripcion = enlace.data('descripcion');
    var latitud = enlace.data('latitud');
    var longitud = enlace.data('longitud');
    cargarDatosEvento(evento_id, nombre_evento, capacidad, localidad, hora, descripcion, latitud, longitud);
});

$('#modalEditarEvento').on('shown.bs.modal', function (event) {
    var enlace = $(event.relatedTarget);
    var latitud = parseFloat(enlace.data('latitud'));
    var longitud = parseFloat(enlace.data('longitud'));

    window.mapEditar = L.map('mapEditar').setView([latitud, longitud], 13);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(window.mapEditar);

    var marker = L.marker([latitud, longitud]).addTo(window.mapEditar);
    marker.bindPopup("Punto Fijado.").openPopup();

    window.mapEditar.on('click', function (event) {
        latitud = event.latlng.lat; 
        longitud = event.latlng.lng; 

        if (marker) {
            window.mapEditar.removeLayer(marker);
        }

        marker = L.marker([latitud, longitud]).addTo(window.mapEditar);
        marker.bindPopup("Punto Fijado.").openPopup();

        $('#editar_latitud').val(latitud);
        $('#editar_longitud').val(longitud);
    });
});



$('#modalCrearEvento').on('shown.bs.modal', function () {

    window.mapCrear = L.map('mapCrear').setView([-34.6037, -58.3816], 6);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(window.mapCrear);

    var markerCrear;
    window.mapCrear.on('click', function (event) {
        var latitud = event.latlng.lat;
        var longitud = event.latlng.lng;

        if (markerCrear) {
            window.mapCrear.removeLayer(markerCrear);
        }

        markerCrear = L.marker([latitud, longitud]).addTo(window.mapCrear);
        markerCrear.bindPopup("Punto Fijado.").openPopup();

        $('#latitudCrear').val(latitud);
        $('#longitudCrear').val(longitud);
    });
});

        $(document).ready(function() {
            $('#openEditarPerfilModalBtn').click(function() {
                $('#editarPerfilModal').modal('show');
            });
        });
    </script>
</body>
</html>
