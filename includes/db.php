<?php

declare(strict_types=1);

require_once __DIR__ . '/config.php';

$useSQLite = file_exists(__DIR__ . '/../database/db.sqlite');

if ($useSQLite) {
    $dsn = 'sqlite:' . __DIR__ . '/../database/db.sqlite';
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ];
} else {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];
}

try {
    $pdo = new PDO($dsn, $useSQLite ? null : DB_USER, $useSQLite ? null : DB_PASS, $options);
} catch (PDOException $exception) {
    exit('Erro ao conectar com o banco de dados.');
}