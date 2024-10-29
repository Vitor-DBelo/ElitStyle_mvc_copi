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
    $id_user = $_SESSION['id_user'];
    
    // Recebe os dados do formulário
    $email = $_POST['email'];
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT); // Cria hash da senha
    $telefone = $_POST['telefone'];

    try {
        // Verifica se o usuário existe
        $query = "SELECT id_user FROM user_db1 WHERE id_user = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $id_user);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // UPDATE - Atualiza dados do usuário
            $query = "UPDATE user_db1 SET 
                     email = ?, 
                     senha = ?, 
                     telefone = ? 
                     WHERE id_user = ?";
            
            $stmt = $conn->prepare($query);
            $stmt->bind_param("sssi", $email, $senha, $telefone, $id_user);
            
            if ($stmt->execute()) {
                header("Location: ../../Views/usuario.php?message=usuario_atualizado");
                exit();
            } else {
                throw new Exception("Erro ao atualizar usuário");
            }
        } else {
            throw new Exception("Usuário não encontrado");
        }
    } catch (Exception $e) {
        header("Location: ../../Views/AlterUser.html?message=erro&error=" . urlencode($e->getMessage()));
        exit();
    } finally {
        $stmt->close();
        $conn->close();
    }
}
?>