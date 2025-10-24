<?php
// backend/session_init.php
// Вызывать до вывода любого содержимого и до session_start в других файлах
$cookieParams = session_get_cookie_params();
session_set_cookie_params([
    'lifetime' => $cookieParams['lifetime'],
    'path' => $cookieParams['path'],
    'domain' => $cookieParams['domain'],
    'secure' => false,            // true на prod (HTTPS)
    'httponly' => true,
    'samesite' => 'Lax'          // или 'Strict' если можно
]);
// теперь стартуем сессию
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
