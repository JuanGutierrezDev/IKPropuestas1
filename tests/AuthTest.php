<?php
use PHPUnit\Framework\TestCase;

require __DIR__ . '/../mdl_ingreso_registro/Clases/Auth.php';

class AuthTest extends TestCase {
    private $conn;
    private $auth;

    protected function setUp(): void {
        // Crear una conexión a una base de datos en memoria
        $this->conn = new PDO('sqlite::memory:');
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Crear la tabla `usuarios` para pruebas
        $this->conn->exec("CREATE TABLE usuarios (
            idUsuario INTEGER PRIMARY KEY,
            email TEXT NOT NULL,
            passUsuario TEXT NOT NULL,
            nombreUsuario TEXT NOT NULL
        )");

        // Insertar un usuario de prueba
        $passwordHash = password_hash('password123', PASSWORD_BCRYPT);
        $this->conn->exec("INSERT INTO usuarios (email, passUsuario, nombreUsuario) VALUES ('test@example.com', '$passwordHash', 'TestUser')");

        // Instanciar la clase Auth con la conexión de prueba
        $this->auth = new Auth($this->conn);
    }

    public function testAuthenticateWithValidCredentials() {
        $result = $this->auth->authenticate('test@example.com', 'password123');
        $this->assertIsArray($result);
        $this->assertEquals('TestUser', $result['nombreUsuario']);
    }

    public function testAuthenticateWithInvalidCredentials() {
        $result = $this->auth->authenticate('test@example.com', 'wrongpassword');
        $this->assertFalse($result);
    }
    
    public function testAuthenticateWithNonExistentUser() {
        $result = $this->auth->authenticate('nonexistent@example.com', 'password123');
        $this->assertFalse($result);
    }
}