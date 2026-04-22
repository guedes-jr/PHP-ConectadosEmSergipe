<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

require_admin();
$categories = fetch_all_categories($pdo);
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validate_csrf()) {
        $message = 'Token inválido. Tente novamente.';
    } else {
        $titulo = trim((string)($_POST['titulo'] ?? ''));
    $descricao = trim((string)($_POST['descricao'] ?? ''));
    $categoriaId = (int)($_POST['categoria_id'] ?? 0);
    $telefone = trim((string)($_POST['telefone'] ?? ''));
    $cidade = trim((string)($_POST['cidade'] ?? ''));
    $destaque = isset($_POST['destaque']) ? 1 : 0;
    $status = ($_POST['status'] ?? 'ativo') === 'inativo' ? 'inativo' : 'ativo';
    $slug = slugify($titulo);

    if ($titulo !== '' && $descricao !== '' && $categoriaId > 0 && $telefone !== '' && $cidade !== '') {
        $stmt = $pdo->prepare(
            'INSERT INTO anuncios (titulo, slug, descricao, categoria_id, telefone, cidade, destaque, status)
             VALUES (:titulo, :slug, :descricao, :categoria_id, :telefone, :cidade, :destaque, :status)'
        );
        $stmt->execute([
            'titulo' => $titulo,
            'slug' => $slug,
            'descricao' => $descricao,
            'categoria_id' => $categoriaId,
            'telefone' => $telefone,
            'cidade' => $cidade,
            'destaque' => $destaque,
            'status' => $status,
        ]);
        $message = 'Anúncio criado com sucesso. Implemente o upload múltiplo na próxima etapa.';
    }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar anúncio</title>
    <link rel="stylesheet" href="/assets/css/admin.css">
</head>
<body class="admin-body">
    <main class="admin-form-wrap">
        <h1>Novo anúncio</h1>
        <?php if ($message !== ''): ?><p class="alert-success"><?php echo e($message); ?></p><?php endif; ?>
        <form class="admin-form" method="post" enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            <label>Título</label>
            <input type="text" name="titulo" required>

            <label>Descrição</label>
            <textarea name="descricao" rows="6" required></textarea>

            <label>Categoria</label>
            <select name="categoria_id" required>
                <option value="">Selecione</option>
                <?php foreach ($categories as $category): ?>
                    <option value="<?php echo (int)$category['id']; ?>"><?php echo e($category['nome']); ?></option>
                <?php endforeach; ?>
            </select>

            <label>Telefone</label>
            <input type="text" name="telefone" required>

            <label>Cidade</label>
            <input type="text" name="cidade" required>

            <label class="checkbox"><input type="checkbox" name="destaque"> Em destaque</label>

            <label>Status</label>
            <select name="status">
                <option value="ativo">Ativo</option>
                <option value="inativo">Inativo</option>
            </select>

            <label>Imagens</label>
            <input type="file" name="imagens[]" multiple accept=".jpg,.jpeg,.png,.webp">

            <button type="submit">Salvar</button>
        </form>
    </main>
</body>
</html>
