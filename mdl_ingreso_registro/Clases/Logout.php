<?php

namespace App;

class Logout {
    public function execute() {
        session_start(); // Inicia la sesión

        // Elimina todas las variables de sesión
        $_SESSION = array();

        // Destruye la sesión
        session_destroy();

        // Redirige al login
        header("Location: login.php");
        exit;
    }
}
