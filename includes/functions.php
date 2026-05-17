<?php

declare(strict_types=1);

if (!function_exists('mb_strlen')) {
    function mb_strlen($string, $encoding = 'UTF-8') {
        return strlen($string);
    }
}

if (!function_exists('mb_substr')) {
    function mb_substr($string, $start, $length = null, $encoding = 'UTF-8') {
        return substr($string, $start, $length);
    }
}

if (!function_exists('mb_strtolower')) {
    function mb_strtolower($string, $encoding = 'UTF-8') {
        return strtolower($string);
    }
}

function e(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

function url(string $path): string
{
    $basePath = defined('BASE_PATH') ? BASE_PATH : '';
    return $basePath . '/' . ltrim($path, '/');
}

function asset_url(?string $path): string
{
    if (!$path) return url('assets/img/placeholder.svg');
    if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
        return $path;
    }
    return url($path);
}

function redirect(string $path): void
{
    header('Location: ' . url($path));
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

function whatsapp_link(string $phone, string $message = ''): string
{
    $url = 'https://wa.me/55' . only_digits($phone);
    if (!empty($message)) {
        $url .= '?text=' . urlencode($message);
    }
    return $url;
}

function fetch_all_categories(PDO $pdo): array
{
    $stmt = $pdo->query('SELECT c.id, c.nome, c.slug, c.icone, COUNT(a.id) AS Total FROM categorias c LEFT JOIN anuncios a ON a.categoria_id = c.id AND a.status = "ativo" GROUP BY c.id ORDER BY c.nome ASC');
    return $stmt->fetchAll();
}

function fetch_all_clients(PDO $pdo): array
{
    $stmt = $pdo->query('SELECT * FROM clientes ORDER BY nome ASC');
    return $stmt->fetchAll();
}

function fetch_featured_ads(PDO $pdo, int $limit = 8): array
{
    $stmt = $pdo->prepare(
        'SELECT a.id, a.titulo, a.slug, a.descricao, a.cidade, a.imagem_principal, a.imagem_banner, a.nota, a.avaliacoes,
                c.nome AS categoria, c.icone AS categoria_icone, a.destaque, a.tipo
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
                c.nome AS categoria, c.icone AS categoria_icone, a.tipo
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
        'SELECT id, titulo, slug, cidade, imagem_principal, tipo
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
        'SELECT a.*, c.nome AS categoria_nome, c.slug AS categoria_slug, 
                cl.nome AS cliente_nome, cl.email AS cliente_email, 
                cl.telefone AS cliente_telefone, cl.whatsapp AS cliente_whatsapp, 
                cl.cidade AS cliente_cidade
         FROM anuncios a
         INNER JOIN categorias c ON c.id = a.categoria_id
         LEFT JOIN clientes cl ON a.cliente_id = cl.id
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

function search_ads(PDO $pdo, string $term = '', string $city = '', string $category = '', string $rating = '', string $region = '', string $estado = '', int $limit = 0, int $offset = 0, bool $countOnly = false)
{
    if ($countOnly) {
        $sql = 'SELECT COUNT(a.id)';
    } else {
        $sql = 'SELECT a.id, a.titulo, a.slug, a.cidade, a.regiao, a.imagem_principal, a.nota, a.avaliacoes, a.destaque, a.tipo, c.nome AS categoria_nome, c.icone AS categoria_icone';
    }
    $sql .= ' FROM anuncios a
            INNER JOIN categorias c ON c.id = a.categoria_id
            WHERE a.status = :status';

    $params = ['status' => 'ativo'];

    if ($term !== '') {
        $sql .= ' AND (a.titulo LIKE :term1 OR a.descricao LIKE :term2 OR c.nome LIKE :term3)';
        $params['term1'] = '%' . $term . '%';
        $params['term2'] = '%' . $term . '%';
        $params['term3'] = '%' . $term . '%';
    }

    if ($city !== '') {
        $sql .= ' AND a.cidade LIKE :city';
        $params['city'] = '%' . $city . '%';
    }

    if ($region !== '') {
        $sql .= ' AND a.regiao = :region';
        $params['region'] = $region;
    }

    if ($estado !== '') {
        $sql .= ' AND a.estado = :estado';
        $params['estado'] = $estado;
    }

    if ($category !== '') {
        $sql .= ' AND c.slug = :category';
        $params['category'] = $category;
    }

    if ($rating !== '') {
        $sql .= ' AND a.nota >= :rating';
        $params['rating'] = (float)$rating;
    }

    if (!$countOnly) {
        $sql .= ' ORDER BY a.destaque DESC, a.created_at DESC';
        if ($limit > 0) {
            $sql .= ' LIMIT ' . (int)$limit . ' OFFSET ' . (int)$offset;
        }
    }

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    
    if ($countOnly) {
        return (int)$stmt->fetchColumn();
    }
    return $stmt->fetchAll();
}

function fetch_unique_states(PDO $pdo): array
{
    $stmt = $pdo->query("SELECT DISTINCT estado FROM anuncios WHERE status = 'ativo' AND estado IS NOT NULL AND estado != '' ORDER BY estado ASC");
    return $stmt->fetchAll(PDO::FETCH_COLUMN);
}

function fetch_unique_cities(PDO $pdo, string $estado = ''): array
{
    if ($estado !== '') {
        $stmt = $pdo->prepare("SELECT DISTINCT cidade FROM anuncios WHERE status = 'ativo' AND estado = ? AND cidade IS NOT NULL AND cidade != '' ORDER BY cidade ASC");
        $stmt->execute([$estado]);
    } else {
        $stmt = $pdo->query("SELECT DISTINCT cidade FROM anuncios WHERE status = 'ativo' AND cidade IS NOT NULL AND cidade != '' ORDER BY cidade ASC");
    }
    return $stmt->fetchAll(PDO::FETCH_COLUMN);
}

function fetch_unique_regions(PDO $pdo, string $estado = ''): array
{
    if ($estado !== '') {
        $stmt = $pdo->prepare("SELECT DISTINCT regiao FROM anuncios WHERE status = 'ativo' AND estado = ? AND regiao IS NOT NULL AND regiao != '' ORDER BY regiao ASC");
        $stmt->execute([$estado]);
    } else {
        $stmt = $pdo->query("SELECT DISTINCT regiao FROM anuncios WHERE status = 'ativo' AND regiao IS NOT NULL AND regiao != '' ORDER BY regiao ASC");
    }
    return $stmt->fetchAll(PDO::FETCH_COLUMN);
}
function get_category_icon(string $name, ?string $storedIcon = null): string
{
    if ($storedIcon) {
        if (str_starts_with($storedIcon, '<svg')) {
            return $storedIcon;
        }
        return "<i data-lucide='{$storedIcon}'></i>";
    }

    $name = mb_strtolower($name, 'UTF-8');
    
    $icons = [
        'eletricista' => 'zap',
        'encanador' => 'droplets',
        'pedreiro' => 'hard-hat',
        'pintor' => 'paint-bucket',
        'diarista' => 'brush',
        'cabeleireiro' => 'scissors',
        'fotografia' => 'camera',
        'confeitaria' => 'coffee',
        'mecanico' => 'wrench',
        'frete' => 'truck',
    ];

    foreach ($icons as $key => $iconName) {
        if (str_contains($name, $key)) {
            return "<i data-lucide='{$iconName}'></i>";
        }
    }

    return '<i data-lucide="package"></i>';
}

/**
 * Helper to upload images
 * @return string|false Path to image relative to root or false on failure
 */
function upload_image(array $file, string $folder = 'ads'): string|false
{
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return false;
    }

    $allowed = ['image/jpeg', 'image/png', 'image/webp'];
    if (!in_array($file['type'], $allowed)) {
        return false;
    }

    $uploadDir = __DIR__ . '/../uploads/' . $folder . '/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $filename = uniqid('img_', true) . '.' . $extension;
    $targetPath = $uploadDir . $filename;

    if (move_uploaded_file($file['tmp_name'], $targetPath)) {
        return 'uploads/' . $folder . '/' . $filename;
    }

    return false;
}

/**
 * Fetch schedules for an ad
 */
function fetch_horarios_by_ad(PDO $pdo, int $adId): array
{
    $stmt = $pdo->prepare("SELECT * FROM horarios WHERE anuncio_id = ? ORDER BY dia_semana ASC");
    $stmt->execute([$adId]);
    return $stmt->fetchAll();
}

/**
 * Busca uma configuração global do banco de dados
 */
function get_setting(PDO $pdo, string $key, $default = ''): string {
    static $settings = [];
    if (empty($settings)) {
        try {
            $stmt = $pdo->query("SELECT chave, valor FROM configuracoes");
            if ($stmt) {
                while ($row = $stmt->fetch()) {
                    $settings[$row['chave']] = $row['valor'];
                }
            }
        } catch (Exception $e) { return $default; }
    }
    return $settings[$key] ?? $default;
}
