<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/layout.php';
require_once __DIR__ . '/../includes/seo.php';

$categories = fetch_all_categories($pdo);
$featuredAds = fetch_featured_ads($pdo);
$recentAds = fetch_recent_ads($pdo, 6);

render_header($pdo, seo_title('Início'), 'Encontre serviços e negócios locais com facilidade.');
?>

<section class="hero">
    <div class="hero-slider">
        <div class="hero-slide active">
            <img src="/assets/img/hero-orla.png" alt="Orla de Aracaju">
            <div class="hero-overlay"></div>
        </div>
        <div class="hero-slide">
            <img src="/assets/img/sergipe-cidade1.jpg" alt="Sergipe">
            <div class="hero-overlay"></div>
        </div>
        <div class="hero-slide">
            <img src="/assets/img/sergipe-cidade2.jpg" alt="Sergipe">
            <div class="hero-overlay"></div>
        </div>
        <div class="hero-slide">
            <img src="/assets/img/sergipe-cidade3.jpg" alt="Sergipe">
            <div class="hero-overlay"></div>
        </div>
        <div class="hero-slide">
            <img src="/assets/img/caranguejo.png" alt="Cultura sergipana">
            <div class="hero-overlay"></div>
        </div>
        <div class="hero-nav">
            <button class="hero-dot active" data-slide="0"></button>
            <button class="hero-dot" data-slide="1"></button>
            <button class="hero-dot" data-slide="2"></button>
            <button class="hero-dot" data-slide="3"></button>
            <button class="hero-dot" data-slide="4"></button>
        </div>
        <button class="hero-arrow prev">‹</button>
        <button class="hero-arrow next">›</button>
    </div>
    <div class="container">
        <div class="hero-content">
            <h1><?php echo get_setting($pdo, 'hero_titulo', 'A plataforma ideal para encontrar serviços locais na sua cidade'); ?></h1>
            <p class="lead"><?php echo get_setting($pdo, 'hero_subtitulo', 'Conectamos prestadores qualificados a pessoas que realmente precisam.'); ?></p>
            <div class="hero-actions">
                <a class="btn btn-primary" href="/buscar">Explorar categorias</a>
                <a class="btn btn-outline" href="https://wa.me/557996327084?text=Gostaria%20de%20anunciar%20na%20Conectado%20em%20Sergipe" target="_blank">Quero anunciar</a>
            </div>
        </div>
    </div>
</section>

<section class="quick-section">
    <div class="container">
        <div class="quick-card">
            <div class="quick-header">
                <div>
                    <span class="section-label">Busca Rápida</span>
                    <h2>O que você procura hoje?</h2>
                </div>
                <div class="quick-filters">
                    <select id="categoryFilter" class="quick-select">
                        <option value="">Todas as categorias</option>
                        <option value="eletricista">⚡ Eletricista</option>
                        <option value="encanador">🔧 Encanador</option>
                        <option value="pedreiro">👷 Pedreiro</option>
                        <option value="pintor">🎨 Pintor</option>
                    </select>
                    <select id="cityFilter" class="quick-select">
                        <option value="">Todas as cidades</option>
                        <option>Aracaju</option>
                        <option>Nossa Senhora do Socorro</option>
                        <option>Lagarto</option>
                        <option>Itabaiana</option>
                        <option>São Cristóvão</option>
                        <option>Estância</option>
                    </select>
                </div>
            </div>
            <div class="quick-pills">
                <a href="/buscar?categoria=eletricista" class="pill">⚡ Eletricista</a>
                <a href="/buscar?categoria=encanador" class="pill">🔧 Encanador</a>
                <a href="/buscar?categoria=pedreiro" class="pill">👷 Pedreiro</a>
                <a href="/buscar?categoria=pintor" class="pill">🎨 Pintor</a>
                <a href="/buscar?categoria=diarista" class="pill">🧹 Diarista</a>
                <a href="/buscar?categoria=cabeleireiro" class="pill">💇 Cabeleireiro</a>
                <a href="/buscar?categoria=fotografia" class="pill">📸 Fotografia</a>
                <a href="/buscar?categoria=confeitaria" class="pill">🎂 Confeitaria</a>
            </div>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="section-head">
            <div>
                <span class="section-label">Em alta</span>
                <h2>Mais procurados da semana</h2>
            </div>
            <a href="/buscar" class="view-all">Ver tudo →</a>
        </div>
        <div class="trending-carousel">
            <div class="trending-track">
                <?php foreach ($featuredAds as $ad): ?>
                    <a href="/anuncio/<?php echo e($ad['slug']); ?>" class="mini-card">
                        <img src="<?php echo asset_url($ad['imagem_principal']); ?>"
                            alt="<?php echo e($ad['titulo']); ?>">
                        <strong><?php echo e($ad['titulo']); ?></strong>
                    </a>
                <?php endforeach; ?>
                <!-- Duplicate for infinite effect -->
                <?php foreach ($featuredAds as $ad): ?>
                    <a href="/anuncio/<?php echo e($ad['slug']); ?>" class="mini-card">
                        <img src="<?php echo asset_url($ad['imagem_principal']); ?>"
                            alt="<?php echo e($ad['titulo']); ?>">
                        <strong><?php echo e($ad['titulo']); ?></strong>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>

<section class="section categories-area">
    <div class="container">
        <div class="section-head">
            <div>
                <span class="section-label">Categorias</span>
                <h2>Navegue por especialidade</h2>
            </div>
            <a href="/buscar" class="view-all">Todas as categorias</a>
        </div>
        <div class="category-grid">
            <?php foreach ($categories as $category): ?>
                <a href="/buscar?categoria=<?php echo e($category['slug']); ?>" class="category-card">
                    <div class="cat-icon-wrapper">
                        <?php echo get_category_icon($category['nome']); ?>
                    </div>
                    <strong><?php echo e($category['nome']); ?></strong>
                    <small><?php echo $category['Total']; ?> profissionais</small>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="section-head">
            <div>
                <span class="section-label">Lojas Curadas</span>
                <h2>Anúncios em destaque</h2>
            </div>
        </div>
        <div class="cards-grid">
            <?php foreach ($featuredAds as $ad): ?>
                <a href="/anuncio/<?php echo e($ad['slug']); ?>" class="service-card">
                    <div class="card-cover">
                        <img src="<?php echo asset_url($ad['imagem_principal']); ?>"
                            alt="<?php echo e($ad['titulo']); ?>">
                        <div class="card-badges">
                            <span class="badge-featured">★ EM DESTAQUE</span>
                            <span class="badge-store">LOJA</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <span class="card-cat"><?php echo e($ad['categoria']); ?></span>
                        <h3><?php echo e($ad['titulo']); ?></h3>
                        <div class="card-footer">
                            <div class="location">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" />
                                    <circle cx="12" cy="10" r="3" />
                                </svg>
                                <span><?php echo e($ad['cidade']); ?></span>
                            </div>
                            <div class="rating">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor" stroke="none">
                                    <polygon
                                        points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2" />
                                </svg>
                                <span><?php echo $ad['nota']; ?> · <?php echo $ad['avaliacoes'] ?? '0'; ?></span>
                            </div>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="section">
    <div class="container">
        <div class="section-head">
            <div>
                <span class="section-label">Acabou de chegar</span>
                <h2>Recentes em Sergipe</h2>
            </div>
            <a href="/buscar" class="view-all">Ver todos →</a>
        </div>
        <div class="cards-grid">
            <?php foreach ($recentAds as $ad): ?>
                <a href="/anuncio/<?php echo e($ad['slug']); ?>" class="service-card">
                    <div class="card-cover">
                        <img src="<?php echo asset_url($ad['imagem_principal']); ?>"
                            alt="<?php echo e($ad['titulo']); ?>">
                        <div class="card-badges">
                            <?php if (isset($ad['destaque']) && $ad['destaque']): ?>
                                <span class="badge-featured">★ EM DESTAQUE</span>
                            <?php endif; ?>
                            <span class="badge-store">LOJA</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <span class="card-cat"><?php echo e($ad['categoria']); ?></span>
                        <h3><?php echo e($ad['titulo']); ?></h3>
                        <div class="card-footer">
                            <div class="location">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" />
                                    <circle cx="12" cy="10" r="3" />
                                </svg>
                                <span><?php echo e($ad['cidade']); ?></span>
                            </div>
                            <div class="rating">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor" stroke="none">
                                    <polygon
                                        points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2" />
                                </svg>
                                <span><?php echo $ad['nota'] ?? '5.0'; ?> · <?php echo $ad['avaliacoes'] ?? '0'; ?></span>
                            </div>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="cta-section">
    <div class="container">
        <div class="cta-card">
            <div class="cta-content">
                <span class="section-label" style="color: rgba(255,255,255,0.6);">Para prestadores</span>
                <h2>Faça parte da maior vitrine de serviços de Sergipe.</h2>
                <p>Cadastro gratuito de anúncio, Loja própria, descrição do trabalho e contato direto pelo WhatsApp.</p>
                <div class="cta-actions">
                    <a class="btn btn-primary whatsapp-btn" href="https://wa.me/5579999999999">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path
                                d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 1 1-7.6-11.7 1 1 0 0 1 .5.1l3.6-1 1 3.6a1 1 0 0 1 .1.5 8.5 8.5 0 0 1 3.3 4.7z" />
                        </svg>
                        Falar no WhatsApp
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<?php render_footer(); ?>