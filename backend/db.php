<?php
// backend/db.php
$config = require __DIR__ . '/config.php';

function getPDO() {
    static $pdo = null;
    if ($pdo === null) {
        $cfg = require __DIR__ . '/config.php';
        $dsn = "mysql:host={$cfg['db_host']};dbname = {$cfg['db_name']};charset=utf8mb4";
        $pdo = new PDO($dsn, $cfg['db_user'], $cfg['db_pass']);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    return $pdo;
}
