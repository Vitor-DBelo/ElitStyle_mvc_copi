<?php
class AuthController {
    public static function verificarAutenticacao() {
        session_start();
        if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
            header("Location: ../../Views/login.html");
            exit();
        }
    }
}
?>