<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cargando...</title>
  <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      background-color: #23272a;
      margin: 0;
    }
    .progress {
      width: 100%;
    }
    
  </style>
</head>
<body>
  <div class="text-center">
    <img src="../img/logopc.png" alt="Logo" width="100%">
    <div class="progress mt-3">
      <div class="progress-bar progress-bar-striped progress-bar-animated bg-danger" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
    </div>
  </div>

  <script>
    let progress = 0;
    let progressBar = document.querySelector('.progress-bar');

    function avanzarBarra() {
      progress += 1;
      progressBar.style.width = progress + '%';
      if (progress < 100) {
        setTimeout(avanzarBarra, 10);
      } else {
        setTimeout(function() {
          window.location.href = 'inicio.php';
        }, 1000); 
      }
    }

    avanzarBarra(); 
  </script>
</body>
</html>
