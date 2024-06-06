<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        :root {
            --primary-bg-color: #23272a;
            --secondary-bg-color: #343a40;
            --highlight-color: #DD2E44;
            --text-color: #ffffff;
            --link-color: #fa1100;
            --link-hover-color: #f70909;
            --box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            --border-radius: 10px;
            --input-border-color: #ccc;
        }

        body {
            background-color: var(--primary-bg-color);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .login-container {
            background-color: var(--secondary-bg-color);
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            text-align: center;
            padding: 20px;
            width: 90%;
            max-width: 500px;
        }

        .login-container h3,
        .login-container label,
        .login-container p {
            color: var(--text-color);
        }

        .login-container .logo {
            width: 90%;
            margin-top: -3%;
            margin-bottom: -3%;
        }

        .login-container input[type="email"],
        .login-container input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid var(--input-border-color);
            border-radius: 5px;
            box-sizing: border-box;
        }

        .login-container input[type="submit"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            background-color: var(--highlight-color);
            color: var(--text-color);
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .login-container a {
            color: var(--link-color);
        }

        .login-container a:hover {
            color: var(--link-hover-color);
        }

        @media (max-width: 768px) {
            .login-container {
                padding: 10px;
            }

            .login-container h3 {
                font-size: 1.5rem;
            }

            .login-container input[type="email"],
            .login-container input[type="password"] {
                padding: 5px;
            }

            .login-container input[type="submit"] {
                padding: 5px;
            }
        }
        .input {
    height: 40px;
    padding: 10px;
    border: 2px solid rgb(68, 6, 6);
    border-radius: 5px;
    box-shadow: 3px 3px 2px rgb(85, 3, 0); 
  }
  
  .input:focus {
    color: rgb(255, 0, 0);
    outline-color: rgb(255, 0, 0);
    box-shadow: -3px -3px 15px rgb(255, 0, 0);
    transition: .1s;
  }
  p{
    margin: 10px 0px;
  }

    </style>
</head>
<body>
    <div class="login-container">
        <img src="src/img/logopc.png" alt="Logo" class="logo">
        <form action="src/php/loginvalidar.php" method="post">
            <div class="form-group">
                <label for="correo">Correo electrónico:</label>
                <input type="email" name="correo" id="correo" class="form-control input" required>
            </div>
            <div class="form-group">
                <label for="contraseña">Contraseña:</label>
                <input type="password" name="contraseña" id="contraseña" class="form-control input" required>
            </div>
            <p><a href="src/recuperarcontra/formulario.php">¿Olvidaste tu contraseña?</a></p>

            <input type="submit" value="Iniciar Sesión" class="input">
            <p>¿No tienes cuenta? <a href="src/views/registro.php">Regístrate aquí</a></p>

        </form>
    </div>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
