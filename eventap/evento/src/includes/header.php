<?php
include 'conexion.php'; 

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['correo'])) {
    header('Location: index.php');
    exit;
}

$correo = $_SESSION['correo'];

$query_id = "SELECT id FROM usuarios WHERE correo = ?";
$stmt_id = $conexion->prepare($query_id);

if ($stmt_id === false) {
    die("Error al preparar la consulta: " . $conexion->error);
}

$stmt_id->bind_param("s", $correo);

if (!$stmt_id->execute()) {
    die("Error al ejecutar la consulta: " . $stmt_id->error);
}

$stmt_id->bind_result($usuario_id);
$stmt_id->fetch();
$stmt_id->close();

if (empty($usuario_id)) {
    die("No se encontró el usuario.");
}

$query_foto = "SELECT foto FROM usuarios WHERE id = ?";
$stmt_foto = $conexion->prepare($query_foto);

if ($stmt_foto === false) {
    die("Error al preparar la consulta: " . $conexion->error);
}

$stmt_foto->bind_param("i", $usuario_id);

if (!$stmt_foto->execute()) {
    die("Error al ejecutar la consulta: " . $stmt_foto->error);
}

$stmt_foto->bind_result($foto_usuario);
$stmt_foto->fetch();
$stmt_foto->close();

$foto_usuario = !empty($foto_usuario) ? $foto_usuario : '../img/foto.png';
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Event App</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
  <link rel="stylesheet" href="CSS/menu1.css">
  <link rel="icon" href="../img/logocel.png" >
  <link rel="stylesheet" href="../CSS/variables.css">

<style>
  body {
    margin: 0;
    padding: 0;
  }
  @media (min-width: 1024px){
    .menu .logopc{
      height: 27%;
      width: 100%;
      margin-top:-26%;
      margin-left:0%;
    }
    .logopc .logopc1{
      height: 27%;
      width: 100%;
      margin-top:-26%;
      margin-left:-2%;
    }
    .logocel {
      display: none; 
    }
  }
  .menu {
    height: 100%;
    width: 20%;
    position: fixed;
    top: 0;
    left: 0;
    background-color: var(--negro);
    padding-top: 50px; 
    text-align: left;
    box-shadow: 
                2px 0 3px rgba(0, 0, 0, 0.2), /* Sombra derecha más fina */
                -2px 0 3px rgba(0, 0, 0, 0.2); /* Sombra izquierda más fina */
  }
  .menu .boton{
    font-size: 20px;
    padding: 10px 15px;
    display: block;
    text-decoration: none;
    color: var(--textoblanco);
    border-color: var(--gris);
    border-radius:20px;
    margin-left:4%;
    margin-right: 4%;
    margin-top: 4%;
    
  }
  .menu .boton:hover {
    background-color: var(--negro);
    border: 2px solid var(--negrosecundario);
    color:var(--textoblanco);
    box-shadow: 
                2px 0 3px rgba(0, 0, 0, 0.2), /* Sombra derecha más fina */
                -2px 0 3px rgba(0, 0, 0, 0.2); /* Sombra izquierda más fina */
  }
  .menu .boton:active{
    background-color: var(--negro);
    border-color: var(--gris);
    color:var(--textoblanco);
  }
  .menu .boton i {
    margin-right: 8px; 
  }
  @media (max-width: 1024px) {
    .menu {
      height: 100%;
      width: 10%;
      position: fixed;
      top: 0;
      left: 0;
      padding-top: 50px; 
      text-align: center;
    }
    .logopc {
      display: none; 
    }
    .logocel {
      display: block; 
      width:100%;
      margin-top:-50%;
      margin-bottom:100%;
    }
    .menu .boton {
      padding: 5px;
      font-size: 22px;
    }
    .menu .boton:active{
      color:var(--gris);
    }
    .menu .boton:hover {
      color:var(--gris);
      border:0px;
    }
    .menu .boton i {
      margin-right: 0; 
    }
    .menu .boton span {
      display: none; 
    }
  }
  @media (max-width: 768px) {
 .logocel {
      display:none; 
    }
    .menu {
      width: 100%;
      height: 10%;
      bottom: 0;
      top: auto;
      padding-top: 0;
      display: flex;
      justify-content: space-around;
      align-items: center;
    }
    .menu .boton {
      padding: 5px;
      font-size: 22px;
    }
    .menu .boton:active{
      color:var(--gris);
      border:0px;
    }
    .menu .boton:hover {
      color:var(--gris);
      border:0px;
    }
    .menu .boton i {
      margin-right: 0; 
    }
    .menu .boton span {
      display: none;
    }
  }
  .perfil{
    margin-left:-2%;
    margin-right:2%;
    height: 30px;
    width: 30px;
    object-fit: cover; 
  }
.modal-header{
  background-color: var(--negro);
  color:var(--textoblanco);


}
.modal-body{
color:var(--textoblanco);
background-color: var(--negro);
}

.button {
  background-color: #DD2E44;
  color: white;
  border: none;
  margin-top: 4%;
  cursor: pointer;
}
.modal-content{
  background-color: var(--negro);
  border: 2px solid var(--negrosecundario);
}

.mapa{
  width:45%;
  float:left;
  height:100%;
  height: 450px;
}
.datos{
  float:right;
  width:50%;
  margin-left:1%;
  color:;

}
.eventocrear{
  border-radius: 20px;
            background-color:#DD2E44;
            color: white;
            border: none;
            cursor: pointer;
            
    width:40%;
    height:140%;

}
</style>
</head>
<body>

<div class="menu">
  <a href="#" class="logopc"><img src="../img/logopc.png" class="logopc1"></a>
  <a href="#" ><img src="../img/logocel.png" class="logocel"></a>

  <a href="inicio.php" class="boton" onclick="startHold('button1')"><i class="bi bi-house-door"></i><span>inicio</span></a>
  <a href="miseventos.php" class="boton" onclick="startHold('button2')"><i class="bi bi-clipboard"></i><span>eventos</span></a>
  <a href="#" class="boton" onclick="startHold('button3')" data-toggle="modal" data-target="#modalCrearEvento"><i class="bi bi-plus-square"></i><span>crear</span></a>
  <a href="perfill.php" class="boton"><img src="<?php echo $foto_usuario; ?>" class="perfil rounded-circle"><span>perfil</span></a>
  <a href="#" class="boton"><i class="bi bi-list"></i><span>mas</span></a>
  <a href="../php/cerrar_sesion.php" class="boton" onclick="confirmarCerrarSesion(event)"><i class="bi bi-box-arrow-in-left"></i> <span>Cerrar sesión</span></a>
</div>
<div class="menu1">
</div>



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
                                <div id="mapCrear" class="mapa mb-3"></div>
                                <div class="datos">    
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
                                            <button type="submit" class="eventocrear">Crear Evento</button>
                                        </div>
                                    </div>
                                  </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
        function confirmarCerrarSesion(event) {
            if (!confirm("¿Estás seguro de que quieres cerrar sesión?")) {
                event.preventDefault(); 
            }
        }
   
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
  function changeIcon(buttonId) {
    var icon = document.getElementById("icon" + buttonId);
    // NO ANDA!!!!NO ANDA!!!!NO ANDA!!!!NO ANDA!!!!
    switch (buttonId) {
      case 'button1':
        icon.classList.remove('bi-house-door');
        icon.classList.add('bi-house-door-fill');
        break;
      case 'button2':
        icon.classList.remove('bi-clipboard');
        icon.classList.add('bi-clipboard-fill');
        break;
      case 'button3':
        icon.classList.remove('bi-plus-square');
        icon.classList.add('bi-plus-square-fill');
        break;
      default:
        break;
    }
  }
</script>
</body>
</html>
