<?php
require_once __DIR__ . '/../includes/db.php';
$stmt = $pdo->query("SELECT DISTINCT categoria FROM configuracoes");
foreach ($stmt->fetchAll() as $row) {
    echo $row['categoria'] . "\n";
}
