<?php
require_once __DIR__ . '/../controllers/AuthController.php';
require_once __DIR__ . '/../controllers/UserDataController.php';
require_once __DIR__ . '/../db/database.php';

// Verifica se existe uma sessão ativa e se o usuário está logado
if (isset($_SESSION['id_user'])) {
    // Pega o ID do usuário da sessão corretamente
    $id_user = $_SESSION['id_user'];

    // Dados do formulário
    $numero = $_POST['numero'] ?? '';
    $rua = $_POST['rua'] ?? '';
    $bairro = $_POST['bairro'] ?? '';
    $cep = $_POST['cep'] ?? '';
    $estado = $_POST['estado'] ?? '';
    $cidade = $_POST['cidade'] ?? '';
   

    // Cria conexão com o banco
    $database = new Database();
    $conn = $database->getConnection();

    // Primeiro verifica se já existe um endereço para este usuário
    $check_query = "SELECT id_endereco FROM endereco WHERE id_user_db1 = ?";
    $check_stmt = $conn->prepare($check_query);
    $check_stmt->bind_param("i", $id_user);
    $check_stmt->execute();
    $result = $check_stmt->get_result();

    if ($result->num_rows > 0) {
        // UPDATE - Se já existe um endereço
        $update_query = "UPDATE endereco SET 
            numero = ?, 
            rua = ?, 
            bairro = ?, 
            cep = ?, 
            estado = ?, 
            cidade = ? 
            WHERE id_user_db1 = ?";

        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("ssssssi", $numero, $rua, $bairro, $cep, $estado, $cidade, $id_user);
    } else {
        // INSERT - Se não existe um endereço
        $insert_query = "INSERT INTO endereco (numero, rua, bairro, cep, estado, cidade, id_user_db1) 
                        VALUES (?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($insert_query);
        $stmt->bind_param("ssssssi", $numero, $rua, $bairro, $cep, $estado, $cidade, $id_user);
    }

    if ($stmt->execute()) {
        header("Location: ../../Views/usuario.php?message=endereco_atualizado");
        exit();
    } else {
        header("Location: ../../Views/usuario.php?message=erro_atualizacao");
        exit();
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: ../../Views/usuario.php?message=sessao_invalida");
    exit();
}
