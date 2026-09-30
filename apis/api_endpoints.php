<?php
// api_endpoints.php

$host = strtolower($_SERVER['HTTP_HOST'] ?? 'localhost');
$host = explode(':', $host)[0];

if ($host === 'haladrive.ae') {
    $baseUrl = 'https://admin.haladrive.ae/api/v1';
} elseif ($host === 'dev.haladrive.ae') {
    $baseUrl = 'https://dev-admin.haladrive.ae/api/v1';
} else {
    $baseUrl = 'http://localhost/admin_haladrive/public/api/v1';
}

return [
    'base_url' => $baseUrl,
    'promo_base_url' => 'https://dev-admin.haladrive.ae/api',
    
    'webcontent' => [
        'home' => '/en/home',
        'about' => '/en/about',
        'faq' => '/en/faq',
        'privacy-policy' => '/en/privacy-policy',
        'contact' => '/en/contact',
    ],
    'header' => [
        'header' => '/en/header',
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
    'blogs' => [
        'main' => '/en/blog',
        'single' => '/en/blog/{id}',
    ],
    'contact' => [
        'store' => '/en/contact/inquire/store', // The relative path
    ],
    'inquire' => [
        'store' => '/en/contact/send/inquire', // The relative path
    ],
    'promo_codes' => [
        'apply' => '/promo-codes/apply',
    ],
    'website' => [
        'bookings' => '/website/bookings',
    ]
];
?>
