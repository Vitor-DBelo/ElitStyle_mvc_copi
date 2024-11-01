<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/IncludeEmail.php';
require_once __DIR__ . '/login.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require_once __DIR__ . '/../../vendor/autoload.php';

function enviarEmailVerificacao($token, $emailDestino) {
    $mail = new PHPMailer(true);

    try {
        // Configurações do servidor SMTP
        $mail->isSMTP();
        $mail->SMTPDebug = 0;
        $mail->Host       = SMTP_HOST;
        $mail->SMTPAuth   = true;
        $mail->Username   = SMTP_USER;
        $mail->Password   = SMTP_PASS;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = SMTP_PORT;

        // Recipientes
        $mail->setFrom(SMTP_USER, 'Elite Style');
        $mail->addAddress($emailDestino);

        // Conteúdo do e-mail
        $mail->isHTML(true);
        $mail->Subject = 'Código de verificação';
        $mail->Body    = 'Este é o código de verificação: ' . $token;
        $mail->AltBody = 'Este é o código de verificação: ' . $token;

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Erro ao enviar e-mail: {$mail->ErrorInfo}");
        return false;
    }
}
?>
