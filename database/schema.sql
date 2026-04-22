CREATE DATABASE IF NOT EXISTS guia_servicos CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE guia_servicos;

CREATE TABLE IF NOT EXISTS usuarios (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    nome VARCHAR(150) DEFAULT NULL,
    password VARCHAR(255) NOT NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS categorias (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(120) NOT NULL,
    slug VARCHAR(140) NOT NULL UNIQUE,
    descricao VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS anuncios (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(180) NOT NULL,
    slug VARCHAR(200) NOT NULL UNIQUE,
    descricao TEXT NOT NULL,
    categoria_id INT UNSIGNED NOT NULL,
    telefone VARCHAR(30) NOT NULL,
    cidade VARCHAR(120) NOT NULL,
    imagem_principal VARCHAR(255) DEFAULT NULL,
    destaque TINYINT(1) NOT NULL DEFAULT 0,
    status ENUM('ativo','inativo') NOT NULL DEFAULT 'ativo',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_anuncios_categoria FOREIGN KEY (categoria_id) REFERENCES categorias(id) ON DELETE RESTRICT ON UPDATE CASCADE,
    INDEX idx_anuncios_categoria (categoria_id),
    INDEX idx_anuncios_cidade (cidade),
    INDEX idx_anuncios_destaque (destaque),
    INDEX idx_anuncios_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS imagens (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    anuncio_id INT UNSIGNED NOT NULL,
    caminho VARCHAR(255) NOT NULL,
    ordem INT UNSIGNED NOT NULL DEFAULT 0,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_imagens_anuncio FOREIGN KEY (anuncio_id) REFERENCES anuncios(id) ON DELETE CASCADE ON UPDATE CASCADE,
    INDEX idx_imagens_anuncio (anuncio_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO categorias (nome, slug, descricao) VALUES
('Pedreiro', 'pedreiro', 'Serviços de alvenaria e reforma.'),
('Encanador', 'encanador', 'Serviços hidráulicos residenciais e comerciais.'),
('Eletricista', 'eletricista', 'Instalações e manutenção elétrica.'),
('Pintor', 'pintor', 'Pintura residencial e comercial.'),
('Limpeza', 'limpeza', 'Serviços gerais de limpeza.')
ON DUPLICATE KEY UPDATE nome = VALUES(nome), descricao = VALUES(descricao);

-- Senha padrão sugerida para o seed: admin123
-- Gere novamente em produção se preferir.
INSERT INTO usuarios (username, nome, password)
VALUES ('admin', 'Administrador', '$2y$10$WjD1quI6C2K8j1rI6db2fuWw5LTBhl5xQJh9S0i5K3q8YfM9E4m6G')
ON DUPLICATE KEY UPDATE nome = VALUES(nome);
