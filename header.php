<?php
// Enable error logging
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', dirname(__FILE__) . '/php_errors.log');
error_reporting(E_ALL);

$lang = 'en'; // default
$uri = $_SERVER['REQUEST_URI'];

// Local base path
$base = '/haladrive/src';
$cleanUri = $uri;
if (strpos($uri, $base) === 0) {
    $cleanUri = substr($uri, strlen($base));
}

// Determine language
if (preg_match('#^/ar/#', $cleanUri)) {
    $lang = 'ar';
}

// Body direction
$dir = ($lang === 'ar') ? 'rtl' : 'ltr';

// English URL: remove /ar/
$englishUrl = preg_replace('#/ar/#', '/', $cleanUri);
$englishUrl = $base . $englishUrl;

// Arabic URL: add /ar/ if not already
if ($lang === 'ar') {
    $arabicUrl = $uri; // already Arabic
} else {
    $arabicUrl = $base . '/ar' . $cleanUri;
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
    <base href="https://new.haladrive.ae/">
    <title>Hala Drive</title>
    <!-- <link rel="stylesheet" href="<?php echo $base_url; ?>output.css">
    <link rel="stylesheet" href="<?php echo $base_url; ?>style.css"> -->
    <link rel="stylesheet" href="/output.css">
    <link rel="stylesheet" href="/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <!-- <link rel="preload" rel="shortcut icon" href="./images/logo.webp" type="image/x-icon">
    <link rel="preload" fetchpriority="high" as="image" href="images/banner/mobile-slider-1.webp" type="image/webp">
    <link rel="preload" fetchpriority="high" as="image" href="images/banner/mobile-slider-2.webp" type="image/webp">
    <link rel="preload" fetchpriority="high" as="image" href="images/banner/mobile-slider-4.webp" type="image/webp">
    <link rel="preload" fetchpriority="high" as="image" href="images/banner/slider.webp" type="image/webp">
    <link rel="preload" fetchpriority="high" as="image" href="images/banner/slider2.webp" type="image/webp">
    <link rel="preload" fetchpriority="high" as="image" href="images/banner/slider3.webp" type="image/webp"> -->
</head>

<body>

    <!--------------------------------- header ------------------------------->

   <header class="relative">
        <div class="bg-black py-3 px-6 flex items-center z-[9999] min-[1023px]:hidden relative justify-between">
            <img src="images/logo/Hala-Drive-resize.webp" class="w-[80px] " alt="">
            <div class="flex items-center gap-4">
                <div class="-skew-x-12 w-fit bg-[#ff000d] cursor-pointer text-white px-3 py-1 text-[12px]">Send Inquiry
                </div>
                <img src="images/icons/hamburger.svg" class="w-8" alt="" id="menuBtn" />
            </div>
        </div>
        <a href="" class="z-[9999]">
            <img src="images/logo/Hala-Drive-resize.webp"
            class="absolute top-[40%] ar_logo -translate-y-1/2 left-10 w-[110px] max-[1023px]:hidden" alt="">
        </a>
        <div class="flex ar_header justify-between items-center container mx-auto py-2 max-[1024px]:hidden">
            <div class="w-[85%] ar_ml ml-auto flex items-center justify-between">
                <ul class="flex items-center gap-5 ">
                    <li class="flex items-center gap-2 max-[1000px]:ml-auto">
                        <div class="bg-[#ff000d] py-[.2rem] px-[.3rem] flex items-center justify-center -skew-x-12">
                            <img src="./images/icons/phone.svg" alt="" class="w-5">
                        </div>
                        <a class="text-[#ff000d]" href="tel:+971501837112" class="">+971501837112</a>
                    </li>
                    <li class="flex items-center gap-2">
                        <a class="text-[#ff000d]" href="tel:+97142711125" class=" text-[22px]">+97142711125</a>
                    </li>
                </ul>
                <div class="flex items-center gap-3">
                    <a href="<?= $englishUrl; ?>" class="flex items-center gap-2 -skew-x-12 cursor-pointer shadow-[0_4px_10px_rgba(0,0,0,0.3)] px-4 py-2 bg-white">
                        <img src="images/flags/flag-en.webp" alt="" class="w-6">
                        <div>English</div>
                    </a>
                    <a href="<?= $arabicUrl; ?>" class="flex items-center gap-2 -skew-x-12 cursor-pointer shadow-[0_4px_10px_rgba(0,0,0,0.3)] px-4 py-2 bg-white">
                        <img src="images/flags/flag-ar.webp" alt="" class="w-6">
                        <div>العربية</div>
                    </a>
                    <div class="-skew-x-12 bg-[#ff000d] cursor-pointer text-white px-4 py-2">Send Inquiry</div>
                    <div class="bg-[#29a71a] -skew-x-12 px-2 py-2 cursor-pointer">
                        <img src="images/icons/whatsapp.svg" alt="" class="w-6">
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
                        <img class='z-[999] icon_dropdown' src="images/icons/arrow-down.svg" alt="">
                    </div>
                    <ul
                        class="absolute left-0 mt-3 z-[999] bg-[#e9ecef] rounded-md grid grid-cols-2 dropdown opacity-0 invisible group-hover:opacity-100 group-hover:visible group-hover:translate-y-0 translate-y-2 transition-all duration-300">
                        <li><a href="brands/rent-a-bmw"
                                class="block px-4 py-2 text-[#212529] hover:bg-[#ff000d] hover:text-white duration-300">BMW</a>
                        </li>
                        <li><a href="brands/rent-a-nissan"
                                class="block px-4 py-2 text-[#212529] hover:bg-[#ff000d] hover:text-white duration-300">Nissan</a>
                        </li>
                        <li><a href="brands/rent-a-mercedes"
                                class="block px-4 py-2 text-[#212529] hover:bg-[#ff000d] hover:text-white duration-300">Mercedes</a>
                        </li>
                        <li><a href="brands/rent-a-ford"
                                class="block px-4 py-2 text-[#212529] hover:bg-[#ff000d] hover:text-white duration-300">Ford</a></li>
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
                        <img class='z-[999] icon_dropdown' src="images/icons/arrow-down.svg" alt="">
                    </div>
                    <ul
                        class="absolute z-[999] left-0 mt-3 bg-[#e9ecef] rounded-md dropdown opacity-0 invisible group-hover:opacity-100 group-hover:visible group-hover:translate-y-0 translate-y-2 transition-all duration-300">
                        <li><a href="lease/monthly-rent-a-car"
                                class="block px-4 py-2 text-[#212529] hover:bg-[#ff000d] hover:text-white duration-300">Cheap Rent a car</a></li>
                        <li><a href="lease/monthly-rent-a-car"
                                class="block px-4 py-2 text-[#212529] hover:bg-[#ff000d] hover:text-white duration-300">Monthly Rent a car</a></li>
                        <li><a href="lease/economy-car-rental"
                                class="block px-4 py-2 text-[#212529] hover:bg-[#ff000d] hover:text-white duration-300">Economy Car Rental</a></li>
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
                <img src="images/flags/flag-en.webp" alt="" class="w-6">
                <div class="">English</div>
            </div>
            <div
                class="flex items-center gap-2 min-[1024px]:hidden text-[12px] w-fit mx-8 -skew-x-12 cursor-pointer shadow-[0_4px_10px_rgba(0,0,0,0.3)] px-3 py-1 bg-white">
                <img src="images/flags/flag-ar.webp" alt="" class="w-6">
                <div class="">العربية</div>
            </div>
        </nav>
    </header>