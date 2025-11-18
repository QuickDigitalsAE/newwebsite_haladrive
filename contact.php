<?php include_once('header.php');?>

<?php
$banner_image = "images/about/top-banner.webp";
$banner_title = "Contact Us";
$banner_subtitle = "Top rated car rental in Dubai. Low prices, great deals, convenient pick-up, top-notch service!";
include_once('banner.php');
?>

    <!--------------------------------- location ------------------------------->

    <section class="relative">
        <div class="w-[80%] max-[1024px]:w-[90%] mx-auto pb-16 pt-6 max-[1024px]:py-10">
            <div class="text-black font-bold text-[2.5rem] leading-[1] mb-8">Contact</div>
            <div class="grid grid-cols-3 max-[1024px]:grid-cols-1 gap-6">
                <div
                    class="rounded-[10px] px-6 py-10 shadow-[0_5px_90px_0_rgba(110,123,131,0.1)] border-b-[6px] border-transparent hover:border-[#ff000d] hover:scale-[1.03] transition-all duration-300 ease-in-out">
                    <div class="font-bold pl-2 text-[1.5rem] border-l-4 border-[#ff000d]">Address</div>
                    <div class="mt-6 flex gap-2">
                        <img src="images/icons/location-red.svg" class="w-8" alt="">
                        <div class="font-bold text-black leading-[1]">Azurite tower shop no.2 Al-Jaddaf</div>
                    </div>
                </div>
                <div
                    class="rounded-[10px] px-6 py-10 shadow-[0_5px_90px_0_rgba(110,123,131,0.1)] border-b-[6px] border-transparent hover:border-[#ff000d] hover:scale-[1.03] transition-all duration-300 ease-in-out">
                    <div class="font-bold pl-2 text-[1.5rem] border-l-4 border-[#ff000d]">Open Hours</div>
                    <div class="mt-6 flex gap-2 items-center">
                        <img src="images/icons/clock.svg" class="w-8" alt="">
                        <div class="font-bold text-black leading-[1]">9:00 AM to 7.00 PM</div>
                    </div>
                </div>
                <div
                    class="rounded-[10px] px-6 py-10 shadow-[0_5px_90px_0_rgba(110,123,131,0.1)] border-b-[6px] border-transparent hover:border-[#ff000d] hover:scale-[1.03] transition-all duration-300 ease-in-out">
                    <div class="font-bold pl-2 text-[1.5rem] border-l-4 border-[#ff000d]">Customer Support</div>
                    <div class="mt-6 flex gap-2 items-center">
                        <img src="images/icons/phone-red.svg" class="w-8" alt="">
                        <a href="tel:+971501837112" class="font-bold text-black leading-[1]">+971501837112</a>
                    </div>
                    <div class="mt-2 flex gap-2 items-center">
                        <img src="images/icons/phone-red.svg" class="w-8" alt="">
                        <a href="tel:+97142711125" class="font-bold text-black leading-[1]">+97142711125</a>
                    </div>
                </div>
            </div>
            <div class="grid grid-cols-2 max-[1024px]:grid-cols-1 items-center gap-10 mt-10">
                <div class="">
                    <div class="text-black text-[2rem] font-bold mb-6">Contact Us</div>
                    <form action="">
                        <div class="grid grid-cols-1 gap-4">
                            <input class="border border-[#ced4da] px-4 py-2 focus:outline-none placeholder:text-[#939393]"
                                type="text" placeholder="Your Name">
                            <input class="border border-[#ced4da] px-4 py-2 focus:outline-none placeholder:text-[#939393]"
                                type="tel" placeholder="Your Number">
                            <input class="border border-[#ced4da] px-4 py-2 focus:outline-none placeholder:text-[#939393]"
                                type="email" placeholder="Your Email">
                            <textarea
                                class="border border-[#ced4da] px-4 py-2 focus:outline-none placeholder:text-[#939393]"
                                name="" id="" placeholder="Message"></textarea>
                        </div>
                        <button
                            class="text-white bg-[#ff000d] transition-all duration-300 px-6 py-2 mx-auto mt-4 uppercase hover:bg-[#b8101f]">send
                            message</button>
                    </form>
                </div>
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3609.460411329929!2d55.32507!3d25.2214132!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3e5f5d9c7bcfa2e1%3A0xd537887f6324ed6!2sHala%20Drive%20Car%20rental!5e0!3m2!1sen!2sae!4v1692009056978!5m2!1sen!2sae" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </div>
    </section>

<?php include_once('footer.php');?>