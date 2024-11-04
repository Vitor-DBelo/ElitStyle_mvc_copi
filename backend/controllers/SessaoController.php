<?php
session_start();
require_once __DIR__ . '/../db/database.php';

// Cria uma instância da classe Database e obtém a conexão
$database = new Database();
$conn = $database->getConnection();

// Verifica se a sessão expirou
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 7200)) {
    session_unset();
    session_destroy();
    header("Location: ../../Views/login.html?message=sessao_expirada");
    exit();
}

$_SESSION['LAST_ACTIVITY'] = time();

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'] ?? ''; // Evita erro se não estiver definido
    $senha = $_POST['senha'] ?? ''; // Evita erro se não estiver definido

    // Consulta para verificar as credenciais
    $query = "SELECT id_user, senha FROM user_db1 WHERE nome = ?"; // Corrigido aqui
    $stmt = $conn->prepare($query);
    if ($stmt === false) {
        die('Erro na preparação da consulta: ' . htmlspecialchars($conn->error));
    }
    
    $stmt->bind_param("s", $nome);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($senha, $user['senha'])) {
        $_SESSION['user_id'] = $user['id_user']; // Corrigido aqui
        $salt = "chave_secreta_unica";
        $_SESSION['user_hash'] = hash('sha256', $user['id_user'] . $salt); // Corrigido aqui
        $_SESSION['CREATED'] = time();

        echo "Login bem-sucedido!"; // Redirecionamento ou mensagem
    } else {
        echo "Usuário ou senha incorretos.";
    }

    $stmt->close(); // Fecha o statement
} else {
    echo "Por favor, preencha o formulário de login.";
    exit();
}

// Função para verificar a validade da sessão
function verificarSessao() {
    if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_hash'])) {
        return false;
    }

    $salt = "chave_secreta_unica";
    $hash_esperado = hash('sha256', $_SESSION['user_id'] . $salt);

    if ($_SESSION['user_hash'] !== $hash_esperado) {
        return false;
    }

    if (isset($_SESSION['CREATED']) && (time() - $_SESSION['CREATED'] > 7200)) {
        session_unset();
        session_destroy();
        return false;
    }

    return true;
}

// Exemplo de uso da função de verificação
if (!verificarSessao()) {
    header("Location: ../../Views/index.html?message=sessao_invalida");
    exit();
}
?>
