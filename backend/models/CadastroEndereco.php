<?php
session_start();
require_once __DIR__ . '/../db/database.php';

// Verifica se o usuário está logado
if (!isset($_SESSION['id_user'])) {
    header("Location: ../../Views/login.html?message=nao_logado");
    exit();
}

$database = new Database();
$conn = $database->getConnection();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Pega o ID do usuário da sessão
    $id_user_db1 = $_SESSION['id_user'];
    
    // Recebe os dados do formulário
    $numero = $_POST['numero'];
    $rua = $_POST['rua'];
    $bairro = $_POST['bairro'];
    $cep = $_POST['cep'];
    $estado = $_POST['estado'];
    $cidade = $_POST['cidade'];
    $infoAdicional = $_POST['infoAdicional'];

    try {
        // Verifica se já existe endereço para este usuário
        $query = "SELECT id_endereco FROM endereco WHERE id_user_db1 = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $id_user_db1);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // UPDATE - Atualiza endereço existente
            $query = "UPDATE endereco SET 
                     numero = ?, 
                     rua = ?, 
                     bairro = ?, 
                     cep = ?, 
                     estado = ?, 
                     cidade = ?, 
                     informacoes_adicionais = ? 
                     WHERE id_user_db1 = ?";
            
            $stmt = $conn->prepare($query);
            $stmt->bind_param("sssssssi", $numero, $rua, $bairro, $cep, $estado, $cidade, $infoAdicional, $id_user_db1);
            
            if ($stmt->execute()) {
                header("Location: ../../Views/usuario.php?message=endereco_atualizado");
                exit();
            }
        } else {
            // INSERT - Cadastra novo endereço
            $query = "INSERT INTO endereco (numero, rua, bairro, cep, estado, cidade, informacoes_adicionais, id_user_db1) 
                     VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            
            $stmt = $conn->prepare($query);
            $stmt->bind_param("sssssssi", $numero, $rua, $bairro, $cep, $estado, $cidade, $infoAdicional, $id_user_db1);
            
            if ($stmt->execute()) {
                header("Location: ../../Views/usuario.php?message=endereco_cadastrado");
                exit();
            }
        }
    } catch (Exception $e) {
        header("Location: ../../Views/CadastroEndereco.html?message=erro&error=" . urlencode($e->getMessage()));
        exit();
    } finally {
        $stmt->close();
        $conn->close();
    }
}
?>