<?php
require_once __DIR__ . '/../db/database.php';
session_start();

// Configurações seguras de sessão
ini_set('session.cookie_httponly', 1); // Impede o acesso ao cookie de sessão via JavaScript
ini_set('session.cookie_secure', 1);   // Garante que o cookie de sessão seja transmitido apenas via HTTPS
ini_set('session.use_only_cookies', 1); // Utiliza apenas cookies para armazenar o ID da sessão



// Criar instância da classe Database
$database = new Database();
$conn = $database->getConnection();

// Função para gerar token de 8 números aleatórios de 0 a 9
function gerarTokenNumerico() {
    $token = '';
    for ($i = 0; $i < 8; $i++) {
        $token .= mt_rand(0, 9);
    }
    return $token;
}
$token = gerarTokenNumerico();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = filter_input(INPUT_POST, 'senha', FILTER_SANITIZE_STRING);

    // Log dos valores recebidos
    error_log("Email recebido: " . $email);
    error_log("Senha recebida: " . $password);

    // Use prepared statements
    $stmt = $conn->prepare("SELECT id_user, senha FROM user_db1 WHERE email = ?");
    if ($stmt === false) {
        die('Erro na preparação da consulta: ' . $conn->error);
    }
    
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows > 0){
        $row = $result->fetch_assoc();
        $senha_hash = $row['senha'];
        
        if(password_verify($password, $senha_hash)){
            $_SESSION['id_user'] = $row['id_user'];
            
            header("Location: ../../Views/index.html");
            exit();
        } else {
            echo "<p>E-mail ou senha incorretos!</p>";
        }
    } else {
        echo "<p>E-mail ou senha incorretos!</p>";
    }
    $stmt->close();
}

$conn->close();
?>
