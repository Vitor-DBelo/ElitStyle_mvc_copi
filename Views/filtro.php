<?php
require_once __DIR__ . '/../backend/models/Produto.php';
$produtoModel = new Produto();

// Obter os produtos filtrados
$produtos = isset($produtos) ? $produtos : $produtoModel->getProdutos();

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Filtro de Produtos - Elit Style</title>
    <link rel="shortcut icon" href="../public/img/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="../public/css/style.css">
    <link rel="stylesheet" href="../public/css/animation.css">
    <link rel="stylesheet" href="../public/css/filtro.css">
    <script src="https://unpkg.com/scrollreveal"></script>
</head>
<body>
    <header> 
        <!-- Inclua o cabeçalho aqui (similar ao que está no filtro.html) -->
    </header>

    <div class="layout">
        <div id="filtro">
            <h3>Filtros</h3>
            <form action="index.php?route=filtro" method="GET">
                <input type="hidden" name="route" value="filtro">
                <label for="marca">Marca:</label>
                <select id="marca" name="marca">
                    <option value="">Todas</option>
                    <option value="Abidas">Abidas</option>
                    <!-- Adicione outras opções de marca aqui -->
                </select>
                <label for="precoMinimo">Preço Mínimo:</label>
                <input type="number" id="precoMinimo" name="precoMinimo" value="20">
                <label for="precoMaximo">Preço Máximo:</label>
                <input type="number" id="precoMaximo" name="precoMaximo" value="100">
                <label for="tamanho">Tamanho:</label>
                <select id="tamanho" name="tamanho">
                    <option value="">Todos</option>
                    <option value="PP">Extra Pequeno (PP)</option>
                    <!-- Adicione outras opções de tamanho aqui -->
                </select>
                <button type="submit" class="filtro-butt">Aplicar Filtros</button>
            </form>
        </div>

        <div class="galeria">
            <?php foreach ($produtos as $produto): ?>
                <div class="galeria-item">
                    <figure class="square">
                        <a href="index.php?route=produto&id=<?php echo $produto['id_produto']; ?>">
                            <img src="<?php echo htmlspecialchars($produto['url']); ?>" alt="<?php echo htmlspecialchars($produto['nome']); ?>">
                        </a>
                        <figcaption><?php echo htmlspecialchars($produto['nome']); ?></figcaption>
                    </figure>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script src="../public/script/app.js"></script>
</body>
</html>
