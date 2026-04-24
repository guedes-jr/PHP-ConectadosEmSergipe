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

function search_ads(PDO $pdo, string $term = '', string $city = '', string $category = ''): array
{
    $sql = 'SELECT a.id, a.titulo, a.slug, a.cidade, a.imagem_principal, c.nome AS categoria_nome
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

    $sql .= ' ORDER BY a.destaque DESC, a.created_at DESC';

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}
