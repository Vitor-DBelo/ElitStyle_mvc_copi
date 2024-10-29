<?php
require_once __DIR__ . '/../db/database.php';

$database = new Database();
$conn = $database->getConnection();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $phone = $_POST['phone'];

    try {
        // Inserir o usuário na tabela user_db1
        $query = "INSERT INTO user_db1 (nome, email, senha, telefone) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssss", $name, $email, $password, $phone);

        if ($stmt->execute()) {
            $user_id = $stmt->insert_id;
            
            // Redirecionar para a página de cadastro de endereço usando caminho relativo
            $redirect_url = "../../Views/CadastroEndereco.html?user_id=" . $user_id;
            header("Location: " . $redirect_url);
            exit();
        } else {
            throw new Exception("Erro ao cadastrar usuário: " . $stmt->error);
        }
    } catch (Exception $e) {
        echo $e->getMessage();
    } finally {
        $stmt->close();
        $conn->close();
    }
}
?>
