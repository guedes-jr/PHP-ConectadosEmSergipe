<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/auth.php';

require_admin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!validate_csrf()) {
        header('Location: /admin/dashboard');
        exit;
    }
    $ids = $_POST['ids'] ?? ($_POST['id'] ? [$_POST['id']] : []);
    $type = $_POST['type'] ?? 'anuncios';
    $redirect = $_POST['redirect'] ?? '/admin/anuncios';
    
    if (!empty($ids)) {
        $ids = array_map('intval', (array)$ids);
        $placeholders = str_repeat('?,', count($ids) - 1) . '?';
        
        if ($type === 'clientes') {
            // Se deletar cliente, deleta os anúncios dele também
            $stmt = $pdo->prepare("DELETE FROM anuncios WHERE cliente_id IN ($placeholders)");
            $stmt->execute($ids);
            
            $stmt = $pdo->prepare("DELETE FROM clientes WHERE id IN ($placeholders)");
            $stmt->execute($ids);
        } else {
            $stmt = $pdo->prepare("DELETE FROM anuncios WHERE id IN ($placeholders)");
            $stmt->execute($ids);
        }
    }
}

header('Location: ' . $redirect);
exit;