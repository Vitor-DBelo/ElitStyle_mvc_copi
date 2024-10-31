<?php
session_start();
require_once __DIR__ . '/../db/database.php';
require_once __DIR__ . '/IncludeEmail.php';
// Adicionar estas linhas para importar o PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Verificar se você tem o autoload do Composer
require_once __DIR__ . '/../../vendor/autoload.php';

$mail = new PHPMailer(true);

try {
    // Configurações do servidor SMTP
    $mail->isSMTP();                                          
    $mail->Host       = SMTP_HOST;                    
    $mail->SMTPAuth   = true;                                   
    $mail->Username   = SMTP_USER;               
    $mail->Password   = SMTP_PASS;                         
    $mail->Port       = SMTP_PORT;                                    

    // Recipientes 
    $mail->setFrom('elitstyle404@gmail.com', 'Elite Style');
    $mail->addAddress('humamaigohan@gmail.com', 'humamaigohan');     
    
    // Conteúdo
    $mail->isHTML(true);                                  
    $mail->Subject = 'Codigo de verificação';
    $mail->Body    = 'Este é o codigo de verificação: <b>123456</b>';
    $mail->AltBody = 'Este é o codigo de verificação: cusao';

    $mail->send();
    echo 'Email enviado com sucesso';
} catch (Exception $e) {
    echo "Erro ao enviar email: {$mail->ErrorInfo}";
}


// Criar instância da classe Database
$database = new Database();
$conn = $database->getConnection();


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

