<?php
require '../partials/validasesion.php'; 
require '../database.php'; 

// Inicializar variables del formulario
$tipoServicio = '';
$modalidad = '';
$numeroSucursales = '';
$numeroUsuarios = '';
$tiempo = '';
$idUsuario = '';
$idClientes = '';
$idPropuestas = ''; 
$detallePropuesta = ''; 

$message = '';
$messageclass = '';
$isEditing = false; 

// Verifica si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") { 
    if (!empty($_POST['idClientes']) && !empty($_POST['tipoServicio']) && !empty($_POST['modalidad']) && 
        !empty($_POST['numeroSucursales']) && !empty($_POST['numeroUsuarios']) && 
        !empty($_POST['tiempo']) && !empty($_POST['idUsuario']) && 
        !empty($_POST['detallePropuesta'])) {
        
        // Limpiar y almacenar datos del formulario
        $idClientes = trim($_POST['idClientes']);
        $tipoServicio = trim($_POST['tipoServicio']);
        $modalidad = trim($_POST['modalidad']);
        $numeroSucursales = trim($_POST['numeroSucursales']);
        $numeroUsuarios = trim($_POST['numeroUsuarios']);
        $tiempo = trim($_POST['tiempo']);
        $idUsuario = trim($_POST['idUsuario']);
        $detallePropuesta = trim($_POST['detallePropuesta']);

        // Si se está editando, actualizamos en lugar de insertar
        if (isset($_POST['idPropuestas']) && !empty($_POST['idPropuestas'])) {
            $idPropuestas = trim($_POST['idPropuestas']);
            $sql = "UPDATE propuestas SET 
                        idClientes = :idClientes, 
                        tipoServicio = :tipoServicio, 
                        modalidad = :modalidad, 
                        numeroSucursales = :numeroSucursales, 
                        numeroUsuarios = :numeroUsuarios, 
                        tiempo = :tiempo, 
                        idUsuario = :idUsuario,
                        detallePropuesta = :detallePropuesta
                    WHERE idPropuestas = :idPropuestas";
        } else {
            $sql = "INSERT INTO propuestas (idClientes, tipoServicio, 
                                            modalidad, numeroSucursales, 
                                            numeroUsuarios, tiempo, idUsuario, detallePropuesta) 
                    VALUES (:idClientes, :tipoServicio, 
                            :modalidad, :numeroSucursales, 
                            :numeroUsuarios, :tiempo, 
                            :idUsuario, :detallePropuesta)";
        }
        
        $stmt = $conn->prepare($sql);

        // Vincular parámetros
        $stmt->bindParam(':idClientes', $idClientes);
        $stmt->bindParam(':tipoServicio', $tipoServicio);
        $stmt->bindParam(':modalidad', $modalidad);
        $stmt->bindParam(':numeroSucursales', $numeroSucursales);
        $stmt->bindParam(':numeroUsuarios', $numeroUsuarios);
        $stmt->bindParam(':tiempo', $tiempo);
        $stmt->bindParam(':idUsuario', $idUsuario);
        $stmt->bindParam(':detallePropuesta', $detallePropuesta);

                // Vincular el idPropuestas solo si es una actualización
                if (isset($idPropuestas) && !empty($idPropuestas)) {
                    $stmt->bindParam(':idPropuestas', $idPropuestas);
                }
        

        // Ejecutar la consulta
        if ($stmt->execute()) {
            // Limpiar los datos del formulario
            $idClientes = '';
            $tipoServicio = '';
            $modalidad = '';
            $numeroSucursales = '';
            $numeroUsuarios = '';
            $tiempo = '';
            $idUsuario = '';
            $detallePropuesta = '';

            header('Location: propuestas.php?success=1');
            exit;
        } else {
            $message = 'Error al guardar la propuesta.';
            $messageclass = 'alert alert-danger';
        }
    } else {
        $message = 'Los campos obligatorios deben ser llenados.';
        $messageclass = 'alert alert-danger';
    }
}

// Cargar datos si se está editando
if (isset($_GET['idPropuestas'])) {
    $idPropuestas = $_GET['idPropuestas'];
    $sql = "SELECT * FROM propuestas WHERE idPropuestas = :idPropuestas";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':idPropuestas', $idPropuestas);
    $stmt->execute();
    $propuestas = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($propuestas) {
        $idClientes = htmlspecialchars($propuestas['idClientes']);
        $tipoServicio = htmlspecialchars($propuestas['tipoServicio']);
        $modalidad = htmlspecialchars($propuestas['modalidad']);
        $numeroSucursales = htmlspecialchars($propuestas['numeroSucursales']);
        $numeroUsuarios = htmlspecialchars($propuestas['numeroUsuarios']);
        $tiempo = htmlspecialchars($propuestas['tiempo']);
        $idUsuario = htmlspecialchars($propuestas['idUsuario']);
        $detallePropuesta = htmlspecialchars($propuestas['detallePropuesta']);
    }

    $isEditing = true; 
}

// Consultar usuarios
$queryUsuarios = "SELECT idUsuario, nombreUsuario FROM usuarios";
$stmtUsuarios = $conn->prepare($queryUsuarios);
$stmtUsuarios->execute();
$usuarios = $stmtUsuarios->fetchAll(PDO::FETCH_ASSOC);

// Consultar clientes
$queryClientes = "SELECT idClientes, nombreEmpresa FROM clientes";
$stmtClientes = $conn->prepare($queryClientes);
$stmtClientes->execute();
$clientes = $stmtClientes->fetchAll(PDO::FETCH_ASSOC);

// Consultar propuestas
$query = "
    SELECT p.idPropuestas, c.nombreEmpresa, p.tipoServicio, p.modalidad, 
           p.numeroSucursales, p.numeroUsuarios, p.tiempo, 
           p.detallePropuesta, u.nombreUsuario
    FROM propuestas p
    JOIN clientes c ON p.idClientes = c.idClientes
    JOIN usuarios u ON p.idUsuario = u.idUsuario
";
$stmt = $conn->prepare($query);
$stmt->execute();
$propuestas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Propuestas</title>
    <link rel="stylesheet" href="../Bootstrap-5.3.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/styles.css">
    <script src="../Bootstrap-5.3.3/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        function confirmDelete() {
            return confirm("¿Estás seguro de que deseas eliminar esta propuesta?");
        }
    </script>
</head>
<body>

<?php require '../partials/menus.php'; ?>

<div class="container-fluid col-12" style="background-color: white;">
    <div class="mx-2">
        <div class="titulomodulo contenidotitulofuncion py-4">
            <span class="iconomenufuncion material-symbols-outlined mx-1"> face </span> 
            Gestión de Propuestas 
        </div>
    </div>
</div>

<form action="propuestas.php" method="post">
    <div class="container-fluid center my-3">
        <section class="desplazar cajaform container" style="margin-right: 0px;">
            <span class="titulofuncion" style="text-align: left;">
                <span style="font-size: 15px;">Crea o edita tus propuestas</span>
            </span>
            <hr>
            <input type="hidden" name="idPropuestas" value="<?= htmlspecialchars($idPropuestas); ?>">

            <div class="row form-outline my-3">
                <div class="col-lg-3 my-2">
                    <label class="form-label etiquetaform">Selecciona tu cliente *</label>
                    <select name="idClientes" class="form-control form-control-lg camposform" required>
                        <option value="">Selecciona un cliente</option>
                        <?php foreach ($clientes as $cliente): ?>
                            <option value="<?= htmlspecialchars($cliente['idClientes']); ?>" <?= (isset($idClientes) && $idClientes == $cliente['idClientes']) ? 'selected' : ''; ?>>
                                <?= htmlspecialchars($cliente['nombreEmpresa']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-lg-3 my-2">
                    <label class="form-label etiquetaform">Selecciona el tipo de servicio *</label>
                    <select name="tipoServicio" class="form-control form-control-lg camposform" required>
                        <option value="">Selecciona un servicio</option>
                        <option value="Orfeo - Capacitación">Orfeo - Capacitación</option>
                        <option value="Orfeo - Desarrollo">Orfeo - Desarrollo</option>
                        <option value="Orfeo - Implantación">Orfeo - Implantación</option>
                        <option value="Gestión documental">Gestión documental</option>
                        <option value="Gestión territorial">Gestión territorial</option>
                        <option value="Investigación">Investigación</option>
                    </select>
                </div>
                <div class="col-lg-3 my-2">
                    <label class="form-label etiquetaform">Selecciona la modalidad del servicio *</label>
                    <select name="modalidad" class="form-control form-control-lg camposform" required>
                        <option value="">Selecciona modalidad del servicio</option>
                        <option value="Presencial">Presencial</option>
                        <option value="Remoto">Remoto</option>
                        <option value="Híbrido">Híbrido</option>
                    </select>
                </div>
                <div class="col-lg-3 my-2">
                    <label class="form-label etiquetaform">Número de sucursales de la empresa *</label>
                    <input type="number" name="numeroSucursales" class="form-control form-control-lg camposform" required value="<?= htmlspecialchars($numeroSucursales); ?>" />
                </div>
            </div>

            <div class="row form-outline my-3">
                <div class="col-lg-3 my-2">
                    <label class="form-label etiquetaform">Número de usuarios *</label>
                    <input type="number" name="numeroUsuarios" class="form-control form-control-lg camposform" required value="<?= htmlspecialchars($numeroUsuarios); ?>" />
                </div>
                <div class="col-lg-3 my-2">
                    <label class="form-label etiquetaform">Tiempo estimado de servicio *</label>
                    <select name="tiempo" class="form-control form-control-lg camposform" required>
                        <option value="">Selecciona tiempo</option>
                        <option value="Un Mes">Un Mes</option>
                        <option value="Cuatro Meses">Cuatro Meses</option>
                        <option value="Seis Meses">Seis Meses</option>
                        <option value="Diez Meses">Diez Meses</option>
                        <option value="Un Año">Un Año</option>
                        <option value="Dos Años">Dos Años</option>
                    </select>   
                 </div>
                <div class="col-lg-3 my-2">
                    <label class="form-label etiquetaform">Asignación de usuario *</label>
                    <select name="idUsuario" class="form-control form-control-lg camposform" required>
                        <option value="">Selecciona un usuario</option>
                        <?php foreach ($usuarios as $usuario): ?>
                            <option value="<?= htmlspecialchars($usuario['idUsuario']); ?>" <?= (isset($idUsuario) && $idUsuario == $usuario['idUsuario']) ? 'selected' : ''; ?>>
                                <?= htmlspecialchars($usuario['nombreUsuario']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="row form-outline my-3">
                <div class="col-lg-12 my-2">
                    <label class="form-label etiquetaform">Detalles de la propuesta *</label>
                    <textarea name="detallePropuesta" class="form-control form-control-lg camposform" required><?= htmlspecialchars($detallePropuesta); ?></textarea>
                </div>
            </div>

            <?php if (!empty($message)): ?>
                <p class="<?= $messageclass ?>"> <?= $message ?></p>
            <?php endif; ?>
            
            <?php if (isset($_GET['success'])): ?>
                <p class="alert alert-success">Propuesta guardada exitosamente.</p>
            <?php endif; ?>

            <?php if (isset($_GET['success_delete'])): ?>
                <p class="alert alert-success">Propuesta eliminada exitosamente.</p>
            <?php endif; ?>

            <?php if (isset($_GET['error_delete'])): ?>
                <p class="alert alert-danger">Error al eliminar la Propuesta.</p>
            <?php endif; ?>

            <div class="container-fluid center my-1">
                <section class="desplazar container text-end" style="margin-right: 0px;">
                    <input type="submit" value="<?= $isEditing ? 'Modificar Propuesta' : 'Crear Propuesta' ?>" class="btn btn-sm textoboton btn-block btn-primary btn-principal px-4">
                    <button class="btn btn-sm btn-outline-primary btn-secundario textoboton px-4 my-1" type="reset"> Limpiar datos </button>
                </section>
            </div>
        </section>
    </div>
</form>

</div>

<div class="container-fluid center my-3">
    <section class="desplazar cajaform container" style="margin-right: 0px;">
        <span class="titulotabla textoiconocentrar " style="text-align: left;"> Listado de propuestas creadas <i class="material-symbols-outlined"> travel_explore </i></span>
        <hr>
        <div class="row form-outline my-3">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th class="encabezadotabla" scope="col">Nombre Empresa</th>
                        <th class="encabezadotabla" scope="col">Servicio</th>
                        <th class="encabezadotabla" scope="col">Modalidad</th>
                        <th class="encabezadotabla" scope="col">No. Sucursales</th>
                        <th class="encabezadotabla" scope="col">No. Usuarios</th>
                        <th class="encabezadotabla" scope="col">Tiempo</th>
                        <th class="encabezadotabla" scope="col">Usuario</th>
                        <th class="encabezadotabla" scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($propuestas as $propuesta): ?>
                                        <tr class="contenidotabla">
                            <td><?php echo htmlspecialchars($propuesta['nombreEmpresa']); ?></td>
                            <td><?php echo htmlspecialchars($propuesta['tipoServicio']); ?></td>
                            <td><?php echo htmlspecialchars($propuesta['modalidad']); ?></td>
                            <td><?php echo htmlspecialchars($propuesta['numeroSucursales']); ?></td>
                            <td><?php echo htmlspecialchars($propuesta['numeroUsuarios']); ?></td>
                            <td><?php echo htmlspecialchars($propuesta['tiempo']); ?></td>
                            <td><?php echo htmlspecialchars($propuesta['nombreUsuario']); ?></td>
                            <td>
                                <a style="text-decoration:none;" href="javascript:void(0);" onclick="showDetails('<?php echo htmlspecialchars($propuesta['detallePropuesta']); ?>');">
                                    <span class="iconosaccion material-symbols-outlined">info</span>
                                </a> &nbsp;
                                <a style="text-decoration:none;" href="propuestas.php?idPropuestas=<?= $propuesta['idPropuestas']; ?>">
                                    <span class="iconosaccion iconomodificar material-symbols-outlined">edit</span>
                                </a> &nbsp;
                                                          </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </section>
</div>

<!-- Modal -->
<div class="modal fade" id="detailsModal" tabindex="-1" role="dialog" aria-labelledby="detailsModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <p class="titulofuncion" id="detailsModalLabel">Detalles de la Propuesta</p>
             </div>
            <div class="contenidomodal modal-body" id="modalDetailsBody">
                <!-- Aquí se insertarán los detalles -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<script>
    function showDetails(details) {
        // Inserta los detalles en el modal
        document.getElementById('modalDetailsBody').innerText = details;
        // Muestra el modal
        $('#detailsModal').modal('show');
    }
</script>
</body>
</html>

