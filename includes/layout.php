<?php

declare(strict_types=1);

require_once __DIR__ . '/seo.php';

function render_header(PDO $pdo, string $title, string $description = '', string $image = '', string $url = ''): void
{
    echo '<!DOCTYPE html>';
    echo '<html lang="pt-BR">';
    echo '<head>';
    echo '<meta charset="UTF-8">';
    echo '<meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">';
    render_seo_tags($pdo, $title, $description, $image, $url);
    echo '<link rel="preconnect" href="https://fonts.googleapis.com">';
    echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>';
    echo '<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">';
    echo '<link rel="stylesheet" href="/assets/css/style.css">';
    echo '<link rel="icon" type="image/x-icon" href="/assets/img/favicon.ico">';
    echo '</head>';
    echo '<body>';
    echo '<header class="site-header">';
    echo '<nav class="nav container">';
    echo '<a class="brand" href="/" aria-label="Conectado em Sergipe">';
    echo '<img src="/assets/img/logo-hero.png" alt="Conectado em Sergipe">';
    echo '<span><strong>Conectado em Sergipe</strong><small>Vitrine de serviços</small></span>';
    echo '</a>';
    echo '<div class="nav-center">';
    echo '<a href="/" class="nav-link">Início</a>';
    echo '<a href="/buscar" class="nav-link">Buscar</a>';
    echo '<a href="/admin/" class="nav-link">Painel</a>';
    echo '</div>';
    echo '<div class="nav-actions">';
    echo '<a class="btn btn-primary" href="/admin/criar">Anunciar serviço</a>';
    echo '<button class="menu-toggle" aria-label="Abrir menu">';
    echo '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>';
    echo '</button>';
    echo '</div>';
    echo '</nav>';
    echo '<div class="nav-links-mobile" id="mobileMenu">';
    echo '<a href="/">Início</a>';
    echo '<a href="/buscar">Buscar</a>';
    echo '<a href="/admin/">Painel</a>';
    echo '<a href="/admin/criar" class="btn btn-primary">Anunciar serviço</a>';
    echo '</div>';
    echo '</header>';
    echo '<main>';
}

function render_footer(): void
{
    $year = date('Y');
    echo '</main>';
    echo '<footer class="site-footer">';
    echo '<div class="footer-main"><div class="container footer-grid">';
    echo '<div class="footer-col footer-about">';
    echo '<a class="footer-brand" href="/" aria-label="Conectado em Sergipe">';
    echo '<img src="/assets/img/logo-hero.png" alt="Conectado em Sergipe">';
    echo '<span><strong>Conectado em Sergipe</strong><small>Vitrine de serviços</small></span>';
    echo '</a>';
    echo '<p>A maior vitrine de serviços e profissionais autônomos de Sergipe. Conectamos quem precisa de um serviço confiável aos melhores profissionais da sua cidade.</p>';
    echo '<div class="footer-social">';
    echo '<a href="#" aria-label="Instagram"><svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.012-3.584.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.947.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.947-.072 4.357-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.221-4.402-2.561-6.775-6.979-6.979-1.281-.059-1.69-.073-4.949-.073z"/><path d="M12 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8z"/><circle cx="18.406" cy="5.594" r="1.44"/></svg></a>';
    echo '<a href="#" aria-label="Facebook"><svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24"><path d="M9.101 23.691c-3.504 0-6.435-2.93-6.435-6.434 0-3.505 2.93-6.436 6.435-6.436 3.505 0 6.436 2.93 6.436 6.436 0 3.504-2.93 6.434-6.436 6.434zm7.042-10.422c0-3.253-2.637-5.894-5.894-5.894h-1.235c-3.26 0-5.894 2.63-5.894 5.894v11.303h1.918v-5.48h1.882v3.994h1.868v-3.994h1.868v-3.83H17.09V8.282h-1.047z"/></svg></a>';
    echo '</div></div>';
    echo '<div class="footer-col"><h3>Para clientes</h3><nav class="footer-links"><a href="/buscar">Buscar serviços</a><a href="/buscar?categoria=eletricista">Eletricistas</a><a href="/buscar?categoria=diarista">Diaristas</a><a href="/buscar?categoria=pintor">Pintores</a><a href="/buscar?categoria=pedreiro">Pedreiros</a></nav></div>';
    echo '<div class="footer-col"><h3>Para profissionais</h3><nav class="footer-links"><a href="/admin/">Painel de Controle</a><a href="/admin/criar">Criar loja gratuita</a><a href="https://wa.me/5579999999999" target="_blank" rel="noopener">Falar no WhatsApp</a></nav></div>';
    echo '<div class="footer-col"><h3>Institucional</h3><nav class="footer-links"><a href="/">Início</a><a href="/buscar">Buscar</a><a href="/privacidade">Política de privacidade</a><a href="/termos">Termos de uso</a></nav></div>';
    echo '</div></div>';
    echo '<div class="footer-bottom"><div class="container footer-bottom-inner"><p>&copy; ' . $year . ' Conectado em Sergipe. Todos os direitos reservados.</p><p class="footer-location">📍 Sergipe — Brasil</p></div></div>';
    echo '</footer>';
    echo '<div class="theme-toggle-container">';
    echo '<button class="theme-toggle-btn" id="themeToggleBtn" aria-label="Mudar tema" aria-haspopup="true" aria-expanded="false">';
    echo '<svg class="icon-sun" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="5"></circle><line x1="12" y1="1" x2="12" y2="3"></line><line x1="12" y1="21" x2="12" y2="23"></line><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line><line x1="1" y1="12" x2="3" y2="12"></line><line x1="21" y1="12" x2="23" y2="12"></line><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line></svg>';
    echo '<svg class="icon-moon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:none;"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path></svg>';
    echo '<svg class="icon-system" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:none;"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect><line x1="8" y1="21" x2="16" y2="21"></line><line x1="12" y1="17" x2="12" y2="21"></line></svg>';
    echo '</button>';
    echo '<div class="theme-dropdown" id="themeDropdown">';
    echo '<button class="theme-option active" data-theme-value="light"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="5"></circle><line x1="12" y1="1" x2="12" y2="3"></line><line x1="12" y1="21" x2="12" y2="23"></line><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line><line x1="1" y1="12" x2="3" y2="12"></line><line x1="21" y1="12" x2="23" y2="12"></line><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line></svg> Claro</button>';
    echo '<button class="theme-option" data-theme-value="dark"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path></svg> Escuro</button>';
    echo '<button class="theme-option" data-theme-value="system"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect><line x1="8" y1="21" x2="16" y2="21"></line><line x1="12" y1="17" x2="12" y2="21"></line></svg> Sistema</button>';
    echo '</div></div>';
    echo '<script src="https://unpkg.com/lucide@latest"></script>';
    echo '<script>lucide.createIcons();</script>';
    echo '<script src="/assets/js/script.js" defer></script>';
    echo '</body></html>';
}
