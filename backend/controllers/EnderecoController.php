<?php
require_once __DIR__ . '/../db/database.php';

function getEnderecoData($id_user) {
    $database = new Database();
    $conn = $database->getConnection();
    
    $query = "SELECT * FROM endereco WHERE id_user_db1 = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id_user);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    }
    return null;
} 