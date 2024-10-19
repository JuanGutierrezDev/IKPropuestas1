<?php
session_start();

// Verifica si la sesión está activa
if (!isset($_SESSION['idUsuario'])) { 
    header("Location: ../mdl_ingreso_registro/login.php"); 
    exit();
}
?>