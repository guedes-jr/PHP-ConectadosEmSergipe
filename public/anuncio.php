<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/layout.php';
require_once __DIR__ . '/../includes/seo.php';

$slug = isset($_GET['slug']) ? trim((string)$_GET['slug']) : '';
$ad = $slug !== '' ? find_ad_by_slug($pdo, $slug) : null;

if (!$ad) {
    http_response_code(404);
    render_header(seo_title('Anúncio não encontrado'), 'Anúncio não encontrado.');
    echo '<h1>Anúncio não encontrado</h1>';
    render_footer();
    exit;
}

$images = fetch_images_by_ad($pdo, (int)$ad['id']);
render_header(seo_title($ad['titulo']), seo_description($ad['descricao']));
?>
<article class="ad-detail">
    <h1><?php echo e($ad['titulo']); ?></h1>
    <p class="muted"><?php echo e($ad['categoria_nome']); ?> · <?php echo e($ad['cidade']); ?></p>
    <div class="gallery">
        <?php if (!empty($images)): ?>
            <?php foreach ($images as $image): ?>
                <img loading="lazy" src="/<?php echo e($image['caminho']); ?>" alt="<?php echo e($ad['titulo']); ?>">
            <?php endforeach; ?>
        <?php else: ?>
            <img loading="lazy" src="/assets/img/placeholder.svg" alt="<?php echo e($ad['titulo']); ?>">
        <?php endif; ?>
    </div>
    <div class="content-box">
        <p><?php echo nl2br(e($ad['descricao'])); ?></p>
        <p><strong>Telefone:</strong> <a href="<?php echo e(whatsapp_link($ad['telefone'])); ?>" target="_blank" rel="noopener noreferrer"><?php echo e($ad['telefone']); ?></a></p>
        <p><strong>Cidade:</strong> <?php echo e($ad['cidade']); ?></p>
        <a class="button button-whatsapp" href="<?php echo e(whatsapp_link($ad['telefone'])); ?>" target="_blank" rel="noopener noreferrer">Falar no WhatsApp</a>
    </div>
</article>
<?php render_footer(); ?>
