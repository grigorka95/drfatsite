<?php
// backend/db.php
$config = require __DIR__ . '/config.php';

function getPDO() {
    static $pdo = null;
    if ($pdo === null) {
        $cfg = require __DIR__ . '/config.php';
        $path = $cfg['db_path'];
        if (!is_dir(dirname($path))) {
            mkdir(dirname($path), 0755, true);
        }
        $dsn = 'sqlite:' . $path;
        $pdo = new PDO($dsn);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // enable foreign keys
        $pdo->exec('PRAGMA foreign_keys = ON;');
    }
    return $pdo;
}