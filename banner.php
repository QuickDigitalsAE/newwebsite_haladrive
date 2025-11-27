    <!--------------------------------- Banner ------------------------------->
<?php 
    $tag = $heading ?? 'div'; 
?>

<section class="relative">
    <img 
        src="<?php echo $banner_image ?? 'images/default-banner.webp'; ?>" 
        class="absolute w-full h-full banner_image bottom-0 left-0 transition-all duration-700" 
        alt="Banner"
    >
    <div class="w-[80%] max-[1024px]:w-[90%] mx-auto py-[9rem] max-[1024px]:py-[5rem] relative">
        <div class="text-[#E02D3C]">
            <<?= $tag ?> class="font-bold text-[3rem] max-[1024px]:text-[2rem] leading-[1]">
                <?= $banner_title ?? 'Default Title' ?>
            </<?= $tag ?>>
            <p class='text-[#333333] w-[35%] max-[1024px]:w-full'><?php echo $banner_subtitle ?? ''; ?></p>
        </div>
    </div>
</section>