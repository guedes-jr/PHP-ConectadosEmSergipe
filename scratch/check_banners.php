<?php
require_once __DIR__ . '/../includes/db.php';
$stmt = $pdo->query("SELECT chave, valor FROM configuracoes WHERE chave LIKE 'hero_banner_%'");
foreach ($stmt->fetchAll() as $row) {
    echo $row['chave'] . ' => ' . $row['valor'] . "\n";
}
