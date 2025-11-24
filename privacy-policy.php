<?php
require_once 'global.php';

$meta_title = '';
$meta_desc  = '';
 

try {
    $privacyPolicyContent = $api->loadData('webcontent', 'privacy-policy', []);
    if ($privacyPolicyContent['success']) {
        $privacyPolicyContentData = $privacyPolicyContent['data']["data"];

        $titleKey = "title_" . $lang;
            $descKey  = "description_" . $lang;

            $meta_title = $privacyPolicyContentData["meta_data"][$titleKey] ?? '';
            $meta_desc  = $privacyPolicyContentData["meta_data"][$descKey] ?? '';
    }
} catch (Exception $e) {
    echo "Error loading featured products: " . $e->getMessage();
}

include_once('header.php');

$banner_image = "$imagePath/about/top-banner.webp";
$banner_title = $messages['privacypolicy'];
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