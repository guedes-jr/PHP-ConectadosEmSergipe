<?php

declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_name('ADMIN_SESS');
    session_start();
}

function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="csrf_token" value="' . e(csrf_token()) . '">';
}

function validate_csrf(): bool
{
    $token = $_POST['csrf_token'] ?? $_GET['csrf_token'] ?? '';
    return hash_equals($_SESSION['csrf_token'] ?? '', $token);
}

function is_logged_in(): bool
{
    if (session_status() === PHP_SESSION_NONE) {
        return false;
    }
    return isset($_SESSION['admin_id']) && is_numeric($_SESSION['admin_id']);
}

function require_admin(): void
{
    if (!is_logged_in()) {
        header('Location: /admin');
        exit;
    }
}

function login_admin(int $id, string $username): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_name('ADMIN_SESS');
        session_start();
    }
    session_regenerate_id(true);
    $_SESSION['admin_id'] = $id;
    $_SESSION['admin_username'] = $username;
}

function logout_admin(): void
{
    if (session_status() !== PHP_SESSION_NONE) {
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
        }
        session_destroy();
    }
}
