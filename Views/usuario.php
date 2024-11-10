<?php
require_once '../backend/controllers/UserDataController.php';
$userData = getUserData();
$userAddress = getUserAddress();

// Redirecionar se não estiver logado
if (!$userData) {
    header('Location: login.html');
    exit();
}



?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User</title>
    <link rel="shortcut icon" href="../public/img/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="../public/css/style.css">
    <link rel="stylesheet" href="../public/css/animation.css">
    <link rel="stylesheet" href="../public/css/modal.css">
    <link rel="stylesheet" href="../public/css/menu-mobile.css">
    <style>
         #searchInput {
            width: 565px;
        }
        @media screen and (max-width: 1536px) {
            #searchInput {
                width: 370px;
            }
        }
        @media screen and (min-width: 100px) and (max-width: 600px) {
            #searchInput {
                width: 100px;
            }
        }
    </style>
    
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
                <form action="filtro.php" method="GET" id="searchForm">
                    <input type="text" name="search" class="search-input" placeholder="Pesquise..."  id="searchInput">
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
            <ul class="nav-links">
                <div class="hamburger-menu">
                    <a href="menu.html">
                        <img src="../public/img/icon/icon_hamburger.png" alt="Menu" style="width: 30px; height: 30px;">
                    </a>
                </div>
                <li><a href="index.html#lancamentos">Lançamentos</a></li>
                <li><a href="index.html#marcas">Marcas</a></li>
                <li><a href="index.html#acessorios">Acessórios</a></li>
                <li><a href="#" id="carrinho-icon">🛒</a></li>
                <li class="user-profile">
                    <img src="<?php echo $imagemPerfil; ?>" alt="Foto do usuário">
                    <div class="dropdown-menu">
                        <ul>
                            <li><a href="#">Editar</a></li>
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
    
    <main class="content-user">
        <aside class="container_esquerda_esq">
            <div class="profile-user">
                <img class="img-user" src="<?php echo $imagemPerfil; ?>" alt="Imagem de Perfil">
                <p class="user-name"><?php echo htmlspecialchars($userData['nome']); ?></p>
                <div class="file-upload">
                    <form action="../backend/controllers/UserimgPerfil.php" method="POST" enctype="multipart/form-data">
                        <button type="button" class="custom-button" onclick="document.getElementById('imagem-input').click()">Alterar Imagem</button>
                        <input type="file" id="imagem-input" name="imagem-perfil" accept="image/*" style="display: none;" onchange="this.form.submit()">
                    </form>
                </div>
            </div>
            <div class="user-actions" style="margin-top: 390px; display: flex; gap: 20px; justify-content: left;">
                <a href="AlterUser.html"><button class="button">Alterar</button></a>
                <form action="../backend/controllers/DeletUser.php" method="POST">
                    <button type="submit" class="button">Deletar Conta</button>
                </form>
            </div>
        </aside>
        
        <section class="container_direita_dir">
            <div class="container_direita">
                <div class="mini-menu">
                    <button id="enderecoBtn" class="active">Endereço</button>
                    <button id="historicoBtn">Histórico</button>
                </div>
        
                <div id="endereco" class="content-section-endereco">
                    <?php if ($userAddress): ?>
                        <div class="input-group">
                            <p>Número:</p>
                            <p class="informacao_endereco"><?php echo htmlspecialchars($userAddress['numero']); ?></p>
                        </div>
                        <div class="input-group">
                            <p>Rua:</p>
                            <p class="informacao_endereco"><?php echo htmlspecialchars($userAddress['rua']); ?></p>
                        </div>
                        <div class="input-group">
                            <p>Bairro:</p>
                            <p class="informacao_endereco"><?php echo htmlspecialchars($userAddress['bairro']); ?></p>
                        </div>
                        <div class="input-group">
                            <p>CEP:</p>
                            <p class="informacao_endereco"><?php echo htmlspecialchars($userAddress['cep']); ?></p>
                        </div>
                        <div class="input-group">
                            <p>Cidade:</p>
                            <p class="informacao_endereco"><?php echo htmlspecialchars($userAddress['cidade']); ?></p>
                        </div>
                        <div class="input-group">
                            <p>Estado:</p>
                            <p class="informacao_endereco"><?php echo htmlspecialchars($userAddress['estado']); ?></p>
                        </div>
                    <?php else: ?>
                        <p>Nenhum endereço cadastrado.</p>
                    <?php endif; ?>
                    <a href="EditarEndereco.html"><button class="button" id="canto">Editar</button></a>
                </div>
        
                <div id="historico" class="content-section">
                    <p>Aqui será exibido o histórico de compras do usuário.</p>
                </div>
            </div>
        </section>        
    </main>
    <script src="../public/script/compra.js"></script>
    <script>
        // Seleciona os botões e as seções
const enderecoBtn = document.getElementById('enderecoBtn');
const historicoBtn = document.getElementById('historicoBtn');
const enderecoSection = document.getElementById('endereco');
const historicoSection = document.getElementById('historico');

// Adiciona os event listeners para os botões
enderecoBtn.addEventListener('click', function() {
    // Ativa o botão de endereço e desativa o de histórico
    enderecoBtn.classList.add('active');
    historicoBtn.classList.remove('active');

    // Exibe a seção de endereço e oculta a de histórico
    enderecoSection.style.display = 'block';
    historicoSection.style.display = 'none';
});

historicoBtn.addEventListener('click', function() {
    // Ativa o botão de histórico e desativa o de endereço
    historicoBtn.classList.add('active');
    enderecoBtn.classList.remove('active');

    // Exibe a seção de histórico e oculta a de endereço
    historicoSection.style.display = 'block';
    enderecoSection.style.display = 'none';
});

// Inicialmente, a seção de endereço estará visível
enderecoSection.style.display = 'block';
historicoSection.style.display = 'none';

document.getElementById('imagem-input').onchange = function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.querySelector('.img-user').src = e.target.result;
        }
        reader.readAsDataURL(file);
    }
}

    </script>
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

    function limparCarrinho() {
        localStorage.removeItem('carrinho');
        atualizarCarrinho([]);
        modal.style.display = 'none';
        document.body.style.overflow = '';
        mostrarMensagemTemporaria('Carrinho limpo com sucesso!');
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

    // Atualizar o ícone do carrinho ao carregar a página
    atualizarIconeCarrinho();
});
</script>
<div class="overlay"></div>
<div class="menu-mobile">
    <button class="close-btn">×</button>
    <ul>
        <li><a href="index.html#lancamentos">Lançamentos</a></li>
        <li><a href="index.html#acessorios">Acessórios</a></li>
        <li><a href="#" id="carrinho-icon-mobile">🛒</a></li>
        <hr class="separator">
        <li><a href="usuario.php">Editar</a></li>
        <li><a href="login.html">Login</a></li>
    </ul>
</div>
<script src="../public/script/menu-mobile.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Seleciona os elementos do menu mobile
        const menuIcon = document.querySelector('.menu-icon');
        const menuMobile = document.querySelector('.menu-mobile');
        const overlay = document.querySelector('.overlay');
        const closeBtn = document.querySelector('.close-btn');

        // Abre o menu mobile
        menuIcon.addEventListener('click', function() {
            menuMobile.classList.add('active');
            overlay.classList.add('active');
            document.body.style.overflow = 'hidden';
        });

        // Fecha o menu mobile
        function closeMenu() {
            menuMobile.classList.remove('active');
            overlay.classList.remove('active');
            document.body.style.overflow = '';
        }

        closeBtn.addEventListener('click', closeMenu);
        overlay.addEventListener('click', closeMenu);
    });
</script>
</body>
</html>