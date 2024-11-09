<?php

use PHPUnit\Framework\TestCase;
use App\Logout;
require __DIR__ . '/../mdl_ingreso_registro/Clases/Logout.php';

class LogoutTest extends TestCase {

    public function testExecuteLogout() {
        // Simulamos que la sesión está activa
        $_SESSION['user'] = 'testUser';

        // Creamos una instancia de la clase Logout
        $logout = new Logout();

        // Usamos output buffering para interceptar la redirección
        ob_start();
        $logout->execute();
        $output = ob_get_clean();

        // Verificamos que la sesión ha sido destruida
        $this->assertEmpty($_SESSION);

        // Verificamos la redirección
        // PHPUnit no puede verificar directamente la llamada a header(), pero puedes comprobar la salida.
        // Aquí puedes mockear cabeceras si es necesario.
    }
}