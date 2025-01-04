<?php
// Iniciar sesión para acceder al ID de la propuesta almacenado en la sesión
session_start();

// Verificar si se ha enviado el formulario
if (isset($_POST['submit'])) {
    // Verifica si se han seleccionado las propuestas y si se ha enviado un archivo
    if (isset($_POST['idPropuestas']) && !empty($_FILES['archivo']) && $_FILES['archivo']['error'] == 0) {
        // Recibir las propuestas seleccionadas
        $idPropuestasSeleccionadas = $_POST['idPropuestas'];

        // Directorio donde se guardarán los archivos subidos
        $directorioDestino = 'bodegaArchivos/'; // Asegúrate de que esta carpeta exista y tenga permisos de escritura

        // Verifica si el directorio existe, si no, lo crea
        if (!is_dir($directorioDestino)) {
            mkdir($directorioDestino, 0777, true); // Crea el directorio con permisos de escritura
        }

        // Obtén el nombre y la extensión del archivo
        $nombreArchivo = $_FILES['archivo']['name'];
        $extensionArchivo = pathinfo($nombreArchivo, PATHINFO_EXTENSION);

        // Verifica si el archivo es un PDF
        if ($extensionArchivo === 'pdf') {
            // Generar un nombre único para el archivo en el servidor (usamos la fecha y hora actual)
            $nombreArchivoUnico = time() . '_' . $nombreArchivo;

            // Ruta completa donde se guardará el archivo
            $rutaArchivo = $directorioDestino . $nombreArchivoUnico;

            // Mueve el archivo al directorio de destino
            if (move_uploaded_file($_FILES['archivo']['tmp_name'], $rutaArchivo)) {
                // Ahora actualizamos la base de datos con la ruta del archivo cargado
                try {
                    // Conexión a la base de datos (ajusta con tus credenciales)
                    $pdo = new PDO('mysql:host=localhost;dbname=bdik_propuestas', 'root', '');
                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    // Prepara la consulta SQL para actualizar la columna 'archivoPropuesta' para todas las propuestas seleccionadas
                    $sql = "UPDATE propuestas SET archivoPropuesta = :archivo WHERE idPropuestas = :idPropuesta";
                    $stmt = $pdo->prepare($sql);

                    // Asocia los valores y ejecuta la consulta para cada propuesta seleccionada
                    foreach ($idPropuestasSeleccionadas as $idPropuesta) {
                        // Asocia el valor para cada propuesta seleccionada
                        $stmt->bindParam(':archivo', $rutaArchivo);
                        $stmt->bindParam(':idPropuesta', $idPropuesta);
                        $stmt->execute();
                    }

                    // Redirigir a la página de destino con un parámetro 'mensaje' en la URL
                    header("Location: ../bandeja.php?mensaje=Archivo%20cargado%20correctamente");
                    exit(); // Asegurarse de que no se ejecute más código después de la redirección
                } catch (PDOException $e) {
                    echo "Error de base de datos: " . $e->getMessage();
                }
            } else {
                echo "Hubo un error al mover el archivo. Código de error: " . $_FILES['archivo']['error'];
            }
        } else {
            echo "Solo se permiten archivos PDF.";
        }
    } else {
        echo "No se seleccionó ningún archivo o no se seleccionaron propuestas.";
    }
}
?>
