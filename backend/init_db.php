<?php
// backend/init_db.php
require __DIR__ . '/db.php';

$pdo = getPDO();

$pdo->exec("
CREATE TABLE IF NOT EXISTS reviews (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    rating INTEGER NOT NULL CHECK(rating BETWEEN 1 AND 5),
    message TEXT NOT NULL,
    ip TEXT,
    status TEXT NOT NULL DEFAULT 'pending', -- pending | approved | deleted
    hash TEXT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);
");

$pdo->exec("CREATE INDEX IF NOT EXISTS idx_reviews_status ON reviews(status);");
$pdo->exec("CREATE INDEX IF NOT EXISTS idx_reviews_hash ON reviews(hash);");

echo "DB initialized\n";