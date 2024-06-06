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
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <link rel="stylesheet" href="../css/cargar.css">

</head>
<body>
<div class="container mt-6"> 
    <div class="row justify-content-center">
        <div class="evento-cont">
            <center>
                <button type="button" class="btn button" data-toggle="modal" data-target="#modalCrearEvento">Crear Nuevo Evento</button>
            </center>
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
                        echo "<h2>Eventos Creados</h2>";

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

                <div class="modal fade" id="modalCrearEvento" tabindex="-1" role="dialog" aria-labelledby="modalCrearEventoLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="modalCrearEventoLabel">Crear Nuevo Evento</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form action="../php/evento_cargar.php" method="post">
                                    <div class="form-group row">
                                        <label for="nombre_evento" class="col-sm-3 col-form-label">Nombre del Evento:</label>
                                        <div class="col-sm-9">
                                            <input type="text" name="nombre_evento" id="nombre_evento" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="capacidad" class="col-sm-3 col-form-label">Capacidad:</label>
                                        <div class="col-sm-9">
                                            <input type="number" name="capacidad" id="capacidad" class="form-control" min="15" max="100000000">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="localidad" class="col-sm-3 col-form-label">Localidad del Evento:</label>
                                        <div class="col-sm-9">
                                            <input type="text" name="localidad" id="localidad" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="hora" class="col-sm-3 col-form-label">  Dia y Hora:</label>
                                        <div class="col-sm-9">
                                            <input type="datetime-local" name="hora" id="hora" class="form-control">
                                        </div>
                                    </div>
                                    <div id="mapCrear" class="mb-3" style="height: 300px;"></div>
                                    <div class="form-group row">
                                        <label for="descripcion" class="col-sm-3 col-form-label">Descripción del Evento:</label>
                                        <div class="col-sm-9">
                                            <textarea name="descripcion" id="descripcion" class="form-control"></textarea>
                                        </div>
                                    </div>
                                    <input type="hidden" name="usuario_id" value="<?php echo $usuario_id; ?>">
                                    <input type="hidden" id="latitudCrear" name="latitud">
                                    <input type="hidden" id="longitudCrear" name="longitud">
                                    <div class="form-group row">
                                        <div class="col-sm-12 text-center">
                                            <button type="submit" class="btn button">Crear Evento</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

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

</script>
</body>
</html>
