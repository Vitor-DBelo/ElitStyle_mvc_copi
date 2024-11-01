<?php
session_start();
require_once __DIR__ . '/../db/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verifica se existe token na sessão
    if (!isset($_SESSION['verification_token'])) {
        $_SESSION['error_message'] = "Sessão expirada. Por favor, faça login novamente.";
        header("Location: ../../Views/login.html");
        exit();
    }

    // Obtém o token digitado pelo usuário
    $tokenDigitado = filter_input(INPUT_POST, 'token', FILTER_SANITIZE_STRING);
    $tokenSessao = $_SESSION['verification_token'];

    if ($tokenDigitado === $tokenSessao) {
        // Token correto - Criar sessão autenticada
        $_SESSION['authenticated'] = true;
        
        // Limpar tokens de verificação
        unset($_SESSION['verification_token']);
        unset($_SESSION['verification_email']);
        
        // Redirecionar para a página principal
        header("Location: ../../Views/index.html");
        exit();
    } else {
        // Token incorreto
        $_SESSION['error_message'] = "Código de verificação incorreto. Tente novamente.";
        header("Location: ../../Views/token.html");
        exit();
    }
}

// Se alguém tentar acessar diretamente este arquivo
header("Location: ../../Views/login.html");
exit();
?>
