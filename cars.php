<?php
session_start();
require_once 'apis/ApiHandler.php';
$api = new ApiHandler();

$slug = $_GET['slug'] ?? null;


include_once('header.php');

if ($slug) {

    try {
        $carSingleContent = $api->loadData('car', 'single', [], $slug);

        if ($carSingleContent['success']) {
            $carSingleContentData = $carSingleContent['data']["data"];
        }
    } catch (Exception $e) {
        echo "Error loading car details: " . $e->getMessage();
    }

    $banner_image = "images/about/top-banner.webp";
    $banner_title = 'Cars';
    $banner_subtitle = "Top rated car rental in Dubai. Low prices & great deals!";
    include_once('banner.php');
    ?>


    <section class="relative py-16 max-[1024px]:py-10">
        <div class="w-[80%] max-[1024px]:w-[90%] mx-auto">
            <div class="grid grid-cols-3 max-[1024px]:grid-cols-1 gap-10">
                <div class="col-span-2 max-[1024px]:col-span-1 flex flex-col gap-10">
                    <div class="relative p-4 rounded-[10px] ">
                        <div class="">
                            <div class="text-black text-[2rem]"><?php echo $carSingleContentData["car"]["name_en"]; ?></div>
                            <div class="w-[100%] max-[1024px]:w-full">
                                <img src="<?php echo $carSingleContentData["car"]["image_url"]; ?>" alt="">
                            </div>
                        </div>
                        <div class="">
                            <div class="text-black text-[1.5rem]">Car Features</div>
                            <div class="grid grid-cols-4 max-[1024px]:grid-cols-2 gap-2">
                                <div class="bg-[#e9ecef] px-4 py-1 text-[14px] text-[#939393] font-bold border border-[#ced4da]">
                                    <div class="">Engine Size</div>
                                    <div class=""><?php echo $carSingleContentData["car"]["engine"]; ?></div>
                                </div>
                                <div class="bg-[#e9ecef] px-4 py-1 text-[14px] text-[#939393] font-bold border border-[#ced4da]">
                                    <div class="">Bluetooth</div>
                                    <div class=""><?php echo $carSingleContentData["car"]["bluetooth"]; ?></div>
                                </div>
                                <div class="bg-[#e9ecef] px-4 py-1 text-[14px] text-[#939393] font-bold border border-[#ced4da]">
                                    <div class="">Cruise Control</div>
                                    <div class=""><?php echo $carSingleContentData["car"]["cruise"]; ?></div>
                                </div>
                                <div class="bg-[#e9ecef] px-4 py-1 text-[14px] text-[#939393] font-bold border border-[#ced4da]">
                                    <div class="">Luggage</div>
                                    <div class=""><?php echo $carSingleContentData["car"]["luggage"]; ?></div>
                                </div>
                            </div>
                        </div>
                        <div class="text-black mt-6 string">
                            <?php echo $carSingleContentData["car"]["description_en"]; ?>
                        </div>
                    </div>
                </div>
                <div class="col-span-1">
                    <div class="text-black text-center bg-[#f1f4f8] p-4 mb-6">
                        <div class="mb-4 text-[1.3rem]">Rent Cost</div>
                        <div class="flex gap-2 justify-center items-center mt-3">
                            <div
                                class="text-black bg-[#f2fdff] text-[10px] -skew-x-12 border-2 text-center border-[#d1eaee] cursor-pointer hover:text-white hover:bg-[#ff000d] duration-300 py-1 px-2">
                                <div class="">Daily</div>
                                <div class="font-bold"><?php echo $carSingleContentData["car"]["price_daily"]; ?></div>
                            </div>
                            <div
                                class="text-black bg-[#f2fdff] text-[10px] -skew-x-12 border-2 text-center border-[#d1eaee] cursor-pointer hover:text-white hover:bg-[#ff000d] duration-300 py-1 px-2">
                                <div class="">Weekly</div>
                                <div class="font-bold"><?php echo $carSingleContentData["car"]["price_weekly"]; ?></div>
                            </div>
                            <div
                                class="text-black bg-[#f2fdff] text-[10px] -skew-x-12 border-2 text-center border-[#d1eaee] cursor-pointer hover:text-white hover:bg-[#ff000d] duration-300 py-1 px-2">
                                <div class="">Monthly</div>
                                <div class="font-bold"><?php echo $carSingleContentData["car"]["price_weekly"]; ?></div>
                            </div>
                        </div>
                        <div class="mt-4">
                            <div
                                class="text-white bg-[#ff000d] px-[5rem] py-1 cursor-pointer -skew-x-12 shadow-[10px_7px_20px_rgb(255,9,9,38%)] border-r border-b border-[#198754] text-center mx-auto w-fit">
                                Send Inquiry</div>
                        </div>
                    </div>
                    <div class="text-black flex justify-between items-center bg-[#f1f4f8] px-4 py-2 mb-6">
                        <div class="text-[1rem] text-[#939393]">Availability</div>
                        <div class="">In Stock</div>
                    </div>
                    <div class="text-black flex justify-between items-center bg-[#f1f4f8] px-4 py-2 mb-6">
                        <div class="text-[1rem] text-[#939393]">Security Amount</div>
                        <div class="">1000 AED</div>
                    </div>
                    <div class="text-black flex justify-between items-center bg-[#f1f4f8] px-4 py-2 mb-6">
                        <div class="text-[1rem] text-[#939393]">Security Type</div>
                        <div class="">Card only</div>
                    </div>
                    <div class="text-black flex justify-between items-center bg-[#f1f4f8] px-4 py-2 mb-6">
                        <div class="text-[1rem] text-[#939393]">Payment Type</div>
                        <div class="">Credit Card, Cash</div>
                    </div>
                    <div class="text-black flex justify-between items-center bg-[#f1f4f8] px-4 py-2 mb-6">
                        <div class="text-[1rem] text-[#939393]">24x7 Customer Support</div>
                        <div class="">Yes</div>
                    </div>
                    <div class="text-black flex justify-between items-center bg-[#f1f4f8] px-4 py-2 mb-6">
                        <div class="text-[1rem] text-[#939393]">Free Delivery</div>
                        <div class="">Yes</div>
                    </div>
                    <div class="text-black flex justify-between items-center bg-[#f1f4f8] px-4 py-2 mb-6">
                        <div class="text-[1rem] text-[#939393]">Free Cancellation</div>
                        <div class="">Yes</div>
                    </div>
                    <div class="text-black flex justify-between items-center bg-[#f1f4f8] px-4 py-2 mb-6">
                        <div class="text-[1rem] text-[#939393]">Insurance</div>
                        <div class="">Yes</div>
                    </div>
                    <div class="text-black bg-[#f1f4f8] px-4 py-2 mb-6">
                        <div class="">Requirements for UAE Residents</div>
                        <ul class="mt-4">
                            <li class="flex items-center gap-2">
                                <img src="images/icons/tick-red.svg" class="w-8" alt="">
                                <div class="text-[#939393]">Copy of Passport</div>
                            </li>
                            <li class="flex items-center gap-2">
                                <img src="images/icons/tick-red.svg" class="w-8" alt="">
                                <div class="text-[#939393]">Copy of Residential Visa</div>
                            </li>
                            <li class="flex items-center gap-2">
                                <img src="images/icons/tick-red.svg" class="w-8" alt="">
                                <div class="text-[#939393]">UAE Driving License</div>
                            </li>
                            <li class="flex items-center gap-2">
                                <img src="images/icons/tick-red.svg" class="w-8" alt="">
                                <div class="text-[#939393]">Copy of Emirates ID</div>
                            </li>
                        </ul>
                    </div>
                    <div class="text-black bg-[#f1f4f8] px-4 py-2 mb-6">
                        <div class="">Requirements for Tourists</div>
                        <ul class="mt-4">
                            <li class="flex items-center gap-2">
                                <img src="images/icons/tick-red.svg" class="w-8" alt="">
                                <div class="text-[#939393]">Copy of Passport</div>
                            </li>
                            <li class="flex items-center gap-2">
                                <img src="images/icons/tick-red.svg" class="w-8" alt="">
                                <div class="text-[#939393]">Copy of Visit Visa</div>
                            </li>
                            <li class="flex items-center gap-2">
                                <img src="images/icons/tick-red.svg" class="w-8" alt="">
                                <div class="text-[#939393]">US, Canada, EU, GCC or international Driving License</div>
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


$sort = $_GET['sort'] ?? null;

try {
    $carContent = $api->loadData('car', 'main', ['sort' => $sort]);
    if ($carContent['success']) {
        $carContentData = $carContent['data']["data"];
    }
} catch (Exception $e) {
    echo "Error loading car list: " . $e->getMessage();
}

$banner_image = "images/about/top-banner.webp";
$banner_title = "Explore Our Signature Collection";
$banner_subtitle = "Top rated car rental in Dubai!";
include_once('banner.php');
?>

    <section class="relative py-16 max-[1024px]:py-10">
        <div class="w-[80%] max-[1024px]:w-[90%] mx-auto">
            <div class="grid grid-cols-4 max-[1024px]:grid-cols-1 gap-10">
                <div class="h-fit col-span-1">
                    <div class="text-black text-center bg-[#f1f4f8] p-4 mb-6">
                        <div class="mb-4 text-[1.3rem]">Sort By Price</div>
                        <div class="flex flex-col gap-2 font-semibold text-[12px]">
                            <a href='cars' class="bg-white border border-[#b8101f] py-2">Default</a>
                            <a href="cars?sort=price_asc" class="bg-white border border-[#b8101f] py-2">
                                Low to High
                            </a>
                            <a href="cars?sort=price_desc" class="bg-white border border-[#b8101f] py-2">
                                High to Low
                            </a>
                        </div>
                    </div>
                    <div class="text-black text-center bg-[#f1f4f8] p-4 mb-6">
                        <div class="mb-4 text-[1.3rem]">Types of cars</div>
                        <div class="grid grid-cols-2 gap-2 font-semibold text-[12px]">
                            <a href='cars?sort=Economy&id=1' class="bg-white border border-[#b8101f] py-2 px-4">Economy</a>
                            <a href='cars?sort=SUV&id=2' class="bg-white border border-[#b8101f] py-2 px-4">SUV</a>
                            <a href='cars?sort=Midsize&id=3' class="bg-white border border-[#b8101f] py-2 px-4">Midsize</a>
                            <a href='cars?sort=Featured&id=4' class="bg-white border border-[#b8101f] py-2 px-4">Featured</a>
                            <a href='cars?sort=Crossover&id=5' class="bg-white border border-[#b8101f] py-2 px-4">Crossover</a>
                        </div>
                    </div>
                    <div class="text-black text-center bg-[#f1f4f8] p-4 mb-6">
                        <div class="mb-4 text-[1.3rem]">Availability</div>
                        <div class="grid grid-cols-1 gap-2 font-semibold text-[12px]">
                            <div class="bg-white border border-[#b8101f] py-2 px-4">In Stock</div>
                            <div class="bg-white border border-[#b8101f] py-2 px-4">Out Of Stock</div>
                            <a href='cars' class="bg-[#ff000d] text-white uppercase py-3 text-[1rem]">reset</a>
                        </div>
                    </div>
                    <div class="text-black text-center bg-[#f1f4f8] p-4">
                        <div class="mb-4 text-[1.3rem]">Sort By Brand</div>
                        <div class="grid grid-cols-1 gap-2 text-[12px]">
                            <button id="dropdownUsersButton" data-dropdown-toggle="dropdownUsers"
                                data-dropdown-placement="bottom"
                                class="text-[#939393] bg-white focus:outline-none font-medium rounded-lg text-[1rem] px-5 py-2.5 text-center inline-flex items-center justify-center"
                                type="button">Sort By Brand <svg class="w-2.5 h-2.5 ms-3" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m1 1 4 4 4-4" />
                                </svg>
                            </button>

                            <!-- Dropdown menu -->
                            <div id="dropdownUsers" class="z-[99999] relative hidden bg-white rounded-lg shadow-sm dark:bg-gray-700">
                                <ul class="h-48 py-2 overflow-y-auto text-gray-700 dark:text-gray-200"
                                    aria-labelledby="dropdownUsersButton">
                                    <?php foreach($carContentData["brands"] as $brands): ?>
                                        <li>
                                            <a href="carsbrands/<?php echo $brands["slug"]; ?>"
                                            class="flex items-center px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                                            <img class="w-6 h-6 me-2 rounded-full"
                                                src="<?php echo $brands["logo_url"]; ?>" alt="audi">
                                            <?php echo $brands["name_en"]; ?>
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
                                in Stock</div>
                            <img src="<?php echo $car["brand"]["logo_url"]; ?>" class="w-16" alt="">
                        </div>
                        <div class="flex items-center max-[1024px]:flex-col gap-4">
                            <a href='cars/<?php echo $car["slug"]; ?>' class="w-[50%] max-[1024px]:w-full">
                                <img src="<?php echo $car["image_url"]; ?>" alt="">
                                <div class="flex gap-2 justify-center items-center mt-3">
                                    <div
                                        class="text-black bg-[#f2fdff] text-[10px] -skew-x-12 border-2 text-center border-[#d1eaee] cursor-pointer hover:text-white hover:bg-[#ff000d] duration-300 py-1 px-2">
                                        <div class="">Daily</div>
                                        <div class="font-bold"><?php echo $car["price_daily"]; ?></div>
                                    </div>
                                    <div
                                        class="text-black bg-[#f2fdff] text-[10px] -skew-x-12 border-2 text-center border-[#d1eaee] cursor-pointer hover:text-white hover:bg-[#ff000d] duration-300 py-1 px-2">
                                        <div class="">Weekly</div>
                                        <div class="font-bold"><?php echo $car["price_weekly"]; ?></div>
                                    </div>
                                    <div
                                        class="text-black bg-[#f2fdff] text-[10px] -skew-x-12 border-2 text-center border-[#d1eaee] cursor-pointer hover:text-white hover:bg-[#ff000d] duration-300 py-1 px-2">
                                        <div class="">Monthly</div>
                                        <div class="font-bold"><?php echo $car["price_monthly"]; ?></div>
                                    </div>
                                </div>
                            </a>
                            <div class="w-[50%] max-[1024px]:w-full">
                                <div class="text-[2rem] font-bold leading-[1] max-[1024px]:text-center"><?php echo $car["name_en"]; ?></div>
                                <ul
                                    class="list-disc text-[#939393] text-[11px] mt-4 max-[1024px]:mx-auto max-[1024px]:w-fit">
                                    <li class="flex items-center gap-2 ">
                                        <img src="images/cars/star.svg" class="w-3" alt="">
                                        <div class="">Engine Size 1.5 L</div>
                                    </li>
                                    <li class="flex items-center gap-2 ">
                                        <img src="images/cars/star.svg" class="w-3" alt="">
                                        <div class=""> Bluetooth Yes</div>
                                    </li>
                                    <li class="flex items-center gap-2 ">
                                        <img src="images/cars/star.svg" class="w-3" alt="">
                                        <div class="">Cruise Control Yes</div>
                                    </li>
                                    <li class="flex items-center gap-2 ">
                                        <img src="images/cars/star.svg" class="w-3" alt="">
                                        <div class="">Luggage Yes</div>
                                    </li>
                                </ul>
                                <div class="mt-4">
                                    <div
                                        class="text-white bg-[#ff000d] px-[5rem] py-1 cursor-pointer -skew-x-12 shadow-[10px_7px_20px_rgb(255,9,9,38%)] border-r border-b border-[#198754] text-center max-[1024px]:mx-auto w-fit">
                                        Send Inquiry</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        </div>
    </section>

 
<?php include_once('footer.php'); ?>