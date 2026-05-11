<?php

declare(strict_types=1);

$dir = __DIR__ . '/..';
$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));

$count = 0;
foreach ($files as $file) {
    if ($file->isDir()) continue;
    if ($file->getExtension() !== 'php') continue;
    
    $path = $file->getPathname();
    if (str_contains($path, 'scratch/')) continue;
    
    $content = file_get_contents($path);
    $original = $content;

    // Pattern for imagem_principal
    $content = preg_replace(
        '/src="\/<\?php echo e\(\$ad\[\'imagem_principal\'\](?:\s*\?:\s*\'[^\']+\')?\);\s*\?>"/i',
        'src="<?php echo asset_url($ad[\'imagem_principal\']); ?>"',
        $content
    );

    // Pattern for imagem_banner
    $content = preg_replace(
        '/src="\/<\?php echo e\(\$ad\[\'imagem_banner\'\]\);\s*\?>"/i',
        'src="<?php echo asset_url($ad[\'imagem_banner\']); ?>"',
        $content
    );

    // Pattern for caminho (src)
    $content = preg_replace(
        '/src="\/<\?php echo e\(\$img\[\'caminho\'\]\);\s*\?>"/i',
        'src="<?php echo asset_url($img[\'caminho\']); ?>"',
        $content
    );

    // Pattern for caminho (href)
    $content = preg_replace(
        '/href="\/<\?php echo e\(\$img\[\'caminho\'\]\);\s*\?>"/i',
        'href="<?php echo asset_url($img[\'caminho\']); ?>"',
        $content
    );

    if ($content !== $original) {
        file_put_contents($path, $content);
        echo "Updated: $path\n";
        $count++;
    }
}

echo "Total files updated: $count\n";
