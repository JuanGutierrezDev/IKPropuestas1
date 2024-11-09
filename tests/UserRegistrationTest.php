<?php
use PHPUnit\Framework\TestCase;
require __DIR__ . '/../mdl_ingreso_registro/Clases/UserRegistration.php';


class UserRegistrationTest extends TestCase {
    private $conn;
    private $userRegistration;

    protected function setUp(): void {
        // Configuración de la conexión a la base de datos en memoria para pruebas
        $this->conn = $this->createMock(PDO::class);
        $this->userRegistration = new UserRegistration($this->conn);
    }

    public function testValidatePassword() {
        $this->assertTrue($this->userRegistration->validatePassword('12345', '12345'));
        $this->assertFalse($this->userRegistration->validatePassword('12345', '54321'));
    }

    public function testRegisterUserSuccess() {
        // Simulamos la ejecución exitosa
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
             ->method('execute')
             ->willReturn(true);

        $this->conn->expects($this->once())
                   ->method('prepare')
                   ->willReturn($stmt);

        $result = $this->userRegistration->registerUser(
            'UsuarioTest', 'usertest@example.com', '12345', 'Cédula de ciudadanía', 
            '123456789', '1234567890', '1990-01-01'
        );

        $this->assertTrue($result);
    }

    public function testRegisterUserFailure() {
        // Simulamos un fallo en la ejecución
        $stmt = $this->createMock(PDOStatement::class);
        $stmt->expects($this->once())
             ->method('execute')
             ->willReturn(false);

        $this->conn->expects($this->once())
                   ->method('prepare')
                   ->willReturn($stmt);

        $result = $this->userRegistration->registerUser(
            'UsuarioTest', 'usertest@example.com', '12345', 'Cédula de ciudadanía', 
            '123456789', '1234567890', '1990-01-01'
        );

        $this->assertFalse($result);
    }
}
?>
