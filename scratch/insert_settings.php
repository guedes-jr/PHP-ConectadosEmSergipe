<?php
require_once __DIR__ . '/../includes/db.php';
$banners = [
    ['hero_banner_1', '/assets/img/hero-orla.png', 'Banners Principais', 'Banner 1 (Recomendado 1920x1080)'],
    ['hero_banner_2', '/assets/img/sergipe-cidade1.jpg', 'Banners Principais', 'Banner 2 (Recomendado 1920x1080)'],
    ['hero_banner_3', '/assets/img/sergipe-cidade2.jpg', 'Banners Principais', 'Banner 3 (Recomendado 1920x1080)'],
    ['hero_banner_4', '/assets/img/sergipe-cidade3.jpg', 'Banners Principais', 'Banner 4 (Recomendado 1920x1080)'],
    ['hero_banner_5', '/assets/img/caranguejo.png', 'Banners Principais', 'Banner 5 (Recomendado 1920x1080)']
];

foreach ($banners as $b) {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM configuracoes WHERE chave = ?");
    $stmt->execute([$b[0]]);
    if ($stmt->fetchColumn() == 0) {
        $stmt = $pdo->prepare("INSERT INTO configuracoes (chave, valor, categoria, descricao) VALUES (?, ?, ?, ?)");
        $stmt->execute($b);
        echo "Inserted {$b[0]}\n";
    } else {
        echo "{$b[0]} already exists\n";
    }
}
