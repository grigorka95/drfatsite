<?php
// backend/api/list_reviews.php
require __DIR__ . '/../functions.php';
$config = require __DIR__ . '/../config.php';

$secret = $_GET['secret'] ?? $_POST['secret'] ?? '';
// Здесь выставьте секрет в config или используйте env
$ADMIN_SECRET = 'verysecretkey'; // замените и храните безопасно

if ($secret !== $ADMIN_SECRET) {
    json_response(['error' => 'Unauthorized'], 401);
}

$pdo = getPDO();

// optional: ?status=approved|pending
$status = $_GET['status'] ?? null;
if ($status) {
    $stmt = $pdo->prepare("SELECT * FROM reviews WHERE status = :status ORDER BY created_at DESC");
    $stmt->execute([':status' => $status]);
} else {
    $stmt = $pdo->query("SELECT * FROM reviews ORDER BY created_at DESC");
}
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
json_response(['data' => $rows]);