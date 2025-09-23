<?php
require __DIR__ . '/../functions.php';
header('Content-Type: application/json; charset=utf-8');
if (session_status() === PHP_SESSION_NONE) session_start();
echo json_encode(['token' => getCsrfToken()]);
