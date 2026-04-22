<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

require_admin();
$id = (int)($_GET['id'] ?? 0);
$categories = fetch_all_categories($pdo);
$message = '';

$stmt = $pdo->prepare('SELECT * FROM anuncios WHERE id = :id LIMIT 1');
$stmt->execute(['id' => $id]);
$ad = $stmt->fetch();

if (!$ad) {
    exit('Anúncio não encontrado.');
}

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

    $update = $pdo->prepare(
        'UPDATE anuncios
         SET titulo = :titulo, slug = :slug, descricao = :descricao, categoria_id = :categoria_id,
             telefone = :telefone, cidade = :cidade, destaque = :destaque, status = :status
         WHERE id = :id'
    );
    $update->execute([
        'titulo' => $titulo,
        'slug' => $slug,
        'descricao' => $descricao,
        'categoria_id' => $categoriaId,
        'telefone' => $telefone,
        'cidade' => $cidade,
        'destaque' => $destaque,
        'status' => $status,
        'id' => $id,
    ]);

    $message = 'Anúncio atualizado com sucesso.';
    $stmt->execute(['id' => $id]);
    $ad = $stmt->fetch();
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar anúncio</title>
    <link rel="stylesheet" href="/assets/css/admin.css">
</head>
<body class="admin-body">
    <main class="admin-form-wrap">
        <h1>Editar anúncio</h1>
        <?php if ($message !== ''): ?><p class="alert-success"><?php echo e($message); ?></p><?php endif; ?>
        <form class="admin-form" method="post">
            <?php echo csrf_field(); ?>
            <label>Título</label>
            <input type="text" name="titulo" value="<?php echo e($ad['titulo']); ?>" required>

            <label>Descrição</label>
            <textarea name="descricao" rows="6" required><?php echo e($ad['descricao']); ?></textarea>

            <label>Categoria</label>
            <select name="categoria_id" required>
                <?php foreach ($categories as $category): ?>
                    <option value="<?php echo (int)$category['id']; ?>" <?php echo (int)$category['id'] === (int)$ad['categoria_id'] ? 'selected' : ''; ?>><?php echo e($category['nome']); ?></option>
                <?php endforeach; ?>
            </select>

            <label>Telefone</label>
            <input type="text" name="telefone" value="<?php echo e($ad['telefone']); ?>" required>

            <label>Cidade</label>
            <input type="text" name="cidade" value="<?php echo e($ad['cidade']); ?>" required>

            <label class="checkbox"><input type="checkbox" name="destaque" <?php echo (int)$ad['destaque'] === 1 ? 'checked' : ''; ?>> Em destaque</label>

            <label>Status</label>
            <select name="status">
                <option value="ativo" <?php echo $ad['status'] === 'ativo' ? 'selected' : ''; ?>>Ativo</option>
                <option value="inativo" <?php echo $ad['status'] === 'inativo' ? 'selected' : ''; ?>>Inativo</option>
            </select>

            <button type="submit">Atualizar</button>
        </form>
    </main>
</body>
</html>
