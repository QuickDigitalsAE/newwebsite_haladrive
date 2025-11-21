<?php 

// Include the API handler
require_once 'apis/ApiHandler.php';

$api = new ApiHandler();

// ------------------- Language Detection -------------------
$lang = 'en';
$uri = $_SERVER['REQUEST_URI'];
$liveBaseUrl = "https://new.haladrive.ae";

// Detect Arabic language
if (preg_match('#/(ar)(/|$)#', $uri)) {
    $lang = 'ar';
}

// Direction & Base URL
$dir = ($lang === 'ar') ? 'rtl' : 'ltr';
$baseHref = ($lang === 'ar') ? $liveBaseUrl . '/ar/' : $liveBaseUrl . '/';

// Language Switch URLs
$englishUrl = ($lang === 'ar') ? preg_replace('#/ar/#', '/', $uri) : $uri;
$arabicUrl  = ($lang === 'ar') ? $uri : '/ar' . $uri;

// CSS & Images
$cssPath       = $liveBaseUrl . '/style.css';
$outputCssPath = $liveBaseUrl . '/output.css';
$imagePath     = $liveBaseUrl . '/images/';

// ------------------- Load Messages -------------------
$messagesFile = ($lang === 'ar') ? __DIR__ . '/messages_ar.php' : __DIR__ . '/messages.php';
$messages = include $messagesFile;


?>