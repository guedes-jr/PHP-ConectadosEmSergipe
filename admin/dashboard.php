<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

$stmt = $pdo->query(
    'SELECT a.id, a.titulo, a.cidade, a.destaque, a.status, a.created_at, c.nome AS categoria_nome
     FROM anuncios a
     INNER JOIN categorias c ON c.id = a.categoria_id
     ORDER BY a.created_at DESC
     LIMIT 100'
);
$ads = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="/assets/css/admin.css">
</head>
<body class="admin-body">
    <header class="admin-topbar">
        <h1>Dashboard</h1>
        <nav>
            <a href="/admin/criar">Novo anúncio</a>
            <a href="/admin/logout">Sair</a>
        </nav>
    </header>
    <main class="admin-main">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Título</th>
                    <th>Categoria</th>
                    <th>Cidade</th>
                    <th>Destaque</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($ads as $ad): ?>
                    <tr>
                        <td><?php echo e($ad['titulo']); ?></td>
                        <td><?php echo e($ad['categoria_nome']); ?></td>
                        <td><?php echo e($ad['cidade']); ?></td>
                        <td><?php echo (int)$ad['destaque'] === 1 ? 'Sim' : 'Não'; ?></td>
                        <td><?php echo e($ad['status']); ?></td>
                        <td>
                            <a href="/admin/editar/<?php echo (int)$ad['id']; ?>">Editar</a>
                            <form method="post" action="/admin/excluir/<?php echo (int)$ad['id']; ?>" style="display:inline">
                                <?php echo csrf_field(); ?>
                                <button type="submit" onclick="return confirm('Excluir este anúncio?')" class="btn-link">Excluir</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>
</body>
</html>