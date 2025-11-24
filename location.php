<?php
// Include global
require_once 'global.php';

// Default SEO fallback values
$meta_title = '';
$meta_desc  = '';

// Detect full URL
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
$host = $_SERVER['HTTP_HOST'];
$uri = $_SERVER['REQUEST_URI'];
$fullUrl = $protocol . $host . $uri;

// Extract slug
$slug = basename(parse_url($uri, PHP_URL_PATH));

include_once('header.php');

// If slug missing
if (!$slug) {
    ?>
    <div class="no-data">
        <h2>No Data Found</h2>
        <a href="" class="btn-back">Back to Home</a>
    </div>
    <?php
    include_once('footer.php');
    exit;
}

try {
    $locationContent = $api->loadData('location', 'single', [], $slug);

    if ($locationContent['success']) {
        $singleLocationContentData = $locationContent['data']["data"];

        $titleKey = "title_" . $lang;
        $descKey  = "description_" . $lang;

        $meta_title = $singleLocationContentData["meta_data"][$titleKey] ?? '';
        $meta_desc  = $singleLocationContentData["meta_data"][$descKey] ?? '';

        // If API returned empty data
        if (empty($locationContent['data']["data"])) {
            $banner_image = "$imagePath/about/top-banner.webp";
            $banner_title = "No Data Found";
            $banner_subtitle = ""; // optional

            include_once('banner.php');  // Now banner will show updated text
            ?>

            <div style="text-align:center; padding:30px;">
                <a href="" class="btn-back" style="
                    display:inline-block;
                    padding:12px 25px;
                    background:#000;
                    color:#fff;
                    border-radius:6px;
                    text-decoration:none;
                    font-weight:bold;">
                    Back to Home
                </a>
            </div>

            <?php
            include_once('footer.php');
            exit;
    }

        $locationData = $locationContent['data']["data"];

    } else {
        $banner_image = "$imagePath/about/top-banner.webp";
        $banner_title = "No Data Found";
        $banner_subtitle = ""; // optional
        

        include_once('banner.php');  // Now banner will show updated text
        ?>

        <div style="text-align:center; padding:30px;">
            <a href="" class="btn-back" style="
                display:inline-block;
                padding:12px 25px;
                background:#000;
                color:#fff;
                border-radius:6px;
                text-decoration:none;
                font-weight:bold;">
                Back to Home
            </a>
        </div>

        <?php
        include_once('footer.php');
        exit;
    }

} catch (Exception $e) {
    $banner_image = "$imagePath/about/top-banner.webp";
    $banner_title = "No Data Found";
    $banner_subtitle = ""; // optional

    include_once('banner.php');  // Now banner will show updated text
    ?>

    <div style="text-align:center; padding:30px;">
        <a href="" class="btn-back" style="
            display:inline-block;
            padding:12px 25px;
            background:#000;
            color:#fff;
            border-radius:6px;
            text-decoration:none;
            font-weight:bold;">
            Back to Home
        </a>
    </div>

    <?php
    include_once('footer.php');
    exit;
}

include_once('header.php');
// If data found → continue
$banner_image = "$imagePath/about/top-banner.webp";
$banner_title = $singleLocationContentData["location"]["location_{$lang}"];
$banner_subtitle = $message['aboutBannerPera'];
include_once('banner.php');
?>

    <!--------------------------------- cars ------------------------------->

    <section class="relative py-16 max-[1024px]:py-10">
        <div class="w-[80%] max-[1024px]:w-[90%] mx-auto">
            <div class="grid grid-cols-4 max-[1024px]:grid-cols-1 gap-10">
                <div class="h-fit col-span-1">
                    <div class="text-black text-center bg-[#f1f4f8] p-4 mb-6">
                        <div class="mb-4 text-[1.3rem]"><?= $messages['price'] ?></div>
                        <div class="flex flex-col gap-2 font-semibold text-[12px]">
                            <a href='cars' class="bg-white border border-[#b8101f] py-2"><?= $messages['default'] ?></a>
                            <a href="<?php echo $locationData["location"]["slug"]; ?>?sort=price_asc" class="bg-white border border-[#b8101f] py-2">
                                <?= $messages['lowtohigh'] ?>
                            </a>
                            <a href="<?php echo $locationData["location"]["slug"]; ?>?sort=price_desc" class="bg-white border border-[#b8101f] py-2">
                                <?= $messages['hightolow'] ?>
                            </a>
                        </div>
                    </div>
                    <div class="text-black text-center bg-[#f1f4f8] p-4 mb-6">
                        <div class="mb-4 text-[1.3rem]"><?= $messages['typesofcars'] ?></div>
                        <div class="grid grid-cols-2 gap-2 font-semibold text-[12px]">
                            <a href='<?php echo $locationData["location"]["slug"]; ?>?sort=Economy&id=1' class="bg-white border border-[#b8101f] py-2 px-4"><?= $messages['economy'] ?></a>
                            <a href='<?php echo $locationData["location"]["slug"]; ?>?sort=SUV&id=2' class="bg-white border border-[#b8101f] py-2 px-4"><?= $messages['suv'] ?></a>
                            <a href='<?php echo $locationData["location"]["slug"]; ?>?sort=Midsize&id=3' class="bg-white border border-[#b8101f] py-2 px-4"><?= $messages['midsize'] ?></a>
                            <a href='<?php echo $locationData["location"]["slug"]; ?>?sort=Featured&id=4' class="bg-white border border-[#b8101f] py-2 px-4"><?= $messages['featured'] ?></a>
                            <a href='<?php echo $locationData["location"]["slug"]; ?>?sort=Crossover&id=5' class="bg-white border border-[#b8101f] py-2 px-4"><?= $messages['crossover'] ?></a>
                        </div>
                    </div>
                    <div class="text-black text-center bg-[#f1f4f8] p-4 mb-6">
                        <div class="mb-4 text-[1.3rem]"><?= $messages['availability'] ?></div>
                        <div class="grid grid-cols-1 gap-2 font-semibold text-[12px]">
                            <div class="bg-white border border-[#b8101f] py-2 px-4"><?= $messages['instock'] ?></div>
                            <div class="bg-white border border-[#b8101f] py-2 px-4"><?= $messages['outofstock'] ?></div>
                            <a href='cars' class="bg-[#ff000d] text-white uppercase py-3 text-[1rem]"><?= $messages['reset'] ?></a>
                        </div>
                    </div>
                    <div class="text-black text-center bg-[#f1f4f8] p-4">
                        <div class="mb-4 text-[1.3rem]"><?= $messages['sortbybrand'] ?></div>
                        <div class="grid grid-cols-1 gap-2 text-[12px]">
                            <button id="dropdownUsersButton" data-dropdown-toggle="dropdownUsers"
                                data-dropdown-placement="bottom"
                                class="text-[#939393] bg-white focus:outline-none font-medium rounded-lg text-[1rem] px-5 py-2.5 text-center inline-flex items-center justify-center"
                                type="button"><?= $messages['sortbybrand'] ?> <svg class="w-2.5 h-2.5 ms-3" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m1 1 4 4 4-4" />
                                </svg>
                            </button>

                            <!-- Dropdown menu -->
                            <div id="dropdownUsers"
                                class="z-[99999] relative hidden bg-white rounded-lg shadow-sm dark:bg-gray-700">
                                <ul class="h-48 py-2 overflow-y-auto text-gray-700 dark:text-gray-200"
                                    aria-labelledby="dropdownUsersButton">
                                    <?php foreach($locationData["brands"] as $brands): ?>
                                    <li>
                                        <a href="carsbrands/<?php echo $brands["slug"]; ?>"
                                            class="flex items-center px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                                            <img class="w-6 h-6 me-2 rounded-full"
                                                src="<?php echo $brands["logo_url"]; ?>" alt="audi">
                                            <?php echo $brands["name_{$lang}"]; ?>
                                        </a>
                                    </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-span-3 max-[1024px]:col-span-1 flex flex-col gap-10">
                    <?php foreach($locationData["cars"] as $car): ?>
                    <div class="relative p-4 rounded-[10px] shadow-[4px_7px_15px_rgba(75,75,77,.25)]">
                        <div class="flex items-center justify-between mb-2">
                            <div
                                class="bg-[#daffda] text-[#29a71a] border border-[#29a71a] rounded-full text-[.8rem] px-2 py-1">
                                <?= $messages['instock'] ?></div>
                            <img width="0" hight='0' src="<?php echo $car["brand"]["logo_url"]; ?>" class="w-16" alt="">
                        </div>
                        <div class="flex items-center max-[1024px]:flex-col gap-4">
                            <a href='cars/<?php echo $car["slug"]; ?>' class="w-[50%] max-[1024px]:w-full">
                                <img src="<?php echo $car["image_url"]; ?>" alt="">
                                <div class="flex gap-2 justify-center items-center mt-3">
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
                                <div class="text-[2rem] font-bold leading-[1] max-[1024px]:text-center"><?php echo $car["name_{$lang}"]; ?></div>
                                <ul
                                    class="list-disc text-[#939393] text-[11px] mt-4 max-[1024px]:mx-auto max-[1024px]:w-fit">
                                    <li class="flex items-center gap-2 ">
                                        <img src="<?= $imagePath ?>cars/star.svg" class="w-3" alt="">
                                        <div class=""><?= $messages['engine'] ?> Size 1.5 L</div>
                                    </li>
                                    <li class="flex items-center gap-2 ">
                                        <img src="<?= $imagePath ?>cars/star.svg" class="w-3" alt="">
                                        <div class=""><?= $messages['bluetooth'] ?> Yes</div>
                                    </li>
                                    <li class="flex items-center gap-2 ">
                                        <img src="<?= $imagePath ?>cars/star.svg" class="w-3" alt="">
                                        <div class=""><?= $messages['control'] ?> Yes</div>
                                    </li>
                                    <li class="flex items-center gap-2 ">
                                        <img src="<?= $imagePath ?>cars/star.svg" class="w-3" alt="">
                                        <div class=""><?= $messages['luggage'] ?> Yes</div>
                                    </li>
                                </ul>
                                <div class="mt-4">
                                    <div
                                        class="text-white openModalBtn bg-[#ff000d] px-[5rem] py-1 cursor-pointer -skew-x-12 shadow-[10px_7px_20px_rgb(255,9,9,38%)] border-r border-b border-[#198754] text-center max-[1024px]:mx-auto w-fit">
                                        <?= $messages['inquiry'] ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <!-- <div class="text-black mt-6 string">
                    <?php echo $locationData["cars"]["data"]["description_{$lang}"]; ?>
                </div> -->
            </div>
        </div>
        </div>
    </section>

    <!--------------------------------- footer ------------------------------->

<?php include_once('footer.php');?>