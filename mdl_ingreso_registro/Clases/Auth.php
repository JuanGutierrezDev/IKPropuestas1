<?php
class Auth {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function authenticate($email, $password) {
        // Limpiar y validar el email
        $email = trim($email);

        // Preparar la consulta SQL
        $stmt = $this->conn->prepare('SELECT idUsuario, email, passUsuario, nombreUsuario FROM usuarios WHERE email = :email');
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verificar si el usuario existe y validar la contraseña
        if ($result && password_verify($password, $result['passUsuario'])) {
            return [
                'idUsuario' => $result['idUsuario'],
                'nombreUsuario' => $result['nombreUsuario']
            ];
        }
        return false;
    }
}
