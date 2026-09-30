<?php
require_once 'global.php';

$meta_title = $lang === 'ar' ? 'تم الدفع بنجاح | هلا درايف' : 'Payment Successful | Hala Drive';
$meta_desc = $lang === 'ar'
    ? 'تم استلام دفعتك بنجاح. سيتواصل معك فريق هلا درايف لتأكيد الحجز.'
    : 'Your payment was completed successfully. Hala Drive will contact you to confirm your booking.';
$siteRoot = (($_SERVER['HTTP_HOST'] ?? '') === 'localhost') ? rtrim((string) ($basePath ?? ''), '/') : '';

require_once 'header.php';
?>

<main class="relative overflow-hidden bg-white">
    <section class="relative min-h-[620px] flex items-center py-20 max-[1024px]:py-14">
        <div class="absolute -top-32 -right-24 h-[28rem] w-[28rem] rounded-full bg-[#fff1f2] blur-3xl" aria-hidden="true"></div>
        <div class="absolute -bottom-48 -left-24 h-[24rem] w-[24rem] rounded-full bg-[#f1f4f8] blur-3xl" aria-hidden="true"></div>

        <div class="relative z-10 w-[80%] max-[1024px]:w-[90%] mx-auto">
            <div class="mx-auto max-w-3xl text-center">
                <div class="mx-auto mb-8 flex h-24 w-24 items-center justify-center rounded-full bg-[#fff1f2] text-[#b8101f] shadow-[0_16px_40px_rgba(184,16,31,.16)]">
                    <svg width="46" height="46" viewBox="0 0 48 48" fill="none" aria-hidden="true">
                        <circle cx="24" cy="24" r="19" stroke="currentColor" stroke-width="3"/>
                        <path d="m14.5 24.5 6.1 6.1L34 17.2" stroke="currentColor" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>

                <?php if ($lang === 'ar'): ?>
                    <p class="mb-4 text-sm font-bold uppercase tracking-[.25em] text-[#b8101f]">هلا درايف</p>
                    <h1 class="syne text-4xl font-bold leading-tight text-black max-[640px]:text-3xl">تم الدفع بنجاح</h1>
                    <p class="mx-auto mt-5 max-w-2xl text-base leading-8 text-[#666]">شكراً لاختيارك هلا درايف. تم استلام دفعتك بنجاح، وسيتواصل معك فريقنا قريباً لتأكيد تفاصيل حجز السيارة.</p>
                <?php else: ?>
                    <p class="mb-4 text-sm font-bold uppercase tracking-[.25em] text-[#b8101f]">Hala Drive</p>
                    <h1 class="syne text-4xl font-bold leading-tight text-black max-[640px]:text-3xl">Payment successful</h1>
                    <p class="mx-auto mt-5 max-w-2xl text-base leading-8 text-[#666]">Thank you for choosing Hala Drive. Your payment has been received successfully, and our team will contact you shortly to confirm your car booking.</p>
                <?php endif; ?>

                <div class="mx-auto mt-10 grid max-w-2xl grid-cols-3 gap-4 max-[640px]:grid-cols-1">
                    <?php
                    $steps = $lang === 'ar'
                        ? [['01', 'تم استلام الدفع'], ['02', 'مراجعة الحجز'], ['03', 'تأكيد الرحلة']]
                        : [['01', 'Payment received'], ['02', 'Booking review'], ['03', 'Trip confirmation']];
                    foreach ($steps as [$number, $label]):
                    ?>
                        <div class="rounded-[6px] border border-[#e9e9e9] bg-white px-4 py-5 text-center shadow-[0_8px_24px_rgba(0,0,0,.04)]">
                            <div class="syne text-xl font-bold text-[#b8101f]"><?= htmlspecialchars($number, ENT_QUOTES, 'UTF-8') ?></div>
                            <div class="mt-2 text-xs font-bold text-[#555]"><?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="mt-10 flex justify-center gap-4 max-[640px]:flex-col max-[640px]:items-stretch">
                    <a href="cars" class="rounded-[5px] bg-[#b8101f] px-7 py-3 text-center text-sm font-bold uppercase tracking-wide text-white transition hover:bg-[#8e0d18]">
                        <?= $lang === 'ar' ? 'استعرض السيارات' : 'Browse cars' ?>
                    </a>
                    <a href="<?= htmlspecialchars($siteRoot . '/', ENT_QUOTES, 'UTF-8') ?>" class="rounded-[5px] border border-[#b8101f] px-7 py-3 text-center text-sm font-bold uppercase tracking-wide text-[#b8101f] transition hover:bg-[#fff1f2]">
                        <?= $lang === 'ar' ? 'العودة للرئيسية' : 'Back to home' ?>
                    </a>
                </div>

                <p class="mt-8 text-xs text-[#888]">
                    <?= $lang === 'ar' ? 'تحتاج إلى مساعدة؟ تواصل معنا عبر واتساب.' : 'Need help? Contact us on WhatsApp.' ?>
                    <a class="font-bold text-[#b8101f]" href="https://wa.me/971501837112?text=Hi" target="_blank" rel="noopener">+971 50 183 7112</a>
                </p>
            </div>
        </div>
    </section>
</main>

<?php include_once 'footer.php'; ?>
