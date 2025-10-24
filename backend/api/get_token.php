<?php
require __DIR__ . '/../functions.php';
require __DIR__ . '/../session_init.php;
header('Content-Type: application/json; charset=utf-8');
echo json_encode(['token' => getCsrfToken()]);
exit;
