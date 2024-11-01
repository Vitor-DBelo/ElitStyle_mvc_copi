<?php
session_start();
require_once __DIR__ . '/../db/database.php';
require_once __DIR__ . '/EnviarEmail.php';
require_once __DIR__ . '/IncludeEmail.php';

// Criar instância da classe Database
$database = new Database();
$conn = $database->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $senha = htmlspecialchars($_POST['senha'], ENT_QUOTES, 'UTF-8');

    // Log dos valores recebidos
    error_log("Email recebido: " . $email);
    error_log("Senha recebida: " . $senha);

    $stmt = $conn->prepare("SELECT id_user, senha FROM user_db1 WHERE email = ?");
    if ($stmt === false) {
        die('Erro na preparação da consulta: ' . $conn->error);
    }
    
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows > 0){
        $row = $result->fetch_assoc();
        
        if(password_verify($senha, $row['senha'])){
            $_SESSION['id_user'] = $row['id_user'];

            // Gerar o código de verificação e enviar o e-mail
            $token = gerarCodigoVerificacao();
            if(enviarEmailVerificacao($token, $email)){
                $_SESSION['verification_email'] = $email; // Armazenar email na sessão
                $_SESSION['verification_token'] = $token; // Armazenar token na sessão
                header("Location: ../../Views/token.html");
                exit();
            } else {
                $_SESSION['error_message'] = "Erro ao enviar e-mail de verificação";
                header("Location: ../../Views/login.html");
                exit();
            }
        }
    }
    
    $_SESSION['error_message'] = "E-mail ou senha incorretos!";
    header("Location: ../../Views/login.html");
    exit();
    
    $stmt->close();
}

$conn->close();

function gerarCodigoVerificacao() {
    $token = '';
    for ($i = 0; $i < 6; $i++) {
        $token .= rand(1, 9);
    }
    return $token;
}
?>
