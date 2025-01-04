<?php
require '../partials/validasesion.php'; 
require '../database.php'; 

// Verificar si el parámetro 'mensaje' está presente en la URL
if (isset($_GET['mensaje'])) {
    $mensaje = $_GET['mensaje'];
    echo "<script>alert('$mensaje');</script>"; // Mostrar el mensaje en una alerta del navegador
}

// Obtener el ID del usuario actual
$idUsuario = $_SESSION['idUsuario'];



// Preparar y ejecutar la consulta
$stmt = $conn->prepare("
    SELECT 
        c.nombreEmpresa,
        p.tipoServicio,
        p.modalidad,
        p.numeroSucursales,
        p.numeroUsuarios,
        p.tiempo,
        p.detallePropuesta,
        p.archivoPropuesta,
        p.validaPropuesta,
        p.idPropuestas
    FROM 
        propuestas p
    JOIN 
        clientes c ON p.idClientes = c.idClientes
    WHERE 
        p.idUsuario = :idUsuario 
");
$stmt->execute(['idUsuario' => $idUsuario]);
$propuestas = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Traer nombre usuario
$nombreUsuario = isset($_SESSION['nombreUsuario']) ? $_SESSION['nombreUsuario'] : 'Usuario';

// Obtener usuarios para trasladar
$stmtUsers = $conn->prepare("SELECT idUsuario, nombreUsuario FROM usuarios");
$stmtUsers->execute();
$usuarios = $stmtUsers->fetchAll(PDO::FETCH_ASSOC);
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bandeja de propuestas</title>
    <link rel="stylesheet" href="../Bootstrap-5.3.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/styles.css">
    <script src="../Bootstrap-5.3.3/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    
    
</head>
<body>

<?php require '../partials/menus.php'; ?>

<div class="container-fluid col-12" style="background-color: white;">
    <div class="mx-2">
        <div class="titulomodulo contenidotitulofuncion py-4">
            <span class="iconomenufuncion material-symbols-outlined mx-1">mail</span>
            Bandeja de propuestas
        </div>
    </div>
</div>

<div class="container-fluid center my-3">
    <section class="desplazar cajaform container" style="margin-right: 0px;">
        <span class="titulofuncion" style="text-align: left;">
            <span style="font-size: 18px;">Hola <?php echo $_SESSION['nombreUsuario']; ?></span> Tienes <span style="font-size: 18px;"><?php echo count($propuestas); ?></span> propuestas por gestionar
        </span>
        <hr>
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th class="encabezadotabla" scope="col">Cliente</th>
                    <th class="encabezadotabla" scope="col">Servicio</th>
                    <th class="encabezadotabla" scope="col">Modalidad</th>
                    <th class="encabezadotabla" scope="col">Sucursales</th>
                    <th class="encabezadotabla" scope="col">Usuarios</th>
                    <th class="encabezadotabla" scope="col">Tiempo</th>
                    <th class="encabezadotabla " scope="col">Detalle</th>
                    <th class="encabezadotabla" scope="col">Archivo</th>
                    <th class="encabezadotabla" scope="col">Aprueba Envío</th>
                    <th class="encabezadotabla" scope="col">Historia</th>
                    <th class="encabezadotabla" scope="col">
                        <input type="checkbox" id="checkAll" />
                    </th>
                </tr>
            </thead>
            <tbody class="contenidotabla">
                <?php foreach ($propuestas as $index => $propuesta): ?>
                <tr>
                    <td><?php echo htmlspecialchars($propuesta['nombreEmpresa']); ?></td>
                    <td><?php echo htmlspecialchars($propuesta['tipoServicio']); ?></td>
                    <td><?php echo htmlspecialchars($propuesta['modalidad']); ?></td>
                    <td><?php echo htmlspecialchars($propuesta['numeroSucursales']); ?></td>
                    <td><?php echo htmlspecialchars($propuesta['numeroUsuarios']); ?></td>
                    <td><?php echo htmlspecialchars($propuesta['tiempo']); ?></td>
                    <td>
                        <a style="text-decoration:none;" href="javascript:void(0);" onclick="showDetails('<?php echo htmlspecialchars($propuesta['detallePropuesta']); ?>');">
                            <span class="iconosaccion material-symbols-outlined">info</span>
                        </a>
                    </td>
                    <td>
                                                <?php
                                if (!empty($propuesta['archivoPropuesta'])) {
                                    // Obtener el nombre del archivo
                                    $nombreArchivo = basename($propuesta['archivoPropuesta']);
                                    echo '<a href="../mdl_bandeja/mdl_cargar_propuestas/descargar.php?archivo=' . $nombreArchivo . '" download>Descargar</a>';
                                } else {
                                    echo 'No hay archivo';
                                }
                                ?>
                    </td>
                    <td>
                    <?php 
                        if (empty($propuesta['validaPropuesta']) || $propuesta['validaPropuesta'] === 'En espera') {
                            echo '<div class="iconoytexto"> <span class="marginicono amarillo iconosaccion material-symbols-outlined">pending</span>En espera</div>' ;
                        } elseif ($propuesta['validaPropuesta'] === 'Corregir Propuesta') {
                            echo '<div class="iconoytexto"> <span class="marginicono rojo iconosaccion material-symbols-outlined">assignment</span> Corregir</div>';
                        } elseif ($propuesta['validaPropuesta'] === 'Aprobar Propuesta') {
                            echo '<div class="iconoytexto"> <span class="marginicono verde iconosaccion verde material-symbols-outlined">how_to_reg</span> Aprobado</div>';
                        }
                    ?>
                    </td>
                    <td>
                    <span class="icono-historia" onclick="showHistory(<?php echo htmlspecialchars($propuesta['idPropuestas']); ?>)" style="cursor: pointer;">
                    <i class="fas fa-history"></i> <!-- Cambia esto por el ícono que prefieras -->
</span>        </td>

                    <td>
                        <input type="checkbox" class="row-checkbox" data-id="<?php echo htmlspecialchars($propuesta['idPropuestas']); ?>" data-estado-actual="<?php echo htmlspecialchars($propuesta['validaPropuesta']); ?>" />
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="pt-1 text-end m-1 w-100 button-container" id="buttonContainer">
            <button class="btn btn-xs btn-primary btn-interno-1" type="button" data-bs-toggle="modal" data-bs-target="#trasladarModal">
                <i class="fa-solid fa-paper-plane"></i> Trasladar Propuestas
            </button>




            <button class="btn btn-xs btn-outline-primary btn-interno-2" type="button" data-bs-toggle="modal" data-bs-target="#cambiarEstadoModal">
                <i class="fa-solid fa-file-pen"></i> Cambia el estado de la propuesta
            </button>
            <button type="button" class="btn btn-xs btn-outline-primary btn-interno-3" data-bs-toggle="modal" data-bs-target="#anotacionModal" onclick="setSelectedProposalId(<?php echo htmlspecialchars($propuesta['idPropuestas']); ?>)">
            <i class="fa-solid fa-file-pen"></i> Agrega anotación a la propuesta
            </button>

   <!-- Formulario para cargar el archivo -->

            <form action="./mdl_cargar_propuestas/subir_archivo.php" method="POST" enctype="multipart/form-data" class="mt-3">
                <label for="archivo" class="textocard">Suba su propuesta (PDF):</label>
                <input type="file" name="archivo" id="archivo" class="textocard1" accept="application/pdf" required>
                <input type="hidden" name="idPropuestas[]" id="idPropuestas" value="">
                <button type="submit" class="btn btn-xs btn-outline-success btn-carga" name="submit">Subir archivo</button>
            </form>
            


<script>
    let selectedProposalId = null;

    function setSelectedProposalId(id) {
        selectedProposalId = id;
        document.getElementById('anotacionTexto').value = ''; // Limpiar el texto anterior
    }
</script>
            
        </div>

    </section>
</div>

<script>
    // Obtener el checkbox de seleccionar todos, los checkboxes de las filas y el formulario
    const checkAll = document.getElementById('checkAll');
    const rowCheckboxes = document.querySelectorAll('.row-checkbox');
    const buttonContainer = document.getElementById('buttonContainer');
    const form = document.querySelector('form');
    const hiddenField = document.getElementById('idPropuestas'); // Este es el campo oculto

    // Función para actualizar el estado de los botones
    function updateButtonVisibility() {
        const anyChecked = Array.from(rowCheckboxes).some(cb => cb.checked);
        if (anyChecked) {
            buttonContainer.classList.add('show');
        } else {
            buttonContainer.classList.remove('show');
        }
    }

    // Función para actualizar el valor del campo oculto con los ID de las propuestas seleccionadas
    function updateSelectedProposals() {
        const selectedProposals = [];
        rowCheckboxes.forEach(cb => {
            if (cb.checked) {
                selectedProposals.push(cb.getAttribute('data-id'));
            }
        });

        // Actualizar el campo oculto con los IDs seleccionados
        hiddenField.value = selectedProposals.join(',');
    }

    // Event listener para el checkbox de seleccionar todos
    checkAll.addEventListener('change', function() {
        rowCheckboxes.forEach(cb => cb.checked = checkAll.checked);
        updateButtonVisibility();
        updateSelectedProposals(); // Actualiza los IDs cuando cambia la selección
    });

    // Event listeners para los checkboxes de las filas
    rowCheckboxes.forEach(cb => cb.addEventListener('change', function() {
        updateButtonVisibility();
        updateSelectedProposals(); // Actualiza los IDs cuando se cambia una fila
    }));

    // Event listener para el envío del formulario
    form.onsubmit = function() {
        updateSelectedProposals(); // Asegura que los IDs seleccionados se envíen al servidor antes de enviar el formulario
    };
</script>









<!-- Modal para Detalles -->
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
                <button type="button" class="btn btn-sm btn-primary btn-interno-1" data-bs-dismiss="modal">Cerrar</button>
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

<!-- Modal para Cambiar el estado de la propuesta -->
<div class="modal fade" id="cambiarEstadoModal" tabindex="-1" role="dialog" aria-labelledby="cambiarEstadoModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <p class="titulofuncion" id="cambiarEstadoModalLabel">Cambiar Estado de la Propuesta</p>
            </div>
            <div class="contenidomodal modal-body">
                <p>Seleccione el nuevo estado de la propuesta:</p>
                <select id="estadoSelect" class="form-select">
                    <option value="">Seleccione un estado</option>
                    <option value="Corregir Propuesta">Corregir Propuesta</option>
                    <option value="Aprobar Propuesta">Aprobar Propuesta</option>
                    <option value="En espera">En espera</option>
                </select>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-primary btn-interno-1" id="confirmarCambioEstado">Confirmar Cambio</button>
                <button type="button" class="btn btn-sm btn-outline-primary btn-interno-2" data-bs-dismiss="modal">Cancelar</button>
                
            </div>
        </div>
    </div>
</div>

<script>
    // Funcionalidad para confirmar el cambio de estado
    document.getElementById('confirmarCambioEstado').addEventListener('click', function() {
        const selectedState = document.getElementById('estadoSelect').value;

        const selectedProposals = Array.from(rowCheckboxes)
            .filter(checkbox => checkbox.checked)
            .map(checkbox => checkbox.dataset.id);

        if (selectedProposals.length === 0) {
            alert('Por favor, seleccione al menos una propuesta para cambiar el estado.');
            return;
        }

        // Enviar una solicitud AJAX para actualizar el estado de las propuestas seleccionadas
        $.ajax({
            url: 'cambiarestadopropuestas.php',
            type: 'POST',
            data: {
                state: selectedState, // Enviar el estado seleccionado directamente
                proposals: selectedProposals
            },
            success: function(response) {
                const result = JSON.parse(response);
                if (result.status === 'success') {
                    alert('Estado de las propuestas actualizado exitosamente.');
                    location.reload();
                } else {
                    alert('Error: ' + result.message);
                }
            },
            error: function() {
                alert('Hubo un error al cambiar el estado de las propuestas.');
            }
        });

        // Cerrar el modal
        $('#cambiarEstadoModal').modal('hide');
    });
</script>

<!-- Modal para Trasladar Propuestas -->
<div class="modal fade" id="trasladarModal" tabindex="-1" role="dialog" aria-labelledby="trasladarModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <p class="titulofuncion" id="trasladarModalLabel">Trasladar Propuestas</p>
            </div>
            <div class="contenidomodal modal-body">
                <p>Seleccione el usuario al que desea trasladar las propuestas seleccionadas:</p>
                <select id="usuarioSelect" class="form-select">
                    <option value="">Seleccione un usuario</option>
                    <?php foreach ($usuarios as $usuario): ?>
                        <option value="<?php echo htmlspecialchars($usuario['idUsuario']); ?>"><?php echo htmlspecialchars($usuario['nombreUsuario']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-primary btn-interno-1" id="confirmTransfer">Confirmar Traslado</button>
                <button type="button" class="btn btn-sm btn-outline-primary btn-interno-2" data-bs-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>

<script>
    // Funcionalidad para confirmar el traslado
    document.getElementById('confirmTransfer').addEventListener('click', function() {
        const selectedUserId = document.getElementById('usuarioSelect').value;
        if (!selectedUserId) {
            alert('Por favor, seleccione un usuario para trasladar las propuestas.');
            return;
        }

        const selectedProposals = Array.from(document.querySelectorAll('.row-checkbox:checked'))
            .map(checkbox => checkbox.dataset.id);

        if (selectedProposals.length === 0) {
            alert('Por favor, seleccione al menos una propuesta para trasladar.');
            return;
        }

        // Enviar una solicitud AJAX para actualizar el idUsuario de las propuestas seleccionadas
        $.ajax({
            url: 'trasladarpropuestas.php', // Asegúrate de que la URL sea correcta
            type: 'POST',
            data: {
                idUsuario: selectedUserId, // Cambiado a idUsuario
                proposals: selectedProposals
            },
            success: function(response) {
                const result = JSON.parse(response);
                if (result.status === 'success') {
                    alert('Propuestas trasladadas exitosamente.');
                    location.reload(); // Recargar la página para ver los cambios
                } else {
                    alert('Error: ' + result.message);
                }
            },
            error: function() {
                alert('Hubo un error al trasladar las propuestas.');
            }
        });

        // Mueve esta línea aquí para asegurarte de que se cierra después de la solicitud
        $('#trasladarModal').modal('hide');
    });
</script>

<!-- Modal para agregar anotación -->
<div class="modal fade" id="anotacionModal" tabindex="-1" role="dialog" aria-labelledby="anotacionModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <p class="titulofuncion" id="anotacionModalLabel">Agregar Anotación</p>
            </div>
            <div class="contenidomodal modal-body">
                <textarea id="anotacionTexto" class="form-control" rows="5" placeholder="Escriba su anotación aquí..."></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-primary btn-interno-1" id="guardarAnotacion">Guardar Anotación</button>
                <button type="button" class="btn btn-sm btn-outline-primary btn-interno-2" data-bs-dismiss="modal">Cancelar</button>

            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('guardarAnotacion').addEventListener('click', function() {
        const anotacion = document.getElementById('anotacionTexto').value;

        if (!anotacion.trim()) {
            alert('Por favor, escriba una anotación.');
            return;
        }

        // Enviar la anotación al servidor
        $.ajax({
            url: 'guardarAnotacion.php', // Archivo PHP que procesará la anotación
            type: 'POST',
            data: {
                anotacion: anotacion,
                idPropuestas: selectedProposalId // definir este ID en el contexto correcto
            },
            success: function(response) {
                const result = JSON.parse(response);
                if (result.status === 'success') {
                    alert('Anotación guardada exitosamente.');
                    location.reload(); // Recargar la página para ver los cambios
                } else {
                    alert('Error: ' + result.message);
                }
            },
            error: function() {
                alert('Hubo un error al guardar la anotación.');
            }
        });

        // Cerrar el modal
        $('#anotacionModal').modal('hide');
    });
</script>

<!-- Modal para Mostrar Historia -->
<div class="modal fade" id="historyModal" tabindex="-1" role="dialog" aria-labelledby="historyModalLabel" aria-hidden="true">
    <div class="modal-dialog ancho-modal-tabla" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title titulofuncion" id="historyModalLabel">Historia de la Propuesta</h5>
                <button type="button btn-sm" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body contenidotabladt">
                <table id="historyTable" class="table table-striped table-hover table-bordered">
                    <thead class="p-5">
                        <tr>
                            <th class="encabezadotabladt">Usuario</th>
                            <th class="encabezadotabladt">Acción</th>
                            <th class="encabezadotabladt" >Fecha</th>
                        </tr>
                    </thead>
                    <tbody id="modalHistoryBody" class="contenidotabladt">
                        <!-- Aquí se insertará la historia -->
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<script>
    $.fn.dataTable.ext.errMode = 'none'; // Esto suprime los mensajes de error

function showHistory(proposalId) {
    $.ajax({
        url: 'verhistoria.php', // Cambia a la URL que manejará la obtención de la historia
        type: 'POST',
        data: { idPropuestas: proposalId },
        success: function(response) {
            const historyData = JSON.parse(response);
            const historyBody = document.getElementById('modalHistoryBody');

            // Limpiar el contenido anterior
            historyBody.innerHTML = '';

            if (historyData.length === 0) {
                historyBody.innerHTML = '<tr><td colspan="3">No hay historia registrada para esta propuesta.</td></tr>';
            } else {
                historyData.forEach(item => {
                    historyBody.innerHTML += `
                        <tr>
                            <td>${item.nombreUsuario}</td> <!-- Cambia esto según cómo almacenes el nombre de usuario -->
                            <td>${item.accion}</td>
                            <td>${item.fecha}</td> <!-- Asegúrate de que este campo exista en tu consulta -->
                        </tr>
                    `;
                });
            }

            // Inicializa el DataTable
            $('#historyTable').DataTable({
    language: {
        "sEmptyTable": "No hay datos disponibles en la tabla",
        "sInfo": "Mostrando _START_ a _END_ de _TOTAL_ entradas",
        "sInfoEmpty": "Mostrando 0 a 0 de 0 entradas",
        "sInfoFiltered": "(filtrado de _MAX_ entradas totales)",
        "sLengthMenu": "Mostrar _MENU_ entradas",
        "sLoadingRecords": "Cargando...",
        "sProcessing": "Procesando...",
        "sSearch": "Buscar:",
        "sZeroRecords": "No se encontraron resultados",
        "oPaginate": {
            "sFirst": "Primero",
            "sLast": "Último",
            "sNext": "Siguiente",
            "sPrevious": "Anterior"
        },
        "oAria": {
            "sSortAscending": ": activar para ordenar la columna de manera ascendente",
            "sSortDescending": ": activar para ordenar la columna de manera descendente"
        }
    }
});            

            // Mostrar el modal
            $('#historyModal').modal('show');


            
        },
        error: function() {
            alert('Hubo un error al cargar la historia de la propuesta.');
        }
    });
}

</script>



</body>
</html>
