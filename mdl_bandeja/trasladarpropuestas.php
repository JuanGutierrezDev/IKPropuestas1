<?php
require '../database.php'; 
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idUsuario = $_POST['idUsuario']; // ID del usuario seleccionado
    $proposals = $_POST['proposals']; // IDs de las propuestas seleccionadas

    // Verificar que el usuario existe en la tabla usuarios
    $stmtUser = $conn->prepare("SELECT nombreUsuario FROM usuarios WHERE idUsuario = :idUsuario");
    $stmtUser->execute(['idUsuario' => $idUsuario]);
    $userDetails = $stmtUser->fetch(PDO::FETCH_ASSOC);

    if (!$userDetails) {
        echo json_encode(['status' => 'error', 'message' => 'Usuario no encontrado.']);
        exit;
    }

    $nuevoNombreUsuario = $userDetails['nombreUsuario']; // Nombre del nuevo usuario

    try {
        $conn->beginTransaction();

        // Actualizar el idUsuario en las propuestas
        $stmtUpdate = $conn->prepare("UPDATE propuestas SET idUsuario = :idUsuario WHERE idPropuestas = :idPropuestas");

        foreach ($proposals as $proposalId) {
            $stmtUpdate->execute(['idUsuario' => $idUsuario, 'idPropuestas' => $proposalId]);
        }

        // Registrar la acción en la tabla historia
        $stmtHistory = $conn->prepare("INSERT INTO historia (idPropuestas, idUsuario, accion, fecha) VALUES (:idPropuestas, :idUsuario, :accion, NOW())");

        foreach ($proposals as $proposalId) {
            $stmtHistory->execute([
                'idPropuestas' => $proposalId,
                'idUsuario' => $_SESSION['idUsuario'], // ID del usuario que realiza el traslado
                'accion' => "Traslado de usuario " . $_SESSION['nombreUsuario'] . " a usuario " . $nuevoNombreUsuario
            ]);
        }

        $conn->commit();
        echo json_encode(['status' => 'success']);
    } catch (Exception $e) {
        $conn->rollBack();
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Método no permitido.']);
}
