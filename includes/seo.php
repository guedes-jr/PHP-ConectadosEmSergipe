<?php

declare(strict_types=1);

function seo_title(string $pageTitle): string
{
    global $pdo;
    $siteName = get_setting($pdo, 'site_nome', 'Conectado em Sergipe');
    return $pageTitle . ' | ' . $siteName;
}

function seo_description(string $description, int $limit = 155): string
{
    $description = trim(strip_tags($description));
    if (mb_strlen($description, 'UTF-8') <= $limit) {
        return $description;
    }
    return mb_substr($description, 0, $limit, 'UTF-8') . '...';
}

function render_seo_tags(PDO $pdo, string $title, string $description, string $image = '', string $url = ''): void
{
    $siteName = get_setting($pdo, 'site_nome', 'Conectado em Sergipe');
    $fullTitle = seo_title($title);
    $cleanDesc = seo_description($description);
    $fullUrl = $url ?: (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    $fullImage = $image ? (str_starts_with($image, 'http') ? $image : (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]/$image") : '';

    echo "<!-- SEO Tags -->\n";
    echo "<title>{$fullTitle}</title>\n";
    echo "<meta name=\"description\" content=\"{$cleanDesc}\">\n";
    echo "<meta name=\"google-site-verification\" content=\"zUie6fKOmAQLS_cfc9WaVlwMqq11sFy9-ZgbrqPg-2w\" />\n";
    echo "<link rel=\"canonical\" href=\"{$fullUrl}\">\n";
    
    echo "<!-- Open Graph / Facebook -->\n";
    echo "<meta property=\"og:type\" content=\"website\">\n";
    echo "<meta property=\"og:url\" content=\"{$fullUrl}\">\n";
    echo "<meta property=\"og:title\" content=\"{$fullTitle}\">\n";
    echo "<meta property=\"og:description\" content=\"{$cleanDesc}\">\n";
    if ($fullImage) echo "<meta property=\"og:image\" content=\"{$fullImage}\">\n";
    
    echo "<!-- Twitter -->\n";
    echo "<meta property=\"twitter:card\" content=\"summary_large_image\">\n";
    echo "<meta property=\"twitter:url\" content=\"{$fullUrl}\">\n";
    echo "<meta property=\"twitter:title\" content=\"{$fullTitle}\">\n";
    echo "<meta property=\"twitter:description\" content=\"{$cleanDesc}\">\n";
    if ($fullImage) echo "<meta property=\"twitter:image\" content=\"{$fullImage}\">\n";
}
