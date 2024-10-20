<?php

require_once 'backend/models/Produto.php';

class FiltroController {
    
    public function index() {
        $produtoModel = new Produto();
        $produtos = $produtoModel->getProdutos();
        include 'Views/filtro.html';
    }

    public function aplicarFiltro($marca = null, $precoMinimo = null, $precoMaximo = null, $tamanho = null) {
        $produtoModel = new Produto();
        $produtos = $produtoModel->getProdutosFiltrados($marca, $precoMinimo, $precoMaximo, $tamanho);
        include 'Views/filtro.html';
    }
}
