<?php
// Include global
require_once 'global.php';

// Default SEO fallback values
$meta_title = '';
$meta_desc  = '';

try {
    // This will be processed before rendering the page
    $locationsContent = $api->loadData('location', 'main', []);
    
    if ($locationsContent['success']) {
        // Use the data in your page
        $locationsContentData = $locationsContent['data']["data"];

        $titleKey = "title_" . $lang;
            $descKey  = "description_" . $lang;

            $meta_title = $locationsContentData["meta_data"][$titleKey] ?? '';
            $meta_desc  = $locationsContentData["meta_data"][$descKey] ?? '';
        // print_r($locationsContentData);
    }
} catch (Exception $e) {
    echo "Error loading featured products: " . $e->getMessage();
}

include_once('header.php');

$banner_image = "$imagePath/about/top-banner.webp";
$banner_title = $messages["locationsBannerHeading"];
$banner_subtitle = $messages['locationsBannerPera'];
include_once('banner.php');
?>

    <!--------------------------------- location ------------------------------->

    <section class="relative">
       
        <div class="w-[80%] max-[1024px]:w-[90%] mx-auto pb-16 pt-6 max-[1024px]:py-10">
            <h1 class="text-black font-bold text-[2.5rem] leading-[1] mb-8"><?= $messages["loactionsMainHeading"]; ?></h1>
            <div class="grid grid-cols-5 items-center max-[1024px]:grid-cols-1 gap-6">
                <?php foreach($locationsContentData["locations"] as $location): ?>
                    <a href="<?php echo $location["slug"]; ?>" class="bg-[#f7f7f7] text-black relative text-center py-6 overflow-hidden">
                        <span class="inline-block w-4 h-4 absolute bg-[#ff000d] top-0 right-0"></span>
                        <div class="px-4 leading-[1]"><?php echo $location["location_{$lang}"]; ?></div>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!--------------------------------- footer ------------------------------->

<?php include_once('footer.php');?>