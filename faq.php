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
    $faqContent = $api->loadData('webcontent', 'faq', []);
    
    if ($faqContent['success']) {
        // Use the data in your page
        $faqContentData = $faqContent['data']["data"];
        // print_r($faqContentData);
    }
} catch (Exception $e) {
    echo "Error loading featured products: " . $e->getMessage();
}
?>


<?php
$banner_image = "images/about/top-banner.webp";
$banner_title = "Frequently Asked Questions";
include_once('banner.php');
?>

        <!--------------------------------- faq ------------------------------->

    <section class="w-[80%] max-[1024px]:w-[90%] mx-auto py-16 max-[1024px]:py-10">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-10">
            <div>
                <h2 class="text-2xl md:text-3xl font-bold mb-2">Frequently Asked Questions</h2>
                <p class="text-gray-600">These are the questions we hear more often.</p>
            </div>
            <div
                class="relative border-2 border-[#92aacb] px-5 py-3 mt-4 md:mt-0 flex items-center gap-3 shadow-[1px_1px_2px_#999] -skew-x-12">
                <div>
                    <p class="font-semibold text-gray-800">Don't see the answer you need?</p>
                    <p class="text-sm text-gray-500">Just drop a msg we will get back to you ASAP!</p>
                </div>
                <div class="bg-[#1bd741] p-2 -skew-x-4">
                    <img src="images/icons/whatsapp.svg" alt="WhatsApp" class="w-8 h-8">
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