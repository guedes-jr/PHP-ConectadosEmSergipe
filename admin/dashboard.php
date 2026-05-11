<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/admin_layout.php';

require_admin();

// Detect context
$currentUri = $_SERVER['REQUEST_URI'];
$isAdsList = str_contains($currentUri, '/admin/anuncios');
$activeMenu = $isAdsList ? 'anuncios' : 'dashboard';

// Metrics
$totalAds = $pdo->query("SELECT COUNT(*) FROM anuncios")->fetchColumn();
$totalClients = $pdo->query("SELECT COUNT(DISTINCT email) FROM anuncios")->fetchColumn();
$totalViews = $pdo->query("SELECT SUM(visualizacoes) FROM anuncios")->fetchColumn() ?: 0;
$totalWhatsApp = 892; // Mock for now

// Fetch Data
if ($isAdsList) {
    $search = $_GET['q'] ?? '';
    $sql = "SELECT a.*, c.nome as categoria_nome, cl.nome as cliente_nome 
            FROM anuncios a 
            INNER JOIN categorias c ON a.categoria_id = c.id 
            LEFT JOIN clientes cl ON a.cliente_id = cl.id
            WHERE 1=1";
    $params = [];
    if ($search) {
        $sql .= " AND (a.titulo LIKE ? OR cl.nome LIKE ? OR cl.cidade LIKE ?)";
        $params[] = "%$search%";
        $params[] = "%$search%";
        $params[] = "%$search%";
    }
    $sql .= " ORDER BY a.created_at DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $ads = $stmt->fetchAll();
} else {
    $ads = $pdo->query("
        SELECT a.*, c.nome as categoria_nome, cl.nome as cliente_nome 
        FROM anuncios a 
        INNER JOIN categorias c ON a.categoria_id = c.id 
        LEFT JOIN clientes cl ON a.cliente_id = cl.id
        ORDER BY a.created_at DESC 
        LIMIT 5
    ")->fetchAll();
}

$headerButtons = '<div style="display:flex; gap:1rem;">';
if ($isAdsList) {
    $headerButtons .= '
        <button type="button" id="btnEnableSelect" class="btn btn-outline" style="padding:0.625rem 1.25rem; font-size:0.875rem; display:flex; align-items:center; gap:0.5rem;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 11l3 3L22 4"></path><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path></svg>
            Selecionar Vários
        </button>
        <button type="button" id="btnCancelSelect" class="btn btn-outline" style="display:none; padding:0.625rem 1.25rem; font-size:0.875rem;">
            Cancelar
        </button>
        <button type="submit" form="bulkDeleteForm" id="bulkDeleteBtn" class="btn btn-primary" style="display:none; background:#ef4444; border-color:#ef4444; padding:0.625rem 1.25rem; font-size:0.875rem; align-items:center; gap:0.5rem;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
            Excluir Selecionados (<span id="selectedCount">0</span>)
        </button>';
}
$headerButtons .= '
    <a href="/admin/criar" class="btn btn-primary" style="padding:0.625rem 1.25rem; font-size:0.875rem; text-decoration:none; display:flex; align-items:center; gap:0.5rem;">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
        Cadastrar Anúncio
    </a>
</div>';

render_admin_header($isAdsList ? 'Gerenciar Anúncios' : 'Dashboard', $activeMenu, $headerButtons);
?>

<div class="dashboard-container">
    <?php if (!$isAdsList): ?>
    <!-- Stats Grid -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon blue"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg></div>
            <div class="stat-info">
                <span class="trend up">+12%</span>
                <p class="value"><?php echo $totalAds; ?></p>
                <h3>Anúncios ativos</h3>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon purple"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle></svg></div>
            <div class="stat-info">
                <span class="trend up">+8%</span>
                <p class="value">+<?php echo number_format($totalClients, 0, ',', '.'); ?></p>
                <h3>Clientes cadastrados</h3>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon green"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg></div>
            <div class="stat-info">
                <span class="trend up">+24%</span>
                <p class="value"><?php echo number_format($totalViews/1000, 1); ?>k</p>
                <h3>Visualizações</h3>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon orange"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg></div>
            <div class="stat-info">
                <span class="trend down">-3%</span>
                <p class="value"><?php echo $totalWhatsApp; ?></p>
                <h3>Contatos WhatsApp</h3>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <?php if ($isAdsList): ?>
    <!-- Filter Bar -->
    <form class="filter-bar" method="get" action="/admin/anuncios">
        <div class="filter-group">
            <label>Buscar por título, cliente ou cidade</label>
            <input type="text" name="q" value="<?php echo e($search ?? ''); ?>" placeholder="Ex: Eletricista, João...">
        </div>
        <button type="submit" class="btn btn-primary" style="height: 42px; padding: 0 1.5rem;">Filtrar</button>
        <a href="/admin/anuncios" class="btn btn-outline" style="height: 42px; display: flex; align-items: center; padding: 0 1.5rem; text-decoration: none;">Limpar</a>
    </form>
    <?php endif; ?>

    <form id="bulkDeleteForm" method="post" action="/admin/excluir" onsubmit="return confirm('Excluir todos os anúncios selecionados?')">
        <?php echo csrf_field(); ?>
        <input type="hidden" name="redirect" value="<?php echo $isAdsList ? '/admin/anuncios' : '/admin/dashboard'; ?>">
        <div class="table-card">
            <div style="padding: 1.5rem 2rem; border-bottom: 1px solid var(--border); display:flex; justify-content:space-between; align-items:center;">
                <div>
                    <span class="section-label"><?php echo $isAdsList ? 'GERENCIAMENTO' : 'CADASTROS RECENTES'; ?></span>
                    <h3 style="margin:0; font-size:1.1rem;"><?php echo $isAdsList ? 'Todos os Anúncios (' . count($ads) . ')' : 'Últimos profissionais'; ?></h3>
                </div>
                <?php if (!$isAdsList): ?>
                    <a href="/admin/anuncios" style="font-size:0.85rem; font-weight:600; color:var(--primary);">Ver todos →</a>
                <?php endif; ?>
            </div>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <?php if ($isAdsList): ?>
                            <th class="col-selection" style="width: 40px; padding-right: 0; display:none;"><input type="checkbox" id="selectAll" style="cursor:pointer;"></th>
                            <?php endif; ?>
                            <th>Anúncio</th>
                            <th>Cidade</th>
                            <th>Status</th>
                            <th>Criado em</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($ads as $ad): ?>
                        <tr>
                            <?php if ($isAdsList): ?>
                            <td class="col-selection" style="padding-right: 0; display:none;"><input type="checkbox" name="ids[]" value="<?php echo $ad['id']; ?>" class="ad-checkbox" style="cursor:pointer;"></td>
                            <?php endif; ?>
                            <td>
                                <div class="client-info">
                                    <img src="<?php echo asset_url($ad['imagem_principal']); ?>" alt="" style="width:40px; height:40px; border-radius:10px; object-fit:cover; flex-shrink:0;">
                                    <div class="client-details">
                                        <span class="client-name" style="font-weight:600;"><?php echo e($ad['titulo']); ?></span>
                                        <span class="client-email" style="font-size:0.8rem; color:var(--muted-foreground);"><?php echo e($ad['categoria_nome']); ?><?php echo $ad['cliente_nome'] ? ' · ' . e($ad['cliente_nome']) : ''; ?></span>
                                    </div>
                                </div>
                            </td>
                            <td><?php echo e($ad['cidade']); ?></td>
                            <td>
                                <span class="status-pill <?php echo $ad['status'] === 'ativo' ? 'status-ativo' : 'status-inativo'; ?>">
                                    <?php echo ucfirst(e($ad['status'])); ?>
                                </span>
                            </td>
                            <td><?php echo date('d/m/Y', strtotime($ad['created_at'])); ?></td>
                            <td>
                                <div class="actions">
                                    <a href="/admin/editar/<?php echo $ad['id']; ?>" class="btn-icon" title="Editar">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                    </a>
                                    <a href="/anuncio/<?php echo $ad['slug']; ?>" target="_blank" class="btn-icon" title="Ver no site">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
                                    </a>
                                    <form method="post" action="/admin/excluir" onsubmit="return confirm('Excluir este anúncio?')" style="display:inline;">
                                        <?php echo csrf_field(); ?>
                                        <input type="hidden" name="id" value="<?php echo $ad['id']; ?>">
                                        <input type="hidden" name="redirect" value="<?php echo $isAdsList ? '/admin/anuncios' : '/admin/dashboard'; ?>">
                                        <button type="submit" class="btn-icon" style="color:#ef4444;" title="Excluir">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </form>
</div>

<style>
    .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 1.5rem; margin-bottom: 2rem; }
    .stat-card { background: var(--card); border: 1px solid var(--border); border-radius: 1rem; padding: 1.5rem; display: flex; gap: 1.25rem; }
    .stat-icon { width: 44px; height: 44px; border-radius: 10px; display: flex; align-items: center; justify-content: center; }
    .stat-icon.blue { background: rgba(37, 99, 235, 0.1); color: #2563eb; }
    .stat-icon.purple { background: rgba(139, 92, 246, 0.1); color: #8b5cf6; }
    .stat-icon.green { background: rgba(34, 197, 94, 0.1); color: #22c55e; }
    .stat-icon.orange { background: rgba(249, 115, 22, 0.1); color: #f97316; }
    .value { font-size: 1.5rem; font-weight: 800; margin: 0; }
    .trend { font-size: 0.7rem; font-weight: 700; padding: 0.2rem 0.5rem; border-radius: 2rem; float: right; }
    .trend.up { background: #dcfce7; color: #15803d; }
    .trend.down { background: #fee2e2; color: #b91c1c; }
    .filter-bar { background: var(--card); border: 1px solid var(--border); border-radius: 1rem; padding: 1.25rem; margin-bottom: 2rem; display: flex; gap: 1rem; align-items: flex-end; box-shadow: var(--shadow-sm); }
    .filter-group { display: flex; flex-direction: column; gap: 0.5rem; flex: 1; }
    .filter-group label { font-size: 0.75rem; font-weight: 700; color: var(--muted-foreground); text-transform: uppercase; }
    .filter-group input, .filter-group select { padding: 0.625rem 1rem; border: 1px solid var(--border); border-radius: 0.75rem; background: var(--muted-bg); color: var(--foreground); font-size: 0.9rem; }
    .table-card { background: var(--card); border: 1px solid var(--border); border-radius: 1.5rem; overflow: hidden; box-shadow: var(--shadow-sm); }
    .table-responsive { width: 100%; overflow-x: auto; }
    table { width: 100%; border-collapse: collapse; text-align: left; }
    th { background: var(--accent); padding: 1rem 1.5rem; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; color: var(--muted-foreground); border-bottom: 1px solid var(--border); }
    td { padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--border); font-size: 0.9rem; color: var(--foreground); vertical-align: middle; }
    .client-info { display: flex; align-items: center; gap: 1rem; }
    .client-details { display: flex; flex-direction: column; }
    .status-pill { padding: 0.25rem 0.75rem; border-radius: 2rem; font-size: 0.75rem; font-weight: 700; text-transform: capitalize; display: inline-block; }
    .status-ativo { background: #dcfce7; color: #166534; }
    .status-inativo { background: #fee2e2; color: #991b1b; }
    .actions { display: flex; gap: 0.5rem; }
    @media (max-width: 768px) {
        .stats-grid { grid-template-columns: repeat(2, 1fr); }
        .filter-bar { flex-direction: column; }
    }
    @media (max-width: 480px) {
        .stats-grid { grid-template-columns: 1fr; }
    }
</style>

<?php if ($isAdsList): ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectAll = document.getElementById('selectAll');
        const checkboxes = document.querySelectorAll('.ad-checkbox');
        const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');
        const selectedCount = document.getElementById('selectedCount');
        const btnEnableSelect = document.getElementById('btnEnableSelect');
        const btnCancelSelect = document.getElementById('btnCancelSelect');
        const selectionCols = document.querySelectorAll('.col-selection');

        function updateBulkButton() {
            const checked = document.querySelectorAll('.ad-checkbox:checked').length;
            bulkDeleteBtn.style.display = checked > 0 ? 'flex' : 'none';
            selectedCount.innerText = checked;
        }

        if (btnEnableSelect) {
            btnEnableSelect.addEventListener('click', function() {
                selectionCols.forEach(col => col.style.display = 'table-cell');
                btnEnableSelect.style.display = 'none';
                btnCancelSelect.style.display = 'flex';
            });
        }

        if (btnCancelSelect) {
            btnCancelSelect.addEventListener('click', function() {
                selectionCols.forEach(col => col.style.display = 'none');
                btnEnableSelect.style.display = 'flex';
                btnCancelSelect.style.display = 'none';
                bulkDeleteBtn.style.display = 'none';
                checkboxes.forEach(cb => cb.checked = false);
                if (selectAll) selectAll.checked = false;
            });
        }

        if (selectAll) {
            selectAll.addEventListener('change', function() {
                checkboxes.forEach(cb => cb.checked = this.checked);
                updateBulkButton();
            });
        }

        checkboxes.forEach(cb => {
            cb.addEventListener('change', updateBulkButton);
        });
    });
</script>
<?php endif; ?>

<?php render_admin_footer(); ?>