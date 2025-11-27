  <footer class="pt-[6rem] pb-[1rem] relative">
        <img src="<?= $imagePath ?>footer/footer-bg.webp" class="w-full h-full object-cover absolute inset-0 z-[-1]" alt="footer-bg">
        <div class="w-[80%] grid grid-cols-3 pb-[4rem] text-white m-auto gap-[3rem] banner-1">
            <div class="">
                <a href="#">
                    <img src="./images/logo/Hala-Drive-resize.webp" alt="Hala-Drive" class="max-[1000px]:m-auto">
                </a>
                <p class=" py-4">Your trusted partner for affordable and luxury car rentals across Dubai and the UAE.
                    Drive with confidence and convenience every time with well-maintained vehicles, flexible booking
                    options, and 24/7 customer support designed to keep your journey smooth.</p>
                <ul class="flex social gap-2">
                    <li>
                        <div class="bg-[#ff000d] p-3 rounded-full">
                            <a href="#"><img src="<?= $imagePath ?>icons/facebook.svg" class="w-6" alt="facebook"></a>
                        </div>
                    </li>
                    <li>
                        <div class="bg-[#ff000d] p-3 rounded-full">
                            <a href="#"><img src="<?= $imagePath ?>icons/insta.svg" class="w-6" alt="insta"></a>
                        </div>
                    </li>
                    <li>
                        <div class="bg-[#ff000d] p-3 rounded-full">
                            <a href="#"><img src="<?= $imagePath ?>icons/whatsapp.svg" class="w-6" alt="whatsapp"></a>
                        </div>
                    </li>
                </ul>

                <div class="social_icon">
                    <ul class="flex gap-1 list social_icom" style="margin-top:20px;">
                        <li>
                            <a href="https://www.facebook.com/quickdigitaldubai" target="_blank">
                                <img src="./images/facebook.svg" alt="facebook">
                                <span style="display:none;">facebook</span>
                            </a>
                        </li>
                        <li>
                            <a href="https://www.linkedin.com/company/quick-digital-solutions/" target="_blank">
                                <img src="./images/linkedin.svg" alt="linkedin">
                                <span style="display:none;">linkedin</span>
                            </a>
                        </li>
                        <li>
                            <a href="https://www.instagram.com/quickdigitaldubai/" target="_blank">
                                <img src="./images/insta.svg" alt="insta">
                                <span style="display:none;">instagram</span>
                            </a>
                        </li>
                        <li>
                            <!--<a href="https://twitter.com/quickdigitalae/" target="_blank">-->
                            <!--    <i class="fa-brands fa-twitter"></i>-->
                            <!--</a>-->
                        </li>
                    </ul>
                </div>
            </div>
            <div class="flex gap-[2rem] banner-1 first-1">
                <ul class="flex flex-col gap-5 mt-6">
                    <span class="font-bold text-[1.5rem] border-l-2 pl-2 border-[#ff000d] text-white ">Quick
                        Links</span>
                    <div class="grid grid-cols-2 gap-4 mt-6">
                        <li><a href="/src">Home</a></li>
                        <li><a href="/src/about.html">About Us</a></li>
                        <li><a href="/src/car.html">Cars</a></li>
                        <li><a href="/src/privacypolicy.html">Privacy Policy</a></li>
                        <li><a href="/src/faq.html">FAQ</a></li>
                        <li><a href="/src/location.html">Our Locations</a></li>
                    </div>
                </ul>
            </div>
            <div class="flex gap-[2rem] banner-1 first-1">
                <ul class="flex flex-col gap-5 mt-6">
                    <span class="font-bold text-[1.5rem] border-l-2 pl-2 border-[#ff000d] text-white ">Contact
                        Details</span>
                    <div class="flex flex-col gap-2 mt-6">
                        <li class="flex items-center gap-2">
                            <div class="bg-[#ff000d] p-1 rounded-full">
                                <img src="<?= $imagePath ?>icons/phone.svg" class="w-4" alt="phone">
                            </div>
                            <div class="flex gap-2 text-[#ff000d]">
                                <a href="tel:+971501837112">+971501837112</a>
                                /
                                <a href="tel:+97142711125">+97142711125</a>
                            </div>
                        </li>
                        <li class="flex items-center gap-2">
                            <div class="bg-[#ff000d] p-1 rounded-full">
                                <img src="<?= $imagePath ?>icons/mail.svg" class="w-4" alt="mail">
                            </div>
                            <a href="mailto:sales@haladrive.ae" class="text-[#ff000d]">sales@haladrive.ae</a>
                        </li>
                        <li class="flex items-center gap-2">
                            <div class="bg-[#ff000d] p-1 rounded-full">
                                <img src="<?= $imagePath ?>icons/location.svg" class="w-4" alt="location">
                            </div>
                            <a href="https://goo.gl/maps/9mWsWUggjrUJhYy66" class="text-[#ff000d]">Azurite tower shop
                                no.2 Al-Jaddaf</a>
                        </li>
                    </div>
                </ul>
            </div>
        </div>
        <div class="text-center mb-3 text-white">© <a class="border-b border-white" href="https://haladrive.ae">HALA Drive</a>, All Right Reserved. Powered By <a target="_blank" class="border-b border-white" href="https://quickdigitals.ae">Quick Digitals</a></div>
    </footer>

    </body>

</html>