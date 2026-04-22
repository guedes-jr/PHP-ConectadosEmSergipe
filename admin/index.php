<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

if (is_logged_in()) {
    redirect('/admin/dashboard');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validate_csrf()) {
        $error = 'Token inválido. Tente novamente.';
    } else {
        $username = trim((string)($_POST['username'] ?? ''));
    $password = (string)($_POST['password'] ?? '');

    $stmt = $pdo->prepare('SELECT id, username, password FROM usuarios WHERE username = :username AND is_active = 1 LIMIT 1');
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        login_admin((int)$user['id'], (string)$user['username']);
        redirect('/admin/dashboard');
    }

    $error = 'Usuário ou senha inválidos.';
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin</title>
    <link rel="stylesheet" href="/assets/css/admin.css">
</head>
<body class="admin-auth-body">
    <form class="admin-auth-card" method="post">
        <?php echo csrf_field(); ?>
        <h1>Entrar</h1>
        <?php if ($error !== ''): ?>
            <p class="alert-error"><?php echo e($error); ?></p>
        <?php endif; ?>
        <label>Usuário</label>
        <input type="text" name="username" required>
        <label>Senha</label>
        <input type="password" name="password" required>
        <button type="submit">Acessar</button>
    </form>
</body>
</html>