<?php
// ------------------- API Header Content -------------------
try {
    $headerContent = $api->loadData('header', 'header', []);
    if ($headerContent['success']) {
        $headerContentData = $headerContent['data']["data"];
    }
} catch (Exception $e) {
    echo "Error loading header content: " . $e->getMessage();
}

?>

<!DOCTYPE html>
<html lang="<?= $lang ?>" dir="<?= $dir ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta property="title" content="<?php echo $meta_title ?? 'Default Title'; ?>" />
    <meta property="description" content="<?php echo $meta_desc ?? 'Default desc'; ?>" />
    <meta name="robots" content="noindex">
    <link href="<?= $imagePath; ?>icons/favicon.ico" rel="icon">
    <base href="<?= $baseHref; ?>">
    <link defer rel="stylesheet" href="<?= $cssPath; ?>">
    <link defer rel="stylesheet" href="<?= $outputCssPath; ?>">
    <title><?php echo $meta_title ?? 'Default Title'; ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
</head>

<body>

    <!--------------------------------- header ------------------------------->

   <header class="relative">
        <div class="bg-[black] py-3 px-6 flex items-center z-[9999] min-[1023px]:hidden relative justify-between">
            <img src="<?= $imagePath ?>logo/Hala-Drive-resize.webp" class="w-[80px] " alt="">
            <div class="flex items-center gap-4">
                <div class="bg-[#E02D3C] openModalBtn rounded-[5px] cursor-pointer text-white px-3 py-1"><?= $messages['inquiry'] ?>
                </div>
                <img src="<?= $imagePath ?>icons/hamburger.svg" class="w-8" alt="" id="menuBtn" />
            </div>
        </div>
        <a href="" class="">
            <img src="<?= $imagePath ?>logo/Hala-Drive-resize.webp"
            class="absolute top-[50%] ar_logo z-[9999] -translate-y-1/2 left-10 w-[110px] max-[1023px]:hidden" alt="">
        </a>
        <div class="flex bg-[#E02D3C] ar_header justify-between items-center py-2 max-[1024px]:hidden">
            <div class="container mx-auto">
                <div class="w-[85%] ar_ml ml-auto">
                    <ul class="flex justify-end items-center gap-5 ">
                        <li class="flex items-center gap-2 max-[1000px]:ml-auto">
                            <div class="">
                                <img src="<?= $imagePath ?>icons/phone.svg" alt="" class="w-5">
                            </div>
                            <a class="text-white" href="tel:+971501837112" class="">+971501837112</a>
                        </li>
                        <li class="flex items-center gap-2">
                            <a class="text-white" href="tel:+97142711125" class=" text-[22px]">+97142711125</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <nav id="mobileMenu" class="bg-black max-[1024px]:pb-12 max-[1024px]:w-full max-[1024px]:fixed max-[1024px]:z-[999] transition-all duration-500 max-[1024px]:top-[-100%]">
            <div class="container mx-auto flex items-center justify-end max-[1024px]:justify-start">
                <ul class="uppercase flex max-[1024px]:flex-col items-center ar_gap justify-center max-[1024px]:items-start max-[1024px]:gap-0 max-[1024px]:pb-4 max-[1024px]:pt-24 text-white relative max-[1024px]:mx-5">
                    <!-- <li class="relative ">
                        <a href=""
                            class="transition-all duration-300 hover:bg-[#ff000d] ar_px block py-4 max-[1024px]:py-2 px-3"><?= $messages['home'] ?></a>
                    </li> -->
                    <li class="relative ">
                        <a href="about"
                            class="transition-all duration-300 hover:bg-[#ff000d] ar_px block py-4 max-[1024px]:py-2 px-3"><?= $messages['about'] ?></a>
                    </li>
                    <li class="relative group ">
                        <div class="flex items-center gap-1 transition-all duration-300 hover:bg-[#ff000d] ar_px py-4 px-3 max-[1024px]:py-2 ">
                            <a href="#" class=""><?= $messages['brands'] ?></a>
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
                    <li class="relative ">
                        <a href="cars" class="transition-all duration-300 hover:bg-[#ff000d] ar_px block py-4 max-[1024px]:py-2 px-3"><?= $messages['cars'] ?></a>
                    </li>
                    <li class="relative ">
                        <a href="faq" class="transition-all duration-300 hover:bg-[#ff000d] ar_px block py-4 max-[1024px]:py-2 px-3"><?= $messages['faq'] ?></a>
                    </li>
                    <li class="relative ">
                        <a href="blogs" class="transition-all duration-300 hover:bg-[#ff000d] ar_px block py-4 max-[1024px]:py-2 px-3"><?= $messages['blogs'] ?></a>
                    </li>
                    <li class="relative ">
                        <a href="locations" class="transition-all duration-300 hover:bg-[#ff000d] ar_px block py-4 max-[1024px]:py-2 px-3"><?= $messages['locations'] ?></a>
                    </li>
                    <li class="relative group ">
                        <div class="flex items-center gap-1 transition-all duration-300 hover:bg-[#ff000d] ar_px py-4 px-3 max-[1024px]:py-2">
                            <a href="#" class=""><?= $messages['lease'] ?></a>
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
                    <li class="relative ">
                        <a href="contact" class="transition-all duration-300 hover:bg-[#ff000d] ar_px block py-4 max-[1024px]:py-2 px-3"><?= $messages['contact'] ?></a>
                    </li>
                    <li class="flex gap-2 bg-[#E02D3C] ar_ml3 ml-3 max-[1024px]:flex-col rounded-[5px] group relative">
                        <div class="flex items-center gap-1 transition-all duration-300 cursor-pointer text-white px-3 py-1 max-[1024px]:py-2">
                            <?= ($lang === 'ar') ? 'العربية' : $lang ?>
                            <img class='z-[999] icon_dropdown' src="<?= $imagePath ?>icons/arrow-down.svg" alt="">
                        </div>
                        <ul class="absolute top-[3rem] left-0 w-[8.3rem] p-[.5rem] ar_header_drop z-[999] bg-[#e9ecef] rounded-md opacity-0 invisible group-hover:opacity-100 group-hover:visible group-hover:translate-y-0 translate-y-2 transition-all duration-300">
                            <a href="<?= $englishUrl; ?>" class="flex rounded-[5px] items-center gap-2 cursor-pointer Poppins shadow-[0_4px_10px_rgba(0,0,0,0.3)] px-3 py-1 bg-white">
                                <img src="<?= $imagePath ?>flags/flag-en.webp" alt="" class="w-6 h-6 rounded-full">
                                <div class='text-black'>English</div>
                            </a>
                            <a href="<?= $arabicUrl; ?>" class="flex rounded-[5px] items-center gap-2 mt-2 cursor-pointer Poppins shadow-[0_4px_10px_rgba(0,0,0,0.3)] px-3 py-1 bg-white">
                                <img src="<?= $imagePath ?>flags/flag-ar.webp" alt="" class="w-6 h-6 rounded-full">
                                <div class='text-black'>العربية</div>
                            </a>
                        </ul>
                    </li>
                    <li class="relative ">
                        <div class="bg-[#E02D3C] ar_ml3 ml-3 max-[1024px]:hidden openModalBtn rounded-[5px] cursor-pointer text-white px-3 py-1"><?= $messages['inquiry'] ?></div>
                    </li>
                </ul>
            </div>
        </nav>
    </header>