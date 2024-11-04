<?php
require_once __DIR__ . '/../db/database.php';
require_once __DIR__ . '/../models/cadastro.php';
session_start(); // Iniciando a sessão

$database = new Database();
$conn = $database->getConnection();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Verificar se existe um ID de usuário na sessão
    if (!isset($_SESSION['user_id'])) {
        echo "Erro: ID do usuário não encontrado na sessão";
        exit();
    }

    $numero = $conn->real_escape_string($_POST['numero']);
    $rua = $conn->real_escape_string($_POST['rua']);
    $bairro = $conn->real_escape_string($_POST['bairro']);
    $cep = $conn->real_escape_string($_POST['cep']);
    $estado = $conn->real_escape_string($_POST['estado']);
    $cidade = $conn->real_escape_string($_POST['cidade']);
    $id_user = $_SESSION['user_id']; // Pegando o ID do usuário da sessão

    $query = "INSERT INTO endereco (numero, rua, bairro, cep, estado, cidade, id_user_db1) 
              VALUES ('$numero', '$rua', '$bairro', '$cep', '$estado', '$cidade', $id_user)";

    if ($conn->query($query)) {
        header("Location: ../../Views/login.html");
        exit();
    } else {
        echo "Erro ao cadastrar endereço: " . $conn->error;
    }
    
    $conn->close();
}
?>