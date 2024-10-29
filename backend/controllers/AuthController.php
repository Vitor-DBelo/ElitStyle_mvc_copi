<?php
session_start();

// Verifica se a sessão está estabelecida
if (!isset($_SESSION['id'])) {
    // Se não estiver logado, redireciona para a página de login
    header("Location: ../frontend/index.html");
    exit();
}
?>