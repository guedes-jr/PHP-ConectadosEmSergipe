<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/layout.php';
require_once __DIR__ . '/../includes/seo.php';

$q = isset($_GET['q']) ? trim((string)$_GET['q']) : '';
$cidade = isset($_GET['cidade']) ? trim((string)$_GET['cidade']) : '';
$categoria = isset($_GET['categoria']) ? trim((string)$_GET['categoria']) : '';
$rating = isset($_GET['avaliacao']) ? trim((string)$_GET['avaliacao']) : '';

$categories = fetch_all_categories($pdo);
$cities = fetch_unique_cities($pdo);
$results = search_ads($pdo, $q, $cidade, $categoria, $rating);

render_header(seo_title('Buscar'), 'Busque anúncios por título, categoria ou cidade.');
?>

<section class="search-hero section">
    <div class="container search-hero-container">
        <span class="search-subtitle">ENCONTRE POR AQUI</span>
        <h1 class="search-title">Busque o serviço <span class="text-primary">ideal.</span></h1>
        
        <form class="search-main-form" method="get" action="/buscar">
            <div class="search-input-wrapper">
                <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                <input type="text" name="q" value="<?php echo e($q); ?>" placeholder="Buscar por nome ou serviço...">
                <?php if ($cidade): ?>
                    <input type="hidden" name="cidade" value="<?php echo e($cidade); ?>">
                <?php endif; ?>
                <?php if ($categoria): ?>
                    <input type="hidden" name="categoria" value="<?php echo e($categoria); ?>">
                <?php endif; ?>
                <?php if ($rating): ?>
                    <input type="hidden" name="avaliacao" value="<?php echo e($rating); ?>">
                <?php endif; ?>
                <button type="submit" class="btn btn-primary">Buscar</button>
            </div>
        </form>
    </div>
</section>

<div class="search-layout container section">
    <aside class="search-sidebar">
        <div class="sidebar-block">
            <form id="filters-form" action="/buscar" method="get">
                <input type="hidden" name="q" value="<?php echo e($q); ?>">
                <input type="hidden" name="categoria" value="<?php echo e($categoria); ?>">
                
                <h3 style="margin-top: 0;">CIDADE</h3>
                <div class="select-wrapper" style="margin-bottom: 1.5rem;">
                    <select name="cidade" class="quick-select" style="width: 100%;" onchange="document.getElementById('filters-form').submit()">
                        <option value="">Todas as cidades</option>
                        <?php foreach ($cities as $c): ?>
                            <option value="<?php echo e($c); ?>" <?php echo $cidade === $c ? 'selected' : ''; ?>><?php echo e($c); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <hr style="border: none; border-top: 1px solid var(--border); margin: 1.5rem 0;">

                <h3 style="margin-top: 0;">AVALIAÇÃO</h3>
                <div style="display: flex; gap: 0.5rem; flex-wrap: wrap; margin-bottom: 1.5rem;">
                    <label class="pill" style="cursor: pointer; padding: 0.375rem 0.875rem; <?php echo $rating === '5' ? 'background: var(--primary); color: white; border-color: var(--primary);' : ''; ?>">
                        <input type="radio" name="avaliacao" value="5" <?php echo $rating === '5' ? 'checked' : ''; ?> onchange="document.getElementById('filters-form').submit()" style="display:none;">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor" style="display:inline-block; margin-right:2px; vertical-align:middle;"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                        <span style="vertical-align:middle;">5+</span>
                    </label>
                    <label class="pill" style="cursor: pointer; padding: 0.375rem 0.875rem; <?php echo $rating === '4' ? 'background: var(--primary); color: white; border-color: var(--primary);' : ''; ?>">
                        <input type="radio" name="avaliacao" value="4" <?php echo $rating === '4' ? 'checked' : ''; ?> onchange="document.getElementById('filters-form').submit()" style="display:none;">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor" style="display:inline-block; margin-right:2px; vertical-align:middle;"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                        <span style="vertical-align:middle;">4+</span>
                    </label>
                    <label class="pill" style="cursor: pointer; padding: 0.375rem 0.875rem; <?php echo $rating === '3' ? 'background: var(--primary); color: white; border-color: var(--primary);' : ''; ?>">
                        <input type="radio" name="avaliacao" value="3" <?php echo $rating === '3' ? 'checked' : ''; ?> onchange="document.getElementById('filters-form').submit()" style="display:none;">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor" style="display:inline-block; margin-right:2px; vertical-align:middle;"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                        <span style="vertical-align:middle;">3+</span>
                    </label>
                </div>
            </form>

            <hr style="border: none; border-top: 1px solid var(--border); margin: 1.5rem 0;">

            <h3 style="margin-top: 0;">CATEGORIA</h3>
            <div class="category-list">
                <a href="/buscar?q=<?php echo urlencode($q); ?><?php echo $cidade ? '&cidade='.urlencode($cidade) : ''; ?><?php echo $rating ? '&avaliacao='.urlencode($rating) : ''; ?>" class="cat-link <?php echo empty($categoria) ? 'active' : ''; ?>">
                    <span>Todas as categorias</span>
                </a>
                <?php foreach ($categories as $item): ?>
                <a href="/buscar?q=<?php echo urlencode($q); ?><?php echo $cidade ? '&cidade='.urlencode($cidade) : ''; ?><?php echo $rating ? '&avaliacao='.urlencode($rating) : ''; ?>&categoria=<?php echo e($item['slug']); ?>" class="cat-link <?php echo $categoria === $item['slug'] ? 'active' : ''; ?>">
                    <span><?php echo e($item['nome']); ?></span>
                    <small><?php echo $item['Total'] ?? ''; ?></small>
                </a>
                <?php endforeach; ?>
            </div>
        </div>
    </aside>

    <main class="search-results">
        <div class="results-header">
            <p><?php echo count($results); ?> profissionais encontrados</p>
        </div>
        
        <?php if (count($results) > 0): ?>
            <div class="cards-grid">
                <?php foreach ($results as $ad): ?>
                    <a href="/anuncio/<?php echo e($ad['slug']); ?>" class="service-card">
                        <div class="card-cover">
                            <img src="/<?php echo e($ad['imagem_principal'] ?: 'assets/img/placeholder.svg'); ?>" alt="<?php echo e($ad['titulo']); ?>">
                            <div class="card-badges">
                                <?php if (isset($ad['destaque']) && $ad['destaque']): ?>
                                    <span class="badge-featured">★ EM DESTAQUE</span>
                                <?php endif; ?>
                                <span class="badge-store">LOJA</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <span class="card-cat"><?php echo e($ad['categoria_nome'] ?? $ad['categoria'] ?? ''); ?></span>
                            <h3><?php echo e($ad['titulo']); ?></h3>
                            <div class="card-footer">
                                <div class="location">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                                    <span><?php echo e($ad['cidade']); ?></span>
                                </div>
                                <div class="rating">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor" stroke="none"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                                    <span><?php echo isset($ad['nota']) ? $ad['nota'] : '5.0'; ?> · <?php echo $ad['avaliacoes'] ?? '120'; ?></span>
                                </div>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="no-results" style="padding: 3rem; text-align: center; background: var(--card); border-radius: 1rem; border: 1px solid var(--border);">
                <h3>Nenhum resultado</h3>
                <p style="color: var(--muted-foreground); margin-top: 0.5rem;">Não encontramos prestadores de serviço com os termos buscados.</p>
            </div>
        <?php endif; ?>
    </main>
</div>

<?php render_footer(); ?>
