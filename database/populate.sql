-- Dados fake para testes
-- Execute após criar as tabelas

-- Usuários (senha: admin123 para todos)
INSERT INTO usuarios (username, nome, password) VALUES 
('admin', 'Administrador', '$2y$12$GiFelnJHjsJK33YNnksQ4O5nQPcADt7vhd/LGs2etbi4DNu0FFI8e'),
('joao', 'João Silva', '$2y$12$GiFelnJHjsJK33YNnksQ4O5nQPcADt7vhd/LGs2etbi4DNu0FFI8e'),
('maria', 'Maria Santos', '$2y$12$GiFelnJHjsJK33YNnksQ4O5nQPcADt7vhd/LGs2etbi4DNu0FFI8e');

-- Categorias
INSERT INTO categorias (nome, slug, descricao, icone) VALUES 
('Eletricista', 'eletricista', 'Instalações elétricas residenciais e comerciais', '⚡'),
('Encanador', 'encanador', 'Serviços hidráulicos e reparos', '🔧'),
('Pedreiro', 'pedreiro', 'Alvenaria e reformas', '👷'),
('Pintor', 'pintor', 'Pintura residencial e comercial', '🎨'),
('Diarista', 'diarista', 'Limpeza doméstica', '🧹'),
('Cabeleireiro', 'cabeleireiro', 'Cortes e tratamentos', '💇'),
('Fotografia', 'fotografia', 'Ensaios fotográficos', '📸'),
('Confeitaria', 'confeitaria', 'Bolos e doces', '🎂'),
('Mecânico', 'mecanico', 'Automotivo', '🔩'),
('Frete', 'frete', 'Fretes e mudanças', '🚚');

-- Anúncios
INSERT INTO anuncios (titulo, slug, descricao, categoria_id, telefone, whatsapp, cidade, imagem_principal, destaque, status, nota, avaliacoes) VALUES 
('Eletro Aju - Eletricista', 'eletro-aju', 'Instalações elétricas residenciais e comerciais. Profissionais qualificados com anos de experiência.', 1, '79999991234', '557999991234', 'Aracaju', 'assets/img/hero-electrician.jpg', 1, 'ativo', 4.9, 128),
('Doces da Lu Confeitaria', 'doces-da-lu', 'Bolos artesanais, doces finos e festas. Tradição em sabores.', 8, '79999955678', '557999955678', 'Aracaju', 'assets/img/hero-cake.jpg', 1, 'ativo', 5.0, 214),
('Pintura do César', 'pintura-do-cesar', 'Pintura residencial e comercial com acabamento impecável.', 4, '79999988776', '557999988776', 'Nossa Senhora do Socorro', 'assets/img/service-painter.jpg', 0, 'ativo', 4.8, 76),
('Marta Diarista', 'marta-diarista', 'Diarista experiente, organização e limpeza profunda.', 5, '799999912345', '5579999912345', 'Aracaju', 'assets/img/service-cleaner.jpg', 0, 'ativo', 4.9, 92),
('Studio Tata Cabelos', 'studio-tata', 'Corte feminino, coloração e tratamentos capilares.', 6, '79999987654', '557999987654', 'Aracaju', 'assets/img/service-barber.jpg', 0, 'ativo', 4.7, 145),
('Pedro Encanador', 'pedro-encanador', 'Conserto de torneiras, vazamentos e plumbing.', 2, '79999976543', '557999976543', 'São Cristóvão', 'assets/img/service-plumber.jpg', 0, 'ativo', 4.8, 64),
('Lente Livre Fotografia', 'lente-livre', 'Ensaios fotográficos, eventos e casamentos.', 7, '79999965432', '557999965432', 'Aracaju', 'assets/img/service-photographer.jpg', 0, 'ativo', 4.9, 203),
('Carlos Frete', 'carlos-frete', 'Frete e mudança com segurança.', 10, '79999954321', '557999954321', 'Aracaju', 'assets/img/service-mover.jpg', 0, 'ativo', 4.6, 45),
(' Oficina Mecânica Silva', 'oficina-silva', 'Mecânica geral e auto elétrica.', 9, '79999943210', '557999943210', 'Itabaiana', 'assets/img/service-mechanic.jpg', 0, 'ativo', 4.8, 89),
('Maria Confeitaria', 'maria-confeitaria', 'Bolos caseiros e doces regionais.', 8, '79999932109', '557999932109', 'Lagarto', 'assets/img/service-baker.jpg', 0, 'ativo', 4.9, 67);

-- Depoimentos
INSERT INTO depoimentos (nome, texto, nota) VALUES 
('Ana Paula', 'Excelente serviço! Chegou no horário e fez um trabalho perfeita na minha casa.', 5),
('Roberto Silva', 'Muito profissional, recomiendo para todos.', 5),
('Juliana Costa', 'ótima experiência, recomendo!', 5),
('Pedro Henrique', 'Serviço de qualidade, preço justo.', 4),
('Mariana Santos', 'Excelente eletricista, muito atencioso.', 5);

-- Configurações do site
INSERT INTO config (chave, valor) VALUES 
('site_nome', 'Conectado em Sergipe'),
('site_descricao', 'A maior vitrine de serviços e profissionais de Sergipe'),
('site_email', 'contato@conectadoemsergipe.com.br'),
('site_telefone', '79999999999'),
('site_whatsapp', '5579999999999'),
('site_cidade', 'Aracaju'),
('site_estado', 'Sergipe'),
('facebook', 'https://facebook.com/conectadoemsergipe'),
('instagram', 'https://instagram.com/conectadoemsergipe'),
('youtube', '');