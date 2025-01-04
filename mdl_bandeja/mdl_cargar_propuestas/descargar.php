<?php
// Verificar si se ha pasado un parámetro 'archivo' por GET
if (isset($_GET['archivo'])) {
    // Obtener el nombre del archivo desde la URL
    $nombreArchivo = $_GET['archivo'];

    // Ruta completa del archivo en el servidor
    $rutaArchivo = '../mdl_cargar_propuestas/bodegaArchivos/' . $nombreArchivo;

    // Verificar si el archivo existe
    if (file_exists($rutaArchivo)) {
        // Definir los encabezados para la descarga del archivo
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="' . basename($rutaArchivo) . '"');
        header('Content-Length: ' . filesize($rutaArchivo));

        // Leer el archivo y enviarlo al navegador
        readfile($rutaArchivo);
        exit; // Termina el script después de la descarga
    } else {
        echo "El archivo no existe.";
    }
} else {
    echo "No se especificó un archivo para descargar.";
}
?>