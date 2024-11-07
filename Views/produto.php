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
    <link rel="stylesheet" href="../public/css/modal.css">
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
                <li><a href="index.html">Lançamentos</a></li>
                <li><a href="index.html">Marcas</a></li>
                <li><a href="index.html">Acessórios</a></li>
                <li><a href="#" id="carrinho-icon">🛒</a></li>
                <li class="user-profile">
                    <img src="https://i.pinimg.com/564x/cb/2d/a9/cb2da9b8e06f5e2addc04d92d9fb64a1.jpg" alt="Foto do usuário">
                    <div class="dropdown-menu">
                        <ul>
                            <li><a href="usuario.php">Editar</a></li>
                            <li><a href="login.html">Login</a></li>
                        </ul>
                    </div>
                </li>
            </ul>
        </nav>
    </header>
    <div class="modal_comprar" id="modal-carrinho" style="display: none;">
        <div class="modal-content">
            <span class="close">&times;</span>
            <div id="top-carrinho">
                <h1>Seu Carrinho</h1>
            </div>
            <div id="main-carrinho">
                <div id="carrinho">
                    <div id="itens-carrinho"></div>
                    <div id="resumo-carrinho">
                        <h2>Resumo do Pedido</h2>
                        <div class="total">
                            <span>Total:</span>
                            <span id="total-carrinho">R$ 0.00</span>
                        </div>
                        <a href="compra.html?origem=carrinho" id="finalizar-compra" class="finalizar-compra-btn">Finalizar Compra</a>
                        <a href="#" id="limpar-carrinho" style="color: #aaa; font-size: 14px; text-decoration: none; display: block; text-align: center; margin-top: 15px; padding: 5px; border-bottom: 1px solid #ddd;">Limpar Carrinho</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
                    <button type="button" onclick="comprarProduto(<?php echo $produto['preco']; ?>, '<?php echo htmlspecialchars($produto['nome']); ?>')">Comprar</button>
                    <button type="button" id="adicionar-ao-carrinho" 
                        data-id="<?php echo $produto['id_produto']; ?>" 
                        data-nome="<?php echo htmlspecialchars($produto['nome']); ?>" 
                        data-preco="<?php echo $produto['preco']; ?>" 
                        data-descricao="<?php echo htmlspecialchars($produto['descricao']); ?>">
                        Adicionar ao carrinho
                    </button>
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
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const btnAdicionarAoCarrinho = document.getElementById('adicionar-ao-carrinho');
        const carrinhoIcon = document.getElementById('carrinho-icon');
        const modal = document.getElementById('modal-carrinho');
        const closeBtn = modal.querySelector('.close');
        
        btnAdicionarAoCarrinho.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const nome = this.getAttribute('data-nome');
            const preco = parseFloat(this.getAttribute('data-preco'));
            const descricao = this.getAttribute('data-descricao');
            
            adicionarAoCarrinho(id, nome, preco, descricao);
            mostrarNotificacao(`${nome} adicionado com sucesso`);
        });

        carrinhoIcon.addEventListener('click', function(e) {
            e.preventDefault();
            modal.style.display = 'block';
            document.body.style.overflow = 'hidden';
            carregarCarrinho();
        });

        closeBtn.addEventListener('click', function() {
            modal.style.display = 'none';
            document.body.style.overflow = '';
        });

        function adicionarAoCarrinho(id, nome, preco, descricao) {
            let carrinho = JSON.parse(localStorage.getItem('carrinho')) || [];
            let item = carrinho.find(item => item.id === id);
            
            if (item) {
                item.quantidade++;
            } else {
                const imagemUrl = document.querySelector('.produto-descri img').src;
                carrinho.push({id, nome, preco, descricao, quantidade: 1, imagemUrl});
            }
            
            localStorage.setItem('carrinho', JSON.stringify(carrinho));
            atualizarIconeCarrinho();
        }

        function carregarCarrinho() {
            let carrinho = JSON.parse(localStorage.getItem('carrinho')) || [];
            atualizarCarrinho(carrinho);
        }

        function atualizarCarrinho(carrinho) {
            let listaCarrinho = document.getElementById('itens-carrinho');
            let resumoCarrinho = document.getElementById('resumo-carrinho');
            
            listaCarrinho.innerHTML = '';
            let total = 0;

            carrinho.forEach((item, index) => {
                let itemDiv = document.createElement('div');
                itemDiv.className = 'item-carrinho';
                itemDiv.innerHTML = `
                    <div class="item-imagem">
                        <img src="${item.imagemUrl}" alt="${item.nome}">
                    </div>
                    <div class="item-detalhes">
                        <div class="item-texto">
                            <div class="item-nome">${item.nome}</div>
                            <div class="item-descricao">${item.descricao}</div>
                        </div>
                        <div class="item-info-container">
                            <div class="item-info-box">
                                <span class="item-info-label">Quantidade</span>
                                <div class="quantidade-controle">
                                    <button onclick="atualizarQuantidade(${index}, -1)">-</button>
                                    <span class="item-info-valor">${item.quantidade}</span>
                                    <button onclick="atualizarQuantidade(${index}, 1)">+</button>
                                </div>
                            </div>
                            <div class="item-info-box">
                                <span class="item-info-label">Valor unitário</span>
                                <span class="item-info-valor">R$ ${item.preco.toFixed(2)}</span>
                            </div>
                            <div class="item-info-box">
                                <span class="item-info-label">Preço</span>
                                <span class="item-info-valor item-preco-total">R$ ${(item.preco * item.quantidade).toFixed(2)}</span>
                            </div>
                        </div>
                    </div>
                `;
                listaCarrinho.appendChild(itemDiv);
                total += item.preco * item.quantidade;
            });

            resumoCarrinho.innerHTML = `
                <h2>Resumo do Pedido</h2>
                <div class="total">
                    <span>Total:</span>
                    <span id="total-carrinho">R$ ${total.toFixed(2)}</span>
                </div>
                <a href="compra.html?origem=carrinho" id="finalizar-compra" class="finalizar-compra-btn">Finalizar Compra</a>
                <a href="#" id="limpar-carrinho" style="color: #aaa; font-size: 14px; text-decoration: none; display: block; text-align: center; margin-top: 15px; padding: 5px; border-bottom: 1px solid #ddd;" ${carrinho.length === 0 ? 'class="disabled"' : ''}>Limpar Carrinho</a>
            `;

            document.getElementById('finalizar-compra').addEventListener('click', finalizarCompra);
            const limparBtn = document.getElementById('limpar-carrinho');
            if (carrinho.length > 0) {
                limparBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    limparCarrinho();
                });
            } else {
                limparBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    mostrarMensagemTemporaria('O carrinho já está vazio!');
                });
            }
        }

        window.atualizarQuantidade = function(index, delta) {
            let carrinho = JSON.parse(localStorage.getItem('carrinho')) || [];
            carrinho[index].quantidade += delta;
            if (carrinho[index].quantidade <= 0) {
                carrinho.splice(index, 1);
            }
            localStorage.setItem('carrinho', JSON.stringify(carrinho));
            atualizarCarrinho(carrinho);
            atualizarIconeCarrinho();
        }

        function finalizarCompra(event) {
            event.preventDefault();
            let carrinho = JSON.parse(localStorage.getItem('carrinho')) || [];
            if (carrinho.length > 0) {
                window.location.href = 'compra.html?origem=carrinho';
            } else {
                mostrarMensagemTemporaria('Seu carrinho está vazio. Adicione itens antes de finalizar a compra.');
            }
        }

        function mostrarMensagemTemporaria(mensagem) {
            const mensagemDiv = document.createElement('div');
            mensagemDiv.textContent = mensagem;
            mensagemDiv.style.cssText = `
                position: fixed;
                top: 20px;
                left: 50%;
                transform: translateX(-50%);
                background-color: #ff6b6b;
                color: white;
                padding: 10px 20px;
                border-radius: 5px;
                z-index: 1000;
                transition: opacity 0.5s ease-in-out;
            `;
            document.body.appendChild(mensagemDiv);

            setTimeout(() => {
                mensagemDiv.style.opacity = '0';
                setTimeout(() => {
                    document.body.removeChild(mensagemDiv);
                }, 500);
            }, 3000);
        }

        function atualizarIconeCarrinho() {
            const carrinhoIcon = document.querySelector('.nav-links a[href="#"]');
            carrinhoIcon.textContent = '🛒';
        }

        function mostrarNotificacao(mensagem) {
            const notificacao = document.createElement('div');
            notificacao.className = 'notification';
            notificacao.textContent = mensagem;
            document.body.appendChild(notificacao);

            setTimeout(() => {
                notificacao.classList.add('show');
            }, 10);

            setTimeout(() => {
                notificacao.classList.remove('show');
                setTimeout(() => {
                    notificacao.remove();
                }, 500);
            }, 3000);
        }

        // Atualizar o ícone do carrinho ao carregar a página
        atualizarIconeCarrinho();

        // Adicione a função limparCarrinho aqui, dentro do DOMContentLoaded
        function limparCarrinho() {
            localStorage.removeItem('carrinho');
            atualizarCarrinho([]);
            modal.style.display = 'none';
            document.body.style.overflow = '';
            mostrarMensagemTemporaria('Carrinho limpo com sucesso!');
        }
    });
    </script>
    <script>
    function comprarProduto(preco, nome) {
        localStorage.setItem('compraProdutoUnico', JSON.stringify({
            preco: preco,
            nome: nome,
            tipo: 'produto_unico'
        }));
        window.location.href = 'compra.html?origem=produto_unico';
    }
    </script>
</body>
</html>
