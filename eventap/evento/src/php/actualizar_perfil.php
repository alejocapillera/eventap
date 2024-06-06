<?php
session_start();
include '../includes/conexion.php';

if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}

$usuario_id = $_SESSION['usuario_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $foto = $_FILES['foto'];
    $banner = $_FILES['banner'];

    $target_dir = "../uploads/";

    if ($foto['size'] > 0) {
        $foto_target_file = $target_dir . basename($foto['name']);
        move_uploaded_file($foto['tmp_name'], $foto_target_file);
    } else {
        $foto_target_file = null;
    }

    if ($banner['size'] > 0) {
        $banner_target_file = $target_dir . basename($banner['name']);
        move_uploaded_file($banner['tmp_name'], $banner_target_file);
    } else {
        $banner_target_file = null;
    }

    $query_actualizar = "UPDATE usuarios SET usuario = ?, correo = ?, foto = COALESCE(?, foto), banner = COALESCE(?, banner) WHERE id = ?";
    $stmt_actualizar = $conexion->prepare($query_actualizar);

    if ($stmt_actualizar === false) {
        die("Error al preparar la consulta: " . $conexion->error);
    }

    $stmt_actualizar->bind_param("ssssi", $nombre, $correo, $foto_target_file, $banner_target_file, $usuario_id);

    if (!$stmt_actualizar->execute()) {
        die("Error al ejecutar la consulta: " . $stmt_actualizar->error);
    }

    $stmt_actualizar->close();
    header('Location: ../views/perfil.php');
    exit;
}
?>
