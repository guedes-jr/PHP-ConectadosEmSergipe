<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

require_admin();

// Queries reais e mocked para o dashboard
$stmtAds = $pdo->query(
    'SELECT a.id, a.titulo, a.cidade, a.destaque, a.status, a.created_at, c.nome AS categoria_nome, a.nota
     FROM anuncios a
     INNER JOIN categorias c ON c.id = a.categoria_id
     ORDER BY a.created_at DESC
     LIMIT 10'
);
$recentAds = $stmtAds->fetchAll();

$totalAds = (int)$pdo->query('SELECT COUNT(*) FROM anuncios WHERE status = \'ativo\'')->fetchColumn();
$totalClients = 1284;
$totalViews = '32.5k';
$totalContacts = 892;

$topCats = [
    ['name' => 'Eletricista', 'perc' => 82],
    ['name' => 'Diarista', 'perc' => 71],
    ['name' => 'Confeitaria', 'perc' => 64],
    ['name' => 'Cabeleireiro', 'perc' => 55],
    ['name' => 'Encanador', 'perc' => 48],
];

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard — Admin</title>
    <link rel="stylesheet" href="/assets/css/style.css">
    <style>
        body {
            margin: 0;
            background-color: var(--background);
            color: var(--foreground);
            font-family: 'Inter', sans-serif;
            overflow-x: hidden;
            display: flex;
            height: 100vh;
        }

        .admin-layout {
            display: flex;
            width: 100%;
            height: 100%;
        }

        /* Sidebar */
        .sidebar {
            width: 260px;
            background-color: #0f172a;
            color: white;
            display: flex;
            flex-direction: column;
            border-right: 1px solid rgba(255, 255, 255, 0.1);
            flex-shrink: 0;
        }

        .sidebar-header {
            padding: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 1.25rem;
            font-weight: 700;
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }

        .sidebar-header .logo-icon {
            width: 32px;
            height: 32px;
            background: #2563eb;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .sidebar-header span {
            color: #60a5fa;
            font-weight: 400;
        }

        .sidebar-menu {
            padding: 1.5rem 1rem;
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .sidebar-section {
            font-size: 0.75rem;
            font-weight: 700;
            color: #64748b;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            margin-bottom: 0.5rem;
            padding-left: 0.75rem;
        }

        .sidebar-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1rem;
            color: #cbd5e1;
            text-decoration: none;
            border-radius: 0.5rem;
            font-size: 0.9375rem;
            font-weight: 500;
            transition: all 0.2s;
        }

        .sidebar-item:hover {
            background-color: rgba(255, 255, 255, 0.05);
            color: white;
        }

        .sidebar-item.active {
            background-color: #2563eb;
            color: white;
        }

        .sidebar-item svg {
            color: inherit;
        }

        .sidebar-footer {
            padding: 1.5rem;
            border-top: 1px solid rgba(255,255,255,0.05);
            margin-top: auto;
        }
        
        .sidebar-logout {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            color: #94a3b8;
            text-decoration: none;
            font-size: 0.9375rem;
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            transition: all 0.2s;
            margin-bottom: 1rem;
        }

        .sidebar-logout:hover {
            color: white;
            background-color: rgba(255, 255, 255, 0.05);
        }

        .sidebar-user {
            background-color: rgba(255, 255, 255, 0.05);
            padding: 1rem;
            border-radius: 0.75rem;
        }

        .sidebar-user-role {
            font-size: 0.75rem;
            color: #94a3b8;
            margin-bottom: 0.25rem;
        }

        .sidebar-user-email {
            font-size: 0.875rem;
            color: white;
            font-weight: 600;
            word-break: break-all;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
            background-color: var(--secondary); /* slight gray in light mode */
        }

        .top-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1.5rem 2rem;
            background-color: var(--background);
            border-bottom: 1px solid var(--border);
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .top-header h1 {
            font-size: 1.5rem;
            font-weight: 700;
            margin: 0;
            color: var(--foreground);
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .search-bar {
            position: relative;
        }

        .search-bar svg {
            position: absolute;
            left: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--muted-foreground);
        }

        .search-bar input {
            background-color: var(--secondary);
            border: 1px solid var(--border);
            border-radius: 2rem;
            padding: 0.5rem 1rem 0.5rem 2.5rem;
            font-size: 0.875rem;
            color: var(--foreground);
            width: 250px;
            transition: all 0.2s;
        }

        .search-bar input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.1);
        }

        .icon-btn {
            background: transparent;
            border: 1px solid var(--border);
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--foreground);
            cursor: pointer;
            transition: all 0.2s;
        }

        .icon-btn:hover {
            background: var(--secondary);
        }

        .avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: var(--primary);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 1rem;
        }

        .dashboard-container {
            padding: 2rem;
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
            max-width: 1400px;
            margin: 0 auto;
            width: 100%;
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 1.5rem;
        }

        .stat-card {
            background-color: var(--card);
            border: 1px solid var(--border);
            border-radius: 1rem;
            padding: 1.5rem;
            display: flex;
            flex-direction: column;
            position: relative;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
        }

        .stat-icon.blue { background-color: rgba(37, 99, 235, 0.1); color: #2563eb; }
        .stat-icon.green { background-color: rgba(16, 185, 129, 0.1); color: #10b981; }
        .stat-icon.purple { background-color: rgba(139, 92, 246, 0.1); color: #8b5cf6; }
        .stat-icon.orange { background-color: rgba(245, 158, 11, 0.1); color: #f59e0b; }

        .stat-value {
            font-size: 2rem;
            font-weight: 800;
            color: var(--foreground);
            margin-bottom: 0.25rem;
        }

        .stat-label {
            font-size: 0.875rem;
            color: var(--muted-foreground);
        }

        .stat-badge {
            position: absolute;
            top: 1.5rem;
            right: 1.5rem;
            padding: 0.25rem 0.5rem;
            border-radius: 2rem;
            font-size: 0.75rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        .stat-badge.up { background-color: rgba(16, 185, 129, 0.1); color: #10b981; }
        .stat-badge.down { background-color: rgba(239, 68, 68, 0.1); color: #ef4444; }

        /* Middle Section */
        .middle-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 1.5rem;
        }

        .panel {
            background-color: var(--card);
            border: 1px solid var(--border);
            border-radius: 1rem;
            padding: 1.5rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }

        .panel-header {
            margin-bottom: 1.5rem;
        }

        .panel-subtitle {
            font-size: 0.75rem;
            font-weight: 700;
            color: var(--primary);
            text-transform: uppercase;
            letter-spacing: 0.1em;
            margin-bottom: 0.5rem;
            display: block;
        }

        .panel-title {
            font-size: 1.25rem;
            font-weight: 800;
            color: var(--foreground);
            margin: 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        /* Mock Chart */
        .chart-mock {
            height: 200px;
            display: flex;
            align-items: flex-end;
            gap: 4px;
            margin-top: 2rem;
            padding-bottom: 1.5rem;
            border-bottom: 1px solid var(--border);
            position: relative;
        }

        .chart-bar {
            flex: 1;
            background: linear-gradient(180deg, var(--primary) 0%, rgba(37, 99, 235, 0.2) 100%);
            border-radius: 4px 4px 0 0;
            transition: all 0.3s;
        }
        
        .chart-bar:hover {
            opacity: 0.8;
        }

        .chart-labels {
            display: flex;
            justify-content: space-between;
            margin-top: 0.5rem;
            font-size: 0.75rem;
            color: var(--muted-foreground);
        }

        /* Categories List */
        .cat-list {
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
        }

        .cat-item {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .cat-info {
            display: flex;
            justify-content: space-between;
            font-size: 0.875rem;
            color: var(--foreground);
            font-weight: 500;
        }

        .cat-progress-bg {
            height: 8px;
            background-color: var(--secondary);
            border-radius: 4px;
            overflow: hidden;
        }

        .cat-progress-fill {
            height: 100%;
            background-color: var(--primary);
            border-radius: 4px;
        }

        /* Table Area */
        .table-panel {
            padding: 0;
            overflow: hidden;
        }

        .table-panel .panel-header {
            padding: 1.5rem 1.5rem 0 1.5rem;
        }

        .table-responsive {
            width: 100%;
            overflow-x: auto;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }

        .data-table th {
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--muted-foreground);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 1rem 1.5rem;
            border-bottom: 1px solid var(--border);
        }

        .data-table td {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid var(--border);
            color: var(--foreground);
            font-size: 0.875rem;
        }

        .data-table tr:last-child td {
            border-bottom: none;
        }

        .badge-status {
            padding: 0.25rem 0.75rem;
            border-radius: 2rem;
            font-size: 0.75rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
        }

        .badge-status.ativo { background-color: rgba(16, 185, 129, 0.1); color: #10b981; }
        .badge-status.inativo { background-color: rgba(239, 68, 68, 0.1); color: #ef4444; }

        .btn-action {
            padding: 0.35rem 0.75rem;
            border-radius: 0.5rem;
            font-size: 0.75rem;
            font-weight: 600;
            text-decoration: none;
            background-color: var(--secondary);
            color: var(--foreground);
            transition: all 0.2s;
            border: 1px solid var(--border);
        }

        .btn-action:hover {
            background-color: var(--primary);
            color: white;
            border-color: var(--primary);
        }
        
        .table-actions {
            display: flex;
            gap: 0.5rem;
        }
        
        .btn-create {
            background: var(--primary);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.2s;
        }

        .btn-create:hover {
            background: var(--primary-hover);
        }

        @media (max-width: 1024px) {
            .middle-grid { grid-template-columns: 1fr; }
            .sidebar { position: absolute; transform: translateX(-100%); z-index: 50; height: 100%; transition: 0.3s; }
            .sidebar.open { transform: translateX(0); }
        }
    </style>
</head>
<body>

<div class="admin-layout">
    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="logo-icon">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 3-1.912 5.813a2 2 0 0 1-1.275 1.275L3 12l5.813 1.912a2 2 0 0 1 1.275 1.275L12 21l1.912-5.813a2 2 0 0 1 1.275-1.275L21 12l-5.813-1.912a2 2 0 0 1-1.275-1.275L12 3Z"/></svg>
            </div>
            Painel <span>admin</span>
        </div>
        
        <nav class="sidebar-menu">
            <span class="sidebar-section">Gestão</span>
            <a href="/admin/dashboard" class="sidebar-item active">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="9" rx="1"></rect><rect x="14" y="3" width="7" height="5" rx="1"></rect><rect x="14" y="12" width="7" height="9" rx="1"></rect><rect x="3" y="16" width="7" height="5" rx="1"></rect></svg>
                Dashboard
            </a>
            <a href="#" class="sidebar-item">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M22 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                Clientes
            </a>
            <a href="/admin/criar" class="sidebar-item">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                Anúncios
            </a>
        </nav>

        <div class="sidebar-footer">
            <a href="/admin/logout" class="sidebar-logout">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                Sair
            </a>
            <div class="sidebar-user">
                <div class="sidebar-user-role">Logado como</div>
                <div class="sidebar-user-email"><?php echo e($_SESSION['admin_username'] ?? 'admin@conectado.com'); ?></div>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <header class="top-header">
            <h1>Dashboard</h1>
            <div class="header-actions">
                <div class="search-bar">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                    <input type="text" placeholder="Buscar no painel...">
                </div>
                
                <!-- Theme Toggle Reutilizado -->
                <button class="icon-btn theme-toggle-btn" id="themeToggleBtn" aria-label="Mudar tema">
                    <svg class="icon-sun" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="5"></circle><line x1="12" y1="1" x2="12" y2="3"></line><line x1="12" y1="21" x2="12" y2="23"></line><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line><line x1="1" y1="12" x2="3" y2="12"></line><line x1="21" y1="12" x2="23" y2="12"></line><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line></svg>
                    <svg class="icon-moon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display:none;"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path></svg>
                </button>

                <div class="avatar">
                    A
                </div>
            </div>
        </header>

        <div class="dashboard-container">
            
            <!-- 4 Stats Cards -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-badge up"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline><polyline points="17 6 23 6 23 12"></polyline></svg> +12%</div>
                    <div class="stat-icon blue">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                    </div>
                    <div class="stat-value"><?php echo $totalAds; ?></div>
                    <div class="stat-label">Anúncios ativos</div>
                </div>

                <div class="stat-card">
                    <div class="stat-badge up"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline><polyline points="17 6 23 6 23 12"></polyline></svg> +8%</div>
                    <div class="stat-icon green">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M22 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                    </div>
                    <div class="stat-value">+<?php echo number_format($totalClients, 0, ',', '.'); ?></div>
                    <div class="stat-label">Clientes cadastrados</div>
                </div>

                <div class="stat-card">
                    <div class="stat-badge up"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline><polyline points="17 6 23 6 23 12"></polyline></svg> +24%</div>
                    <div class="stat-icon purple">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                    </div>
                    <div class="stat-value"><?php echo $totalViews; ?></div>
                    <div class="stat-label">Visualizações (30d)</div>
                </div>

                <div class="stat-card">
                    <div class="stat-badge down"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 18 13.5 8.5 8.5 13.5 1 6"></polyline><polyline points="17 18 23 18 23 12"></polyline></svg> -3%</div>
                    <div class="stat-icon orange">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"></path></svg>
                    </div>
                    <div class="stat-value"><?php echo $totalContacts; ?></div>
                    <div class="stat-label">Contatos via WhatsApp</div>
                </div>
            </div>

            <!-- Middle Grid: Chart & Top Categories -->
            <div class="middle-grid">
                <div class="panel">
                    <div class="panel-header">
                        <span class="panel-subtitle">TENDÊNCIA</span>
                        <h3 class="panel-title">
                            Visualizações nos últimos 30 dias
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="2"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"></polyline><polyline points="17 6 23 6 23 12"></polyline></svg>
                        </h3>
                    </div>
                    
                    <div class="chart-mock">
                        <?php 
                        // Mock 30 bars
                        for($i=0; $i<30; $i++) {
                            $height = rand(30, 95);
                            echo "<div class='chart-bar' style='height: {$height}%'></div>";
                        }
                        ?>
                    </div>
                    <div class="chart-labels">
                        <span>1 abr</span>
                        <span>15 abr</span>
                        <span>30 abr</span>
                    </div>
                </div>

                <div class="panel">
                    <div class="panel-header">
                        <span class="panel-subtitle">TOP CATEGORIAS</span>
                        <h3 class="panel-title">Mais populares</h3>
                    </div>
                    
                    <div class="cat-list">
                        <?php foreach($topCats as $cat): ?>
                        <div class="cat-item">
                            <div class="cat-info">
                                <span><?php echo $cat['name']; ?></span>
                                <span><?php echo $cat['perc']; ?>%</span>
                            </div>
                            <div class="cat-progress-bg">
                                <div class="cat-progress-fill" style="width: <?php echo $cat['perc']; ?>%"></div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- Recent Ads Table -->
            <div class="panel table-panel">
                <div class="panel-header" style="display:flex; justify-content:space-between; align-items:center;">
                    <div>
                        <span class="panel-subtitle">CADASTROS RECENTES</span>
                        <h3 class="panel-title">Últimos prestadores</h3>
                    </div>
                    <a href="/admin/criar" class="btn-create">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                        Novo anúncio
                    </a>
                </div>
                
                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Anúncio</th>
                                <th>Categoria</th>
                                <th>Localização</th>
                                <th>Status</th>
                                <th>Avaliação</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recentAds as $ad): ?>
                                <tr>
                                    <td style="font-weight: 500;"><?php echo e($ad['titulo']); ?></td>
                                    <td><?php echo e($ad['categoria_nome']); ?></td>
                                    <td><?php echo e($ad['cidade']); ?></td>
                                    <td>
                                        <span class="badge-status <?php echo e($ad['status']); ?>">
                                            <?php echo ucfirst(e($ad['status'])); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if ($ad['nota'] > 0): ?>
                                            <div style="display:flex; align-items:center; gap:4px; color:#f59e0b; font-weight:600;">
                                                <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
                                                <?php echo number_format($ad['nota'], 1, ',', '.'); ?>
                                            </div>
                                        <?php else: ?>
                                            <span style="color:var(--muted-foreground)">Novo</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="table-actions">
                                            <a href="/admin/editar/<?php echo (int)$ad['id']; ?>" class="btn-action">Editar</a>
                                            <form method="post" action="/admin/excluir/<?php echo (int)$ad['id']; ?>" style="display:inline" onsubmit="return confirm('Deseja realmente excluir este anúncio?');">
                                                <?php echo csrf_field(); ?>
                                                <button type="submit" class="btn-action" style="color:var(--danger); border-color:rgba(220, 38, 38, 0.2);">Excluir</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            <?php if(empty($recentAds)): ?>
                                <tr>
                                    <td colspan="6" style="text-align:center; padding: 3rem; color:var(--muted-foreground)">
                                        Nenhum anúncio encontrado.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </main>
</div>

<!-- Scripts for theme toggle -->
<script>
    // Logic inside script.js will handle the theme, but we need to initialize the dropdown structure if we want full fidelity.
    // However, the standard button will hook perfectly to the global script!
</script>
<script src="/assets/js/script.js" defer></script>
</body>
</html>