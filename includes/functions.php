<?php

declare(strict_types=1);

function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function redirect(string $path): void
{
    header('Location: ' . $path);
    exit;
}

function slugify(string $text): string
{
    $text = trim(mb_strtolower($text, 'UTF-8'));
    $text = preg_replace('/[áàãâä]/u', 'a', $text);
    $text = preg_replace('/[éèêë]/u', 'e', $text);
    $text = preg_replace('/[íìîï]/u', 'i', $text);
    $text = preg_replace('/[óòõôö]/u', 'o', $text);
    $text = preg_replace('/[úùûü]/u', 'u', $text);
    $text = preg_replace('/[ç]/u', 'c', $text);
    $text = preg_replace('/[^a-z0-9]+/u', '-', $text);
    return trim((string)$text, '-');
}

function only_digits(string $value): string
{
    return preg_replace('/\D+/', '', $value) ?? '';
}

function whatsapp_link(string $phone): string
{
    return 'https://wa.me/55' . only_digits($phone);
}

function fetch_all_categories(PDO $pdo): array
{
    $stmt = $pdo->query('SELECT c.id, c.nome, c.slug, COUNT(a.id) AS Total FROM categorias c LEFT JOIN anuncios a ON a.categoria_id = c.id AND a.status = "ativo" GROUP BY c.id ORDER BY c.nome ASC');
    return $stmt->fetchAll();
}

function fetch_featured_ads(PDO $pdo, int $limit = 8): array
{
    $stmt = $pdo->prepare(
        'SELECT a.id, a.titulo, a.slug, a.descricao, a.cidade, a.imagem_principal, a.nota, a.avaliacoes,
                c.nome AS categoria, a.destaque
         FROM anuncios a
         INNER JOIN categorias c ON c.id = a.categoria_id
         WHERE a.status = "ativo" AND a.destaque = 1
         ORDER BY a.created_at DESC
         LIMIT ?'
    );
    $stmt->execute([$limit]);
    return $stmt->fetchAll();
}

function fetch_recent_ads(PDO $pdo, int $limit = 6): array
{
    $stmt = $pdo->prepare(
        'SELECT a.id, a.titulo, a.slug, a.descricao, a.cidade, a.imagem_principal, a.nota, a.avaliacoes,
                c.nome AS categoria
         FROM anuncios a
         INNER JOIN categorias c ON c.id = a.categoria_id
         WHERE a.status = "ativo"
         ORDER BY a.created_at DESC
         LIMIT ?'
    );
    $stmt->execute([$limit]);
    return $stmt->fetchAll();
}

function find_category_by_slug(PDO $pdo, string $slug): ?array
{
    $stmt = $pdo->prepare('SELECT * FROM categorias WHERE slug = :slug LIMIT 1');
    $stmt->execute(['slug' => $slug]);
    $category = $stmt->fetch();
    return $category ?: null;
}

function fetch_ads_by_category(PDO $pdo, int $categoryId): array
{
    $stmt = $pdo->prepare(
        'SELECT id, titulo, slug, cidade, imagem_principal
         FROM anuncios
         WHERE categoria_id = :categoria_id AND status = :status
         ORDER BY destaque DESC, created_at DESC'
    );
    $stmt->execute([
        'categoria_id' => $categoryId,
        'status' => 'ativo',
    ]);
    return $stmt->fetchAll();
}

function find_ad_by_slug(PDO $pdo, string $slug): ?array
{
    $stmt = $pdo->prepare(
        'SELECT a.*, c.nome AS categoria_nome, c.slug AS categoria_slug
         FROM anuncios a
         INNER JOIN categorias c ON c.id = a.categoria_id
         WHERE a.slug = :slug AND a.status = :status
         LIMIT 1'
    );
    $stmt->execute([
        'slug' => $slug,
        'status' => 'ativo',
    ]);
    $ad = $stmt->fetch();
    return $ad ?: null;
}

function fetch_images_by_ad(PDO $pdo, int $adId): array
{
    $stmt = $pdo->prepare('SELECT id, caminho, ordem FROM imagens WHERE anuncio_id = :anuncio_id ORDER BY ordem ASC, id ASC');
    $stmt->execute(['anuncio_id' => $adId]);
    return $stmt->fetchAll();
}

function search_ads(PDO $pdo, string $term = '', string $city = '', string $category = '', string $rating = ''): array
{
    $sql = 'SELECT a.id, a.titulo, a.slug, a.cidade, a.imagem_principal, a.nota, a.avaliacoes, a.destaque, c.nome AS categoria_nome
            FROM anuncios a
            INNER JOIN categorias c ON c.id = a.categoria_id
            WHERE a.status = :status';

    $params = ['status' => 'ativo'];

    if ($term !== '') {
        $sql .= ' AND (a.titulo LIKE :term OR a.descricao LIKE :term)';
        $params['term'] = '%' . $term . '%';
    }

    if ($city !== '') {
        $sql .= ' AND a.cidade LIKE :city';
        $params['city'] = '%' . $city . '%';
    }

    if ($category !== '') {
        $sql .= ' AND c.slug = :category';
        $params['category'] = $category;
    }

    if ($rating !== '') {
        $sql .= ' AND a.nota >= :rating';
        $params['rating'] = (float)$rating;
    }

    $sql .= ' ORDER BY a.destaque DESC, a.created_at DESC';

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

function fetch_unique_cities(PDO $pdo): array
{
    $stmt = $pdo->query("SELECT DISTINCT cidade FROM anuncios WHERE status = 'ativo' AND cidade IS NOT NULL AND cidade != '' ORDER BY cidade ASC");
    return $stmt->fetchAll(PDO::FETCH_COLUMN);
}
function get_category_icon(string $name): string
{
    $name = mb_strtolower($name, 'UTF-8');
    
    $icons = [
        'eletricista' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/></svg>',
        'encanador' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.77 3.77z"/></svg>',
        'pedreiro' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>',
        'pintor' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 8a2 2 0 1 0-4 0 2 2 0 0 0 4 0z"/><path d="M12 20v2"/><path d="M14 17h-4"/><path d="M21 11V5a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v6"/><path d="M12 12A10 10 0 0 0 2 22"/><path d="M12 12a10 10 0 0 1 10 10"/></svg>',
        'diarista' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 3v3"/><path d="M18.66 4.34l-2.12 2.12"/><path d="M21 11h-3"/><path d="M18.66 17.66l-2.12-2.12"/><path d="M12 21v-3"/><path d="M5.34 17.66l2.12-2.12"/><path d="M3 11h3"/><path d="M5.34 4.34l2.12 2.12"/><path d="M11.4 11.4a1.2 1.2 0 1 0 1.2 1.2 1.2 1.2 0 0 0-1.2-1.2z"/></svg>',
        'cabeleireiro' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="6" cy="6" r="3"/><circle cx="6" cy="18" r="3"/><line x1="20" y1="4" x2="8.12" y2="15.88"/><line x1="14.47" y1="12" x2="20" y2="17.53"/><line x1="4.47" y1="4.47" x2="9.53" y2="9.53"/></svg>',
        'fotografia' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"/><circle cx="12" cy="13" r="4"/></svg>',
        'confeitaria' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-8a2 2 0 0 0-2-2H6a2 2 0 0 0-2 2v8"/><path d="M4 11a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2"/><path d="M9 22v-4h6v4"/><path d="M12 2v3"/><path d="M12 7h.01"/></svg>',
        'mecanico' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.77 3.77z"/><circle cx="12" cy="12" r="3"/></svg>',
        'frete' => '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>',
    ];

    foreach ($icons as $key => $svg) {
        if (str_contains($name, $key)) {
            return $svg;
        }
    }

    return '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>';
}
