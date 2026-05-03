<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/admin_layout.php';
require_once __DIR__ . '/../includes/sergipe_data.php';

require_admin();

$id = (int)($_GET['id'] ?? 0);
$error = '';
$message = '';
$regions = get_sergipe_regions();
$cityMapping = get_city_region_mapping();

$stmt = $pdo->prepare("SELECT * FROM clientes WHERE id = ?");
$stmt->execute([$id]);
$client = $stmt->fetch();

if (!$client) {
    header('Location: /admin/clientes');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validate_csrf()) {
        $error = 'Token de segurança inválido.';
    } else {
        $nome = trim($_POST['nome'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $telefone = trim($_POST['telefone'] ?? '');
        $whatsapp = trim($_POST['whatsapp'] ?? '');
        $cep = trim($_POST['cep'] ?? '');
        $rua = trim($_POST['rua'] ?? '');
        $bairro = trim($_POST['bairro'] ?? '');
        $cidade = trim($_POST['cidade'] ?? '');
        $estado = trim($_POST['estado'] ?? '');
        $regiao = trim($_POST['regiao'] ?? '');

        if (!$nome || !$email) {
            $error = 'Nome e E-mail são obrigatórios.';
        } else {
            try {
                $stmt = $pdo->prepare("UPDATE clientes SET nome = ?, email = ?, telefone = ?, whatsapp = ?, cep = ?, rua = ?, bairro = ?, cidade = ?, estado = ?, regiao = ? WHERE id = ?");
                $stmt->execute([$nome, $email, $telefone, $whatsapp, $cep, $rua, $bairro, $cidade, $estado, $regiao, $id]);
                $message = 'Dados atualizados com sucesso!';
                
                // Refresh data
                $stmt = $pdo->prepare("SELECT * FROM clientes WHERE id = ?");
                $stmt->execute([$id]);
                $client = $stmt->fetch();
            } catch (Exception $e) {
                $error = 'Erro ao salvar: ' . $e->getMessage();
            }
        }
    }
}

render_admin_header('Editar Profissional', 'clientes');
?>

<header class="top-header">
    <h1>Editar Profissional</h1>
    <a href="/admin/clientes" style="color:var(--muted-foreground); text-decoration:none; font-size:0.875rem;">Voltar para Lista</a>
</header>

<div class="dashboard-container" style="max-width: 900px; margin: 0 auto;">
    <?php if ($message): ?>
        <div style="background:#dcfce7; color:#166534; padding:1rem; border-radius:0.75rem; margin-bottom:2rem; border:1px solid #bbf7d0;">
            <?php echo e($message); ?>
        </div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div style="background:#fee2e2; color:#991b1b; padding:1rem; border-radius:0.75rem; margin-bottom:2rem; border:1px solid #fecaca;">
            <?php echo e($error); ?>
        </div>
    <?php endif; ?>

    <form method="post" id="clientForm" style="background:var(--card); border:1px solid var(--border); border-radius:1.5rem; padding:2.5rem; box-shadow:var(--shadow-sm);">
        <?php echo csrf_field(); ?>
        
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:1.5rem;">
            <h2 style="grid-column: span 2; font-size: 1rem; color: var(--primary); font-weight: 700; margin-bottom: 0.5rem; border-bottom: 1px solid var(--border); padding-bottom: 0.5rem;">Informações de Contato</h2>

            <div style="grid-column: span 2; display:flex; flex-direction:column; gap:0.5rem;">
                <label style="font-size:0.875rem; font-weight:600;">Nome Completo / Nome Fantasia *</label>
                <input type="text" name="nome" value="<?php echo e($client['nome']); ?>" required style="padding:0.75rem 1rem; border:1px solid var(--border); border-radius:0.75rem; background:var(--muted-bg); color:var(--foreground);">
            </div>

            <div style="display:flex; flex-direction:column; gap:0.5rem;">
                <label style="font-size:0.875rem; font-weight:600;">E-mail *</label>
                <input type="email" name="email" value="<?php echo e($client['email']); ?>" required style="padding:0.75rem 1rem; border:1px solid var(--border); border-radius:0.75rem; background:var(--muted-bg); color:var(--foreground);">
            </div>

            <div style="display:flex; flex-direction:column; gap:0.5rem;">
                <label style="font-size:0.875rem; font-weight:600;">Telefone Principal *</label>
                <input type="text" name="telefone" id="telefone" value="<?php echo e($client['telefone']); ?>" required maxlength="15" style="padding:0.75rem 1rem; border:1px solid var(--border); border-radius:0.75rem; background:var(--muted-bg); color:var(--foreground);">
                <label style="font-size:0.75rem; display:flex; align-items:center; gap:0.5rem; cursor:pointer; color:var(--muted-foreground); margin-top:0.25rem;">
                    <input type="checkbox" id="syncWhatsapp" <?php echo $client['telefone'] === $client['whatsapp'] ? 'checked' : ''; ?>> WhatsApp é o mesmo do telefone
                </label>
            </div>

            <div style="display:flex; flex-direction:column; gap:0.5rem;">
                <label style="font-size:0.875rem; font-weight:600;">WhatsApp</label>
                <input type="text" name="whatsapp" id="whatsapp" value="<?php echo e($client['whatsapp']); ?>" maxlength="15" style="padding:0.75rem 1rem; border:1px solid var(--border); border-radius:0.75rem; background:var(--muted-bg); color:var(--foreground);">
            </div>

            <h2 style="grid-column: span 2; font-size: 1rem; color: var(--primary); font-weight: 700; margin-top: 1rem; margin-bottom: 0.5rem; border-bottom: 1px solid var(--border); padding-bottom: 0.5rem;">Endereço</h2>

            <div style="display:flex; flex-direction:column; gap:0.5rem;">
                <label style="font-size:0.875rem; font-weight:600;">CEP</label>
                <input type="text" name="cep" id="cep" value="<?php echo e($client['cep'] ?? ''); ?>" placeholder="00000-000" style="padding:0.75rem 1rem; border:1px solid var(--border); border-radius:0.75rem; background:var(--muted-bg); color:var(--foreground);">
            </div>

            <div style="display:flex; flex-direction:column; gap:0.5rem;">
                <label style="font-size:0.875rem; font-weight:600;">Logradouro (Rua)</label>
                <input type="text" name="rua" id="rua" value="<?php echo e($client['rua'] ?? ''); ?>" style="padding:0.75rem 1rem; border:1px solid var(--border); border-radius:0.75rem; background:var(--muted-bg); color:var(--foreground);">
            </div>

            <div style="display:flex; flex-direction:column; gap:0.5rem;">
                <label style="font-size:0.875rem; font-weight:600;">Bairro</label>
                <input type="text" name="bairro" id="bairro" value="<?php echo e($client['bairro'] ?? ''); ?>" style="padding:0.75rem 1rem; border:1px solid var(--border); border-radius:0.75rem; background:var(--muted-bg); color:var(--foreground);">
            </div>

            <div style="display:flex; flex-direction:column; gap:0.5rem;">
                <label style="font-size:0.875rem; font-weight:600;">Cidade</label>
                <input type="text" name="cidade" id="cidade" value="<?php echo e($client['cidade']); ?>" style="padding:0.75rem 1rem; border:1px solid var(--border); border-radius:0.75rem; background:var(--muted-bg); color:var(--foreground);">
            </div>

            <div style="display:flex; flex-direction:column; gap:0.5rem;">
                <label style="font-size:0.875rem; font-weight:600;">Região de Sergipe</label>
                <select name="regiao" id="regiao" style="padding:0.75rem 1rem; border:1px solid var(--border); border-radius:0.75rem; background:var(--muted-bg); color:var(--foreground);">
                    <option value="">Selecione uma região</option>
                    <?php foreach ($regions as $r): ?>
                        <option value="<?php echo e($r); ?>" <?php echo $client['regiao'] === $r ? 'selected' : ''; ?>><?php echo e($r); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div style="display:flex; flex-direction:column; gap:0.5rem;">
                <label style="font-size:0.875rem; font-weight:600;">Estado (UF)</label>
                <input type="text" name="estado" id="estado" value="<?php echo e($client['estado'] ?? ''); ?>" maxlength="2" placeholder="SE" style="padding:0.75rem 1rem; border:1px solid var(--border); border-radius:0.75rem; background:var(--muted-bg); color:var(--foreground);">
            </div>
        </div>

        <button type="submit" class="btn btn-primary" style="margin-top:2rem; width:100%; padding:1rem; border-radius:0.75rem; font-weight:700;">Salvar Alterações</button>
    </form>
</div>

<script>
    const cityMapping = <?php echo json_encode($cityMapping); ?>;

    document.addEventListener('DOMContentLoaded', function() {
        const cepInput = document.getElementById('cep');
        const whatsappInput = document.getElementById('whatsapp');
        const telefoneInput = document.getElementById('telefone');
        const syncWhatsapp = document.getElementById('syncWhatsapp');
        const cidadeInput = document.getElementById('cidade');
        const regiaoSelect = document.getElementById('regiao');

        function updateRegion(city) {
            if (cityMapping[city]) {
                regiaoSelect.value = cityMapping[city];
            }
        }

        cidadeInput.addEventListener('change', (e) => updateRegion(e.target.value));
        cidadeInput.addEventListener('blur', (e) => updateRegion(e.target.value));

        function mask(v, type) {
            v = v.replace(/\D/g, "");
            if (type === 'cep') {
                v = v.replace(/^(\d{5})(\d)/, "$1-$2");
            } else if (type === 'phone') {
                v = v.replace(/^(\d{2})(\d)/g, "($1) $2");
                v = v.replace(/(\d)(\d{4})$/, "$1-$2");
            }
            return v;
        }

        [whatsappInput, telefoneInput].forEach(el => {
            el.addEventListener('input', (e) => {
                e.target.value = mask(e.target.value, 'phone');
                if (syncWhatsapp.checked && e.target.id === 'telefone') {
                    whatsappInput.value = telefoneInput.value;
                }
            });
            if (el.value) el.value = mask(el.value, 'phone');
        });

        cepInput.addEventListener('input', (e) => {
            e.target.value = mask(e.target.value, 'cep');
        });

        syncWhatsapp.addEventListener('change', () => {
            if (syncWhatsapp.checked) {
                whatsappInput.value = telefoneInput.value;
                whatsappInput.readOnly = true;
                whatsappInput.style.opacity = '0.7';
            } else {
                whatsappInput.readOnly = false;
                whatsappInput.style.opacity = '1';
            }
        });

        if (syncWhatsapp.checked) {
            whatsappInput.readOnly = true;
            whatsappInput.style.opacity = '0.7';
        }

        cepInput.addEventListener('blur', function() {
            const cep = this.value.replace(/\D/g, '');
            if (cep.length === 8) {
                fetch(`https://viacep.com.br/ws/${cep}/json/`)
                    .then(res => res.json())
                    .then(data => {
                        if (!data.erro) {
                            document.getElementById('rua').value = data.logradouro;
                            document.getElementById('bairro').value = data.bairro;
                            document.getElementById('cidade').value = data.localidade;
                            document.getElementById('estado').value = data.uf;
                            updateRegion(data.localidade);
                        }
                    });
            }
        });
    });
</script>

<?php render_admin_footer(); ?>
