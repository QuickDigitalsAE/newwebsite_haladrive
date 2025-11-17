<?php include_once('header.php');?>

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
            <!-- FAQ Item -->
            <div class="border border-gray-200 bg-[#f7fdff] rounded-md overflow-hidden">
                <button
                    class="faq-toggle w-full text-left flex justify-between items-center px-5 py-4 font-medium text-gray-700">
                    <span>How much better are your services for renting a car?</span>
                    <span class="text-2xl font-bold text-gray-400">+</span>
                </button>
                <div class="faq-content hidden px-5 pb-4 text-gray-600">
                    We provide top-notch rental services ensuring the best experience and reliability for all customers.
                </div>
            </div>

            <div class="border border-gray-200 bg-[#f7fdff] rounded-md overflow-hidden">
                <button
                    class="faq-toggle w-full text-left flex justify-between items-center px-5 py-4 font-medium text-gray-700">
                    <span>Can you tell the average cost to get a vehicle on rent in Dubai?</span>
                    <span class="text-2xl font-bold text-gray-400">+</span>
                </button>
                <div class="faq-content hidden px-5 pb-4 text-gray-600">
                    The cost depends on the vehicle model, duration, and season. Contact us for tailored pricing.
                </div>
            </div>

            <div class="border border-gray-200 bg-[#f7fdff] rounded-md overflow-hidden">
                <button
                    class="faq-toggle w-full text-left flex justify-between items-center px-5 py-4 font-medium text-gray-700">
                    <span>Is it true that renting a car in Dubai has a specific age limit?</span>
                    <span class="text-2xl font-bold text-gray-400">+</span>
                </button>
                <div class="faq-content hidden px-5 pb-4 text-gray-600">
                    Yes, typically drivers must be at least 21 years old to rent a car in Dubai.
                </div>
            </div>

            <div class="border border-gray-200 bg-[#f7fdff] rounded-md overflow-hidden">
                <button
                    class="faq-toggle w-full text-left flex justify-between items-center px-5 py-4 font-medium text-gray-700">
                    <span>Should I require an international driving permit or UAE license for renting?</span>
                    <span class="text-2xl font-bold text-gray-400">+</span>
                </button>
                <div class="faq-content hidden px-5 pb-4 text-gray-600">
                    UAE residents can use a valid UAE license. Tourists may need an international driving permit.
                </div>
            </div>

            <div class="border border-gray-200 bg-[#f7fdff] rounded-md overflow-hidden">
                <button
                    class="faq-toggle w-full text-left flex justify-between items-center px-5 py-4 font-medium text-gray-700">
                    <span>Are additional charges associated with renting a car?</span>
                    <span class="text-2xl font-bold text-gray-400">+</span>
                </button>
                <div class="faq-content hidden px-5 pb-4 text-gray-600">
                    Some rentals include extra charges like insurance or mileage — all details are shared upfront.
                </div>
            </div>

            <div class="border border-gray-200 bg-[#f7fdff] rounded-md overflow-hidden">
                <button
                    class="faq-toggle w-full text-left flex justify-between items-center px-5 py-4 font-medium text-gray-700">
                    <span>Can I take my rental car outside of Dubai?</span>
                    <span class="text-2xl font-bold text-gray-400">+</span>
                </button>
                <div class="faq-content hidden px-5 pb-4 text-gray-600">
                    Taking the vehicle outside UAE boundaries is not allowed unless explicitly permitted.
                </div>
            </div>

            <div class="border border-gray-200 bg-[#f7fdff] rounded-md overflow-hidden">
                <button
                    class="faq-toggle w-full text-left flex justify-between items-center px-5 py-4 font-medium text-gray-700">
                    <span>Are credit cards accepted as payment when renting a car in Dubai?</span>
                    <span class="text-2xl font-bold text-gray-400">+</span>
                </button>
                <div class="faq-content hidden px-5 pb-4 text-gray-600">
                    Yes, all major credit cards are accepted for security deposit and payment.
                </div>
            </div>
        </div>
    </section>

    <!--------------------------------- footer ------------------------------->

<?php include_once('footer.php');?>