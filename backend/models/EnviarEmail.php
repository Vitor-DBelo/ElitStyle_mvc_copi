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
        $mail->CharSet = 'UTF-8'; 
        $mail->Subject = 'Código de verificação';
        $mail->Body = '
        <html>
        <head>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    margin: 0;
                    padding: 0;
                }
                .email-container {
                    background-color: #2c2c2c;
                    width: 100%;
                    max-width: 600px;
                    margin: 50px auto;
                    padding: 30px;
                    border-radius: 8px;
                    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
                }
                span{
                    color: #000;
                }
                .email-header {
                    text-align: center;
                }
                .email-header h1 {
                    font-size: 24px;
                    margin: 0;
                    color: #FFD700;  
                }
                .email-body {
                    font-size: 16px;
                    line-height: 1.5;
                }
                .email-body p {
                    margin: 0;
                    color: #000;
                }
                .code {
                    font-size: 24px;
                    font-weight: bold;
                    color: #FFD700;  /* Cor dourada para o código */
                    background-color: #f0f0f0;
                    padding: 15px;
                    border-radius: 5px;
                    text-align: center;
                }
                .footer {
                    text-align: center;
                    margin-top: 30px;
                    font-size: 12px;
                    color: #000;
                }
                .footer span {
                    color: #FFD700;  /* Cor dourada para links no rodapé */
                    text-decoration: none;
                }
            </style>
        </head>
        <body>
            <div class="email-container">
                <div class="email-header">
                    <h1><span>Elite</span> Style</h1>
                </div>
                <div class="email-body">
                    <p>Olá,</p>
                    <p>Para continuar, use o código de verificação abaixo:</p>
                    <div class="code">' . $token . '</div>
                    <p>Este código expirará em 10 minutos.</p>
                </div>
                <div class="footer">
                    <p>Se você não solicitou este código, ignore este e-mail.</p>
                  <p><span>&copy; 2024</span>  Elite Style. Todos os direitos reservados.</p>
                </div>
            </div>
        </body>
        </html>';
        $mail->AltBody = 'Este é o código de verificação: ' . $token;

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Erro ao enviar e-mail: {$mail->ErrorInfo}");
        return false;
    }
}
?>
