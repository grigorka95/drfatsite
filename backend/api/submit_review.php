<?php
// backend/api/submit_review.php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require __DIR__ . '/../functions.php';
$config = require __DIR__ . '/../config.php';

// CORS (если нужно)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header('Access-Control-Allow-Methods: POST, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type');
    header('Access-Control-Allow-Origin: ' . ($config['site_origin'] ?? '*'));
    exit;
}
header('Access-Control-Allow-Origin: ' . ($config['site_origin'] ?? '*'));

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    json_response(['error' => 'Method not allowed'], 405);
}
// Проверка токена получаем тело (может быть JSON)
$raw = file_get_contents('php://input');
$input = json_decode($raw, true);
if (!is_array($input)) {
    $input = $_POST;
}
// CSRF: токен может прийти в поле tcrf или в заголовке X-CSRF-Token
$token = $input['tcrf'] ?? ($_SERVER['HTTP_X_CSRF_TOKEN'] ?? null);
if (empty($token) || !hash_equals($_SESSION['csrf_token'], $token)) {
    json_response(['success' => false, 'error' => 'Неверный CSRF токен'], 403);
}
    
$name = clean_text($input['name'] ?? '');
$rating = intval($input['rating'] ?? 0);
$message = clean_text($input['message'] ?? '');
if (mb_strlen($name, 'UTF-8') > 200) json_response(['error'=>'Имя слишком длинное'], 400);
if (mb_strlen($message, 'UTF-8') > 3000) json_response(['error'=>'Слишком длинное сообщение'], 400);

if ($name === '' || $message === '' || $rating < 1 || $rating > 5) {
    json_response(['error' => 'Неверные данные'], 400);
}

$ip = ip_address();
$hash = make_hash($name, $rating, $message);

// проверка дублей отзывов
if (is_duplicate($hash)) {
    json_response(['error' => 'Похожий отзыв уже существует'], 409);
}

// Лимит отправок с 1 ip адреса
$todayCount = ip_count_today($ip);
if ($todayCount >= ($config['max_reviews_per_ip_per_day'] ?? 5)) {
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

// Уведомление админа
notify_admin($name, $rating, $message, $config['admin_email'] ?? '7trane777@mail.ru');

json_response(['success' => true, 'message' => 'Отзыв принят на модерацию']);
