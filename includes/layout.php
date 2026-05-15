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
    echo '<link rel="stylesheet" href="/assets/css/style.css?v=' . filemtime(__DIR__ . '/../assets/css/style.css') . '">';
    echo '<link rel="icon" type="image/x-icon" href="/assets/img/favicon.ico">';
    echo '</head>';
    echo '<body>';
    echo '<header class="site-header">';
    echo '<nav class="nav container">';
    echo '<a class="brand" href="' . url('/') . '" aria-label="Conectado em Sergipe">';
    echo '<img src="/assets/img/logo-hero.png" alt="Conectado em Sergipe">';
    echo '<span><strong>Conectado em Sergipe</strong><small>Vitrine de serviços</small></span>';
    echo '</a>';
    echo '<div class="nav-center">';
    echo '<a href="' . url('/') . '" class="nav-link">Início</a>';
    echo '<a href="' . url('/buscar') . '" class="nav-link">Buscar</a>';
    echo '<a href="' . url('/admin/') . '" class="nav-link">Painel</a>';
    echo '</div>';
    echo '<div class="nav-actions">';
    echo '<a class="btn btn-primary" href="/admin/criar">Anunciar serviço</a>';
    echo '<button class="menu-toggle" aria-label="Abrir menu">';
    echo '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>';
    echo '</button>';
    echo '</div>';
    echo '</nav>';
    echo '<div class="nav-links-mobile" id="mobileMenu">';
    echo '<a href="' . url('/') . '">Início</a>';
    echo '<a href="' . url('/buscar') . '">Buscar</a>';
    echo '<a href="' . url('/admin/') . '">Painel</a>';
    echo '<a href="' . url('/admin/criar') . '" class="btn btn-primary">Anunciar serviço</a>';
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
    echo '<a class="footer-brand" href="' . url('/') . '" aria-label="Conectado em Sergipe">';
    echo '<img src="/assets/img/logo-hero.png" alt="Conectado em Sergipe">';
    echo '<span><strong>Conectado em Sergipe</strong><small>Vitrine de serviços</small></span>';
    echo '</a>';
    echo '<p>A maior vitrine de serviços e profissionais autônomos de Sergipe. Conectamos quem precisa de um serviço confiável aos melhores profissionais da sua cidade.</p>';
    echo '<div class="footer-social">';
    echo '<a href="https://www.instagram.com/conectadoemsergipe/" aria-label="Instagram" target="_blank" rel="noopener"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="20" height="20" x="2" y="2" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line></svg></a>';
    echo '<a href="https://www.youtube.com/@ConectadoemSergipe" aria-label="YouTube" target="_blank" rel="noopener"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22.54 6.42a2.78 2.78 0 0 0-1.94-2C18.88 4 12 4 12 4s-6.88 0-8.6.42a2.78 2.78 0 0 0-1.94 2C1 8.14 1 12 1 12s0 3.86.46 5.58a2.78 2.78 0 0 0 1.94 2c1.72.42 8.6.42 8.6.42s6.88 0 8.6-.42a2.78 2.78 0 0 0 1.94-2C23 15.86 23 12 23 12s0-3.86-.46-5.58z"></path><polygon points="9.75 15.02 15.5 12 9.75 8.98 9.75 15.02"></polygon></svg></a>';
    echo '</div></div>';
    echo '<div class="footer-col"><h3>Para clientes</h3><nav class="footer-links"><a href="' . url('/buscar') . '">Buscar serviços</a><a href="' . url('/buscar?categoria=eletricista') . '">Eletricistas</a><a href="' . url('/buscar?categoria=diarista') . '">Diaristas</a><a href="' . url('/buscar?categoria=pintor') . '">Pintores</a><a href="' . url('/buscar?categoria=pedreiro') . '">Pedreiros</a></nav></div>';
    echo '<div class="footer-col"><h3>Para profissionais</h3><nav class="footer-links"><a href="' . url('/admin/') . '">Painel de Controle</a><a href="' . url('/admin/criar') . '">Criar loja gratuita</a><a href="https://wa.me/5579999999999" target="_blank" rel="noopener">Falar no WhatsApp</a></nav></div>';
    echo '<div class="footer-col"><h3>Institucional</h3><nav class="footer-links"><a href="' . url('/') . '">Início</a><a href="' . url('/buscar') . '">Buscar</a><a href="' . url('/privacidade') . '">Política de privacidade</a><a href="' . url('/termos') . '">Termos de uso</a></nav></div>';
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
