<?php
session_start();
include '../includes/conexion.php';

if (!isset($_SESSION['correo'])) {
    echo "<script> alert('Debes iniciar sesión para unirte a un evento.'); window.location.href = 'login.php'; </script>";
    exit;
}

$correo = $_SESSION['correo'];

$query = "SELECT id FROM usuarios WHERE correo = '$correo'";
$result = $conexion->query($query);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $usuario_id = $row['id'];
} else {
    echo "<script> alert('Error: No se encontró el usuario en la base de datos.'); window.history.back(); </script>";
    exit;
}

if (isset($_POST['evento_id'])) {
    $eventoId = $_POST['evento_id'];

    // Verificar si el usuario es el creador del evento
    $verificar_creador_query = "SELECT usuario_id, capacidad, capacidad_actual FROM eventos WHERE id = '$eventoId'";
    $verificar_creador_result = $conexion->query($verificar_creador_query);

    if ($verificar_creador_result->num_rows > 0) {
        $row_evento = $verificar_creador_result->fetch_assoc();
        if ($row_evento['usuario_id'] == $usuario_id) {
            echo "<script> alert('No puedes unirte a tu propio evento.'); window.history.back(); </script>";
            exit;
        }
        
        // Verificar la capacidad del evento
        if ($row_evento['capacidad_actual'] >= $row_evento['capacidad']) {
            echo "<script> alert('El evento ha alcanzado su capacidad máxima.'); window.history.back(); </script>";
            exit;
        }
    } else {
        echo "<script> alert('Error: No se encontró el evento en la base de datos.'); window.history.back(); </script>";
        exit;
    }

    // Verificar si el usuario ya está inscrito en el evento
    $verificar_query = "SELECT * FROM unidos WHERE user_id = '$usuario_id' AND event_id = '$eventoId'";
    $verificar_result = $conexion->query($verificar_query);

    if ($verificar_result->num_rows > 0) {
        echo "<script> alert('Ya estás inscrito en este evento.'); window.history.back(); </script>";
        exit;
    } else {
        // Unirse al evento
        $conexion->autocommit(FALSE); // Desactivar autocommit

        $sql = "INSERT INTO unidos (user_id, event_id) VALUES ('$usuario_id', '$eventoId')";

        if ($conexion->query($sql) === TRUE) {
            // Incrementar la capacidad actual del evento
            $sql_update = "UPDATE eventos SET capacidad_actual = capacidad_actual + 1 WHERE id = '$eventoId'";
            if ($conexion->query($sql_update) === TRUE) {
                $conexion->commit(); // Confirmar transacción
                echo "<script> alert('Te has unido al evento exitosamente.'); window.history.back();  </script>";
                exit;
            } else {
                $conexion->rollback(); // Revertir transacción
                echo "<script> alert('Error al actualizar la capacidad del evento: " . $conexion->error . "'); window.history.back(); </script>";
                exit;
            }
        } else {
            $conexion->rollback(); // Revertir transacción
            echo "<script> alert('Error al unirse al evento: " . $conexion->error . "'); window.history.back(); </script>";
            exit;
        }

        $conexion->autocommit(TRUE); // Reactivar autocommit
    }
} else {
    echo "<script> alert('ID de evento no recibido.'); window.history.back(); </script>";
    exit;
}
?>
