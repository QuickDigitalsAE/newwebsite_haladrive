<?php
// Include global
require_once 'global.php';

// Default SEO fallback values
$meta_title = '';
$meta_desc  = '';

try {
    $faqContent = $api->loadData('webcontent', 'faq', []);
    
    if ($faqContent['success']) {
        $faqContentData = $faqContent['data']["data"];

        // Debug field names (run once)
        // echo "<pre>"; print_r($faqContentData["meta_data"]); echo "</pre>"; exit;

        $titleKey = "title_" . $lang;
        $descKey  = "description_" . $lang;

        $meta_title = $faqContentData["meta_data"][$titleKey] ?? '';
        $meta_desc  = $faqContentData["meta_data"][$descKey] ?? '';
    }
} catch (Exception $e) {
    echo "Error loading featured products: " . $e->getMessage();
}

require_once 'header.php';

$banner_image = "$imagePath/about/top-banner.webp";
$banner_title = $messages['homeFaqs_1'];
include_once('banner.php');
?>

        <!--------------------------------- faq ------------------------------->

    <section class="w-[80%] max-[1024px]:w-[90%] mx-auto py-16 max-[1024px]:py-10">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-10">
            <div>
                <h2 class="text-2xl md:text-3xl font-bold mb-2"><?= $messages['homeFaqs_1'] ?></h2>
                <p class="text-gray-600"><?= $messages['homeFaqs_pera_1'] ?></p>
            </div>
            <div
                class="relative border-2 border-[#92aacb] px-5 py-3 mt-4 md:mt-0 flex items-center gap-3 shadow-[1px_1px_2px_#999] -skew-x-12">
                <div>
                    <p class="font-semibold text-gray-800"><?= $messages['homeFaqs_2'] ?></p>
                    <p class="text-sm text-gray-500"><?= $messages['homeFaqs_pera_2'] ?></p>
                </div>
                <div class="bg-[#1bd741] p-2 -skew-x-4">
                    <img src="<?= $imagePath ?>icons/whatsapp.svg" alt="WhatsApp" class="w-8 h-8">
                </div>
            </div>
        </div>

        <!-- Accordion -->
        <div class="space-y-3">
            <?php foreach($faqContentData["faqs"] as $faqs): ?>
                <div class="border border-gray-200 bg-[#f7fdff] rounded-md overflow-hidden">
                    <button
                        class="faq-toggle w-full text-left flex justify-between items-center px-5 py-4 font-medium text-gray-700">
                        <span><?php echo $faqs["question_{$lang}"]; ?></span>
                        <span class="text-2xl font-bold text-gray-400">+</span>
                    </button>
                    <div class="faq-content hidden px-5 pb-4 text-gray-600">
                        <?php echo $faqs["answer_{$lang}"]; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!--------------------------------- footer ------------------------------->

<?php include_once('footer.php');?>