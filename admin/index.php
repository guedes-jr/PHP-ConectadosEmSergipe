<?php
$requestPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
if ($requestPath !== '/admin' && $requestPath !== '/admin/') {
    // Workaround for PHP built-in server routing quirk where it serves index.php for missing files in a directory
    require __DIR__ . '/../router.php';
    return;
}

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
    <title>Entrar — Conectado em Sergipe</title>
    <link rel="stylesheet" href="/assets/css/style.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            display: flex;
            min-height: 100vh;
            background-color: var(--background);
            color: var(--foreground);
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            overflow-x: hidden;
        }

        .login-layout {
            display: flex;
            width: 100vw;
            height: 100vh;
        }

        .login-left {
            flex: 1;
            background: linear-gradient(135deg, #0f172a 0%, #0c2b5e 100%);
            color: white;
            padding: 4rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .login-right {
            flex: 1;
            background-color: var(--background);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 3rem;
            position: relative;
        }

        .login-brand {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-weight: 700;
            font-size: 1.25rem;
            color: white;
            text-decoration: none;
        }

        .login-brand .logo-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            background: #2563eb;
            border-radius: 8px;
            color: white;
        }

        .login-brand .logo-text {
            color: white;
        }

        .login-brand .logo-text span {
            color: #60a5fa;
            font-weight: 400;
        }

        .login-content {
            max-width: 500px;
        }

        .login-subtitle {
            font-size: 0.75rem;
            font-weight: 800;
            color: #60a5fa;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            margin-bottom: 1.5rem;
            display: block;
        }

        .login-title {
            font-size: clamp(2rem, 3.5vw, 3rem);
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: 1.5rem;
        }

        .login-desc {
            font-size: 1.125rem;
            color: #94a3b8;
            line-height: 1.6;
            max-width: 420px;
        }

        .login-footer {
            font-size: 0.875rem;
            color: #64748b;
        }

        .login-form-container {
            width: 100%;
            max-width: 400px;
        }

        .login-form-header {
            margin-bottom: 2.5rem;
        }

        .login-form-subtitle {
            font-size: 0.75rem;
            font-weight: 800;
            color: var(--primary);
            letter-spacing: 0.1em;
            text-transform: uppercase;
            margin-bottom: 1rem;
            display: block;
        }

        .login-form-title {
            font-size: 2rem;
            font-weight: 800;
            margin-bottom: 0.75rem;
            color: var(--foreground);
        }

        .login-form-desc {
            color: var(--muted-foreground);
            font-size: 0.9375rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--muted-foreground);
            margin-bottom: 0.5rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .input-with-icon {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-with-icon svg {
            position: absolute;
            left: 1rem;
            color: var(--muted-foreground);
        }

        .input-with-icon input {
            width: 100%;
            padding: 0.875rem 1rem 0.875rem 3rem;
            background: transparent;
            border: 1px solid var(--border);
            border-radius: 0.75rem;
            color: var(--foreground);
            font-size: 1rem;
            transition: all 0.2s;
        }

        .input-with-icon input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .btn-submit {
            width: 100%;
            padding: 1rem;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 0.75rem;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            margin-top: 1rem;
        }

        .btn-submit:hover {
            background: var(--primary-hover);
            transform: translateY(-1px);
        }

        .alert-error {
            background: rgba(239, 68, 68, 0.1);
            color: #ef4444;
            padding: 1rem;
            border-radius: 0.5rem;
            margin-bottom: 1.5rem;
            font-size: 0.875rem;
            border: 1px solid rgba(239, 68, 68, 0.2);
        }

        @media (max-width: 992px) {
            .login-left {
                display: none;
            }
            .login-right {
                padding: 2rem;
            }
        }
        
        .theme-toggle-container {
            position: absolute;
            bottom: 2rem;
            right: 2rem;
            z-index: 50;
        }
    </style>
</head>
<body>
    <div class="login-layout">
        <div class="login-left">
            <a href="/" class="login-brand">
                <div class="logo-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 3-1.912 5.813a2 2 0 0 1-1.275 1.275L3 12l5.813 1.912a2 2 0 0 1 1.275 1.275L12 21l1.912-5.813a2 2 0 0 1 1.275-1.275L21 12l-5.813-1.912a2 2 0 0 1-1.275-1.275L12 3Z"/></svg>
                </div>
                <div class="logo-text">Conectado <span>em Sergipe</span></div>
            </a>
            
            <div class="login-content">
                <span class="login-subtitle">PAINEL ADMINISTRATIVO</span>
                <h1 class="login-title">Gerencie a vitrine de prestadores de Sergipe num só lugar.</h1>
                <p class="login-desc">Cadastre novos profissionais, aprove anúncios e mantenha as lojas sempre atualizadas para os clientes da região.</p>
            </div>
            
            <div class="login-footer">
                &copy; <?php echo date('Y'); ?> Conectado em Sergipe
            </div>
        </div>
        
        <div class="login-right">
            <div class="login-form-container">
                <div class="login-form-header">
                    <span class="login-form-subtitle">ACESSO RESTRITO</span>
                    <h2 class="login-form-title">Entrar como administrador</h2>
                    <p class="login-form-desc">Apenas administradores podem cadastrar prestadores de serviço.</p>
                </div>

                <form method="post" action="/admin/">
                    <?php echo csrf_field(); ?>
                    
                    <?php if ($error !== ''): ?>
                        <div class="alert-error"><?php echo e($error); ?></div>
                    <?php endif; ?>
                    
                    <div class="form-group">
                        <label for="username">Usuário ou E-mail</label>
                        <div class="input-with-icon">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                            <input type="text" id="username" name="username" placeholder="admin@conectadoemsergipe.com.br" required autofocus>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="password">Senha</label>
                        <div class="input-with-icon">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                            <input type="password" id="password" name="password" placeholder="••••••••" required>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn-submit">Entrar no painel</button>
                    
                    <p style="text-align: center; margin-top: 1.5rem; font-size: 0.75rem; color: var(--muted-foreground);">
                        Demonstração com dados mockados — qualquer senha 4+ caracteres funciona.
                    </p>
                </form>
            </div>
            
            <div class="theme-toggle-container">
                <button class="theme-toggle-btn" id="themeToggleBtn" aria-label="Mudar tema" aria-haspopup="true" aria-expanded="false">
                    <svg class="icon-sun" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="5"></circle><line x1="12" y1="1" x2="12" y2="3"></line><line x1="12" y1="21" x2="12" y2="23"></line><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line><line x1="1" y1="12" x2="3" y2="12"></line><line x1="21" y1="12" x2="23" y2="12"></line><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line></svg>
                    <svg class="icon-moon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:none;"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path></svg>
                    <svg class="icon-system" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:none;"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect><line x1="8" y1="21" x2="16" y2="21"></line><line x1="12" y1="17" x2="12" y2="21"></line></svg>
                </button>
                <div class="theme-dropdown" id="themeDropdown">
                    <button class="theme-option active" data-theme-value="light"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="5"></circle><line x1="12" y1="1" x2="12" y2="3"></line><line x1="12" y1="21" x2="12" y2="23"></line><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line><line x1="1" y1="12" x2="3" y2="12"></line><line x1="21" y1="12" x2="23" y2="12"></line><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line></svg> Claro</button>
                    <button class="theme-option" data-theme-value="dark"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path></svg> Escuro</button>
                    <button class="theme-option" data-theme-value="system"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect><line x1="8" y1="21" x2="16" y2="21"></line><line x1="12" y1="17" x2="12" y2="21"></line></svg> Sistema</button>
                </div>
            </div>
        </div>
    </div>
    <script src="/assets/js/script.js" defer></script>
</body>
</html>