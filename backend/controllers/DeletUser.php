<?php
require_once __DIR__ . '/../db/database.php';
session_start();

function deleteUser() {
    if (!isset($_SESSION['id_user'])) {
        return ['success' => false, 'message' => 'Usuário não está logado'];
    }

    $database = new Database();
    $conn = $database->getConnection();
    
    if (!$conn) {
        return ['success' => false, 'message' => 'Erro ao conectar ao banco de dados'];
    }
    
    $id_user = $_SESSION['id_user'];
    
    try {
        // Desativa verificação de chave estrangeira temporariamente
        $conn->query("SET FOREIGN_KEY_CHECKS=0");
        
        // Inicia a transação
        $conn->begin_transaction();
        
        // Deleta endereços
        $stmt = $conn->prepare("DELETE FROM endereco WHERE id_user_db1 = ?");
        $stmt->bind_param("i", $id_user);
        $stmt->execute();
        
        // Deleta o usuário
        $stmt = $conn->prepare("DELETE FROM user_db1 WHERE id_user = ?");
        $stmt->bind_param("i", $id_user);
        $stmt->execute();
        
        // Confirma as alterações
        $conn->commit();
        
        // Reativa verificação de chave estrangeira
        $conn->query("SET FOREIGN_KEY_CHECKS=1");
        
        // Limpa a sessão
        session_unset();
        session_destroy();
        
        
        header('Location: ../../Views/index.html');
        
    } catch (Exception $e) {
        $conn->rollback();
        $conn->query("SET FOREIGN_KEY_CHECKS=1");
        return ['success' => false, 'message' => 'Erro ao deletar conta: ' . $e->getMessage()];
    } finally {
        if ($conn) {
            $conn->close();
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    echo json_encode(deleteUser());
    exit();
}
?>
