<?php
require __DIR__ . '/../functions.php';
header('Content-Type: application/json');
echo json_encode(['token' => getCsrfToken()]);
