<?php
function render_admin_header(string $title, string $activePage = 'dashboard', string $headerButtons = ''): void {
    $username = $_SESSION['admin_username'] ?? 'admin@conectado.com';
    ?>
<!DOCTYPE html>
<html lang="pt-BR" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($title); ?> — Painel Administrativo</title>
    <script>
        (function() {
            const savedTheme = localStorage.getItem('theme') || 'system';
            const html = document.documentElement;
            if (savedTheme === 'system') {
                const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                html.setAttribute('data-theme', systemPrefersDark ? 'dark' : 'light');
            } else {
                html.setAttribute('data-theme', savedTheme);
            }
        })();
    </script>
    <link rel="stylesheet" href="/assets/css/style.css">
    <style>
        body { background-color: var(--muted-bg); display: flex; height: 100vh; margin: 0; font-family: 'Inter', system-ui, sans-serif; color: var(--foreground); overflow: hidden; }
        .admin-layout { display: flex; width: 100%; height: 100%; overflow: hidden; }
        .sidebar { 
            width: 280px; 
            background-color: var(--site-footer-bg); 
            color: white; 
            display: flex; 
            flex-direction: column; 
            flex-shrink: 0; 
            position: relative; 
            z-index: 20;
            box-shadow: 4px 0 24px rgba(0,0,0,0.1);
            transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .sidebar.collapsed { width: 80px; }
        .sidebar.collapsed .sidebar-header span,
        .sidebar.collapsed .sidebar-section,
        .sidebar.collapsed .sidebar-item span,
        .sidebar.collapsed .sidebar-user,
        .sidebar.collapsed .sidebar-logout span { display: none; }
        .sidebar.collapsed .sidebar-header { justify-content: center; padding: 1.5rem 0; }
        .sidebar.collapsed .sidebar-item { justify-content: center; padding: 0.875rem 0; }
        .sidebar.collapsed .sidebar-logout { justify-content: center; padding: 0.875rem 0; }

        .main-content { 
            flex: 1; 
            overflow-y: auto; 
            display: flex; 
            flex-direction: column; 
            position: relative;
            scroll-behavior: smooth;
        }
        .top-header { 
            padding: 1.25rem 2.5rem; 
            background: var(--card); 
            border-bottom: 1px solid var(--border); 
            display: flex; 
            align-items: center; 
            justify-content: space-between; 
            position: sticky; 
            top: 0; 
            z-index: 10;
            backdrop-filter: blur(10px);
        }
        .top-header h1 { font-size: 1.25rem; font-weight: 700; margin: 0; letter-spacing: -0.02em; }
        
        .sidebar-header { 
            padding: 2rem 1.5rem; 
            display: flex; 
            align-items: center; 
            gap: 1rem; 
            font-size: 1.15rem; 
            font-weight: 800; 
            border-bottom: 1px solid rgba(255,255,255,0.05);
            letter-spacing: -0.03em;
            position: relative;
        }
        .sidebar-toggle {
            position: absolute;
            right: -12px;
            top: 2.2rem;
            width: 24px;
            height: 24px;
            background: var(--primary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            cursor: pointer;
            border: 2px solid var(--site-footer-bg);
            z-index: 30;
            transition: transform 0.3s;
        }
        .sidebar.collapsed .sidebar-toggle { transform: rotate(180deg); }
        .sidebar-header .logo-icon { 
            width: 36px; 
            height: 36px; 
            background: linear-gradient(135deg, var(--primary), #4f46e5); 
            border-radius: 10px; 
            display: flex; 
            align-items: center; 
            justify-content: center;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
        }
        .sidebar-header span { color: var(--primary); }
        .sidebar-menu { padding: 2rem 1rem; flex: 1; display: flex; flex-direction: column; gap: 0.25rem; }
        .sidebar-section { 
            font-size: 0.65rem; 
            font-weight: 800; 
            color: #64748b; 
            text-transform: uppercase; 
            letter-spacing: 0.1em; 
            padding: 1.5rem 1.25rem 0.5rem; 
        }
        .sidebar-item { 
            display: flex; 
            align-items: center; 
            gap: 0.875rem; 
            padding: 0.875rem 1.25rem; 
            color: #94a3b8; 
            text-decoration: none; 
            border-radius: 0.75rem; 
            font-size: 0.9375rem; 
            font-weight: 500;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .sidebar-item:hover { 
            background-color: rgba(255,255,255,0.08); 
            color: white; 
            transform: translateX(4px);
        }
        .sidebar-item.active { 
            background: linear-gradient(90deg, var(--primary) 0%, #4f46e5 100%); 
            color: white; 
            box-shadow: 0 8px 16px rgba(37, 99, 235, 0.2);
            font-weight: 600;
        }
        
        .sidebar-footer { padding: 2rem 1.5rem; border-top: 1px solid rgba(255,255,255,0.05); background: rgba(0,0,0,0.1); }
        .sidebar-logout { 
            display: flex; 
            align-items: center; 
            gap: 0.75rem; 
            color: #f87171; 
            text-decoration: none; 
            font-size: 0.9375rem; 
            font-weight: 600; 
            padding: 0.875rem 1.25rem; 
            border-radius: 0.75rem; 
            transition: 0.2s; 
        }
        .sidebar-logout:hover { background: rgba(239, 68, 68, 0.1); transform: translateX(4px); }
        .sidebar-user-role { font-size: 0.65rem; color: #64748b; text-transform: uppercase; font-weight: 800; letter-spacing: 0.05em; }
        .sidebar-user-email { font-size: 0.875rem; color: white; font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

        .dashboard-container { padding: 2.5rem 3.5rem; max-width: 1600px; margin: 0 auto; width: 100%; }

        /* Floating Premium Theme Toggle (As in User Image) */
        .theme-toggle-container { position: fixed; bottom: 2rem; right: 2rem; z-index: 1000; }
        .theme-toggle-btn { 
            width: 56px; height: 56px; border-radius: 50%; background: #3b82f6; color: white; 
            border: none; display: flex; align-items: center; justify-content: center; cursor: pointer; 
            box-shadow: 0 10px 25px -5px rgba(59, 130, 246, 0.5); transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .theme-toggle-btn:hover { transform: scale(1.1) translateY(-2px); box-shadow: 0 15px 30px -5px rgba(59, 130, 246, 0.6); }
        
        .theme-dropdown { 
            position: absolute; bottom: calc(100% + 1rem); right: 0; background: #1e293b; 
            border: 1px solid rgba(255,255,255,0.1); border-radius: 1.25rem; padding: 0.75rem; 
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.3); display: flex; flex-direction: column; gap: 0.5rem; 
            opacity: 0; visibility: hidden; transform: translateY(10px) scale(0.95); 
            transform-origin: bottom right; transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1); min-width: 180px; 
        }
        .theme-toggle-container.active .theme-dropdown { opacity: 1; visibility: visible; transform: translateY(0) scale(1); }
        
        .theme-option { 
            display: flex; align-items: center; gap: 1rem; width: 100%; padding: 0.875rem 1.25rem; 
            border: none; background: transparent; color: #94a3b8; font-size: 0.9375rem; 
            border-radius: 0.75rem; cursor: pointer; text-align: left; transition: all 0.2s; 
        }
        .theme-option:hover { background: rgba(255,255,255,0.05); color: white; }
        .theme-option.active { background: rgba(59, 130, 246, 0.1); color: #3b82f6; font-weight: 600; }
        .theme-option svg { width: 18px; height: 18px; opacity: 0.8; }
        .theme-option.active svg { opacity: 1; }
    </style>
</head>
<body>
<div class="admin-layout">
    <aside class="sidebar" id="mainSidebar">
        <div class="sidebar-toggle" onclick="toggleSidebar()">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="15 18 9 12 15 6"></polyline></svg>
        </div>
        <div class="sidebar-header">
            <div class="logo-icon">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m12 3-1.912 5.813a2 2 0 0 1-1.275 1.275L3 12l5.813 1.912a2 2 0 0 1 1.275 1.275L12 21l1.912-5.813a2 2 0 0 1 1.275-1.275L21 12l-5.813-1.912a2 2 0 0 1-1.275-1.275L12 3Z"/></svg>
            </div>
            <span>Painel admin</span>
        </div>
        
        <nav class="sidebar-menu">
            <span class="sidebar-section">Geral</span>
            <a href="/admin/dashboard" class="sidebar-item <?php echo $activePage === 'dashboard' ? 'active' : ''; ?>">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="9" rx="1"></rect><rect x="14" y="3" width="7" height="5" rx="1"></rect><rect x="14" y="12" width="7" height="9" rx="1"></rect><rect x="3" y="16" width="7" height="5" rx="1"></rect></svg>
                <span>Dashboard</span>
            </a>
            
            <span class="sidebar-section">Gestão</span>
            <a href="/admin/clientes" class="sidebar-item <?php echo $activePage === 'clientes' ? 'active' : ''; ?>">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                <span>Clientes</span>
            </a>
            <a href="/admin/categorias" class="sidebar-item <?php echo $activePage === 'categorias' ? 'active' : ''; ?>">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7" rx="1"></rect><rect x="14" y="3" width="7" height="7" rx="1"></rect><rect x="14" y="14" width="7" height="7" rx="1"></rect><rect x="3" y="14" width="7" height="7" rx="1"></rect></svg>
                <span>Categorias</span>
            </a>
            <a href="/admin/anuncios" class="sidebar-item <?php echo $activePage === 'anuncios' ? 'active' : ''; ?>">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                <span>Anúncios</span>
            </a>
            <a href="/admin/configuracoes" class="sidebar-item <?php echo $activePage === 'configuracoes' ? 'active' : ''; ?>">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
                <span>Ajustes</span>
            </a>
        </nav>

        <div class="sidebar-footer">
            <a href="/admin/logout" class="sidebar-logout">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                <span>Sair</span>
            </a>
            <div class="sidebar-user">
                <div class="sidebar-user-role">Logado como</div>
                <div class="sidebar-user-email"><?php echo e($username); ?></div>
            </div>
        </div>
    </aside>
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('mainSidebar');
            sidebar.classList.toggle('collapsed');
            localStorage.setItem('admin-sidebar-collapsed', sidebar.classList.contains('collapsed'));
        }
        if (localStorage.getItem('admin-sidebar-collapsed') === 'true') {
            document.getElementById('mainSidebar').classList.add('collapsed');
        }
    </script>
    <main class="main-content">
        <header class="top-header">
            <h1><?php echo e($title); ?></h1>
            <div style="display:flex; align-items:center; gap:1.5rem;">
                <?php echo $headerButtons; ?>
            </div>
        </header>
        <div class="dashboard-container">
    <?php
}

function render_admin_footer(): void {
    ?>
        </div>
        
        <!-- Floating Premium Theme Toggle (As in User Image) -->
        <div class="theme-toggle-container">
            <button class="theme-toggle-btn" id="themeToggleBtn" aria-label="Mudar tema" aria-haspopup="true" aria-expanded="false">
                <svg class="icon-sun" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="5"></circle><line x1="12" y1="1" x2="12" y2="3"></line><line x1="12" y1="21" x2="12" y2="23"></line><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line><line x1="1" y1="12" x2="3" y2="12"></line><line x1="21" y1="12" x2="23" y2="12"></line><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line></svg>
                <svg class="icon-moon" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display:none;"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path></svg>
                <svg class="icon-system" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display:none;"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect><line x1="8" y1="21" x2="16" y2="21"></line><line x1="12" y1="17" x2="12" y2="21"></line></svg>
            </button>
            <div class="theme-dropdown" id="themeDropdown">
                <button class="theme-option active" data-theme-value="light">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="5"></circle><line x1="12" y1="1" x2="12" y2="3"></line><line x1="12" y1="21" x2="12" y2="23"></line><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line><line x1="1" y1="12" x2="3" y2="12"></line><line x1="21" y1="12" x2="23" y2="12"></line><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line></svg>
                    Claro
                </button>
                <button class="theme-option" data-theme-value="dark">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path></svg>
                    Escuro
                </button>
                <button class="theme-option" data-theme-value="system">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect><line x1="8" y1="21" x2="16" y2="21"></line><line x1="12" y1="17" x2="12" y2="21"></line></svg>
                    Sistema
                </button>
            </div>
        </div>
    </main>
</div>
<script src="https://unpkg.com/lucide@latest"></script>
<script src="/assets/js/script.js" defer></script>
<script>lucide.createIcons();</script>
</body>
</html>
<?php
}
