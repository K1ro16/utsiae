<?php
// Di file app/Http/Kernel.php Course service
protected $middleware = [
    // ...
    \Fruitcake\Cors\HandleCors::class,
];

// config/cors.php
return [
    'paths' => ['api/*'],
    'allowed_origins' => ['*'],
    'allowed_methods' => ['*'],
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => false,
];