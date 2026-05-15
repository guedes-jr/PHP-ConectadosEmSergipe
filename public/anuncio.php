<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/layout.php';
require_once __DIR__ . '/../includes/seo.php';

$slug = isset($_GET['slug']) ? trim((string)$_GET['slug']) : '';
$ad = $slug !== '' ? find_ad_by_slug($pdo, $slug) : null;

if (!$ad) {
    http_response_code(404);
    render_header($pdo, seo_title('Anúncio não encontrado'), 'Anúncio não encontrado.');
    echo '<div class="container section" style="text-align:center; padding: 10rem 0;">';
    echo '<i data-lucide="search-x" style="width:64px; height:64px; color:var(--muted-foreground); margin-bottom:1rem;"></i>';
    echo '<h1>Anúncio não encontrado</h1>';
    echo '<p>O profissional que você procura pode ter removido o anúncio ou o link está incorreto.</p>';
    echo '<a href="' . url('/') . '" class="btn btn-primary" style="margin-top:2rem;">Voltar para o Início</a>';
    echo '</div>';
    render_footer();
    exit;
}

$images = fetch_images_by_ad($pdo, (int)$ad['id']);
$horarios = fetch_horarios_by_ad($pdo, (int)$ad['id']);

$diasSemana = [
    1 => 'Segunda', 2 => 'Terça', 3 => 'Quarta', 4 => 'Quinta', 
    5 => 'Sexta', 6 => 'Sábado', 7 => 'Domingo'
];

render_header($pdo, $ad['titulo'], $ad['descricao'], $ad['imagem_principal'] ?: '');
?>

<div class="ad-page-wrapper">
    <div class="ad-banner-hero">
        <?php if ($ad['imagem_banner']): ?>
            <img src="<?php echo asset_url($ad['imagem_banner']); ?>" alt="Banner" class="banner-img">
        <?php else: ?>
            <div style="background: linear-gradient(135deg, var(--primary), #4f46e5); width:100%; height:100%;"></div>
        <?php endif; ?>
        <div class="banner-overlay"></div>
        <a href="javascript:history.back()" class="btn-back" style="background:#fff;color:#333;border:1px solid #e5e7eb;">
            <i data-lucide="arrow-left"></i> Voltar
        </a>
    </div>

    <div class="container ad-content-container">
        <div class="ad-header-card animate-fade-in">
            <div class="ad-header-main">
                <div class="ad-header-profile">
                    <img src="<?php echo asset_url($ad['imagem_principal']); ?>" alt="<?php echo e($ad['titulo']); ?>">
                </div>
                <div class="ad-header-info">
                    <div style="display:flex; align-items:center; gap:0.5rem; margin-bottom:0.5rem;">
                        <span class="ad-badge-category" style="background: var(--accent); padding: 0.25rem 0.75rem; border-radius: 99px;"><?php echo e($ad['categoria_nome']); ?></span>
                        <?php if($ad['destaque']): ?>
                            <span style="background: #fef3c7; color: #92400e; font-size: 0.7rem; font-weight: 800; padding: 0.25rem 0.75rem; border-radius: 99px;">★ DESTAQUE</span>
                        <?php endif; ?>
                        <span class="ad-badge-type" style="background: var(--muted-bg); color: var(--muted-foreground); border: 1px solid var(--border); font-size: 0.75rem; font-weight: 700; padding: 0.25rem 0.75rem; border-radius: 99px;">
                            <?php echo $ad['tipo'] === 'loja' ? '🏪 Loja' : '🛠️ Prestador'; ?>
                        </span>
                        <?php if ($ad['cnpj']): ?>
                            <span style="font-size: 0.75rem; color: var(--muted-foreground); margin-left: 0.5rem;">CNPJ: <?php echo e($ad['cnpj']); ?></span>
                        <?php endif; ?>
                    </div>
                    <h1 style="font-family: 'Outfit', sans-serif;"><?php echo e($ad['titulo']); ?></h1>
                    <div class="ad-header-meta">
                        <div class="meta-item rating">
                            <i data-lucide="star" style="width:16px; height:16px; fill:currentColor;"></i>
                            <span><?php echo e($ad['nota']); ?> (<?php echo e($ad['avaliacoes']); ?> avaliações)</span>
                        </div>
                        <div class="meta-item">
                            <i data-lucide="map-pin" style="width:16px; height:16px;"></i>
                            <span><?php echo e($ad['cliente_cidade'] ?: $ad['cidade']); ?> — <?php echo e($ad['regiao']); ?></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="ad-header-actions">
                <a href="<?php echo e(whatsapp_link($ad['cliente_whatsapp'] ?: $ad['cliente_telefone'], 'Olá, encontrei seu anúncio pelo Conectado em Sergipe')); ?>" class="btn-action whatsapp" target="_blank">
                    <i data-lucide="message-circle"></i> WhatsApp
                </a>
                <a href="tel:<?php echo only_digits($ad['cliente_telefone']); ?>" class="btn-action outline">
                    <i data-lucide="phone"></i> Ligar
                </a>
                <button class="btn-action outline" onclick="shareAd()">
                    <i data-lucide="share-2"></i> Compartilhar
                </button>
            </div>
        </div>

        <div class="ad-main-layout">
            <div class="ad-left-col">
                <section class="content-section animate-fade-in">
                    <span class="section-label">SOBRE</span>
                    <h2>Conheça o trabalho</h2>
                    <p style="white-space: pre-line; color: var(--foreground);"><?php echo e($ad['descricao']); ?></p>
                </section>

                <?php if (!empty($images)): ?>
                <section class="content-section animate-fade-in">
                    <span class="section-label">GALERIA</span>
                    <h2>Trabalhos realizados</h2>
                    <div class="ad-gallery-grid">
                        <?php foreach ($images as $i => $img): ?>
                            <div class="gallery-card" onclick="openGallery(<?php echo $i; ?>)">
                                <img src="<?php echo asset_url($img['caminho']); ?>" alt="Trabalho realizado" loading="lazy">
                            </div>
                        <?php endforeach; ?>
                    </div>
                </section>

                <div id="galleryModal" class="gallery-modal" onclick="closeGallery()">
                    <button class="gallery-btn prev" onclick="event.stopPropagation(); prevGallery()">
                        <i data-lucide="chevron-left"></i>
                    </button>
                    <div class="gallery-content" onclick="event.stopPropagation()">
                        <img id="galleryImg" src="" alt="Trabalho realizado">
                    </div>
                    <button class="gallery-btn next" onclick="event.stopPropagation(); nextGallery()">
                        <i data-lucide="chevron-right"></i>
                    </button>
                    
                    <div class="gallery-footer" onclick="event.stopPropagation()">
                        <div class="gallery-indicators"></div>
                        <button class="gallery-close-bottom" onclick="closeGallery()" aria-label="Fechar">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                            <span>Fechar</span>
                        </button>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <aside class="ad-right-col">
                <div class="sidebar-card animate-fade-in">
                    <h3 class="sidebar-title"><i data-lucide="clock"></i> Horário</h3>
                    <div class="schedule-list">
                        <?php foreach($horarios as $h): ?>
                            <div class="schedule-item <?php echo $h['fechado'] ? 'closed' : ''; ?>">
                                <span><?php echo $diasSemana[$h['dia_semana']]; ?></span>
                                <strong><?php echo $h['fechado'] ? '<span style="color:#ef4444">Fechado</span>' : e($h['abertura']) . ' às ' . e($h['fechamento']); ?></strong>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <?php if ($ad['instagram'] || $ad['facebook']): ?>
                <div class="sidebar-card animate-fade-in">
                    <h3 class="sidebar-title"><i data-lucide="share-2"></i> Redes Sociais</h3>
                    <div class="social-btns">
                        <?php if ($ad['instagram']): ?>
                        <a href="https://instagram.com/<?php echo ltrim(e($ad['instagram']), '@'); ?>" class="btn btn-outline" target="_blank">
                            <i data-lucide="instagram"></i> Instagram
                        </a>
                        <?php endif; ?>
                        <?php if ($ad['facebook']): ?>
                        <a href="https://facebook.com/<?php echo e($ad['facebook']); ?>" class="btn btn-outline" target="_blank">
                            <i data-lucide="facebook"></i> Facebook
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>

                <div class="sidebar-card contact-main animate-fade-in" style="background: var(--muted-bg); border: none;">
                    <span class="section-label">CONTATO</span>
                    <h3 class="sidebar-title">Fale diretamente</h3>
                    <div class="contact-methods">
                        <div class="method-item" style="background: var(--card);">
                            <i data-lucide="phone"></i>
                            <span><?php echo e($ad['cliente_telefone']); ?></span>
                        </div>
                        <?php if ($ad['cliente_email']): ?>
                        <div class="method-item" style="background: var(--card);">
                            <i data-lucide="mail"></i>
                            <span><?php echo e($ad['cliente_email']); ?></span>
                        </div>
                        <?php endif; ?>
                    </div>
                    <div class="sidebar-info-card" style="margin-top:2rem;">
                        <span>Anunciante</span>
                        <strong style="font-size:1.1rem;"><?php echo e($ad['cliente_nome']); ?></strong>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</div>

<?php render_footer(); ?>
