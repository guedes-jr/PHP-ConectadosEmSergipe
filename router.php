<?php

$uri = $_SERVER['REQUEST_URI'];
$path = parse_url($uri, PHP_URL_PATH);
$path = rtrim($path, '/');

if ($path === '' || $path === '/') {
    require __DIR__ . '/public/index.php';
    return;
}

if (str_starts_with($path, '/categoria/')) {
    $_GET['slug'] = substr($path, strlen('/categoria/'));
    require __DIR__ . '/public/categoria.php';
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
    if (str_starts_with($adminPath, '/excluir/')) {
        $_GET['id'] = substr($adminPath, strlen('/excluir/'));
        require __DIR__ . '/admin/excluir.php';
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
}

http_response_code(404);
echo '404 - Not Found';