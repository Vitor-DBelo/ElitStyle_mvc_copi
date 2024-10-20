<?php

require_once 'backend/models/Produto.php';

class ProdutoController {
    
    // Exibe a lista de produtos
    public function index() {
        $produtoModel = new Produto();
        $produtos = $produtoModel->getProdutos();
        require 'Views/index.php';
    }

    // Exibe um produto específico
    public function exibirProduto($id) {
        $produtoModel = new Produto();
        $produto = $produtoModel->getProdutoPorId($id);
        if ($produto) {
            require 'Views/produto.php';
        } else {
            // Redirecionar para uma página de erro ou para a lista de produtos
            header('Location: /');
        }
    }
}
