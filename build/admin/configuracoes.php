<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/admin_layout.php';

require_admin();

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validate_csrf()) {
        $error = 'Token de segurança inválido.';
    } else {
        try {
            $pdo->beginTransaction();
            foreach ($_POST['settings'] as $chave => $valor) {
                $stmt = $pdo->prepare("UPDATE configuracoes SET valor = ? WHERE chave = ?");
                $stmt->execute([$valor, $chave]);
            }
            $pdo->commit();
            $message = 'Configurações atualizadas com sucesso!';
        } catch (Exception $e) {
            $pdo->rollBack();
            $error = 'Erro ao salvar: ' . $e->getMessage();
        }
    }
}

// Fetch settings grouped by category
$stmt = $pdo->query("SELECT * FROM configuracoes ORDER BY categoria, chave");
$allSettings = $stmt->fetchAll();
$groupedSettings = [];
foreach ($allSettings as $s) {
    $groupedSettings[$s['categoria']][] = $s;
}

$headerButtons = '<button type="submit" form="settingsForm" class="btn btn-primary" style="padding:0.625rem 1.25rem; font-size:0.875rem; display:flex; align-items:center; gap:0.5rem;">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
    Salvar Alterações
</button>';

render_admin_header('Configurações do Site', 'configuracoes', $headerButtons);
?>

<div class="dashboard-container">
    <style>
        .form-wrapper { max-width: 900px; margin: 0 auto; width: 100%; }
        .config-card { background: var(--card); border: 1px solid var(--border); border-radius: 1.25rem; padding: 2rem; margin-bottom: 2rem; box-shadow: var(--shadow-sm); }
        .config-category-title { font-size: 0.75rem; font-weight: 800; color: var(--primary); text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.5rem; }
        .config-category-title::after { content: ''; flex: 1; height: 1px; background: var(--border); }
        .config-group { margin-bottom: 1.5rem; }
        .config-group:last-child { margin-bottom: 0; }
        .config-group label { display: block; font-size: 0.9rem; font-weight: 600; margin-bottom: 0.5rem; color: var(--foreground); }
        .config-group input, .config-group textarea { width: 100%; padding: 0.75rem 1rem; border: 1px solid var(--border); border-radius: 0.75rem; background: var(--muted-bg); color: var(--foreground); font-size: 0.95rem; transition: 0.2s; }
        .config-group input:focus { border-color: var(--primary); outline: none; box-shadow: 0 0 0 3px rgba(37,99,235,0.1); }
        .config-group small { display: block; margin-top: 0.4rem; color: var(--muted-foreground); font-size: 0.8rem; }
        .alert { padding: 1rem 1.5rem; border-radius: 0.75rem; margin-bottom: 2rem; font-weight: 500; }
        .alert-success { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
        .alert-danger { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
    </style>

    <?php if ($message): ?>
        <div class="alert alert-success"><?php echo e($message); ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo e($error); ?></div>
    <?php endif; ?>

    <div class="form-wrapper">
        <form id="settingsForm" method="post">
            <?php echo csrf_field(); ?>
            
            <?php foreach ($groupedSettings as $categoria => $settings): ?>
                <div class="config-card">
                    <h3 class="config-category-title"><?php echo e($categoria); ?></h3>
                    <?php foreach ($settings as $s): ?>
                        <div class="config-group">
                            <label><?php echo e($s['descricao']); ?></label>
                            <?php if (strlen($s['valor']) > 100 || str_contains($s['chave'], 'titulo')): ?>
                                <textarea name="settings[<?php echo $s['chave']; ?>]" rows="3"><?php echo e($s['valor']); ?></textarea>
                            <?php else: ?>
                                <input type="text" name="settings[<?php echo $s['chave']; ?>]" value="<?php echo e($s['valor']); ?>">
                            <?php endif; ?>
                            <small>Chave interna: <code><?php echo $s['chave']; ?></code></small>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
        </form>
    </div>
</div>

<?php render_admin_footer(); ?>
