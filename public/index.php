<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/layout.php';
require_once __DIR__ . '/../includes/seo.php';

$categories = fetch_all_categories($pdo);
$featuredAds = fetch_featured_ads($pdo);
$recentAds = fetch_recent_ads($pdo, 6);

render_header(seo_title('Início'), 'Encontre serviços e negócios locais com facilidade.');
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
            <h1>Conectado em Sergipe é a plataforma ideal para encontrar serviços locals na sua cidade.</h1>
            <p class="lead">Conectamos prestadores qualificados a pessoas que realmente precisam, de forma rápida, simples e eficiente.</p>
            <p>Encontre eletricistas, manicures, pedreiros e muito mais nas 75 cidades de Sergipe. Simples, rápido e gratuito.</p>
            <div class="hero-actions">
                <a class="btn btn-primary" href="/buscar">Explorar categorias</a>
                <a class="btn btn-outline" href="/login">Quero anuncia</a>
            </div>
        </div>
    </div>
</section>

<section class="quick-section">
    <div class="quick-card container">
        <div class="quick-header">
            <div>
                <span class="section-title">Atalho rápido</span>
            </div>
            <div class="quick-filters">
                <select id="categoryFilter" class="quick-select">
                    <option value="">Todas as categorias</option>
                    <option value="eletricista">⚡ Eletricista</option>
                    <option value="encanador">🔧 Encanador</option>
                    <option value="pedreiro">👷 Pedreiro</option>
                    <option value="pintor">🎨 Pintor</option>
                    <option value="diarista">🧹 Diarista</option>
                    <option value="cabeleireiro">💇 Cabeleireiro</option>
                    <option value="fotografia">📸 Fotografia</option>
                    <option value="confeitaria">🎂 Confeitaria</option>
                    <option value="mecanico">🔩 Mecânico</option>
                    <option value="frete">🚚 Frete e Mudança</option>
                </select>
                <select id="cityFilter" class="quick-select">
                    <option value="">Todas as cidades</option>
                    <option>Amparo de São Francisco</option>
                    <option>Aquidabã</option>
                    <option>Aracaju</option>
                    <option>Arauá</option>
                    <option>Areia Branca</option>
                    <option>Barra dos Coqueiros</option>
                    <option>Boquim</option>
                    <option>Brejo Grande</option>
                    <option>Campo do Brito</option>
                    <option>Canhoba</option>
                    <option>Canindé de São Francisco</option>
                    <option>Capela</option>
                    <option>Carira</option>
                    <option>Carmópolis</option>
                    <option>Cedro de São João</option>
                    <option>Cristinápolis</option>
                    <option>Cumbe</option>
                    <option>Divina Pastora</option>
                    <option>Estância</option>
                    <option>Feira Nova</option>
                    <option>Frei Paulo</option>
                    <option>Gararu</option>
                    <option>General Maynard</option>
                    <option>Gracho Cardoso</option>
                    <option>Ilha das Flores</option>
                    <option>Indiaroba</option>
                    <option>Itabaiana</option>
                    <option>Itabaianinha</option>
                    <option>Itabi</option>
                    <option>Itaporanga d'Ajuda</option>
                    <option>Japaratuba</option>
                    <option>Japoatã</option>
                    <option>Lagarto</option>
                    <option>Laranjeiras</option>
                    <option>Macambira</option>
                    <option>Malhada dos Bois</option>
                    <option>Malhador</option>
                    <option>Maruim</option>
                    <option>Moita Bonita</option>
                    <option>Monte Alegre de Sergipe</option>
                    <option>Muribeca</option>
                    <option>Neópolis</option>
                    <option>Nossa Senhora Aparecida</option>
                    <option>Nossa Senhora da Glória</option>
                    <option>Nossa Senhora das Dores</option>
                    <option>Nossa Senhora de Lourdes</option>
                    <option>Nossa Senhora do Socorro</option>
                    <option>Pacatuba</option>
                    <option>Pedra Mole</option>
                    <option>Pedrinhas</option>
                    <option>Penedo do Sertão</option>
                    <option>Pinhão</option>
                    <option>Pirambu</option>
                    <option>Poço Redondo</option>
                    <option>Poço Verde</option>
                    <option>Porto da Folha</option>
                    <option>Propriá</option>
                    <option>Riachão do Dantas</option>
                    <option>Riachuelo</option>
                    <option>Ribeirópolis</option>
                    <option>Rosário do Catete</option>
                    <option>Salgado</option>
                    <option>Santa Luzia do Itanhy</option>
                    <option>Santa Rosa de Lima</option>
                    <option>Santana do São Francisco</option>
                    <option>Santo Amaro das Brotas</option>
                    <option>São Cristóvão</option>
                    <option>São Domingos</option>
                    <option>São Francisco</option>
                    <option>São Miguel do Aleixo</option>
                    <option>Simão Dias</option>
                    <option>Siriri</option>
                    <option>Telha</option>
                    <option>Tobias Barreto</option>
                    <option>Tomar do Geru</option>
                    <option>Umbaúba</option>
                </select>
                <a href="/buscar" class="view-all">Ver todas →</a>
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
            <a href="/buscar?categoria=mecanico" class="pill">🔩 Mecânico</a>
            <a href="/buscar?categoria=frete" class="pill">🚚 Frete</a>
        </div>
    </div>
</section>

<section class="section trending-section">
    <div class="container">
        <div class="section-head">
            <div><span class="section-title">Em alta</span><h2>Mais procurados esta semana</h2></div>
            <a href="/buscar" class="view-all">Ver tudo →</a>
        </div>
        <div class="trending-row">
            <?php foreach ($featuredAds as $ad): ?>
                <a href="/anuncio/<?php echo e($ad['slug']); ?>" class="mini-card">
                    <img src="/<?php echo e($ad['imagem_principal'] ?: 'assets/img/placeholder.svg'); ?>" alt="<?php echo e($ad['titulo']); ?>">
                    <span class="mini-cat">★ <?php echo e($ad['categoria']); ?></span>
                    <strong><?php echo e($ad['titulo']); ?></strong>
                </a>
            <?php endforeach; ?>
            <?php foreach ($featuredAds as $ad): ?>
                <a href="/anuncio/<?php echo e($ad['slug']); ?>" class="mini-card">
                    <img src="/<?php echo e($ad['imagem_principal'] ?: 'assets/img/placeholder.svg'); ?>" alt="<?php echo e($ad['titulo']); ?>">
                    <span class="mini-cat">★ <?php echo e($ad['categoria']); ?></span>
                    <strong><?php echo e($ad['titulo']); ?></strong>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="section categories-area">
    <div class="container">
        <div class="section-head">
            <div><span class="section-title">Categorias</span><h2>Navegue por especialidade</h2><p>Cada categoria reúne uma lista filtrada apenas dos profissionais da sua região em Sergipe.</p></div>
            <a href="/buscar">Ver todas</a>
        </div>
        <div class="category-grid">
            <?php foreach ($categories as $category): ?>
                <a href="/categoria/<?php echo e($category['slug']); ?>" class="category-card">
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
            <span class="section-title">Lojas curadas</span>
            <h2>Anúncios em destaque</h2>
        </div>
        <div class="cards-grid">
            <?php foreach ($featuredAds as $ad): ?>
                <a href="/anuncio/<?php echo e($ad['slug']); ?>" class="service-card">
                    <div class="card-cover">
                        <img src="/<?php echo e($ad['imagem_principal'] ?: 'assets/img/placeholder.svg'); ?>" alt="<?php echo e($ad['titulo']); ?>">
                        <?php if ($ad['destaque']): ?>
                            <span class="badge">★ Em destaque</span>
                        <?php endif; ?>
                        <span class="card-label">Loja</span>
                    </div>
                    <div class="card-body">
                        <span class="card-cat"><?php echo e($ad['categoria']); ?></span>
                        <h3><?php echo e($ad['titulo']); ?></h3>
                        <p><?php echo e($ad['descricao']); ?></p>
                        <div class="meta">
                            <span><?php echo e($ad['cidade']); ?></span>
                            <span class="rating"><?php echo $ad['nota']; ?> · <?php echo $ad['avaliacoes']; ?></span>
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
            <span class="section-title">Acabou de chegar</span>
            <h2>Recentes em Sergipe</h2>
        </div>
        <div class="cards-grid">
            <?php foreach ($recentAds as $ad): ?>
                <a href="/anuncio/<?php echo e($ad['slug']); ?>" class="service-card">
                    <div class="card-cover">
                        <img src="/<?php echo e($ad['imagem_principal'] ?: 'assets/img/placeholder.svg'); ?>" alt="<?php echo e($ad['titulo']); ?>">
                        <span class="card-label">Loja</span>
                    </div>
                    <div class="card-body">
                        <span class="card-cat"><?php echo e($ad['categoria']); ?></span>
                        <h3><?php echo e($ad['titulo']); ?></h3>
                        <p><?php echo e($ad['descricao']); ?></p>
                        <div class="meta">
                            <span><?php echo e($ad['cidade']); ?></span>
                            <span class="rating"><?php echo $ad['nota']; ?> · <?php echo $ad['avaliacoes']; ?></span>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="cta-section">
    <div class="container cta-card">
        <div>
            <span class="section-title">Para prestadores</span>
            <h2>Faça parte da maior vitrine de serviços de Sergipe.</h2>
            <p>Cadastro gratuito de anúncio. Loja própria, descrição do trabalho e contato direto pelo WhatsApp.</p>
        </div>
        <a class="btn btn-light" href="https://wa.me/5579999999999" target="_blank" rel="noopener">
            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M2.992 16.342a2 2 0 0 1 .094 1.167l-1.065 3.29a1 1 0 0 0 1.236 1.168l3.413-.998a2 2 0 0 1 1.099.092 10 10 0 1 0-4.777-4.719"></path></svg>
            Falar no WhatsApp
        </a>
    </div>
</section>

<?php render_footer();