<?php
require_once __DIR__ . '/../includes/db.php';

try {
    $isSqlite = $pdo->getAttribute(PDO::ATTR_DRIVER_NAME) === 'sqlite';
    
    // Anuncios
    if ($isSqlite) {
        $pdo->exec("ALTER TABLE anuncios ADD COLUMN tipo TEXT DEFAULT 'prestador'");
        $pdo->exec("ALTER TABLE anuncios ADD COLUMN cnpj TEXT DEFAULT NULL");
    } else {
        $pdo->exec("ALTER TABLE anuncios ADD COLUMN tipo ENUM('prestador', 'loja') DEFAULT 'prestador' AFTER categoria_id");
        $pdo->exec("ALTER TABLE anuncios ADD COLUMN cnpj VARCHAR(20) DEFAULT NULL AFTER tipo");
    }
    
    // Clientes (if table exists)
    try {
        if ($isSqlite) {
            $pdo->exec("ALTER TABLE clientes ADD COLUMN tipo TEXT DEFAULT 'prestador'");
            $pdo->exec("ALTER TABLE clientes ADD COLUMN cnpj TEXT DEFAULT NULL");
        } else {
            $pdo->exec("ALTER TABLE clientes ADD COLUMN tipo ENUM('prestador', 'loja') DEFAULT 'prestador'");
            $pdo->exec("ALTER TABLE clientes ADD COLUMN cnpj VARCHAR(20) DEFAULT NULL");
        }
    } catch (Exception $e) {
        echo "Note: Could not update 'clientes' table (maybe it doesn't exist or already updated).\n";
    }
    
    echo "Migration successful!";
} catch (Exception $e) {
    echo "Migration failed: " . $e->getMessage();
}
