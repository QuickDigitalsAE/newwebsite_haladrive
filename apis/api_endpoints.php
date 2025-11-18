<?php
// api_endpoints.php

return [
    'base_url' => 'https://admin.haladrive.ae/api/v1',
    
    'webcontent' => [
        'home' => '/en/home',
        'about' => '/en/about',
        'faq' => '/en/faq',
        'privacy-policy' => '/en/privacy-policy',
    ],
    
    'brand' => [
        'brand' => '/en/brand/{id}',
    ],
    'car' => [
        'main' => '/en/car',
        'single' => '/en/car/{id}',
    ],
    'lease' => [
        'lease' => '/en/lease/{id}',
    ],
    'location' => [
        'main' => '/en/location',
        'single' => '/en/location/{id}',
    ],
];
?>