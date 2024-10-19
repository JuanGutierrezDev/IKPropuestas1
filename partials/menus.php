
<?php
$nombreUsuario = isset($_SESSION['nombreUsuario']) ? $_SESSION['nombreUsuario'] : 'Usuario';
?>

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sistema de propuestas IK</title>
  <link rel="stylesheet" href="../Bootstrap-5.3.3/css/bootstrap.min.css">
  <link rel="stylesheet" href="../assets/css/styles.css">
  <link href="../iconos/fontawesome-6.5.2/css/fontawesome.css" rel="stylesheet" />
  <link href="../iconos/fontawesome-6.5.2/css/brands.css" rel="stylesheet" />
  <link href="../iconos/fontawesome-6.5.2/css/solid.css" rel="stylesheet" />
  <script src="../Bootstrap-5.3.3/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
</head>
<body style="background-color:#F6F5FB">
    
  <div>
      <!-- Sidebar -->
      <div class="sidebar">
          <div>
            <a href="../mdl_ingreso_registro/inicioSesion.php" class="w-100 center"> <img src="../img/infometrika.png" class="img-fluid center mb-5" style="width: 65%" alt="Logo Empresa" />
</a> </div>
          <div class="nombremenu mb-5">
              <span class="material-symbols-outlined ms-4">home_work</span>
              <a class="py-2 ms-3" style="color:white; text-decoration:none;" href="../mdl_ingreso_registro/inicioSesion.php">Infométrika</a>
          </div>
          <ul class="nav flex-column">
              <li class="nav-item">
                  <a class="nav-link opcionmenu" href="../mdl_clientes/clientes.php">
                      <span class="material-symbols-outlined ms-3 iconomenu">face</span>
                      <span class="py-1 ms-3">Clientes</span>
                  </a>
              </li>
              <li class="nav-item">
                  <a class="nav-link opcionmenu" href="../mdl_propuestas/propuestas.php">
                      <span class="material-symbols-outlined ms-3 iconomenu">insert_chart</span>
                      <span class="py-1 ms-3">Propuestas</span>
                  </a>
              </li>
              <li class="nav-item">
                  <a class="nav-link opcionmenu" href="../mdl_bandeja/bandeja.php">
                      <span class="material-symbols-outlined ms-3 iconomenu">mail</span>
                      <span class="py-1 ms-3">Bandeja de propuestas</span>
                  </a>
              </li>
              <li class="nav-item">
            <a class="nav-link opcionmenu" href="../mdl_ingreso_registro/logout.php">
              <span class="material-symbols-outlined ms-3 iconomenu">logout</span>
              <span class="py-1 ms-3">Cerrar sesión</span>
            </a>
          </li>
          </ul>
      </div>
      <!-- Contenido del dashboard -->
      <div class="content">
          <!-- Barra de navegación (navbar) -->
          <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
              <a class="navbar-brand titulonav desplazarnombre">Bienvenido, <?php echo $_SESSION['nombreUsuario']; ?></a>
              <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                  <span class="navbar-toggler-icon"></span>
              </button>
              <div class="collapse navbar-collapse mx-4" id="navbarNav" style="justify-content: flex-end;">
                  <ul class="navbar-nav ml-auto cajonusuario">
                      <img src="../img/cr7.jpg" alt="Descripción de la imagen" class="imagenusuario">
                      <li class="nav-item" style="align-content: center;">
                          <a class="nav-link" href="../mdl_ingreso_registro/logout.php">
                              <span class="mx-1 nombreusuario"> Cierra tu sesión </span>
                              <span class="material-symbols-outlined ms-1" style="float: right;"> close </span>
                          </a>
                      </li>
                  </ul>
              </div>
          </nav>
      </div>
  </div>
</body>
