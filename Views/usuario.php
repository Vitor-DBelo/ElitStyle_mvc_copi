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
                <input type="text" class="search-input" placeholder="Pesquise...">
                <button class="search-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
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
</body>
</html>