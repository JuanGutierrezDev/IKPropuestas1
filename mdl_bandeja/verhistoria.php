<?php
require '../database.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idPropuestas = $_POST['idPropuestas'];

    $stmt = $conn->prepare("SELECT h.accion, h.fecha, u.nombreUsuario FROM historia h JOIN usuarios u ON h.idUsuario = u.idUsuario WHERE h.idPropuestas = :idPropuestas ORDER BY h.fecha DESC");
    $stmt->execute(['idPropuestas' => $idPropuestas]);
    $historia = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($historia);
}
?>