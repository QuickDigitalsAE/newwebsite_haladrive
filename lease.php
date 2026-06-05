<?php
// Include global
require_once 'global.php';

// Default SEO fallback values
$meta_title = '';
$meta_desc  = '';


// $slug = $_GET['slug'] ?? null;
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
$host = $_SERVER['HTTP_HOST'];
$uri = $_SERVER['REQUEST_URI'];

$fullUrl = $protocol . $host . $uri;

$slug = basename(parse_url($uri, PHP_URL_PATH));



if (!$slug) {
    echo "Brand not found.";
    include_once('footer.php');
    exit;
}


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
$stock = $_GET['stock'] ?? null;


$currentPage = $_GET['page'] ?? 1;

try {
    $brandContent = $api->loadData('lease', 'lease', ['sort' => $sort, 'id' => $id, 'stock' => $stock, 'page' => $currentPage ], $slug);
    if ($brandContent['success']) {
        $brandData = $brandContent['data']["data"];

        $titleKey = "seo_title_" . $lang;
        $descKey  = "seo_brief_" . $lang;

        $meta_title = $brandData["lease"][$titleKey] ?? '';
        $meta_desc  = $brandData["lease"][$descKey] ?? '';

        if (!empty($currentPage) && $currentPage > 1) {
            $meta_title .= " - Page " . $currentPage;
            $meta_desc  .= " (Page " . $currentPage . ")";
        }

        // print_r($brandData);
    } else {
        echo "Brand data not found.";
        include_once('footer.php');
        exit;
    }
    } catch (Exception $e) {
        echo "Error loading brand data: " . $e->getMessage();
        include_once('footer.php');
        exit;
    }

    require_once 'header.php';


    $banner_image = "$imagePath/about/top-banner.webp";
    $banner_title = $brandData["lease"]["title_{$lang}"];
    $banner_subtitle = $messages['locationsBannerPera'];
    if (!empty($currentPage) && $currentPage > 1) {
        $banner_title .= "- Page " . $currentPage;
    }
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
                            <a 
                                href="javascript:void(0);" 
                                onClick="
                                    document.cookie = 'sort=;';
                                    document.cookie = 'id=;';
                                    document.cookie = 'stock=;';
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
                                    document.cookie = 'stock=;';
                                    document.cookie = 'id=;';
                                    location.reload();
                                " 
                                rel="nofollow" 
                                class="<?= ($_GET['sort'] ?? '') == 'price_asc' ? 'bg-[#ff000d] text-white' : 'bg-white' ?> border border-[#E02D3C] py-2 px-4"
                            >
                                <?= $messages['lowtohigh'] ?>
                            </a>
                            <a 
                                href="javascript:void(0);" 
                                onClick="
                                    document.cookie = 'sort=price_desc';
                                    document.cookie = 'stock=;';
                                    document.cookie = 'id=;';
                                    location.reload();
                                " 
                                rel="nofollow" 
                                class="<?= ($_GET['sort'] ?? '') == 'price_desc' ? 'bg-[#ff000d] text-white' : 'bg-white' ?> border border-[#E02D3C] py-2 px-4"
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
                                    document.cookie = 'stock=;';
                                    location.reload();
                                " 
                                rel="nofollow" 
                                class="<?= ($_GET['sort'] ?? '') == 'Economy' ? 'bg-[#ff000d] text-white' : 'bg-white' ?> border border-[#E02D3C] py-2 px-4"
                            >
                                <?= $messages['economy'] ?>
                            </a>
                            <a 
                                href="javascript:void(0);" 
                                onClick="
                                    document.cookie = 'sort=suv';
                                    document.cookie = 'id=2';
                                    document.cookie = 'stock=;';
                                    location.reload();
                                " 
                                rel="nofollow" 
                                class="<?= ($_GET['sort'] ?? '') == 'suv' ? 'bg-[#ff000d] text-white' : 'bg-white' ?> border border-[#E02D3C] py-2 px-4"
                            >
                                <?= $messages['suv'] ?>
                            </a>
                            <a 
                                href="javascript:void(0);" 
                                onClick="
                                    document.cookie = 'sort=Midsize';
                                    document.cookie = 'id=3';
                                    document.cookie = 'stock=;';
                                    location.reload();
                                " 
                                rel="nofollow" 
                                class="<?= ($_GET['sort'] ?? '') == 'Midsize' ? 'bg-[#ff000d] text-white' : 'bg-white' ?> border border-[#E02D3C] py-2 px-4"
                            >
                                <?= $messages['midsize'] ?>
                            </a>
                            <a 
                                href="javascript:void(0);" 
                                onClick="
                                    document.cookie = 'sort=Featured';
                                    document.cookie = 'id=4';
                                    document.cookie = 'stock=;';
                                    location.reload();
                                " 
                                rel="nofollow" 
                                class="<?= ($_GET['sort'] ?? '') == 'Featured' ? 'bg-[#ff000d] text-white' : 'bg-white' ?> border border-[#E02D3C] py-2 px-4"
                            >
                                <?= $messages['featured'] ?>
                            </a>
                            <a 
                                href="javascript:void(0);" 
                                onClick="
                                    document.cookie = 'sort=Crossover';
                                    document.cookie = 'id=5';
                                    document.cookie = 'stock=;';
                                    location.reload();
                                " 
                                rel="nofollow" 
                                class="<?= ($_GET['sort'] ?? '') == 'Crossover' ? 'bg-[#ff000d] text-white' : 'bg-white' ?> border border-[#E02D3C] py-2 px-4 col-span-2"
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
                                class="<?= ($_GET['stock'] ?? '') == 'in_stock' ? 'bg-[#ff000d] text-white' : 'bg-white' ?> border border-[#E02D3C] py-2 px-4"
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
                                class="<?= ($_GET['stock'] ?? '') == 'out_of_stock' ? 'bg-[#ff000d] text-white' : 'bg-white' ?> border border-[#E02D3C] py-2 px-4"
                            >
                                <?= $messages['outofstock'] ?>
                            </a>
                            <a 
                                href="javascript:void(0);" 
                                onClick="
                                    document.cookie = 'sort=;';
                                    document.cookie = 'id=;';
                                    document.cookie = 'stock=;';
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
                                    <?php foreach($brandData["brands"] as $brands): ?>
                                        <li>
                                            <a href="carsbrands/<?php echo $brands["slug"]; ?>"
                                            class="flex items-center px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                                            <img class="w-6 h-6 me-2 rounded-full object-contain"
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
                    <?php if (empty($brandData["cars"]["data"])): ?>
                        <div class="text-[2rem] font-bold leading-[1] text-center syne">
                            <?= $messages['nocarsfound'] ?? 'No Cars Found' ?>
                        </div>
                    <?php else: ?>
                        <?php foreach($brandData["cars"]["data"] as $car): ?>
                        <div class="relative p-4 rounded-[10px] shadow-[4px_7px_15px_rgba(75,75,77,.25)]">
                            <div class="flex items-center justify-between mb-2">
                                <?php if (!empty($car["stock"]) && $car["stock"] == "Yes"): ?>
                                    <div class="bg-[#daffda] text-[#29a71a] border border-[#29a71a] rounded-full text-[.8rem] px-2 py-1">
                                        <?= $messages['instock'] ?>
                                    </div>
                                <?php else: ?>
                                    <div class="bg-[#d60000] text-white border border-[#d60000] rounded-full text-[.8rem] px-2 py-1">
                                        <?= $messages['outofstock'] ?>
                                    </div>
                                <?php endif; ?>
                                <img width="0" hight='0' src="<?php echo $car["brand"]["logo_url"]; ?>" class="w-16" alt="">
                            </div>
                            <div class="flex items-center max-[1024px]:flex-col gap-4">
                                <a href='cars/<?php echo $car["slug"]; ?>' class="w-[50%] max-[1024px]:w-full">
                                    <img src="<?php echo $car["image_url"]; ?>" alt="">
                                    <div class="flex gap-2 justify-center items-center mt-3">
                                        <div
                                            class="text-black bg-[#f2fdff] text-[10px] -skew-x-12 border-2 text-center border-[#d1eaee] cursor-pointer hover:text-white hover:bg-[#ff000d] duration-300 py-1 px-2">
                                            <div class=""><?= $messages['daily'] ?></div>
                                            <div class="font-bold car-card-price inline-flex items-center gap-1"><img src="<?= $imagePath ?>darham.png" class="h-[1em] w-auto object-contain" alt="AED"><?php echo $car["price_daily"]; ?></div>
                                        </div>
                                        <div
                                            class="text-black bg-[#f2fdff] text-[10px] -skew-x-12 border-2 text-center border-[#d1eaee] cursor-pointer hover:text-white hover:bg-[#ff000d] duration-300 py-1 px-2">
                                            <div class=""><?= $messages['weekly'] ?></div>
                                            <div class="font-bold car-card-price inline-flex items-center gap-1"><img src="<?= $imagePath ?>darham.png" class="h-[1em] w-auto object-contain" alt="AED"><?php echo $car["price_weekly"]; ?></div>
                                        </div>
                                        <div
                                            class="text-black bg-[#f2fdff] text-[10px] -skew-x-12 border-2 text-center border-[#d1eaee] cursor-pointer hover:text-white hover:bg-[#ff000d] duration-300 py-1 px-2">
                                            <div class=""><?= $messages['monthly'] ?></div>
                                            <div class="font-bold car-card-price inline-flex items-center gap-1"><img src="<?= $imagePath ?>darham.png" class="h-[1em] w-auto object-contain" alt="AED"><?php echo $car["price_monthly"]; ?></div>
                                        </div>
                                    </div>
                                </a>
                                <div class="w-[50%] max-[1024px]:w-full">
                                    <div class="text-[2rem] font-bold leading-[1] max-[1024px]:text-center syne"><?php echo $car["name_{$lang}"]; ?></div>
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
                                    <div class="mt-4 grid grid-cols-2 gap-3 max-w-[430px] max-[1024px]:mx-auto">

                                        <!-- Book Now -->
                                        <a href="cars/<?php echo $car['slug']; ?>?payment_flow=now"
                                            class="group flex items-center justify-center gap-2 book-now-btn bg-[#FF000D] text-white px-4 py-3 rounded-xl text-[15px] font-semibold shadow-md hover:bg-black hover:text-white hover:shadow-xl hover:scale-[1.02] transition-all duration-300">

                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M3 11l9-8 9 8"></path>
                                                <path d="M5 10v10h14V10"></path>
                                            </svg>

                                            <span class="leading-tight">
                                                Book Now
                                                <span class="block text-[11px] font-medium opacity-90">
                                                    (5% OFF)
                                                </span>
                                            </span>
                                        </a>

                                        <!-- Pay Later -->
                                        <a href="cars/<?php echo $car['slug']; ?>?payment_flow=later"
                                            class="group flex items-center justify-center gap-2 book-now-btn bg-white border border-gray-300 text-black px-4 py-3 rounded-xl text-[15px] font-semibold shadow-md  hover:text-white hover:shadow-xl hover:scale-[1.02] transition-all duration-300">

                                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <rect x="2" y="5" width="20" height="14" rx="2"></rect>
                                                <path d="M2 10h20"></path>
                                            </svg>

                                            <span class="leading-tight">
                                                Pay Later
                                                <span class="block text-[11px] font-medium text-gray-500 group-hover:text-white transition-all duration-300">
                                                    Reserve First
                                                </span>
                                            </span>
                                        </a>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; 
                            $baseUrl = "";
                            
                            $baseUrl = './lease/'.$brandData['lease']['slug']; ; 
                            
                            if (!empty($brandData["cars"]["links"])): ?>
                            <div class="flex justify-center mt-10">
                                <div class="flex gap-2 max-[1024px]:flex-wrap items-center">
                                    
                                    <?php foreach ($brandData["cars"]["links"] as $link): ?>
                                        
                                        <?php if ($link["url"]): ?>

                                            <?php $pageUrl = !empty($baseUrl) ?  $link['page'] == '1' ? $baseUrl : $baseUrl. "?page=" . $link["page"] : "#";?>

                                            <?php if ($link["active"]): ?>

                                                <a 
                                                    href="<?= $pageUrl ?>" 
                                                    class="px-4 py-2 bg-[#ff000d] text-white rounded"
                                                >
                                                    <?= $link["label"] ?>
                                                </a>

                                            <?php else: ?>

                                                <a 
                                                    href="<?= $pageUrl ?>" 
                                                    class="px-4 py-2 bg-white text-[#333] border rounded hover:bg-[#ff000d] hover:text-white"
                                                >
                                                    <?= $link["label"] ?>
                                                </a>

                                            <?php endif; ?>

                                        <?php else: ?>
                                            <span class="px-4 py-2 text-gray-500 w-[122px]"><?= $link["label"] ?></span>
                                        <?php endif; ?>

                                    <?php endforeach; ?>

                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
                <div class="text-black mt-6 string col-span-4 max-[1024px]:col-span-1 tableStyle">
                    <?php echo $brandData["lease"]["lease_description_{$lang}"]; ?>
                </div>
            </div>
        </div>
        </div>
    </section>

    <!--------------------------------- footer ------------------------------->

<?php include_once('footer.php');?>
