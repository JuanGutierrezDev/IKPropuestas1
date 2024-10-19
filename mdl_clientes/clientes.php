<?php
require '../partials/validasesion.php'; 
require '../database.php'; 

// Inicializar variables del formulario
$nombreEmpresa = '';
$nitEmpresa = '';
$correoEmpresa = '';
$nombreContacto = '';
$apellidosContacto = '';
$numeroContacto = '';
$correoContacto = '';
$telefonoEmpresa = '';
$paisEmpresa = '';
$ciudadEmpresa = '';

$message = '';
$messageclass = '';
$idClientes = null;
$isEditing = false; // Estado para editar

// Verifica si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") { 
    if (!empty($_POST['nombreEmpresa']) && !empty($_POST['nitEmpresa']) && 
        !empty($_POST['correoEmpresa']) && !empty($_POST['nombreContacto']) && 
        !empty($_POST['apellidosContacto']) && !empty($_POST['numeroContacto']) && 
        !empty($_POST['correoContacto'])) {
        
        // Limpiar y almacenar datos del formulario
        $nombreEmpresa = trim($_POST['nombreEmpresa']);
        $nitEmpresa = trim($_POST['nitEmpresa']);
        $correoEmpresa = trim($_POST['correoEmpresa']);
        $nombreContacto = trim($_POST['nombreContacto']);
        $apellidosContacto = trim($_POST['apellidosContacto']);
        $numeroContacto = trim($_POST['numeroContacto']);
        $correoContacto = trim($_POST['correoContacto']);

        // Campos no obligatorios
        $telefonoEmpresa = !empty($_POST['telefonoEmpresa']) ? trim($_POST['telefonoEmpresa']) : null;
        $paisEmpresa = !empty($_POST['paisEmpresa']) ? trim($_POST['paisEmpresa']) : null;
        $ciudadEmpresa = !empty($_POST['ciudadEmpresa']) ? trim($_POST['ciudadEmpresa']) : null;

        // Si se está editando, actualizamos en lugar de insertar
        if (isset($_POST['idClientes']) && !empty($_POST['idClientes'])) {
            $idClientes = trim($_POST['idClientes']);
            $sql = "UPDATE clientes SET 
                        nombreEmpresa = :nombreEmpresa, 
                        nitEmpresa = :nitEmpresa, 
                        correoEmpresa = :correoEmpresa, 
                        nombreContacto = :nombreContacto, 
                        apellidosContacto = :apellidosContacto, 
                        numeroContacto = :numeroContacto, 
                        correoContacto = :correoContacto, 
                        telefonoEmpresa = :telefonoEmpresa, 
                        paisEmpresa = :paisEmpresa, 
                        ciudadEmpresa = :ciudadEmpresa 
                    WHERE idClientes = :idClientes";
        } else {
            // Consulta SQL para insertar el nuevo cliente
            $sql = "INSERT INTO clientes (nombreEmpresa, nitEmpresa, correoEmpresa, 
                                            nombreContacto, apellidosContacto, 
                                            numeroContacto, correoContacto, 
                                            telefonoEmpresa, paisEmpresa, 
                                            ciudadEmpresa) 
                    VALUES (:nombreEmpresa, :nitEmpresa, :correoEmpresa, 
                            :nombreContacto, :apellidosContacto, 
                            :numeroContacto, :correoContacto, 
                            :telefonoEmpresa, :paisEmpresa, 
                            :ciudadEmpresa)";
        }
        
        $stmt = $conn->prepare($sql);
        
        // Vincular parámetros
        $stmt->bindParam(':nombreEmpresa', $nombreEmpresa);
        $stmt->bindParam(':nitEmpresa', $nitEmpresa);
        $stmt->bindParam(':correoEmpresa', $correoEmpresa);
        $stmt->bindParam(':nombreContacto', $nombreContacto);
        $stmt->bindParam(':apellidosContacto', $apellidosContacto);
        $stmt->bindParam(':numeroContacto', $numeroContacto);
        $stmt->bindParam(':correoContacto', $correoContacto);
        $stmt->bindParam(':telefonoEmpresa', $telefonoEmpresa);
        $stmt->bindParam(':paisEmpresa', $paisEmpresa);
        $stmt->bindParam(':ciudadEmpresa', $ciudadEmpresa);

        // Si se está editando, vincular el idClientes
        if (isset($idClientes)) {
            $stmt->bindParam(':idClientes', $idClientes);
        }
        
        // Ejecutar la consulta
        if ($stmt->execute()) {
            // Limpiar los datos del formulario
            $nombreEmpresa = '';
            $nitEmpresa = '';
            $correoEmpresa = '';
            $nombreContacto = '';
            $apellidosContacto = '';
            $numeroContacto = '';
            $correoContacto = '';
            $telefonoEmpresa = '';
            $paisEmpresa = '';
            $ciudadEmpresa = '';

            header('Location: clientes.php?success=1');
            exit;
        } else {
            $message = 'Error al guardar el cliente.';
            $messageclass = 'alert alert-danger';
        }
    } else {
        $message = 'Los campos obligatorios deben ser llenados.';
        $messageclass = 'alert alert-danger';
    }
}

// Cargar datos si se está editando
if (isset($_GET['idClientes'])) {
    $idClientes = $_GET['idClientes'];
    $sql = "SELECT * FROM clientes WHERE idClientes = :idClientes";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':idClientes', $idClientes);
    $stmt->execute();
    $cliente = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Cargar datos en las variables del formulario
    if ($cliente) {
        $nombreEmpresa = htmlspecialchars($cliente['nombreEmpresa']);
        $nitEmpresa = htmlspecialchars($cliente['nitEmpresa']);
        $correoEmpresa = htmlspecialchars($cliente['correoEmpresa']);
        $nombreContacto = htmlspecialchars($cliente['nombreContacto']);
        $apellidosContacto = htmlspecialchars($cliente['apellidosContacto']);
        $numeroContacto = htmlspecialchars($cliente['numeroContacto']);
        $correoContacto = htmlspecialchars($cliente['correoContacto']);
        $telefonoEmpresa = htmlspecialchars($cliente['telefonoEmpresa']);
        $paisEmpresa = htmlspecialchars($cliente['paisEmpresa']);
        $ciudadEmpresa = htmlspecialchars($cliente['ciudadEmpresa']);
    }

    $isEditing = true; // Cambiar el estado a editar
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Clientes</title>
    <link rel="stylesheet" href="../Bootstrap-5.3.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/styles.css">
    <script src="../Bootstrap-5.3.3/js/bootstrap.bundle.min.js"></script>
    <script>
function confirmDelete() {
    return confirm("¿Estás seguro de que deseas eliminar este cliente?");
}
</script>
</head>

<body>

<?php require '../partials/menus.php'; ?>

<div class="container-fluid col-12" style="background-color: white;">
    <div class="mx-2">
        <div class="titulomodulo contenidotitulofuncion py-4">
            <span class="iconomenufuncion material-symbols-outlined mx-1"> face </span> 
            Gestión de Clientes 
        </div>
    </div>
</div>

<form action="clientes.php" method="post">

    <div class="container-fluid center my-3">
        <section class="desplazar cajaform container" style="margin-right: 0px;">
            <span class="titulofuncion" style="text-align: left;">
                <span style="font-size: 15px;">Crea o edita tus clientes</span>
            </span>
            <hr>

            <input type="hidden" name="idClientes" value="<?= isset($cliente) ? htmlspecialchars($cliente['idClientes']) : ''; ?>">

            <div class="row form-outline my-3">
                <div class="col-lg-3 my-2">
                    <label class="form-label etiquetaform">Nombre Empresa *</label>
                    <input type="text" name="nombreEmpresa" class="form-control form-control-lg camposform" required value="<?= htmlspecialchars($nombreEmpresa); ?>" />
                </div>
                <div class="col-lg-3 my-2">
                    <label class="form-label etiquetaform">NIT *</label>
                    <input type="number" name="nitEmpresa" class="form-control form-control-lg camposform" required value="<?= htmlspecialchars($nitEmpresa); ?>" />
                </div>
                <div class="col-lg-3 my-2">
                    <label class="form-label etiquetaform">Teléfono </label>
                    <input type="tel" name="telefonoEmpresa" maxlength="10" class="form-control form-control-lg camposform" value="<?= htmlspecialchars($telefonoEmpresa); ?>" />
                </div>
                <div class="col-lg-3 my-2">
                    <label class="form-label etiquetaform">Correo Electrónico *</label>
                    <input type="email" name="correoEmpresa" class="form-control form-control-lg camposform" required value="<?= htmlspecialchars($correoEmpresa); ?>" />
                </div>
            </div>

            <div class="row form-outline my-3">
                <div class="col-lg-3 my-2">
                    <label class="form-label etiquetaform">Nombre de Contacto *</label>
                    <input type="text" name="nombreContacto" class="form-control form-control-lg camposform" required value="<?= htmlspecialchars($nombreContacto); ?>" />
                </div>
                <div class="col-lg-3 my-2">
                    <label class="form-label etiquetaform">Apellidos de Contacto *</label>
                    <input type="text" name="apellidosContacto" class="form-control form-control-lg camposform" required value="<?= htmlspecialchars($apellidosContacto); ?>" />
                </div>
                <div class="col-lg-3 my-2">
                    <label class="form-label etiquetaform">Teléfono Contacto*</label>
                    <input type="tel" name="numeroContacto" maxlength="10" class="form-control form-control-lg camposform" value="<?= htmlspecialchars($numeroContacto); ?>" />
                </div>
                <div class="col-lg-3 my-2">
                    <label class="form-label etiquetaform">Correo Electrónico Contacto *</label>
                    <input type="email" name="correoContacto" class="form-control form-control-lg camposform" required value="<?= htmlspecialchars($correoContacto); ?>" />
                </div>
            </div>

            <div class="row form-outline my-3">
                <div class="col-lg-6 my-2">
                    <label class="form-label etiquetaform">Ciudad</label>
                    <input type="text" name="ciudadEmpresa" class="form-control form-control-lg camposform" required value="<?= htmlspecialchars($ciudadEmpresa); ?>" />
                </div>
                <div class="col-lg-6 my-2">
                    <label class="form-label etiquetaform">País </label>
                    <input type="text" name="paisEmpresa" maxlength="10" class="form-control form-control-lg camposform" value="<?= htmlspecialchars($paisEmpresa); ?>" />
                </div>
            </div>

            <?php if (!empty($message)): ?>
                <p class="<?= $messageclass ?>"> <?= $message ?></p>
            <?php endif; ?>
            
            <?php if (isset($_GET['success'])): ?>
                <p class="alert alert-success">Cliente guardado exitosamente.</p>
            <?php endif; ?>

            <?php if (isset($_GET['success_delete'])): ?>
    <p class="alert alert-success">Cliente eliminado exitosamente.</p>
<?php endif; ?>

<?php if (isset($_GET['error_delete'])): ?>
    <p class="alert alert-danger">Error al eliminar el cliente.</p>
<?php endif; ?>

            <div class="container-fluid center my-1">
                <section class="desplazar container text-end" style="margin-right: 0px;">
                    <input type="submit" value="<?= $isEditing ? 'Modificar Cliente' : 'Crear Cliente' ?>" class="btn btn-sm textoboton btn-block btn-primary btn-principal px-4">
                    <button class="btn btn-sm btn-outline-primary btn-secundario textoboton px-4 my-1" type="reset"> Limpiar datos </button>
                </section>
            </div>
        </section>
    </form>
</div>

<?php
// Consultar todos los clientes
$query = "SELECT idClientes, nombreEmpresa, nitEmpresa, numeroContacto, correoEmpresa, nombreContacto, apellidosContacto, correoContacto, telefonoEmpresa, paisEmpresa, ciudadEmpresa FROM clientes";
$stmt = $conn->prepare($query);
$stmt->execute();
$clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="container-fluid center my-3">
    <section class="desplazar cajaform container" style="margin-right: 0px;">
        <span class="titulotabla textoiconocentrar " style="text-align: left;"> Listado de clientes creados <i class="material-symbols-outlined"> travel_explore </i></span>
        <hr>
        <div class="row form-outline my-3">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th class="encabezadotabla" scope="col">Nombre Empresa</th>
                        <th class="encabezadotabla" scope="col">NIT</th>
                        <th class="encabezadotabla" scope="col">Número de Empresa</th>
                        <th class="encabezadotabla" scope="col">Correo de Empresa</th>
                        <th class="encabezadotabla" scope="col">Nombre de Contacto</th>
                        <th class="encabezadotabla" scope="col">Correo de Contacto</th>
                        <th class="encabezadotabla" scope="col">Teléfono de contacto</th>
                        <th class="encabezadotabla" scope="col">Ubicación</th>
                        <th class="encabezadotabla" scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($clientes as $cliente): ?>
                        <tr class="contenidotabla">
                            <td><?php echo htmlspecialchars($cliente['nombreEmpresa']); ?></td>
                            <td><?php echo htmlspecialchars($cliente['nitEmpresa']); ?></td>
                            <td><?php echo htmlspecialchars($cliente['numeroContacto']); ?></td>
                            <td><?php echo htmlspecialchars($cliente['correoEmpresa']); ?></td>
                            <td><?php echo htmlspecialchars($cliente['nombreContacto'] . ' ' . $cliente['apellidosContacto']); ?></td>
                            <td><?php echo htmlspecialchars($cliente['correoContacto']); ?></td>
                            <td><?php echo htmlspecialchars($cliente['telefonoEmpresa']); ?></td>
                            <td><?php echo htmlspecialchars($cliente['ciudadEmpresa'] . ', ' . $cliente['paisEmpresa']); ?></td>
                            <td>
                                <a style="text-decoration:none;" href="clientes.php?idClientes=<?= $cliente['idClientes']; ?>"><span class="iconosaccion iconomodificar material-symbols-outlined">edit</span> </a> &nbsp;
                                <a style="text-decoration:none;" href="eliminarclientes.php?idClientes=<?= $cliente['idClientes']; ?>" onclick="return confirmDelete();">
                                 <span class="iconosaccion iconoeliminar material-symbols-outlined">delete</span></a>                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </section>
</div>

</body>
</html>
