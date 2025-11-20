<?php
// Enable error logging
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', dirname(__FILE__) . '/php_errors.log');
error_reporting(E_ALL);

try {
    $headerContent = $api->loadData('header', 'header', []);
    if ($headerContent['success']) {
        $headerContentData = $headerContent['data']["data"];
    }
} catch (Exception $e) {
    echo "Error loading featured products: " . $e->getMessage();
}

$lang = 'en';
$uri = $_SERVER['REQUEST_URI'];

if (preg_match('#^/ar/#', $uri)) {
    $lang = 'ar';
}

// Base URL for links, images, CSS
$liveBaseUrl = "https://new.haladrive.ae";
$baseHref = ($lang === 'ar') ? $liveBaseUrl . '/ar/' : $liveBaseUrl . '/';
$cssPath      = $liveBaseUrl . '/style.css';
$outputCssPath = $liveBaseUrl . '/output.css';
$imagePath    = $liveBaseUrl . '/images/';

// Dynamic PHP file include (server path)
$messagesFile = $_SERVER['DOCUMENT_ROOT'] . '/messages' . ($lang === 'ar' ? '_ar' : '') . '.php';

if (file_exists($messagesFile)) {
    require_once $messagesFile;
} else {
    die("Messages file not found: " . $messagesFile);
}
?>

<!DOCTYPE html>
<html lang="<?= $lang ?>" dir="<?= $dir ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta property="og:title" content="Dubai’s Top E-Commerce Website Design Agency | Best Way to Grow Businesses" />
    <meta property="og:description"
        content="Build, grow, and succeed your business with a trusted and leading e-commerce website design agency in Dubai. Boost the online presence of your business." />
    <meta name="robots" content="noindex, nofollow">
    <base href="<?= $baseHref; ?>">
    <link defer rel="stylesheet" href="<?= $cssPath; ?>">
    <link defer rel="stylesheet" href="<?= $outputCssPath; ?>">
    <title>Hala Drive</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
</head>

<body>

    <!--------------------------------- header ------------------------------->

   <header class="relative">
        <div class="bg-black py-3 px-6 flex items-center z-[9999] min-[1023px]:hidden relative justify-between">
            <img src="<?= $imagePath ?>logo/Hala-Drive-resize.webp" class="w-[80px] " alt="">
            <div class="flex items-center gap-4">
                <div class="-skew-x-12 w-fit bg-[#ff000d] cursor-pointer text-white px-3 py-1 text-[12px]">Send Inquiry
                </div>
                <img src="<?= $imagePath ?>icons/hamburger.svg" class="w-8" alt="" id="menuBtn" />
            </div>
        </div>
        <a href="" class="z-[9999]">
            <img src="<?= $imagePath ?>logo/Hala-Drive-resize.webp"
            class="absolute top-[40%] ar_logo -translate-y-1/2 left-10 w-[110px] max-[1023px]:hidden" alt="">
        </a>
        <div class="flex ar_header justify-between items-center container mx-auto py-2 max-[1024px]:hidden">
            <div class="w-[85%] ar_ml ml-auto flex items-center justify-between">
                <ul class="flex items-center gap-5 ">
                    <li class="flex items-center gap-2 max-[1000px]:ml-auto">
                        <div class="bg-[#ff000d] py-[.2rem] px-[.3rem] flex items-center justify-center -skew-x-12">
                            <img src="<?= $imagePath ?>icons/phone.svg" alt="" class="w-5">
                        </div>
                        <a class="text-[#ff000d]" href="tel:+971501837112" class="">+971501837112</a>
                    </li>
                    <li class="flex items-center gap-2">
                        <a class="text-[#ff000d]" href="tel:+97142711125" class=" text-[22px]">+97142711125</a>
                    </li>
                </ul>
                <div class="flex items-center gap-3">
                    <a href="<?= $englishUrl; ?>" class="flex items-center gap-2 -skew-x-12 cursor-pointer shadow-[0_4px_10px_rgba(0,0,0,0.3)] px-4 py-2 bg-white">
                        <img src="<?= $imagePath ?>flags/flag-en.webp" alt="" class="w-6">
                        <div>English</div>
                    </a>
                    <a href="<?= $arabicUrl; ?>" class="flex items-center gap-2 -skew-x-12 cursor-pointer shadow-[0_4px_10px_rgba(0,0,0,0.3)] px-4 py-2 bg-white">
                        <img src="<?= $imagePath ?>flags/flag-ar.webp" alt="" class="w-6">
                        <div>العربية</div>
                    </a>
                    <div class="-skew-x-12 bg-[#ff000d] cursor-pointer text-white px-4 py-2">Send Inquiry</div>
                    <div class="bg-[#29a71a] -skew-x-12 px-2 py-2 cursor-pointer">
                        <img src="<?= $imagePath ?>icons/whatsapp.svg" alt="" class="w-6">
                    </div>
                </div>
            </div>
        </div>
        <nav id="mobileMenu"
            class="bg-black border-b-4 max-[1024px]:pb-12 border-[#ff000d] max-[1024px]:w-full max-[1024px]:fixed max-[1024px]:z-[999] transition-all duration-500 max-[1024px]:top-[-100%]">
            <ul
                class="uppercase flex max-[1024px]:flex-col items-center gap-6 justify-center max-[1024px]:items-start max-[1024px]:gap-0 max-[1024px]:pb-4 max-[1024px]:pt-24 text-white relative max-[1024px]:mx-5">
                <li
                    class="relative before:content-[''] before:absolute before:-left-3 before:top-1/2 before:-translate-y-1/2 before:w-[2px] before:h-[25px] before:bg-gray-500 max-[1024px]:before:bg-black first:before:hidden">
                    <a href=""
                        class="transition-all duration-300 hover:bg-[#ff000d] block py-6 max-[1024px]:py-2 px-3">Home</a>
                </li>
                <li
                    class="relative before:content-[''] before:absolute before:-left-3 before:top-1/2 before:-translate-y-1/2 before:w-[2px] before:h-[25px] before:bg-gray-500 max-[1024px]:before:bg-black first:before:hidden">
                    <a href="about"
                        class="transition-all duration-300 hover:bg-[#ff000d] block py-6 max-[1024px]:py-2 px-3">About
                        Us</a>
                </li>
                <li class="relative group before:content-[''] before:absolute before:-left-3 before:top-1/2 before:-translate-y-1/2 before:w-[2px] before:h-[25px] before:bg-gray-500 max-[1024px]:before:bg-black first:before:hidden">
                    <div class="flex items-center gap-1 transition-all duration-300 hover:bg-[#ff000d] py-6 px-3 max-[1024px]:py-2 ">
                        <a href="#" class="">Brands</a>
                        <img class='z-[999] icon_dropdown' src="<?= $imagePath ?>icons/arrow-down.svg" alt="">
                    </div>
                    <ul class="absolute left-0 ar_header_drop mt-3 z-[999] bg-[#e9ecef] rounded-md grid grid-cols-2 dropdown opacity-0 invisible group-hover:opacity-100 group-hover:visible group-hover:translate-y-0 translate-y-2 transition-all duration-300">
                        <?php foreach($headerContentData["brands"] as $brands): ?>
                            <li>
                                <a href="brands/<?php echo $brands["slug"]; ?>" class="block px-4 py-2 text-[#212529] hover:bg-[#ff000d] hover:text-white duration-300"><?php echo $brands["name_{$lang}"]; ?></a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </li>
                <li
                    class="relative before:content-[''] before:absolute before:-left-3 before:top-1/2 before:-translate-y-1/2 before:w-[2px] before:h-[25px] before:bg-gray-500 max-[1024px]:before:bg-black first:before:hidden">
                    <a href="cars"
                        class="transition-all duration-300 hover:bg-[#ff000d] block py-6 max-[1024px]:py-2 px-3">Cars</a>
                </li>
                <li
                    class="relative before:content-[''] before:absolute before:-left-3 before:top-1/2 before:-translate-y-1/2 before:w-[2px] before:h-[25px] before:bg-gray-500 max-[1024px]:before:bg-black first:before:hidden">
                    <a href="faq"
                        class="transition-all duration-300 hover:bg-[#ff000d] block py-6 max-[1024px]:py-2 px-3">FAQ</a>
                </li>
                <li
                    class="relative before:content-[''] before:absolute before:-left-3 before:top-1/2 before:-translate-y-1/2 before:w-[2px] before:h-[25px] before:bg-gray-500 max-[1024px]:before:bg-black first:before:hidden">
                    <a href="blogs"
                        class="transition-all duration-300 hover:bg-[#ff000d] block py-6 max-[1024px]:py-2 px-3">Blogs</a>
                </li>
                <li
                    class="relative before:content-[''] before:absolute before:-left-3 before:top-1/2 before:-translate-y-1/2 before:w-[2px] before:h-[25px] before:bg-gray-500 max-[1024px]:before:bg-black first:before:hidden">
                    <a href="locations"
                        class="transition-all duration-300 hover:bg-[#ff000d] block py-6 max-[1024px]:py-2 px-3">Our
                        Locations</a>
                </li>
                <li class="relative group before:content-[''] before:absolute before:-left-3 before:top-1/2 before:-translate-y-1/2 before:w-[2px] before:h-[25px] before:bg-gray-500 max-[1024px]:before:bg-black first:before:hidden">
                    <div class="flex items-center gap-1 transition-all duration-300 hover:bg-[#ff000d] py-6 px-3 max-[1024px]:py-2">
                        <a href="#" class="">lease</a>
                        <img class='z-[999] icon_dropdown' src="<?= $imagePath ?>icons/arrow-down.svg" alt="">
                    </div>
                    <ul class="absolute left-0 ar_header_drop mt-3 z-[999] bg-[#e9ecef] rounded-md dropdown opacity-0 invisible group-hover:opacity-100 group-hover:visible group-hover:translate-y-0 translate-y-2 transition-all duration-300">
                        <?php foreach($headerContentData["lease"] as $lease): ?>
                            <li>
                                <a href="lease/<?php echo $lease["slug"]; ?>" class="block px-4 py-2 text-[#212529] hover:bg-[#ff000d] hover:text-white duration-300"><?php echo $lease["title_{$lang}"]; ?></a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </li>
                <li
                    class="relative before:content-[''] before:absolute before:-left-3 before:top-1/2 before:-translate-y-1/2 before:w-[2px] before:h-[25px] before:bg-gray-500 max-[1024px]:before:bg-black first:before:hidden">
                    <a href="contact"
                        class="transition-all duration-300 hover:bg-[#ff000d] block py-6 max-[1024px]:py-2 px-3">Contact</a>
                </li>
            </ul>
            <div
                class="flex items-center gap-2 min-[1024px]:hidden text-[12px] w-fit mx-8 mb-6 -skew-x-12 cursor-pointer shadow-[0_4px_10px_rgba(0,0,0,0.3)] px-3 py-1 bg-white">
                <img src="<?= $imagePath ?>flags/flag-en.webp" alt="" class="w-6">
                <div class="">English</div>
            </div>
            <div
                class="flex items-center gap-2 min-[1024px]:hidden text-[12px] w-fit mx-8 -skew-x-12 cursor-pointer shadow-[0_4px_10px_rgba(0,0,0,0.3)] px-3 py-1 bg-white">
                <img src="<?= $imagePath ?>flags/flag-ar.webp" alt="" class="w-6">
                <div class="">العربية</div>
            </div>
        </nav>
    </header>