<?php
class UserRegistration {
    private $conn;

    public function __construct($dbConnection) {
        $this->conn = $dbConnection;
    }

    public function validatePassword($password, $confirmPassword) {
        return $password === $confirmPassword;
    }

    public function registerUser($nombreUsuario, $email, $passUsuario, $tipoIdentificacion, $numeroIdentificacion, $telefonoUsuario, $fechaNacimiento) {
        $sql = "INSERT INTO usuarios (nombreUsuario, email, passUsuario, tipoIdentificacion, numeroIdentificacion, telefonoUsuario, fechaNacimiento) 
                VALUES (:nombreUsuario, :email, :passUsuario, :tipoIdentificacion, :numeroIdentificacion, :telefonoUsuario, :fechaNacimiento)";

        $stmt = $this->conn->prepare($sql);
        
        // Hash password
        $hashedPassword = password_hash($passUsuario, PASSWORD_BCRYPT);

        $stmt->bindParam(':nombreUsuario', $nombreUsuario);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':passUsuario', $hashedPassword);
        $stmt->bindParam(':tipoIdentificacion', $tipoIdentificacion);
        $stmt->bindParam(':numeroIdentificacion', $numeroIdentificacion);
        $stmt->bindParam(':telefonoUsuario', $telefonoUsuario);
        $stmt->bindParam(':fechaNacimiento', $fechaNacimiento);

        return $stmt->execute();
    }
}
?>