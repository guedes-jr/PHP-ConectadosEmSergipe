<?php
declare(strict_types=1);

require_once __DIR__ . '/../includes/db.php';

$host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'conectadoemsergipe.com';
$baseUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $host;

echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

// Home
echo "  <url>\n";
echo "    <loc>{$baseUrl}/</loc>\n";
echo "    <changefreq>daily</changefreq>\n";
echo "    <priority>1.0</priority>\n";
echo "  </url>\n";

// Buscar
echo "  <url>\n";
echo "    <loc>{$baseUrl}/buscar</loc>\n";
echo "    <changefreq>always</changefreq>\n";
echo "    <priority>0.8</priority>\n";
echo "  </url>\n";

// Static pages
$staticPages = ['/termos', '/privacidade'];
foreach ($staticPages as $page) {
    echo "  <url>\n";
    echo "    <loc>{$baseUrl}{$page}</loc>\n";
    echo "    <changefreq>monthly</changefreq>\n";
    echo "    <priority>0.3</priority>\n";
    echo "  </url>\n";
}

// Categories
try {
    $stmt = $pdo->query("SELECT slug, created_at FROM categorias");
    while ($cat = $stmt->fetch()) {
        $date = !empty($cat['created_at']) ? date('c', strtotime($cat['created_at'])) : date('c');
        echo "  <url>\n";
        echo "    <loc>{$baseUrl}/categoria/" . htmlspecialchars($cat['slug']) . "</loc>\n";
        echo "    <lastmod>{$date}</lastmod>\n";
        echo "    <changefreq>weekly</changefreq>\n";
        echo "    <priority>0.7</priority>\n";
        echo "  </url>\n";
    }
} catch (PDOException $e) {
    // Ignore error in sitemap generation to not break the whole file
}

// Ads
try {
    $stmt = $pdo->query("SELECT slug, updated_at, created_at FROM anuncios WHERE status = 'ativo'");
    while ($ad = $stmt->fetch()) {
        $dateField = !empty($ad['updated_at']) ? $ad['updated_at'] : $ad['created_at'];
        $date = !empty($dateField) ? date('c', strtotime($dateField)) : date('c');
        echo "  <url>\n";
        echo "    <loc>{$baseUrl}/anuncio/" . htmlspecialchars($ad['slug']) . "</loc>\n";
        echo "    <lastmod>{$date}</lastmod>\n";
        echo "    <changefreq>weekly</changefreq>\n";
        echo "    <priority>0.9</priority>\n";
        echo "  </url>\n";
    }
} catch (PDOException $e) {
    // Ignore error
}

echo "</urlset>\n";
