<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include '../includes/conexion.php';

    $correo = $_POST['correo'];
    $contraseña = $_POST['contraseña'];

    $consulta = "SELECT * FROM usuarios WHERE correo = ?";
    $stmt = $conexion->prepare($consulta);
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $usuario = $resultado->fetch_assoc();
        if (password_verify($contraseña, $usuario['contraseña'])) {
            session_start();
            $_SESSION['correo'] = $correo;
            echo "<script>  window.location.href = '../views/pantalladecarga.php'; </script>";
            exit();
        } else {
            echo "<script> alert('Contraseña incorrecta. Vuelve a intentarlo.'); window.history.back(); </script>";
            exit;
        }
    } else {
        echo "<script> alert('Correo electrónico no encontrado. Vuelve a intentarlo.'); window.history.back(); </script>";
        exit;
    }
}
?>
