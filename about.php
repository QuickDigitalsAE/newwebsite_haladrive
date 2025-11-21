<?php 
// Start session for token storage
session_start();

// Include the API handler
require_once 'apis/ApiHandler.php';

// Initialize API handler
$api = new ApiHandler();
?>

<?php include_once('header.php');?>

<?php
try {
    // This will be processed before rendering the page
    $aboutContent = $api->loadData('webcontent', 'about', []);
    
    if ($aboutContent['success']) {
        // Use the data in your page
        $aboutContentData = $aboutContent['data']["data"];
        // print_r($aboutContentData);
    }
} catch (Exception $e) {
    echo "Error loading featured products: " . $e->getMessage();
}
?>


<?php
$banner_image = "$imagePath/about/top-banner.webp";
$banner_title = $messages['aboutBannerHeading'];
$banner_subtitle = $messages['aboutBannerPera'];
include_once('banner.php');
?>

    <!--------------------------------- 1st section ------------------------------->

    <section class="relative">
        <div class="w-[80%] max-[1024px]:w-[90%] mx-auto py-16 max-[1024px]:py-10">
            <div class="grid grid-cols-2 max-[1024px]:grid-cols-1 items-center gap-10">
                <div class="text-black">
                    <div class="mb-4 string"><?php echo $aboutContentData["about"]["our_mission_brief_{$lang}"]; ?></div>
                    <!-- <p class="text-[#939393] mb-4">The prosperity of Hala Drive continues to this day. Our car rental guiding ideals and modest beginnings revolve around personal honesty and integrity. We believe it's essential to collaborate in order to enhance our communities. Treating our customers like they're a part of the family—and rewarding hard work.</p> -->
                    <button class="p-2 rounded-[6px] border border-[#ff000d] uppercase"><?= $messages['journy'] ?></button>
                </div>
                <div class="">
                    <img src="<?php echo $aboutContentData["about"]["image_url"]; ?>" alt="">
                </div>
            </div>
        </div>
    </section>

    <!--------------------------------- 2nd section ------------------------------->

    <section class="relative bg-[#f1f4f8]">
        <div class="w-[80%] max-[1024px]:w-[90%] mx-auto py-16 max-[1024px]:py-10">
            <div class="grid grid-cols-2 max-[1024px]:grid-cols-1 gap-10 items-center">
                <div class="flex flex-col gap-4 text-[#939393]">
                    <p><?= $messages['aboutSectionOnePera_1'] ?></p>
                    <p><?= $messages['aboutSectionOnePera_2'] ?></p>
                    <p><?= $messages['aboutSectionOnePera_3'] ?></p>
                    <p><?= $messages['aboutSectionOnePera_4'] ?></p>
                </div>
                <div class="grid grid-cols-2 items-center gap-4">
                    <div class="">
                        <div class="text-black text-[3.5rem] max-[1024px]:text-[2.5rem]">10K+</div>
                        <p class="text-[#939393]"><?= $messages['aboutSectionOneHeading_1'] ?></p>
                    </div>
                    <div class="">
                        <div class="text-black text-[3.5rem] max-[1024px]:text-[2.5rem]">100K+</div>
                        <p class="text-[#939393]"><?= $messages['aboutSectionOneHeading_3'] ?></p>
                    </div>
                    <div class="">
                        <div class="text-black text-[3.5rem] max-[1024px]:text-[2.5rem]">5 <?= $messages['aboutSectionOneHeading_years'] ?></div>
                        <p class="text-[#939393]"><?= $messages['aboutSectionOneHeading_2'] ?></p>
                    </div>
                    <div class="">
                        <img src="<?= $imagePath ?>sections/arrow.webp" alt="">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!--------------------------------- brands logo ------------------------------->

    <section>
        <div class="slider-wrap w-[80%] max-[1024px]:w-[90%] mx-auto py-16 max-[1024px]:py-10">
            <div class="swiper mySwiper1">
                <div class="swiper-wrapper">
                    <?php foreach($aboutContentData["brands"] as $brand): ?>
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

    <!--------------------------------- Footer ------------------------------->

<?php include_once('footer.php');?>