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
    $homeContent = $api->loadData('webcontent', 'home', []);
    
    if ($homeContent['success']) {
        // Use the data in your page
        $homeContentData = $homeContent['data']["data"];
        // print_r($homeContentData["cars"]);
    }
} catch (Exception $e) {
    echo "Error loading featured products: " . $e->getMessage();
}
?>

    <!--------------------------------- banner ------------------------------->

    <section class="relative w-full ">
        <div class="swiper mySwiper w-full h-full">
            <div class="swiper-wrapper">
                <?php foreach($homeContentData["highlight"] as $banner): ?>
                <div class="swiper-slide">
                    <picture>
                        <source fetchpriority="high" srcset="images/banner/mobile-slider-1.webp" type="image/webp"
                            media="(max-width: 768px)">
                        <source fetchpriority="high" srcset="<?php echo $banner["image_{$lang}_url"]; ?>" type="image/webp" media="(min-width: 769px)">
                        <img fetchpriority="high" src="<?php echo $banner["image_{$lang}_url"]; ?>" alt="Banner" class="w-full h-auto object-cover">
                    </picture>
                </div>
                <?php endforeach; ?>
            </div>

            <div class="swiper-button-next !text-white !right-6 after:!text-3xl max-[1024px]:hidden"></div>
            <div class="swiper-button-prev !text-white !left-6 after:!text-3xl max-[1024px]:hidden"></div>
        </div>
    </section>

    <!--------------------------------- filter ------------------------------->

    <section class="relative bg-[#f1f4f8] py-8 max-[1024px]:hidden">
        <div class="container mx-auto flex justify-between items-center">
            <ul class="flex gap-4 items-center">
                <li
                    class="px-4 py-2 -skew-x-12 bg-white text-black cursor-pointer border border-[#bbb] hover:text-white hover:bg-[#ff000d] duration-300 ">
                    Economy</li>
                <li
                    class="px-4 py-2 -skew-x-12 bg-white text-black cursor-pointer border border-[#bbb] hover:text-white hover:bg-[#ff000d] duration-300 ">
                    SUV</li>
                <li
                    class="px-4 py-2 -skew-x-12 bg-white text-black cursor-pointer border border-[#bbb] hover:text-white hover:bg-[#ff000d] duration-300 ">
                    Midsize</li>
                <li
                    class="px-4 py-2 -skew-x-12 bg-white text-black cursor-pointer border border-[#bbb] hover:text-white hover:bg-[#ff000d] duration-300 ">
                    Featured</li>
                <li
                    class="px-4 py-2 -skew-x-12 bg-white text-black cursor-pointer border border-[#bbb] hover:text-white hover:bg-[#ff000d] duration-300 ">
                    Crossover</li>
            </ul>
            <form action="">
                <div class="flex gap-6 items-center">
                    <input class="-skew-x-12 bg-white px-4 py-2 shadow-[1px_1px_2px_#999] text-[#939393]" type="date"
                        name="" id="">
                    <input class="-skew-x-12 bg-white px-4 py-2 shadow-[1px_1px_2px_#999] text-[#939393]" type="time"
                        name="" id="">
                    <button class="cursor-pointer bg-[#ff000d] text-white -skew-x-12 px-16 py-2">Search</button>
                </div>
            </form>
        </div>
    </section>

    <!--------------------------------- cars ------------------------------->
    

    <section class="relative bg-white py-16 max-[1024px]:py-10">
        <div class="container mx-auto">
            <div class="text-black font-semibold text-[2.5rem] max-[1024px]:text-[1.5rem] leading-[1] text-center w-[80%] max-[1024px]:w-[90%] mx-auto">
                Rent a Car in Dubai with Hala Drive - Affordable, Reliable & Hassle-Free Car Rentals</div>
            <div class="grid grid-cols-2 max-[1024px]:grid-cols-1 gap-8 mt-12 w-[80%] max-[1024px]:w-[90%] mx-auto">
                <?php foreach($homeContentData["cars"] as $car): ?>
                <div class="relative p-4 rounded-[10px] shadow-[4px_7px_15px_rgba(75,75,77,.25)]">
                    <div class="flex items-center justify-between mb-2">
                        <?php if ($car['stock'] === "Yes"): ?>
                            <div class="bg-[#daffda] text-[#29a71a] border border-[#29a71a] rounded-full text-[.8rem] px-2 py-1">
                            In Stock
                        </div>
                    <?php else: ?>
                        <div class="bg-[#ffdddd] text-[#d11a1a] border border-[#d11a1a] rounded-full text-[.8rem] px-2 py-1">
                        Out Stock
                    </div>
                    <?php endif; ?>
                    <img src="<?php echo $car["brand"]["logo_url"]; ?>" class="w-16" alt="">
                    </div>
                    <div class="flex max-[1024px]:flex-col gap-4">
                        <a href='cars/<?php echo $car["slug"]; ?>' class="w-[50%] max-[1024px]:w-full">
                            <img src="<?php echo $car["image_url"]; ?>" alt="<?php echo $car["name_{$lang}"]; ?>">
                            <div class="flex gap-2 max-[1024px]:justify-center items-center mt-3">
                                <div
                                    class="text-black bg-[#f2fdff] text-[10px] -skew-x-12 border-2 text-center border-[#d1eaee] cursor-pointer hover:text-white hover:bg-[#ff000d] duration-300 py-1 px-2">
                                    <div class="">Daily</div>
                                    <div class="font-bold"><?php echo $car["price_daily"]; ?></div>
                                </div>
                                <div
                                    class="text-black bg-[#f2fdff] text-[10px] -skew-x-12 border-2 text-center border-[#d1eaee] cursor-pointer hover:text-white hover:bg-[#ff000d] duration-300 py-1 px-2">
                                    <div class="">Weekly</div>
                                    <div class="font-bold"><?php echo $car["price_weekly"]; ?></div>
                                </div>
                                <div
                                    class="text-black bg-[#f2fdff] text-[10px] -skew-x-12 border-2 text-center border-[#d1eaee] cursor-pointer hover:text-white hover:bg-[#ff000d] duration-300 py-1 px-2">
                                    <div class="">Monthly</div>
                                    <div class="font-bold"><?php echo $car["price_monthly"]; ?></div>
                                </div>
                            </div>
                        </a>
                        <div class="w-[50%] max-[1024px]:w-full">
                            <div class="text-[1.5rem] font-bold leading-[1] max-[1024px]:text-center"><?php echo $car["name_{$lang}"]; ?>
                            </div>
                            <ul
                                class="list-disc text-[#939393] text-[11px] mt-4 max-[1024px]:mx-auto max-[1024px]:w-fit">
                                <li class="flex items-center gap-2 ">
                                    <img src="<?= $imagePath ?>cars/star.svg" class="w-3" alt="">
                                    <div class="">Engine <?php echo $car["engine"]; ?></div>
                                </li>
                                <li class="flex items-center gap-2 ">
                                    <img src="<?= $imagePath ?>cars/star.svg" class="w-3" alt="">
                                    <div class=""> Bluetooth <?php echo $car["bluetooth"]; ?></div>
                                </li>
                                <li class="flex items-center gap-2 ">
                                    <img src="<?= $imagePath ?>cars/star.svg" class="w-3" alt="">
                                    <div class="">Cruise Control <?php echo $car["cruise"]; ?></div>
                                </li>
                                <li class="flex items-center gap-2 ">
                                    <img src="<?= $imagePath ?>cars/star.svg" class="w-3" alt="">
                                    <div class="">Luggage <?php echo $car["luggage"]; ?></div>
                                </li>
                            </ul>
                            <div class="flex items-center max-[1024px]:justify-center gap-4 mt-4">
                                <div
                                    class="text-white bg-[#ff000d] px-2 py-1 cursor-pointer -skew-x-12 shadow-[10px_7px_20px_rgb(255,9,9,38%)] border-r border-b border-[#198754] text-[12px]">
                                    Send Inquiry</div>
                                <a target="_blank" href="https://wa.me/971501837112?text=Hi" class="bg-[#29a71a] px-4 py-1 -skew-x-12 cursor-pointer"><img
                                        src="images/icons/whatsapp.svg" class="w-5" alt=""></a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>


    <!--------------------------------- 1st content ------------------------------->

    <section>
        <div class="container mx-auto pb-16 max-[1024px]:py-10">
            <div
                class="grid grid-cols-2 max-[1024px]:grid-cols-1 items-center gap-10 w-[80%] max-[1024px]:w-[90%] mx-auto">
                <div class="">
                    <div class="text-[1.7rem] leading-[1] font-bold">Welcome to Hala Drive - Your Trusted Car Rental
                        Partner in Dubai</div>
                    <p class="text-[#7c7c7c] mt-4">A reliable rent a car Dubai service provides Dubai visitors endless
                        possibilities to maximize their experience. Hala Drive provides a complete collection of
                        vehicles that serve both business requirements and leisure activities. Customers can choose
                        between luxury car models of BMW, Mercedes, Audi and economical rental options to serve every
                        travel requirement. We deliver smooth and hassle-free journeys in Dubai through our competitive
                        car rental prices and exceptional service to customers.</p>
                </div>
                <div class="">
                    <p class="text-[#7c7c7c]">Car rental Dubai selection becomes difficult for many people to find the
                        right option. Our team dedicates itself to helping customers find their optimal vehicle while
                        obtaining maximum cost-effectiveness. Our company provides the ideal car rental solutions that
                        span from short-term bookings to long-term car lease Dubai services. Hala Drive offers you
                        exceptional car rental services throughout Dubai while enabling you to travel with convenience
                        and ease.</p>
                    <p class="text-[#7c7c7c] mt-4 font-bold">Our service allows customers to easily book cars with both
                        convenience and affordability. Through our platform users can access the most advantageous car
                        rental deals Dubai while enjoying peace of mind during their drive. You can experience complete
                        freedom and flexibility to explore both city landmarks and natural landscapes throughout the
                        city through Hala Drive's services.</p>
                </div>
            </div>
        </div>
    </section>

    <!--------------------------------- 2nd content ------------------------------->

    <section class="py-16 max-[1024px]:py-10 bg-[#f2fdff]">
        <div class="container mx-auto relative">
            <img src="<?= $imagePath ?>sections/arrow.webp"
                class="absolute w-fit left-0 right-0 mx-auto top-[53%] max-[1024px]:top-[53%] -translate-y-1/2" alt="">
            <div
                class="grid grid-cols-2 max-[1024px]:grid-cols-1 items-center gap-10 w-[80%] max-[1024px]:w-[90%] mx-auto">
                <div class="">
                    <div class="text-[1.7rem] leading-[1] font-bold">Why Choose Hala Drive for Your Car Rental Needs?
                    </div>
                    <p class="text-[#7c7c7c] mt-4">Hala Drive car rental delivers a smooth and trustworthy experience to
                        all passengers who select their services. Our company provides diverse car rental services in
                        Dubai that match different financial constraints and customer preferences. Our vehicle fleet
                        contains several options which range from compact cars to spacious SUVs along with luxury
                        vehicles to suit every customer need. Our Dubai-based online booking system provides simple
                        tools for customers to reserve their rental vehicles without difficulty. Through our car leasing
                        Dubai option, you can select rental plans that match your specific needs.</p>
                    <p class="text-[#7c7c7c] mt-4">Hala Drive provides affordable car rental Dubai prices that include
                        no hidden costs for customers. Hala Drive provides exclusive deals alongside promotional offers
                        that allow budget-conscious customers to acquire luxury vehicles and affordable rentals. Our
                        dedicated team of support agents provides customer satisfaction guarantees along with
                        hassle-free car hire Dubai services. Our fleet consists of vehicles that come with premium care
                        and boast both safety features and comfort and reliable performance while you are traveling. Our
                        car rental services in Dubai deliver both excellence and convenience to every resident and
                        tourist.</p>
                </div>
                <img src="<?= $imagePath ?>sections/about-a.webp" class="h-full w-full object-cover" alt="">
            </div>
            <div
                class="grid grid-cols-2 max-[1024px]:grid-cols-1 items-center gap-10 w-[80%] max-[1024px]:w-[90%] mx-auto pt-20">
                <img src="<?= $imagePath ?>sections/about-b.webp" class="h-full w-full object-cover" alt="">
                <div class="">
                    <div class="text-[1.7rem] leading-[1] font-bold">Find the Best Car Rental Deals in Dubai</div>
                    <p class="text-[#7c7c7c] mt-4">Our company Hala Drive understands that budget-minded travelers need
                        best car rental deals Dubai. Our company offers the most affordable rates for every vehicle we
                        provide so you can maximize your budget while driving. Our car hire Dubai services include
                        options for both short-term visitors and extended travelers. </p>
                    <p class="text-[#7c7c7c] mt-4">Customers can find clear costs on our website together with multiple
                        range of fleet that match varying budgets. Our vehicle selection includes both compact city cars
                        and luxurious sedans that serve all types of clients. The best rent a car deals in Dubai
                        combined with premium service are our commitment to offering customers affordable options. </p>
                    <p class="text-[#7c7c7c] mt-4">For those looking for long-term convenience, our car leasing Dubai
                        services provide flexible rental plans at competitive prices. Whether you need a vehicle for a
                        weekend getaway or an extended stay, we make it easy to rent a car Dubai without hidden charges,
                        ensuring a hassle-free experience.</p>
                </div>
            </div>
        </div>
    </section>

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
                    <img src="<?= $imagePath ?>icons/whatsapp.svg" alt="WhatsApp" class="w-8 h-8">
                </div>
            </div>
        </div>

        <!-- Accordion -->
        <div class="space-y-3">
            <?php foreach($homeContentData["faqs"] as $faqs): ?>
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

    <!--------------------------------- 3rd content ------------------------------->

    <section class="relative py-16 max-[1024px]:py-10 bg-[#f2fdff]">
        <div class="w-[80%] mx-auto max-[1024px]:w-[90%]">
            <div
                class="grid grid-cols-2 max-[1024px]:grid-cols-1 items-center gap-10 max-[1024px]:gap-4 max-[1024px]:mb-6">
                <img src="<?= $imagePath ?>sections/no1.webp" alt="">
                <div class="grid grid-cols-2 items-center gap-y-8 max-[1024px]:gap-x-4">
                    <div class="flex items-center gap-4">
                        <div class="bg-[#ff000d] w-8 h-8 flex items-center justify-center">
                            <img src="<?= $imagePath ?>sections/key2.svg" class="w-6" alt="">
                        </div>
                        <div class="font-bold max-[1024px]:leading-[1]">
                            Timely delivery
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="bg-[#ff000d] w-8 h-8 flex items-center justify-center">
                            <img src="<?= $imagePath ?>sections/calculator.svg" class="w-6" alt="">
                        </div>
                        <div class="font-bold max-[1024px]:leading-[1]">
                            Unbeatable Rates
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="bg-[#ff000d] w-8 h-8 flex items-center justify-center">
                            <img src="<?= $imagePath ?>sections/folder.svg" class="w-6" alt="">
                        </div>
                        <div class="font-bold max-[1024px]:leading-[1]">
                            Professional Staff
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="bg-[#ff000d] w-8 h-8 flex items-center justify-center">
                            <img src="<?= $imagePath ?>sections/calender.svg" class="w-6" alt="">
                        </div>
                        <div class="font-bold max-[1024px]:leading-[1]">
                            Flexible Return Policy
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="bg-[#ff000d] w-8 h-8 flex items-center justify-center">
                            <img src="<?= $imagePath ?>sections/tick.svg" class="w-6" alt="">
                        </div>
                        <div class="font-bold max-[1024px]:leading-[1]">
                            Online Reservation
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="bg-[#ff000d] w-8 h-8 flex items-center justify-center">
                            <img src="<?= $imagePath ?>sections/lock.svg" class="w-6" alt="">
                        </div>
                        <div class="font-bold max-[1024px]:leading-[1]">
                            Fully Insured
                        </div>
                    </div>
                </div>
            </div>
            <p class="text-[#939393] text-center">During your journey the covered insurance and damages provide you with
                both financial protection and assurance for your travels. Rental vehicle clients of our company benefit
                from adjustable rates that minimize costs whenever unexpected journey changes occur. Hala Drive provides
                an unmatched rental service by offering a complete range of economical cars such as such as KIA, Nissan
                and Chevrolet at remarkable prices along with exceptional customer care.</p>
        </div>
    </section>

    <!--------------------------------- blogs ------------------------------->

    <section class="py-16 max-[1024px]:py-10 relative">
        <div class="w-[80%] max-[1024px]:w-[90%] mx-auto">
            <div class="text-black text-[1.5rem] font-bold">Our Blogs</div>
            <div class="grid grid-cols-2 max-[1024px]:grid-cols-1 gap-4">
                <?php foreach($homeContentData["blogs"] as $blog): ?>
                <a href="blogs/<?php echo $blog["slug"]; ?>" class="relative">
                    <div class="bg-black/40 absolute inset-0 rounded-[10px]"></div>
                    <div class="bg-white text-black rounded-full py-1 px-3 absolute top-4 left-4 text-[10px]"><?php echo $blog["blog_schedule"]; ?>
                    </div>
                    <img src="<?php echo $blog["image_url"]; ?>" class="rounded-[10px]" alt="<?php echo $blog["img_alt_{$lang}"]; ?>">
                    <div class="text-white text-[1rem] absolute bottom-4 px-4"><?php echo $blog["title_{$lang}"]; ?>
                    </div>
                </a>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!--------------------------------- section 4 ------------------------------->

    <section class="bg-[#0b0b0b] relative">
        <div class="w-[80%] max-[1024px]:w-[90%] mx-auto py-16 max-[1024px]:py-10">
            <div class="text-white flex max-[1024px]:flex-col max-[1024px]:gap-6 gap-20 max-[1024px]:mb-10">
                <div class="text-[1.7rem] max-[1024px]:text-[1.3rem] max-[1024px]:leading-[1] font-bold">Rent a Car
                    Dubai Online Today and Enjoy These Perks</div>
                <div class="">Contact Us now for Updates and Promotions</div>
            </div>
            <div class="">
                <form action="" class="flex gap-4 max-[1024px]:flex-col">
                    <input class="focus:outline-none px-4 py-2" placeholder="Your Name" type="text">
                    <input class="focus:outline-none px-4 py-2" placeholder="Your Number" type="tel">
                    <input class="focus:outline-none px-4 py-2" placeholder="Your Email" type="email">
                    <button class="uppercase text-white bg-[#ff000d] px-4 py-2">Send Message</button>
                </form>
            </div>
            <div class="text-white mt-12">
                <p>at Hala Drive, our company offers excellent customer assistance at affordable rates. The team takes
                    pride in offering modern cars that meet all safety standards as well as equipped based on
                    state-of-the-art technologies like GPS navigation systems, rearview cameras and automatic
                    transmissions that help make driving safer, smoother and more enjoyable than ever. You can be sure
                    that no matter which model of the car you choose from us, it will be serviced regularly and
                    maintained in peak condition before being sent off with our customers. You will end up getting the
                    best car leasing Dubai experience.</p>
                <p class="mt-4">In addition, we also provide additional benefits like 24/7 roadside assistance services
                    in case of any emergencies during using our cars. So no matter where your journey takes you to the
                    city, you can feel secure knowing that Our Best car rental UAE service has your back when needed.
                    And for those who want their car delivered directly to their doorstep on any day, we even offer
                    pickup services.</p>
            </div>
        </div>
    </section>

    <!--------------------------------- section 5 ------------------------------->

    <section class="py-16 max-[1024px]:py-10">
        <div class="w-[80%] max-[1024px]:w-[90%] mx-auto">
            <div class="grid grid-cols-2 max-[1024px]:grid-cols-1 max-[1024px]:gap-6 gap-10 items-center">
                <div class="text-black font-bold">
                    <div class="text-[3rem] max-[1024px]:text-[2rem]">Join Over</div>
                    <div class="text-[3rem] max-[1024px]:text-[2rem] text-center max-[1024px]:text-left leading-[1]">
                        2000+ Satisfied Clients</div>
                    <div class="text-[1.5rem] leading-[1] mt-4">Experience the Quality and Excellence of Our Services
                    </div>
                </div>
                <div class="">
                    <img src="<?= $imagePath ?>sections/hero-img.webp" alt="">
                </div>
            </div>
            <div class="grid grid-cols-3 max-[1024px]:grid-cols-1 max-[1024px]:gap-6 gap-10">
                <div class="">
                    <div class="text-black font-bold text-[1.7rem] mb-2">Junnalyn Sali</div>
                    <p class="text-[#939393]">The service at Hala Drive rent a car satisfied me completely during my
                        time there. You can reach their service through a phone call so I strongly suggest choosing
                        them. Everything about the rental experience was positive because the vehicle remained clean
                        while the personnel treated me well and the service avoided hidden fees.</p>
                </div>
                <div class="">
                    <div class="text-black font-bold text-[1.7rem] mb-2">Sumodo Hasan</div>
                    <p class="text-[#939393]">Dubai residents should choose this company as their preferred rental car
                        provider. I have relied on their service more than once and I am consistently satisfied with
                        their standard of service. The rental staff maintains high professionalism while offering cars
                        which remain in perfect condition. Highly recommended!</p>
                </div>
                <div class="">
                    <div class="text-black font-bold text-[1.7rem] mb-2">Roshan Smith</div>
                    <p class="text-[#939393]">I found the vehicle outstanding because it combined superb fuel economy
                        and pristine cleanliness. The team provided clear communication while the service locations were
                        easy to reach. I sincerely appreciate the services provided by Hala Drive car rental services.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!--------------------------------- footer ------------------------------->

    <section class="bg-[#f1f4f8] py-16 max-[1024px]:py-10">
        <div class="w-[80%] max-[1024px]:w-[90%] mx-auto text-center text-[#939393]">
            <div class="text-black font-bold text-[1.7rem] leading-[1] mb-10">Experience Convenience, Comfort, and
                Cost-Effective Car Rentals</div>
            <p>Customers can experience a smooth car rental UAE journey through Hala Drive. Our company offers the ideal
                vehicles at competitive prices for both urban commuters and business professionals. The experienced
                staff member at our company stand ready to assist clients in deciding which vehicle best fits as per
                your requirements.</p>
            <p class="mt-6">Our car lease Dubai option lets customers access their desired vehicles without the
                ownership costs. Different financing options and special discounts enable you to drive your prestigious
                vehicle choice with freedom from traditional ownership terms.</p>
            <p class="mt-6">The company maintains its dedication to safety and reliability which enables you to feel
                completely at ease while driving. The vehicles we offer receive routine maintenance checks alongside
                inspections to guarantee their top condition for their customer. The car rental services at Hala Drive
                guarantee you the highest standard of service throughout Dubai.</p>
            <p class="mt-6">Hala Drive offers car hire UAE services that extend far beyond basic vehicle rentals. Hala
                Drive dedicates itself to delivering superior customer assistance together with premium maintenance
                services in addition to special rental packages which boost your travel comfort. Our Dubai-based service
                locations provide convenient accessibility to our customers throughout the city.</p>
            <p class="mt-6">Whether you’re visiting Dubai for business, leisure, or an extended stay, Hala Drive has the
                perfect car rental Dubai cheap option for you. Book your vehicle today and enjoy a stress-free journey
                with the best Hala drive car rental services available!</p>
        </div>
    </section>

    <!--------------------------------- brands logo ------------------------------->

    <section>
        <div class="slider-wrap w-[80%] max-[1024px]:w-[90%] mx-auto py-16 max-[1024px]:py-10">
            <div class="swiper mySwiper1">
                <div class="swiper-wrapper">
                    <?php foreach($homeContentData["brands"] as $id => $logo): ?>
                    <?php if (!empty($logo)): // skip null images ?>
                        <div class="swiper-slide">
                            <img 
                                src="<?= $logo["logo_url"] ?>" 
                                class="mx-auto h-[60px] object-contain" 
                                alt="Brand <?= $id ?>" 
                            >
                        </div>
                    <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>

    <!--------------------------------- Features On ------------------------------->

    <section>
        <div class="w-[80%] max-[1024px]:w-[90%] mx-auto py-8 ">
            <div class="text-center text-black font-bold text-[1.7rem] leading-[1] mb-10">Features On</div>
            <div class="flex max-[1024px]:flex-wrap  justify-center gap-6">
                <img class="object-contain w-[10rem] " src="images/sections/features/gulfnews.webp" alt="">
                <img class="object-contain w-[10rem] " src="images/sections/features/gulfstory.webp" alt="">
                <img class="object-contain w-[10rem] " src="images/sections/features/hiuae.webp" alt="">
                <img class="object-contain w-[10rem] " src="images/sections/features/khaleejtime.webp" alt="">
                <img class="object-contain w-[10rem] " src="images/sections/features/localuae.webp" alt="">
            </div>
        </div>
    </section>

<?php include_once('footer.php');?>
