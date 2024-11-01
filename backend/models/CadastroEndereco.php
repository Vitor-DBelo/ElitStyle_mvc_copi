<?php
require_once __DIR__ . '/../db/database.php';

$database = new Database();
$conn = $database->getConnection();

// Recebe os dados do formulário
$id_user_db1 = 1; // Substitua pelo ID do usuário real
$numero = $_POST['numero'];
$rua = $_POST['rua'];
$bairro = $_POST['bairro'];
$cep = $_POST['cep'];
$estado = $_POST['estado'];
$cidade = $_POST['cidade'];
$infoAdicional = $_POST['infoAdicional'];

// Query para inserção
$query = "INSERT INTO endereco (numero, rua, bairro, cep, estado, cidade, informacoes_adicionais, id_user_db1) 
         VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($query);
$stmt->bind_param("sssssssi", $numero, $rua, $bairro, $cep, $estado, $cidade, $infoAdicional, $id_user_db1);

if ($stmt->execute()) {
    header("Location: ../../Views/login.html");
    exit();
}

$stmt->close();
$conn->close();
?>