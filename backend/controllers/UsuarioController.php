<?php

require_once 'backend/models/Usuario.php';

class UsuarioController {

    // Página inicial de usuários
    public function index() {
        require 'views/pages/usuario.php';
    }

    // Método de login
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuarioModel = new Usuario();
            $email = $_POST['email'];
            $senha = $_POST['senha'];
            
            if ($usuarioModel->autenticar($email, $senha)) {
                $_SESSION['usuario'] = $email;
                header('Location: /usuario');
            } else {
                echo "Email ou senha incorretos!";
            }
        } else {
            require 'views/pages/login.php';
        }
    }
}
