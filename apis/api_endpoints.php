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
    ]
];
?>