    <!--------------------------------- Banner ------------------------------->

<section class="relative">
    <img src="<?php echo $banner_image ?? 'images/default-banner.webp'; ?>" 
         class="absolute w-full h-full object-cover banner_image inset-0" 
         alt="">
    <div class="w-[80%] max-[1024px]:w-[90%] mx-auto py-16 relative">
        <div class="text-white">
            <div class="font-bold text-[2rem] leading-[1]">
                <?php echo $banner_title ?? 'Default Title'; ?>
            </div>
            <p><?php echo $banner_subtitle ?? ''; ?></p>
        </div>
    </div>
</section>