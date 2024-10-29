<?php
require_once __DIR__ . '/../db/database.php';
session_start();

function getUserData() {
    if (!isset($_SESSION['id_user'])) {
        return null;
    }

    $database = new Database();
    $conn = $database->getConnection();
    
    $id_user = $_SESSION['id_user'];
    
    $stmt = $conn->prepare("SELECT nome, imagem_perfil FROM user_db1 WHERE id_user = ?");
    $stmt->bind_param("i", $id_user);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        return $row;
    }
    
    return null;
}

function getUserAddress() {
    if (!isset($_SESSION['id_user'])) {
        return null;
    }

    $database = new Database();
    $conn = $database->getConnection();
    
    $id_user = $_SESSION['id_user'];
    $stmt = $conn->prepare("SELECT * FROM endereco WHERE id_user_db1 = ?");
    $stmt->bind_param("i", $id_user);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    }
    
    return null;
}
?> 