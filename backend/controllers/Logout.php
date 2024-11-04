<?php
session_start();

class Logout {
    public function __construct() {
        $this->encerrarSessao();
    }

    private function encerrarSessao() {
        // Limpa todas as variáveis de sessão
        $_SESSION = array();

        // Destrói o cookie da sessão
        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time() - 3600, '/');
        }

        // Destrói a sessão
        session_destroy();

        // Redireciona para a página inicial
        header('Location: /Views/index.html');
        exit();
    }
}

// Instancia a classe para executar o logout
new Logout();
?> 