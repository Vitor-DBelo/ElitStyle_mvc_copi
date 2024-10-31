<?php
require_once __DIR__ . '/../db/database.php';
require_once '../../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

define("SMTP_HOST", "sandbox.smtp.mailtrap.io");
define("SMTP_PORT", 2525); 
define("SMTP_USER", "8f3be8032a8292");
define("SMTP_PASS", "e75c64ed8e9fe9"); 
?>
