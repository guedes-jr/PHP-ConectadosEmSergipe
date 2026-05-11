<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

echo "Iniciando povoamento de anúncios...\n";

function get_4devs_pessoa() {
    $url = 'https://www.4devs.com.br/ferramentas_online.php';
    $data = [
        'acao' => 'gerar_pessoa',
        'pontuacao' => 'N',
        'sexo' => 'I',
        'idade' => rand(20, 60),
        'cep_estado' => 'SE',
        'cep_cidade' => '',
        'txt_qtde' => '1'
    ];

    $options = [
        'http' => [
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'method'  => 'POST',
            'content' => http_build_query($data),
            'timeout' => 10
        ],
    ];
    
    $context  = stream_context_create($options);
    $result = @file_get_contents($url, false, $context);
    
    if ($result === false) return null;
    
    // Debug: echo "API Raw: " . substr($result, 0, 100) . "\n";
    
    $decoded = json_decode($result, true);
    
    if (is_array($decoded) && isset($decoded[0])) {
        return $decoded[0];
    }
    
    if (is_array($decoded) && isset($decoded['nome'])) {
        return $decoded;
    }
    
    return null;
}

$categorias = [12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22];
$cidades_fallback = ['Aracaju', 'Nossa Senhora do Socorro', 'Lagarto', 'Itabaiana', 'São Cristóvão', 'Estância'];

for ($i = 0; $i < 10; $i++) {
    $suffix = bin2hex(random_bytes(2));
    echo "Gerando anúncio " . ($i + 1) . "/10 (Suffix: {$suffix})...\n";
    
    $pessoa = get_4devs_pessoa();
    
    if (!$pessoa || !isset($pessoa['nome'])) {
        echo "Aviso: Falha na API 4devs ou formato inesperado. Usando fallback.\n";
        $pessoa = [
            'nome' => 'Profissional ' . strtoupper($suffix),
            'email' => 'contato.' . $suffix . '@exemplo.com.br',
            'telefone_fixo' => '(79) 3' . rand(100, 999) . '-' . rand(1000, 9999),
            'celular' => '(79) 9' . rand(8000, 9999) . '-' . rand(1000, 9999),
            'cep' => '49000-000',
            'endereco' => 'Rua das Flores',
            'numero' => (string)rand(1, 2000),
            'bairro' => 'Centro',
            'cidade' => $cidades_fallback[array_rand($cidades_fallback)],
            'estado' => 'SE'
        ];
    }

    $nome = $pessoa['nome'];
    $email = $pessoa['email'] ?? ('cli' . $suffix . '@teste.com');
    $tel = $pessoa['telefone_fixo'] ?? $pessoa['celular'] ?? '(79) 3000-0000';
    $cel = $pessoa['celular'] ?? $pessoa['telefone_fixo'] ?? '(79) 9000-0000';
    $cid = $pessoa['cidade'] ?? $cidades_fallback[array_rand($cidades_fallback)];
    $cep = $pessoa['cep'] ?? '49000-000';
    $rua = ($pessoa['endereco'] ?? 'Rua') . ', ' . ($pessoa['numero'] ?? 'S/N');
    $bairro = $pessoa['bairro'] ?? 'Bairro';
    $est = $pessoa['estado'] ?? 'SE';

    try {
        $pdo->beginTransaction();

        // 1. Criar Cliente
        $stmt = $pdo->prepare('INSERT INTO clientes (nome, email, telefone, whatsapp, cidade, cep, rua, bairro, estado) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)');
        $stmt->execute([$nome, $email, $tel, $cel, $cid, $cep, $rua, $bairro, $est]);
        $cliente_id = (int)$pdo->lastInsertId();

        // 2. Criar Anúncio
        $cat_id = $categorias[array_rand($categorias)];
        $cat_stmt = $pdo->prepare("SELECT nome FROM categorias WHERE id = ?");
        $cat_stmt->execute([$cat_id]);
        $cat_name = $cat_stmt->fetchColumn();
        
        $titulo = $cat_name . " Especialista - " . $nome;
        $slug = slugify($titulo) . '-' . $suffix;
        $descricao = "Sou especialista em " . $cat_name . " com atendimento em " . $cid . ". Ofereço serviços profissionais com foco em qualidade, pontualidade e satisfação do cliente. Entre em contato para um orçamento sem compromisso.";
        
        $img_principal = "https://picsum.photos/seed/p" . $suffix . "/800/600";
        $img_banner = "https://picsum.photos/seed/b" . $suffix . "/1200/400";

        $stmt = $pdo->prepare('INSERT INTO anuncios (titulo, slug, descricao, categoria_id, telefone, whatsapp, email, endereco, cidade, imagem_principal, imagem_banner, cliente_id, destaque, status, nota, avaliacoes) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)');
        $stmt->execute([
            $titulo,
            $slug,
            $descricao,
            $cat_id,
            $tel,
            $cel,
            $email,
            $rua,
            $cid,
            $img_principal,
            $img_banner,
            $cliente_id,
            rand(0, 1),
            'ativo',
            (rand(42, 50) / 10),
            rand(10, 80)
        ]);
        $anuncio_id = (int)$pdo->lastInsertId();

        // 3. Criar Galeria (10 imagens)
        for ($j = 0; $j < 10; $j++) {
            $caminho = "https://picsum.photos/seed/g" . $suffix . $j . "/800/600";
            $stmt = $pdo->prepare('INSERT INTO imagens (anuncio_id, caminho, ordem) VALUES (?, ?, ?)');
            $stmt->execute([$anuncio_id, $caminho, $j]);
        }

        $pdo->commit();
        echo "Anúncio '{$titulo}' criado com sucesso!\n";
    } catch (Exception $e) {
        $pdo->rollBack();
        echo "Erro ao criar anúncio {$i}: " . $e->getMessage() . "\n";
    }

    usleep(300000); 
}

echo "Processo concluído!\n";
