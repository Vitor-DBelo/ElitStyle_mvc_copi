-- Criação do banco de dados ElitStyle
CREATE DATABASE elitStyle
default character set utf8mb4
default collate utf8mb4_general_ci;

-- Selecionar o banco de dados
USE ElitStyle;

-- Tabela User_db1 (usuários)
CREATE TABLE User_db1 (
    id_user INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    senha VARCHAR(255) NOT NULL,
    telefone VARCHAR(20),
    dataCriacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabela Endereco
CREATE TABLE Endereco (
    id_endereco INT AUTO_INCREMENT PRIMARY KEY,
    Estado VARCHAR(50) NOT NULL,
    cidade VARCHAR(100) NOT NULL,
    bairro VARCHAR(100),
    rua VARCHAR(100),
    numero VARCHAR(10),
    cep VARCHAR(20) NOT NULL,
    tipo_endereco VARCHAR(50),
    idUser_db1 INT,
    FOREIGN KEY (idUser_db1) REFERENCES User_db1(id_user) ON DELETE CASCADE
);

-- Tabela produto
CREATE TABLE produto (
    id_produto INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    descricao TEXT,
    preco DECIMAL(10, 2) NOT NULL,
    quantidade_estoque INT DEFAULT 0,
    url text
);

-- Tabela categoria
CREATE TABLE categoria (
    id_categoria INT AUTO_INCREMENT PRIMARY KEY,
    nome_categoria VARCHAR(255) NOT NULL, -- Exemplos: 'conjuntos', 'acessórios', etc.
    idproduto INT,
    FOREIGN KEY (idproduto) REFERENCES produto(id_produto) ON DELETE SET NULL
);

-- Tabela envio
CREATE TABLE envio (
    id_envio INT AUTO_INCREMENT PRIMARY KEY,
    data_envio TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    idproduto INT,
    FOREIGN KEY (idproduto) REFERENCES produto(id_produto) ON DELETE CASCADE
);

-- Tabela historico (histórico de compras)
CREATE TABLE historico (
    id_historico INT AUTO_INCREMENT PRIMARY KEY,
    datacompra TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    idUser_db1 INT,
    idenvio INT,
    idproduto INT,
    FOREIGN KEY (idUser_db1) REFERENCES User_db1(id_user) ON DELETE CASCADE,
    FOREIGN KEY (idenvio) REFERENCES envio(id_envio) ON DELETE CASCADE,
    FOREIGN KEY (idproduto) REFERENCES produto(id_produto) ON DELETE CASCADE
);

-- Tabela venda_item (itens vendidos)
CREATE TABLE venda_item (
    id_venda_item INT AUTO_INCREMENT PRIMARY KEY,
    idproduto INT,
    idhistorico INT,
    idcategoria INT,
    FOREIGN KEY (idproduto) REFERENCES produto(id_produto) ON DELETE CASCADE,
    FOREIGN KEY (idhistorico) REFERENCES historico(id_historico) ON DELETE CASCADE,
    FOREIGN KEY (idcategoria) REFERENCES categoria(id_categoria) ON DELETE SET NULL
);

-- Modifique os caminhos das imagens nos INSERTs
UPDATE produto SET url = REPLACE(url, '../public/', '/');

-- Por exemplo:
-- 'Acessório 1', 'Descrição do Acessório 1', 189.00, 10, '/img/produtos/acessorios/IMGA1.png'),
-- 'Acessório 2', 'Descrição do Acessório 2', 199.00, 8, '/img/produtos/acessorios/IMGA2.png'),
-- ...

INSERT INTO produto (nome, descricao, preco, quantidade_estoque, url) VALUES
('Acessório 1', 'Descrição do Acessório 1', 189.00, 10, '/img/produtos/acessorios/IMGA1.png'),
('Acessório 2', 'Descrição do Acessório 2', 199.00, 8, '/img/produtos/acessorios/IMGA2.png'),
('Acessório 3', 'Descrição do Acessório 3', 209.00, 12, '/img/produtos/acessorios/IMGA3.png'),
('Acessório 4', 'Descrição do Acessório 4', 220.00, 12, '/img/produtos/acessorios/IMGA4.png'),
('Acessório 5', 'Descrição do Acessório 5', 235.00, 6, '/img/produtos/acessorios/IMGA5.png'),
('Acessório 6', 'Descrição do Acessório 6', 245.00, 20, '/img/produtos/acessorios/IMGA6.png'),
('Acessório 7', 'Descrição do Acessório 7', 305.00, 18, '/img/produtos/acessorios/IMGA7.png'),
('Acessório 8', 'Descrição do Acessório 8', 345.00, 10, '/img/produtos/acessorios/IMGA8.png'),
('Calça', 'Descrição da calça', 521.00, 15, '/public/img/produtos/calsa01.jpg'),
('Conjunto 01', 'Descrição do conjunto 01', 872.00, 8, '/public/img/produtos/conjunto-01.jpg'),
('Conjunto 02', 'Descrição do conjunto 02', 1123.00, 10, '/public/img/produtos/conjunto-02.jpg'),
('Conjunto 03', 'Descrição do conjunto 03', 1887.00, 12, '/public/img/produtos/conjunto-03.jpg'),
('Conjunto 04', 'Descrição do conjunto 04', 2542.00, 5, '/public/img/produtos/conjunto-4.jpg'),
('Imagem 1', 'Descrição da imagem 1', 429.00, 20, '/public/img/produtos/img1.png'),
('Imagem 2', 'Descrição da imagem 2', 449.00, 18, '/public/img/produtos/img2.png'),
('Imagem 3', 'Descrição da imagem 3', 469.00, 25, '/public/img/produtos/img3.png'),
('Imagem 4', 'Descrição da imagem 4', 479.00, 30, '/public/img/produtos/img4.png'),
('Imagem 5', 'Descrição da imagem 5', 489.00, 15, '/public/img/produtos/img5.png'),
('Imagem 6', 'Descrição da imagem 6', 509.00, 20, '/public/img/produtos/img6.png'),
('Imagem 7', 'Descrição da imagem 7', 529.00, 10, '/public/img/produtos/img7.png'),
('Imagem 8', 'Descrição da imagem 8', 549.00, 7, '/public/img/produtos/img8.png');

INSERT INTO categoria (nome_categoria, idproduto) VALUES
('acessórios', 1),
('acessórios', 2),
('acessórios', 3),
('acessórios', 4),
('acessórios', 5),
('acessórios', 6),
('acessórios', 7),
('acessórios', 8),
('acessórios', 9),
('conjuntos', 10),
('conjuntos', 11),
('conjuntos', 12),
('conjuntos', 13),
('conjuntos', 14);
