<?php
session_start();
include '../includes/conexion.php';

if (!isset($_SESSION['correo'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['evento_id'], $_POST['nombre_evento'], $_POST['localidad'], $_POST['capacidad'], $_POST['hora'], $_POST['descripcion'], $_POST['latitud'], $_POST['longitud'])) {
    $evento_id = $_POST['evento_id'];
    $nombre = $_POST['nombre_evento'];
    $capacidad = $_POST['capacidad'];
    $localidad = $_POST['localidad'];
    $hora = $_POST['hora'];
    $descripcion = $_POST['descripcion'];
    $latitud = $_POST['latitud'];
    $longitud = $_POST['longitud'];

    $sql_actualizar_punto = "UPDATE puntos SET latitud = '$latitud', longitud = '$longitud' WHERE id = (SELECT puntos_id FROM eventos WHERE id = '$evento_id')";

    if ($conexion->query($sql_actualizar_punto) === TRUE) {
        $sql_actualizar_evento = "UPDATE eventos SET nombre_evento = '$nombre', capacidad = '$capacidad', localidad = '$localidad', hora = '$hora', descripcion = '$descripcion' WHERE id = '$evento_id'";

        if ($conexion->query($sql_actualizar_evento) === TRUE) {
            echo "<script> alert('Evento actualizado exitosamente'); window.history.back(); </script>";
        } else {
            echo "Error al actualizar el evento: " . $conexion->error;
        }
    } else {
        echo "Error al actualizar el punto: " . $conexion->error;
    }

    $conexion->close();
} else {
    echo "Error: Datos incompletos o método de solicitud incorrecto";
}
?>
