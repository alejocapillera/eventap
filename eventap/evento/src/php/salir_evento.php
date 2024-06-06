<?php
session_start();
if (!isset($_SESSION['correo'])) {
    header('Location: login.php');
    exit;
}

include '../includes/conexion.php';

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

$user_id = $_SESSION['usuario_id'];
$event_id = $_GET['event_id'];

// Empezar una transacción
$conexion->autocommit(FALSE);

// Intentar eliminar la inscripción del usuario del evento
$sql_delete = "DELETE FROM unidos WHERE user_id = ? AND event_id = ?";
$stmt_delete = $conexion->prepare($sql_delete);

if ($stmt_delete === false) {
    echo "Error al preparar la consulta.";
    $conexion->rollback();
    exit;
}

$stmt_delete->bind_param("ii", $user_id, $event_id);
$stmt_delete->execute();

if ($stmt_delete->affected_rows > 0) {
    // Si la eliminación es exitosa, reducir la capacidad actual del evento
    $sql_update = "UPDATE eventos SET capacidad_actual = capacidad_actual - 1 WHERE id = ?";
    $stmt_update = $conexion->prepare($sql_update);
    
    if ($stmt_update === false) {
        echo "Error al preparar la consulta para actualizar la capacidad.";
        $conexion->rollback();
        exit;
    }

    $stmt_update->bind_param("i", $event_id);
    $stmt_update->execute();

    if ($stmt_update->affected_rows > 0) {
        // Confirmar la transacción
        $conexion->commit();
        echo "Te has salido del evento exitosamente.";
    } else {
        echo "Error al actualizar la capacidad del evento.";
        $conexion->rollback();
    }

    $stmt_update->close();
} else {
    echo "Error al intentar salir del evento.";
    $conexion->rollback();
}

$stmt_delete->close();
$conexion->autocommit(TRUE);
$conexion->close();
?>
