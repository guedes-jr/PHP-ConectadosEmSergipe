<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/admin_layout.php';

require_admin();

$message = '';
$error = '';

// Garantir que as chaves de banner existam
$bannersDefaults = [
    1 => ['/assets/img/hero-orla.png', 'Banner 1 (Recomendado 1920x1080)'],
    2 => ['/assets/img/sergipe-cidade1.jpg', 'Banner 2 (Recomendado 1920x1080)'],
    3 => ['/assets/img/sergipe-cidade2.jpg', 'Banner 3 (Recomendado 1920x1080)'],
    4 => ['/assets/img/sergipe-cidade3.jpg', 'Banner 4 (Recomendado 1920x1080)'],
    5 => ['/assets/img/caranguejo.png', 'Banner 5 (Recomendado 1920x1080)']
];
foreach ($bannersDefaults as $i => $data) {
    $chave = 'hero_banner_' . $i;
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM configuracoes WHERE chave = ?");
    $stmt->execute([$chave]);
    if ($stmt->fetchColumn() == 0) {
        $stmt = $pdo->prepare("INSERT INTO configuracoes (chave, valor, categoria, descricao) VALUES (?, '', 'Banners Principais', ?)");
        $stmt->execute([$chave, $data[1]]);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validate_csrf()) {
        $error = 'Token de segurança inválido.';
    } else {
        try {
            $pdo->beginTransaction();
            foreach ($_POST['settings'] ?? [] as $chave => $valor) {
                $stmt = $pdo->prepare("UPDATE configuracoes SET valor = ? WHERE chave = ?");
                $stmt->execute([$valor, $chave]);
            }

            $uploadErrors = [];
            // Handle banner removal
            if (!empty($_POST['remove_banner'])) {
                foreach ($_POST['remove_banner'] as $chave => $remove) {
                    if ($remove == '1') {
                        $stmt = $pdo->prepare("UPDATE configuracoes SET valor = '' WHERE chave = ?");
                        $stmt->execute([$chave]);
                    }
                }
            }

            if (!empty($_FILES['settings_file'])) {
                foreach ($_FILES['settings_file']['name'] as $chave => $name) {
                    if ($_FILES['settings_file']['error'][$chave] === UPLOAD_ERR_OK) {
                        $file = [
                            'name' => $_FILES['settings_file']['name'][$chave],
                            'type' => $_FILES['settings_file']['type'][$chave],
                            'tmp_name' => $_FILES['settings_file']['tmp_name'][$chave],
                            'error' => $_FILES['settings_file']['error'][$chave],
                            'size' => $_FILES['settings_file']['size'][$chave],
                        ];
                        $path = upload_image($file, 'banners');
                        if ($path) {
                            $stmt = $pdo->prepare("UPDATE configuracoes SET valor = ? WHERE chave = ?");
                            $stmt->execute(['/' . $path, $chave]);
                        } else {
                            $uploadErrors[] = "A imagem do {$chave} não é suportada (apenas JPG, PNG, WEBP) ou houve erro de permissão.";
                        }
                    } elseif ($_FILES['settings_file']['error'][$chave] !== UPLOAD_ERR_NO_FILE) {
                        $uploadErrors[] = "Erro ao enviar {$chave}: Código " . $_FILES['settings_file']['error'][$chave];
                    }
                }
            }

            $pdo->commit();
            if (empty($uploadErrors)) {
                $message = 'Configurações atualizadas com sucesso!';
            } else {
                $message = 'Algumas configurações foram salvas.';
                $error = implode('<br>', $uploadErrors);
            }
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
        <form id="settingsForm" method="post" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            
            <?php foreach ($groupedSettings as $categoria => $settings): ?>
                <div class="config-card">
                    <h3 class="config-category-title"><?php echo e($categoria); ?></h3>
                    <?php foreach ($settings as $s): ?>
                        <div class="config-group">
                            <label><?php echo e($s['descricao']); ?></label>
                            <?php if (str_starts_with($s['chave'], 'hero_banner_')): ?>
                                <?php if (!empty($s['valor'])): ?>
                                    <div style="margin-bottom: 0.5rem;">
                                        <img src="<?php echo asset_url($s['valor']); ?>" style="max-height: 100px; border-radius: 8px; object-fit: cover;">
                                    </div>
                                    <label style="font-weight:normal; font-size:0.8rem; display:flex; align-items:center; gap:0.35rem; margin-bottom:0.5rem; cursor:pointer;">
                                        <input type="checkbox" name="remove_banner[<?php echo $s['chave']; ?>]" value="1">
                                        Remover esta imagem e voltar para a foto padrão
                                    </label>
                                <?php endif; ?>
                                <input type="file" name="settings_file[<?php echo $s['chave']; ?>]" accept="image/*" style="padding: 0.5rem; background: #fff;">
                                <small>Para manter a imagem atual, não envie nada neste campo.</small>
                            <?php elseif (strlen($s['valor']) > 100 || str_contains($s['chave'], 'titulo')): ?>
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
