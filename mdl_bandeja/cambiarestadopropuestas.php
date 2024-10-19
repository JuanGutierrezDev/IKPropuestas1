<?php
require '../database.php'; 
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newState = $_POST['state']; // Obtiene el nuevo estado
    $proposals = $_POST['proposals'];
    $userId = $_SESSION['idUsuario']; // ID del usuario que realiza el cambio
    $nombreUsuario = $_SESSION['nombreUsuario']; // Nombre del usuario que realiza el cambio

    try {
        $conn->beginTransaction();

        // Preparar la consulta para obtener el estado actual de las propuestas
        $stmtSelect = $conn->prepare("SELECT idPropuestas, validaPropuesta FROM propuestas WHERE idPropuestas IN (" . implode(',', array_fill(0, count($proposals), '?')) . ")");
        $stmtSelect->execute($proposals);
        $currentStates = $stmtSelect->fetchAll(PDO::FETCH_ASSOC);

        // Actualizar el estado de las propuestas seleccionadas
        $stmtUpdate = $conn->prepare("UPDATE propuestas SET validaPropuesta = :validaPropuesta WHERE idPropuestas = :idPropuestas");

        foreach ($currentStates as $currentState) {
            $proposalId = $currentState['idPropuestas'];
            $estadoAnterior = $currentState['validaPropuesta']; // Captura el estado anterior

            // Actualiza el estado de la propuesta
            $stmtUpdate->execute([
                'validaPropuesta' => $newState, // Aquí se guarda el nuevo estado
                'idPropuestas' => $proposalId
            ]);

            // Registrar la acción en la tabla historia
            $accion = "Cambio de estado por " . $nombreUsuario . " de " . ($estadoAnterior ?? 'null') . " a " . $newState;
            $stmtHistory = $conn->prepare("INSERT INTO historia (idPropuestas, idUsuario, accion) VALUES (:idPropuestas, :idUsuario, :accion)");
            $stmtHistory->execute([
                'idPropuestas' => $proposalId,
                'idUsuario' => $userId,
                'accion' => $accion
            ]);
        }

        $conn->commit();
        echo json_encode(['status' => 'success']);
    } catch (Exception $e) {
        $conn->rollBack();
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}
