<?php
require '../database.php'; 

// Verificar si se ha enviado un ID
if (isset($_GET['idPropuestas'])) {
    $idPropuestas = $_GET['idPropuestas'];

    // Preparar la consulta de eliminación
    $sql = "DELETE FROM propuestas WHERE idPropuestas = :idPropuestas";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':idPropuestas', $idPropuestas);

    // Ejecutar la consulta
    if ($stmt->execute()) {
        // Redirigir después de eliminar
        header('Location: propuestas.php?success_delete=1');
        exit;
    } else {
        // Manejar error
        header('Location: propuestas.php?error_delete=1');
        exit;
    }
} else {
    // Redirigir si no se proporciona un ID
    header('Location: propuestas.php');
    exit;
}
?>