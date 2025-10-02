<?php
// backend/init_db.php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require __DIR__ . '/db.php';

$pdo = getPDO();

$pdo->exec("
CREATE TABLE IF NOT EXISTS reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(200) NOT NULL,
    rating INT NOT NULL CHECK(rating BETWEEN 1 AND 5),
    message TEXT NOT NULL,
    ip VARCHAR(45),
    status ENUM('pending', 'approved', 'deleted') NOT NULL DEFAULT 'pending',
    hash CHAR(64) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
");

try{$pdo->exec("CREATE INDEX idx_reviews_status ON reviews(status);");
   } catch (Exception $e){
    // индекс может уже существовать
}
try{$pdo->exec("CREATE INDEX idx_reviews_hash ON reviews(hash);");
   } catch (Exception $e){
    // игнорируем
}

echo "DB initialized\n";
