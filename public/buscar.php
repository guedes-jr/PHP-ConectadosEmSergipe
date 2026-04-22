<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/layout.php';
require_once __DIR__ . '/../includes/seo.php';

$q = isset($_GET['q']) ? trim((string)$_GET['q']) : '';
$cidade = isset($_GET['cidade']) ? trim((string)$_GET['cidade']) : '';
$categoria = isset($_GET['categoria']) ? trim((string)$_GET['categoria']) : '';

$categories = fetch_all_categories($pdo);
$results = search_ads($pdo, $q, $cidade, $categoria);

render_header(seo_title('Buscar'), 'Busque anúncios por título, categoria ou cidade.');
?>
<h1>Buscar anúncios</h1>
<form class="search-form filters" method="get" action="/buscar">
    <input type="text" name="q" value="<?php echo e($q); ?>" placeholder="Título ou palavra-chave">
    <input type="text" name="cidade" value="<?php echo e($cidade); ?>" placeholder="Cidade">
    <select name="categoria">
        <option value="">Todas as categorias</option>
        <?php foreach ($categories as $item): ?>
            <option value="<?php echo e($item['slug']); ?>" <?php echo $categoria === $item['slug'] ? 'selected' : ''; ?>><?php echo e($item['nome']); ?></option>
        <?php endforeach; ?>
    </select>
    <button type="submit">Buscar</button>
</form>

<div class="grid cards-grid">
    <?php foreach ($results as $ad): ?>
        <article class="card ad-card">
            <div class="thumb-wrap">
                <img loading="lazy" src="/<?php echo e($ad['imagem_principal'] ?: 'assets/img/placeholder.svg'); ?>" alt="<?php echo e($ad['titulo']); ?>">
            </div>
            <h2><?php echo e($ad['titulo']); ?></h2>
            <p><?php echo e($ad['categoria_nome']); ?> · <?php echo e($ad['cidade']); ?></p>
            <a class="button" href="/anuncio/<?php echo e($ad['slug']); ?>">Ver mais</a>
        </article>
    <?php endforeach; ?>
</div>
<?php render_footer(); ?>
