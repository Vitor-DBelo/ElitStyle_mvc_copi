<?php
session_start();
require 'db/database.php';

// Verifica se a sessão expirou
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 7200)) {
    // Sessão expirou após 2 horas de inatividade
    session_unset();     // Limpa todas as variáveis de sessão
    session_destroy();   // Destrói a sessão
    header("Location: ../../Views/login.html?message=sessao_expirada");
    exit();
}

// Atualiza o timestamp da última atividade
$_SESSION['LAST_ACTIVITY'] = time();

// Exemplo de dados recebidos do formulário de login
$nome = $_POST['nome'];
$senha = $_POST['senha'];

// Consulta para verificar as credenciais
$query = "SELECT id, senha FROM user_db WHERE nome = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $nome);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($user && password_verify($senha, $user['senha'])) {
    // Login válido: inicia a sessão e cria o hash
    $_SESSION['user_id'] = $user['id'];

    // Cria um hash de sessão único
    $salt = "chave_secreta_unica"; // Defina uma chave secreta única
    $_SESSION['user_hash'] = hash('sha256', $user['id'] . $salt);

    // Define o tempo de início da sessão
    $_SESSION['CREATED'] = time();

    echo "Login bem-sucedido!"; // Redirecionamento ou mensagem
} else {
    echo "Usuário ou senha incorretos.";
}

// Função para verificar a validade da sessão
function verificarSessao() {
    if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_hash'])) {
        return false;
    }

    $salt = "chave_secreta_unica"; // A mesma chave secreta usada na criação
    $hash_esperado = hash('sha256', $_SESSION['user_id'] . $salt);

    if ($_SESSION['user_hash'] !== $hash_esperado) {
        return false;
    }

    // Verifica se passaram 2 horas desde a criação da sessão
    if (isset($_SESSION['CREATED']) && (time() - $_SESSION['CREATED'] > 7200)) {
        session_unset();
        session_destroy();
        return false;
    }

    return true;
}

// Exemplo de uso da função de verificação
if (!verificarSessao()) {
    header("Location: ../../Views/login.html?message=sessao_invalida");
    exit();
}
?>
