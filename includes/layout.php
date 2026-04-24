<?php

declare(strict_types=1);

function render_header(string $title, string $description = ''): void
{
    $safeTitle = htmlspecialchars($title, ENT_QUOTES, 'UTF-8');
    $safeDescription = htmlspecialchars($description, ENT_QUOTES, 'UTF-8');
    echo '<!DOCTYPE html>';
    echo '<html lang="pt-BR">';
    echo '<head>';
    echo '<meta charset="UTF-8">';
    echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
    echo '<title>' . $safeTitle . '</title>';
    echo '<meta name="description" content="' . $safeDescription . '">';
    echo '<link rel="stylesheet" href="/assets/css/style.css">';
    echo '<link rel="icon" type="image/x-icon" href="/favicon.ico">';
    echo '</head>';
    echo '<body>';
    echo '<header class="site-header">';
    echo '<nav class="nav container">';
    echo '<a class="brand" href="/" aria-label="Conectado em Sergipe">';
    echo '<img src="/assets/img/logo-hero.png" alt="Conectado em Sergipe">';
    echo '<span><strong>Conectado em Sergipe</strong><small>Vitrine de serviços</small></span>';
    echo '</a>';
    echo '<div class="nav-center">';
    echo '<a href="/">Início</a>';
    echo '<a href="/buscar">Buscar</a>';
    echo '<a href="/admin/">Painel</a>';
    echo '<a href="/admin/">Login</a>';
    echo '</div>';
    echo '<a class="btn btn-primary" href="/admin/criar">Anunciar serviço</a>';
    echo '<button class="menu-toggle" aria-label="Abrir menu">☰</button>';
    echo '</nav>';
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
    echo '<a class="footer-brand" href="/"><img src="/assets/img/logo-white.png" alt="Conectado em Sergipe"><span>Conectado em Sergipe</span></a>';
    echo '<p>A maior vitrine de serviços e profissionais autônomos de Sergipe. Conectamos quem precisa de um serviço confiável aos melhores profissionais da sua cidade.</p>';
    echo '<div class="footer-social">';
    echo '<a href="#" aria-label="Instagram"><svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.012-3.584.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.947.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.947-.072 4.357-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.221-4.402-2.561-6.775-6.979-6.979-1.281-.059-1.69-.073-4.949-.073z"/><path d="M12 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8z"/><circle cx="18.406" cy="5.594" r="1.44"/></svg></a>';
    echo '<a href="#" aria-label="Facebook"><svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24"><path d="M9.101 23.691c-3.504 0-6.435-2.93-6.435-6.434 0-3.505 2.93-6.436 6.435-6.436 3.505 0 6.436 2.93 6.436 6.436 0 3.504-2.93 6.434-6.436 6.434zm7.042-10.422c0-3.253-2.637-5.894-5.894-5.894h-1.235c-3.26 0-5.894 2.63-5.894 5.894v11.303h1.918v-5.48h1.882v3.994h1.868v-3.994h1.868v-3.83H17.09V8.282h-1.047z"/></svg></a>';
    echo '<a href="#" aria-label="WhatsApp"><svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.964-.94 1.162-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.1.297-.347.446-.52.149-.174.198-.298.297-.496.099-.198.05-.372-.025-.52-.075-.149-.66-1.59-1.905-2.77-.772-.728-1.467-1.223-1.685-1.413-.218-.19-.743-.198-.99-.099-.247.099-.42.149-.599.149-.149 0-.372-.025-.535-.149-.162-.124-.433-.347-.598-.52-.173-.174-.347-.223-.496-.298-.297-.149-.595.074-.756.297-.161.223-.538.695-.767 1.182-.223.473-.397.397-.556.397-.149 0-.372-.025-.568-.124-.196-.099-.307-.149-.436-.149-.129 0-.321.025-.444.173-.124.149-.488.487-.488 1.23 0 1.732.42 2.511.927 3.351.507.84 1.108 1.628 1.18 1.828.074.199.024.348-.017.485-.05.149-.124.298-.297.496z"/></svg></a>';
    echo '</div></div>';
    echo '<div class="footer-col"><h3>Para clientes</h3><nav class="footer-links"><a href="/buscar">Buscar serviços</a><a href="/buscar?categoria=eletricista">Eletricistas</a><a href="/buscar?categoria=diarista">Diaristas</a><a href="/buscar?categoria=pintor">Pintores</a><a href="/buscar?categoria=pedreiro">Pedreiros</a></nav></div>';
    echo '<div class="footer-col"><h3>Para profissionais</h3><nav class="footer-links"><a href="/admin/">Anunciar serviço</a><a href="/admin/criar">Criar loja gratuita</a><a href="https://wa.me/5579999999999" target="_blank" rel="noopener">Falar no WhatsApp</a></nav></div>';
    echo '<div class="footer-col"><h3>Institucional</h3><nav class="footer-links"><a href="/">Início</a><a href="/buscar">Buscar</a><a href="#">Política de privacidade</a><a href="#">Termos de uso</a></nav></div>';
    echo '</div></div>';
    echo '<div class="footer-bottom"><div class="container footer-bottom-inner"><p>&copy; ' . $year . ' Conectado em Sergipe. Todos os direitos reservados.</p><p class="footer-location">📍 Sergipe — Brasil</p></div></div>';
    echo '</footer>';
    echo '<script src="/assets/js/script.js" defer></script>';
    echo '</body></html>';
}
