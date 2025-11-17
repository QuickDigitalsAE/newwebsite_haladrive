<?php
// api_endpoints.php

return [
    'base_url' => 'https://admin.haladrive.ae/api/v1',
    
    'home' => [
        'home' => '/ar/home',
        'create' => '/products',
        'get' => '/products/{id}',
        'update' => '/products/{id}',
        'delete' => '/products/{id}',
        'search' => '/products/search'
    ],
    
    'brand' => [
        'brand' => '/en/brand/{slug}',
    ],
    'car' => [
        'main' => '/ar/car',
        'single' => '/en/car/{id}',
    ]
];
?>