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
    $locationsContent = $api->loadData('location', 'main', []);
    
    if ($locationsContent['success']) {
        // Use the data in your page
        $locationsContentData = $locationsContent['data']["data"];
        // print_r($locationsContentData);
    }
} catch (Exception $e) {
    echo "Error loading featured products: " . $e->getMessage();
}
?>


<?php
$banner_image = "images/about/top-banner.webp";
$banner_title = "Our Locations";
$banner_subtitle = "Top rated car rental in Dubai. Low prices, great deals, convenient pick-up, top-notch service!";
include_once('banner.php');
?>

    <!--------------------------------- location ------------------------------->

    <section class="relative">
        <div class="w-[80%] max-[1024px]:w-[90%] mx-auto pb-16 pt-6 max-[1024px]:py-10">
            <div class="text-black font-bold text-[2.5rem] leading-[1] mb-8">A Journey through the Enchanting Locations of Haladrive.ae</div>
            <div class="grid grid-cols-5 items-center max-[1024px]:grid-cols-1 gap-6">
                <?php foreach($locationsContentData["locations"] as $location): ?>
                    <a href="location/<?php echo $location["slug"]; ?>" class="bg-[#f7f7f7] text-black relative text-center py-6 overflow-hidden">
                        <span class="inline-block w-4 h-4 absolute bg-[#ff000d] top-0 right-0"></span>
                        <div class="px-4 leading-[1]"><?php echo $location["location_en"]; ?></div>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!--------------------------------- footer ------------------------------->

<?php include_once('footer.php');?>