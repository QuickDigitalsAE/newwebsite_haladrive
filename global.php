<?php 

// Include the API handler
require_once 'apis/ApiHandler.php';

$api = new ApiHandler();

// ------------------- Language Detection -------------------
$lang = 'en';
$uri = $_SERVER['REQUEST_URI'];
// $liveBaseUrl = "https://haladrive.ae";
$uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/';
$liveBaseUrl = "http://localhost/newwebsite_haladrive";
$basePath = '/newwebsite_haladrive';

$cleanUri = preg_replace('#^' . preg_quote($basePath, '#') . '#', '', $uri);
$cleanUri = '/' . ltrim($cleanUri, '/');

// Detect Arabic
if (preg_match('#^/ar(/|$)#', $cleanUri)) {
    $lang = 'ar';
}


// Detect Arabic language
if (preg_match('#/(ar)(/|$)#', $uri)) {
    $lang = 'ar';
}

// Direction & Base URL
$dir = ($lang === 'ar') ? 'rtl' : 'ltr';
$baseHref = ($lang === 'ar') ? $liveBaseUrl . '/ar/' : $liveBaseUrl . '/';

// Language Switch URLs

// $englishUrl = ($lang === 'ar') ? preg_replace('#/ar/#', '/', $uri) : $uri;

// English URL handling
if ($lang === 'ar') {
    if ($uri === '/ar' || $uri === '/ar/') {
        $englishUrl = '/';
    } else {
        $englishUrl = preg_replace('#^/ar#', '', $uri);
    }
} else {
    $englishUrl = $uri;
}

// $arabicUrl  = ($lang === 'ar') ? $uri : '/ar' . $uri;

// Arabic URL handling
if ($lang === 'ar') {
    $arabicUrl = $uri;
} else {
    // If home page
    if ($uri === '/' || $uri === '') {
        $arabicUrl = '/ar';
    } else {
        $arabicUrl = '/ar' . $uri;
    }
}

// CSS & Images
$cssPath       = $liveBaseUrl . '/style.css';
$outputCssPath = $liveBaseUrl . '/output.css';
$imagePath     = $liveBaseUrl . '/images/';

// ------------------- Load Messages -------------------
$messagesFile = ($lang === 'ar') ? __DIR__ . '/messages_ar.php' : __DIR__ . '/messages.php';
$messages = include $messagesFile;


?>