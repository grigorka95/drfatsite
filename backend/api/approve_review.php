<?php
// backend/api/approve_review.php
require __DIR__ . '/../functions.php';
$secret = $_POST['secret'] ?? '';
$ADMIN_SECRET = 'verysecretkey'; // должен совпадать

if ($secret !== $ADMIN_SECRET) json_response(['error'=>'Unauthorized'],401);

$id = intval($_POST['id'] ?? 0);
if ($id <= 0) json_response(['error'=>'Bad id'],400);

$pdo = getPDO();
$stmt = $pdo->prepare("UPDATE reviews SET status = 'approved' WHERE id = :id");
$stmt->execute([':id' => $id]);

json_response(['success' => true]);