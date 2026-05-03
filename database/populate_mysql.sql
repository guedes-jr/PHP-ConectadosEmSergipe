-- Dados para popular o banco MySQL
-- Execute após criar as tabelas

-- Usuários (senha: admin123)
INSERT INTO usuarios (username, nome, password, is_active, created_at) VALUES
('admin', 'Administrador', '$2y$12$GiFelnJHjsJK33YNnksQ4O5nQPcADt7vhd/LGs2etbi4DNu0FFI8e', 1, '2026-04-24 12:42:44'),
('joao', 'João Silva', '$2y$12$GiFelnJHjsJK33YNnksQ4O5nQPcADt7vhd/LGs2etbi4DNu0FFI8e', 1, '2026-04-24 12:42:44'),
('maria', 'Maria Santos', '$2y$12$GiFelnJHjsJK33YNnksQ4O5nQPcADt7vhd/LGs2etbi4DNu0FFI8e', 1, '2026-04-24 12:42:44');

-- Categorias
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
('Programador', 'programador', 'Desenvimento e/ou manutenção de Sistemas', 'monitor', '2026-05-03 18:31:21');

-- Clientes
INSERT INTO clientes (nome, email, telefone, whatsapp, cidade, cep, rua, bairro, estado, regiao, created_at) VALUES
('João Guedes', 'joao@teste.com', '(44) 44444-4444', '(44) 44444-4444', 'Natal', '59069-250', 'Rua Escritor Mário de Andrade', 'Pitimbu', 'RN', '', '2026-05-03 18:05:44');

-- Configurações do site
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

-- Configurações dinâmicas
INSERT INTO configuracoes (chave, valor, descricao, categoria) VALUES
('site_nome', 'Conectado em Sergipe', 'Nome principal da plataforma', 'geral'),
('site_whatsapp', '5579999999998', 'WhatsApp de suporte para o rodapé', 'contato'),
('site_instagram', 'conectadoemsergipe', 'Username do Instagram', 'contato'),
('hero_titulo', 'Conectado em Sergipe é a plataforma ideal para encontrar serviços locais na sua cidade.', 'Título principal do Hero na Home', 'home'),
('hero_subtitulo', 'Conectamos prestadores qualificados a pessoas que realmente precisam, de forma rápida, simples e eficiente.', 'Subtítulo do Hero na Home', 'home');