<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/admin_layout.php';

require_admin();

$message = '';
$error = '';

// Handle Actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validate_csrf()) {
        $error = 'Token de segurança inválido.';
    } else {
        $action = $_POST['action'] ?? '';
        $nome = trim($_POST['nome'] ?? '');
        $icone = trim($_POST['icone'] ?? '');
        $descricao = trim($_POST['descricao'] ?? '');
        $id = (int)($_POST['id'] ?? 0);

        if ($action === 'create' || $action === 'update') {
            if (!$nome) {
                $error = 'O nome da categoria é obrigatório.';
            } else {
                $slug = slugify($nome);
                if ($action === 'create') {
                    $stmt = $pdo->prepare("INSERT INTO categorias (nome, slug, icone, descricao) VALUES (?, ?, ?, ?)");
                    $stmt->execute([$nome, $slug, $icone, $descricao]);
                    $message = 'Categoria criada com sucesso!';
                } else {
                    $stmt = $pdo->prepare("UPDATE categorias SET nome = ?, slug = ?, icone = ?, descricao = ? WHERE id = ?");
                    $stmt->execute([$nome, $slug, $icone, $descricao, $id]);
                    $message = 'Categoria atualizada com sucesso!';
                }
            }
        } elseif ($action === 'delete') {
            try {
                $stmt = $pdo->prepare("DELETE FROM categorias WHERE id = ?");
                $stmt->execute([$id]);
                $message = 'Categoria excluída com sucesso!';
            } catch (Exception $e) {
                $error = 'Não é possível excluir esta categoria pois existem anúncios vinculados a ela.';
            }
        }
    }
}

// Fetch all categories
$categories = $pdo->query("
    SELECT c.*, (SELECT COUNT(*) FROM anuncios WHERE categoria_id = c.id) as total_anuncios 
    FROM categorias c 
    ORDER BY c.nome ASC
")->fetchAll();

$headerButtons = '<button onclick="openModal()" class="btn btn-primary" style="padding:0.625rem 1.25rem; font-size:0.875rem; display:flex; align-items:center; gap:0.5rem;">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
    Nova Categoria
</button>';

render_admin_header('Gerenciar Categorias', 'categorias', $headerButtons);
?>

<div class="dashboard-container">

<div class="dashboard-container">
    <style>
        .cat-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 2rem; }
        .cat-card { background: var(--card); border: 1px solid var(--border); border-radius: 1.25rem; padding: 1.5rem; display: flex; align-items: center; gap: 1.25rem; transition: 0.2s; position: relative; }
        .cat-card:hover { transform: translateY(-4px); box-shadow: var(--shadow-md); border-color: var(--primary); }
        .cat-icon-box { width: 48px; height: 48px; background: var(--muted-bg); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; color: var(--primary); }
        .cat-icon-box svg { width: 24px; height: 24px; }
        .cat-info { flex: 1; }
        .cat-info h3 { margin: 0; font-size: 1rem; font-weight: 700; }
        .cat-info p { margin: 0.25rem 0 0; font-size: 0.8rem; color: var(--muted-foreground); }
        .cat-actions { display: flex; gap: 0.5rem; }
        .modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 100; align-items: center; justify-content: center; backdrop-filter: blur(4px); }
        .modal-content { background: var(--card); padding: 2.5rem; border-radius: 1.5rem; width: 100%; max-width: 500px; box-shadow: var(--shadow-lg); }
        .icon-modal-content { max-width: 800px; max-height: 80vh; overflow-y: auto; }
        .form-group { margin-bottom: 1.25rem; }
        .form-group label { display: block; font-size: 0.875rem; font-weight: 600; margin-bottom: 0.5rem; }
        .form-group input, .form-group textarea { width: 100%; padding: 0.75rem 1rem; border: 1px solid var(--border); border-radius: 0.75rem; background: var(--muted-bg); color: var(--foreground); }
        
        .icon-search-box { position: sticky; top: -2.5rem; background: var(--card); padding-bottom: 1.5rem; z-index: 10; }
        .icon-search-wrapper { position: relative; display: flex; align-items: center; }
        .icon-search-wrapper svg { position: absolute; left: 1rem; color: var(--muted-foreground); pointer-events: none; }
        .icon-search-wrapper input { width: 100%; padding: 0.875rem 1rem 0.875rem 3rem !important; font-size: 1rem; border-radius: 1rem !important; border: 2px solid var(--border) !important; background: var(--muted-bg) !important; color: var(--foreground) !important; transition: 0.2s; }
        .icon-search-wrapper input:focus { border-color: var(--primary) !important; outline: none; box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1); }
        .icon-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(60px, 1fr)); gap: 1rem; }
        .icon-item { aspect-ratio: 1; background: var(--muted-bg); border: 1px solid var(--border); border-radius: 0.75rem; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: 0.2s; color: var(--muted-foreground); }
        .icon-item:hover { border-color: var(--primary); color: var(--primary); background: var(--accent); transform: scale(1.1); }
        .icon-item svg { width: 24px; height: 24px; }
    </style>

    <?php if ($message): ?>
        <div style="background:#dcfce7; color:#166534; padding:1rem; border-radius:0.75rem; margin-bottom:2rem;"><?php echo e($message); ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div style="background:#fee2e2; color:#991b1b; padding:1rem; border-radius:0.75rem; margin-bottom:2rem;"><?php echo e($error); ?></div>
    <?php endif; ?>

    <div class="cat-grid">
        <?php foreach ($categories as $cat): ?>
            <div class="cat-card">
                <div class="cat-icon-box">
                    <?php 
                    if (str_starts_with($cat['icone'], '<svg')) {
                        echo $cat['icone'];
                    } else if (!empty($cat['icone'])) {
                        echo "<i data-lucide='{$cat['icone']}'></i>";
                        // Fallback simple search icons
                        echo get_category_icon($cat['nome']);
                    } else {
                        echo get_category_icon($cat['nome']);
                    }
                    ?>
                </div>
                <div class="cat-info">
                    <h3><?php echo e($cat['nome']); ?></h3>
                    <p><?php echo $cat['total_anuncios']; ?> anúncios vinculados</p>
                </div>
                <div class="cat-actions">
                    <button onclick='editCategory(<?php echo json_encode($cat); ?>)' class="action-btn">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                    </button>
                    <form method="post" style="display:inline;" onsubmit="return confirm('Excluir esta categoria?')">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id" value="<?php echo $cat['id']; ?>">
                        <button type="submit" class="action-btn" style="color:#ef4444;">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18"></path><path d="M19 6v14c0 1-1 2-2 2H7c-1 2-2-1-2-2V6"></path><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path></svg>
                        </button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Modal Principal -->
<div id="catModal" class="modal">
    <div class="modal-content">
        <h2 id="modalTitle" style="margin-top:0; margin-bottom:1.5rem;">Nova Categoria</h2>
        <form method="post">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="action" id="formAction" value="create">
            <input type="hidden" name="id" id="catId" value="">
            
            <div class="form-group">
                <label>Nome da Categoria</label>
                <input type="text" name="nome" id="catNome" required placeholder="Ex: Eletricista">
            </div>
            
            <div class="form-group">
                <label>Ícone Selecionado</label>
                <div style="display:flex; gap:0.5rem;">
                    <input type="text" name="icone" id="catIcone" placeholder="Ex: hammer, wrench, zap...">
                    <button type="button" onclick="openIconModal()" class="btn btn-outline" style="padding:0.75rem; aspect-ratio:1;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                    </button>
                </div>
            </div>
            
            <div class="form-group">
                <label>Descrição</label>
                <textarea name="descricao" id="catDesc" rows="3"></textarea>
            </div>
            
            <div style="display:flex; gap:1rem; margin-top:2rem;">
                <button type="button" onclick="closeModal()" class="btn btn-outline" style="flex:1;">Cancelar</button>
                <button type="submit" class="btn btn-primary" style="flex:1;">Salvar</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal de Ícones -->
<div id="iconModal" class="modal">
    <div class="modal-content icon-modal-content">
        <div class="icon-search-box">
            <h3 style="margin-top:0; margin-bottom:1rem;">Escolha um Ícone</h3>
            <div class="icon-search-wrapper">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                <input type="text" id="iconSearch" placeholder="Pesquisar ícone por nome...">
            </div>
        </div>
        
        <div id="iconGrid" class="icon-grid">
            <!-- Icons injected via JS -->
        </div>
        
        <div style="margin-top:2rem; text-align:right;">
            <button type="button" onclick="closeIconModal()" class="btn btn-outline">Fechar</button>
        </div>
    </div>
</div>

<script src="https://unpkg.com/lucide@latest"></script>
<script>
    const lucideIcons = [
        // Essentials & Services
        'zap', 'wrench', 'hammer', 'plug', 'scissors', 'camera', 'utensils', 'truck', 'brush', 'paint-bucket', 
        'home', 'user', 'settings', 'phone', 'mail', 'map-pin', 'search', 'trash-2', 'edit-3', 'plus', 
        'heart', 'star', 'shopping-cart', 'credit-card', 'briefcase', 'graduation-cap', 'stethoscope', 'dog',
        'car', 'bike', 'plane', 'coffee', 'pizza', 'music', 'video', 'tv', 'mic',
        'shield', 'lock', 'unlock', 'key', 'bell', 'check', 'x', 'info', 'alert-triangle',
        'thermometer', 'droplets', 'flame', 'wind', 'sun', 'moon', 'cloud', 'hard-hat', 'tools',
        // Business & Tools
        'bar-chart', 'pie-chart', 'line-chart', 'database', 'code', 'cpu', 'smartphone', 'laptop', 'monitor',
        'printer', 'wifi', 'bluetooth', 'battery', 'hard-drive', 'mouse', 'keyboard', 'headphones',
        // Home & Lifestyle
        'bed', 'sofa', 'lamp', 'refrigerator', 'bath', 'shower', 'glass-water', 'wine', 'beer', 'ice-cream',
        'cake', 'candy', 'cookie', 'apple', 'carrot', 'fish', 'clover', 'leaf', 'flower', 'tree-pine',
        // Travel & Places
        'map', 'compass', 'globe', 'anchor', 'ship', 'train', 'bus', 'tram', 'hotel', 'mountain', 'palmtree',
        // Sports & Fun
        'award', 'medal', 'trophy', 'target', 'flag', 'dribbble', 'gamepad-2', 'puzzle', 'ghost', 'smile',
        // New Additions
        'construction', 'factory', 'warehouse', 'ruler', 'shovel', 'gem', 'shirt', 'watch', 'umbrella',
        'gift', 'wallet', 'banknote', 'coins', 'calculator', 'book', 'book-open', 'pencil', 'eraser'
    ];

    function openModal() {
        document.getElementById('modalTitle').innerText = 'Nova Categoria';
        document.getElementById('formAction').value = 'create';
        document.getElementById('catId').value = '';
        document.getElementById('catNome').value = '';
        document.getElementById('catIcone').value = '';
        document.getElementById('catDesc').value = '';
        document.getElementById('catModal').style.display = 'flex';
    }
    
    function closeModal() {
        document.getElementById('catModal').style.display = 'none';
    }
    
    function editCategory(cat) {
        document.getElementById('modalTitle').innerText = 'Editar Categoria';
        document.getElementById('formAction').value = 'update';
        document.getElementById('catId').value = cat.id;
        document.getElementById('catNome').value = cat.nome;
        document.getElementById('catIcone').value = cat.icone;
        document.getElementById('catDesc').value = cat.descricao;
        document.getElementById('catModal').style.display = 'flex';
    }

    function openIconModal() {
        renderIcons();
        document.getElementById('iconModal').style.display = 'flex';
        setTimeout(() => document.getElementById('iconSearch').focus(), 100);
    }

    function closeIconModal() {
        document.getElementById('iconModal').style.display = 'none';
    }

    function renderIcons(filter = '') {
        const grid = document.getElementById('iconGrid');
        grid.innerHTML = '';
        
        const filtered = lucideIcons.filter(i => i.includes(filter.toLowerCase()));
        
        if (filtered.length === 0) {
            grid.innerHTML = '<div style="grid-column: 1/-1; text-align: center; padding: 2rem; color: var(--muted-foreground);">Nenhum ícone encontrado.</div>';
            return;
        }

        filtered.forEach(iconName => {
            const item = document.createElement('div');
            item.className = 'icon-item';
            item.title = iconName;
            item.innerHTML = `<i data-lucide="${iconName}"></i>`;
            item.onclick = () => {
                document.getElementById('catIcone').value = iconName;
                closeIconModal();
            };
            grid.appendChild(item);
        });
        
        lucide.createIcons();
    }

    document.getElementById('iconSearch').addEventListener('input', (e) => {
        renderIcons(e.target.value);
    });

    // Initialize icons for current list
    lucide.createIcons();
</script>

<?php render_admin_footer(); ?>
