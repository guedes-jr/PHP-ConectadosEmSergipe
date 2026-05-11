<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/admin_layout.php';
require_once __DIR__ . '/../includes/sergipe_data.php';

require_admin();

$categories = fetch_all_categories($pdo);
$regions = get_sergipe_regions();
$cityMapping = get_city_region_mapping();
$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
        $email = trim($_POST['email'] ?? '') ?: null;
        $instagram = trim($_POST['instagram'] ?? '');
        $facebook = trim($_POST['facebook'] ?? '');
        $destaque = isset($_POST['destaque']) ? 1 : 0;
        $status = $_POST['status'] ?? 'ativo';
        $slug = slugify($titulo);

        if (!$titulo || !$categoria_id || !$descricao || !$cidade || !$telefone) {
            $error = 'Preencha todos os campos obrigatórios (*).';
        } else {
            try {
                $pdo->beginTransaction();

                $clientId = (int)($_POST['cliente_id_selected'] ?? 0);
                if (!$clientId) {
                    $stmtClient = $pdo->prepare("SELECT id FROM clientes WHERE email = ?");
                    $stmtClient->execute([$email]);
                    $client = $stmtClient->fetch();

                    if ($client) {
                        $clientId = $client['id'];
                        $stmtUpdateClient = $pdo->prepare("UPDATE clientes SET nome = ?, telefone = ?, whatsapp = ?, cidade = ?, regiao = ? WHERE id = ?");
                        $stmtUpdateClient->execute([$titulo, $telefone, $whatsapp, $cidade, $regiao, $clientId]);
                    } else {
                        $stmtInsertClient = $pdo->prepare("INSERT INTO clientes (nome, email, telefone, whatsapp, cidade, regiao) VALUES (?, ?, ?, ?, ?, ?)");
                        $stmtInsertClient->execute([$titulo, $email, $telefone, $whatsapp, $cidade, $regiao]);
                        $clientId = $pdo->lastInsertId();
                    }
                } else {
                    $stmtUpdateClient = $pdo->prepare("UPDATE clientes SET telefone = ?, whatsapp = ?, cidade = ?, regiao = ? WHERE id = ?");
                    $stmtUpdateClient->execute([$telefone, $whatsapp, $cidade, $regiao, $clientId]);
                }

                $img_principal = '';
                if (!empty($_FILES['imagem_principal']['name'])) {
                    $img_principal = upload_image($_FILES['imagem_principal'], 'ads/profile');
                }

                $img_banner = '';
                if (!empty($_FILES['imagem_banner']['name'])) {
                    $img_banner = upload_image($_FILES['imagem_banner'], 'ads/banners');
                }

                $stmt = $pdo->prepare("
                    INSERT INTO anuncios (
                        titulo, slug, descricao, categoria_id, cliente_id, cidade, regiao,
                        imagem_principal, imagem_banner, destaque, status, instagram, facebook
                    ) VALUES (
                        :titulo, :slug, :descricao, :categoria_id, :cliente_id, :cidade, :regiao,
                        :imagem_principal, :imagem_banner, :destaque, :status, :instagram, :facebook
                    )
                ");

                $stmt->execute([
                    'titulo' => $titulo,
                    'slug' => $slug,
                    'descricao' => $descricao,
                    'categoria_id' => $categoria_id,
                    'cliente_id' => $clientId,
                    'cidade' => $cidade,
                    'regiao' => $regiao,
                    'imagem_principal' => $img_principal ?: null,
                    'imagem_banner' => $img_banner ?: null,
                    'destaque' => $destaque,
                    'status' => $status,
                    'instagram' => $instagram ?: null,
                    'facebook' => $facebook ?: null
                ]);

                $adId = $pdo->lastInsertId();

                if (!empty($_FILES['galeria']['name'][0])) {
                    foreach ($_FILES['galeria']['tmp_name'] as $key => $tmpName) {
                        if ($_FILES['galeria']['error'][$key] === UPLOAD_ERR_OK) {
                            $fileData = ['name' => $_FILES['galeria']['name'][$key], 'type' => $_FILES['galeria']['type'][$key], 'tmp_name' => $tmpName, 'error' => $_FILES['galeria']['error'][$key], 'size' => $_FILES['galeria']['size'][$key]];
                            $path = upload_image($fileData, 'ads/gallery');
                            if ($path) {
                                $stmtImg = $pdo->prepare("INSERT INTO imagens (anuncio_id, caminho, ordem) VALUES (?, ?, ?)");
                                $stmtImg->execute([$adId, $path, $key]);
                            }
                        }
                    }
                }

                $dias = ['seg', 'ter', 'qua', 'qui', 'sex', 'sab', 'dom'];
                $stmtSched = $pdo->prepare("INSERT INTO horarios (anuncio_id, dia_semana, abertura, fechamento, fechado) VALUES (?, ?, ?, ?, ?)");
                foreach ($dias as $index => $dia) {
                    $abertura = $_POST["horario_{$dia}_abre"] ?? null;
                    $fechamento = $_POST["horario_{$dia}_fecha"] ?? null;
                    $fechado = isset($_POST["horario_{$dia}_fechado"]) ? 1 : 0;
                    $stmtSched->execute([$adId, $index + 1, $abertura, $fechamento, $fechado]);
                }

                $pdo->commit();
                $message = 'Anúncio cadastrado com sucesso!';
                $_POST = [];
            } catch (Exception $e) {
                $pdo->rollBack();
                $error = 'Erro ao salvar: ' . $e->getMessage();
            }
        }
    }
}

$headerButtons = '<a href="/admin/dashboard" class="btn btn-outline">Voltar ao Dashboard</a>';
render_admin_header('Cadastrar Anúncio', 'anuncios', $headerButtons);
?>

<div class="container-form" style="color: var(--foreground);">
    <style>
        .container-form { max-width: 1000px; margin: 2rem auto; padding: 0 1rem; width: 100%; color: var(--foreground); }
        .form-card { background: var(--card); border: 1px solid var(--border); border-radius: 1rem; padding: 2rem; margin-bottom: 2rem; color: var(--foreground); }
        .form-section-title { font-size: 1.1rem; font-weight: 700; margin-bottom: 1.5rem; color: var(--primary); border-bottom: 2px solid var(--border); padding-bottom: 0.5rem; }
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; }
        .full-width { grid-column: span 2; }
        .form-group { display: flex; flex-direction: column; gap: 0.5rem; }
        .form-group label { font-size: 0.875rem; font-weight: 600; color: var(--foreground); }
        .form-group input, .form-group select, .form-group textarea { padding: 0.75rem 1rem; border: 1px solid var(--border); border-radius: 0.5rem; background: var(--muted-bg); color: var(--foreground); }
        .btn-save { background: var(--primary); color: white; border: none; padding: 1rem 2rem; border-radius: 0.5rem; font-weight: 700; cursor: pointer; width: 100%; margin-top: 2rem; }
        .schedule-row { display: grid; grid-template-columns: 120px 1fr 1fr 100px; gap: 1rem; align-items: center; margin-bottom: 0.5rem; }
        .image-preview { width: 100px; height: 100px; object-fit: cover; border-radius: 0.5rem; border: 1px solid var(--border); }
    </style>

    <?php if ($message): ?>
        <div class="alert alert-success" style="padding:1rem; background:#dcfce7; color:#166534; border-radius:0.5rem; margin-bottom:2rem;"><?php echo e($message); ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-danger" style="padding:1rem; background:#fee2e2; color:#991b1b; border-radius:0.5rem; margin-bottom:2rem;"><?php echo e($error); ?></div>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data" onsubmit="return validateTotalSize(this)">
        <?php echo csrf_field(); ?>

        <div class="form-card">
            <h2 class="form-section-title">Dados do Anúncio</h2>
            <div class="form-grid">
                <div class="form-group full-width">
                    <label>Título *</label>
                    <input type="text" name="titulo" required>
                </div>
                <div class="form-group">
                    <label>Categoria *</label>
                    <select name="categoria_id" required>
                        <option value="">Selecione...</option>
                        <?php foreach($categories as $cat): ?>
                            <option value="<?php echo $cat['id']; ?>"><?php echo e($cat['nome']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Cidade *</label>
                    <input type="text" name="cidade" id="ad_cidade" required>
                </div>
                <div class="form-group">
                    <label>Região</label>
                    <select name="regiao" id="ad_regiao">
                        <option value="">Selecione...</option>
                        <?php foreach($regions as $r): ?>
                            <option value="<?php echo e($r); ?>"><?php echo e($r); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group full-width">
                    <label>Descrição *</label>
                    <textarea name="descricao" rows="5" required></textarea>
                </div>
            </div>
        </div>

        <div class="form-card">
            <h2 class="form-section-title">Horários</h2>
            <?php 
            $diasLabels = ['Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado', 'Domingo'];
            $diasKeys = ['seg', 'ter', 'qua', 'qui', 'sex', 'sab', 'dom'];
            foreach($diasKeys as $i => $k): ?>
            <div class="schedule-row">
                <span><?php echo $diasLabels[$i]; ?></span>
                <input type="time" name="horario_<?php echo $k; ?>_abre" value="08:00">
                <input type="time" name="horario_<?php echo $k; ?>_fecha" value="18:00">
                <label><input type="checkbox" name="horario_<?php echo $k; ?>_fechado"> Fechado</label>
            </div>
            <?php endforeach; ?>
        </div>

        <div class="form-card">
            <h2 class="form-section-title">Contato e Profissional</h2>
            <div class="form-grid">
                <div class="form-group full-width">
                    <label>Vincular a Profissional Existente</label>
                    <select id="selectClient" name="cliente_id_selected">
                        <option value="">-- Novo Profissional --</option>
                        <?php foreach(fetch_all_clients($pdo) as $c): ?>
                            <option value="<?php echo $c['id']; ?>" data-email="<?php echo e($c['email']); ?>" data-telefone="<?php echo e($c['telefone']); ?>" data-whatsapp="<?php echo e($c['whatsapp']); ?>" data-cidade="<?php echo e($c['cidade']); ?>" data-regiao="<?php echo e($c['regiao']); ?>">
                                <?php echo e($c['nome']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>E-mail</label>
                    <input type="email" name="email" id="email">
                </div>
                <div class="form-group">
                    <label>Telefone *</label>
                    <input type="text" name="telefone" id="telefone" required>
                </div>
                <div class="form-group">
                    <label>WhatsApp</label>
                    <input type="text" name="whatsapp" id="whatsapp">
                </div>
            </div>
        </div>

        <div class="form-card">
            <h2 class="form-section-title">Imagens Principais</h2>
            <div class="form-grid">
                <div class="form-group">
                    <label>Foto de Perfil / Logo</label>
                    <input type="file" name="imagem_principal" accept="image/*">
                </div>
                <div class="form-group">
                    <label>Banner de Fundo</label>
                    <input type="file" name="imagem_banner" accept="image/*">
                </div>
            </div>
        </div>

        <div class="form-card">
            <h2 class="form-section-title">Galeria de Trabalhos / Produtos</h2>
            <p style="font-size: 0.85rem; color: var(--muted-foreground); margin-bottom: 1.5rem;">Adicione várias fotos que demonstrem seus serviços ou produtos. Elas aparecerão como um carrossel na página do anúncio.</p>
            <div class="form-group full-width">
                <label>Selecionar Fotos (Várias)</label>
                <input type="file" name="galeria[]" id="galleryInput" multiple accept="image/*" style="padding: 1.5rem; border-style: dashed; border-width: 2px;">
                <div id="galleryPreview" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 1rem; margin-top: 1.5rem;">
                    <!-- Preview das imagens aparecerá aqui -->
                </div>
            </div>
        </div>

        <button type="submit" class="btn-save">Salvar Anúncio</button>
    </form>
</div>

<script>
    const cityMapping = <?php echo json_encode($cityMapping); ?>;
    document.addEventListener('DOMContentLoaded', function() {
        const adCidade = document.getElementById('ad_cidade');
        const adRegiao = document.getElementById('ad_regiao');
        adCidade.addEventListener('input', (e) => {
            if (cityMapping[e.target.value]) adRegiao.value = cityMapping[e.target.value];
        });

        const selectClient = document.getElementById('selectClient');
        if (selectClient) {
            selectClient.addEventListener('change', function() {
                const opt = this.options[this.selectedIndex];
                if (opt.value) {
                    document.getElementById('email').value = opt.dataset.email;
                    document.getElementById('telefone').value = opt.dataset.telefone;
                    document.getElementById('whatsapp').value = opt.dataset.whatsapp;
                    adCidade.value = opt.dataset.cidade;
                    adRegiao.value = opt.dataset.regiao;
                }
            });
        }
        const galleryInput = document.getElementById('galleryInput');
        if (galleryInput) {
            galleryInput.addEventListener('change', function(e) {
                const preview = document.getElementById('galleryPreview');
                preview.innerHTML = '';
                for (let file of e.target.files) {
                    if (file.size > 200 * 1024 * 1024) { alert(`O arquivo "${file.name}" excede 200MB.`); e.target.value = ''; preview.innerHTML = ''; return; }
                    const reader = new FileReader();
                    reader.onload = (event) => {
                        const div = document.createElement('div');
                        div.className = 'image-preview-box';
                        div.innerHTML = `<img src="${event.target.result}" style="width:100%; height:100%; object-fit:cover; border-radius:0.5rem; border:1px solid var(--border);">`;
                        preview.appendChild(div);
                    };
                    reader.readAsDataURL(file);
                }
            });
        }
    });
    function validateTotalSize(form) {
        let total = 0;
        const files = form.querySelectorAll('input[type="file"]');
        files.forEach(f => {
            for (let i = 0; i < f.files.length; i++) {
                total += f.files[i].size;
            }
        });
        if (total > 200 * 1024 * 1024) {
            alert('Erro: O tamanho total das imagens (' + (total / 1024 / 1024).toFixed(2) + 'MB) excede o limite do servidor (200MB).');
            return false;
        }
        return true;
    }
</script>

<?php render_admin_footer(); ?>
