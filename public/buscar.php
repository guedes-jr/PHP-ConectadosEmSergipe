<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/layout.php';
require_once __DIR__ . '/../includes/seo.php';
require_once __DIR__ . '/../includes/sergipe_data.php';

$q = isset($_GET['q']) ? trim((string)$_GET['q']) : '';
$cidade = isset($_GET['cidade']) ? trim((string)$_GET['cidade']) : '';
$regiao = isset($_GET['regiao']) ? trim((string)$_GET['regiao']) : '';
$categoria = isset($_GET['categoria']) ? trim((string)$_GET['categoria']) : '';
$rating = isset($_GET['avaliacao']) ? trim((string)$_GET['avaliacao']) : '';

$categories = fetch_all_categories($pdo);
$cities = fetch_unique_cities($pdo);
$regions = fetch_unique_regions($pdo);
$cityMapping = get_city_region_mapping();
$results = search_ads($pdo, $q, $cidade, $categoria, $rating, $regiao);

render_header($pdo, seo_title('Buscar'), 'Busque anúncios por título, categoria ou cidade.');
?>

<section class="search-hero section">
    <div class="container search-hero-container">
        <span class="search-subtitle">ENCONTRE POR AQUI</span>
        <h1 class="search-title">Busque o serviço <span class="text-primary">ideal.</span></h1>
        
        <form class="search-main-form" method="get" action="/buscar">
            <div class="search-input-wrapper">
                <i data-lucide="search" style="margin-right: 1rem; color: var(--muted-foreground);"></i>
                <input type="text" name="q" value="<?php echo e($q); ?>" placeholder="Buscar por nome ou serviço...">
                <?php if ($cidade): ?><input type="hidden" name="cidade" value="<?php echo e($cidade); ?>"><?php endif; ?>
                <?php if ($regiao): ?><input type="hidden" name="regiao" value="<?php echo e($regiao); ?>"><?php endif; ?>
                <?php if ($categoria): ?><input type="hidden" name="categoria" value="<?php echo e($categoria); ?>"><?php endif; ?>
                <?php if ($rating): ?><input type="hidden" name="avaliacao" value="<?php echo e($rating); ?>"><?php endif; ?>
                <button type="submit" class="btn btn-primary">Buscar</button>
            </div>
        </form>
    </div>
</section>

<div class="search-layout container section">
    <aside class="search-sidebar">
        <div class="sidebar-block animate-fade-in">
            <form id="filters-form" action="/buscar" method="get">
                <input type="hidden" name="q" value="<?php echo e($q); ?>">
                <input type="hidden" name="categoria" value="<?php echo e($categoria); ?>">
                
                <h3>REGIÃO</h3>
                <div class="select-wrapper" style="margin-bottom: 1.5rem;">
                    <select name="regiao" id="searchRegiao" class="quick-select" style="width: 100%;" onchange="document.getElementById('filters-form').submit()">
                        <option value="">Todas as regiões</option>
                        <?php foreach ($regions as $r): ?>
                            <option value="<?php echo e($r); ?>" <?php echo $regiao === $r ? 'selected' : ''; ?>><?php echo e($r); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <h3>CIDADE</h3>
                <div class="select-wrapper" style="margin-bottom: 1.5rem;">
                    <select name="cidade" id="searchCidade" class="quick-select" style="width: 100%;" onchange="document.getElementById('filters-form').submit()">
                        <option value="">Todas as cidades</option>
                        <?php foreach ($cities as $c): ?>
                            <option value="<?php echo e($c); ?>" <?php echo $cidade === $c ? 'selected' : ''; ?>><?php echo e($c); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <hr style="border: none; border-top: 1px solid var(--border); margin: 1.5rem 0;">

                <h3>AVALIAÇÃO</h3>
                <div style="display: flex; gap: 0.5rem; flex-wrap: wrap; margin-bottom: 1.5rem;">
                    <?php foreach(['5', '4', '3'] as $star): ?>
                    <label class="pill" style="cursor: pointer; padding: 0.375rem 0.875rem; <?php echo $rating === $star ? 'background: var(--primary); color: white; border-color: var(--primary);' : ''; ?>">
                        <input type="radio" name="avaliacao" value="<?php echo $star; ?>" <?php echo $rating === $star ? 'checked' : ''; ?> onchange="document.getElementById('filters-form').submit()" style="display:none;">
                        <i data-lucide="star" style="width:12px; height:12px; fill:<?php echo $rating === $star ? 'white' : 'currentColor'; ?>; display:inline-block; margin-right:2px; vertical-align:middle;"></i>
                        <span style="vertical-align:middle;"><?php echo $star; ?>+</span>
                    </label>
                    <?php endforeach; ?>
                </div>
            </form>

            <hr style="border: none; border-top: 1px solid var(--border); margin: 1.5rem 0;">

            <h3>CATEGORIA</h3>
            <div class="category-list">
                <a href="/buscar?q=<?php echo urlencode($q); ?><?php echo $cidade ? '&cidade='.urlencode($cidade) : ''; ?><?php echo $regiao ? '&regiao='.urlencode($regiao) : ''; ?><?php echo $rating ? '&avaliacao='.urlencode($rating) : ''; ?>" class="cat-link <?php echo empty($categoria) ? 'active' : ''; ?>">
                    <span>Todas as categorias</span>
                </a>
                <?php foreach ($categories as $item): ?>
                <a href="/buscar?q=<?php echo urlencode($q); ?><?php echo $cidade ? '&cidade='.urlencode($cidade) : ''; ?><?php echo $regiao ? '&regiao='.urlencode($regiao) : ''; ?><?php echo $rating ? '&avaliacao='.urlencode($rating) : ''; ?>&categoria=<?php echo e($item['slug']); ?>" class="cat-link <?php echo $categoria === $item['slug'] ? 'active' : ''; ?>">
                    <div style="display:flex; align-items:center; gap:0.75rem;">
                        <?php echo get_category_icon($item['nome'], $item['icone']); ?>
                        <span><?php echo e($item['nome']); ?></span>
                    </div>
                    <small><?php echo $item['Total'] ?? ''; ?></small>
                </a>
                <?php endforeach; ?>
            </div>
        </div>
    </aside>

    <main class="search-results">
        <div class="results-header animate-fade-in">
            <p><?php echo count($results); ?> profissionais encontrados</p>
        </div>
        
        <?php if (count($results) > 0): ?>
            <div class="cards-grid">
                <?php foreach ($results as $ad): ?>
                    <div class="service-card animate-fade-in">
                        <button class="btn-favorite" data-id="<?php echo $ad['id']; ?>" title="Favoritar">
                            <i data-lucide="heart"></i>
                        </button>
                        <a href="/anuncio/<?php echo e($ad['slug']); ?>" class="card-cover">
                            <img src="<?php echo asset_url($ad['imagem_principal']); ?>" alt="<?php echo e($ad['titulo']); ?>">
                            <div class="card-badges">
                                <?php if (isset($ad['destaque']) && $ad['destaque']): ?>
                                    <span class="badge-featured">★ EM DESTAQUE</span>
                                <?php endif; ?>
                            </div>
                        </a>
                        <div class="card-body">
                            <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom: 0.5rem;">
                                <div class="card-cat-wrapper" style="display:flex; align-items:center; gap:0.5rem; color:var(--primary); font-size:0.75rem; font-weight:700;">
                                    <?php echo get_category_icon($ad['categoria_nome'], $ad['categoria_icone']); ?>
                                    <span class="card-cat" style="color:var(--muted-foreground); text-transform:uppercase;"><?php echo e($ad['categoria_nome']); ?></span>
                                </div>
                                <div class="rating">
                                    <i data-lucide="star" style="width:14px; height:14px; fill:currentColor;"></i>
                                    <span><?php echo $ad['nota'] ?? '5.0'; ?></span>
                                </div>
                            </div>
                            <a href="/anuncio/<?php echo e($ad['slug']); ?>"><h3><?php echo e($ad['titulo']); ?></h3></a>
                            <div class="card-footer">
                                <div class="location">
                                    <i data-lucide="map-pin" style="width:14px; height:14px;"></i>
                                    <span><?php echo e($ad['cidade']); ?></span>
                                </div>
                                <a href="<?php echo whatsapp_link($ad['cliente_telefone'] ?? $ad['telefone'] ?? '0000000000'); ?>" target="_blank" class="whatsapp-float-btn">
                                    <i data-lucide="message-circle" style="width:14px; height:14px;"></i> Whats
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="no-results animate-fade-in" style="padding: 4rem; text-align: center; background: var(--card); border-radius: 2rem; border: 1px solid var(--border);">
                <i data-lucide="search-x" style="width:48px; height:48px; color:var(--muted-foreground); margin-bottom:1rem;"></i>
                <h3>Nenhum resultado encontrado</h3>
                <p style="color: var(--muted-foreground); margin-top: 0.5rem;">Tente ajustar seus filtros ou termos de busca.</p>
            </div>
        <?php endif; ?>
    </main>
</div>

<script>
    const cityMapping = <?php echo json_encode($cityMapping); ?>;
    const allCities = <?php echo json_encode($cities); ?>;

    document.addEventListener('DOMContentLoaded', function() {
        const searchRegiao = document.getElementById('searchRegiao');
        const searchCidade = document.getElementById('searchCidade');

        function filterCities(region) {
            const currentCity = searchCidade.value;
            searchCidade.innerHTML = '<option value="">Todas as cidades</option>';
            const filtered = region 
                ? allCities.filter(city => cityMapping[city] === region)
                : allCities;
            
            filtered.forEach(city => {
                const opt = document.createElement('option');
                opt.value = city;
                opt.textContent = city;
                if (city === currentCity) opt.selected = true;
                searchCidade.appendChild(opt);
            });
        }

        if (searchRegiao) {
            searchRegiao.addEventListener('change', (e) => filterCities(e.target.value));
            if (searchRegiao.value) filterCities(searchRegiao.value);
        }

        // Favorites
        const favorites = JSON.parse(localStorage.getItem('sergipe_favs') || '[]');
        document.querySelectorAll('.btn-favorite').forEach(btn => {
            if (favorites.includes(btn.dataset.id)) btn.classList.add('active');
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                const id = btn.dataset.id;
                const index = favorites.indexOf(id);
                if (index > -1) { favorites.splice(index, 1); btn.classList.remove('active'); }
                else { favorites.push(id); btn.classList.add('active'); }
                localStorage.setItem('sergipe_favs', JSON.stringify(favorites));
            });
        });
    });
</script>

<?php render_footer(); ?>
