<?php
// Include global
require_once 'global.php';

// Default SEO fallback values
$meta_title = '';
$meta_desc  = '';

try {
    // Load homepage content
    $homeResponse = $api->loadData('webcontent', 'home', []);

    if (!empty($homeResponse['success']) && !empty($homeResponse['data']['data'])) {

        $homeContentData = $homeResponse['data']['data'];

        // Safely fetch SEO fields
        $sectionContent = $homeContentData['sectioncontent'][0] ?? [];

        $meta_title = $sectionContent["seo_title_{$lang}"]  ?? '';
        $meta_desc  = $sectionContent["seo_brief_{$lang}"] ?? '';

    }
} catch (Exception $e) {
    // Log the error instead of printing (prevents broken layout)
    error_log('Home content load error: ' . $e->getMessage());
}

// Include header (SEO variables will be used there)
require_once 'header.php';
?>



    <!--------------------------------- banner ------------------------------->

    <section class="relative w-full max-[1024px]:h-[65vh] max-[1024px]: pb-[4rem]">
        <img fetchpriority=high class='w-full object-cover ar_img h-full' src="<?= $imagePath ?>banner/bg.webp" alt="banner">
        <img class='absolute right-0 max-[1024px]:left-0 max-[1024px]:w-[95%] max-[1024px]:mx-auto ar_banner_img bottom-[-2rem] max-[1024px]:bottom-[-1rem]' src="<?= $imagePath ?>banner/car.webp" alt="banner car">
        <div class="text-[#333333] w-[40%] max-[1024px]:w-[85%] absolute top-[35%] max-[1024px]:top-[30%] -translate-y-1/2 left-[10%] max-[1024px]:left-0 max-[1024px]:right-0 max-[1024px]:mx-auto ar_banner_text">
            <div class='text-[3rem] max-[1024px]:text-[2rem] syne leading-[1] mb-3'><?= $messages['bannernewh'] ?></div>
            <p class='leading-[1.4] w-[80%] max-[1024px]:w-full '><?= $messages['bannernewp'] ?></p>
            <a href='cars' class="bg-[#E02D3C] p-2 w-fit rounded-[5px] text-white mt-3 inline-block"><?= $messages['exploremore'] ?></a>
        </div>

        <!-- <div class="swiper mySwiper w-full h-full">
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
        </div> -->
    </section>

    <!--------------------------------- filter ------------------------------->

    <section class="relative py-8 max-[1024px]:hidden">
        <div class="w-[60%] mx-auto justify-between items-center shadow-[0px_0px_50px_#1D293914] p-4 rounded-[10px]">
            <form action="" class='w-full'>
                <div class="gap-6 items-end grid grid-cols-3">
                    <div class="w-full">
                        <label class='text-[#333333] block' for=""><?= $messages['date'] ?></label>
                        <input class="px-4 py-2 bg-[#F4F4F4] w-full text-[#535353] rounded-[2px]" type="date" name="" id="">
                    </div>
                    <div class="w-full">
                        <label class='text-[#333333] block' for=""><?= $messages['time'] ?></label>
                        <input class="px-4 py-2 bg-[#F4F4F4] w-full text-[#535353] rounded-[2px]" type="time" name="" id="">
                    </div>
                    <a href="cars" class=''>
                        <div class="cursor-pointer text-center bg-[#E02D3C] text-white rounded-[5px] px-4 py-2"><?= $messages['find'] ?></div>
                    </a>
                </div>
            </form>
        </div>
    </section>

    <!--------------------------------- cars ------------------------------->
    

    <section class="relative bg-white py-16 max-[1024px]:py-10">
        <div class="">
            <h1 class="text-[#333333] capitalize syne font-semibold text-[2.5rem] max-[1024px]:text-[1.5rem] leading-[1] text-center w-[80%] max-[1024px]:w-[90%] mx-auto"><?= $messages['homeFirstHeading'] ?></h1>
            <ul class="grid grid-cols-5 max-[1024px]:grid-cols-2 w-[80%] max-[1024px]:w-[90%] mx-auto mt-4 gap-4 items-center justify-center">
                <li class="">
                    <a class='px-4 py-2 bg-[#F4F4F4] text-center text-black cursor-pointer rounded-[5px] hover:text-white hover:bg-[#ff000d] duration-300 w-full inline-block' rel="nofollow" href="cars">
                        <?= $messages['economy'] ?>
                    </a>
                </li>
                <li class="">
                    <a class='px-4 py-2 bg-[#F4F4F4] text-center text-black cursor-pointer rounded-[5px] hover:text-white hover:bg-[#ff000d] duration-300 w-full inline-block' rel="nofollow" href="cars">
                        <?= $messages['suv'] ?>
                    </a>
                </li>
                <li class="">
                    <a class='px-4 py-2 bg-[#F4F4F4] text-center text-black cursor-pointer rounded-[5px] hover:text-white hover:bg-[#ff000d] duration-300 w-full inline-block' rel="nofollow" href="cars">
                        <?= $messages['midsize'] ?>
                    </a>
                </li>
                <li class="">
                    <a class='px-4 py-2 bg-[#F4F4F4] text-center text-black cursor-pointer rounded-[5px] hover:text-white hover:bg-[#ff000d] duration-300 w-full inline-block' rel="nofollow" href="cars">
                        <?= $messages['featured'] ?>
                    </a>
                </li>
                <li class="max-[1024px]:col-span-2">
                    <a class='px-4 py-2 bg-[#F4F4F4] text-center text-black cursor-pointer rounded-[5px] hover:text-white hover:bg-[#ff000d] duration-300 w-full inline-block' rel="nofollow" href="cars">
                        <?= $messages['crossover'] ?>
                    </a>
                </li>
            </ul>
            <div class="grid grid-cols-2 max-[1024px]:grid-cols-1 gap-8 mt-12 w-[80%] max-[1024px]:w-[90%] mx-auto">
                <?php foreach($homeContentData["cars"] as $car): ?>
                    <div class="relative p-4 rounded-[10px] shadow-[4px_7px_15px_rgba(75,75,77,.25)]">
                        <div class="flex items-center justify-between ">
                            <div class="text-[1.5rem] font-bold leading-[1] max-[1024px]:text-center"><?php echo $car["name_{$lang}"]; ?></div>
                            <a target="_blank" href="https://wa.me/971501837112?text=Hi" class="bg-[#29a71a] flex items-center justify-center w-9 h-9 rounded-full cursor-pointer"><img src="<?= $imagePath ?>icons/whatsapp.svg" class="w-5" alt="whatsapp"></a>
                        </div>
                        <div class="flex items-center gap-4 max-[1024px]:flex-col-reverse">
                            <div class="flex flex-col w-[40%] max-[1024px]:w-full gap-2 max-[1024px]:justify-center items-center mt-3">
                                <div class="text-black w-full bg-[#F7F7F7] text-center cursor-pointer hover:text-white hover:bg-[#ff000d] duration-300 py-1 px-2">
                                    <!-- <div class=""></div> -->
                                    <div class="text-[1rem]"><?php echo $car["price_daily"]; ?>/<?= $messages['daily'] ?></div>
                                </div>
                                <div class="text-black w-full bg-[#F7F7F7] text-center cursor-pointer hover:text-white hover:bg-[#ff000d] duration-300 py-1 px-2">
                                    <!-- <div class=""></div> -->
                                    <div class="text-[1rem]"><?php echo $car["price_weekly"]; ?>/<?= $messages['weekly'] ?></div>
                                </div>
                                <div class="text-black w-full bg-[#F7F7F7] text-center cursor-pointer hover:text-white hover:bg-[#ff000d] duration-300 py-1 px-2">
                                    <!-- <div class=""></div> -->
                                    <div class="text-[1rem]"><?php echo $car["price_monthly"]; ?>/<?= $messages['monthly'] ?></div>
                                </div>
                            </div>
                            <a href='cars/<?php echo $car["slug"]; ?>' class="w-[60%] h-[11.5rem] max-[1024px]:w-full">
                                <img class='w-full h-full object-contain' src="<?php echo $car["image_url"]; ?>" alt="<?php echo $car["name_{$lang}"]; ?>">
                            </a>
                        </div>      
                        <div class="mt-5 grid grid-cols-2 gap-3">

                            <!-- Book Now Button -->
                            <a href="javascript:void(0)"
                            class="openModalBtn flex items-center justify-center gap-2 bg-[#FF000D] text-white px-4 py-3 rounded-xl text-[15px] font-semibold shadow-md hover:shadow-xl hover:scale-[1.02] transition-all duration-300">

                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M3 11l9-8 9 8"></path>
                                    <path d="M5 10v10h14V10"></path>
                                </svg>

                                <span>
                                    Book Now
                                    <span class="block text-[11px] font-medium opacity-90">(5% OFF)</span>
                                </span>
                            </a>

                            <!-- Pay Later Button -->
                            <a href="javascript:void(0)"
                            class="openModalBtn flex items-center justify-center gap-2 bg-white border border-gray-300 text-black px-4 py-3 rounded-xl text-[15px] font-semibold shadow-md hover:border-black hover:shadow-xl hover:scale-[1.02] transition-all duration-300">

                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="2" y="5" width="20" height="14" rx="2"></rect>
                                    <path d="M2 10h20"></path>
                                </svg>

                                <span>
                                    Pay Later
                                    <span class="block text-[11px] font-medium text-gray-500">
                                        Reserve First
                                    </span>
                                </span>
                            </a>

                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>


    <!--------------------------------- 1st content ------------------------------->

    <section class='relative animate_sec1 py-16 max-[1024px]:py-10'>
        <!-- <img class='absolute z-[-1] w-full h-full object-cover inset-0' src="<?= $imagePath ?>home1.webp" alt="home1"> -->
        <div class="w-[80%] max-[1024px]:w-[90%] mx-auto">
            <h2 class="text-[2.5rem] max-[1024px]:text-[1.7rem] mb-6 capitalize syne text-center w-[80%] max-[1024px]:w-full mx-auto leading-[1] font-bold"><?= $messages['homeSectionOneHeading_1'] ?></h2>
            <div class="flex max-[1024px]:flex-col-reverse items-center gap-10 max-[1024px]:gap-2">
                <div class="w-[55%] max-[1024px]:w-full">
                    <p class="text-[#333333] mt-4"><?= $messages['homeSectionOnePera_1'] ?></p>
                </div>
                <div class="w-[45%] max-[1024px]:w-full">
                    <img class='w-full h-full object-cover' src="<?= $imagePath ?>home2.webp" alt="home2">
                </div>
            </div>
            <div class="flex max-[1024px]:flex-col bg-white mt-6 p-6 max-[1024px]:p-[.7rem] rounded-[10px] items-center gap-10 max-[1024px]:gap-2">
                <div class="w-[40%] max-[1024px]:w-full">
                    <img class='w-full h-full object-cover' src="<?= $imagePath ?>home3.webp" alt="home3">
                </div>
                <div class="w-[60%] max-[1024px]:w-full">
                    <p class="text-[#333333]"><?= $messages['homeSectionOnePera_2'] ?></p>
                    <p class="text-[#333333] mt-4"><?= $messages['homeSectionOnePera_3'] ?></p>
                </div>
            </div>
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
                                <img class='slider_image' src="<?php echo $brand["logo_url"]; ?>" class="mx-auto" alt="slider_image">
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>

    <!--------------------------------- 3rd content ------------------------------->

    <section class="relative py-16 max-[1024px]:py-10 ">
        <img class='absolute z-[-1] w-full h-full object-cover inset-0' src="<?= $imagePath ?>footerbg.webp" alt="footerbg">
        <div class="w-[80%] mx-auto max-[1024px]:w-[90%]">
            <div class="text-white text-[2.5rem] max-[1024px]:text-[1.7rem] max-[1024px]:leading-[1] text-center syne mb-6"><?= $messages['homeNewHeading'] ?></div>
            <div class="w-[75%] max-[1024px]:w-full mx-auto">
                <div class="grid text-white grid-cols-3 max-[1024px]:grid-cols-2 items-center gap-2 mb-4">
                    <div class="flex items-center gap-2 border border-white rounded-[5px] justify-center max-[1024px]:py-1 max-[1024px]:gap-1 max-[1024px]:px-2 max-[1024px]:justify-start">
                        <div class="w-8 h-8 flex items-center justify-center">
                            <img src="<?= $imagePath ?>icons/common.webp" class="w-6" alt="common">
                        </div>
                        <div class="max-[1024px]:leading-[1]">
                            <?= $messages['timely'] ?>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 border border-white rounded-[5px] justify-center max-[1024px]:py-1 max-[1024px]:gap-1 max-[1024px]:px-2 max-[1024px]:justify-start">
                        <div class="w-8 h-8 flex items-center justify-center">
                            <img src="<?= $imagePath ?>icons/common.webp" class="w-6" alt="common">
                        </div>
                        <div class="max-[1024px]:leading-[1]">
                            <?= $messages['unbeatable'] ?>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 border border-white rounded-[5px] justify-center max-[1024px]:py-1 max-[1024px]:gap-1 max-[1024px]:px-2 max-[1024px]:justify-start">
                        <div class="w-8 h-8 flex items-center justify-center">
                            <img src="<?= $imagePath ?>icons/common.webp" class="w-6" alt="common">
                        </div>
                        <div class="max-[1024px]:leading-[1]">
                            <?= $messages['professional'] ?>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 border border-white rounded-[5px] justify-center max-[1024px]:py-1 max-[1024px]:gap-1 max-[1024px]:px-2 max-[1024px]:justify-start">
                        <div class="w-8 h-8 flex items-center justify-center">
                            <img src="<?= $imagePath ?>icons/common.webp" class="w-6" alt="common">
                        </div>
                        <div class="max-[1024px]:leading-[1]">
                            <?= $messages['flexible'] ?>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 border border-white rounded-[5px] justify-center max-[1024px]:py-1 max-[1024px]:gap-1 max-[1024px]:px-2 max-[1024px]:justify-start">
                        <div class="w-8 h-8 flex items-center justify-center">
                            <img src="<?= $imagePath ?>icons/common.webp" class="w-6" alt="common">
                        </div>
                        <div class="max-[1024px]:leading-[1]">
                            <?= $messages['online'] ?>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 border border-white rounded-[5px] justify-center max-[1024px]:py-1 max-[1024px]:gap-1 max-[1024px]:px-2 max-[1024px]:justify-start">
                        <div class="w-8 h-8 flex items-center justify-center">
                            <img src="<?= $imagePath ?>icons/common.webp" class="w-6" alt="common">
                        </div>
                        <div class="max-[1024px]:leading-[1]">
                            <?= $messages['fully'] ?>
                        </div>
                    </div>
                </div>
            </div>
            <p class="text-white text-center"><?= $messages['homeSectionThreePera'] ?></p>
        </div>
    </section>

    <!--------------------------------- 2nd content ------------------------------->

    <section class="py-16 animate_sec relative max-[1024px]:py-10 ">
        <!-- <img class='absolute z-[-1] w-full h-full object-cover inset-0' src="<?= $imagePath ?>homebg.webp" alt="homebg"> -->
        <div class="">
            <div class="flex max-[1024px]:flex-col-reverse items-center gap-10 w-[80%] max-[1024px]:w-[90%] mx-auto">
                <div class="w-[60%] max-[1024px]:w-full">
                    <h2 class="text-[2rem] capitalize syne leading-[1] font-bold"><?= $messages['homeSectionTwoHeading_1'] ?></h2>
                    <p class="text-[#333333] mt-4"><?= $messages['homeSectionTwoPera_1'] ?></p>
                </div>
                <div class="w-[40%] max-[1024px]:w-full">
                    <img src="<?= $imagePath ?>home4.webp" class="h-full w-full object-cover" alt="home4">
                </div>
            </div>
            <div class="flex max-[1024px]:flex-col items-center gap-10 max-[1024px]:gap-2 w-[80%] max-[1024px]:w-[90%] mx-auto max-[1024px]:mt-6">
                <div class="w-[40%] max-[1024px]:w-full">
                    <img src="<?= $imagePath ?>home5.webp" class="h-full rounded-[10px] w-full object-cover" alt="home5">
                </div>
                <div class="w-[60%] max-[1024px]:w-full">
                    <p class="text-[#333333] mt-4"><?= $messages['homeSectionTwoPera_2'] ?></p>
                </div>
            </div>
            <div class="w-[80%] mt-10 max-[1024px]:w-[90%] mx-auto bg-white p-4 rounded-[10px]">
                <div class="text-center">
                    <h3 class="text-[2rem] max-[1024px]:text-[1.7rem] capitalize syne leading-[1] font-bold"><?= $messages['homeSectionTwoHeading_2'] ?></h3>
                    <p class="text-[#333333] mt-4"><?= $messages['homeSectionTwoPera_3'] ?></p>
                    <p class="text-[#333333] mt-4"><?= $messages['homeSectionTwoPera_4'] ?></p>
                    <p class="text-[#333333] mt-4"><?= $messages['homeSectionTwoPera_5'] ?></p>
                </div>
            </div>
        </div>
    </section>
    
    <!--------------------------------- section 4 ------------------------------->

    <section class="relative">
        <img class='absolute z-[-1] w-full h-full object-cover inset-0' src="<?= $imagePath ?>footerbg.webp" alt="footerbg">
        <div class="w-[80%] max-[1024px]:w-[90%] mx-auto py-16 max-[1024px]:py-10">
            <div class="text-white text-center w-[60%] max-[1024px]:w-full mx-auto flex flex-col mb-4 max-[1024px]:mb-10">
                <h3 class="text-[2rem] leading-[1] mb-3 syne max-[1024px]:text-[1.5rem] max-[1024px]:leading-[1]"><?= $messages['homeSectionForthHeading_1'] ?></h3>
                <div class=""><?= $messages['homeSectionForthPera_1'] ?></div>
            </div>
            <div class="">
                <form method="POST" class="contact-form grid grid-cols-4 gap-4 max-[1024px]:grid-cols-1">
                    <input name="name" id="name"
                        class="focus:outline-none rounded-[5px] placeholder:text-[#BABABA] px-4 py-2"
                        placeholder="<?= $messages['name'] ?>" type="text">
                
                    <input name="number" id="contact_number"
                        class="focus:outline-none rounded-[5px] placeholder:text-[#BABABA] px-4 py-2"
                        placeholder="<?= $messages['number'] ?>" type="text">
                
                    <input name="email" id="email"
                        class="focus:outline-none rounded-[5px] placeholder:text-[#BABABA] px-4 py-2"
                        placeholder="<?= $messages['email'] ?>" type="email">
                
                    <button type="submit"
                        class="submit-btn text-white bg-[#E02D3C] rounded-[5px] px-4 py-2">
                        <?= $messages['send'] ?>
                    </button>
                </form>

            </div>
            <div class="text-white text-center mt-12">
                <p class='text-center'><?= $messages['homeSectionForthPera_2'] ?></p>
                <p class="mt-4 text-center"><?= $messages['homeSectionForthPera_3'] ?></p>
            </div>
        </div>
    </section>

    <!--------------------------------- section 5 ------------------------------->

    <section class="py-16 relative max-[1024px]:py-10">
        <img src="<?= $imagePath ?>testimonials.webp" class='w-full absolute inset-0 h-full object-cover z-[-1]' alt="testimonials">
        <div class="w-[80%] max-[1024px]:w-[90%] mx-auto">
            <div class="items-center">
                <div class="text-[#333333] mb-4">
                    <h3 class="text-[3rem] leading-[1] text-center max-[1024px]:text-[2rem]">
                        <?= $messages['satisfied_1'] ?> <span class="text-[3rem] leading-[1] max-[1024px]:text-[2rem] syne "><?= $messages['satisfied_2'] ?></span>
                    </h3>
                    <div class="text-[1.5rem] text-center syne leading-[1] mt-4"><?= $messages['satisfied_3'] ?></div>
                </div>
            </div>
            <div class="grid grid-cols-3 max-[1024px]:grid-cols-1 max-[1024px]:gap-6 gap-10">
                <div class="text-[#333333] bg-white hover:bg-[#FF000D] hover:text-white group p-4 rounded-[10px] cursor-pointer transition-all duration-300">
                    <div class="text-[3rem] leading-[1] mb-[-.9rem]">"</div>
                    <p class="text-left text_ar"><?= $messages['homeSectionFifthPera_1'] ?></p>
                    <div class="font-bold text-[.9rem] mt-2 text-[#FF000D] group-hover:text-white"><?= $messages['homeSectionFifthHeading_1'] ?></div>
                </div>
                <div class="text-[#333333] bg-white hover:bg-[#FF000D] hover:text-white group p-4 rounded-[10px] cursor-pointer transition-all duration-300">
                    <div class="text-[3rem] leading-[1] mb-[-.9rem]">"</div>
                    <p class="text-left text_ar"><?= $messages['homeSectionFifthPera_2'] ?></p>
                    <div class="font-bold text-[.9rem] mt-2 text-[#FF000D] group-hover:text-white"><?= $messages['homeSectionFifthHeading_2'] ?></div>
                </div>
                <div class="text-[#333333] bg-white hover:bg-[#FF000D] hover:text-white group p-4 rounded-[10px] cursor-pointer transition-all duration-300">
                    <div class="text-[3rem] leading-[1] mb-[-.9rem]">"</div>
                    <p class="text-left text_ar"><?= $messages['homeSectionFifthPera_3'] ?></p>
                    <div class="font-bold text-[.9rem] mt-2 text-[#FF000D] group-hover:text-white"><?= $messages['homeSectionFifthHeading_3'] ?></div>
                </div>
            </div>
        </div>
    </section>

    <!--------------------------------- faq ------------------------------->

    <section class="w-[80%] gap-6 flex max-[1024px]:flex-col max-[1024px]:w-[90%] mx-auto py-16 max-[1024px]:py-10">
        <div class='w-[40%] max-[1024px]:w-full'>
            <h3 class="text-[2rem] max-[1024px]:text-[1.5rem] syne text-[#333333] mb-4 md:text-3xl font-bold leading-[1]"><?= $messages['homeFaqs_1'] ?></h3>
            <img src="<?= $imagePath ?>FAQs.webp" alt="FAQs" class='rounded-[10px] max-[1024px]:hidden'>
        </div>
        <div class="space-y-3 w-[60%] max-[1024px]:w-full">
            <?php foreach($homeContentData["faqs"] as $faqs): ?>
            <div class="shadow-[0px_0px_50px_#0000000D] rounded-md overflow-hidden">
                <button class="faq-toggle w-full text-left flex justify-between items-center px-5 py-4 font-medium text-[#333333]">
                    <span><?php echo $faqs["question_{$lang}"]; ?></span>
                    <span class="text-2xl font-bold text-[#333333]">+</span>
                </button>
                <div class="faq-content hidden px-5 pb-4 text-[#333333]">
                   <?php echo $faqs["answer_{$lang}"]; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!--------------------------------- blogs ------------------------------->

    <section class="py-16 max-[1024px]:py-10 relative">
        <img class='w-full h-full object-cover absolute inset-0 z-[-1]' src="<?= $imagePath ?>blogbg.webp" alt="blogbg">
        <div class="w-[80%] max-[1024px]:w-[90%] mx-auto">
            <div class="flex items-center justify-between max-[1024px]:flex-col max-[1024px]:gap-4 mb-6">
                <h3 class="text-[#333333] text-[2rem] syne font-bold"><?= $messages['ourblogs'] ?></h3>
                <div class="">
                    <a class='px-4 py-2 bg-[#E02D3C] rounded-[5px] text-white ' href="blogs"><?= $messages['viewall'] ?></a>
                </div>
            </div>
            <div class="grid grid-cols-2 max-[1024px]:grid-cols-1 gap-4">
                <?php foreach($homeContentData["blogs"] as $blog): 
                    $date = new DateTime($blog['blog_schedule']); // Convert string to DateTime object
                    $formattedDate = $date->format('d M Y');
                    ?>
                    <div class="flex max-[1024px]:flex-col items-center shadow-[0px_0px_50px_#0000000D] bg-white max-[1024px]:p-3 rounded-[10px]">
                        <div class="w-1/2 max-[1024px]:w-full">
                            <img src="<?php echo $blog["image_url"]; ?>" class="rounded-[10px]" alt="<?php echo $blog["img_alt_{$lang}"]; ?>">
                        </div>
                        <div class="w-1/2 max-[1024px]:w-full p-4 max-[1024px]:p-0 max-[1024px]:pt-3">
                            <div class="font-bold text-[#E02D3C] text-[.7rem] mb-2"><?php echo $formattedDate; ?></div>
                            <a href="blogs/<?php echo $blog["slug"]; ?>" class="">
                                <div class="text-[#333333] syne text-[.9rem] leading-[1]"><?php echo $blog["title_{$lang}"]; ?></div>
                                <div class="text-white text-[.7rem] w-fit rounded-[5px] bg-[#E02D3C] px-3 py-1 syne font-bold mt-2"><?= $messages['read_more'] ?></div>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!--------------------------------- section 6 ------------------------------->

    <section class="py-16 max-[1024px]:py-10">
        <div class="w-[80%] max-[1024px]:w-[90%] mx-auto">
            <div class="text-center mb-6">
                <h4 class="text-[2.5rem] max-[1024px]:text-[1.7rem] mb-4 capitalize syne text-center w-[80%] max-[1024px]:w-full mx-auto leading-[1] font-bold"><?= $messages['homeSectionSixthHeading_1'] ?></h4>
                <p class='text-[#333333] text-center'><?= $messages['homeSectionSixthPera_1'] ?></p>
            </div>
            <div class="flex max-[1024px]:flex-col-reverse items-center gap-10 max-[1024px]:gap-6">
                <div class="w-[55%] max-[1024px]:w-full">
                    <p class="text-[#333333]"><?= $messages['homeSectionSixthPera_2'] ?></p>
                    <p class="text-[#333333]"><?= $messages['homeSectionSixthPera_3'] ?></p>
                </div>
                <div class="w-[45%] max-[1024px]:w-full">
                    <img class='w-full h-full object-cover rounded-[10px]' src="<?= $imagePath ?>home6.webp" alt="home6">
                </div>
            </div>
            <div class="flex max-[1024px]:flex-col max-[1024px]:mt-0 mt-6 py-6 rounded-[10px] items-center gap-10 max-[1024px]:gap-6">
                <div class="w-[40%] max-[1024px]:w-full">
                    <img class='w-full h-full object-cover rounded-[10px]' src="<?= $imagePath ?>home7.webp" alt="home7">
                </div>
                <div class="w-[60%] max-[1024px]:w-full">
                    <p class="text-[#333333]"><?= $messages['homeSectionSixthPera_4'] ?></p>
                    <p class="text-[#333333] mt-4"><?= $messages['homeSectionSixthPera_5'] ?></p>
                </div>
            </div>
        </div>
    </section>

    <!--------------------------------- Features On ------------------------------->

    <!-- <section>
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
    </section> -->

<script>
document.querySelectorAll('.contact-form').forEach(form => {

    form.addEventListener('submit', function(e) {
        e.preventDefault();

        const button = form.querySelector('.submit-btn');
        button.classList.add('loading');
        button.disabled = true;
        button.textContent = 'Sending...';

        const formData = new FormData(form);

        const base_url = 'https://admin.haladrive.ae/api/v1'; // apna base URL set karein

        fetch(base_url + '/en/contact/inquire/store', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {

            if (data.status === true) {
                alert(data.message || 'Thanks for contacting us, we\'ll get back to you.');
                form.reset();
            } else {
                alert(data.message || 'Error submitting inquiry.');
            }

        })
        .catch(error => {
            console.error(error);
            alert('Server error occurred.');
        })
        .finally(() => {
            // Remove loading class & enable button
            button.classList.remove('loading');
            button.disabled = false;
            button.textContent = '<?= $messages['send'] ?>';
        });

    });

});
</script>

    

<?php include_once('footer.php');?>
