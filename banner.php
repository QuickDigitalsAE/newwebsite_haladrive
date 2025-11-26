    <!--------------------------------- Banner ------------------------------->
<?php 
    $tag = $heading ?? 'div'; 
?>

<section class="relative">
    <img src="<?php echo $banner_image ?? 'images/default-banner.webp'; ?>" class="absolute w-full h-full object-cover banner_image inset-0" alt="">
    <div class="w-[80%] max-[1024px]:w-[90%] mx-auto py-16 relative">
        <div class="text-white">
            <<?= $tag ?> class="font-bold text-[2rem] leading-[1]">
                <?= $banner_title ?? 'Default Title' ?>
            </<?= $tag ?>>
            <p><?php echo $banner_subtitle ?? ''; ?></p>
        </div>
    </div>
</section>