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

    <div class="table-section">
        <div class="card-header" style="padding: 2rem; border-bottom: 1px solid var(--border);">
            <div>
                <span class="section-label"><?php echo $isAdsList ? 'GERENCIAMENTO' : 'CADASTROS RECENTES'; ?></span>
                <h3><?php echo $isAdsList ? 'Todos os Anúncios' : 'Últimos profissionais'; ?></h3>
            </div>
        </div>

        <form id="bulkDeleteForm" method="post" action="/admin/excluir">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="redirect" value="/admin/anuncios">
            <table class="ad-table">
                <thead>
                    <tr>
                        <?php if ($isAdsList): ?>
                        <th class="col-selection" style="width: 40px; padding-right: 0; display:none;"><input type="checkbox" id="selectAll"></th>
                        <?php endif; ?>
                        <th>Anúncio</th>
                        <th>Cidade</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($ads as $ad): ?>
                    <tr>
                        <?php if ($isAdsList): ?>
                        <td class="col-selection" style="display:none;"><input type="checkbox" name="ids[]" value="<?php echo $ad['id']; ?>" class="ad-checkbox"></td>
                        <?php endif; ?>
                        <td>
                            <div class="ad-info">
                                <img src="/<?php echo e($ad['imagem_principal'] ?: 'assets/img/placeholder.svg'); ?>" alt="" class="ad-img">
                                <div>
                                    <span class="ad-title"><?php echo e($ad['titulo']); ?></span>
                                    <span class="ad-cat"><?php echo e($ad['categoria_nome']); ?></span>
                                </div>
                            </div>
                        </td>
                        <td><?php echo e($ad['cidade']); ?></td>
                        <td><span class="status-badge <?php echo $ad['status'] === 'ativo' ? 'active' : 'pending'; ?>"><?php echo e(ucfirst($ad['status'])); ?></span></td>
                        <td>
                            <div class="action-btns">
                                <a href="/admin/editar/<?php echo $ad['id']; ?>" class="action-btn">Editar</a>
                                <a href="/anuncio/<?php echo $ad['slug']; ?>" target="_blank" class="action-btn">Ver</a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </form>
    </div>
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
    .ad-table { width: 100%; border-collapse: collapse; }
    .ad-table td, .ad-table th { padding: 1rem 2rem; border-bottom: 1px solid var(--border); text-align: left; }
    .ad-info { display: flex; align-items: center; gap: 1rem; }
    .ad-img { width: 40px; height: 40px; border-radius: 8px; object-fit: cover; }
    .ad-title { font-weight: 600; display: block; }
    .ad-cat { font-size: 0.75rem; color: var(--muted-foreground); }
    .status-badge { padding: 0.2rem 0.6rem; border-radius: 1rem; font-size: 0.7rem; font-weight: 700; }
    .status-badge.active { background: #dcfce7; color: #15803d; }
    .status-badge.pending { background: #fef9c3; color: #854d0e; }
    .action-btn { font-size: 0.8rem; color: var(--primary); text-decoration: none; font-weight: 600; margin-right: 0.5rem; }
</style>

<?php if ($isAdsList): ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const btnEnableSelect = document.getElementById('btnEnableSelect');
        const btnCancelSelect = document.getElementById('btnCancelSelect');
        const selectionCols = document.querySelectorAll('.col-selection');
        if (btnEnableSelect) {
            btnEnableSelect.addEventListener('click', () => {
                selectionCols.forEach(c => c.style.display = 'table-cell');
                btnEnableSelect.style.display = 'none';
                btnCancelSelect.style.display = 'inline-flex';
            });
        }
    });
</script>
<?php endif; ?>

<?php render_admin_footer(); ?>