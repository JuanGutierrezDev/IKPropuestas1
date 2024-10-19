<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Index</title>
    <link rel="stylesheet" href="./Bootstrap-5.3.3/css/bootstrap.min.css">
  <link rel="stylesheet" href="./assets/css/styles.css">
  <link href="./iconos/fontawesome-6.5.2/css/fontawesome.css" rel="stylesheet" />
  <link href="./iconos/fontawesome-6.5.2/css/brands.css" rel="stylesheet" />
  <link href="./iconos/fontawesome-6.5.2/css/solid.css" rel="stylesheet" />
  <script src="./Bootstrap-5.3.3/js/bootstrap.bundle.min.js"></script>


</head>


<?php require 'partials/header.php' ?>


<body>

    <div class="login">

    <article class="centrarcont col-12 vh-100" > 
    <section class="cajalogin col-lg-8 col-md-10 col-sm-12 h-75"> 

        <div class="col-7 h-100 float-start p-5">

            <div class="w-100 ">
                <img src="img/infometrika.png" class="img-fluid h-100" style="max-width: 35%; min-width: 25%;" alt="login logo"/>
            </div>

            <div>
      
                <h5 class="titulologin my-5">Sistema de registro y envío de propuestas de Infométrika</h5>

                <h1 class="subtitulologin"> Por favor logueate o registrate en el sistema </h1> <br> <br>

                <a href ="./mdl_ingreso_registro/login.php"> <button class="btn btn-md btn-block btn-primary btn-principal px-4" type="button"> <i class="mx-1 fa-regular fa-user"></i>  Logueate </button></a>
                <a href ="./mdl_ingreso_registro/signup.php"><button class="btn btn-md btn-outline-primary btn-secundario px-4 mx-2" type="button"> <i class="mx-1 fa-regular fa-user"></i>  Registrate en el sistema </button></a> 

                <p class="pt-5">
                    Sistema de registro y envío de propuestas de Infométrika es un producto de <span style="color: #be272e"> Infométrika SAS </span>
                </p>
</div>
        </div>

           
        
        <div class="col-5 h-100 float-end">

            <img src="img/imagelogin.png" 
            alt="login form" class="img-fluid float-end imgcaja h-100 " style="border-radius: 40px 40px 40px 40px;" />
        </div>
    
</body>
</html>