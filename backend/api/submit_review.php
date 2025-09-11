<?php
// backend/api/submit_review.php
require __DIR__ . '/../functions.php';
$config = require __DIR__ . '/../config.php';

// CORS (если нужно)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header('Access-Control-Allow-Methods: POST, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type');
    header('Access-Control-Allow-Origin: ' . $config['site_origin']);
    exit;
}
header('Access-Control-Allow-Origin: ' . $config['site_origin']);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_response(['error' => 'Method not allowed'], 405);
}

$input = json_decode(file_get_contents('php://input'), true);
if (!$input) {
    // fallback to form-encoded
    $input = $_POST;
}

$name = clean_text($input['name'] ?? '');
$rating = intval($input['rating'] ?? 0);
$message = clean_text($input['message'] ?? '');

if ($name === '' || $message === '' || $rating < 1 || $rating > 5) {
    json_response(['error' => 'Неверные данные'], 400);
}

$ip = ip_address();
$hash = make_hash($name, $rating, $message);

// check duplicate
if (is_duplicate($hash)) {
    json_response(['error' => 'Похожий отзыв уже существует'], 409);
}

// rate limit by IP
$todayCount = ip_count_today($ip);
if ($todayCount >= $config['max_reviews_per_ip_per_day']) {
    json_response(['error' => 'Превышен лимит отправок с вашего IP за сегодня'], 429);
}

// insert
$pdo = getPDO();
$stmt = $pdo->prepare("INSERT INTO reviews (name, rating, message, ip, hash, status) VALUES (:name, :rating, :message, :ip, :hash, 'pending')");
$stmt->execute([
    ':name' => $name,
    ':rating' => $rating,
    ':message' => $message,
    ':ip' => $ip,
    ':hash' => $hash,
]);

// notify admin (async-ish — mail() is synchronous but fine here)
notify_admin($name, $rating, $message, $config['admin_email']);

json_response(['success' => true, 'message' => 'Отзыв принят на модерацию']);