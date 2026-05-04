<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/layout.php';
require_once __DIR__ . '/../includes/seo.php';

$slug = isset($_GET['slug']) ? trim((string)$_GET['slug']) : '';
$category = $slug !== '' ? find_category_by_slug($pdo, $slug) : null;

if (!$category) {
    http_response_code(404);
    render_header(seo_title('Categoria não encontrada'), 'Categoria não encontrada.');
    echo '<h1>Categoria não encontrada</h1>';
    render_footer();
    exit;
}

$ads = fetch_ads_by_category($pdo, (int)$category['id']);
render_header(seo_title($category['nome']), seo_description($category['descricao'] ?? $category['nome']));
?>
<h1><?php echo e($category['nome']); ?></h1>
<p style="color:var(--foreground);"><?php echo e($category['descricao'] ?? ''); ?></p>
<div class="grid cards-grid">
    <?php foreach ($ads as $ad): ?>
        <article class="card ad-card">
            <div class="thumb-wrap">
                <img loading="lazy" src="/<?php echo e($ad['imagem_principal'] ?: 'assets/img/placeholder.svg'); ?>" alt="<?php echo e($ad['titulo']); ?>">
            </div>
            <h2><?php echo e($ad['titulo']); ?></h2>
            <p><?php echo e($ad['cidade']); ?></p>
            <a class="button" href="/anuncio/<?php echo e($ad['slug']); ?>">Ver mais</a>
        </article>
    <?php endforeach; ?>
</div>
<?php render_footer(); ?>
