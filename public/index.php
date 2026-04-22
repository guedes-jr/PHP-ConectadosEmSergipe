<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/layout.php';
require_once __DIR__ . '/../includes/seo.php';

$categories = fetch_all_categories($pdo);
$featuredAds = fetch_featured_ads($pdo);

render_header(seo_title('Início'), 'Encontre serviços e negócios locais com facilidade.');
?>
<section class="hero">
    <h1>Encontre serviços locais com rapidez</h1>
    <p>Busque por categoria, cidade ou veja anúncios em destaque.</p>
    <form class="search-form" action="/buscar" method="get">
        <input type="text" name="q" placeholder="O que você procura?">
        <button type="submit">Buscar</button>
    </form>
</section>

<section>
    <h2>Categorias</h2>
    <div class="grid categories-grid">
        <?php foreach ($categories as $category): ?>
            <a class="card category-card" href="/categoria/<?php echo e($category['slug']); ?>">
                <strong><?php echo e($category['nome']); ?></strong>
            </a>
        <?php endforeach; ?>
    </div>
</section>

<section>
    <h2>Destaques</h2>
    <div class="grid cards-grid">
        <?php foreach ($featuredAds as $ad): ?>
            <article class="card ad-card">
                <div class="thumb-wrap">
                    <img loading="lazy" src="/<?php echo e($ad['imagem_principal'] ?: 'assets/img/placeholder.svg'); ?>" alt="<?php echo e($ad['titulo']); ?>">
                </div>
                <h3><?php echo e($ad['titulo']); ?></h3>
                <p><?php echo e($ad['cidade']); ?></p>
                <a class="button" href="/anuncio/<?php echo e($ad['slug']); ?>">Ver mais</a>
            </article>
        <?php endforeach; ?>
    </div>
</section>
<?php render_footer(); ?>
