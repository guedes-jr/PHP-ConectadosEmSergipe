-- Schema MySQL para produção (Hostinger)
-- Execute: mysql -u dev -p db_conectadosp < database/schema_mysql.sql

SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE IF NOT EXISTS usuarios (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    nome VARCHAR(100),
    password VARCHAR(255) NOT NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS categorias (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    slug VARCHAR(100) NOT NULL UNIQUE,
    descricao TEXT,
    icone VARCHAR(50),
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS clientes (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE,
    telefone VARCHAR(20),
    whatsapp VARCHAR(20),
    cidade VARCHAR(100),
    cep VARCHAR(20),
    rua VARCHAR(255),
    bairro VARCHAR(100),
    estado VARCHAR(50),
    regiao VARCHAR(100),
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS anuncios (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(200) NOT NULL,
    slug VARCHAR(200) NOT NULL UNIQUE,
    descricao TEXT NOT NULL,
    categoria_id INT UNSIGNED NOT NULL,
    telefone VARCHAR(20) NOT NULL,
    whatsapp VARCHAR(20),
    email VARCHAR(100),
    endereco VARCHAR(255),
    cidade VARCHAR(100) NOT NULL,
    regiao VARCHAR(100),
    imagem_principal VARCHAR(255),
    imagem_banner VARCHAR(255),
    destaque TINYINT(1) NOT NULL DEFAULT 0,
    status VARCHAR(20) NOT NULL DEFAULT 'ativo',
    nota DECIMAL(3,1) DEFAULT 0,
    avaliacoes INT UNSIGNED DEFAULT 0,
    visualizacoes INT UNSIGNED DEFAULT 0,
    instagram VARCHAR(100),
    facebook VARCHAR(255),
    cliente_id INT UNSIGNED,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME,
    FOREIGN KEY (categoria_id) REFERENCES categorias(id) ON DELETE RESTRICT,
    FOREIGN KEY (cliente_id) REFERENCES clientes(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS imagens (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    anuncio_id INT UNSIGNED NOT NULL,
    caminho VARCHAR(255) NOT NULL,
    ordem INT UNSIGNED NOT NULL DEFAULT 0,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (anuncio_id) REFERENCES anuncios(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS horarios (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    anuncio_id INT UNSIGNED NOT NULL,
    dia_semana TINYINT(1) NOT NULL,
    abertura TIME,
    fechamento TIME,
    fechado TINYINT(1) DEFAULT 0,
    FOREIGN KEY (anuncio_id) REFERENCES anuncios(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS depoimentos (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    texto TEXT NOT NULL,
    nota TINYINT(1) NOT NULL DEFAULT 5,
    status VARCHAR(20) NOT NULL DEFAULT 'aprovado',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS configuracoes (
    chave VARCHAR(100) PRIMARY KEY,
    valor TEXT,
    descricao VARCHAR(255),
    categoria VARCHAR(50)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS config (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    chave VARCHAR(100) NOT NULL UNIQUE,
    valor TEXT NOT NULL,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Índices
CREATE INDEX idx_anuncios_categoria ON anuncios(categoria_id);
CREATE INDEX idx_anuncios_cidade ON anuncios(cidade);
CREATE INDEX idx_anuncios_destaque ON anuncios(destaque);
CREATE INDEX idx_anuncios_status ON anuncios(status);
CREATE INDEX idx_imagens_anuncio ON imagens(anuncio_id);
CREATE INDEX idx_horarios_anuncio ON horarios(anuncio_id);