<?php
require_once __DIR__ . '/../backend/models/Produto.php';
$produtoModel = new Produto();

if (isset($_GET['id'])) {
    $id_produto = $_GET['id'];
    $produto = $produtoModel->getProdutoPorId($id_produto);
} else {
    // Redirecionar para a página inicial se nenhum ID for fornecido
    header('Location: index.html');
    exit();
}

if (!$produto) {
    // Redirecionar para a página inicial se o produto não for encontrado
    header('Location: index.html');
    exit();
}

// Adicione este código no início do arquivo, após a obtenção do produto
if ($produto) {
    error_log("Dados do produto: " . print_r($produto, true));
} else {
    error_log("Produto não encontrado para o ID: " . $id_produto);
}

function corrigirCaminhoImagem($url) {
    // Remove qualquer '../' ou './' do início do caminho
    $path = preg_replace('/^(\.\.\/|\.\/)*/', '', $url);
    
    // Remove '/public' do início do caminho, se estiver presente
    $path = preg_replace('/^\/public/', '', $path);
    
    // Adiciona '../public' no início do caminho se não estiver presente
    if (strpos($path, '../public') !== 0) {
        $path = '../public' . $path;
    }
    
    return $path;
}

if (!function_exists('base_url')) {
    function base_url($path = '') {
        $base_url = '//' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']);
        return rtrim($base_url, '/') . '/' . ltrim($path, '/');
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($produto['nome']); ?> - Elit Style</title>
    <link rel="shortcut icon" href="../public/img/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="../public/css/style.css">
    <link rel="stylesheet" href="../public/css/animation.css">
</head>
<body>
    <header> 
        <nav class="Nav_Bar">
            <div class="logo">
                <a href="<?php echo base_url(); ?>" class="logo-link">
                    <span>Elit</span>
                    <span class="Dorado">Style</span>
                </a>
            </div>
            <div class="search-pesqui">
                <input type="text" class="search-input" placeholder="Pesquise...">
                <button class="search-icon">
                  <img src="https://cdn.icon-icons.com/icons2/2645/PNG/512/search_icon_159890.png" alt="Ícone de pesquisa">
                </button>
                <div class="base-bar"></div>
                <div class="animation_bar"></div>
            </div>
            <div class="menu-icon">
                <img src="../public/img/icon/icon_hamburger.png" alt="Menu"> 
            </div>
            <ul class="nav-links">
                <li><a href="#">Lançamentos</a></li>
                <li><a href="#">Marcas</a></li>
                <li><a href="#">Acessórios</a></li>
                <li><a href="#">🛒</a></li>
                <li class="user-profile">
                    <img src="https://i.pinimg.com/564x/cb/2d/a9/cb2da9b8e06f5e2addc04d92d9fb64a1.jpg" alt="Foto do usuário">
                    <div class="dropdown-menu">
                        <ul>
                            <li><a href="usuario.html">Editar</a></li>
                            <li><a href="login.html">Login</a></li>
                        </ul>
                    </div>
                </li>
            </ul>
        </nav>
    </header>
    
    <main class="main-produto">
        <div class="content-pg-Produto">
           <div class="produto-descri">
                <?php
                $imagemUrl = corrigirCaminhoImagem($produto['url']);
                ?>
                <img src="<?php echo htmlspecialchars($imagemUrl); ?>" alt="<?php echo htmlspecialchars($produto['nome']); ?>">
                <p><?php echo htmlspecialchars($produto['descricao']); ?></p>
            </div> 
            <div class="produto-info">
                <h1><?php echo htmlspecialchars($produto['nome']); ?></h1>
                <h2>Tamanho</h2>
                <div class="tamanho">
                    <button class="button_tamanho">PP</button>
                    <button class="button_tamanho">P</button>
                    <button class="button_tamanho">M</button>
                    <button class="button_tamanho">G</button>
                    <button class="button_tamanho">GG</button>
                </div>
                <h2>Preço</h2>
                <h3><span>R$ </span><?php echo number_format($produto['preco'], 2, ',', '.'); ?></h3>
                <div class="button-submit">
                    <button type="submit">Comprar</button>
                    <button type="submit">adicionar ao carrinho</button>
                </div>
            </div>
        </div>
            
        <div class="carousel-proprock">
            <div class="carousel-prock">
                <?php
                $carouselItems = [
                    ['id_produto' => 1, 'url' => '../public/img/produtos/calsa01.jpg', 'nome' => 'Calça'],
                    ['id_produto' => 2, 'url' => '../public/img/produtos/conjunto-01.jpg', 'nome' => 'Conjunto 01'],
                    ['id_produto' => 3, 'url' => '../public/img/produtos/conjunto-02.jpg', 'nome' => 'Conjunto 02'],
                    ['id_produto' => 4, 'url' => '../public/img/produtos/conjunto-03.jpg', 'nome' => 'Conjunto 03'],
                    ['id_produto' => 5, 'url' => '../public/img/produtos/conjunto-4.jpg', 'nome' => 'Conjunto 04'],
                    ['id_produto' => 6, 'url' => '../public/img/produtos/imgl1.png', 'nome' => 'Imagem 1'],
                    ['id_produto' => 7, 'url' => '../public/img/produtos/imgl2.png', 'nome' => 'Imagem 2'],
                    ['id_produto' => 8, 'url' => '../public/img/produtos/imgl3.png', 'nome' => 'Imagem 3'],
                    ['id_produto' => 14, 'url' => '../public/img/produtos/acessorios/IMGA1.png', 'nome' => 'Acessório 1'],
                    ['id_produto' => 15, 'url' => '../public/img/produtos/acessorios/IMGA2.png', 'nome' => 'Acessório 2'],
                    ['id_produto' => 16, 'url' => '../public/img/produtos/acessorios/IMGA3.png', 'nome' => 'Acessório 3'],
                    ['id_produto' => 17, 'url' => '../public/img/produtos/acessorios/IMGA4.png', 'nome' => 'Acessório 4'],
                ];

                foreach ($carouselItems as $item):
                ?>
                    <div class="item-carousel-roll">
                        <a href="produto.php?id=<?php echo $item['id_produto']; ?>">
                            <img src="<?php echo htmlspecialchars($item['url']); ?>" alt="<?php echo htmlspecialchars($item['nome']); ?>">
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </main>
    <script src="../public/script/app.js"></script>
</body>
</html>
