<?php
// Definir o diretório raiz do projeto
// define('ROOT_DIR', dirname(__DIR__));

include_once ROOT_DIR . '/backend/db/database.php';

require_once ROOT_DIR . '/backend/controllers/ProdutoController.php';
require_once ROOT_DIR . '/backend/controllers/UsuarioController.php';
require_once ROOT_DIR . '/backend/controllers/FiltroController.php';

$route = $_GET['route'] ?? '';

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

switch ($route) {
    case '':
    case 'home':
        $indexPath = ROOT_DIR . '/Views/index.php';
        if (file_exists($indexPath)) {
            require $indexPath;
        } else {
            // Tente encontrar o arquivo index.php em outros diretórios comuns
            $alternativePaths = [
                ROOT_DIR . '/index.php',
                ROOT_DIR . '/public/index.php',
                ROOT_DIR . '/src/index.php'
            ];
            foreach ($alternativePaths as $path) {
                if (file_exists($path)) {
                    require $path;
                    break;
                }
            }
            if (!isset($path)) {
                die("Arquivo index.php não encontrado. Verifique a estrutura do seu projeto.");
            }
        }
        break;
    
    case 'produto':
        $id = $_GET['id'] ?? null;
        if ($id) {
            $produtoController = new ProdutoController();
            $produtoController->exibirProduto($id);
        } else {
            // Redirecionar para a página inicial ou mostrar um erro
            header('Location: index.php');
        }
        break;
    
    case 'filtro':
        $filtroController = new FiltroController();
        if ($_SERVER['REQUEST_METHOD'] === 'GET' && !empty($_GET)) {
            $marca = $_GET['marca'] ?? null;
            $precoMinimo = $_GET['precoMinimo'] ?? null;
            $precoMaximo = $_GET['precoMaximo'] ?? null;
            $tamanho = $_GET['tamanho'] ?? null;
            $filtroController->aplicarFiltro($marca, $precoMinimo, $precoMaximo, $tamanho);
        } else {
            $filtroController->index();
        }
        break;

    case '/usuario':
        $usuarioController = new UsuarioController();
        $usuarioController->index();
        break;
    
    case '/login':
        $usuarioController = new UsuarioController();
        $usuarioController->login();
        break;

    case '/produto.php':
        $id = $_GET['id'] ?? null;
        if ($id) {
            $produtoController = new ProdutoController();
            $produtoController->exibirProduto($id);
        } else {
            // Redirecionar para a página inicial ou mostrar um erro
            header('Location: index.php');
        }
        break;

    case (preg_match('/^\/produto\/(\d+)$/', $uri, $matches) ? true : false):
        $produtoController = new ProdutoController();
        $produtoController->exibirProduto($matches[1]);
        break;

    default:
        http_response_code(404);
        echo "Página não encontrada";
        break;
}
