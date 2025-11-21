<?php
session_start();
require_once 'apis/ApiHandler.php';
$api = new ApiHandler();
?>

<?php
try {
    $privacyPolicyContent = $api->loadData('webcontent', 'privacy-policy', []);
    if ($privacyPolicyContent['success']) {
        $privacyPolicyContentData = $privacyPolicyContent['data']["data"];
    }
} catch (Exception $e) {
    echo "Error loading featured products: " . $e->getMessage();
}
?>


<?php
$meta_title = 'kia azhar';
$meta_desc = 'kia azhar';
include_once('header.php');

$banner_image = "$imagePath/about/top-banner.webp";
$banner_title = "Privacy Policy";
include_once('banner.php');
?>


        <!--------------------------------- faq ------------------------------->

    <section class="w-[80%] max-[1024px]:w-[90%] mx-auto py-16 max-[1024px]:py-10">
        <div class="">
            <?php echo $privacyPolicyContentData["policy"]["privacy_policy_{$lang}"]; ?>
        </div>
    </section>

    <!--------------------------------- footer ------------------------------->

<?php include_once('footer.php');?>