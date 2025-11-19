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
    $privacyPolicyContent = $api->loadData('webcontent', 'privacy-policy', []);
    
    if ($privacyPolicyContent['success']) {
        // Use the data in your page
        $privacyPolicyContentData = $privacyPolicyContent['data']["data"];
        // print_r($privacyPolicyContentData);
    }
} catch (Exception $e) {
    echo "Error loading featured products: " . $e->getMessage();
}
?>


<?php
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