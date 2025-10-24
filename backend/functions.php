<?php
// backend/functions.php
require_once __DIR__ . '/db.php';
$config = require __DIR__ . '/config.php';

ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL); 

session_start();
function getCsrfToken() : string {
    if (session_status() !== PHP_SESSION_ACTIVE) session_start();
    if (empty($_SESSION['csrf_token'])){
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}
function validateCsrfToken(?string $token) : bool {
    if (session_status() !== PHP_SESSION_ACTIVE) session_start();
    if (empty($_SESSION['csrf_token'])) return false;
    return hash_equals($_SESSION['csrf_token'], (string)$token);
}
function json_response($data, $code = 200) {
    header('Content-Type: application/json; charset=utf-8');
    http_response_code($code);
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

function clean_text($s) {
    $s = trim($s);
    // удаляем лишние пробелы
    $s = preg_replace('/\s{2,}/u', ' ', $s);
    return $s;
}

function make_hash($name, $rating, $message) {
    return hash('sha256', mb_strtolower($name . '|' . $rating . '|' . $message));
}

function ip_address() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) return $_SERVER['HTTP_CLIENT_IP'];
    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) return explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0];
    return $_SERVER['REMOTE_ADDR'] ?? 'unknown';
}

function is_duplicate($hash) {
    $pdo = getPDO();
    $stmt = $pdo->prepare('SELECT COUNT(*) FROM reviews WHERE hash = :hash AND status != "deleted"');
    $stmt->execute([':hash' => $hash]);
    return (int)$stmt->fetchColumn() > 0;
}

function ip_count_today($ip) {
    $pdo = getPDO();
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM reviews WHERE ip = :ip AND DATE(created_at) >= CURDATE()");
    $stmt->execute([':ip' => $ip]);
    return (int)$stmt->fetchColumn();
}

function notify_admin($name, $rating, $message, $admin_email) {
    $subject = "Новый отзыв — требуется модерация";
    $body = "Поступил новый отзыв:\n\nИмя: {$name}\nОценка: {$rating}\nСообщение:\n{$message}\n\nПерейдите в панель администрирования для модерации.";
    // Простая отправка mail(). На проде рекомендую PHPMailer / SMTP.
    @mail($admin_email, $subject, $body, "From: no-reply@example.com\r\nContent-Type: text/plain; charset=UTF-8");
}
