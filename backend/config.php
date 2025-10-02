<?php
require_once __DIR__ . '/env_loader.php';
loadEnv(__DIR__ . '/.env');
return [
    'db_host' => $_ENV['DB_HOST'] ?? 'localhost',
    'db_name' => $_ENV['DB_NAME'] ?? '',
    'db_user' => $_ENV['DB_USER'] ?? '',
    'db_pass' => $_ENV['DB_PASS'] ?? '',
    'admin_email' => $_ENV['ADMIN_EMAIL'] ?? '',
    'site_origin' => $_ENV['SITE_ORIGIN'] ?? '*',
    'max_reviews_per_ip_per_day' => 5,
    'admin_user' => $_ENV['ADMIN_USER'] ?? 'admin',
    'admin_pass' => $_ENV['ADMIN_PASS'] ?? 'changeme',
];
