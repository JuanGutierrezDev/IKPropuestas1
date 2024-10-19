<?php
require '../partials/validasesion.php'; 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ingreso al Sistema</title>
    <link rel="stylesheet" href="../Bootstrap-5.3.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/styles.css">
    <script src="../Bootstrap-5.3.3/js/bootstrap.bundle.min.js"></script>
</head>

<body>

<?php require '../partials/menus.php'; ?>

<div class="container-fluid col-12" style="background-color: white;">
    <div class="mx-1">
        <div class="titulomodulo contenidotitulofuncion py-4">
            <span class="iconomenufuncion material-symbols-outlined mx-1">start</span> 
            Inicio en el sistema 
        </div>
    </div>
</div>

<div class="container-fluid center my-3">
    <section class="desplazar cajaform container" style="margin-right: 0px;">
        <span class="titulofuncion" style="text-align: left;">
            <span style="font-size: 18px;">Bienvenido, </span>
            al sistema de propuestas de <span style="font-size: 18px;">Infométrika</span>, selecciona la opción que requieras: 
        </span>
        <hr>

        <div class="card-group">
            <!-- Tarjeta de Crear Clientes -->
            <div class="card bg-light m-4 cardstyle">
                <div class="card-body cardstyle">
                    <h6 class="card-title sora rojo semibold my-2">
                        <span class="iconomenufuncion material-symbols-outlined mx-1">face</span> 
                        Crea tus Clientes
                    </h6>
                    <hr>
                    <p class="textocard mb-4">Crea nuevos clientes de manera rápida y sencilla. Así podrás asignar las propuestas.</p>
                      <a class="mx-auto btn btn-sm btn-outline-primary btn-secundario center" type="button" href="../mdl_clientes/clientes.php"> 
                        <span class="material-symbols-outlined mx-1">face</span> Ir a Clientes </a> 
                </div>
            </div>

            <!-- Tarjeta de Propuestas -->
            <div class="card bg-light m-4 cardstyle">
                <div class="card-body cardstyle">
                    <h6 class="card-title sora rojo semibold my-2">
                        <span class="iconomenufuncion material-symbols-outlined mx-1">insert_chart</span> 
                        ¿Necesitas una propuesta?
                    </h6>
                    <hr>
                    <p class="textocard mb-4">Genera propuestas de manera rápida y sencilla. Haz clic en el botón a continuación para comenzar.</p>
                    <a class="mx-auto btn btn-sm btn-outline-primary btn-secundario center" type="button" href="../mdl_propuestas/propuestas.php"> 
                        <span class="material-symbols-outlined mx-1">insert_chart</span> Ir a propuestas 
</a>
                </div>
            </div>

            <!-- Tarjeta de Bandeja -->
            <div class="card bg-light m-4 cardstyle">
                <div class="card-body cardstyle">
                    <h6 class="card-title sora rojo semibold my-2">
                        <span class="iconomenufuncion material-symbols-outlined mx-1">mail</span> 
                        Revisa tu bandeja
                    </h6>
                    <hr>
                    <p class="textocard mb-4">Accede rápidamente a tu bandeja de entrada para mantenerte al tanto de todas tus comunicaciones.</p>
                    <a class="mx-auto btn btn-sm btn-outline-primary btn-secundario center" type="button" href="../mdl_bandeja/bandeja.php"> 
                        <span class="material-symbols-outlined mx-1">manage_search</span> Ir a mi bandeja 
</a>
                </div>
            </div>
        </div>
    </section>
</div>

</body>
</html>
