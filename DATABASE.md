# Configuração do Banco de Dados

## MySQL - Produção (Hostinger)

| Config | Valor |
|--------|-------|
| Host | localhost |
| Banco | u361083290_db_conectadosp |
| Usuário | u361083290_dev |
| Senha | C0n3c74d03m534rg1p3 |
| Charset | utf8mb4 |

## Criar database e usuário

```sql
CREATE DATABASE IF NOT EXISTS u361083290_db_conectadosp 
    DEFAULT CHARACTER SET utf8mb4 
    DEFAULT COLLATE utf8mb4_unicode_ci;

CREATE USER IF NOT EXISTS 'u361083290_dev'@'localhost' IDENTIFIED BY 'C0n3c74d03m534rg1p3';
GRANT ALL PRIVILEGES ON u361083290_db_conectadosp.* TO 'u361083290_dev'@'localhost';
FLUSH PRIVILEGES;
```

## Comandos SQL

### Criar tabelas

```sql
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
    estado VARCHAR(50) DEFAULT 'Sergipe',
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

CREATE INDEX idx_anuncios_categoria ON anuncios(categoria_id);
CREATE INDEX idx_anuncios_cidade ON anuncios(cidade);
CREATE INDEX idx_anuncios_destaque ON anuncios(destaque);
CREATE INDEX idx_anuncios_status ON anuncios(status);
CREATE INDEX idx_imagens_anuncio ON imagens(anuncio_id);
CREATE INDEX idx_horarios_anuncio ON horarios(anuncio_id);
```

### Popular dados

```sql
-- Usuários admin (senha: 53nha_4dm1n)
INSERT INTO usuarios (username, nome, password, is_active, created_at) VALUES
('jairo', 'Administrador', '$2y$12$yANVsR8SVkG4xVoq7sZZZeSSzkX66LSWQB8blNLYIf3LAmxQrFrUK', 1, NOW());

INSERT INTO categorias (nome, slug, descricao, icone, created_at) VALUES
('Eletricista', 'eletricista', 'Instalações elétricas residenciais e comerciais', 'zap', '2026-04-24 12:42:44'),
('Encanador', 'encanador', 'Serviços hidráulicos e reparos', 'droplets', '2026-04-24 12:42:44'),
('Pedreiro', 'pedreiro', 'Alvenaria e reformas', 'hard-hat', '2026-04-24 12:42:44'),
('Pintor', 'pintor', 'Pintura residencial e comercial', 'paint-bucket', '2026-04-24 12:42:44'),
('Diarista', 'diarista', 'Limpeza doméstica', 'brush', '2026-04-24 12:42:44'),
('Cabeleireiro', 'cabeleireiro', 'Cortes e tratamentos', 'scissors', '2026-04-24 12:42:44'),
('Fotografia', 'fotografia', 'Ensaios fotográficos', 'camera', '2026-04-24 12:42:44'),
('Confeitaria', 'confeitaria', 'Bolos e doces', 'coffee', '2026-04-24 12:42:44'),
('Mecânico', 'mecanico', 'Automotivo', 'wrench', '2026-04-24 12:42:44'),
('Frete', 'frete', 'Fretes e mudanças', 'truck', '2026-04-24 12:42:44'),
('Programador', 'programador', 'Desenvolvimento e/ou manutenção de Sistemas', 'monitor', '2026-05-03 18:31:21');

INSERT INTO config (chave, valor, updated_at) VALUES
('site_nome', 'Conectado em Sergipe', '2026-04-24 12:42:44'),
('site_descricao', 'A maior vitrine de serviços e profissionais de Sergipe', '2026-04-24 12:42:44'),
('site_email', 'contato@conectadoemsergipe.com.br', '2026-04-24 12:42:44'),
('site_telefone', '79999999999', '2026-04-24 12:42:44'),
('site_whatsapp', '5579999999999', '2026-04-24 12:42:44'),
('site_cidade', 'Aracaju', '2026-04-24 12:42:44'),
('site_estado', 'Sergipe', '2026-04-24 12:42:44'),
('facebook', 'https://facebook.com/conectadoemsergipe', '2026-04-24 12:42:44'),
('instagram', 'https://instagram.com/conectadoemsergipe', '2026-04-24 12:42:44'),
('youtube', '', '2026-04-24 12:42:44');

INSERT INTO configuracoes (chave, valor, descricao, categoria) VALUES
('site_nome', 'Conectado em Sergipe', 'Nome principal da plataforma', 'geral'),
('site_whatsapp', '5579999999998', 'WhatsApp de suporte para o rodapé', 'contato'),
('site_instagram', 'conectadoemsergipe', 'Username do Instagram', 'contato'),
('hero_titulo', 'Conectado em Sergipe é a plataforma ideal para encontrar serviços locais na sua cidade.', 'Título principal do Hero na Home', 'home'),
('hero_subtitulo', 'Conectamos prestadores qualificados a pessoas que realmente precisam, de forma rápida, simples e eficiente.', 'Subtítulo do Hero na Home', 'home');
```

## Usuários admin (senha: 53nha_4dm1n)