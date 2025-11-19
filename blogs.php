<?php
session_start();
require_once 'apis/ApiHandler.php';
$api = new ApiHandler();

$slug = $_GET['slug'] ?? null;

include_once('header.php');

if ($slug) {

    try {
        $singleBlogContent = $api->loadData('blogs', 'single', [], $slug);

        if ($singleBlogContent['success']) {
            $singleBlogContentData = $singleBlogContent['data']["data"];
        }
    } catch (Exception $e) {
        echo "Error loading car details: " . $e->getMessage();
    }

    $banner_image = "images/about/top-banner.webp";
    $banner_title = 'Blogs';
    // $banner_subtitle = "Top rated car rental in Dubai. Low prices & great deals!";
    include_once('banner.php');
    ?>

    <!--------------------------------- blogs inner ------------------------------->

    <section class="relative">
        <div class="w-[90%] max-[1024px]:w-[90%] mx-auto py-16 max-[1024px]:py-10">
            <div class="">
                <div class="">
                    <img src="<?php echo $singleBlogContentData["blog"]["image_url"]; ?>" class="rounded-[10px]" alt="<?php echo $singleBlogContentData["blog"]["img_alt_{$lang}"]; ?>">
                    <div class="">
                        <div class="font-bold text-[#939393] text-[1rem] mt-12">10 Nov 2025</div>
                    </div>
                    <div class="mt-6 string">
                        <?php echo $singleBlogContentData["blog"]["description_{$lang}"]; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!--------------------------------- footer ------------------------------->

    <?php include_once('footer.php'); exit;
}


// -----------------------------------------
// PAGE WITHOUT SLUG → SHOW MAIN PAGE
// -----------------------------------------

try {
    $blogsContent = $api->loadData('blogs', 'main', []);
    if ($blogsContent['success']) {
        $blogsContentData = $blogsContent['data']["data"];
    }
} catch (Exception $e) {
    echo "Error loading car list: " . $e->getMessage();
}

$banner_image = "images/about/top-banner.webp";
$banner_title = "Explore Our Signature Collection";
$banner_subtitle = "Top rated car rental in Dubai!";
include_once('banner.php');
?>

    <section class="relative">
        <div class="w-[90%] max-[1024px]:w-[90%] mx-auto py-16 max-[1024px]:py-10">
            <div class="grid grid-cols-3 max-[1024px]:grid-cols-1 gap-10">
                <?php foreach($blogsContentData["blogs"]["data"] as $blogs): ?>
                    <a href="blogs/<?php echo $blogs["slug"]; ?>" class="rounded-[10px] border border-[#dfdfdf]">
                        <img src="<?php echo $blogs["image_url"]; ?>" class="rounded-[10px]" alt="<?php echo $blogs["img_alt_{$lang}"]; ?>">
                        <div class="p-4">
                            <div class="font-bold text-[#939393] text-[1rem]">10 Nov 2025</div>
                            <div class="text-black font-bold leading-[1] text-[1.3rem] mt-4"><?php echo $blogs["title_{$lang}"]; ?></div>
                            <button class="uppercase bg-[#ff000d] text-white w-fit px-4 py-2 mt-2 rounded-[5px]">read more</button>
                        </div>
                    </a>
                 <?php endforeach; ?>
            </div>
        </div>
    </section>

<?php include_once('footer.php'); ?>