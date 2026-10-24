-- ==============================================================
-- TechByte Soluções - Script DDL de Criação de Banco de Dados
-- Disciplina: Modelagem e Banco de Dados
--
-- REQUISITOS ATENDIDOS:
-- 1. Tabelas suficientes (5 tabelas criadas, exigência mínima = 3).
-- 2. Chaves Primárias (PK) em todas as tabelas com AUTO_INCREMENT.
-- 3. Chaves Estrangeiras (FK) com integridade referencial.
-- 4. Relacionamento Muitos para Muitos (N:N) implementado entre
--    pedidos_orcamentos e produtos_servicos via tabela item_pedido.
-- ==============================================================

CREATE DATABASE IF NOT EXISTS techbyte_db
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE techbyte_db;

-- 1. TABELA DE CATEGORIAS (1:N com produtos_servicos)
CREATE TABLE IF NOT EXISTS categorias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL UNIQUE,
    slug VARCHAR(100) NOT NULL UNIQUE,
    descricao TEXT NULL,
    icone VARCHAR(50) DEFAULT 'bi-folder',
    data_criacao DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- 2. TABELA DE PRODUTOS E SERVIÇOS (Possui FK para categorias)
CREATE TABLE IF NOT EXISTS produtos_servicos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    categoria_id INT NOT NULL,
    nome VARCHAR(150) NOT NULL,
    descricao TEXT NOT NULL,
    tipo ENUM('produto', 'servico') NOT NULL DEFAULT 'produto',
    preco DECIMAL(10, 2) NOT NULL CHECK (preco >= 0),
    estoque INT NOT NULL DEFAULT 0 CHECK (estoque >= 0),
    peso_kg DECIMAL(5, 2) NOT NULL DEFAULT 0.00,
    imagem VARCHAR(255) NOT NULL,
    destaque TINYINT(1) NOT NULL DEFAULT 0,
    data_cadastro DATETIME DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_produtos_categoria
        FOREIGN KEY (categoria_id) REFERENCES categorias(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
) ENGINE=InnoDB;

-- 3. TABELA DE PEDIDOS E ORÇAMENTOS
CREATE TABLE IF NOT EXISTS pedidos_orcamentos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente_nome VARCHAR(120) NOT NULL,
    cliente_email VARCHAR(120) NOT NULL,
    cliente_telefone VARCHAR(30) NULL,
    status ENUM('orcamento', 'aprovado', 'em_transito', 'concluido', 'cancelado') NOT NULL DEFAULT 'orcamento',
    valor_total DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
    valor_frete DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
    regiao_entrega VARCHAR(50) DEFAULT 'sudeste',
    data_pedido DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- 4. TABELA ASSOCIATIVA INTERMEDIÁRIA N:N (MUITOS PARA MUITOS)
-- Um Pedido pode ter vários Produtos/Serviços.
-- Um Produto/Serviço pode estar em vários Pedidos.
CREATE TABLE IF NOT EXISTS item_pedido (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pedido_id INT NOT NULL,
    produto_id INT NOT NULL,
    quantidade INT NOT NULL DEFAULT 1 CHECK (quantidade > 0),
    preco_unitario DECIMAL(10, 2) NOT NULL CHECK (preco_unitario >= 0),
    CONSTRAINT fk_item_pedido_pedido
        FOREIGN KEY (pedido_id) REFERENCES pedidos_orcamentos(id)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
    CONSTRAINT fk_item_pedido_produto
        FOREIGN KEY (produto_id) REFERENCES produtos_servicos(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,
    CONSTRAINT uq_pedido_produto UNIQUE (pedido_id, produto_id)
) ENGINE=InnoDB;

-- 5. TABELA DE MENSAGENS E CONTATOS DO SITE
CREATE TABLE IF NOT EXISTS mensagens_contato (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(120) NOT NULL,
    email VARCHAR(120) NOT NULL,
    telefone VARCHAR(30) NULL,
    assunto VARCHAR(150) NOT NULL,
    mensagem TEXT NOT NULL,
    lida TINYINT(1) NOT NULL DEFAULT 0,
    data_envio DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Índices adicionais para otimização de consultas
CREATE INDEX idx_produtos_tipo_preco ON produtos_servicos(tipo, preco);
CREATE INDEX idx_pedidos_cliente ON pedidos_orcamentos(cliente_email);
