<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/admin_layout.php';
require_once __DIR__ . '/../includes/sergipe_data.php';

require_admin();

$id = (int)($_GET['id'] ?? 0);
$categories = fetch_all_categories($pdo);
$regions = get_sergipe_regions();
$cityMapping = get_city_region_mapping();
$message = '';
$error = '';

$stmt = $pdo->prepare("
    SELECT a.*, cl.nome as cliente_nome, cl.email as cliente_email, cl.telefone as cliente_telefone, cl.whatsapp as cliente_whatsapp, cl.cidade as cliente_cidade, cl.regiao as cliente_regiao
    FROM anuncios a 
    LEFT JOIN clientes cl ON a.cliente_id = cl.id 
    WHERE a.id = ?
");
$stmt->execute([$id]);
$ad = $stmt->fetch();

if (!$ad) {
    header('Location: /admin/dashboard');
    exit;
}

$horarios = fetch_horarios_by_ad($pdo, $id);
$images = $pdo->prepare("SELECT * FROM imagens WHERE anuncio_id = ? ORDER BY ordem ASC");
$images->execute([$id]);
$images = $images->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_image_id'])) {
    $imgId = (int)$_POST['delete_image_id'];
    $stmtFind = $pdo->prepare("SELECT caminho FROM imagens WHERE id = ? AND anuncio_id = ?");
    $stmtFind->execute([$imgId, $id]);
    $imgToDelete = $stmtFind->fetch();
    
    if ($imgToDelete) {
        @unlink(__DIR__ . '/../' . $imgToDelete['caminho']);
        $pdo->prepare("DELETE FROM imagens WHERE id = ?")->execute([$imgId]);
        header("Location: /admin/editar/$id?msg=deleted");
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['delete_image_id'])) {
    if (!validate_csrf()) {
        $error = 'Token de segurança inválido.';
    } else {
        $titulo = trim($_POST['titulo'] ?? '');
        $categoria_id = (int)($_POST['categoria_id'] ?? 0);
        $descricao = trim($_POST['descricao'] ?? '');
        $cidade = trim($_POST['cidade'] ?? '');
        $regiao = trim($_POST['regiao'] ?? '');
        $telefone = trim($_POST['telefone'] ?? '');
        $whatsapp = trim($_POST['whatsapp'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $instagram = trim($_POST['instagram'] ?? '');
        $facebook = trim($_POST['facebook'] ?? '');
        $destaque = isset($_POST['destaque']) ? 1 : 0;
        $status = $_POST['status'] ?? 'ativo';

        if (!$titulo || !$categoria_id || !$descricao) {
            $error = 'Preencha os campos obrigatórios.';
        } else {
            try {
                $pdo->beginTransaction();

                if ($ad['cliente_id']) {
                    $stmtUpd = $pdo->prepare("UPDATE clientes SET email = ?, telefone = ?, whatsapp = ?, cidade = ?, regiao = ? WHERE id = ?");
                    $stmtUpd->execute([$email, $telefone, $whatsapp, $cidade, $regiao, $ad['cliente_id']]);
                }

                $img_principal = $ad['imagem_principal'];
                if (!empty($_FILES['imagem_principal']['name'])) {
                    $img_principal = upload_image($_FILES['imagem_principal'], 'ads/profile');
                }

                $img_banner = $ad['imagem_banner'];
                if (!empty($_FILES['imagem_banner']['name'])) {
                    $img_banner = upload_image($_FILES['imagem_banner'], 'ads/banners');
                }

                $stmt = $pdo->prepare("
                    UPDATE anuncios SET 
                        titulo = ?, descricao = ?, categoria_id = ?, 
                        cidade = ?, regiao = ?,
                        imagem_principal = ?, imagem_banner = ?, 
                        destaque = ?, status = ?, instagram = ?, facebook = ?,
                        cliente_id = ?
                    WHERE id = ?
                ");
                $stmt->execute([
                    $titulo, $descricao, $categoria_id, 
                    $cidade, $regiao,
                    $img_principal, $img_banner, 
                    $destaque, $status, $instagram, $facebook,
                    (int)($_POST['cliente_id'] ?? $ad['cliente_id']), $id
                ]);

                $diasKeys = ['seg', 'ter', 'qua', 'qui', 'sex', 'sab', 'dom'];
                foreach ($diasKeys as $index => $k) {
                    $abre = $_POST["horario_{$k}_abre"] ?? null;
                    $fecha = $_POST["horario_{$k}_fecha"] ?? null;
                    $fechado = isset($_POST["horario_{$k}_fechado"]) ? 1 : 0;
                    
                    $stmtH = $pdo->prepare("UPDATE horarios SET abertura = ?, fechamento = ?, fechado = ? WHERE anuncio_id = ? AND dia_semana = ?");
                    $stmtH->execute([$abre, $fecha, $fechado, $id, $index + 1]);
                }

                // Process Gallery Images
                if (!empty($_FILES['galeria']['name'][0])) {
                    foreach ($_FILES['galeria']['tmp_name'] as $key => $tmpName) {
                        if ($_FILES['galeria']['error'][$key] === UPLOAD_ERR_OK) {
                            $fileData = [
                                'name' => $_FILES['galeria']['name'][$key],
                                'type' => $_FILES['galeria']['type'][$key],
                                'tmp_name' => $tmpName,
                                'error' => $_FILES['galeria']['error'][$key],
                                'size' => $_FILES['galeria']['size'][$key]
                            ];
                            $path = upload_image($fileData, 'ads/gallery');
                            if ($path) {
                                $stmtImg = $pdo->prepare("INSERT INTO imagens (anuncio_id, caminho, ordem) VALUES (?, ?, ?)");
                                $stmtImg->execute([$id, $path, $key]);
                            }
                        }
                    }
                }

                $pdo->commit();
                header("Location: /admin/editar/$id?msg=success");
                exit;

            } catch (Exception $e) {
                $pdo->rollBack();
                $error = 'Erro ao salvar: ' . $e->getMessage();
            }
        }
    }
}

$headerButtons = '
    <a href="/anuncio/'.$ad['slug'].'" target="_blank" class="btn btn-outline" style="text-decoration:none; display:flex; align-items:center; gap:0.5rem; border-color:var(--border); color:var(--foreground);">
        <i data-lucide="external-link" style="width:16px;"></i> Ver no Site
    </a>';
render_admin_header('Editar Anúncio', 'anuncios', $headerButtons);
?>

<div class="container-form" style="color: var(--foreground);">
    <style>
        .container-form { max-width: 1000px; margin: 2rem auto; padding: 0 1rem; width: 100%; color: var(--foreground); }
        .form-card { background: var(--card); border: 1px solid var(--border); border-radius: 1.5rem; padding: 2.5rem; box-shadow: var(--shadow-sm); margin-bottom: 2rem; color: var(--foreground); }
        .form-section-title { font-size: 1.1rem; font-weight: 700; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.75rem; color: var(--primary); }
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; }
        .full-width { grid-column: span 2; }
        .form-group { display: flex; flex-direction: column; gap: 0.5rem; }
        .form-group label { font-size: 0.875rem; font-weight: 600; color: var(--foreground); }
        .form-group input, .form-group select, .form-group textarea { padding: 0.75rem 1rem; border: 1px solid var(--border); border-radius: 0.75rem; background: var(--muted-bg); color: var(--foreground); font-size: 0.95rem; }
        .image-preview-box { width: 100%; aspect-ratio: 1; background: var(--muted-bg); border: 2px dashed var(--border); border-radius: 1rem; display: flex; align-items: center; justify-content: center; position: relative; overflow: hidden; }
        .image-preview-box img { width: 100%; height: 100%; object-fit: cover; }
        .btn-save { background: var(--primary); color: white; border: none; padding: 1rem 2rem; border-radius: 0.75rem; font-weight: 700; cursor: pointer; transition: all 0.2s; width: 100%; font-size: 1rem; }
        .alert { padding: 1rem 1.5rem; border-radius: 0.75rem; margin-bottom: 2rem; font-weight: 500; }
        .alert-success { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
        .alert-danger { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
        .schedule-row { display: grid; grid-template-columns: 120px 1fr 1fr 100px; gap: 1rem; align-items: center; margin-bottom: 0.75rem; }
    </style>

    <?php if (isset($_GET['msg']) && $_GET['msg'] === 'success'): ?>
        <div class="alert alert-success">Anúncio atualizado com sucesso!</div>
    <?php endif; ?>
    <?php if (isset($_GET['msg']) && $_GET['msg'] === 'deleted'): ?>
        <div class="alert alert-success" style="background:#fef9c3; color:#854d0e; border-color:#fef08a;">Foto removida com sucesso.</div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo e($error); ?></div>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>

        <div class="form-card">
            <h2 class="form-section-title">Informações do Serviço</h2>
            <div class="form-grid">
                <div class="form-group full-width">
                    <label>Título do Anúncio *</label>
                    <input type="text" name="titulo" value="<?php echo e($ad['titulo']); ?>" required>
                </div>
                <div class="form-group">
                    <label>Categoria *</label>
                    <select name="categoria_id" required>
                        <?php foreach($categories as $cat): ?>
                            <option value="<?php echo $cat['id']; ?>" <?php echo $cat['id'] == $ad['categoria_id'] ? 'selected' : ''; ?>>
                                <?php echo e($cat['nome']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Status</label>
                    <select name="status">
                        <option value="ativo" <?php echo $ad['status'] === 'ativo' ? 'selected' : ''; ?>>Ativo</option>
                        <option value="inativo" <?php echo $ad['status'] === 'inativo' ? 'selected' : ''; ?>>Inativo</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Cidade *</label>
                    <input type="text" name="cidade" id="ad_cidade" value="<?php echo e($ad['cidade']); ?>" required>
                </div>
                <div class="form-group">
                    <label>Região de Sergipe</label>
                    <select name="regiao" id="ad_regiao">
                        <option value="">Selecione a região</option>
                        <?php foreach($regions as $r): ?>
                            <option value="<?php echo e($r); ?>" <?php echo $ad['regiao'] === $r ? 'selected' : ''; ?>><?php echo e($r); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group full-width">
                    <label>Descrição Completa *</label>
                    <textarea name="descricao" rows="5" required><?php echo e($ad['descricao']); ?></textarea>
                </div>
                <div class="form-group">
                    <label class="checkbox" style="flex-direction:row; align-items:center; gap:0.5rem; cursor:pointer;">
                        <input type="checkbox" name="destaque" <?php echo $ad['destaque'] ? 'checked' : ''; ?>> 
                        Destaque na página inicial
                    </label>
                </div>
            </div>
        </div>

        <div class="form-card">
            <h2 class="form-section-title">Horários de Funcionamento</h2>
            <div class="schedule-grid">
                <?php 
                $diasLabels = ['Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado', 'Domingo'];
                $diasKeys = ['seg', 'ter', 'qua', 'qui', 'sex', 'sab', 'dom'];
                foreach($diasKeys as $i => $k): 
                    $h = $horarios[$i] ?? ['abertura' => '08:00', 'fechamento' => '18:00', 'fechado' => 0];
                ?>
                <div class="schedule-row">
                    <span style="font-weight:600;"><?php echo $diasLabels[$i]; ?></span>
                    <input type="time" name="horario_<?php echo $k; ?>_abre" value="<?php echo $h['abertura']; ?>">
                    <input type="time" name="horario_<?php echo $k; ?>_fecha" value="<?php echo $h['fechamento']; ?>">
                    <label style="font-size:0.75rem; display:flex; align-items:center; gap:0.35rem; cursor:pointer;">
                        <input type="checkbox" name="horario_<?php echo $k; ?>_fechado" <?php echo $h['fechado'] ? 'checked' : ''; ?>> Fechado
                    </label>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="form-card">
            <h2 class="form-section-title">Dados do Profissional (Responsável)</h2>
            <div class="form-grid">
                <div class="form-group full-width">
                    <label>Vincular a Profissional Existente</label>
                    <select id="selectClient" name="cliente_id">
                        <?php foreach(fetch_all_clients($pdo) as $c): ?>
                            <option value="<?php echo $c['id']; ?>" 
                                    <?php echo $c['id'] == $ad['cliente_id'] ? 'selected' : ''; ?>
                                    data-email="<?php echo e($c['email']); ?>"
                                    data-telefone="<?php echo e($c['telefone']); ?>"
                                    data-whatsapp="<?php echo e($c['whatsapp']); ?>"
                                    data-cidade="<?php echo e($c['cidade']); ?>"
                                    data-regiao="<?php echo e($c['regiao']); ?>">
                                <?php echo e($c['nome']); ?> (<?php echo e($c['email']); ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>E-mail *</label>
                    <input type="email" name="email" id="email" value="<?php echo e($ad['cliente_email']); ?>" required>
                </div>
                <div class="form-group">
                    <label>Telefone Principal *</label>
                    <input type="text" name="telefone" id="telefone" value="<?php echo e($ad['cliente_telefone'] ?: $ad['telefone']); ?>" required maxlength="15">
                    <label style="font-size:0.75rem; display:flex; align-items:center; gap:0.5rem; cursor:pointer; color:var(--muted-foreground); margin-top:0.25rem;">
                        <input type="checkbox" id="syncWhatsapp" <?php echo ($ad['cliente_telefone'] ?: $ad['telefone']) === ($ad['cliente_whatsapp'] ?: $ad['whatsapp']) ? 'checked' : ''; ?>> WhatsApp é o mesmo do telefone
                    </label>
                </div>
                <div class="form-group">
                    <label>WhatsApp</label>
                    <input type="text" name="whatsapp" id="whatsapp" value="<?php echo e($ad['cliente_whatsapp'] ?: $ad['whatsapp']); ?>" maxlength="15">
                </div>
            </div>
        </div>

        <div class="form-card">
            <h2 class="form-section-title">Redes Sociais</h2>
            <div class="form-grid">
                <div class="form-group">
                    <label>Instagram</label>
                    <input type="text" name="instagram" value="<?php echo e($ad['instagram']); ?>" placeholder="@usuario">
                </div>
                <div class="form-group">
                    <label>Facebook</label>
                    <input type="text" name="facebook" value="<?php echo e($ad['facebook']); ?>" placeholder="link da pagina">
                </div>
            </div>
        </div>

        <div class="form-card">
            <h2 class="form-section-title">Imagens Principais</h2>
            <div class="form-grid">
                <div class="form-group">
                    <label>Foto de Perfil / Logo</label>
                    <input type="file" name="imagem_principal" accept="image/*" onchange="previewImage(this, 'p1')">
                    <div class="image-preview-box" id="p1">
                        <?php if($ad['imagem_principal']): ?>
                            <img src="<?php echo asset_url($ad['imagem_principal']); ?>">
                        <?php else: ?>
                            <span>Preview</span>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="form-group">
                    <label>Banner de Fundo</label>
                    <input type="file" name="imagem_banner" accept="image/*" onchange="previewImage(this, 'p2')">
                    <div class="image-preview-box" id="p2" style="aspect-ratio:16/9;">
                        <?php if($ad['imagem_banner']): ?>
                            <img src="<?php echo asset_url($ad['imagem_banner']); ?>">
                        <?php else: ?>
                            <span>Preview</span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-card">
            <h2 class="form-section-title">Galeria de Trabalhos / Produtos</h2>
            <p style="font-size: 0.85rem; color: var(--muted-foreground); margin-bottom: 1.5rem;">Gerencie as fotos que aparecem no portfólio do anúncio. Clique no "X" para remover ou selecione novas fotos abaixo.</p>
            <div class="form-group full-width">
                <label>Adicionar Novas Fotos (Várias)</label>
                <input type="file" name="galeria[]" id="galleryInput" multiple accept="image/*" style="padding: 1rem; border-style: dashed; border-width: 2px;">
                <div id="galleryPreview" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(120px, 1fr)); gap: 1rem; margin-top: 1.5rem;">
                    <?php foreach($images as $img): ?>
                        <div class="image-preview-box" style="position:relative;">
                            <img src="<?php echo asset_url($img['caminho']); ?>">
                            <button type="submit" name="delete_image_id" value="<?php echo $img['id']; ?>" 
                                    style="position:absolute; top:5px; right:5px; background:rgba(239, 68, 68, 0.9); color:white; border:none; border-radius:4px; width:24px; height:24px; cursor:pointer; display:flex; align-items:center; justify-content:center; font-size:12px;"
                                    onclick="return confirm('Excluir esta foto?')">
                                <i data-lucide="x" style="width:14px;"></i>
                            </button>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <button type="submit" class="btn-save">Salvar Alterações</button>
    </form>
</div>

<script>
    const cityMapping = <?php echo json_encode($cityMapping); ?>;
    function previewImage(input, previewId) {
        const box = document.getElementById(previewId);
        if (input.files && input.files[0]) {
            if (input.files[0].size > 200 * 1024 * 1024) { alert('Arquivo muito grande (máximo 200MB)'); input.value = ''; return; }
            const reader = new FileReader();
            reader.onload = function(e) { box.innerHTML = `<img src="${e.target.result}" style="width:100%; height:100%; object-fit:cover;">`; }
            reader.readAsDataURL(input.files[0]);
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const whatsappInput = document.getElementById('whatsapp');
        const telefoneInput = document.getElementById('telefone');
        const syncWhatsapp = document.getElementById('syncWhatsapp');
        const selectClient = document.getElementById('selectClient');
        const emailInput = document.getElementById('email');
        const adCidade = document.getElementById('ad_cidade');
        const adRegiao = document.getElementById('ad_regiao');

        function updateRegion(city) { if (cityMapping[city]) adRegiao.value = cityMapping[city]; }
        adCidade.addEventListener('input', (e) => updateRegion(e.target.value));

        const galleryInput = document.getElementById('galleryInput');
        if (galleryInput) {
            galleryInput.addEventListener('change', (e) => {
                const preview = document.getElementById('galleryPreview');
                // Mantemos as antigas ou limpamos? No editar, geralmente adicionamos.
                // Mas para preview de upload, limpamos o preview de 'novas'.
                // Vamos apenas adicionar um container para 'novas' se quiser ser perfeito.
                // Por simplicidade, vamos mostrar o que foi selecionado agora.
                preview.innerHTML = ''; 
                for (let file of e.target.files) {
                    if (file.size > 200 * 1024 * 1024) { alert(`O arquivo "${file.name}" excede 200MB.`); e.target.value = ''; preview.innerHTML = ''; return; }
                    const reader = new FileReader();
                    reader.onload = (event) => {
                        const div = document.createElement('div');
                        div.className = 'image-preview-box';
                        div.innerHTML = `<img src="${event.target.result}">`;
                        preview.appendChild(div);
                    };
                    reader.readAsDataURL(file);
                }
            });
        }

        function mask(v) { v = v.replace(/\D/g, ""); v = v.replace(/^(\d{2})(\d)/g, "($1) $2"); v = v.replace(/(\d)(\d{4})$/, "$1-$2"); return v; }

        [whatsappInput, telefoneInput].forEach(el => {
            el.addEventListener('input', (e) => {
                e.target.value = mask(e.target.value);
                if (syncWhatsapp.checked && e.target.id === 'telefone') whatsappInput.value = telefoneInput.value;
            });
            if (el.value) el.value = mask(el.value);
        });

        syncWhatsapp.addEventListener('change', () => {
            if (syncWhatsapp.checked) { whatsappInput.value = telefoneInput.value; whatsappInput.readOnly = true; whatsappInput.style.opacity = '0.7'; }
            else { whatsappInput.readOnly = false; whatsappInput.style.opacity = '1'; }
        });

        if (syncWhatsapp.checked) { whatsappInput.readOnly = true; whatsappInput.style.opacity = '0.7'; }

        if (selectClient) {
            selectClient.addEventListener('change', function() {
                const option = this.options[this.selectedIndex];
                if (option.value) {
                    emailInput.value = option.dataset.email || '';
                    telefoneInput.value = mask(option.dataset.telefone || '');
                    whatsappInput.value = mask(option.dataset.whatsapp || '');
                    adCidade.value = option.dataset.cidade || '';
                    adRegiao.value = option.dataset.regiao || '';
                    if (telefoneInput.value === whatsappInput.value && telefoneInput.value !== '') { syncWhatsapp.checked = true; whatsappInput.readOnly = true; whatsappInput.style.opacity = '0.7'; }
                    else { syncWhatsapp.checked = false; whatsappInput.readOnly = false; whatsappInput.style.opacity = '1'; }
                }
            });
        }
    });
</script>

<?php render_admin_footer(); ?>
