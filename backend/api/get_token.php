<?php
require __DIR__ . '/../function.php';
header('Content-Type: application/json');
echo json_encode(['token' => getCsrfToken()]);
