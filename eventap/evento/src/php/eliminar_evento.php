<?php
session_start();
include '../includes/conexion.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_SESSION['usuario_id'])) {
        echo 'error: no user id';
        exit;
    }

    $evento_id = $_POST['id'];
    $usuario_id = $_SESSION['usuario_id'];

    error_log("Evento ID: $evento_id");
    error_log("Usuario ID: $usuario_id");

    $query_unidos = "DELETE FROM unidos WHERE event_id = ?";
    $stmt_unidos = $conexion->prepare($query_unidos);
    $stmt_unidos->bind_param("i", $evento_id);

    if ($stmt_unidos->execute()) {
        $query_evento = "DELETE FROM eventos WHERE id = ? AND usuario_id = ?";
        $stmt_evento = $conexion->prepare($query_evento);
        $stmt_evento->bind_param("ii", $evento_id, $usuario_id);

        if ($stmt_evento->execute()) {
            echo 'success';
        } else {
            error_log("Error al ejecutar la consulta de eliminar evento: " . $stmt_evento->error);
            echo 'error: ' . $stmt_evento->error;
        }

        $stmt_evento->close();
    } else {
        error_log("Error al ejecutar la consulta de eliminar unidos: " . $stmt_unidos->error);
        echo 'error: ' . $stmt_unidos->error;
    }

    $stmt_unidos->close();
    $conexion->close();
}
?>
