<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>inicio</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  
</head>
<style>
  .sidebar {
  position: fixed;
  left: 0;
  top: 0;
  height: 100vh; 
  width: 250px;
  background-color: #343a40;
  padding-top: 60px; 
}
.sidebar a {
  color: white;
  padding: 10px;
  display: block;
}
.sidebar a:last-child {
  margin-top: auto;
}
.sidebar a:hover {
  background-color: #495057; 
}
.content {
  margin-left: 250px;
  padding: 20px;
}

.logo {
width: 245px; 
height: 150px;
margin-top:-100px;
}
.logocel {
width: 150px; 
height: 90px; 
}
.nav-link {
  font-size: 1.4em;

}
</style>
<body>
<div class="sidebar d-none d-lg-block bg-dark">
    <center><img src="../img/logo.png" class="logo"></center>
    <ul class="nav flex-column">
      <li class="nav-item">
        <a class="nav-link text-light" href="form_cargarevento.php"><i class="bi bi-megaphone"></i> Crear evento</a>
      </li>
      <li class="nav-item">
        <a class="nav-link text-light" href="inicio.php"><i class="bi bi-people"></i> Unirse evento</a>
      </li>
      <li class="nav-item">
        <a class="nav-link text-light" href="miseventos.php"><i class="bi bi-calendar-event"></i> Tus eventos</a>
      </li>
      <li class="nav-item">
        <a class="nav-link text-light" href="perfil.php"><i class="bi bi-calendar-event"></i> Perfil</a>
      </li>
      
      <li class="nav-item">
        <a class="nav-link text-light" href="../php/cerrar_sesion.php" style="margin-top: 100%;"><i class="bi bi-box-arrow-in-left"></i> Cerrar sesión</a>
      </li>
    </ul>
  </div>
 
  <nav class="navbar navbar-dark bg-dark d-block d-lg-none fixed-top">
    <div class="container-fluid justify-content-between">
      <img src="../img/logo.png" class="logocel">
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNavDropdown">
        <center>
          <ul class="navbar-nav">
            <li class="nav-item">
              <a class="nav-link" href="form_cargarevento.php"><i class="bi bi-megaphone"></i> Crear evento</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="inicio.php"><i class="bi bi-people"></i> Unirse evento</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="miseventos.php"><i class="bi bi-calendar-event"></i> Tus eventos</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="php/cerrar_sesion.php"><i class="bi bi-box-arrow-in-left"></i> Cerrar sesión</a>
            </li>
          </ul>
        </center>
      </div>
    </div>
</nav>
  


  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  
</body>
</html>