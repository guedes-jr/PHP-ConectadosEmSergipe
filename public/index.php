<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/layout.php';
require_once __DIR__ . '/../includes/seo.php';
require_once __DIR__ . '/../includes/sergipe_data.php';

$categories = fetch_all_categories($pdo);
$featuredAds = fetch_featured_ads($pdo);
$recentAds = fetch_recent_ads($pdo, 8);
$regions = get_sergipe_regions();
$cityMapping = get_city_region_mapping();

render_header($pdo, 'Início', 'Encontre serviços e negócios locais com facilidade em Sergipe.');
?>

<section class="hero">
    <div class="hero-slider">
        <div class="hero-slide active">
            <img src="/assets/img/hero-orla.png" alt="Orla de Aracaju">
            <div class="hero-overlay"></div>
        </div>
        <!-- Outros slides podem ser adicionados aqui -->
    </div>
    <div class="container">
        <div class="hero-content animate-fade-in">
            <span class="search-subtitle" style="color: white; opacity: 0.8;">CONECTANDO SERGIPE</span>
            <h1><?php echo e(get_setting($pdo, 'hero_titulo', 'Encontre os melhores profissionais de Sergipe em um só lugar.')); ?></h1>
            <p class="lead"><?php echo e(get_setting($pdo, 'hero_subtitulo', 'Conectamos você aos serviços que você precisa, com a confiança que você merece.')); ?></p>
            
            <div class="quick-card animate-fade-in" style="margin-top: 2rem; background: rgba(255,255,255,0.1); backdrop-filter: blur(12px); border: 1px solid rgba(255,255,255,0.2); color: white;">
                <form action="/buscar" method="get" class="home-filters-wrapper">
                    <div class="form-group region-select-home">
                        <select name="regiao" id="homeRegiao" class="quick-select" style="width: 100%; height: 3.5rem; background: rgba(255,255,255,0.9); color: #1e293b;">
                            <option value="">Todas as regiões</option>
                            <?php foreach($regions as $r): ?>
                                <option value="<?php echo e($r); ?>"><?php echo e($r); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group region-select-home">
                        <select name="cidade" id="homeCidade" class="quick-select" style="width: 100%; height: 3.5rem; background: rgba(255,255,255,0.9); color: #1e293b;">
                            <option value="">Todas as cidades</option>
                            <!-- Cidades serão filtradas via JS se região for escolhida -->
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary" style="height: 3.5rem; padding: 0 2rem;">
                        <i data-lucide="search"></i> Buscar Agora
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>

<section class="section categories-area">
    <div class="container">
        <div class="section-head">
            <div>
                <span class="section-title">Categorias</span>
                <h2>Navegue por especialidade</h2>
            </div>
            <a href="/buscar" class="view-all">Ver todas</a>
        </div>
        <div class="category-grid">
            <?php foreach ($categories as $category): ?>
                <a href="/buscar?categoria=<?php echo e($category['slug']); ?>" class="category-card">
                    <div class="cat-icon-wrapper">
                        <?php echo get_category_icon($category['nome'], $category['icone']); ?>
                    </div>
                    <strong><?php echo e($category['nome']); ?></strong>
                    <small><?php echo $category['Total']; ?> profissionais</small>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="section" style="background: var(--muted-bg);">
    <div class="container">
        <div class="section-head">
            <div>
                <span class="section-title">Lojas Curadas</span>
                <h2>Anúncios em destaque</h2>
            </div>
        </div>
        <div class="cards-grid">
            <?php foreach ($featuredAds as $ad): ?>
                <div class="service-card animate-fade-in">
                    <button class="btn-favorite" data-id="<?php echo $ad['id']; ?>" title="Favoritar">
                        <i data-lucide="heart"></i>
                    </button>
                    <a href="/anuncio/<?php echo e($ad['slug']); ?>" class="card-cover">
                        <img src="/<?php echo e($ad['imagem_principal'] ?: 'assets/img/placeholder.svg'); ?>" alt="<?php echo e($ad['titulo']); ?>">
                        <div class="card-badges">
                            <span class="badge-featured">★ EM DESTAQUE</span>
                        </div>
                    </a>
                    <div class="card-body">
                        <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom: 0.5rem;">
                            <div class="card-cat-wrapper" style="display:flex; align-items:center; gap:0.5rem; color:var(--primary); font-size:0.75rem; font-weight:700;">
                                <?php echo get_category_icon($ad['categoria'], $ad['categoria_icone']); ?>
                                <span class="card-cat" style="color:var(--muted-foreground); text-transform:uppercase;"><?php echo e($ad['categoria']); ?></span>
                            </div>
                            <div class="rating">
                                <i data-lucide="star" style="width:14px; height:14px; fill:currentColor;"></i>
                                <span><?php echo $ad['nota']; ?></span>
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
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="section-head">
            <div>
                <span class="section-title">Recém chegados</span>
                <h2>Novos profissionais na rede</h2>
            </div>
            <a href="/buscar" class="view-all">Explorar tudo</a>
        </div>
        <div class="cards-grid">
            <?php foreach ($recentAds as $ad): ?>
                <div class="service-card">
                    <button class="btn-favorite" data-id="<?php echo $ad['id']; ?>" title="Favoritar">
                        <i data-lucide="heart"></i>
                    </button>
                    <a href="/anuncio/<?php echo e($ad['slug']); ?>" class="card-cover">
                        <img src="/<?php echo e($ad['imagem_principal'] ?: 'assets/img/placeholder.svg'); ?>" alt="<?php echo e($ad['titulo']); ?>">
                    </a>
                    <div class="card-body">
                        <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom: 0.5rem;">
                            <div class="card-cat-wrapper" style="display:flex; align-items:center; gap:0.5rem; color:var(--primary); font-size:0.75rem; font-weight:700;">
                                <?php echo get_category_icon($ad['categoria'], $ad['categoria_icone']); ?>
                                <span class="card-cat" style="color:var(--muted-foreground); text-transform:uppercase;"><?php echo e($ad['categoria']); ?></span>
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
    </div>
</section>

<section class="cta-section" style="padding-bottom: 6rem;">
    <div class="container">
        <div class="cta-card animate-fade-in" style="background: linear-gradient(135deg, var(--primary), #4f46e5); color: white; border-radius: 2rem; padding: 4rem; text-align: center;">
            <span class="section-title" style="color: rgba(255,255,255,0.7);">PARA PRESTADORES</span>
            <h2 style="font-size: 2.5rem; margin-bottom: 1.5rem;">Aumente sua visibilidade em Sergipe.</h2>
            <p style="font-size: 1.1rem; opacity: 0.9; max-width: 600px; margin: 0 auto 2.5rem;">Tenha sua própria vitrine digital, receba contatos diretos no WhatsApp e apareça para milhares de clientes locais.</p>
            <div class="cta-actions" style="justify-content: center;">
                <a class="btn btn-primary" href="/admin/criar" style="background: white; color: var(--primary); padding: 1rem 2.5rem;">Quero anunciar serviço</a>
            </div>
        </div>
    </div>
</section>

<script>
    const cityMapping = <?php echo json_encode($cityMapping); ?>;
    const allCities = <?php 
        $allCities = $pdo->query("SELECT DISTINCT cidade FROM anuncios WHERE status = 'ativo' ORDER BY cidade")->fetchAll(PDO::FETCH_COLUMN);
        echo json_encode($allCities);
    ?>;

    document.addEventListener('DOMContentLoaded', function() {
        const homeRegiao = document.getElementById('homeRegiao');
        const homeCidade = document.getElementById('homeCidade');

        function populateCities(region) {
            homeCidade.innerHTML = '<option value="">Todas as cidades</option>';
            const filtered = region 
                ? allCities.filter(city => cityMapping[city] === region)
                : allCities;
            
            filtered.forEach(city => {
                const opt = document.createElement('option');
                opt.value = city;
                opt.textContent = city;
                homeCidade.appendChild(opt);
            });
        }

        homeRegiao.addEventListener('change', (e) => populateCities(e.target.value));
        populateCities(''); // Init

        // Favorites Logic
        const favorites = JSON.parse(localStorage.getItem('sergipe_favs') || '[]');
        document.querySelectorAll('.btn-favorite').forEach(btn => {
            const id = btn.dataset.id;
            if (favorites.includes(id)) btn.classList.add('active');
            
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                const index = favorites.indexOf(id);
                if (index > -1) {
                    favorites.splice(index, 1);
                    btn.classList.remove('active');
                } else {
                    favorites.push(id);
                    btn.classList.add('active');
                }
                localStorage.setItem('sergipe_favs', JSON.stringify(favorites));
            });
        });
    });
</script>

<?php render_footer(); ?>