<?php 
require_once 'global.php';

$meta_title = '';
$meta_desc  = '';
 

try {
    $contactContent = $api->loadData('webcontent', 'contact', []);
    if ($contactContent['success']) {
        $contactContentData = $contactContent['data']["data"];

        $titleKey = "title_" . $lang;
        $descKey  = "description_" . $lang;

        $meta_title = $contactContentData["meta_data"][$titleKey] ?? '';
        $meta_desc  = $contactContentData["meta_data"][$descKey] ?? '';
    }
} catch (Exception $e) {
    echo "Error loading featured products: " . $e->getMessage();
}

include_once('header.php');

$banner_image = $imagePath."/about/top-banner.webp";
$banner_title = $messages['contact'];
$banner_subtitle = $messages['aboutBannerPera'];
include_once('banner.php');

$message = ''; // Initialize message variable
$alertClass = ''; // For styling the alert

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // 1. Collect the form data
    $contactData = [
        'name' => $_POST['name'] ?? '',
        'number' => $_POST['number'] ?? '',
        'email' => $_POST['email'] ?? '',
        'message' => $_POST['message'] ?? '',
    ];

    try {
        // 2. Send the data to API
        $response = $api->post('contact', 'store', $contactData, 'json');
        $httpCode = $api->getLastHttpCode();

        // 3. Handle response
        if ($httpCode >= 200 && $httpCode < 300) {
            $message = "Your message has been sent successfully!";
            $alertClass = "alert-success";
        } else {
            $errorMessage = $response['message'] ?? 'Unknown API Error';
            $message = "Error submitting form: " . htmlspecialchars($errorMessage);
            $alertClass = "alert-error";
        }

    } catch (Exception $e) {
        $message = "A server error occurred: " . htmlspecialchars($e->getMessage());
        $alertClass = "alert-error";
    }

} 

?>
<style>
    .alert-success {
    background-color: #d4edda;
    color: #155724;
    padding: 10px;
    margin-bottom: 10px;
    border-radius: 5px;
}

.alert-error {
    background-color: #f8d7da;
    color: #721c24;
    padding: 10px;
    margin-bottom: 10px;
    border-radius: 5px;
}

</style>

    <!--------------------------------- location ------------------------------->

    <section class="relative">
        <div class="w-[80%] max-[1024px]:w-[90%] mx-auto pb-16 pt-6 max-[1024px]:py-10">
            <div class="text-black font-bold text-[2.5rem] leading-[1] mb-8"><?= $messages['contact'] ?></div>
            <!-- Display the alert -->
            <?php if ($message): ?>
            <div id="formAlert" class="<?= $alertClass ?>">
                <?= $message ?>
            </div>

            <script>
            // Hide alert after 3 seconds (3000ms)
            setTimeout(function() {
                const alertDiv = document.getElementById('formAlert');
                if (alertDiv) {
                    alertDiv.style.transition = "opacity 0.5s ease";
                    alertDiv.style.opacity = 0;
                    setTimeout(() => alertDiv.remove(), 500); // remove after fade out
                }
            }, 3000);
            </script>
            <?php endif; ?>
            <div class="grid grid-cols-3 max-[1024px]:grid-cols-1 gap-6">
                <div
                    class="rounded-[10px] px-6 py-10 shadow-[0_5px_90px_0_rgba(110,123,131,0.1)] border-b-[6px] border-transparent hover:border-[#ff000d] hover:scale-[1.03] transition-all duration-300 ease-in-out">
                    <div class="font-bold pl-2 text-[1.5rem] border-l-4 border-[#ff000d]"><?= $messages['contactaddress1'] ?></div>
                    <div class="mt-6 flex gap-2">
                        <img src="<?= $imagePath ?>icons/location-red.svg" class="w-8" alt="">
                        <div class="font-bold text-black leading-[1]"><?= $messages['contactaddress2'] ?></div>
                    </div>
                </div>
                <div
                    class="rounded-[10px] px-6 py-10 shadow-[0_5px_90px_0_rgba(110,123,131,0.1)] border-b-[6px] border-transparent hover:border-[#ff000d] hover:scale-[1.03] transition-all duration-300 ease-in-out">
                    <div class="font-bold pl-2 text-[1.5rem] border-l-4 border-[#ff000d]"><?= $messages['contacthours'] ?></div>
                    <div class="mt-6 flex gap-2 items-center">
                        <img src="<?= $imagePath ?>icons/clock.svg" class="w-8" alt="">
                        <div class="font-bold text-black leading-[1]"><?= $messages['contacttime'] ?></div>
                    </div>
                </div>
                <div
                    class="rounded-[10px] px-6 py-10 shadow-[0_5px_90px_0_rgba(110,123,131,0.1)] border-b-[6px] border-transparent hover:border-[#ff000d] hover:scale-[1.03] transition-all duration-300 ease-in-out">
                    <div class="font-bold pl-2 text-[1.5rem] border-l-4 border-[#ff000d]"><?= $messages['contactsupport'] ?></div>
                    <div class="mt-6 flex gap-2 items-center">
                        <img src="<?= $imagePath ?>icons/phone-red.svg" class="w-8" alt="">
                        <a href="tel:+971501837112" class="font-bold text-black leading-[1]">+971501837112</a>
                    </div>
                    <div class="mt-2 flex gap-2 items-center">
                        <img src="<?= $imagePath ?>icons/phone-red.svg" class="w-8" alt="">
                        <a href="tel:+97142711125" class="font-bold text-black leading-[1]">+97142711125</a>
                    </div>
                </div>
            </div>
            <div class="grid grid-cols-2 max-[1024px]:grid-cols-1 items-center gap-10 mt-10">
                <div class="">
                    <div class="text-black text-[2rem] font-bold mb-6"><?= $messages['contact'] ?></div>
                    <form action="./contact" method="POST">
                        <div class="grid grid-cols-1 gap-4">
                            <input class="border border-[#ced4da] px-4 py-2 focus:outline-none placeholder:text-[#939393]"
                                type="text" placeholder="<?= $messages['name'] ?>" name="name" required>
                            <input class="border border-[#ced4da] px-4 py-2 focus:outline-none placeholder:text-[#939393]"
                                type="tel" placeholder="<?= $messages['number'] ?>" name="number" required>
                            <input class="border border-[#ced4da] px-4 py-2 focus:outline-none placeholder:text-[#939393]"
                                type="email" placeholder="<?= $messages['email'] ?>" name="email" required>
                            <textarea
                                class="border border-[#ced4da] px-4 py-2 focus:outline-none placeholder:text-[#939393]"
                                name="message" id="message" placeholder="<?= $messages['message'] ?>"></textarea>
                        </div>
                        <button type="submit"
                            class="text-white bg-[#ff000d] transition-all duration-300 px-6 py-2 mx-auto mt-4 uppercase hover:bg-[#b8101f]">
                            <?= $messages['send'] ?>
                        </button>
                    </form>
                </div>
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3609.460411329929!2d55.32507!3d25.2214132!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3e5f5d9c7bcfa2e1%3A0xd537887f6324ed6!2sHala%20Drive%20Car%20rental!5e0!3m2!1sen!2sae!4v1692009056978!5m2!1sen!2sae" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </div>
    </section>

<?php include_once('footer.php');?>