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
    echo '</head>';
    echo '<body>';
    echo '<header class="site-header">';
    echo '<div class="container">';
    echo '<a href="/" class="brand">Guia Local</a>';
    echo '<nav><a href="/">Início</a><a href="/buscar">Buscar</a><a href="/admin/">Admin</a></nav>';
    echo '</div>';
    echo '</header>';
    echo '<main class="container">';
}

function render_footer(): void
{
    echo '</main>';
    echo '<footer class="site-footer"><div class="container">&copy; ' . date('Y') . ' Guia Local</div></footer>';
    echo '<script src="/assets/js/app.js" defer></script>';
    echo '</body></html>';
}
