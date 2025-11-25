
    <!--------------------------------- footer ------------------------------->

    <footer class="pt-[6rem] max-[1024px]:pt-[2rem] pb-[1rem] relative ">
        <img src="<?= $imagePath ?>footer/footer-bg.webp" class="w-full h-full object-cover absolute inset-0 z-[-1] " alt="">
         <div class="whatsapp_btn">
            <a target="_blank" href="https://wa.me/971501837112?text=Hi">
                <img  src="<?= $imagePath ?>icons/whatsapp.svg" alt="">
            </a>
        </div>
        <div
            class="w-[80%] max-[1024px]:w-[90%] grid grid-cols-3 max-[1024px]:grid-cols-1 pb-[4rem] max-[1024px]:pb-[2rem] text-white m-auto gap-[3rem] max-[1024px]:gap-4 banner-1">
            <div class="">
                <a href="">
                    <img src="<?= $imagePath ?>/logo/Hala-Drive-resize.webp" alt=""
                        class="max-[1000px]:m-auto max-[1024px]:w-[100px]">
                </a>
                <p class=" py-4"><?= $messages['content'] ?></p>
                <ul class="flex social gap-2">
                    <li>
                        <div class="bg-[#ff000d] p-3 rounded-full">
                            <a href="https://www.facebook.com/HalaDrive"><img src="<?= $imagePath ?>icons/facebook.svg" class="w-6" alt=""></a>
                        </div>
                    </li>
                    <li>
                        <div class="bg-[#ff000d] p-3 rounded-full">
                            <a href="https://www.instagram.com/hala_drive_car_rental/"><img src="<?= $imagePath ?>icons/insta.svg" class="w-6" alt=""></a>
                        </div>
                    </li>
                    <li>
                        <div class="bg-[#ff000d] p-3 rounded-full">
                            <a href="https://wa.me/971501837112?text=Hi"><img src="<?= $imagePath ?>icons/whatsapp.svg" class="w-6" alt=""></a>
                        </div>
                    </li>
                </ul>

                <!-- <div class="social_icon">
                    <ul class="flex gap-1 list social_icom" style="margin-top:20px;">
                        <li>
                            <a href="https://www.facebook.com/quickdigitaldubai" target="_blank">
                                <img src="<?= $imagePath ?>icons/facebook.svg" alt="">
                            </a>
                        </li>
                        <li>
                            <a href="https://www.linkedin.com/company/quick-digital-solutions/" target="_blank">
                                <img src="<?= $imagePath ?>icons/linkedin.svg" alt="">
                            </a>
                        </li>
                        <li>
                            <a href="https://www.instagram.com/quickdigitaldubai/" target="_blank">
                                <img src="<?= $imagePath ?>icons/insta.svg" alt="">
                            </a>
                        </li>
                    </ul>
                </div> -->
            </div>
            <div class="flex gap-[2rem] banner-1 first-1">
                <ul class="flex flex-col gap-5 mt-6 max-[1024px]:mt-0">
                    <span class="font-bold text-[1.5rem] border-l-2 pl-2 border-[#ff000d] text-white "><?= $messages['quick'] ?></span>
                    <div class="grid grid-cols-2 gap-4 mt-6">
                        <li><a href=""><?= $messages['home'] ?></a></li>
                        <li><a href="about"><?= $messages['about'] ?></a></li>
                        <li><a href="cars"><?= $messages['cars'] ?></a></li>
                        <li><a href="privacy-policy"><?= $messages['privacy'] ?></a></li>
                        <li><a href="faq"><?= $messages['faq'] ?></a></li>
                        <li><a href="locations"><?= $messages['locations'] ?></a></li>
                    </div>
                </ul>
            </div>
            <div class="flex gap-[2rem] banner-1 first-1">
                <ul class="flex flex-col gap-5 mt-6 max-[1024px]:mt-4">
                    <span class="font-bold text-[1.5rem] border-l-2 pl-2 border-[#ff000d] text-white "><?= $messages['details'] ?></span>
                    <div class="flex flex-col gap-2 mt-6 max-[1024px]:mt-2">
                        <li class="flex items-center gap-2">
                            <div class="bg-[#ff000d] p-1 rounded-full">
                                <img src="<?= $imagePath ?>icons/phone.svg" class="w-4" alt="">
                            </div> 
                            <div class="flex gap-2 text-[#ff000d]">
                                <a href="tel:+971501837112">+971501837112</a>
                                /
                                <a href="tel:+97142711125">+97142711125</a>
                            </div>
                        </li>
                        <li class="flex items-center gap-2">
                            <div class="bg-[#ff000d] p-1 rounded-full">
                                <img src="<?= $imagePath ?>icons/mail.svg" class="w-4" alt="">
                            </div>
                            <a href="mailto:sales@haladrive.ae" class="text-[#ff000d]">sales@haladrive.ae</a>
                        </li>
                        <li class="flex items-center gap-2">
                            <div class="bg-[#ff000d] p-1 rounded-full">
                                <img src="<?= $imagePath ?>icons/location.svg" class="w-4" alt="">
                            </div>
                            <a href="https://goo.gl/maps/9mWsWUggjrUJhYy66" class="text-[#ff000d]">Azurite tower shop
                                no.2 Al-Jaddaf</a>
                        </li>
                    </div>
                </ul>
            </div>
        </div>
        <div class="text-center mb-3 text-white">© <a class="border-b border-white" href="https://haladrive.ae"><?= $messages['hala'] ?> </a><?= $messages['rights'] ?> <a target="_blank" class="border-b border-white" href="https://quickdigitals.ae"> <?= $messages['quickdigitals'] ?></a></div>
    </footer>


<!--------------------------------- POP UP ------------------------------->

    <div id="myModal" class="modal">
        <div class="modal-content">
            <div class="modal-header background_image">
                <img src="https://haladrive.ae/img/Hala-Drive.webp" style="display: block; width: 10rem; margin: 0 auto;" width="100" class="block">
                <button type="button" class="btn-close close custom_butnclose">
                    <img src="<?= $imagePath ?>icons/close.svg" alt="">
                </button>
            </div>
            <div></div>
            <div class="modal-body w-full p-6">
                <h5 class="text-xl text-center font-semibold mb-4" style="font-family: "Poppins", sans-serif;"><?= $messages['book'] ?></h5>

                <form method="POST" id="inquire-form" class="w-full" style="font-family: "Poppins", sans-serif;">
                    <input type="hidden" name="_token" value="FV5HbL6ZEq4mAded9gT90jqo2DrRpPtDjioznKXj">                    <!-- Name -->
                    <div class="relative w-full mb-3">
                        <input type="text" name="name" id="name" placeholder="<?= $messages['name'] ?>" required="" class="custom_input pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none">
                    </div>

                    <!-- Date from -->
                    <div class="relative w-full mb-3">
                        <input type="date" name="date_from" onfocus="this.showPicker()" oninput="document.getElementById('date_to_modal').min = this.value;" id="date_from_modal" placeholder="Date From" required="" class="custom_input pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none" min="2025-11-21">
                    </div>

                    <!-- Date To -->
                    <div class="relative w-full mb-3">
                        <input type="date" name="date_to" onfocus="this.showPicker()" id="date_to_modal" placeholder="Date to" required="" class="custom_input pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none" min="2025-11-21">
                    </div>

                    <!-- Phone -->
                    <div class="relative w-full mb-3">
                        <input type="text" name="number" id="contact_number" placeholder="<?= $messages['number'] ?>" required="" class="custom_input pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none">
                    </div>

                    <!-- Email -->
                    <div class="relative w-full mb-3">
                        <input type="email" name="email" id="email" placeholder="<?= $messages['email'] ?>" class="custom_input pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none">
                    </div>
                    <input type="hidden" name="car_name" id="car_name" value="">
                    <!-- Message -->
                    <div class="relative w-full mb-3">
                        <textarea name="message" id="carName" placeholder="<?= $messages['message'] ?>" rows="4" required="" class="custom_input pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-400 focus:outline-none"></textarea>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="w-full mx-auto custom_button_modal">
                        <?= $messages['send'] ?>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <script>
        const modal = document.getElementById("myModal");
        const buttons = document.querySelectorAll(".openModalBtn");
        const closeBtn = document.querySelector(".close");

        // Loop through all buttons
        buttons.forEach(btn => {
            btn.onclick = function () {
                modal.style.display = "block";
            };
        });

        // Close modal
        closeBtn.onclick = function () {
            modal.style.display = "none";
        }

        // Close modal on outside click
        window.onclick = function (event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }


        // AJAX form submission with JS alert
        document.getElementById('inquire-form').addEventListener('submit', function(e) {
            e.preventDefault(); // prevent default form submission

            const form = e.target;
            const formData = new FormData(form);
            const base_url = 'https://admin.haladrive.ae/api/v1';

            fetch(base_url+'/en/contact/send/inquire', { // relative API endpoint
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                // Show message in JS alert
                if (data.success) {
                    alert(data.message || 'Inquiry sent successfully!');
                } else {
                    alert(data.message || 'Error submitting inquiry.');
                }

                // Close the modal
                const modal = document.getElementById('myModal');
                const bsModal = bootstrap.Modal.getInstance(modal);
                if (bsModal) bsModal.hide();

                // Reset the form
                form.reset();

            })
            .catch(err => {
                alert('A server error occurred.');
            });
        });
    </script>



    <script>
        (function () {
            const btn = document.getElementById('dropdownUsersButton');
            const menu = document.getElementById('dropdownUsers');

            if (!btn || !menu) return;

            // initialize aria attribute
            btn.setAttribute('aria-haspopup', 'true');
            btn.setAttribute('aria-expanded', 'false');

            // Toggle handler
            btn.addEventListener('click', function (e) {
            e.stopPropagation();
            const isOpen = !menu.classList.contains('hidden');
            if (isOpen) {
                menu.classList.add('hidden');
                btn.setAttribute('aria-expanded', 'false');
            } else {
                menu.classList.remove('hidden');
                btn.setAttribute('aria-expanded', 'true');

                // optional: focus first link for keyboard users
                const firstLink = menu.querySelector('a');
                if (firstLink) firstLink.focus();
            }
            });

            // Close when clicking outside
            document.addEventListener('click', function (ev) {
            if (!btn.contains(ev.target) && !menu.contains(ev.target)) {
                menu.classList.add('hidden');
                btn.setAttribute('aria-expanded', 'false');
            }
            });

            // Close on Escape
            document.addEventListener('keydown', function (ev) {
            if (ev.key === 'Escape') {
                menu.classList.add('hidden');
                btn.setAttribute('aria-expanded', 'false');
                btn.focus();
            }
            });

            // Close when a menu link is clicked (useful for single-page and navigation)
            menu.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', function () {
                menu.classList.add('hidden');
                btn.setAttribute('aria-expanded', 'false');
            });
            });
        })();
    </script>

    <script>
        const menuBtn = document.getElementById("menuBtn");
        const mobileMenu = document.getElementById("mobileMenu");

        menuBtn.addEventListener("click", () => {
            if (mobileMenu.classList.contains("max-[1024px]:top-[-100%]")) {
                // Menu hidden hai → show karo
                mobileMenu.classList.remove("max-[1024px]:top-[-100%]");
                mobileMenu.classList.add("!top-0");
            } else {
                // Menu visible hai → hide karo
                mobileMenu.classList.remove("!top-0");
                mobileMenu.classList.add("max-[1024px]:top-[-100%]");
            }
        });
    </script>


    <script>
        const swiper = new Swiper(".mySwiper", {
            loop: true,
            autoplay: {
                delay: 3000,
                disableOnInteraction: false,
            },
            pagination: {
                el: ".swiper-pagination",
                clickable: true,
            },
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
            speed: 800,
        });
    </script>


    <script>
        const swiper1 = new Swiper('.mySwiper1', {
            loop: true,
            spaceBetween: 20,
            slidesPerView: 4,             // show 4 slides by default
            centeredSlides: false,
            autoplay: {
                delay: 2500,
                disableOnInteraction: false,
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            // Responsive breakpoints
            breakpoints: {
                320: { slidesPerView: 4 },
                640: { slidesPerView: 4 },
                900: { slidesPerView: 4 },
                1200: { slidesPerView: 4 }
            },
            speed: 700,
        });
    </script>

    <script>
        const toggles = document.querySelectorAll('.faq-toggle');
        toggles.forEach(btn => {
            btn.addEventListener('click', () => {
                const content = btn.nextElementSibling;
                const plus = btn.querySelector('span:last-child');
                content.classList.toggle('hidden');
                plus.textContent = content.classList.contains('hidden') ? '+' : '−';
            });
        });
    </script>


</body>

</html>