<?php
// Definir o diretório raiz do projeto
define('ROOT_DIR', __DIR__);

// Verificar se a função base_url já foi declarada
if (!function_exists('base_url')) {
    function base_url($atRoot = FALSE, $atCore = FALSE, $parse = FALSE) {
        if (isset($_SERVER['HTTP_HOST'])) {
            $http = isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off' ? 'https' : 'http';
            $hostname = $_SERVER['HTTP_HOST'];
            $dir = str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);
            $core = preg_split('@/@', str_replace($_SERVER['DOCUMENT_ROOT'], '', realpath(dirname(__FILE__))), NULL, PREG_SPLIT_NO_EMPTY);
            $core = $core[0];
            $tmplt = $atRoot ? ($atCore ? "%s://%s/%s/" : "%s://%s/") : ($atCore ? "%s://%s/%s/" : "%s://%s%s");
            $end = $atRoot ? ($atCore ? $core : $hostname) : ($atCore ? $core : $dir);
            $base_url = sprintf($tmplt, $http, $hostname, $end);
        } else $base_url = 'http://localhost/';
        if ($parse) {
            $base_url = parse_url($base_url);
            if (isset($base_url['path'])) if ($base_url['path'] == '/') $base_url['path'] = '';
        }
        return $base_url;
    }
}

$base_url = base_url();

// Incluir o arquivo de rotas
require_once ROOT_DIR . '/config/routes.php';

// Inicializa o modelo de Produto
require_once ROOT_DIR . '/backend/models/Produto.php';
$produtoModel = new Produto();

// Obtém todos os produtos
$produtos = $produtoModel->getProdutos();

// Inclui o arquivo de visualização
$request_uri = $_SERVER['REQUEST_URI'];

if (strpos($request_uri, 'produto.php') !== false) {
    include ROOT_DIR . '/Views/produto.php';
} else {
    include ROOT_DIR . '/Views/index.php';
}
?>
