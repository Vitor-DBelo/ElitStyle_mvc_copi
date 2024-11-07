<?php
require_once('../backend/db/database.php');

// Inicializa as variáveis
$database = new Database();
$conn = $database->getConnection();
$produtos = [];
$termo_pesquisa = '';
$marca = '';
$precoMinimo = 189.00;
$precoMaximo = isset($_GET['precoMaximo']) ? floatval($_GET['precoMaximo']) : 3800.00;
$tamanho = '';

// Verifica se existe um termo de pesquisa ou filtros
if ((isset($_GET['search']) && !empty($_GET['search'])) || 
    (isset($_GET['marca']) && $_GET['marca'] !== 'todas') || 
    isset($_GET['precoMinimo']) || 
    isset($_GET['precoMaximo']) || 
    isset($_GET['tamanho'])) {
    
    $termo_pesquisa = isset($_GET['search']) ? $_GET['search'] : '';
    $marca = isset($_GET['marca']) ? $_GET['marca'] : 'todas';
    
    // Construir a consulta SQL base
    $sql = "SELECT DISTINCT p.* FROM produto p 
            LEFT JOIN categoria c ON p.id_produto = c.id_produto 
            WHERE 1=1";
    $params = [];
    $types = "";
    
    // Se a marca for 'Fake', prioriza a busca na categoria
    if ($marca === 'Fake') {
        $sql = "SELECT DISTINCT p.* FROM produto p 
                INNER JOIN categoria c ON p.id_produto = c.id_produto 
                WHERE LOWER(c.nome_categoria) = LOWER(?)";
        $params[] = $marca;
        $types .= "s";
    } else {
        // Adiciona condições baseadas nos filtros
        if (!empty($termo_pesquisa)) {
            if (strtolower(trim($termo_pesquisa)) === 'lançamento' || 
                strtolower(trim($termo_pesquisa)) === 'lancamento') {
                $sql = "SELECT DISTINCT p.* FROM produto p WHERE 1=1";
            } 
            elseif (strtolower(trim($termo_pesquisa)) === 'acessorios' || 
                    strtolower(trim($termo_pesquisa)) === 'acessórios') {
                $sql .= " AND LOWER(c.nome_categoria) = 'acessórios'";
            }
            else {
                $sql .= " AND (LOWER(p.nome) LIKE LOWER(?) OR LOWER(c.nome_categoria) LIKE LOWER(?))";
                $termo = "%{$termo_pesquisa}%";
                $params[] = $termo;
                $params[] = $termo;
                $types .= "ss";
            }
        }
        
        // Adiciona filtro de marca (se não for 'Fake')
        if ($marca !== 'todas') {
            $sql .= " AND LOWER(p.nome) LIKE LOWER(?)";
            $marcaTermo = "%{$marca}%";
            $params[] = $marcaTermo;
            $types .= "s";
        }
    }
    
    // Adiciona filtro de preço
    $sql .= " AND p.preco BETWEEN ? AND ?";
    $params[] = $precoMinimo;
    $params[] = $precoMaximo;
    $types .= "dd";
    
    // Prepara e executa a consulta
    $stmt = $conn->prepare($sql);
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $produtos[] = $row;
        }
    }
}
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
    <link rel="stylesheet" href="../public/css/modal.css">
    <!-- Adicione o script do ScrollReveal -->
    <script src="https://unpkg.com/scrollreveal"></script>
</head>
<body>
    <header> 
        <nav class="Nav_Bar">
            <div class="logo">
                <a href="index.html" class="logo-link">
                    <span>Elit</span>
                    <span class="Dorado">Style</span>
                </a>
            </div>
            <div class="search-pesqui">
                <form action="filtro.php" method="GET">
                    <input type="text" name="search" class="search-input" placeholder="Pesquise..." style="width: 565px; value="<?php echo htmlspecialchars($termo_pesquisa); ?>"> 
                    <button type="submit" class="search-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        </svg>
                    </button>
                </form>
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

    <!-- Adicione o modal do carrinho logo após o header -->
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

    <div class="layout">
        <div id="filtro">
            <h3>Filtros</h3>
            <form action="filtro.php" method="GET">
                <input type="hidden" name="search" value="<?php echo htmlspecialchars($termo_pesquisa); ?>">
                <label for="marca">Marca:</label>
                <select id="marca" name="marca">
                    <option value="todas" <?php echo $marca === 'todas' ? 'selected' : ''; ?>>Todas</option>
                    <option value="Abidas" <?php echo $marca === 'Abidas' ? 'selected' : ''; ?>>Abidas</option>
                    <option value="New era" <?php echo $marca === 'New era' ? 'selected' : ''; ?>>New era</option>
                    <option value="New balance" <?php echo $marca === 'New balance' ? 'selected' : ''; ?>>New balance</option>
                    <option value="Huf" <?php echo $marca === 'Huf' ? 'selected' : ''; ?>>Huf</option>
                    <option value="Vans" <?php echo $marca === 'Vans' ? 'selected' : ''; ?>>Vans</option>
                    <option value="Fake" <?php echo $marca === 'Fake' ? 'selected' : ''; ?>>Fake</option>
                    <option value="Pumba" <?php echo $marca === 'Pumba' ? 'selected' : ''; ?>>Pumba</option>
                    <option value="Lascou-se" <?php echo $marca === 'Lascou-se' ? 'selected' : ''; ?>>Lascou-se</option>
                </select>
                
                <label for="precoMinimo">Preço Mínimo:</label>
                <input type="number" id="precoMinimo" name="precoMinimo" value="189.00" readonly min="189.00" step="0.01">
                
                <label for="precoMaximo">Preço Máximo:</label>
                <input type="number" id="precoMaximo" name="precoMaximo" value="<?php echo htmlspecialchars($precoMaximo); ?>" min="189.00" step="0.01">
                
                <label for="tamanho">Tamanho:</label>
                <select id="tamanho" name="tamanho">
                    <option value="PP" <?php echo $tamanho === 'PP' ? 'selected' : ''; ?>>Extra Pequeno (PP)</option>
                    <option value="P" <?php echo $tamanho === 'P' ? 'selected' : ''; ?>>Pequeno (P)</option>
                    <option value="M" <?php echo $tamanho === 'M' ? 'selected' : ''; ?>>Médio (M)</option>
                    <option value="G" <?php echo $tamanho === 'G' ? 'selected' : ''; ?>>Grande (G)</option>
                    <option value="GG" <?php echo $tamanho === 'GG' ? 'selected' : ''; ?>>Extra grande (GG)</option>
                </select>
                
                <button type="submit" class="filtro-butt">Aplicar Filtros</button>
            </form>
        </div>

        <div class="galeria">
            <?php if (!empty($termo_pesquisa)): ?>
                <?php if (empty($produtos)): ?>
                    <div class="item-nao-encontrado">
                        <p>Item não encontrado</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($produtos as $produto): ?>
                        <div class="galeria-item">
                            <figure class="square">
                                <a href="produto.php?id=<?php echo $produto['id_produto']; ?>">
                                    <img src="../public<?php echo $produto['url']; ?>" alt="<?php echo htmlspecialchars($produto['nome']); ?>">
                                </a>
                                <figcaption><?php echo htmlspecialchars($produto['nome']); ?></figcaption>
                            </figure>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            <?php else: ?>
                <!-- Aqui você pode mostrar todos os produtos ou deixar vazio -->
                <div class="item-nao-encontrado">
                    <p>Digite algo para pesquisar</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Adicione este script no final do body -->
    <script src="../public/script/app.js"></script>

    <!-- Adicione este script antes do fechamento do body -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const carrinhoIcon = document.getElementById('carrinho-icon');
        const modal = document.getElementById('modal-carrinho');
        const closeBtn = modal.querySelector('.close');

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

        function limparCarrinho() {
            localStorage.removeItem('carrinho');
            atualizarCarrinho([]);
            modal.style.display = 'none';
            document.body.style.overflow = '';
            mostrarMensagemTemporaria('Carrinho limpo com sucesso!');
        }

        // Atualizar o ícone do carrinho ao carregar a página
        atualizarIconeCarrinho();
    });
    </script>

    <!-- Adicione este script após o formulário -->
    <script>
    document.getElementById('precoMaximo').addEventListener('change', function() {
        const min = 189.00;
        if (this.value < min) {
            this.value = min;
        }
    });
    </script>
</body>
</html>
