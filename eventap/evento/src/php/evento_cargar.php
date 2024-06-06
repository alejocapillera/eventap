<?php
session_start();
include '../includes/conexion.php';

if ($conexion->connect_error) {
    die("Error en la conexión a la base de datos: " . $conexion->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['nombre_evento'], $_POST['capacidad'], $_POST['localidad'], $_POST['hora'], $_POST['descripcion'], $_POST['latitud'], $_POST['longitud'], $_POST['usuario_id'])) {
    $nombre = $_POST['nombre_evento'];
    $capacidad = $_POST['capacidad']; 
    $localidad = $_POST['localidad'];
    $hora = $_POST['hora'];
    $descripcion = $_POST['descripcion'];
    $latitud = $_POST['latitud'];
    $longitud = $_POST['longitud'];
    $usuario_id = $_POST['usuario_id']; 

    $hora = date('Y-m-d H:i:s', strtotime($hora));

    $sql_punto = "INSERT INTO puntos (latitud, longitud) VALUES ('$latitud', '$longitud')";
    if ($conexion->query($sql_punto) === TRUE) {
        $puntos_id = $conexion->insert_id;

        $sql_evento = "INSERT INTO eventos (nombre_evento, capacidad, localidad, hora, descripcion, puntos_id, usuario_id) 
                       VALUES ('$nombre', '$capacidad', '$localidad', '$hora', '$descripcion', '$puntos_id', '$usuario_id')"; 

        if ($conexion->query($sql_evento) === TRUE) {
            echo "<script> alert('Nuevo evento creado exitosamente'); window.history.back(); </script>";
        } else {
            echo "<script> alert('Error al crear el evento:'); window.history.back(); </script>" . $conexion->error;
        }
    } else {
        echo "Error al guardar el punto: ". $conexion->error;
    }

    $conexion->close();
} else {
    echo "<script> alert('Error: Datos incompletos o método de solicitud incorrecto'); window.history.back(); </script>";
}
?>
