<?php

// Definir o caminho base do projeto
define('BASE_PATH', dirname(__DIR__, 2) . '/');

// Incluir o arquivo database.php
require_once BASE_PATH . 'backend/db/database.php';

class Produto {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Recupera todos os produtos
    public function getProdutos() {
        $query = "SELECT * FROM produto";
        $result = $this->conn->query($query);

        $produtos = array();
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $produtos[] = $row;
            }
        }

        return $produtos;
    }

    // Recupera um produto específico por ID
    public function getProdutoPorId($id) {
        $query = "SELECT * FROM produto WHERE id_produto = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $produto = $result->fetch_assoc();
            error_log("Produto encontrado: " . print_r($produto, true));
            return $produto;
        } else {
            error_log("Nenhum produto encontrado com o ID: " . $id);
            return null;
        }
    }

    // Adicione este método à classe Produto
    public function getProdutosRelacionados($id, $limit = 8) {
        $query = "SELECT * FROM produto WHERE id_produto != ? ORDER BY RAND() LIMIT ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ii", $id, $limit);
        $stmt->execute();
        $result = $stmt->get_result();

        $produtos = array();
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $produtos[] = $row;
            }
        }

        return $produtos; // Isso sempre retornará um array, mesmo que vazio
    }

    public function getProdutosFiltrados($marca = null, $precoMinimo = null, $precoMaximo = null, $tamanho = null) {
        $query = "SELECT * FROM produto WHERE 1=1";
        $params = array();

        if ($marca) {
            $query .= " AND marca = ?";
            $params[] = $marca;
        }
        if ($precoMinimo) {
            $query .= " AND preco >= ?";
            $params[] = $precoMinimo;
        }
        if ($precoMaximo) {
            $query .= " AND preco <= ?";
            $params[] = $precoMaximo;
        }
        if ($tamanho) {
            $query .= " AND tamanho = ?";
            $params[] = $tamanho;
        }

        $stmt = $this->conn->prepare($query);
        if (!empty($params)) {
            $stmt->bind_param(str_repeat('s', count($params)), ...$params);
        }
        $stmt->execute();
        $result = $stmt->get_result();

        $produtos = array();
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $produtos[] = $row;
            }
        }

        return $produtos;
    }
}
