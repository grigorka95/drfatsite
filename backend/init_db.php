<?php
// backend/init_db.php
require __DIR__ . '/db.php';

$pdo = getPDO();

$pdo->exec("
CREATE TABLE IF NOT EXISTS reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(200) NOT NULL,
    rating INT NOT NULL CHECK(rating BETWEEN 1 AND 5),
    message TEXT NOT NULL,
    ip VARCHAR(45),
    status ENUM('pending', 'approved', 'deleted') NOT NULL DEFAULT 'pendeing',
    hash CHAR(64) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
");

$pdo->exec("CREATE INDEX idx_reviews_status ON reviews(status);");
$pdo->exec("CREATE INDEX idx_reviews_hash ON reviews(hash);");

echo "DB initialized\n";
