<?php

// backend/config.php

return [
    'db_path' => __DIR__ . '/../data/reviews.sqlite',
    'admin_email' => 'dr.fatenko@example.com', // поставьте нужный email
    'site_origin' => 'https://example.com', // для CORS / безопасности, замените на ваш домен (или '*')
    'max_reviews_per_ip_per_day' => 5,
];