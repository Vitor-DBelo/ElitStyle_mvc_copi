<?php
require_once __DIR__ . '/backend/db/database.php';
require_once __DIR__ . '/backend/models/Produto.php';
require_once __DIR__ . '/config/routes.php';

function base_url($path = '') {
    $base_url = '//' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']);
    return $base_url . '/' . ltrim($path, '/');
}

// Inicializa o modelo de Produto
$produtoModel = new Produto();

// Obtém todos os produtos
$produtos = $produtoModel->getProdutos();

// Inclui o arquivo de visualização
$request_uri = $_SERVER['REQUEST_URI'];

if (strpos($request_uri, 'produto.php') !== false) {
    include 'Views/produto.php';
} else {
    include 'Views/index.php';
}
?>
