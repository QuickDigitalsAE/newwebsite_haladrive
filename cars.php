<?php
// Include global
require_once 'global.php';

// Default SEO fallback values
$meta_title = '';
$meta_desc  = '';

$slug = $_GET['slug'] ?? null;

if ($slug) {

    try {
        $carSingleContent = $api->loadData('car', 'single', [], $slug);

        if ($carSingleContent['success']) {
            $carSingleContentData = $carSingleContent['data']["data"];

            $titleKey = "title_" . $lang;
            $descKey  = "description_" . $lang;

            $meta_title = $carSingleContentData["meta_data"][$titleKey] ?? '';
            $meta_desc  = $carSingleContentData["meta_data"][$descKey] ?? '';
        }
    } catch (Exception $e) {
        echo "Error loading car details: " . $e->getMessage();
    }

    include_once('header.php');

    $banner_image = "$imagePath/about/top-banner.webp";
    $banner_title = $messages['cars'];
    $banner_subtitle = $messages['aboutBannerPera'];
    include_once('banner.php');
    ?>


    <section class="relative py-16 max-[1024px]:py-10">
        <div class="w-[80%] max-[1024px]:w-[90%] mx-auto">
            <div class="grid grid-cols-3 max-[1024px]:grid-cols-1 gap-10">
                <div class="col-span-2 max-[1024px]:col-span-1 flex flex-col gap-10">
                    <div class="relative p-4 rounded-[10px] ">
                        <div class="">
                            <div class="text-black text-[2rem] syne"><?php echo $carSingleContentData["car"]["name_{$lang}"]; ?></div>
                            <div class="w-[100%] max-[1024px]:w-full">
                                <img src="<?php echo $carSingleContentData["car"]["image_url"]; ?>" alt="Cars">
                            </div>
                        </div>
                        <div class="">
                            <div class="text-black text-[1.5rem]"><?= $messages['carfeatures'] ?></div>
                            <div class="grid grid-cols-4 max-[1024px]:grid-cols-2 gap-2">
                                <div class="bg-[#e9ecef] px-4 py-1 text-[14px] text-[#939393] font-bold border border-[#ced4da]">
                                    <div class=""><?= $messages['engine'] ?></div>
                                    <div class=""><?php echo $carSingleContentData["car"]["engine"]; ?></div>
                                </div>
                                <div class="bg-[#e9ecef] px-4 py-1 text-[14px] text-[#939393] font-bold border border-[#ced4da]">
                                    <div class=""><?= $messages['bluetooth'] ?></div>
                                    <div class=""><?php echo $carSingleContentData["car"]["bluetooth"]; ?></div>
                                </div>
                                <div class="bg-[#e9ecef] px-4 py-1 text-[14px] text-[#939393] font-bold border border-[#ced4da]">
                                    <div class=""><?= $messages['control'] ?></div>
                                    <div class=""><?php echo $carSingleContentData["car"]["cruise"]; ?></div>
                                </div>
                                <div class="bg-[#e9ecef] px-4 py-1 text-[14px] text-[#939393] font-bold border border-[#ced4da]">
                                    <div class=""><?= $messages['luggage'] ?></div>
                                    <div class=""><?php echo $carSingleContentData["car"]["luggage"]; ?></div>
                                </div>
                            </div>
                        </div>
                        <div class="text-black mt-6 string">
                            <?php echo $carSingleContentData["car"]["description_{$lang}"]; ?>
                        </div>
                    </div>
                </div>
                <div class="col-span-1">
                    <div class="text-black text-center bg-[#f1f4f8] p-4 mb-6">
                        <div class="mb-4 text-[1.3rem]"><?= $messages['rentcost'] ?></div>
                        <div class="flex gap-2 justify-center items-center mt-3">
                            <div
                                class="text-black bg-[#f2fdff] text-[10px] -skew-x-12 border-2 text-center border-[#d1eaee] cursor-pointer hover:text-white hover:bg-[#ff000d] duration-300 py-1 px-2">
                                <div class=""><?= $messages['daily'] ?></div>
                                <div class="font-bold"><?php echo $carSingleContentData["car"]["price_daily"]; ?></div>
                            </div>
                            <div
                                class="text-black bg-[#f2fdff] text-[10px] -skew-x-12 border-2 text-center border-[#d1eaee] cursor-pointer hover:text-white hover:bg-[#ff000d] duration-300 py-1 px-2">
                                <div class=""><?= $messages['weekly'] ?></div>
                                <div class="font-bold"><?php echo $carSingleContentData["car"]["price_weekly"]; ?></div>
                            </div>
                            <div
                                class="text-black bg-[#f2fdff] text-[10px] -skew-x-12 border-2 text-center border-[#d1eaee] cursor-pointer hover:text-white hover:bg-[#ff000d] duration-300 py-1 px-2">
                                <div class=""><?= $messages['monthly'] ?></div>
                                <div class="font-bold"><?php echo $carSingleContentData["car"]["price_weekly"]; ?></div>
                            </div>
                        </div>
                        <div class="mt-4">
                            <div
                                class="text-white openModalBtn bg-[#ff000d] px-[5rem] py-1 cursor-pointer -skew-x-12 shadow-[10px_7px_20px_rgb(255,9,9,38%)] border-r border-b border-[#198754] text-center mx-auto w-fit">
                                <?= $messages['inquiry'] ?></div>
                        </div>
                    </div>
                    <div class="text-black flex justify-between items-center bg-[#f1f4f8] px-4 py-2 mb-6">
                        <div class="text-[1rem] text-[#939393]"><?= $messages['availability'] ?></div>
                        <div class=""><?= $messages['instock'] ?></div>
                    </div>
                    <div class="text-black flex justify-between items-center bg-[#f1f4f8] px-4 py-2 mb-6">
                        <div class="text-[1rem] text-[#939393]"><?= $messages['securityamount'] ?></div>
                        <div class="">1000 AED</div>
                    </div>
                    <div class="text-black flex justify-between items-center bg-[#f1f4f8] px-4 py-2 mb-6">
                        <div class="text-[1rem] text-[#939393]"><?= $messages['securitytype'] ?></div>
                        <div class="">Card only</div>
                    </div>
                    <div class="text-black flex justify-between items-center bg-[#f1f4f8] px-4 py-2 mb-6">
                        <div class="text-[1rem] text-[#939393]"><?= $messages['paymenttype'] ?></div>
                        <div class="">Credit Card, Cash</div>
                    </div>
                    <div class="text-black flex justify-between items-center bg-[#f1f4f8] px-4 py-2 mb-6">
                        <div class="text-[1rem] text-[#939393]"><?= $messages['support'] ?></div>
                        <div class="">Yes</div>
                    </div>
                    <div class="text-black flex justify-between items-center bg-[#f1f4f8] px-4 py-2 mb-6">
                        <div class="text-[1rem] text-[#939393]"><?= $messages['delivery'] ?></div>
                        <div class="">Yes</div>
                    </div>
                    <div class="text-black flex justify-between items-center bg-[#f1f4f8] px-4 py-2 mb-6">
                        <div class="text-[1rem] text-[#939393]"><?= $messages['cancellation'] ?></div>
                        <div class="">Yes</div>
                    </div>
                    <div class="text-black flex justify-between items-center bg-[#f1f4f8] px-4 py-2 mb-6">
                        <div class="text-[1rem] text-[#939393]"><?= $messages['insurance'] ?></div>
                        <div class="">Yes</div>
                    </div>
                    <div class="text-black bg-[#f1f4f8] px-4 py-2 mb-6">
                        <div class=""><?= $messages['residents'] ?></div>
                        <ul class="mt-4">
                            <li class="flex items-center gap-2">
                                <img src="<?= $imagePath ?>icons/tick-red.svg" class="w-8" alt="passport">
                                <div class="text-[#939393]"><?= $messages['passport'] ?></div>
                            </li>
                            <li class="flex items-center gap-2">
                                <img src="<?= $imagePath ?>icons/tick-red.svg" class="w-8" alt="residentialvisa">
                                <div class="text-[#939393]"><?= $messages['residentialvisa'] ?></div>
                            </li>
                            <li class="flex items-center gap-2">
                                <img src="<?= $imagePath ?>icons/tick-red.svg" class="w-8" alt="license1">
                                <div class="text-[#939393]"><?= $messages['license1'] ?></div>
                            </li>
                            <li class="flex items-center gap-2">
                                <img src="<?= $imagePath ?>icons/tick-red.svg" class="w-8" alt="emiratesid">
                                <div class="text-[#939393]"><?= $messages['emiratesid'] ?></div>
                            </li>
                        </ul>
                    </div>
                    <div class="text-black bg-[#f1f4f8] px-4 py-2 mb-6">
                        <div class=""><?= $messages['tourists'] ?></div>
                        <ul class="mt-4">
                            <li class="flex items-center gap-2">
                                <img src="<?= $imagePath ?>icons/tick-red.svg" class="w-8" alt="passport">
                                <div class="text-[#939393]"><?= $messages['passport'] ?></div>
                            </li>
                            <li class="flex items-center gap-2">
                                <img src="<?= $imagePath ?>icons/tick-red.svg" class="w-8" alt="visitvisa">
                                <div class="text-[#939393]"><?= $messages['visitvisa'] ?></div>
                            </li>
                            <li class="flex items-center gap-2">
                                <img src="<?= $imagePath ?>icons/tick-red.svg" class="w-8" alt="license2">
                                <div class="text-[#939393]"><?= $messages['license2'] ?></div>
                            </li>
                        </ul>
                    </div>
                </div>

            </div>
        </div>
        </div>
    </section>


    <!--------------------------------- footer ------------------------------->

 <?php
    include_once('footer.php');
    exit;
}

// -----------------------------------------
// PAGE WITHOUT SLUG → SHOW MAIN PAGE
// -----------------------------------------
if(isset($_COOKIE['sort'])){
    $_GET['sort'] = $_COOKIE['sort'];
}
if(isset($_COOKIE['stock'])){
    $_GET['stock'] = $_COOKIE['stock'];
}
if(isset($_COOKIE['id'])){
    $_GET['id'] = $_COOKIE['id'];
}

$sort = $_GET['sort'] ?? null;
$id = $_GET['id'] ?? null;

$currentPage = $_GET['page'] ?? 1;

try {
    $carContent = $api->loadData('car', 'main', ['sort' => $sort, 'id' => $id, 'stock' => $stock, 'page' => $currentPage]);
    if ($carContent['success']) {
        $carContentData = $carContent['data']["data"];

        $titleKey = "title_" . $lang;
        $descKey  = "description_" . $lang;

        $meta_title = $carContentData["meta_data"][$titleKey] ?? '';
        $meta_desc  = $carContentData["meta_data"][$descKey] ?? '';

        if (!empty($currentPage) && $currentPage > 1) {
            $meta_title .= " - Page " . $currentPage;
            $meta_desc  .= " (Page " . $currentPage . ")";
        }

    }
} catch (Exception $e) {
    echo "Error loading car list: " . $e->getMessage();
}

include_once('header.php');

$banner_image = "$imagePath/about/top-banner.webp";
$banner_title = $messages['carsBannerHeading'];
$banner_subtitle = $messages['carsBannerPera'];
$heading = 'h1';
if (!empty($currentPage) && $currentPage > 1) {
    $banner_title .= "- Page " . $currentPage;
}
include_once('banner.php');
?>

    <section class="relative py-16 max-[1024px]:py-10">
        <div class="w-[80%] max-[1024px]:w-[90%] mx-auto">
            <div class="grid grid-cols-4 max-[1024px]:grid-cols-1 gap-10">
                <div class="h-fit col-span-1">
                    <div class="text-black text-center bg-[#f1f4f8] p-4 mb-6">
                        <div class="mb-4 text-[1.3rem]"><?= $messages['price'] ?></div>
                        <div class="flex flex-col gap-2 font-semibold text-[12px]">
                            <a 
                                href="javascript:void(0);" 
                                onClick="
                                    document.cookie = 'sort=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/';
                                    document.cookie = 'id=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/';
                                    document.cookie = 'stock=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/';
                                    location.reload();
                                " 
                                rel="nofollow" 
                                class="bg-white border border-[#b8101f] py-2 px-4"
                            >
                                <?= $messages['default'] ?>
                            </a>
                            <a 
                                href="javascript:void(0);" 
                                onClick="
                                    document.cookie = 'sort=price_asc';
                                    document.cookie = 'stock=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/';
                                    location.reload();
                                " 
                                rel="nofollow" 
                                class="bg-white border border-[#b8101f] py-2 px-4"
                            >
                                <?= $messages['lowtohigh'] ?>
                            </a>
                            <a 
                                href="javascript:void(0);" 
                                onClick="
                                    document.cookie = 'sort=price_desc';
                                    document.cookie = 'stock=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/';
                                    location.reload();
                                " 
                                rel="nofollow" 
                                class="bg-white border border-[#b8101f] py-2 px-4"
                            >
                                <?= $messages['hightolow'] ?>
                            </a>
                        </div>
                    </div>
                    <div class="text-black text-center bg-[#f1f4f8] p-4 mb-6">
                        <div class="mb-4 text-[1.3rem]"><?= $messages['typesofcars'] ?></div>
                        <div class="grid grid-cols-2 gap-2 font-semibold text-[12px]">
                            <a 
                                href="javascript:void(0);" 
                                onClick="
                                    document.cookie = 'sort=Economy';
                                    document.cookie = 'id=1';
                                    document.cookie = 'stock=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/';
                                    location.reload();
                                " 
                                rel="nofollow" 
                                class="bg-white border border-[#b8101f] py-2 px-4"
                            >
                                <?= $messages['economy'] ?>
                            </a>
                            <a 
                                href="javascript:void(0);" 
                                onClick="
                                    document.cookie = 'sort=suv';
                                    document.cookie = 'id=2';
                                    document.cookie = 'stock=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/';
                                    location.reload();
                                " 
                                rel="nofollow" 
                                class="bg-white border border-[#b8101f] py-2 px-4"
                            >
                                <?= $messages['suv'] ?>
                            </a>
                            <a 
                                href="javascript:void(0);" 
                                onClick="
                                    document.cookie = 'sort=Midsize';
                                    document.cookie = 'id=3';
                                    document.cookie = 'stock=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/';
                                    location.reload();
                                " 
                                rel="nofollow" 
                                class="bg-white border border-[#b8101f] py-2 px-4"
                            >
                                <?= $messages['midsize'] ?>
                            </a>
                            <a 
                                href="javascript:void(0);" 
                                onClick="
                                    document.cookie = 'sort=Featured';
                                    document.cookie = 'id=4';
                                    document.cookie = 'stock=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/';
                                    location.reload();
                                " 
                                rel="nofollow" 
                                class="bg-white border border-[#b8101f] py-2 px-4"
                            >
                                <?= $messages['featured'] ?>
                            </a>
                            <a 
                                href="javascript:void(0);" 
                                onClick="
                                    document.cookie = 'sort=Crossover';
                                    document.cookie = 'id=5';
                                    document.cookie = 'stock=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/';
                                    location.reload();
                                " 
                                rel="nofollow" 
                                class="bg-white border border-[#b8101f] py-2 px-4"
                            >
                                <?= $messages['crossover'] ?>
                            </a>
                        </div>
                    </div>
                    <div class="text-black text-center bg-[#f1f4f8] p-4 mb-6">
                        <div class="mb-4 text-[1.3rem]"><?= $messages['availability'] ?></div>
                        <div class="grid grid-cols-1 gap-2 font-semibold text-[12px]">
                            <a 
                                href="javascript:void(0);" 
                                onClick="
                                    document.cookie = 'stock=in_stock';
                                    location.reload();
                                " 
                                rel="nofollow" 
                                class="bg-white border border-[#b8101f] py-2 px-4"
                            >
                                <?= $messages['instock'] ?>
                            </a>
                            <a 
                                href="javascript:void(0);" 
                                onClick="
                                    document.cookie = 'stock=out_of_stock';
                                    location.reload();
                                " 
                                rel="nofollow" 
                                class="bg-white border border-[#b8101f] py-2 px-4"
                            >
                                <?= $messages['outofstock'] ?>
                            </a>
                            <a 
                                href="javascript:void(0);" 
                                onClick="
                                    document.cookie = 'sort=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/';
                                    document.cookie = 'id=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/';
                                    document.cookie = 'stock=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/';
                                    location.reload();
                                " 
                                rel="nofollow" 
                                class="bg-[#ff000d] text-white uppercase py-3 text-[1rem]"
                            >
                                <?= $messages['reset'] ?>
                            </a>
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
                            <div id="dropdownUsers" class="z-[99999] relative hidden bg-white rounded-lg shadow-sm dark:bg-gray-700">
                                <ul class="h-48 py-2 overflow-y-auto text-gray-700 dark:text-gray-200 !bg-white"
                                    aria-labelledby="dropdownUsersButton">
                                    <?php foreach($carContentData["brands"] as $brands): ?>
                                        <li>
                                            <a href="carsbrands/<?php echo $brands["slug"]; ?>"
                                            class="flex items-center px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                                            <img class="w-6 h-6 me-2 rounded-full"
                                                src="<?php echo $brands["logo_url"]; ?>" alt="<?php echo $brands["name_{$lang}"]; ?>">
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
                    <?php foreach($carContentData["cars"]["data"] as $car): ?>
                    <div class="relative p-4 rounded-[10px] shadow-[4px_7px_15px_rgba(75,75,77,.25)]">
                        <div class="flex items-center justify-between mb-2">
                            <div
                                class="bg-[#daffda] text-[#29a71a] border border-[#29a71a] rounded-full text-[.8rem] px-2 py-1">
                                <?= $messages['instock'] ?></div>
                            <img width="0" hight='0' src="<?php echo $car["brand"]["logo_url"]; ?>" class="w-16" alt="brand logo">
                        </div>
                        <div class="flex items-center max-[1024px]:flex-col gap-4">
                            <a href='cars/<?php echo $car["slug"]; ?>' class="w-[50%] max-[1024px]:w-full">
                                <img src="<?php echo $car["image_url"]; ?>" alt="image_url">
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
                                <div class="text-[2rem] font-bold leading-[1] max-[1024px]:text-center syne"><?php echo $car["name_{$lang}"]; ?></div>
                                <ul class="list-disc text-[#939393] text-[11px] mt-4 max-[1024px]:mx-auto max-[1024px]:w-fit">
                                    <li class="flex items-center gap-2 ">
                                        <img src="<?= $imagePath ?>cars/star.svg" class="w-3" alt="star">
                                        <div class=""><?= $messages['engine'] ?> 1.5 L</div>
                                    </li>
                                    <li class="flex items-center gap-2 ">
                                        <img src="<?= $imagePath ?>cars/star.svg" class="w-3" alt="star">
                                        <div class=""><?= $messages['bluetooth'] ?> Yes</div>
                                    </li>
                                    <li class="flex items-center gap-2 ">
                                        <img src="<?= $imagePath ?>cars/star.svg" class="w-3" alt="star">
                                        <div class=""><?= $messages['control'] ?> Yes</div>
                                    </li>
                                    <li class="flex items-center gap-2 ">
                                        <img src="<?= $imagePath ?>cars/star.svg" class="w-3" alt="star">
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
                    <?php 
                    if (!empty($carContentData["cars"]["links"])): ?>
                        <div class="flex justify-center mt-10 mmm">
                            <div class="flex gap-2 max-[1024px]:flex-wrap items-center pagination">
                                <?php foreach ($carContentData["cars"]["links"] as $link): ?>
                                    <?php if ($link["url"]): ?>
                                        <?php if ($link["active"]): ?>
                                            <a href="cars<?= $link['page'] == '1' ? '' : '?page=' . $link['page'] ?>" 
                                                class="px-4 py-2 bg-[#ff000d] text-white rounded"
                                            >
                                                <?= $link["label"] ?>
                                            </a>

                                        <?php else: ?>
                                            <a 
                                                href="cars<?= $link['page'] == '1' ? '' : '?page=' . $link['page'] ?>" 
                                                class="px-4 py-2 bg-white text-[#333] border rounded hover:bg-[#ff000d] hover:text-white"
                                            >
                                                <?= $link["label"] ?>
                                            </a>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span class="px-4 py-2 text-gray-500"><?= $link["label"] ?></span>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        </div>
    </section>

 
<?php include_once('footer.php'); ?>