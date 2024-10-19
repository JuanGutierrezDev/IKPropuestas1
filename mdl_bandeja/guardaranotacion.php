<?php
require '../database.php'; 
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $anotacion = $_POST['anotacion'];
    $idPropuestas = $_POST['idPropuestas'];
    $userId = $_SESSION['idUsuario']; // ID del usuario que realiza la anotación
    $nombreUsuario = $_SESSION['nombreUsuario']; // Nombre del usuario que realiza la anotación

    // Agregar "Anotación:" antes del texto ingresado
    $anotacionConTexto = "Anotación: " . $anotacion;

    try {
        $stmtHistory = $conn->prepare("INSERT INTO historia (idPropuestas, idUsuario, accion) VALUES (:idPropuestas, :idUsuario, :accion)");
        $stmtHistory->execute([
            'idPropuestas' => $idPropuestas,
            'idUsuario' => $userId,
            'accion' => $anotacionConTexto // Guardar el texto modificado
        ]);
        
        echo json_encode(['status' => 'success']);
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}
