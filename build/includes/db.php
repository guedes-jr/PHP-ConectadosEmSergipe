<?php

declare(strict_types=1);

require_once __DIR__ . '/config.php';

$useMySQL = defined('DB_NAME') && DB_NAME !== '';

if ($useMySQL) {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];
    try {
        $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
    } catch (PDOException $exception) {
        exit('Erro ao conectar com o banco de dados MySQL.');
    }
} else {
    $dsn = 'sqlite:' . __DIR__ . '/../database/db.sqlite';
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ];
    try {
        $pdo = new PDO($dsn, null, null, $options);
    } catch (PDOException $exception) {
        exit('Erro ao conectar com o banco de dados SQLite.');
    }
}