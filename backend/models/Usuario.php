<?php

require_once 'backend/db/database.php';

class Usuario {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Autenticação de usuário
    public function autenticar($email, $senha) {
        $query = "SELECT * FROM usuarios WHERE email = :email AND senha = :senha";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':senha', $senha);
        $stmt->execute();

        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        return $usuario ? true : false;
    }
}
