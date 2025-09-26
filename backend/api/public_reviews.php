<?php
require __DIR__ . '/../functions.php';
header('Content-Type: application/json; charset=utf-8');

$pdo = getPDO();
$stmt = $pdo->prepare("SELECT name, rating, message, created_at 
                       FROM reviews 
                       WHERE status = 'approved' 
                       ORDER BY created_at DESC 
                       LIMIT 50");
$stmt->execute();
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
//Экранируем от XSS вывод
foreach ($rows as &$r) {
    $r['name'] = htmlspecialchars($r['name'], ENT_QUOTES | ENT_SUBTITUTE, 'UTF-8');
    $r['message'] = htmlspecialchars($r['message'], ENT_QUOTES | ENT_SUBTITUTE, 'UTF-8');
}
unset($r);

json_response(['data' => $rows]);
