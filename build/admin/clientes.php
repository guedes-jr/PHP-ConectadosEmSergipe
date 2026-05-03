<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/admin_layout.php';

require_admin();

// Logic to fetch clients
$search = $_GET['q'] ?? '';
$city = $_GET['cidade'] ?? '';

$sql = "SELECT c.*, COUNT(a.id) as total_anuncios 
        FROM clientes c 
        LEFT JOIN anuncios a ON c.id = a.cliente_id 
        WHERE 1=1";
$params = [];

if ($search) {
    $sql .= " AND (c.nome LIKE ? OR c.email LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

if ($city) {
    $sql .= " AND c.cidade = ?";
    $params[] = $city;
}

$sql .= " GROUP BY c.id ORDER BY c.nome ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$clients = $stmt->fetchAll();

$cities = fetch_unique_cities($pdo);

// Render reusable header
$headerButtons = '
    <div style="display:flex; gap:1rem;">
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
        </button>
        <a href="/admin/criar_cliente.php" class="btn btn-primary">Novo Cliente</a>
    </div>';

render_admin_header('Gerenciar Clientes', 'clientes', $headerButtons);
?>

<div class="dashboard-container">
    <style>
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
        .client-avatar { width: 40px; height: 40px; border-radius: 50%; background: var(--primary); color: white; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.9rem; flex-shrink: 0; }
        .status-pill { padding: 0.25rem 0.75rem; border-radius: 2rem; font-size: 0.75rem; font-weight: 700; text-transform: capitalize; display: inline-block; }
        .status-ativo { background: #dcfce7; color: #166534; }
        .status-inativo { background: #fee2e2; color: #991b1b; }
        .actions { display: flex; gap: 0.5rem; }
    </style>
    <!-- Filter Bar -->
    <form class="filter-bar" method="get">
        <div class="filter-group">
            <label>Buscar por nome ou e-mail</label>
            <input type="text" name="q" value="<?php echo e($search); ?>" placeholder="Ex: João da Silva...">
        </div>
        <div class="filter-group">
            <label>Cidade</label>
            <select name="cidade">
                <option value="">Todas as cidades</option>
                <?php foreach($cities as $c): ?>
                    <option value="<?php echo e($c); ?>" <?php echo $city === $c ? 'selected' : ''; ?>><?php echo e($c); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary" style="height: 42px; padding: 0 1.5rem;">Filtrar</button>
        <a href="/admin/clientes" class="btn btn-outline" style="height: 42px; display: flex; align-items: center; padding: 0 1.5rem; text-decoration: none;">Limpar</a>
    </form>

    <form id="bulkDeleteForm" method="post" action="/admin/excluir" onsubmit="return confirm('Excluir todos os profissionais selecionados e seus respectivos anúncios?')">
        <?php echo csrf_field(); ?>
        <input type="hidden" name="type" value="clientes">
        <input type="hidden" name="redirect" value="/admin/clientes">
        <div class="table-card">
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th class="col-selection" style="width: 40px; padding-right: 0; display:none;"><input type="checkbox" id="selectAll" style="cursor:pointer;"></th>
                            <th>Profissional / Cliente</th>
                            <th>Localização</th>
                            <th>Anúncios</th>
                            <th>Cadastro</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($clients as $client): ?>
                        <tr>
                            <td class="col-selection" style="padding-right: 0; display:none;"><input type="checkbox" name="ids[]" value="<?php echo $client['id']; ?>" class="ad-checkbox" style="cursor:pointer;"></td>
                            <td>
                                <div class="client-info">
                                    <div class="client-avatar"><?php echo strtoupper(substr($client['nome'], 0, 1)); ?></div>
                                    <div class="client-details">
                                        <span class="client-name" style="font-weight:600;"><?php echo e($client['nome']); ?></span>
                                        <span class="client-email" style="font-size:0.8rem; color:var(--muted-foreground);"><?php echo e($client['email'] ?: 'Sem e-mail'); ?></span>
                                    </div>
                                </div>
                            </td>
                            <td><?php echo e($client['cidade']); ?></td>
                            <td>
                                <span class="status-pill" style="background:var(--accent); color:var(--foreground);">
                                    <?php echo $client['total_anuncios']; ?> anúncio(s)
                                </span>
                            </td>
                            <td><?php echo date('d/m/Y', strtotime($client['created_at'])); ?></td>
                            <td>
                                <div class="actions">
                                    <a href="/admin/editar_cliente/<?php echo $client['id']; ?>" class="btn-icon" title="Editar Perfil">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                    </a>
                                    <form method="post" action="/admin/excluir" onsubmit="return confirm('Excluir este profissional e todos os seus anúncios?')" style="display:inline;">
                                        <?php echo csrf_field(); ?>
                                        <input type="hidden" name="id" value="<?php echo $client['id']; ?>">
                                        <input type="hidden" name="type" value="clientes">
                                        <input type="hidden" name="redirect" value="/admin/clientes">
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

<?php render_admin_footer(); ?>
