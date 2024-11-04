-- Criação do banco de dados elitstyle
CREATE DATABASE elitstyle
DEFAULT CHARACTER SET utf8mb4
DEFAULT COLLATE utf8mb4_general_ci;

-- Selecionar o banco de dados
USE elitstyle;

-- Tabela user_db1 (usuários)
CREATE TABLE user_db1 (
    id_user INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    senha VARCHAR(255) NOT NULL,
    telefone VARCHAR(20),
    imagem_perfil VARCHAR(255) DEFAULT '../public/img/perfil/gato.jpg',
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status TINYINT(1) DEFAULT 1,
    data_atualizacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);


-- Tabela endereco
CREATE TABLE endereco (
    id_endereco INT AUTO_INCREMENT PRIMARY KEY,
    numero VARCHAR(10),
    rua VARCHAR(100),
	bairro VARCHAR(100),
    cep VARCHAR(20) NOT NULL,
    estado VARCHAR(50) NOT NULL,
    cidade VARCHAR(100) NOT NULL,
    id_user_db1 INT,
    FOREIGN KEY (id_user_db1) REFERENCES user_db1(id_user) ON DELETE CASCADE
);

-- Tabela produto
CREATE TABLE produto (
    id_produto INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    descricao TEXT,
    preco DECIMAL(10,2) NOT NULL,
    quantidade_estoque INT DEFAULT 0,
    url TEXT
);

-- Tabela categoria
CREATE TABLE categoria (
    id_categoria INT AUTO_INCREMENT PRIMARY KEY,
    nome_categoria VARCHAR(255) NOT NULL,
    id_produto INT,
    FOREIGN KEY (id_produto) REFERENCES produto(id_produto) ON DELETE SET NULL
);

-- Tabela envio
CREATE TABLE envio (
    id_envio INT AUTO_INCREMENT PRIMARY KEY,
    data_envio TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    id_produto INT,
    FOREIGN KEY (id_produto) REFERENCES produto(id_produto) ON DELETE CASCADE
);

-- Tabela historico (histórico de compras)
CREATE TABLE historico (
    id_historico INT AUTO_INCREMENT PRIMARY KEY,
    data_compra TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    id_user_db1 INT,
    id_envio INT,
    id_produto INT,
    FOREIGN KEY (id_user_db1) REFERENCES user_db1(id_user) ON DELETE CASCADE,
    FOREIGN KEY (id_envio) REFERENCES envio(id_envio) ON DELETE CASCADE,
    FOREIGN KEY (id_produto) REFERENCES produto(id_produto) ON DELETE CASCADE
);

-- Tabela venda_item (itens vendidos)
CREATE TABLE venda_item (
    id_venda_item INT AUTO_INCREMENT PRIMARY KEY,
    id_produto INT,
    id_historico INT,
    id_categoria INT,
    FOREIGN KEY (id_produto) REFERENCES produto(id_produto) ON DELETE CASCADE,
    FOREIGN KEY (id_historico) REFERENCES historico(id_historico) ON DELETE CASCADE,
    FOREIGN KEY (id_categoria) REFERENCES categoria(id_categoria) ON DELETE SET NULL
);

-- Modifique os caminhos das imagens nos INSERTs
UPDATE produto SET url = REPLACE(url, '../public/', '/');

-- Inserção de produtos
INSERT INTO produto (nome, descricao, preco, quantidade_estoque, url) VALUES
('elittstritt', 'Conjunto exclusivo de parceria, a calça eleiitt combina conforto e estilo esportivo em um design luxuoso e único.', 521.00, 15, '/img/produtos/calsa01.jpg'),
('noir royale set', 'Conjunto com jaqueta preta e calça de logo exclusivo. Luxo e estilo urbano imponente.', 872.00, 8, '/img/produtos/conjunto-01.jpg'),
('elysian elite', 'Casaco preto e camiseta "elitstyle". Exclusividade, classe e conforto.', 1123.00, 10, '/img/produtos/conjunto-02.jpg'),
('conjunto elitstyle', 'Casaco preto e camiseta "elitstyle". Exclusividade, classe e conforto.', 1887.00, 12, '/img/produtos/conjunto-03.jpg'),
('imperial beige ensemble', 'Conjunto com jaqueta bege refinada. Discreto e sofisticado.', 2542.00, 5, '/img/produtos/conjunto-4.jpg'),
('supreme streetwear edition', 'Suéter premium com logo "streetwear". Minimalista, confortável e elegante.', 429.00, 20, '/img/produtos/imgl1.png'),
('obsidian luxe tee', 'Camiseta preta unissex. Simples, exclusiva e luxuosa.', 449.00, 18, '/img/produtos/imgl2.png'),
('emerald prestige joggers', 'Calça de moletom verde com logo "streetwear". Casual, confortável e sofisticada.', 469.00, 25, '/img/produtos/imgl3.png'),
('Titan Noir Sweatshirt', 'Moletom preto luxuoso com logo "Streetwear". Elegância minimalista e conforto superior.', 479.00, 30, '/img/produtos/imgl4.png'),
('Velvet Shadow Joggers', 'Calça jogger preta com bordado sofisticado e conforto elevado.', 489.00, 15, '/img/produtos/imgl5.png'),
('Evergreen Street Tee', 'Camiseta verde oversized, casual e sofisticada, com um toque de estilo urbano.', 509.00, 20, '/img/produtos/imgl6.png'),
('Street War', 'Moletom exclusivo que traz o espírito de batalha urbana em cada detalhe. Estilo audacioso para quem luta nas ruas com atitude.', 529.00, 10, '/img/produtos/imgl7.png'),
('Silent Command', 'Camiseta minimalista e exclusiva, com uma mensagem sutil para os que lideram sem fazer alarde. Estilo discreto com poder nas entrelinhas.', 549.00, 7, '/img/produtos/imgl8.png'),
('boné signature', 'Boné de edição limitada com design minimalista, destacando o logo exclusivo da marca em uma peça moderna.', 189.00, 10, '/img/produtos/acessorios/imga1.png'),
('velano primus', 'Boné exclusivo em tecido refinado, moldado para líderes que valorizam a distinção em cada detalhe.', 199.00, 8, '/img/produtos/acessorios/imga2.png'),
('lúmen argentis', 'Pulseira de elos prateados radiantes, feita para refletir a luz de quem a carrega com autoridade e elegância.', 209.00, 12, '/img/produtos/acessorios/imga3.png'),
('eclipse side', 'Um colar singular, capturando a essência das estrelas e um brilho único que transcende o tempo.', 220.00, 12, '/img/produtos/acessorios/imga4.png'),
('aurum noctis', 'Mistura o preto profundo e o branco puro, ideal para quem transita entre o clássico e o moderno com elegância natural.', 235.00, 6, '/img/produtos/acessorios/imga5.png'),
('solaris prism', 'Chapéu vibrante com bordado multicolorido, representando a fusão entre o estilo urbano e o luxo atemporal.', 245.00, 20, '/img/produtos/acessorios/imga6.png'),
('cortez argentum', 'Uma corrente de elos espessos e polidos, criada para aqueles que valorizam a presença imponente e o estilo refinado.', 305.00, 18, '/img/produtos/acessorios/imga7.png'),
('véloce platinum', 'Uma corrente prateada de design elegante e sutil, ideal para compor visuais sofisticados e minimalistas com um toque de exclusividade.', 345.00, 10, '/img/produtos/acessorios/imga8.png');

-- Inserção de categorias
INSERT INTO categoria (nome_categoria, id_produto) VALUES
('conjuntos', 10),
('conjuntos', 11),
('conjuntos', 12),
('conjuntos', 13),
('conjuntos', 14),
('acessórios', 1),
('acessórios', 2),
('acessórios', 3),
('acessórios', 4),
('acessórios', 5),
('acessórios', 6),
('acessórios', 7),
('acessórios', 8),
('acessórios', 9);

-- Modifique os caminhos das imagens nos INSERTs
UPDATE produto SET url = REPLACE(url, '../public/', '/');

select * from user_db1 ;
select * from produto;

select * from endereco ;
describe endereco;
ALTER TABLE endereco MODIFY COLUMN informacoes_adicionais TEXT;


-- Remover o banco de dados, caso necessário
DROP DATABASE elitstyle
