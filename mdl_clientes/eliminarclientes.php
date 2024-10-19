<?php
require '../database.php'; 

// Verificar si se ha enviado un ID
if (isset($_GET['idClientes'])) {
    $idClientes = $_GET['idClientes'];

    // Preparar la consulta de eliminación
    $sql = "DELETE FROM clientes WHERE idClientes = :idClientes";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':idClientes', $idClientes);

    // Ejecutar la consulta
    if ($stmt->execute()) {
        // Redirigir después de eliminar
        header('Location: clientes.php?success_delete=1');
        exit;
    } else {
        // Manejar error
        header('Location: clientes.php?error_delete=1');
        exit;
    }
} else {
    // Redirigir si no se proporciona un ID
    header('Location: clientes.php');
    exit;
}
?>