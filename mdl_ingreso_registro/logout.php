<?php
session_start(); // Inicia la sesión

// Elimina todas las variables de sesión
$_SESSION = array();

// Si se desea, destruir la sesión
session_destroy();

// Redirige al usuario a la página de inicio o login
header("Location: login.php"); 
exit;
?>