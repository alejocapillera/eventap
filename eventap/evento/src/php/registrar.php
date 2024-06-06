<?php
    include '../includes/conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $usuario = $_POST['usuario'];
    $correo = $_POST['correo'];
    $contraseña = password_hash($_POST['contraseña'], PASSWORD_DEFAULT); 

    $consulta = "INSERT INTO usuarios (usuario, correo, contraseña) VALUES ('$usuario', '$correo', '$contraseña')";
    if ($conexion->query($consulta) === TRUE) {
        echo "<script>  window.location.href = 'src/index.php'; </script>";


        exit; 
    } else {
        echo "Error al registrar usuario: " . $conexion->error ;
        exit;
    }

    $conexion->close();
}
?>
