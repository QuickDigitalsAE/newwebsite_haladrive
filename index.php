<?php 
session_start();
require_once 'apis/ApiHandler.php';
$api = new ApiHandler();

$lang = isset($lang) ? $lang : 'en';
?>

<?php
try {
    $homeContent = $api->loadData('webcontent', 'home', []);
    
    if ($homeContent['success']) {
        $homeContentData = $homeContent['data']["data"];
        $meta_title = $homeContentData["sectioncontent"][0]["seo_title_${lang}"];
        $meta_desc  = $homeContentData["sectioncontent"][0]["seo_brief_${lang}"];
    }
} catch (Exception $e) {
    echo "Error loading content: " . $e->getMessage();
}

include_once('header.php');
?>


    <!--------------------------------- banner ------------------------------->

    <section class="relative w-full ">
        <div class="swiper mySwiper w-full h-full">
            <div class="swiper-wrapper">
                <?php foreach($homeContentData["highlight"] as $banner): ?>
                <div class="swiper-slide">
                    <picture>
                        <source fetchpriority="high" srcset="images/banner/mobile-slider-1.webp" type="image/webp"
                            media="(max-width: 768px)">
                        <source fetchpriority="high" srcset="<?php echo $banner["image_{$lang}_url"]; ?>" type="image/webp" media="(min-width: 769px)">
                        <img fetchpriority="high" src="<?php echo $banner["image_{$lang}_url"]; ?>" alt="Banner" class="w-full h-auto object-cover">
                    </picture>
                </div>
                <?php endforeach; ?>
            </div>

            <div class="swiper-button-next !text-white !right-6 after:!text-3xl max-[1024px]:hidden"></div>
            <div class="swiper-button-prev !text-white !left-6 after:!text-3xl max-[1024px]:hidden"></div>
        </div>
    </section>

    <!--------------------------------- filter ------------------------------->

    <section class="relative bg-[#f1f4f8] py-8 max-[1024px]:hidden">
        <div class="container mx-auto flex justify-between items-center">
            <ul class="flex gap-4 items-center">
                <li class="px-4 py-2 -skew-x-12 bg-white text-black cursor-pointer border border-[#bbb] hover:text-white hover:bg-[#ff000d] duration-300 ">
                    <a href="cars?sort=Economy&id=1">
                        <?= $messages['economy'] ?>
                    </a>
                </li>
                <li class="px-4 py-2 -skew-x-12 bg-white text-black cursor-pointer border border-[#bbb] hover:text-white hover:bg-[#ff000d] duration-300 ">
                    <a href="cars?sort=SUV&id=2">
                        <?= $messages['suv'] ?>
                    </a>
                </li>
                <li class="px-4 py-2 -skew-x-12 bg-white text-black cursor-pointer border border-[#bbb] hover:text-white hover:bg-[#ff000d] duration-300 ">
                    <a href="cars?sort=Midsize&id=3">
                        <?= $messages['midsize'] ?>
                    </a>
                </li>
                <li class="px-4 py-2 -skew-x-12 bg-white text-black cursor-pointer border border-[#bbb] hover:text-white hover:bg-[#ff000d] duration-300 ">
                    <a href="cars?sort=Featured&id=4">
                        <?= $messages['featured'] ?>
                    </a>
                </li>
                <li class="px-4 py-2 -skew-x-12 bg-white text-black cursor-pointer border border-[#bbb] hover:text-white hover:bg-[#ff000d] duration-300 ">
                    <a href="cars?sort=Crossover&id=5">
                        <?= $messages['crossover'] ?>
                    </a>
                </li>
            </ul>
            <form action="">
                <div class="flex gap-6 items-center">
                    <input class="-skew-x-12 bg-white px-4 py-2 shadow-[1px_1px_2px_#999] text-[#939393]" type="date"
                        name="" id="">
                    <input class="-skew-x-12 bg-white px-4 py-2 shadow-[1px_1px_2px_#999] text-[#939393]" type="time"
                        name="" id="">
                        <a href="cars">
                            <button class="cursor-pointer bg-[#ff000d] text-white -skew-x-12 px-16 py-2">Search</button>
                        </a>
                </div>
            </form>
        </div>
    </section>

    <!--------------------------------- cars ------------------------------->
    

    <section class="relative bg-white py-16 max-[1024px]:py-10">
        <div class="">
            <div class="text-black font-semibold text-[2.5rem] max-[1024px]:text-[1.5rem] leading-[1] text-center w-[80%] max-[1024px]:w-[90%] mx-auto">
                <?= $messages['homeFirstHeading'] ?></div>
            <div class="grid grid-cols-2 max-[1024px]:grid-cols-1 gap-8 mt-12 w-[80%] max-[1024px]:w-[90%] mx-auto">
                <?php foreach($homeContentData["cars"] as $car): ?>
                <div class="relative p-4 rounded-[10px] shadow-[4px_7px_15px_rgba(75,75,77,.25)]">
                    <div class="flex items-center justify-between mb-2">
                        <?php if ($car['stock'] === "Yes"): ?>
                            <div class="bg-[#daffda] text-[#29a71a] border border-[#29a71a] rounded-full text-[.8rem] px-2 py-1">
                            <?= $messages['instock'] ?>
                        </div>
                    <?php else: ?>
                        <div class="bg-[#ffdddd] text-[#d11a1a] border border-[#d11a1a] rounded-full text-[.8rem] px-2 py-1">
                        Out Stock
                    </div>
                    <?php endif; ?>
                    <img width="0" hight='0' src="<?php echo $car["brand"]["logo_url"]; ?>" class="w-16" alt="">
                    </div>
                    <div class="flex max-[1024px]:flex-col gap-4">
                        <a href='cars/<?php echo $car["slug"]; ?>' class="w-[50%] max-[1024px]:w-full">
                            <img src="<?php echo $car["image_url"]; ?>" alt="<?php echo $car["name_{$lang}"]; ?>">
                            <div class="flex gap-2 max-[1024px]:justify-center items-center mt-3">
                                <div
                                    class="text-black bg-[#f2fdff] text-[10px] -skew-x-12 border-2 text-center border-[#d1eaee] cursor-pointer hover:text-white hover:bg-[#ff000d] duration-300 py-1 px-2">
                                    <div class=""><?= $messages['daily'] ?></div>
                                    <div class="font-bold"><?php echo $car["price_daily"]; ?></div>
                                </div>
                                <div
                                    class="text-black bg-[#f2fdff] text-[10px] -skew-x-12 border-2 text-center border-[#d1eaee] cursor-pointer hover:text-white hover:bg-[#ff000d] duration-300 py-1 px-2">
                                    <div class=""><?= $messages['weekly'] ?></div>
                                    <div class="font-bold"><?php echo $car["price_weekly"]; ?></div>
                                </div>
                                <div
                                    class="text-black bg-[#f2fdff] text-[10px] -skew-x-12 border-2 text-center border-[#d1eaee] cursor-pointer hover:text-white hover:bg-[#ff000d] duration-300 py-1 px-2">
                                    <div class=""><?= $messages['monthly'] ?></div>
                                    <div class="font-bold"><?php echo $car["price_monthly"]; ?></div>
                                </div>
                            </div>
                        </a>
                        <div class="w-[50%] max-[1024px]:w-full">
                            <div class="text-[1.5rem] font-bold leading-[1] max-[1024px]:text-center"><?php echo $car["name_{$lang}"]; ?>
                            </div>
                            <ul
                                class="list-disc text-[#939393] text-[11px] mt-4 max-[1024px]:mx-auto max-[1024px]:w-fit">
                                <li class="flex items-center gap-2 ">
                                    <img src="<?= $imagePath ?>cars/star.svg" class="w-3" alt="">
                                    <div class=""><?= $messages['engine'] ?> <?php echo $car["engine"]; ?></div>
                                </li>
                                <li class="flex items-center gap-2 ">
                                    <img src="<?= $imagePath ?>cars/star.svg" class="w-3" alt="">
                                    <div class=""><?= $messages['bluetooth'] ?> <?php echo $car["bluetooth"]; ?></div>
                                </li>
                                <li class="flex items-center gap-2 ">
                                    <img src="<?= $imagePath ?>cars/star.svg" class="w-3" alt="">
                                    <div class=""><?= $messages['control'] ?> <?php echo $car["cruise"]; ?></div>
                                </li>
                                <li class="flex items-center gap-2 ">
                                    <img src="<?= $imagePath ?>cars/star.svg" class="w-3" alt="">
                                    <div class=""><?= $messages['luggage'] ?> <?php echo $car["luggage"]; ?></div>
                                </li>
                            </ul>
                            <div class="flex items-center max-[1024px]:justify-center gap-4 mt-4">
                                <div
                                    class="text-white bg-[#ff000d] px-2 py-1 cursor-pointer -skew-x-12 shadow-[10px_7px_20px_rgb(255,9,9,38%)] border-r border-b border-[#198754] text-[12px]">
                                    <?= $messages['inquiry'] ?></div>
                                <a target="_blank" href="https://wa.me/971501837112?text=Hi" class="bg-[#29a71a] px-4 py-1 -skew-x-12 cursor-pointer"><img
                                        src="<?= $imagePath ?>icons/whatsapp.svg" class="w-5" alt=""></a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>


    <!--------------------------------- 1st content ------------------------------->

    <section>
        <div class="pb-16 max-[1024px]:py-10">
            <div
                class="grid grid-cols-2 max-[1024px]:grid-cols-1 items-center gap-10 w-[80%] max-[1024px]:w-[90%] mx-auto">
                <div class="">
                    <div class="text-[1.7rem] leading-[1] font-bold"><?= $messages['homeSectionOneHeading_1'] ?></div>
                    <p class="text-[#7c7c7c] mt-4"><?= $messages['homeSectionOnePera_1'] ?></p>
                </div>
                <div class="">
                    <p class="text-[#7c7c7c]"><?= $messages['homeSectionOnePera_2'] ?></p>
                    <p class="text-[#7c7c7c] mt-4 font-bold"><?= $messages['homeSectionOnePera_3'] ?></p>
                </div>
            </div>
        </div>
    </section>

    <!--------------------------------- 2nd content ------------------------------->

    <section class="py-16 max-[1024px]:py-10 bg-[#f2fdff]">
        <div class="relative">
            <img src="<?= $imagePath ?>sections/arrow.webp"
                class="absolute w-fit left-0 right-0 mx-auto top-[53%] max-[1024px]:top-[53%] -translate-y-1/2" alt="">
            <div
                class="grid grid-cols-2 max-[1024px]:grid-cols-1 items-center gap-10 w-[80%] max-[1024px]:w-[90%] mx-auto">
                <div class="">
                    <div class="text-[1.7rem] leading-[1] font-bold"><?= $messages['homeSectionTwoHeading_1'] ?>
                    </div>
                    <p class="text-[#7c7c7c] mt-4"><?= $messages['homeSectionTwoPera_1'] ?></p>
                    <p class="text-[#7c7c7c] mt-4"><?= $messages['homeSectionTwoPera_2'] ?></p>
                </div>
                <img src="<?= $imagePath ?>sections/about-a.webp" class="h-full w-full object-cover" alt="">
            </div>
            <div
                class="grid grid-cols-2 max-[1024px]:grid-cols-1 items-center gap-10 w-[80%] max-[1024px]:w-[90%] mx-auto pt-20">
                <img src="<?= $imagePath ?>sections/about-b.webp" class="h-full w-full object-cover" alt="">
                <div class="">
                    <div class="text-[1.7rem] leading-[1] font-bold"><?= $messages['homeSectionTwoHeading_2'] ?></div>
                    <p class="text-[#7c7c7c] mt-4"><?= $messages['homeSectionTwoPera_3'] ?></p>
                    <p class="text-[#7c7c7c] mt-4"><?= $messages['homeSectionTwoPera_4'] ?></p>
                    <p class="text-[#7c7c7c] mt-4"><?= $messages['homeSectionTwoPera_5'] ?></p>
                </div>
            </div>
        </div>
    </section>

    <!--------------------------------- faq ------------------------------->

    <section class="w-[80%] max-[1024px]:w-[90%] mx-auto py-16 max-[1024px]:py-10">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-10">
            <div>
                <h2 class="text-2xl md:text-3xl font-bold mb-2"><?= $messages['homeFaqs_1'] ?></h2>
                <p class="text-gray-600"><?= $messages['homeFaqs_pera_1'] ?></p>
            </div>
            <div
                class="relative border-2 border-[#92aacb] px-5 py-3 mt-4 md:mt-0 flex items-center gap-3 shadow-[1px_1px_2px_#999] -skew-x-12">
                <div>
                    <p class="font-semibold text-gray-800"><?= $messages['homeFaqs_2'] ?></p>
                    <p class="text-sm text-gray-500"><?= $messages['homeFaqs_pera_2'] ?></p>
                </div>
                <a target="_blank" href="https://wa.me/971501837112?text=Hi" class="bg-[#1bd741] p-2 -skew-x-4">
                    <img src="<?= $imagePath ?>icons/whatsapp.svg" alt="WhatsApp" class="w-8 h-8">
                </a>
            </div>
        </div>

        <!-- Accordion -->
        <div class="space-y-3">
            <?php foreach($homeContentData["faqs"] as $faqs): ?>
            <div class="border border-gray-200 bg-[#f7fdff] rounded-md overflow-hidden">
                <button
                    class="faq-toggle w-full text-left flex justify-between items-center px-5 py-4 font-medium text-gray-700">
                    <span><?php echo $faqs["question_{$lang}"]; ?></span>
                    <span class="text-2xl font-bold text-gray-400">+</span>
                </button>
                <div class="faq-content hidden px-5 pb-4 text-gray-600">
                   <?php echo $faqs["answer_{$lang}"]; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!--------------------------------- 3rd content ------------------------------->

    <section class="relative py-16 max-[1024px]:py-10 bg-[#f2fdff]">
        <div class="w-[80%] mx-auto max-[1024px]:w-[90%]">
            <div
                class="grid grid-cols-2 max-[1024px]:grid-cols-1 items-center gap-10 max-[1024px]:gap-4 max-[1024px]:mb-6">
                <img src="<?= $imagePath ?>sections/no1.webp" alt="">
                <div class="grid grid-cols-2 items-center gap-y-8 max-[1024px]:gap-x-4">
                    <div class="flex items-center gap-4">
                        <div class="bg-[#ff000d] w-8 h-8 flex items-center justify-center">
                            <img src="<?= $imagePath ?>sections/key2.svg" class="w-6" alt="">
                        </div>
                        <div class="font-bold max-[1024px]:leading-[1]">
                            <?= $messages['timely'] ?>
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="bg-[#ff000d] w-8 h-8 flex items-center justify-center">
                            <img src="<?= $imagePath ?>sections/calculator.svg" class="w-6" alt="">
                        </div>
                        <div class="font-bold max-[1024px]:leading-[1]">
                            <?= $messages['unbeatable'] ?>
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="bg-[#ff000d] w-8 h-8 flex items-center justify-center">
                            <img src="<?= $imagePath ?>sections/folder.svg" class="w-6" alt="">
                        </div>
                        <div class="font-bold max-[1024px]:leading-[1]">
                            <?= $messages['professional'] ?>
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="bg-[#ff000d] w-8 h-8 flex items-center justify-center">
                            <img src="<?= $imagePath ?>sections/calender.svg" class="w-6" alt="">
                        </div>
                        <div class="font-bold max-[1024px]:leading-[1]">
                            <?= $messages['flexible'] ?>
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="bg-[#ff000d] w-8 h-8 flex items-center justify-center">
                            <img src="<?= $imagePath ?>sections/tick.svg" class="w-6" alt="">
                        </div>
                        <div class="font-bold max-[1024px]:leading-[1]">
                            <?= $messages['online'] ?>
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="bg-[#ff000d] w-8 h-8 flex items-center justify-center">
                            <img src="<?= $imagePath ?>sections/lock.svg" class="w-6" alt="">
                        </div>
                        <div class="font-bold max-[1024px]:leading-[1]">
                            <?= $messages['fully'] ?>
                        </div>
                    </div>
                </div>
            </div>
            <p class="text-[#939393] text-center"><?= $messages['homeSectionThreePera'] ?></p>
        </div>
    </section>

    <!--------------------------------- blogs ------------------------------->

    <section class="py-16 max-[1024px]:py-10 relative">
        <div class="w-[80%] max-[1024px]:w-[90%] mx-auto">
            <div class="text-black text-[1.5rem] font-bold"><?= $messages['ourblogs'] ?></div>
            <div class="grid grid-cols-2 max-[1024px]:grid-cols-1 gap-4">
                <?php foreach($homeContentData["blogs"] as $blog): ?>
                <a href="blogs/<?php echo $blog["slug"]; ?>" class="relative">
                    <div class="bg-black/40 absolute inset-0 rounded-[10px]"></div>
                    <div class="bg-white text-black rounded-full py-1 px-3 absolute top-4 left-4 text-[10px]"><?php echo $blog["blog_schedule"]; ?>
                    </div>
                    <img src="<?php echo $blog["image_url"]; ?>" class="rounded-[10px]" alt="<?php echo $blog["img_alt_{$lang}"]; ?>">
                    <div class="text-white text-[1rem] absolute bottom-4 px-4"><?php echo $blog["title_{$lang}"]; ?>
                    </div>
                </a>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!--------------------------------- section 4 ------------------------------->

    <section class="bg-[#0b0b0b] relative">
        <div class="w-[80%] max-[1024px]:w-[90%] mx-auto py-16 max-[1024px]:py-10">
            <div class="text-white flex max-[1024px]:flex-col max-[1024px]:gap-6 gap-20 mb-4 max-[1024px]:mb-10">
                <div class="text-[1.7rem] max-[1024px]:text-[1.3rem] max-[1024px]:leading-[1] font-bold"><?= $messages['homeSectionForthHeading_1'] ?></div>
                <div class=""><?= $messages['homeSectionForthPera_1'] ?></div>
            </div>
            <div class="">
                <form action="" class="flex gap-4 max-[1024px]:flex-col">
                    <input class="focus:outline-none px-4 py-2" placeholder="<?= $messages['name'] ?>" type="text">
                    <input class="focus:outline-none px-4 py-2" placeholder="<?= $messages['number'] ?>" type="tel">
                    <input class="focus:outline-none px-4 py-2" placeholder="<?= $messages['email'] ?>" type="email">
                    <button class="uppercase text-white bg-[#ff000d] px-4 py-2"><?= $messages['send'] ?></button>
                </form>
            </div>
            <div class="text-white mt-12">
                <p><?= $messages['homeSectionForthPera_2'] ?></p>
                <p class="mt-4"><?= $messages['homeSectionForthPera_3'] ?></p>
            </div>
        </div>
    </section>

    <!--------------------------------- section 5 ------------------------------->

    <section class="py-16 max-[1024px]:py-10">
        <div class="w-[80%] max-[1024px]:w-[90%] mx-auto">
            <div class="grid grid-cols-2 max-[1024px]:grid-cols-1 max-[1024px]:gap-6 gap-10 items-center">
                <div class="text-black font-bold">
                    <div class="text-[3rem] max-[1024px]:text-[2rem]"><?= $messages['satisfied_1'] ?></div>
                    <div class="text-[3rem] max-[1024px]:text-[2rem] text-center max-[1024px]:text-left leading-[1]">
                        <?= $messages['satisfied_2'] ?></div>
                    <div class="text-[1.5rem] leading-[1] mt-4"><?= $messages['satisfied_3'] ?></div>
                </div>
                <div class="">
                    <img src="<?= $imagePath ?>sections/hero-img.webp" alt="">
                </div>
            </div>
            <div class="grid grid-cols-3 max-[1024px]:grid-cols-1 max-[1024px]:gap-6 gap-10">
                <div class="">
                    <div class="text-black font-bold text-[1.7rem] mb-2"><?= $messages['homeSectionFifthHeading_1'] ?></div>
                    <p class="text-[#939393]"><?= $messages['homeSectionFifthPera_1'] ?></p>
                </div>
                <div class="">
                    <div class="text-black font-bold text-[1.7rem] mb-2"><?= $messages['homeSectionFifthHeading_2'] ?></div>
                    <p class="text-[#939393]"><?= $messages['homeSectionFifthPera_2'] ?></p>
                </div>
                <div class="">
                    <div class="text-black font-bold text-[1.7rem] mb-2"><?= $messages['homeSectionFifthHeading_3'] ?></div>
                    <p class="text-[#939393]"><?= $messages['homeSectionFifthPera_3'] ?></p>
                </div>
            </div>
        </div>
    </section>

    <!--------------------------------- section 6 ------------------------------->

    <section class="bg-[#f1f4f8] py-16 max-[1024px]:py-10">
        <div class="w-[80%] max-[1024px]:w-[90%] mx-auto text-center text-[#939393]">
            <div class="text-black font-bold text-[1.7rem] leading-[1] mb-10"><?= $messages['homeSectionSixthHeading_1'] ?></div>
            <p><?= $messages['homeSectionSixthPera_1'] ?></p>
            <p class="mt-6"><?= $messages['homeSectionSixthPera_2'] ?></p>
            <p class="mt-6"><?= $messages['homeSectionSixthPera_3'] ?></p>
            <p class="mt-6"><?= $messages['homeSectionSixthPera_4'] ?></p>
            <p class="mt-6"><?= $messages['homeSectionSixthPera_5'] ?></p>
        </div>
    </section>

    <!--------------------------------- brands logo ------------------------------->

    <section>
        <div class="slider-wrap w-[80%] max-[1024px]:w-[90%] mx-auto py-16 max-[1024px]:py-10">
            <div class="swiper mySwiper1">
                <div class="swiper-wrapper">
                    <?php foreach($homeContentData["brands"] as $brand): ?>
                        <div class="swiper-slide">
                            <a href="brands/<?php echo $brand["slug"]; ?>">
                                <img class='slider_image' src="<?php echo $brand["logo_url"]; ?>" class="mx-auto" alt="Image 1">
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>

    <!--------------------------------- Features On ------------------------------->

    <section>
        <div class="w-[80%] max-[1024px]:w-[90%] mx-auto py-8 ">
            <div class="text-center text-black font-bold text-[1.7rem] leading-[1] mb-10"><?= $messages['features'] ?></div>
            <div class="flex max-[1024px]:flex-wrap  justify-center gap-6">
                <img class="object-contain w-[10rem] " src="<?= $imagePath ?>sections/features/gulfnews.webp" alt="">
                <img class="object-contain w-[10rem] " src="<?= $imagePath ?>sections/features/gulfstory.webp" alt="">
                <img class="object-contain w-[10rem] " src="<?= $imagePath ?>sections/features/hiuae.webp" alt="">
                <img class="object-contain w-[10rem] " src="<?= $imagePath ?>sections/features/khaleejtime.webp" alt="">
                <img class="object-contain w-[10rem] " src="<?= $imagePath ?>sections/features/localuae.webp" alt="">
            </div>
        </div>
    </section>

<?php include_once('footer.php');?>
