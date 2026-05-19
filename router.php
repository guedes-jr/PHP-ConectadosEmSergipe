<?php

$uri = $_SERVER['REQUEST_URI'];
$path = parse_url($uri, PHP_URL_PATH);
$path = rtrim($path, '/');

if ($path === '' || $path === '/') {
    require __DIR__ . '/public/index.php';
    return;
}

if ($path === '/sitemap.xml') {
    header('Content-Type: application/xml; charset=utf-8');
    require __DIR__ . '/public/sitemap.php';
    return;
}

if (str_starts_with($path, '/categoria/')) {
    $slug = substr($path, strlen('/categoria/'));
    header("Location: /buscar?categoria=$slug");
    return;
}

if (str_starts_with($path, '/anuncio/')) {
    $_GET['slug'] = substr($path, strlen('/anuncio/'));
    require __DIR__ . '/public/anuncio.php';
    return;
}

if ($path === '/buscar') {
    require __DIR__ . '/public/buscar.php';
    return;
}

if ($path === '/termos') {
    require __DIR__ . '/public/termos.php';
    return;
}

if ($path === '/privacidade') {
    require __DIR__ . '/public/privacidade.php';
    return;
}

if ($path === '/admin' || $path === '/admin/') {
    require __DIR__ . '/admin/index.php';
    return;
}

if (str_starts_with($path, '/admin/')) {
    $adminPath = str_replace('/admin', '', $path);
    if ($adminPath === '/logout') {
        require __DIR__ . '/admin/logout.php';
        return;
    }
    if (str_starts_with($adminPath, '/editar/')) {
        $_GET['id'] = substr($adminPath, strlen('/editar/'));
        require __DIR__ . '/admin/editar.php';
        return;
    }
    if (str_starts_with($adminPath, '/editar_cliente/')) {
        $_GET['id'] = substr($adminPath, strlen('/editar_cliente/'));
        require __DIR__ . '/admin/editar_cliente.php';
        return;
    }
    if ($adminPath === '/excluir' || $adminPath === '/excluir/') {
        require __DIR__ . '/admin/excluir.php';
        return;
    }
    if (str_starts_with($adminPath, '/excluir/')) {
        $_GET['id'] = substr($adminPath, strlen('/excluir/'));
        require __DIR__ . '/admin/excluir.php';
        return;
    }
    if ($adminPath === '/clientes' || $adminPath === '/clientes/') {
        require __DIR__ . '/admin/clientes.php';
        return;
    }
    if ($adminPath === '/categorias' || $adminPath === '/categorias/') {
        require __DIR__ . '/admin/categorias.php';
        return;
    }
    if ($adminPath === '/anuncios' || $adminPath === '/anuncios/') {
        // Como o dashboard já lista os anúncios, podemos apontar para ele 
        // ou para uma página de listagem específica se houver.
        require __DIR__ . '/admin/dashboard.php';
        return;
    }
    if ($adminPath === '/configuracoes' || $adminPath === '/configuracoes/') {
        require __DIR__ . '/admin/configuracoes.php';
        return;
    }
    if ($adminPath === '/dashboard' || $adminPath === '/dashboard/') {
        require __DIR__ . '/admin/dashboard.php';
        return;
    }
    if ($adminPath === '/criar' || $adminPath === '/criar/') {
        require __DIR__ . '/admin/criar.php';
        return;
    }
    if ($adminPath === '/criar_cliente' || $adminPath === '/criar_cliente/') {
        require __DIR__ . '/admin/criar_cliente.php';
        return;
    }
}

http_response_code(404);
echo '404 - Not Found';