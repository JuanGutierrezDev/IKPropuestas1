<?php require '../database.php'; 

$message = '';



if (!empty($_POST['nombreUsuario']) && !empty($_POST['email']) && !empty($_POST['passUsuario'])) 
  if ($_POST['passUsuario'] !== $_POST['Confirm_password']) {
      $message = 'Las contraseñas no coinciden.';
      $messageclass = 'alert alert-danger';
  } else {
      $sql = "INSERT INTO usuarios (nombreUsuario, email, passUsuario, tipoIdentificacion, numeroIdentificacion, telefonoUsuario, fechaNacimiento) VALUES (:nombreUsuario, :email, :passUsuario, :tipoIdentificacion, :numeroIdentificacion, :telefonoUsuario, :fechaNacimiento)";


$stmt = $conn->prepare($sql);

$stmt->bindParam(':nombreUsuario', $_POST['nombreUsuario']);
$stmt->bindParam(':email', $_POST['email']);
$stmt->bindParam(':tipoIdentificacion', $_POST['tipoIdentificacion']);
$stmt->bindParam(':numeroIdentificacion', $_POST['numeroIdentificacion']);
$stmt->bindParam(':telefonoUsuario', $_POST['telefonoUsuario']);
$stmt->bindParam(':fechaNacimiento', $_POST['fechaNacimiento']);


$password = password_hash($_POST['passUsuario'], PASSWORD_BCRYPT);
$stmt->bindParam(':passUsuario', $password);


    if ($stmt->execute()) {
        $message = 'Se ha creado de forma satisfactoria el usuario';
        $messageclass = 'alert alert-success';
    } else {
        $message = 'Lo sentimos, ha ocurrido un error, valide los datos';
        $messageclass = 'alert alert-danger';
        
    }

}

?>


<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registrate en Sistema de propuestas IK</title>

  <link rel="stylesheet" href="../Bootstrap-5.3.3/css/bootstrap.min.css">
  <link rel="stylesheet" href="../assets/css/styles.css">
  <link href="../iconos/fontawesome-6.5.2/css/fontawesome.css" rel="stylesheet" />
  <link href="../iconos/fontawesome-6.5.2/css/brands.css" rel="stylesheet" />
  <link href="../iconos/fontawesome-6.5.2/css/solid.css" rel="stylesheet" />
  <script src="../Bootstrap-5.3.3/js/bootstrap.bundle.min.js"></script>

</head>


<body>

<?php require '../partials/header.php' ?>

    <div class="login">

    <article class="centrarcont col-12 vh-100" > 
    <section class="cajalogin col-lg-8 col-md-10 col-sm-12"> 

        <div class="col-12 h-100 float-start p-5">

            <div class="w-50 ">
                <img src="../img/infometrika.png" class="img-fluid h-100" style="max-width: 35%; min-width: 25%;" alt="login logo"/>
            </div>



            <form action="signup.php" method="post">

        <div class="container-fluid center my-3">
        <section class="cajaform container" style="margin-right: 0px;">
            <span class="titulofuncion" style="text-align: left;"> Información del usuario </span>
            <hr>

        
        <div class="row form-outline my-3">
          <div class="col-lg-3 my-2">
            <label class="form-label etiquetaform">Nombre *</label>
            <input type="text" name="nombreUsuario" class="form-control form-control-lg camposform" required />
          </div>

          <div class="col-lg-3 my-2">
            <label class="form-label etiquetaform">Correo Electrónico *</label>
            <input type="email" name="email" class="form-control form-control-lg camposform" required />
          </div>

         <div class="col-lg-3 my-2">
            <label class="form-label etiquetaform">Contraseña *</label>
            <input type="password" name="passUsuario" class="form-control form-control-lg camposform" required />
          </div>

          <div class="col-lg-3 my-2">
            <label class="form-label etiquetaform">Repita su Contraseña *</label>
            <input type="password" name="Confirm_password" class="form-control form-control-lg camposform" required />
          </div>

          <div class="row form-outline my-3">

        <div class="col-lg-3 my-2">
            <label class="form-label etiquetaform">Tipo de identificación</label>
           <select name="tipoIdentificacion" class="form-control form-control-lg camposform" required>
                <option value="" disabled selected>Seleccione un tipo</option>
                <option value="Cédula de ciudadania">Cédula de ciudadanía</option>
                <option value="Pasaporte">Pasaporte</option>
                <option value="Cedula de Extranjería">Cedula de Extranjería</option>
                <option value="Permiso Especial">Permiso especial</option>
            </select>
          </div>

          <div class="col-lg-3 my-2">
            <label class="form-label etiquetaform">Número de identificación</label>
            <input type="number" name="numeroIdentificacion" class="form-control form-control-lg camposform" max="999999999999"  />
          </div>

         <div class="col-lg-3 my-2">
            <label class="form-label etiquetaform">Teléfono</label>
            <input type="tel" name="telefonoUsuario" maxlength="10" class="form-control form-control-lg camposform" />
          </div>

          <div class="col-lg-3 my-2">
            <label class="form-label etiquetaform">Fecha de nacimiento</label>
            <input type="date" name="fechaNacimiento" class="form-control form-control-lg camposform" />
          </div>

        </div>
    </div>

    <?php if (!empty($message)): ?>
    <p class="<?= $messageclass ?>"> <?= $message ?></p>
<?php endif; ?>



    <div class="container-fluid center my-1">
      <section class="desplazar container text-end" style="margin-right: 0px;">

      <input type="submit" value="Crear Usuario" class="btn btn-sm textoboton btn-block btn-primary btn-principal px-4">

        <button class="btn btn-sm btn-outline-primary btn-secundario textoboton px-4 my-1" type="reset"> Limpiar datos </button>
      </section>
    </form>

           
        
    </section>
    </article>
</div>


</body>
</html>

