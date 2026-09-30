<?php
require_once 'global.php';

$isArabic = $lang === 'ar';
$meta_title = $isArabic ? 'تم الدفع بنجاح | هلا درايف' : 'Payment Successful | Hala Drive';
$meta_desc = $isArabic
    ? 'تم استلام دفعتك بنجاح. سيتواصل معك فريق هلا درايف لتأكيد الحجز.'
    : 'Your payment was completed successfully. Hala Drive will contact you to confirm your booking.';
$siteRoot = (($_SERVER['HTTP_HOST'] ?? '') === 'localhost') ? rtrim((string) ($basePath ?? ''), '/') : '';

require_once 'header.php';
?>

<style>
    .hd-thank-page { background: #f5f6f8; padding: 58px 20px 78px; position: relative; overflow: hidden; }
    .hd-thank-page::before { background: #e02d3c; border-radius: 50%; content: ""; height: 430px; opacity: .08; position: absolute; right: -170px; top: -180px; width: 430px; }
    .hd-thank-page::after { background: #111; border-radius: 50%; bottom: -260px; content: ""; height: 520px; opacity: .06; position: absolute; left: -220px; width: 520px; }
    .hd-thank-card { background: #fff; border-radius: 18px; box-shadow: 0 24px 70px rgba(17,17,17,.12); display: grid; grid-template-columns: minmax(250px, .8fr) minmax(0, 1.5fr); margin: 0 auto; max-width: 1050px; min-height: 480px; overflow: hidden; position: relative; z-index: 1; }
    .hd-thank-aside { background: linear-gradient(150deg, #0a0a0a 0%, #181818 55%, #8d0e19 100%); color: #fff; display: flex; flex-direction: column; justify-content: space-between; padding: 42px 36px; position: relative; }
    .hd-thank-aside::after { border: 1px solid rgba(255,255,255,.14); border-radius: 50%; bottom: -92px; content: ""; height: 260px; position: absolute; right: -74px; width: 260px; }
    .hd-thank-brand { color: #fff; font-family: Syne, sans-serif; font-size: 25px; font-weight: 700; letter-spacing: -.04em; }
    .hd-thank-brand span { color: #e02d3c; }
    .hd-thank-mark { align-items: center; background: #e02d3c; border: 8px solid rgba(255,255,255,.12); border-radius: 50%; display: flex; height: 112px; justify-content: center; margin-top: 48px; width: 112px; }
    .hd-thank-aside-title { font-family: Syne, sans-serif; font-size: 29px; font-weight: 700; line-height: 1.15; margin-top: 24px; max-width: 200px; }
    .hd-thank-aside-copy { color: rgba(255,255,255,.68); font-size: 13px; line-height: 1.7; margin-top: 14px; max-width: 230px; }
    .hd-thank-ref { color: rgba(255,255,255,.55); font-size: 11px; letter-spacing: .08em; text-transform: uppercase; }
    .hd-thank-content { padding: 58px 68px 50px; }
    .hd-thank-eyebrow { color: #b8101f; font-size: 12px; font-weight: 700; letter-spacing: .22em; margin-bottom: 13px; text-transform: uppercase; }
    .hd-thank-title { color: #111; font-family: Syne, sans-serif; font-size: clamp(31px, 4vw, 48px); font-weight: 700; letter-spacing: -.045em; line-height: 1.05; margin: 0; }
    .hd-thank-description { color: #6b6b6b; font-size: 15px; line-height: 1.85; margin: 20px 0 0; max-width: 620px; }
    .hd-thank-steps { border-top: 1px solid #ececef; display: grid; gap: 24px; grid-template-columns: repeat(3, 1fr); margin-top: 42px; padding-top: 27px; }
    .hd-thank-step { position: relative; }
    .hd-thank-step:not(:last-child)::after { background: #dedee2; content: ""; height: 1px; position: absolute; right: -13px; top: 15px; width: 20px; }
    .hd-thank-step-number { align-items: center; background: #fff1f2; border-radius: 50%; color: #b8101f; display: flex; font-family: Syne, sans-serif; font-size: 12px; font-weight: 700; height: 31px; justify-content: center; width: 31px; }
    .hd-thank-step-label { color: #333; font-size: 12px; font-weight: 700; line-height: 1.4; margin-top: 12px; }
    .hd-thank-actions { display: flex; flex-wrap: wrap; gap: 12px; margin-top: 42px; }
    .hd-thank-button { border: 1px solid #b8101f; border-radius: 5px; display: inline-flex; font-size: 12px; font-weight: 700; justify-content: center; letter-spacing: .06em; padding: 15px 22px; text-transform: uppercase; transition: .2s ease; }
    .hd-thank-button-primary { background: #b8101f; color: #fff; }
    .hd-thank-button-primary:hover { background: #8e0d18; }
    .hd-thank-button-secondary { color: #b8101f; }
    .hd-thank-button-secondary:hover { background: #fff1f2; }
    .hd-thank-help { color: #888; font-size: 12px; line-height: 1.7; margin-top: 30px; }
    .hd-thank-help a { color: #b8101f; font-weight: 700; }
    [dir="rtl"] .hd-thank-content, [dir="rtl"] .hd-thank-aside { text-align: right; }
    [dir="rtl"] .hd-thank-step:not(:last-child)::after { left: -13px; right: auto; }
    @media (max-width: 760px) {
        .hd-thank-page { padding: 28px 14px 48px; }
        .hd-thank-card { display: block; min-height: 0; }
        .hd-thank-aside { min-height: 270px; padding: 28px 26px; }
        .hd-thank-mark { height: 76px; margin-top: 28px; width: 76px; }
        .hd-thank-mark svg { height: 34px; width: 34px; }
        .hd-thank-aside-title { font-size: 23px; margin-top: 16px; }
        .hd-thank-aside-copy { display: none; }
        .hd-thank-ref { margin-top: 25px; }
        .hd-thank-content { padding: 34px 25px 32px; }
        .hd-thank-description { font-size: 14px; line-height: 1.75; }
        .hd-thank-steps { gap: 18px; margin-top: 30px; padding-top: 22px; }
        .hd-thank-step:not(:last-child)::after { right: -12px; width: 16px; }
        [dir="rtl"] .hd-thank-step:not(:last-child)::after { left: -12px; }
        .hd-thank-actions { flex-direction: column; margin-top: 30px; }
        .hd-thank-button { width: 100%; }
    }
    @media (max-width: 420px) {
        .hd-thank-steps { gap: 10px; }
        .hd-thank-step-label { font-size: 10px; }
    }
</style>

<main class="hd-thank-page">
    <section class="hd-thank-card" aria-labelledby="thank-you-title">
        <div class="hd-thank-aside">
            <div>
                <div class="hd-thank-brand">Hala <span>Drive</span></div>
                <div class="hd-thank-mark" aria-hidden="true">
                    <svg width="48" height="48" viewBox="0 0 48 48" fill="none">
                        <circle cx="24" cy="24" r="20" stroke="white" stroke-width="3"/>
                        <path d="m14 24.5 6.2 6.2L34.5 16.8" stroke="white" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <div class="hd-thank-aside-title"><?= $isArabic ? 'رحلتك تبدأ الآن' : 'Your journey starts now' ?></div>
                <p class="hd-thank-aside-copy"><?= $isArabic ? 'شكراً لثقتك بنا. نحن نجهز كل شيء لرحلة سلسة.' : 'Thank you for trusting us. We are getting everything ready for a smooth journey.' ?></p>
            </div>
            <div class="hd-thank-ref"><?= $isArabic ? 'تأكيد الدفع مكتمل' : 'Payment confirmation complete' ?></div>
        </div>

        <div class="hd-thank-content">
            <div class="hd-thank-eyebrow"><?= $isArabic ? 'تم الدفع بنجاح' : 'Booking confirmed' ?></div>
            <h1 class="hd-thank-title" id="thank-you-title"><?= $isArabic ? 'شكراً لاختيارك هلا درايف' : 'Payment successful' ?></h1>
            <p class="hd-thank-description"><?= $isArabic ? 'تم استلام دفعتك بنجاح. سيتواصل معك فريق هلا درايف قريباً لتأكيد تفاصيل حجز السيارة.' : 'Your payment has been received successfully. Our Hala Drive team will contact you shortly to confirm your car booking details.' ?></p>

            <div class="hd-thank-steps">
                <?php
                $steps = $isArabic
                    ? [['01', 'تم استلام الدفع'], ['02', 'مراجعة الحجز'], ['03', 'تأكيد الرحلة']]
                    : [['01', 'Payment received'], ['02', 'Booking review'], ['03', 'Trip confirmation']];
                foreach ($steps as [$number, $label]):
                ?>
                    <div class="hd-thank-step">
                        <div class="hd-thank-step-number"><?= htmlspecialchars($number, ENT_QUOTES, 'UTF-8') ?></div>
                        <div class="hd-thank-step-label"><?= htmlspecialchars($label, ENT_QUOTES, 'UTF-8') ?></div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="hd-thank-actions">
                <a class="hd-thank-button hd-thank-button-primary" href="cars"><?= $isArabic ? 'استعرض السيارات' : 'Browse cars' ?></a>
                <a class="hd-thank-button hd-thank-button-secondary" href="<?= htmlspecialchars($siteRoot . '/', ENT_QUOTES, 'UTF-8') ?>"><?= $isArabic ? 'العودة للرئيسية' : 'Back to home' ?></a>
            </div>

        </div>
    </section>
</main>

<?php include_once 'footer.php'; ?>
