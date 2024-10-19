<?php 
require '../database.php'; 
session_start(); // Inicia la sesión

$message = '';

if (!empty($_POST['email']) && !empty($_POST['passUsuario'])) {
    // Limpiar y validar el email
    $email = trim($_POST['email']);

    // Preparar la consulta SQL
    $records = $conn->prepare('SELECT idUsuario, email, passUsuario, nombreUsuario FROM usuarios WHERE email = :email');
    $records->bindParam(':email', $email); // Usar la variable $email ya limpiada
    $records->execute();
    $result = $records->fetch(PDO::FETCH_ASSOC);

    // Verificar si el resultado no está vacío y validar la contraseña
    if ($result && password_verify($_POST['passUsuario'], $result['passUsuario'])) {
        $_SESSION['idUsuario'] = $result['idUsuario'];
        $_SESSION['nombreUsuario'] = $result['nombreUsuario']; // Almacena el nombreUsuario en la sesión
        header('Location: inicioSesion.php'); 
        exit; 
    } else {
        $message = 'Lo sentimos, sus credenciales no son correctas';
        $messageclass = 'alert alert-danger';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Sistema de propuestas IK</title>

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
    <article class="centrarcont col-12 vh-100"> 
        <section class="cajalogin col-lg-8 col-md-10 col-sm-12 h-75"> 
            <div class="col-7 h-100 float-start p-5">
                <div class="w-100">
                    <img src="../img/infometrika.png" class="img-fluid h-100" style="max-width: 35%; min-width: 25%;" alt="login logo"/>
                </div>

                <form action="login.php" method="post">
                    <h5 class="titulologin my-5">Sistema de registro y envío de propuestas de Infométrika</h5>

                    <div class="form-outline mb-2 w-75">
                        <label class="form-label etiquetalogin">Email</label>
                        <input type="email" name="email" placeholder="Ingresa tu email" class="form-control form-control-lg camposlogin" required />
                    </div>

                    <div class="form-outline mb-4 w-75">
                        <label class="form-label etiquetalogin">Contraseña</label>
                        <input type="password" name="passUsuario" placeholder="Ingresa tu contraseña" class="form-control form-control-lg camposlogin" required />                                 
                    </div>

                    <div class="pt-3 mb-4 w-100">
                        <input type="submit" value="Ingresar" class="btn btn-md btn-block btn-primary btn-principal px-4">
                        <button class="btn btn-md btn-outline-primary btn-secundario px-4 mx-2" type="button"> <i class="mx-1 fa-regular fa-unlock-keyhole"></i> Recuperar contraseña </button>
                    </div>

                    <?php if (!empty($message)): ?>
                        <p class="<?= $messageclass ?>"> <?= $message ?></p>
                    <?php endif; ?>

                    <p class="pt-5">
                        Sistema de registro y envío de propuestas de Infométrika es un producto de <span style="color: #be272e"> Infométrika SAS </span>
                    </p>
                </form>
            </div>

            <div class="col-5 h-100 float-end">
                <img src="../img/imagelogin.png" 
                alt="login form" class="img-fluid float-end imgcaja h-100" style="border-radius: 40px;" />
            </div>
        </section>
    </article>
</div>

</body>
</html>
