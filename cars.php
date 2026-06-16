<?php
// Include global
require_once 'global.php';

// Default SEO fallback values
$meta_title = '';
$meta_desc  = '';
$car = [];
$carName = '';
$dailyPrice = 0.0;
$weeklyPrice = 0.0;
$monthlyPrice = 0.0;
$isInStock = false;
$deliveryLocations = [];
$returnLocations = [];
$branchLocations = [];
$locationsEndpoint = '';
$fullInsuranceAmount = 40.0;
$additionalDriverAmount = 100.0;
$babySeatAmount = 40.0;
$depositAmount = 1500.0;
$waiverAmount = 30.0;
$apiEndpoints = include __DIR__ . '/apis/api_endpoints.php';
$promoApiBaseUrl = rtrim((string) ($apiEndpoints['promo_base_url'] ?? preg_replace('#/v1/?$#', '', (string) ($apiEndpoints['base_url'] ?? 'https://admin.haladrive.ae/api/v1'))), '/');
$promoApplyEndpoint = $promoApiBaseUrl . (string) ($apiEndpoints['promo_codes']['apply'] ?? '/promo-codes/apply');
$bookingSubmitEndpoint = $promoApiBaseUrl . (string) ($apiEndpoints['website']['bookings'] ?? '/website/bookings');
$initialPaymentFlow = ($_GET['payment_flow'] ?? '') === 'later' ? 'pay_later' : 'pay_now';

if (!function_exists('normalizeHdLocations')) {
    function normalizeHdLocations($locations): array
    {
        if (!is_array($locations)) {
            return [];
        }

        $normalized = [];

        foreach ($locations as $location) {
            if (is_object($location)) {
                $location = (array) $location;
            }

            if (!is_array($location)) {
                if (is_string($location) && trim($location) !== '') {
                    $normalized[] = [
                        'value' => trim($location),
                        'label' => trim($location),
                        'price' => 0,
                    ];
                }
                continue;
            }

            $rawLabel = '';
            foreach (["name_{$GLOBALS['lang']}", 'name_en', 'name', 'title', 'location', 'address', 'label'] as $key) {
                if (!empty($location[$key]) && is_scalar($location[$key])) {
                    $rawLabel = trim((string) $location[$key]);
                    break;
                }
            }

            if ($rawLabel === '') {
                continue;
            }

            $rawValue = '';
            foreach (['slug', 'code', 'id', 'value'] as $key) {
                if (isset($location[$key]) && is_scalar($location[$key]) && trim((string) $location[$key]) !== '') {
                    $rawValue = trim((string) $location[$key]);
                    break;
                }
            }

            if ($rawValue === '') {
                $rawValue = $rawLabel;
            }

            $price = 0;
            foreach (['price', 'amount', 'fee', 'charges', 'delivery_fee', 'return_fee'] as $key) {
                if (isset($location[$key]) && $location[$key] !== '') {
                    $price = (float) preg_replace('/[^\d.]/', '', (string) $location[$key]);
                    break;
                }
            }

            $normalized[] = [
                'value' => $rawValue,
                'label' => $rawLabel,
                'price' => $price,
            ];
        }

        return $normalized;
    }
}

if (!function_exists('normalizeHdAmount')) {
    function normalizeHdAmount($value, float $default): float
    {
        if ($value === null || $value === '') {
            return $default;
        }

        $amount = (float) preg_replace('/[^\d.]/', '', (string) $value);
        return $amount > 0 ? $amount : $default;
    }
}


if (!function_exists('fetchHdSpeedLocations')) {
    function fetchHdSpeedLocations(string $endpoint): array
    {
        $endpoint = trim($endpoint);
        if ($endpoint === '') {
            return [];
        }

        $response = null;

        if (function_exists('curl_init')) {
            $ch = curl_init($endpoint);
            curl_setopt_array($ch, [
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => 10,
                CURLOPT_CONNECTTIMEOUT => 5,
                CURLOPT_HTTPHEADER => ['Accept: application/json'],
                CURLOPT_PROXY => '',
                CURLOPT_NOPROXY => '*',
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => 0,
            ]);
            $response = curl_exec($ch);
            curl_close($ch);
        } else {
            $context = stream_context_create([
                'http' => [
                    'method' => 'GET',
                    'timeout' => 10,
                    'header' => "Accept: application/json\r\n",
                    'proxy' => null,
                ],
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                ],
            ]);
            $response = @file_get_contents($endpoint, false, $context);
        }

        if (!is_string($response) || trim($response) === '') {
            return [];
        }

        $decoded = json_decode($response, true);
        if (!is_array($decoded) || empty($decoded['success']) || !isset($decoded['result']) || !is_array($decoded['result'])) {
            return [];
        }

        return $decoded['result'];
    }
}

if (!function_exists('normalizeHdBranchLocations')) {
    function normalizeHdBranchLocations($locations): array
    {
        if (!is_array($locations)) {
            return [];
        }

        $normalized = [];

        foreach ($locations as $location) {
            if (is_object($location)) {
                $location = (array) $location;
            }

            if (!is_array($location)) {
                continue;
            }

            $label = trim((string) ($location['name'] ?? $location['title'] ?? $location['location'] ?? $location['address'] ?? ''));
            if ($label === '') {
                continue;
            }

            $value = trim((string) ($location['code'] ?? $location['id'] ?? preg_replace('/[^a-z0-9]+/i', '_', strtolower($label))));
            if ($value === '') {
                $value = preg_replace('/[^a-z0-9]+/i', '_', strtolower($label));
            }

            $normalized[] = [
                'id' => $location['id'] ?? null,
                'tenantId' => $location['tenantId'] ?? null,
                'value' => $value,
                'code' => trim((string) ($location['code'] ?? $value)),
                'label' => $label,
                'address' => trim((string) ($location['address'] ?? $label)),
                'map' => 'https://maps.google.com/?q=' . rawurlencode($label),
            ];
        }

        return $normalized;
    }
}

$locationsEndpoint = rtrim((string) ($promoApiBaseUrl), '/') . '/speed/getLocations';

$slug = $_GET['slug'] ?? null;

if ($slug) {

    try {
        $carSingleContent = $api->loadData('car', 'single', [], $slug);

        if (!empty($carSingleContent['success']) && !empty($carSingleContent['data']['data'])) {
            $carSingleContentData = $carSingleContent['data']["data"];
            $car = $carSingleContentData['car'] ?? [];
            $carName = (string) ($car["name_{$lang}"] ?? '');

            $titleKey = "title_" . $lang;
            $descKey  = "description_" . $lang;

            $meta_title = $carSingleContentData["meta_data"][$titleKey] ?? '';
            $meta_desc  = $carSingleContentData["meta_data"][$descKey] ?? '';

            $dailyPrice = (float) preg_replace('/[^\d.]/', '', (string) ($car['price_daily'] ?? 0));
            $weeklyPrice = (float) preg_replace('/[^\d.]/', '', (string) ($car['price_weekly'] ?? 0));
            $monthlyPrice = (float) preg_replace('/[^\d.]/', '', (string) ($car['price_monthly'] ?? 0));
            $isInStock = !empty($car['stock']) && strtolower((string) $car['stock']) === 'yes';
            $deliveryLocations = $carSingleContentData['delivery_locations'] ?? [];
            $returnLocations = $carSingleContentData['return_locations'] ?? [];
            $fullInsuranceAmount = normalizeHdAmount($car['full_insurance_amount'] ?? $carSingleContentData['full_insurance_amount'] ?? null, $fullInsuranceAmount);
            $additionalDriverAmount = normalizeHdAmount($car['additional_driver_amount'] ?? $carSingleContentData['additional_driver_amount'] ?? null, $additionalDriverAmount);
            $babySeatAmount = normalizeHdAmount($car['baby_seat_amount'] ?? $carSingleContentData['baby_seat_amount'] ?? null, $babySeatAmount);
            $depositAmount = normalizeHdAmount($car['deposit_amount'] ?? $carSingleContentData['deposit_amount'] ?? null, $depositAmount);
            $waiverAmount = normalizeHdAmount($car['waiver_amount'] ?? $carSingleContentData['waiver_amount'] ?? null, $waiverAmount);
            $vehicleGroupId = $car['vehicle_group_id'] ?? $carSingleContentData['vehicle_group_id'] ?? null;
            $tariffGroupId = $car['tariff_group_id'] ?? $carSingleContentData['tariff_group_id'] ?? null;
        }
    } catch (Exception $e) {
        echo "Error loading car details: " . $e->getMessage();
    }

    include_once('header.php');

    $banner_image = "$imagePath/about/top-banner.webp";
    $banner_title = $messages['cars'];
    $banner_subtitle = $messages['aboutBannerPera'];
    include_once('banner.php');
    ?>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer">

    <link rel="stylesheet" href="booking-form-style.css">


    <section class="relative py-16 max-[1024px]:py-10">
        <div class="w-[80%] max-[1024px]:w-[90%] mx-auto">
            <div class="grid grid-cols-3 max-[1024px]:grid-cols-1 gap-10">
                <div class="col-span-2 max-[1024px]:col-span-1 car-detail-main">
                    <div class="car-panel car-panel--hero">
                        <div class="car-hero-head">
                            <div>
                                <div class="car-hero-title syne"><?php echo $carSingleContentData["car"]["name_{$lang}"]; ?></div>
                            </div>
                        </div>
                        <div class="car-hero-image">
                            <img src="<?php echo $carSingleContentData["car"]["image_url"]; ?>" alt="Cars">
                        </div>
                    </div>

                    <div class="car-panel">
                        <div class="car-section-head">
                            <div class="car-section-title"><?= $messages['carfeatures'] ?></div>
                            <div class="car-section-note">Overview</div>
                        </div>
                        <div class="car-spec-grid">
                            <div class="car-spec-card">
                                <div class="car-spec-content">
                                    <span class="car-spec-icon"><i class="fa-solid fa-gauge-high"></i></span>
                                    <div>
                                        <div class="car-spec-label"><?= $messages['engine'] ?></div>
                                        <div class="car-spec-value"><?php echo $carSingleContentData["car"]["engine"]; ?></div>
                                    </div>
                                </div>
                            </div>
                            <div class="car-spec-card">
                                <div class="car-spec-content">
                                    <span class="car-spec-icon"><i class="fa-brands fa-bluetooth-b"></i></span>
                                    <div>
                                        <div class="car-spec-label"><?= $messages['bluetooth'] ?></div>
                                        <div class="car-spec-value"><?php echo $carSingleContentData["car"]["bluetooth"]; ?></div>
                                    </div>
                                </div>
                            </div>
                            <div class="car-spec-card">
                                <div class="car-spec-content">
                                    <span class="car-spec-icon"><i class="fa-solid fa-sliders"></i></span>
                                    <div>
                                        <div class="car-spec-label"><?= $messages['control'] ?></div>
                                        <div class="car-spec-value"><?php echo $carSingleContentData["car"]["cruise"]; ?></div>
                                    </div>
                                </div>
                            </div>
                            <div class="car-spec-card">
                                <div class="car-spec-content">
                                    <span class="car-spec-icon"><i class="fa-solid fa-suitcase-rolling"></i></span>
                                    <div>
                                        <div class="car-spec-label"><?= $messages['luggage'] ?></div>
                                        <div class="car-spec-value"><?php echo $carSingleContentData["car"]["luggage"]; ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="car-panel">
                        <div class="car-section-head">
                            <div class="car-section-title">Details</div>
                            <div class="car-section-note">Rental info</div>
                        </div>
                        <div class="car-detail-grid">
                            <div class="car-detail-row">
                                <div class="car-detail-maininfo">
                                    <span class="car-detail-icon"><i class="fa-solid fa-circle-check"></i></span>
                                    <div class="car-detail-label"><?= $messages['availability'] ?></div>
                                </div>
                                <div class="car-detail-value"><?= $messages['instock'] ?></div>
                            </div>
                            <div class="car-detail-row">
                                <div class="car-detail-maininfo">
                                    <span class="car-detail-icon"><i class="fa-solid fa-shield-halved"></i></span>
                                    <div class="car-detail-label"><?= $messages['securityamount'] ?></div>
                                </div>
                                <div class="car-detail-value"><span class="hd-price-inline"><img src="<?= $imagePath ?>darham.png" class="hd-price-inline__icon" alt="AED"><span><?= number_format($depositAmount, 2); ?></span></span></div>
                            </div>
                            <div class="car-detail-row">
                                <div class="car-detail-maininfo">
                                    <span class="car-detail-icon"><i class="fa-regular fa-credit-card"></i></span>
                                    <div class="car-detail-label"><?= $messages['securitytype'] ?></div>
                                </div>
                                <div class="car-detail-value">Card only</div>
                            </div>
                            <div class="car-detail-row">
                                <div class="car-detail-maininfo">
                                    <span class="car-detail-icon"><i class="fa-solid fa-wallet"></i></span>
                                    <div class="car-detail-label"><?= $messages['paymenttype'] ?></div>
                                </div>
                                <div class="car-detail-value">Credit Card, Cash</div>
                            </div>
                            <div class="car-detail-row">
                                <div class="car-detail-maininfo">
                                    <span class="car-detail-icon"><i class="fa-solid fa-headset"></i></span>
                                    <div class="car-detail-label"><?= $messages['support'] ?></div>
                                </div>
                                <div class="car-detail-value">Yes</div>
                            </div>
                            <div class="car-detail-row">
                                <div class="car-detail-maininfo">
                                    <span class="car-detail-icon"><i class="fa-solid fa-truck-fast"></i></span>
                                    <div class="car-detail-label"><?= $messages['delivery'] ?></div>
                                </div>
                                <div class="car-detail-value">Yes</div>
                            </div>
                            <div class="car-detail-row">
                                <div class="car-detail-maininfo">
                                    <span class="car-detail-icon"><i class="fa-solid fa-ban"></i></span>
                                    <div class="car-detail-label"><?= $messages['cancellation'] ?></div>
                                </div>
                                <div class="car-detail-value">Yes</div>
                            </div>
                            <div class="car-detail-row">
                                <div class="car-detail-maininfo">
                                    <span class="car-detail-icon"><i class="fa-solid fa-car-burst"></i></span>
                                    <div class="car-detail-label"><?= $messages['insurance'] ?></div>
                                </div>
                                <div class="car-detail-value">Yes</div>
                            </div>
                        </div>

                        <div class="car-doc-grid">
                            <div class="car-doc-card">
                                <div class="car-doc-title"><?= $messages['residents'] ?></div>
                                <div class="car-doc-list">
                                    <div class="car-doc-item"><img src="<?= $imagePath ?>icons/tick-red.svg" alt="passport"><span><?= $messages['passport'] ?></span></div>
                                    <div class="car-doc-item"><img src="<?= $imagePath ?>icons/tick-red.svg" alt="residentialvisa"><span><?= $messages['residentialvisa'] ?></span></div>
                                    <div class="car-doc-item"><img src="<?= $imagePath ?>icons/tick-red.svg" alt="license1"><span><?= $messages['license1'] ?></span></div>
                                    <div class="car-doc-item"><img src="<?= $imagePath ?>icons/tick-red.svg" alt="emiratesid"><span><?= $messages['emiratesid'] ?></span></div>
                                </div>
                            </div>
                            <div class="car-doc-card">
                                <div class="car-doc-title"><?= $messages['tourists'] ?></div>
                                <div class="car-doc-list">
                                    <div class="car-doc-item"><img src="<?= $imagePath ?>icons/tick-red.svg" alt="passport"><span><?= $messages['passport'] ?></span></div>
                                    <div class="car-doc-item"><img src="<?= $imagePath ?>icons/tick-red.svg" alt="visitvisa"><span><?= $messages['visitvisa'] ?></span></div>
                                    <div class="car-doc-item"><img src="<?= $imagePath ?>icons/tick-red.svg" alt="license2"><span><?= $messages['license2'] ?></span></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="car-panel">
                        <div class="car-description string">
                            <?php echo $carSingleContentData["car"]["description_{$lang}"]; ?>
                        </div>
                    </div>
                </div>
                <div class="col-span-1">
                    <aside class="hd-booking-sidebar" data-hd-booking-sidebar>
                        <div data-hd-booking-step="form">
                            <div hidden aria-hidden="true">
                                <input type="hidden" name="self_pickup_location_id" data-hd-post-field="self_pickup_location_id">
                                <input type="hidden" name="self_return_location_id" data-hd-post-field="self_return_location_id">
                                <input type="hidden" name="vehicle_group_id" data-hd-post-field="vehicle_group_id">
                                <input type="hidden" name="tariff_group_id" data-hd-post-field="tariff_group_id">
                            </div>
                            
                            <div class="hd-booking-tabs mb-2" role="tablist" aria-label="Rental period">
                                <button type="button" class="hd-booking-tab is-active" data-hd-period="daily" data-hd-price="<?= htmlspecialchars((string) $dailyPrice, ENT_QUOTES, 'UTF-8'); ?>"><?= $messages['daily'] ?></button>
                                <button type="button" class="hd-booking-tab" data-hd-period="weekly" data-hd-price="<?= htmlspecialchars((string) $weeklyPrice, ENT_QUOTES, 'UTF-8'); ?>"><?= $messages['weekly'] ?></button>
                                <button type="button" class="hd-booking-tab" data-hd-period="monthly" data-hd-price="<?= htmlspecialchars((string) $monthlyPrice, ENT_QUOTES, 'UTF-8'); ?>"><?= $messages['monthly'] ?></button>
                            </div>

                            <div class="hd-booking-card mb-2">
                                <div class="hd-booking-card__title">Select duration</div>
                                
                                <div class="hd-booking-field">
                                    <span>Pickup</span>
                                    <input class="hd-booking-input" type="datetime-local" name="pickup_datetime" data-hd-from>
                                </div>
                                <div class="hd-booking-field">
                                    <span>Return</span>
                                    <input class="hd-booking-input" type="datetime-local" name="return_datetime" data-hd-to>
                                </div>
                                
                                <div class="hd-booking-hint" data-hd-duration-hint>Select your rental duration</div>
                                <div class="hd-booking-pricebox">
                                    <div>
                                        <div class="hd-booking-pricebox__label"><?= $messages['rentcost'] ?></div>
                                        <div class="hd-booking-pricebox__meta" data-hd-price-days>1 day</div>
                                    </div>
                                    <div class="hd-booking-pricebox__value"><span class="hd-price-inline"><img src="<?= $imagePath ?>darham.png" class="hd-price-inline__icon" alt="AED"><span data-hd-price-number><?= number_format($dailyPrice, 2); ?></span></span></div>
                                </div>
                            </div>

                            <div class="hd-booking-card mb-2">
                                <div class="hd-booking-card__title">Customer type</div>
                                <div class="hd-segment" role="radiogroup" aria-label="Customer type">
                                    <label class="hd-segment__item is-active">
                                        <input type="radio" name="hd_customer_type" value="resident" checked>
                                        <span>Resident</span>
                                    </label>
                                    <label class="hd-segment__item">
                                        <input type="radio" name="hd_customer_type" value="tourist">
                                        <span>Tourist</span>
                                    </label>
                                </div>
                            </div>

                            <div class="hd-booking-card mb-2">
                                <div class="hd-booking-card__title">Insurance & options</div>
                                <div class="hd-booking-checklist">
                                    <div class="hd-booking-checklist__item">Free cancellation before 24 hours</div>
                                    <div class="hd-booking-checklist__item">Support team available 24/7</div>
                                </div>
                                <div class="hd-booking-list">
                                    <button type="button" class="hd-booking-list__item" data-hd-modal-title="Minimum Age" data-hd-modal-template="hd_modal_min_age">
                                        <span class="hd-booking-list__icon hd-booking-list__icon--age" aria-hidden="true"></span>
                                        <span>Minimum Age</span>
                                        <strong>22 y.o.</strong>
                                    </button>
                                    <button type="button" class="hd-booking-list__item" data-hd-modal-title="Driving Experience" data-hd-modal-template="hd_modal_driving">
                                        <span class="hd-booking-list__icon hd-booking-list__icon--driving" aria-hidden="true"></span>
                                        <span>Driving Experience</span>
                                        <strong>1 Year</strong>
                                    </button>
                                    <button type="button" class="hd-booking-list__item" data-hd-modal-title="Required Documents" data-hd-modal-template="hd_modal_documents">
                                        <span class="hd-booking-list__icon hd-booking-list__icon--docs" aria-hidden="true"></span>
                                        <span>Required Documents</span>
                                        <strong>View</strong>
                                    </button>
                                    <button type="button" class="hd-booking-list__item" data-hd-modal-title="Terms & Conditions" data-hd-modal-template="hd_modal_terms">
                                        <span class="hd-booking-list__icon hd-booking-list__icon--terms" aria-hidden="true"></span>
                                        <span>Terms & Conditions</span>
                                        <strong>View</strong>
                                    </button>
                                    <button type="button" class="hd-booking-list__item" data-hd-modal-title="Basic Comprehensive Insurance" data-hd-modal-template="hd_modal_basic_insurance">
                                        <span class="hd-booking-list__icon hd-booking-list__icon--insurance" aria-hidden="true"></span>
                                        <span>
                                            Basic Comprehensive Insurance
                                            <small>Excess amount of the vehicle <span class="hd-price-inline"><img src="<?= $imagePath ?>darham.png" class="hd-price-inline__icon" alt="AED"><span><?= number_format($depositAmount, 2); ?></span></span></small>
                                        </span>
                                        <strong>View</strong>
                                    </button>
                                </div>
                                <div class="hd-booking-option">
                                    <div class="hd-booking-option__content">
                                        <div class="hd-booking-option__title">Full insurance</div>
                                        <div class="hd-booking-option__sub">Complete coverage add-on</div>
                                    </div>
                                    <div class="hd-booking-option__action">
                                        <div class="hd-booking-option__price"><span class="hd-price-inline"><img src="<?= $imagePath ?>darham.png" class="hd-price-inline__icon" alt="AED"><span><?= number_format($fullInsuranceAmount, 2); ?></span></span></div>
                                        <label class="hd-switch">
                                            <input type="checkbox" data-hd-extra="insurance" data-hd-extra-price="<?= htmlspecialchars((string) $fullInsuranceAmount, ENT_QUOTES, 'UTF-8'); ?>">
                                            <span></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="hd-booking-option">
                                    <div class="hd-booking-option__content">
                                        <div class="hd-booking-option__title">Additional driver</div>
                                        <div class="hd-booking-option__sub">Documents required same as 1st Driver</div>
                                    </div>
                                    <div class="hd-booking-option__action">
                                        <div class="hd-booking-option__price"><span class="hd-price-inline"><img src="<?= $imagePath ?>darham.png" class="hd-price-inline__icon" alt="AED"><span><?= number_format($additionalDriverAmount, 2); ?></span></span></div>
                                        <label class="hd-switch">
                                            <input type="checkbox" data-hd-extra="driver" data-hd-extra-price="<?= htmlspecialchars((string) $additionalDriverAmount, ENT_QUOTES, 'UTF-8'); ?>">
                                            <span></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="hd-booking-option">
                                    <div class="hd-booking-option__content">
                                        <div class="hd-booking-option__title">Baby seat</div>
                                        <div class="hd-booking-option__sub">Ensure your child's safety and comfort on every journey with our clean, secure, and properly installed baby seats available for rent.</div>
                                    </div>
                                    <div class="hd-booking-option__action">
                                        <div class="hd-booking-option__price"><span class="hd-price-inline"><img src="<?= $imagePath ?>darham.png" class="hd-price-inline__icon" alt="AED"><span><?= number_format($babySeatAmount, 2); ?></span></span></div>
                                        <label class="hd-switch">
                                            <input type="checkbox" data-hd-extra="seat" data-hd-extra-price="<?= htmlspecialchars((string) $babySeatAmount, ENT_QUOTES, 'UTF-8'); ?>">
                                            <span></span>
                                        </label>
                                    </div>
                                </div>
                                <div class="hd-waiver-card" data-hd-waiver-card>
                                    <div class="hd-waiver-card__top">
                                        <div class="hd-waiver-card__copy">
                                            <div class="hd-waiver-card__title">Enjoy a deposit-free ride for <span class="hd-price-inline"><img src="<?= $imagePath ?>darham.png" class="hd-price-inline__icon" alt="AED"><span><?= number_format($waiverAmount, 2); ?></span></span></div>
                                            <div class="hd-waiver-card__sub">Rent a car without a security deposit by adding a non-refundable waiver fee to your rental price</div>
                                        </div>
                                        <label class="hd-switch hd-switch--green">
                                            <input type="checkbox" data-hd-extra="waiver" data-hd-extra-price="<?= htmlspecialchars((string) $waiverAmount, ENT_QUOTES, 'UTF-8'); ?>" data-hd-waiver-toggle>
                                            <span></span>
                                        </label>
                                    </div>
                                    <div class="hd-waiver-card__bottom">
                                        <div class="hd-waiver-state hd-waiver-state--on is-hidden" data-hd-waiver-state="on">
                                            <span class="hd-booking-list__icon hd-booking-list__icon--waiver" aria-hidden="true"></span>
                                            <div class="hd-waiver-state__copy">
                                                <div class="hd-waiver-state__title">Waiver</div>
                                            </div>
                                            <div class="hd-waiver-state__price">
                                                <span class="hd-waiver-state__old"><span class="hd-price-inline"><img src="<?= $imagePath ?>darham.png" class="hd-price-inline__icon" alt="AED"><span><?= number_format($depositAmount, 2); ?></span></span></span>
                                                <strong><span class="hd-price-inline"><img src="<?= $imagePath ?>darham.png" class="hd-price-inline__icon" alt="AED"><span><?= number_format($waiverAmount, 2); ?></span></span></strong>
                                            </div>
                                        </div>
                                        <div class="hd-waiver-state hd-waiver-state--off" data-hd-waiver-state="off">
                                            <span class="hd-booking-list__icon hd-booking-list__icon--waiver" aria-hidden="true"></span>
                                            <div class="hd-waiver-state__copy">
                                                <div class="hd-waiver-state__title">Deposit</div>
                                                <div class="hd-waiver-state__sub">Refunded within 30 days after you return the car</div>
                                            </div>
                                            <div class="hd-waiver-state__price">
                                                <strong><span class="hd-price-inline"><img src="<?= $imagePath ?>darham.png" class="hd-price-inline__icon" alt="AED"><span><?= number_format($depositAmount, 2); ?></span></span></strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="hd-booking-card mb-2">
                                <div class="hd-booking-card__title">Delivery &amp; Return Location</div>
                                <div class="hd-booking-card__subtitle">Choose preferred locations</div>

                                <div class="hd-location-block">
                                    <div class="hd-location-block__label">Delivery location</div>
                                    <div class="hd-zone-picker" data-hd-custom-select-wrap>
                                        <button type="button" class="hd-zone-picker__trigger" data-hd-custom-select-trigger aria-expanded="false">
                                            <span class="hd-zone-picker__trigger-text">Select a delivery zone</span>
                                            <span class="hd-zone-picker__trigger-price is-hidden" data-hd-custom-select-price></span>
                                            <span class="hd-zone-picker__trigger-icon"><i class="fa-solid fa-chevron-down"></i></span>
                                        </button>
                                        <div class="hd-zone-picker__menu is-hidden" data-hd-custom-select-menu>
                                            <button type="button" class="hd-zone-picker__option is-placeholder" data-hd-custom-option data-value="" data-price="0">
                                                <span class="hd-zone-picker__label">Select a delivery zone</span>
                                            </button>
                                            <?php if (!empty($deliveryLocations)): ?>
                                                <?php foreach ($deliveryLocations as $location): ?>
                                                    <button type="button" class="hd-zone-picker__option" data-hd-custom-option data-value="<?= htmlspecialchars((string) ($location['city'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>" data-price="<?= htmlspecialchars((string) ($location['price'] ?? 0), ENT_QUOTES, 'UTF-8'); ?>">
                                                        <span class="hd-zone-picker__label"><?= htmlspecialchars((string) ($location['label'] ?? $location['city'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></span>
                                                        <?php if (!empty($location['price'])): ?>
                                                            <span class="hd-zone-picker__price"><img src="<?= $imagePath ?>darham.png" class="hd-zone-picker__price-icon" alt="AED"><span><?= number_format((float) $location['price'], 2); ?></span></span>
                                                        <?php endif; ?>
                                                    </button>
                                                <?php endforeach; ?>
                                                <button type="button" class="hd-zone-picker__option" data-hd-custom-option data-value="other" data-price="0">
                                                    <span class="hd-zone-picker__label">Other</span>
                                                </button>
                                            <?php else: ?>
                                                <button type="button" class="hd-zone-picker__option is-placeholder" data-hd-custom-option data-value="" data-price="0">
                                                    <span class="hd-zone-picker__label">No delivery locations available</span>
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <select class="hd-booking-input hd-location-select is-native-hidden" data-hd-delivery-zone data-hd-zone-select="delivery">
                                        <?php if (!empty($deliveryLocations)): ?>
                                            <option value="" data-price="0">Select a delivery zone</option>
                                            <?php foreach ($deliveryLocations as $location): ?>
                                                <option value="<?= htmlspecialchars((string) ($location['city'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>" data-price="<?= htmlspecialchars((string) ($location['price'] ?? 0), ENT_QUOTES, 'UTF-8'); ?>">
                                                    <?= htmlspecialchars((string) ($location['label'] ?? $location['city'] ?? ''), ENT_QUOTES, 'UTF-8'); ?><?= !empty($location['price']) ? ' - AED ' . number_format((float) $location['price'], 2) : ''; ?>
                                                </option>
                                            <?php endforeach; ?>
                                            <option value="other" data-price="0">Other</option>
                                        <?php else: ?>
                                            <option value="" data-price="0">No delivery locations available</option>
                                        <?php endif; ?>
                                    </select>
                                    <div class="mt-3 is-hidden" data-hd-custom-address-block="delivery">
                                        <input class="hd-booking-input" type="text" name="hd_delivery_custom_address" placeholder="Type delivery address" data-hd-custom-address-input="delivery">
                                    </div>
                                </div>

                                <div class="hd-location-block">
                                    <div class="hd-location-block__label">Self-pick-up locations</div>
                                    <div class="hd-pickup-list" data-hd-pickup-list>
                                        <div class="hd-pickup-loader" data-hd-pickup-loader>
                                            <span class="hd-pickup-loader__spinner" aria-hidden="true"></span>
                                            <span class="hd-pickup-loader__text">Loading pickup locations...</span>
                                        </div>
                                    </div>
                                </div>

                                <label class="hd-return-toggle">
                                    <input type="checkbox" value="1" checked data-hd-return-same>
                                    <span></span>
                                    <span>Return to same location</span>
                                </label>

                                <div class="hd-return-block is-hidden" data-hd-return-block>
                                    <div class="hd-location-block">
                                        <div class="hd-location-block__label">Return location</div>
                                        <div class="hd-zone-picker" data-hd-custom-select-wrap>
                                            <button type="button" class="hd-zone-picker__trigger" data-hd-custom-select-trigger aria-expanded="false">
                                                <span class="hd-zone-picker__trigger-text">Select your return address</span>
                                                <span class="hd-zone-picker__trigger-price is-hidden" data-hd-custom-select-price></span>
                                                <span class="hd-zone-picker__trigger-icon"><i class="fa-solid fa-chevron-down"></i></span>
                                            </button>
                                            <div class="hd-zone-picker__menu is-hidden" data-hd-custom-select-menu>
                                                <button type="button" class="hd-zone-picker__option is-placeholder" data-hd-custom-option data-value="" data-price="0">
                                                    <span class="hd-zone-picker__label">Select your return address</span>
                                                </button>
                                                <?php if (!empty($returnLocations)): ?>
                                                    <?php foreach ($returnLocations as $location): ?>
                                                        <button type="button" class="hd-zone-picker__option" data-hd-custom-option data-value="<?= htmlspecialchars((string) ($location['city'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>" data-price="<?= htmlspecialchars((string) ($location['price'] ?? 0), ENT_QUOTES, 'UTF-8'); ?>">
                                                            <span class="hd-zone-picker__label"><?= htmlspecialchars((string) ($location['label'] ?? $location['city'] ?? ''), ENT_QUOTES, 'UTF-8'); ?></span>
                                                            <?php if (!empty($location['price'])): ?>
                                                                <span class="hd-zone-picker__price"><img src="<?= $imagePath ?>darham.png" class="hd-zone-picker__price-icon" alt="AED"><span><?= number_format((float) $location['price'], 2); ?></span></span>
                                                            <?php endif; ?>
                                                        </button>
                                                    <?php endforeach; ?>
                                                    <button type="button" class="hd-zone-picker__option" data-hd-custom-option data-value="other" data-price="0">
                                                        <span class="hd-zone-picker__label">Other</span>
                                                    </button>
                                                <?php else: ?>
                                                    <button type="button" class="hd-zone-picker__option is-placeholder" data-hd-custom-option data-value="" data-price="0">
                                                        <span class="hd-zone-picker__label">No return locations available</span>
                                                    </button>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <select class="hd-booking-input hd-location-select is-native-hidden" data-hd-return-zone data-hd-zone-select="return">
                                            <?php if (!empty($returnLocations)): ?>
                                                <option value="" data-price="0">Select your return address</option>
                                                <?php foreach ($returnLocations as $location): ?>
                                                    <option value="<?= htmlspecialchars((string) ($location['city'] ?? ''), ENT_QUOTES, 'UTF-8'); ?>" data-price="<?= htmlspecialchars((string) ($location['price'] ?? 0), ENT_QUOTES, 'UTF-8'); ?>">
                                                        <?= htmlspecialchars((string) ($location['label'] ?? $location['city'] ?? ''), ENT_QUOTES, 'UTF-8'); ?><?= !empty($location['price']) ? ' - AED ' . number_format((float) $location['price'], 2) : ''; ?>
                                                    </option>
                                                <?php endforeach; ?>
                                                <option value="other" data-price="0">Other</option>
                                            <?php else: ?>
                                                <option value="" data-price="0">No return locations available</option>
                                            <?php endif; ?>
                                        </select>
                                        <div class="mt-3 is-hidden" data-hd-custom-address-block="return">
                                            <input class="hd-booking-input" type="text" name="hd_return_custom_address" placeholder="Type return address" data-hd-custom-address-input="return">
                                        </div>
                                    </div>

                                    <div class="hd-location-block">
                                        <div class="hd-location-block__label">Self-return locations</div>
                                        <div class="hd-pickup-list" data-hd-return-pickup-list>
                                            <div class="hd-pickup-loader" data-hd-return-pickup-loader>
                                                <span class="hd-pickup-loader__spinner" aria-hidden="true"></span>
                                                <span class="hd-pickup-loader__text">Loading return locations...</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <button class="hd-booking-continue" type="button" data-hd-continue>Continue</button>
                        </div>

                        <div class="hd-order-flow is-hidden" data-hd-booking-step="summary">
                            <div class="hd-order-shell">
                                <div class="hd-order-head">
                                    <button type="button" class="hd-order-back" data-hd-back aria-label="Back"><i class="fa-solid fa-arrow-left"></i></button>
                                    <span>Order Summary</span>
                                </div>

                                <div class="hd-order-row">
                                    <span data-hd-summary-rent-label>Rental Price for 1 Day</span>
                                    <span class="hd-order-amount hd-order-amount--green">
                                        <span class="hd-order-amount__old is-hidden" data-hd-summary-rent-old-wrap><span class="hd-price-inline"><img src="<?= $imagePath ?>darham.png" class="hd-price-inline__icon" alt="AED"><span data-hd-summary-rent-old>0.00</span></span></span>
                                        <span class="hd-order-amount__new"><span class="hd-price-inline"><img src="<?= $imagePath ?>darham.png" class="hd-price-inline__icon" alt="AED"><span data-hd-summary-rent>0.00</span></span></span>
                                    </span>
                                </div>
                                <div class="hd-order-row is-hidden" data-hd-summary-insurance-row>
                                    <span>Full Insurance</span>
                                    <span class="hd-order-amount"><span class="hd-price-inline"><img src="<?= $imagePath ?>darham.png" class="hd-price-inline__icon" alt="AED"><span data-hd-summary-insurance>0.00</span></span></span>
                                </div>
                                <div class="hd-order-row is-hidden" data-hd-summary-driver-row>
                                    <span>Additional Driver</span>
                                    <span class="hd-order-amount"><span class="hd-price-inline"><img src="<?= $imagePath ?>darham.png" class="hd-price-inline__icon" alt="AED"><span data-hd-summary-driver>0.00</span></span></span>
                                </div>
                                <div class="hd-order-row is-hidden" data-hd-summary-seat-row>
                                    <span>Baby Seat</span>
                                    <span class="hd-order-amount"><span class="hd-price-inline"><img src="<?= $imagePath ?>darham.png" class="hd-price-inline__icon" alt="AED"><span data-hd-summary-seat>0.00</span></span></span>
                                </div>
                                <div class="hd-order-row is-hidden" data-hd-summary-waiver-row>
                                    <span data-hd-summary-waiver-label>Deposit Waiver Charges</span>
                                    <span class="hd-order-amount"><span class="hd-price-inline"><img src="<?= $imagePath ?>darham.png" class="hd-price-inline__icon" alt="AED"><span data-hd-summary-waiver>0.00</span></span></span>
                                </div>
                                <div class="hd-order-row is-hidden" data-hd-summary-delivery-row>
                                    <span>Delivery Charges</span>
                                    <span class="hd-order-amount"><span class="hd-price-inline"><img src="<?= $imagePath ?>darham.png" class="hd-price-inline__icon" alt="AED"><span data-hd-summary-delivery>0.00</span></span></span>
                                </div>
                                <div class="hd-order-row is-hidden" data-hd-summary-return-row>
                                    <span>Return Charges</span>
                                    <span class="hd-order-amount"><span class="hd-price-inline"><img src="<?= $imagePath ?>darham.png" class="hd-price-inline__icon" alt="AED"><span data-hd-summary-return>0.00</span></span></span>
                                </div>

                                <div class="hd-order-divider"></div>

                                <div class="hd-order-caption hd-order-caption--caps">Discounts on rental</div>
                                <div class="hd-order-row is-hidden" data-hd-summary-paynow-discount-row>
                                    <span>Pay Now Discount (5%)</span>
                                    <span class="hd-order-amount hd-order-amount--green"><span class="hd-price-inline"><img src="<?= $imagePath ?>darham.png" class="hd-price-inline__icon" alt="AED"><span data-hd-summary-paynow-discount>0.00</span></span></span>
                                </div>
                                <div class="hd-order-row is-hidden" data-hd-summary-discount-row>
                                    <span>Promo Discount</span>
                                    <span class="hd-order-amount hd-order-amount--green"><span class="hd-price-inline"><img src="<?= $imagePath ?>darham.png" class="hd-price-inline__icon" alt="AED"><span data-hd-summary-discount>0.00</span></span></span>
                                </div>
                                
                                <div class="hd-order-divider"></div>

                                <div class="hd-order-row">
                                    <span>Subtotal (before VAT)</span>
                                    <span class="hd-order-amount"><span class="hd-price-inline"><img src="<?= $imagePath ?>darham.png" class="hd-price-inline__icon" alt="AED"><span data-hd-summary-subtotal>0.00</span></span></span>
                                </div>
                                <div class="hd-order-row hd-order-row--vat">
                                    <span>
                                        <span>VAT 5%</span>
                                        <span class="hd-order-caption">Calculated on original prices</span>
                                    </span>
                                    <span class="hd-order-amount"><span class="hd-price-inline"><img src="<?= $imagePath ?>darham.png" class="hd-price-inline__icon" alt="AED"><span data-hd-summary-vat>0.00</span></span></span>
                                </div>

                                <div class="hd-order-divider"></div>

                                <div class="hd-order-row hd-order-row--total">
                                    <span class="hd-order-total-title">Total Amount</span>
                                    <span class="hd-order-total-value"><span class="hd-price-inline"><img src="<?= $imagePath ?>darham.png" class="hd-price-inline__icon" alt="AED"><span data-hd-summary-total>0.00</span></span></span>
                                </div>
                                <div class="hd-order-total-hint is-hidden" data-hd-summary-saved-inline>
                                    <span>You saved</span>
                                    <span class="hd-price-inline"><img src="<?= $imagePath ?>darham.png" class="hd-price-inline__icon" alt="AED"><span data-hd-summary-saved-inline-amount>0.00</span></span>
                                </div>

                                <div class="hd-order-divider"></div>

                                <div class="hd-order-promo">
                                    <div class="hd-order-promo__title">Have a promo code?</div>
                                    <div class="hd-promo-row">
                                        <input class="hd-order-input" type="text" placeholder="Enter promo code" data-hd-promo-code>
                                        <button type="button" class="hd-promo-btn" data-hd-promo-apply>Apply</button>
                                        <button type="button" class="hd-promo-remove is-hidden" data-hd-promo-remove aria-label="Remove promo">×</button>
                                    </div>
                                    <div class="hd-booking-note mt-2" data-hd-promo-note>No promo applied</div>
                                </div>

                                <div class="hd-order-divider"></div>

                                <div class="hd-order-section__title">Payment Method</div>
                                <div class="hd-order-paymethod" data-hd-payment-card>
                                    <div class="hd-order-paymethod__head">
                                        <div class="hd-order-paymethod__meta">
                                            <div class="hd-order-paymethod__tick"><i class="fa-solid fa-check"></i></div>
                                            <div>
                                                <div class="hd-order-paymethod__name" data-hd-payment-title>5% Pay Now Discount Applied</div>
                                                <div class="hd-order-paymethod__sub" data-hd-payment-sub>You saved <span class="hd-price-inline"><img src="<?= $imagePath ?>darham.png" class="hd-price-inline__icon" alt="AED"><span data-hd-payment-saved-number>0.00</span></span> on rental by paying online.</div>
                                            </div>
                                        </div>
                                        <label class="hd-order-paymethod__toggle" aria-label="Toggle pay now discount">
                                            <input type="checkbox" data-hd-payment-toggle>
                                            <span class="hd-order-paymethod__toggle-ui"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="hd-order-panel" data-hd-pay-split-panel>
                                <div class="hd-order-pay-grid">
                                    <div class="hd-order-pay-row">
                                        <div>
                                            <div class="hd-order-pay-title">
                                                <span class="hd-order-pay-icon"><i class="fa-solid fa-fire-flame-curved"></i></span>
                                                <span>Pay Now (20%) to Reserve</span>
                                            </div>
                                            <div class="hd-order-pay-sub">Instant payment - Discount included</div>
                                        </div>
                                        <div class="hd-order-pay-value"><span class="hd-price-inline"><img src="<?= $imagePath ?>darham.png" class="hd-price-inline__icon" alt="AED"><span data-hd-summary-pay-now>0.00</span></span></div>
                                    </div>
                                    <div class="hd-order-divider"></div>
                                    <div class="hd-order-pay-row">
                                        <div>
                                            <div class="hd-order-pay-title">
                                                <span class="hd-order-pay-icon"><i class="fa-regular fa-credit-card"></i></span>
                                                <span>Pay at Pickup (80%)</span>
                                            </div>
                                            <div class="hd-order-pay-sub">Credit card, Debit Card (Visa, Mastercard)</div>
                                        </div>
                                        <div class="hd-order-pay-value"><span class="hd-price-inline"><img src="<?= $imagePath ?>darham.png" class="hd-price-inline__icon" alt="AED"><span data-hd-summary-pay-later>0.00</span></span></div>
                                    </div>
                                </div>
                            </div>

                            <div class="hd-order-panel" data-hd-location-summary>
                                <div class="hd-order-loc">
                                    <div class="hd-order-loc__title" data-hd-summary-pickup-title>
                                        <span class="hd-order-loc__icon"><i class="fa-solid fa-location-dot"></i></span>
                                        <span>Pickup</span>
                                    </div>
                                    <div class="hd-order-loc__meta" data-hd-summary-pickup-time>—</div>
                                    <div class="hd-order-loc__branch" data-hd-summary-pickup-branch>—</div>
                                </div>
                                <div class="hd-order-divider"></div>
                                <div class="hd-order-loc">
                                    <div class="hd-order-loc__title" data-hd-summary-dropoff-title>
                                        <span class="hd-order-loc__icon" style="background: rgba(37, 99, 235, 0.12); color: #2563eb;"><i class="fa-solid fa-square-check"></i></span>
                                        <span>Drop-off</span>
                                    </div>
                                    <div class="hd-order-loc__meta" data-hd-summary-dropoff-time>—</div>
                                    <div class="hd-order-loc__branch" data-hd-summary-dropoff-branch>—</div>
                                </div>
                                <div class="hd-order-divider"></div>
                                <div class="hd-order-cancel">
                                    <div class="hd-order-cancel__title">
                                        <span class="hd-order-cancel__icon"><i class="fa-solid fa-check"></i></span>
                                        <span>Cancellation Policy</span>
                                    </div>
                                    <div class="hd-order-cancel__sub">Free cancellation up to 24 hours before pickup</div>
                                </div>
                            </div>

                            <form class="hd-booking-form" data-hd-submit-form>
                                <input type="hidden" name="car_name" value="<?= htmlspecialchars($car["name_{$lang}"], ENT_QUOTES, 'UTF-8'); ?>">
                                <input type="hidden" name="date_from" data-hd-post-from>
                                <input type="hidden" name="date_to" data-hd-post-to>
                                <input type="hidden" name="message" data-hd-post-message>
                                <div class="hd-order-panel">
                                    <div class="hd-order-card__title">Contact Details</div>
                                    <div class="hd-order-input-grid">
                                        <div>
                                            <input class="hd-order-input" type="text" name="name" placeholder="Enter Your Name" required>
                                        </div>
                                        <div>
                                            <input class="hd-order-input" type="email" name="email" placeholder="Enter Your Email">
                                        </div>
                                    </div>
                                    <div class="hd-order-phone">
                                        <input class="hd-order-phone__input" type="tel" placeholder="Phone number" data-hd-phone-input required>
                                        <input type="hidden" name="phone_country" data-hd-phone-country>
                                        <input type="hidden" name="number" data-hd-phone-full>
                                    </div>
                                    <label class="hd-order-toggle">
                                        <span>I do not use WhatsApp, contact me directly by phone.</span>
                                        <input type="checkbox" name="no_whatsapp" value="1" data-hd-no-whatsapp>
                                        <span class="hd-order-toggle__ui" aria-hidden="true"></span>
                                    </label>
                                    <label class="hd-order-check">
                                        <input type="checkbox" name="confirm_age" value="1" data-hd-confirm-age>
                                        <span>I confirm my age is above 22 years</span>
                                    </label>
                                    <label class="hd-order-check">
                                        <input type="checkbox" name="confirm_driving" value="1" data-hd-confirm-driving>
                                        <span>I confirm my driving experience is above 6 Months</span>
                                    </label>
                                    <div class="hd-order-terms">
                                        By pressing Book, you confirm you've read and agreed to
                                        <button type="button" class="hd-order-terms__link" data-hd-modal-title="Terms &amp; Conditions" data-hd-modal-template="hd_modal_terms">Terms and Conditions</button>
                                        and acknowledged the
                                        <button type="button" class="hd-order-terms__link" data-hd-modal-title="Privacy Notice" data-hd-modal-template="hd_modal_privacy">Privacy Notice</button>.
                                    </div>
                                </div>
                                <div class="is-hidden">
                                    <label class="hd-pay-option">
                                        <input type="radio" name="payment_flow" value="pay_later" checked>
                                        <span>Pay Later</span>
                                    </label>
                                    <label class="hd-pay-option">
                                        <input type="radio" name="payment_flow" value="pay_now">
                                        <span>Pay 20% Now</span>
                                    </label>
                                </div>
                                <button type="submit" class="hd-submit-btn is-pay-now" data-hd-submit-cta>
                                    <span class="btn-text"><span data-hd-submit-label>Pay Now Only ( 20% off )</span> - <span class="hd-price-inline"><img src="<?= $imagePath ?>darham.png" class="hd-price-inline__icon" alt="AED"><span data-hd-submit-amount>0.00</span></span></span>
                                    <span class="loader"></span>
                                </button>
                            </form>
                        </div>
                    </aside>
                </div>
            </div>
        </div>
        </div>
    </section>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@23.8.0/build/css/intlTelInput.min.css">
    <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@23.8.0/build/js/intlTelInput.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@23.8.0/build/js/utils.js"></script>

    <template id="hd_modal_min_age">
        <div class="hd-modal-contentbox">
            <div class="hd-modal-copy">Minimum driver age for this vehicle is 22 years.</div>
        </div>
    </template>
    <template id="hd_modal_driving">
        <div class="hd-modal-contentbox">
            <div class="hd-modal-copy">Valid driving experience of at least 1 year is required.</div>
        </div>
    </template>
    <template id="hd_modal_documents">
        <div class="hd-modal-docs">
            <div>
                <div class="hd-modal-docs__title"><?= $messages['residents'] ?></div>
                <ul>
                    <li><?= $messages['passport'] ?></li>
                    <li><?= $messages['residentialvisa'] ?></li>
                    <li><?= $messages['license1'] ?></li>
                    <li><?= $messages['emiratesid'] ?></li>
                </ul>
            </div>
            <div>
                <div class="hd-modal-docs__title"><?= $messages['tourists'] ?></div>
                <ul>
                    <li><?= $messages['passport'] ?></li>
                    <li><?= $messages['visitvisa'] ?></li>
                    <li><?= $messages['license2'] ?></li>
                </ul>
            </div>
        </div>
    </template>
    <template id="hd_modal_terms">
        <div class="hd-modal-contentbox">
            <div class="hd-modal-copy">Rental is billed on a 24-hour basis. Free cancellation applies before 24 hours. UAE use only. Traffic fines, Salik and damage liabilities remain subject to rental policy and police report requirements.</div>
        </div>
    </template>
    <template id="hd_modal_privacy">
        <div class="hd-modal-contentbox">
            <div class="hd-modal-copy">Your contact details are used only to process your reservation request, booking follow-up and service coordination. We do not share your information outside the booking workflow.</div>
        </div>
    </template>
    <template id="hd_modal_basic_insurance">
        <div class="hd-modal-contentbox">
            <div class="hd-modal-copy">Basic Comprehensive Insurance</div>
            <div class="hd-modal-copy">Excess amount of the vehicle <?= number_format($depositAmount, 2); ?> AED</div>
        </div>
    </template>

    <div class="hd-modal is-hidden" data-hd-modal>
        <div class="hd-modal__backdrop" data-hd-modal-close></div>
        <div class="hd-modal__dialog">
            <button type="button" class="hd-modal__close" data-hd-modal-close>&times;</button>
            <div class="hd-modal__title" data-hd-modal-title></div>
            <div class="hd-modal__body" data-hd-modal-body></div>
        </div>
    </div>

    <!--------------------------------- footer ------------------------------->
    <script>
        (function () {
            const root = document.querySelector('[data-hd-booking-sidebar]');
            if (!root) return;

            const state = {
                period: 'daily',
                price: <?= json_encode($dailyPrice); ?>,
                prices: {
                    daily: <?= json_encode($dailyPrice); ?>,
                    weekly: <?= json_encode($weeklyPrice); ?>,
                    monthly: <?= json_encode($monthlyPrice); ?>
                },
                promoCode: '',
                promoDiscount: 0
            };

            const tabs = Array.from(root.querySelectorAll('[data-hd-period]'));
            const fromInput = root.querySelector('[data-hd-from]');
            const toInput = root.querySelector('[data-hd-to]');
            const phoneInput = root.querySelector('[data-hd-phone-input]');
            const phoneCountryHidden = root.querySelector('[data-hd-phone-country]');
            const phoneFullHidden = root.querySelector('[data-hd-phone-full]');
            const durationHint = root.querySelector('[data-hd-duration-hint]');
            const priceDays = root.querySelector('[data-hd-price-days]');
            const priceNumber = root.querySelector('[data-hd-price-number]');
            const formStep = root.querySelector('[data-hd-booking-step="form"]');
            const summaryStep = root.querySelector('[data-hd-booking-step="summary"]');
            const continueBtn = root.querySelector('[data-hd-continue]');
            const backBtn = root.querySelector('[data-hd-back]');
            const heroHead = document.querySelector('.car-hero-head');
            const extraInputs = Array.from(root.querySelectorAll('[data-hd-extra]'));
            const deliveryZone = root.querySelector('[data-hd-delivery-zone]');
            const returnZone = root.querySelector('[data-hd-return-zone]');
            const returnSameToggle = root.querySelector('[data-hd-return-same]');
            const returnBlock = root.querySelector('[data-hd-return-block]');
            const deliveryCustomAddressInput = root.querySelector('[data-hd-custom-address-input="delivery"]');
            const returnCustomAddressInput = root.querySelector('[data-hd-custom-address-input="return"]');
            const promoInput = root.querySelector('[data-hd-promo-code]');
            const promoApplyBtn = root.querySelector('[data-hd-promo-apply]');
            const promoRemoveBtn = root.querySelector('[data-hd-promo-remove]');
            const promoNote = root.querySelector('[data-hd-promo-note]');
            const submitForm = root.querySelector('[data-hd-submit-form]');
            const paymentToggle = root.querySelector('[data-hd-payment-toggle]');
            const locationSummaryPanel = root.querySelector('[data-hd-location-summary]');
            const paySplitPanel = root.querySelector('[data-hd-pay-split-panel]');
            const paymentTitle = root.querySelector('[data-hd-payment-title]');
            const paymentSub = root.querySelector('[data-hd-payment-sub]');
            const paymentSavedNumber = root.querySelector('[data-hd-payment-saved-number]');
            const submitCta = root.querySelector('[data-hd-submit-cta]');
            const submitLabel = root.querySelector('[data-hd-submit-label]');
            const submitAmount = root.querySelector('[data-hd-submit-amount]');
            const confirmAge = root.querySelector('[data-hd-confirm-age]');
            const confirmDriving = root.querySelector('[data-hd-confirm-driving]');
            const paymentFlowInputs = Array.from(root.querySelectorAll('input[name="payment_flow"]'));
            const waiverToggle = root.querySelector('[data-hd-waiver-toggle]');
            const waiverStateOn = root.querySelector('[data-hd-waiver-state="on"]');
            const waiverStateOff = root.querySelector('[data-hd-waiver-state="off"]');
            const bookingMeta = {
                vehicle_group_id: <?= json_encode($vehicleGroupId); ?>,
                tariff_group_id: <?= json_encode($tariffGroupId); ?>
            };
            let pickupCards = [];
            let pickupBranchInputs = [];
            let returnBranchInputs = [];
            let speedLocationOptions = [];
            const pickupList = root.querySelector('[data-hd-pickup-list]');
            const returnPickupList = root.querySelector('[data-hd-return-pickup-list]');
            const pickupTitleNode = root.querySelector('[data-hd-summary-pickup-title]');
            const dropoffTitleNode = root.querySelector('[data-hd-summary-dropoff-title]');
            const customSelects = Array.from(root.querySelectorAll('[data-hd-custom-select-wrap]'));
            let phoneIti = null;

            if (phoneInput instanceof HTMLInputElement && typeof window.intlTelInput === 'function') {
                phoneIti = window.intlTelInput(phoneInput, {
                    initialCountry: 'ae',
                    preferredCountries: ['ae', 'pk', 'sa', 'in', 'gb', 'us'],
                    separateDialCode: true,
                    utilsScript: 'https://cdn.jsdelivr.net/npm/intl-tel-input@23.8.0/build/js/utils.js'
                });

                const syncPhoneCountry = function () {
                    if (!(phoneCountryHidden instanceof HTMLInputElement)) return;
                    const data = phoneIti && typeof phoneIti.getSelectedCountryData === 'function'
                        ? phoneIti.getSelectedCountryData()
                        : null;
                    phoneCountryHidden.value = data && data.dialCode ? '+' + data.dialCode : '';
                };

                phoneInput.addEventListener('countrychange', syncPhoneCountry);
                phoneInput.addEventListener('change', syncPhoneCountry);
                syncPhoneCountry();
            }

            const summary = {
                rentLabel: root.querySelector('[data-hd-summary-rent-label]'),
                rentOldWrap: root.querySelector('[data-hd-summary-rent-old-wrap]'),
                rentOld: root.querySelector('[data-hd-summary-rent-old]'),
                rent: root.querySelector('[data-hd-summary-rent]'),
                insuranceRow: root.querySelector('[data-hd-summary-insurance-row]'),
                insurance: root.querySelector('[data-hd-summary-insurance]'),
                driverRow: root.querySelector('[data-hd-summary-driver-row]'),
                driver: root.querySelector('[data-hd-summary-driver]'),
                seatRow: root.querySelector('[data-hd-summary-seat-row]'),
                seat: root.querySelector('[data-hd-summary-seat]'),
                waiverRow: root.querySelector('[data-hd-summary-waiver-row]'),
                waiverLabel: root.querySelector('[data-hd-summary-waiver-label]'),
                waiver: root.querySelector('[data-hd-summary-waiver]'),
                deliveryRow: root.querySelector('[data-hd-summary-delivery-row]'),
                delivery: root.querySelector('[data-hd-summary-delivery]'),
                returnRow: root.querySelector('[data-hd-summary-return-row]'),
                returnCharge: root.querySelector('[data-hd-summary-return]'),
                subtotal: root.querySelector('[data-hd-summary-subtotal]'),
                vat: root.querySelector('[data-hd-summary-vat]'),
                payNowDiscountRow: root.querySelector('[data-hd-summary-paynow-discount-row]'),
                payNowDiscount: root.querySelector('[data-hd-summary-paynow-discount]'),
                discountRow: root.querySelector('[data-hd-summary-discount-row]'),
                discount: root.querySelector('[data-hd-summary-discount]'),
                savingsRow: root.querySelector('[data-hd-summary-savings-row]'),
                savings: root.querySelector('[data-hd-summary-savings]'),
                savedInline: root.querySelector('[data-hd-summary-saved-inline]'),
                savedInlineAmount: root.querySelector('[data-hd-summary-saved-inline-amount]'),
                total: root.querySelector('[data-hd-summary-total]'),
                payNow: root.querySelector('[data-hd-summary-pay-now]'),
                payLater: root.querySelector('[data-hd-summary-pay-later]'),
                pickupTime: root.querySelector('[data-hd-summary-pickup-time]'),
                pickupBranch: root.querySelector('[data-hd-summary-pickup-branch]'),
                dropoffTime: root.querySelector('[data-hd-summary-dropoff-time]'),
                dropoffBranch: root.querySelector('[data-hd-summary-dropoff-branch]')
            };

            const modal = document.querySelector('[data-hd-modal]');
            const modalTitle = modal ? modal.querySelector('[data-hd-modal-title]') : null;
            const modalBody = modal ? modal.querySelector('[data-hd-modal-body]') : null;
            const locationsEndpoint = <?= json_encode($locationsEndpoint, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?>;

            function formatAmount(value) {
                return Number(value || 0).toFixed(2);
            }

            function pad2(value) {
                return String(value).padStart(2, '0');
            }

            function ensureToastStack() {
                let stack = document.querySelector('.hd-toast-stack');
                if (stack) return stack;
                stack = document.createElement('div');
                stack.className = 'hd-toast-stack';
                document.body.appendChild(stack);
                return stack;
            }

            function showToast(type, title, message, timeoutMs) {
                const stack = ensureToastStack();
                const toast = document.createElement('div');
                toast.className = 'hd-toast hd-toast--' + (type === 'success' ? 'success' : 'error');
                toast.innerHTML =
                    '<div class="hd-toast__icon" aria-hidden="true">' + (type === 'success' ? '✓' : '!') + '</div>' +
                    '<div><div class="hd-toast__title"></div><div class="hd-toast__msg"></div></div>' +
                    '<button type="button" class="hd-toast__close" aria-label="Close">×</button>';
                toast.querySelector('.hd-toast__title').textContent = title || '';
                toast.querySelector('.hd-toast__msg').textContent = message || '';
                toast.querySelector('.hd-toast__close').addEventListener('click', function () {
                    toast.remove();
                });
                stack.appendChild(toast);
                requestAnimationFrame(function () {
                    toast.classList.add('is-show');
                });
                window.setTimeout(function () {
                    toast.classList.remove('is-show');
                    window.setTimeout(function () {
                        toast.remove();
                    }, 220);
                }, timeoutMs || 3500);
            }

            function parseAmount(value) {
                const amount = Number(value || 0);
                return Number.isFinite(amount) ? amount : 0;
            }

            function setPostField(name, value) {
                const node = root.querySelector('[data-hd-post-field="' + name + '"]');
                if (!(node instanceof HTMLInputElement)) return;
                node.value = value == null ? '' : String(value);
            }

            function getExtraInput(extraName) {
                return extraInputs.find(function (input) {
                    return input.dataset.hdExtra === extraName;
                }) || null;
            }

            function isExtraChecked(extraName) {
                const input = getExtraInput(extraName);
                return !!(input && input.checked);
            }

            function getExtraPrice(extraName) {
                const input = getExtraInput(extraName);
                return input ? parseAmount(input.dataset.hdExtraPrice || 0) : 0;
            }

            function fetchWithTimeout(url, options) {
                const controller = new AbortController();
                const timer = window.setTimeout(function () {
                    controller.abort();
                }, 10000);

                return fetch(url, Object.assign({}, options || {}, { signal: controller.signal }))
                    .finally(function () {
                        window.clearTimeout(timer);
                    });
            }

            function slugifyLocationValue(value, fallback) {
                const source = String(value || fallback || '').trim().toLowerCase();
                const slug = source.replace(/[^a-z0-9]+/g, '_').replace(/^_+|_+$/g, '');
                return slug || ('location_' + Date.now());
            }

            function normalizeSpeedLocations(data) {
                const rawItems = Array.isArray(data && data.result)
                    ? data.result
                    : (Array.isArray(data && data.items)
                        ? data.items
                        : (Array.isArray(data && data.data && data.data.items)
                            ? data.data.items
                            : (Array.isArray(data && data.data) ? data.data : [])));

                return rawItems.map(function (item, index) {
                    if (!item || typeof item !== 'object') return null;
                    const name = String(item.name || item.title || item.location || item.address || '').trim();
                    if (!name) return null;
                    const code = String(item.code || '').trim();
                    const rawId = item.id ?? item.location_id ?? item.locationId ?? item.ID ?? '';
                    const id = rawId == null ? '' : String(rawId).trim();

                    return {
                        id: id,
                        code: code,
                        name: name,
                        value: slugifyLocationValue(code || name || id, String(index + 1))
                    };
                }).filter(Boolean);
            }

            function refreshBranchCollections() {
                pickupCards = Array.from(root.querySelectorAll('.hd-pickup-card'));
                pickupBranchInputs = Array.from(root.querySelectorAll('input[name="hd_pickup_branch"]'));
                returnBranchInputs = Array.from(root.querySelectorAll('input[name="hd_return_branch"]'));
            }

            function renderPickupLoader(container, message, isError) {
                if (!(container instanceof HTMLElement)) return;
                const loader = document.createElement('div');
                loader.className = 'hd-pickup-loader' + (isError ? ' is-error' : '');

                const spinner = document.createElement('span');
                spinner.className = 'hd-pickup-loader__spinner';
                spinner.setAttribute('aria-hidden', 'true');
                if (isError) {
                    spinner.style.display = 'none';
                }

                const text = document.createElement('span');
                text.className = 'hd-pickup-loader__text';
                text.textContent = String(message || '').trim() || 'Loading locations...';

                loader.appendChild(spinner);
                loader.appendChild(text);

                container.innerHTML = '';
                container.appendChild(loader);
            }

            function createSpeedLocationOption(groupName, location, checked) {
                const label = document.createElement('label');
                label.className = 'hd-pickup-card' + (checked ? ' is-active' : '');

                const input = document.createElement('input');
                input.type = 'radio';
                input.name = groupName;
                input.value = location.value;
                input.dataset.locationId = location.id || '';
                input.checked = !!checked;

                const dot = document.createElement('span');
                dot.className = 'hd-pickup-card__dot';

                const body = document.createElement('span');
                body.className = 'hd-pickup-card__body';

                const title = document.createElement('span');
                title.className = 'hd-pickup-card__title';
                title.textContent = location.name || location.code || location.value;
                body.appendChild(title);
                label.appendChild(input);
                label.appendChild(dot);
                label.appendChild(body);

                return label;
            }

            function renderSpeedLocationOptions(container, groupName, locations) {
                if (!(container instanceof HTMLElement)) return;
                if (!Array.isArray(locations) || !locations.length) return;

                const currentValue = getSelectedRadioValue(groupName);
                const selectedValue = locations.some(function (location) {
                    return location.value === currentValue;
                }) ? currentValue : locations[0].value;

                container.innerHTML = '';
                locations.forEach(function (location) {
                    container.appendChild(createSpeedLocationOption(groupName, location, location.value === selectedValue));
                });
            }

            function getSelectedRadioValue(name) {
                const checked = root.querySelector('input[name="' + name + '"]:checked');
                if (!(checked instanceof HTMLInputElement)) return '';
                return checked.value || '';
            }

            function getSelectedRadioLabel(name) {
                const checked = root.querySelector('input[name="' + name + '"]:checked');
                if (!(checked instanceof HTMLInputElement)) return '';
                const label = checked.closest('label');
                const title = label ? label.querySelector('.hd-pickup-card__title') : null;
                if (title) return String(title.textContent || '').trim();
                return getBranchLabel(name, checked.value || '');
            }

            function getSelectedSpeedLocation(name) {
                const selectedValue = getSelectedRadioValue(name);
                if (!selectedValue) return null;
                return speedLocationOptions.find(function (location) {
                    return location.value === selectedValue;
                }) || null;
            }

            function getSelectedSpeedLocationId(name) {
                const checked = root.querySelector('input[name="' + name + '"]:checked');
                if (checked instanceof HTMLInputElement && checked.dataset.locationId) {
                    return checked.dataset.locationId;
                }
                const selectedLocation = getSelectedSpeedLocation(name);
                return selectedLocation && selectedLocation.id ? selectedLocation.id : '';
            }

            async function loadSpeedLocations() {
                if (!(pickupList instanceof HTMLElement) || !(returnPickupList instanceof HTMLElement)) return;

                renderPickupLoader(pickupList, 'Loading pickup locations...');
                renderPickupLoader(returnPickupList, 'Loading return locations...');

                const endpoints = [locationsEndpoint];
                let lastErrorMessage = 'Could not load location data right now.';

                for (let i = 0; i < endpoints.length; i += 1) {
                    const endpoint = endpoints[i];
                    let response;

                    try {
                        response = await fetchWithTimeout(endpoint, {
                            method: 'GET',
                            headers: {
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        });
                    } catch (requestErr) {
                        lastErrorMessage = requestErr && requestErr.name === 'AbortError'
                            ? 'Location request timed out. Please try again.'
                            : 'Could not connect to location service.';
                        continue;
                    }

                    const data = await response.json().catch(function () { return {}; });
                    if (!response.ok) {
                        lastErrorMessage = 'Request failed (' + response.status + ')';
                        continue;
                    }

                    const locations = normalizeSpeedLocations(data);
                    if (!locations.length) {
                        lastErrorMessage = 'No locations available right now.';
                        continue;
                    }

                    speedLocationOptions = locations;
                    renderSpeedLocationOptions(pickupList, 'hd_pickup_branch', locations);
                    renderSpeedLocationOptions(returnPickupList, 'hd_return_branch', locations);
                    refreshBranchCollections();
                    syncLocationSelection('pickup');
                    syncLocationSelection('return');
                    syncReturnSameUi();
                    updatePickupCards();
                    updateSummary();
                    return;
                }

                speedLocationOptions = [];
                renderPickupLoader(pickupList, lastErrorMessage, true);
                renderPickupLoader(returnPickupList, lastErrorMessage, true);
                refreshBranchCollections();
                updatePickupCards();
                updateSummary();
            }

            function isPayNowSelected() {
                const checked = root.querySelector('input[name="payment_flow"]:checked');
                return !!(checked && checked.value === 'pay_now');
            }

            function setPaymentFlow(flow) {
                const nextFlow = flow === 'pay_now' ? 'pay_now' : 'pay_later';
                paymentFlowInputs.forEach(function (input) {
                    input.checked = input.value === nextFlow;
                });
                if (paymentToggle) {
                    paymentToggle.checked = nextFlow === 'pay_now';
                }
            }

            function toLocalInputValue(date) {
                const pad = (n) => String(n).padStart(2, '0');
                return date.getFullYear() + '-' + pad(date.getMonth() + 1) + '-' + pad(date.getDate()) + 'T' + pad(date.getHours()) + ':' + pad(date.getMinutes());
            }

            function diffInDays(start, end) {
                const ms = end - start;
                if (ms <= 0) return 0;
                return ms / (1000 * 60 * 60 * 24);
            }

            function calcRentalPrice(days) {
                const daysCount = Number(days || 0);
                if (!Number.isFinite(daysCount) || daysCount <= 0) return 0;

                const dailyPrice = parseAmount(state.prices.daily || 0);
                const weeklyPrice = parseAmount(state.prices.weekly || 0);
                const monthlyPrice = parseAmount(state.prices.monthly || 0);

                if (daysCount <= 6) {
                    return daysCount * dailyPrice;
                }
                if (daysCount >= 7 && daysCount < 30) {
                    return (daysCount / 7) * weeklyPrice;
                }
                return (daysCount / 30) * monthlyPrice;
            }

            function getCustomerType() {
                const checked = root.querySelector('input[name="hd_customer_type"]:checked');
                return checked ? checked.value : 'resident';
            }

            function getExtrasTotal() {
                return extraInputs.reduce((sum, input) => sum + (input.checked ? Number(input.dataset.hdExtraPrice || 0) : 0), 0);
            }

            function getCharges() {
                const delivery = deliveryZone && deliveryZone.selectedIndex >= 0
                    ? parseAmount(deliveryZone.options[deliveryZone.selectedIndex].dataset.price || 0)
                    : 0;
                let returnFee = 0;

                if (returnSameToggle && returnSameToggle.checked) {
                    returnFee = deliveryZone && deliveryZone.value ? delivery : 0;
                } else if (returnZone && returnZone.selectedIndex >= 0) {
                    returnFee = parseAmount(returnZone.options[returnZone.selectedIndex].dataset.price || 0);
                }

                return { delivery, returnFee };
            }

            function hasSelectableOptions(select) {
                if (!select) return false;
                return Array.from(select.options || []).some(function (option) {
                    return !!(option.value && !option.disabled);
                });
            }

            function hasZoneValue(select) {
                return !!(select && typeof select.value === 'string' && select.value.trim() !== '');
            }

            function formatBookingDate(value) {
                if (!value) return '-';
                const date = new Date(value);
                if (Number.isNaN(date.getTime())) return value;
                return (date.getMonth() + 1) + '/' + date.getDate() + '/' + date.getFullYear() + ' | ' + String(date.getHours()).padStart(2, '0') + ':' + String(date.getMinutes()).padStart(2, '0');
            }

            function getBranchLabel(name, fallback) {
                const checked = root.querySelector('input[name="' + name + '"]:checked');
                const title = checked ? checked.closest('label')?.querySelector('.hd-pickup-card__title') : null;
                if (title) {
                    return String(title.textContent || '').trim();
                }
                return String(fallback || '').replace(/_/g, ' ');
            }

            function getSelectedOptionLabel(select) {
                if (!select || select.selectedIndex < 0) return '';
                const option = select.options[select.selectedIndex];
                if (!option || !option.value) return '';
                return option.textContent.replace(/\s+-\s+AED\s+\d+(?:\.\d+)?\s*$/i, '').trim();
            }

            function getCustomAddressBlock(kind) {
                return root.querySelector('[data-hd-custom-address-block="' + kind + '"]');
            }

            function getCustomAddressInput(kind) {
                return root.querySelector('[data-hd-custom-address-input="' + kind + '"]');
            }

            function getCustomAddressValue(kind) {
                const node = getCustomAddressInput(kind);
                return node instanceof HTMLInputElement ? node.value.trim() : '';
            }

            function closeCustomSelects(exceptWrap) {
                customSelects.forEach(function (wrap) {
                    if (!(wrap instanceof HTMLElement) || wrap === exceptWrap) return;
                    wrap.classList.remove('is-open');
                    const menu = wrap.querySelector('[data-hd-custom-select-menu]');
                    const trigger = wrap.querySelector('[data-hd-custom-select-trigger]');
                    if (menu instanceof HTMLElement) {
                        menu.classList.add('is-hidden');
                    }
                    if (trigger instanceof HTMLElement) {
                        trigger.setAttribute('aria-expanded', 'false');
                    }
                });
            }

            function formatCustomSelectText(select) {
                if (!(select instanceof HTMLSelectElement) || select.selectedIndex < 0) {
                    return '';
                }
                const option = select.options[select.selectedIndex];
                if (!option) return '';
                return option.textContent.replace(/\s+-\s+AED\s+\d+(?:\.\d+)?\s*$/i, '').trim();
            }

            function syncCustomSelectUi(select) {
                if (!(select instanceof HTMLSelectElement)) return;
                const wrap = select.previousElementSibling;
                if (!(wrap instanceof HTMLElement) || !wrap.hasAttribute('data-hd-custom-select-wrap')) return;
                const triggerText = wrap.querySelector('.hd-zone-picker__trigger-text');
                const triggerPrice = wrap.querySelector('[data-hd-custom-select-price]');
                const optionButtons = Array.from(wrap.querySelectorAll('[data-hd-custom-option]'));
                const selectedValue = select.value || '';
                const text = formatCustomSelectText(select) || (optionButtons[0]?.textContent || '').trim();
                const option = select.selectedIndex >= 0 ? select.options[select.selectedIndex] : null;
                const price = option ? parseAmount(option.dataset.price || 0) : 0;

                if (triggerText instanceof HTMLElement) {
                    triggerText.textContent = text;
                    triggerText.classList.toggle('is-placeholder', !selectedValue);
                }

                if (triggerPrice instanceof HTMLElement) {
                    if (selectedValue && price > 0) {
                        triggerPrice.innerHTML = '<img src="<?= $imagePath ?>darham.png" class="hd-zone-picker__price-icon" alt="AED"><span>' + formatAmount(price) + '</span>';
                        triggerPrice.classList.remove('is-hidden');
                    } else {
                        triggerPrice.innerHTML = '';
                        triggerPrice.classList.add('is-hidden');
                    }
                }

                optionButtons.forEach(function (button) {
                    if (!(button instanceof HTMLElement)) return;
                    button.classList.toggle('is-active', button.dataset.value === selectedValue);
                });
            }

            function initCustomSelect(select) {
                if (!(select instanceof HTMLSelectElement)) return;
                const wrap = select.previousElementSibling;
                if (!(wrap instanceof HTMLElement) || !wrap.hasAttribute('data-hd-custom-select-wrap')) return;
                const trigger = wrap.querySelector('[data-hd-custom-select-trigger]');
                const menu = wrap.querySelector('[data-hd-custom-select-menu]');
                const optionButtons = Array.from(wrap.querySelectorAll('[data-hd-custom-option]'));
                if (!(trigger instanceof HTMLButtonElement) || !(menu instanceof HTMLElement)) return;

                trigger.addEventListener('click', function () {
                    const willOpen = !wrap.classList.contains('is-open');
                    closeCustomSelects(wrap);
                    wrap.classList.toggle('is-open', willOpen);
                    menu.classList.toggle('is-hidden', !willOpen);
                    trigger.setAttribute('aria-expanded', willOpen ? 'true' : 'false');
                });

                optionButtons.forEach(function (button) {
                    if (!(button instanceof HTMLButtonElement)) return;
                    button.addEventListener('click', function () {
                        select.value = button.dataset.value || '';
                        syncCustomSelectUi(select);
                        closeCustomSelects();
                        select.dispatchEvent(new Event('change', { bubbles: true }));
                    });
                });

                select.addEventListener('change', function () {
                    syncCustomSelectUi(select);
                });

                syncCustomSelectUi(select);
            }

            function clearRadioGroup(inputs) {
                inputs.forEach(function (input) {
                    input.checked = false;
                });
            }

            function syncLocationSelection(type) {
                if (type === 'pickup') {
                    if (hasZoneValue(deliveryZone)) {
                        clearRadioGroup(pickupBranchInputs);
                    } else if (!pickupBranchInputs.some(function (input) { return input.checked; })) {
                        const defaultPickup = pickupBranchInputs[0] || null;
                        if (defaultPickup) defaultPickup.checked = true;
                    }
                }

                if (type === 'return') {
                    if (returnSameToggle && returnSameToggle.checked) {
                        updatePickupCards();
                        return;
                    }
                    if (hasZoneValue(returnZone)) {
                        clearRadioGroup(returnBranchInputs);
                    } else if (!returnBranchInputs.some(function (input) { return input.checked; })) {
                        const defaultReturnPickup = returnBranchInputs[0] || null;
                        if (defaultReturnPickup) defaultReturnPickup.checked = true;
                    }
                }

                updatePickupCards();
            }

            function buildPickupSummaryData() {
                const deliveryLabel = getSelectedOptionLabel(deliveryZone);
                const deliveryCustomAddress = getCustomAddressValue('delivery');
                if (deliveryLabel) {
                    return {
                        primary: deliveryLabel,
                        secondary: deliveryCustomAddress && deliveryCustomAddress !== deliveryLabel ? deliveryCustomAddress : '',
                        plain: deliveryCustomAddress ? deliveryLabel + ' ' + deliveryCustomAddress : deliveryLabel
                    };
                }
                const branchInput = root.querySelector('input[name="hd_pickup_branch"]:checked');
                const branchLabel = branchInput ? getBranchLabel('hd_pickup_branch', branchInput.value) : 'Not selected';
                return {
                    primary: branchLabel,
                    secondary: '',
                    plain: branchLabel
                };
            }

            function buildDropoffSummaryData() {
                if (returnSameToggle && returnSameToggle.checked) {
                    return buildPickupSummaryData();
                }

                const returnLabel = getSelectedOptionLabel(returnZone);
                const returnCustomAddress = getCustomAddressValue('return');
                const fallbackPickup = buildPickupSummaryData().primary;
                if (returnLabel) {
                    return {
                        primary: returnLabel,
                        secondary: returnCustomAddress && returnCustomAddress !== returnLabel ? returnCustomAddress : '',
                        plain: returnCustomAddress ? returnLabel + ' ' + returnCustomAddress : returnLabel
                    };
                }
                const branchInput = root.querySelector('input[name="hd_return_branch"]:checked');
                const branchLabel = branchInput ? getBranchLabel('hd_return_branch', branchInput.value) : fallbackPickup || 'Not selected';
                return {
                    primary: branchLabel,
                    secondary: '',
                    plain: branchLabel
                };
            }

            function hasCheckedRadio(inputs) {
                return inputs.some(function (input) {
                    return !!input.checked;
                });
            }

            function renderSummaryBranch(node, summaryData) {
                if (!(node instanceof HTMLElement)) return;
                const primary = summaryData && summaryData.primary ? summaryData.primary : '—';
                const secondary = summaryData && summaryData.secondary ? summaryData.secondary : '';

                if (!secondary) {
                    node.textContent = primary;
                    return;
                }

                node.innerHTML = '';

                const primaryNode = document.createElement('span');
                primaryNode.className = 'hd-order-loc__branch-main';
                primaryNode.textContent = primary;
                node.appendChild(primaryNode);

                const secondaryNode = document.createElement('span');
                secondaryNode.className = 'hd-order-loc__branch-sub';
                secondaryNode.textContent = secondary;
                node.appendChild(secondaryNode);
            }

            function syncCustomAddressUi(kind) {
                const select = kind === 'return' ? returnZone : deliveryZone;
                const block = getCustomAddressBlock(kind);
                const input = getCustomAddressInput(kind);
                if (!(block instanceof HTMLElement) || !(input instanceof HTMLInputElement)) return;

                const shouldShow = kind === 'return'
                    ? !!(!returnSameToggle?.checked && hasZoneValue(select))
                    : hasZoneValue(select);

                block.classList.toggle('is-hidden', !shouldShow);
                if (!shouldShow) {
                    input.value = '';
                }
            }

            function canUseReturnSameLocation() {
                return !hasZoneValue(deliveryZone);
            }

            function syncReturnSameUi() {
                if (!(returnSameToggle instanceof HTMLInputElement)) return;
                const canUseSameLocation = canUseReturnSameLocation();
                if (!canUseSameLocation) {
                    returnSameToggle.checked = false;
                }
                returnSameToggle.disabled = !canUseSameLocation;
                const toggleLabel = returnSameToggle.closest('.hd-return-toggle');
                if (toggleLabel instanceof HTMLElement) {
                    toggleLabel.classList.toggle('is-disabled', !canUseSameLocation);
                }
            }

            function getDepositAmount() {
                return <?= json_encode((float) $depositAmount); ?>;
            }

            function getPricing(options) {
                const pricingOptions = options || {};
                let rawDays = 1;
                const start = fromInput && fromInput.value ? new Date(fromInput.value) : null;
                const end = toInput && toInput.value ? new Date(toInput.value) : null;

                if (start && end && !Number.isNaN(start.getTime()) && !Number.isNaN(end.getTime())) {
                    rawDays = Math.max(diffInDays(start, end), 1);
                }

                const rent = calcRentalPrice(rawDays);
                const extras = getExtrasTotal();
                const charges = getCharges();
                const depositCharge = waiverToggle && !waiverToggle.checked ? getDepositAmount() : 0;
                const payNowDiscount = isPayNowSelected() ? rent * 0.05 : 0;
                const rentAfterPayNowDiscount = rent - payNowDiscount;
                const originalTotalBeforeDiscount = rent + extras + charges.delivery + charges.returnFee + depositCharge;
                const subtotalBeforePromo = rentAfterPayNowDiscount + extras + charges.delivery + charges.returnFee + depositCharge;
                const vat = originalTotalBeforeDiscount * 0.05;
                const totalBeforePromo = subtotalBeforePromo + vat;
                const rawPromoDiscount = pricingOptions.ignorePromo ? 0 : parseAmount(state.promoDiscount || 0);
                const promoDiscount = Math.min(rawPromoDiscount, totalBeforePromo);
                const promoDiscountAppliedToRent = Math.min(promoDiscount, rentAfterPayNowDiscount);
                const rentAfterDiscounts = Math.max(0, rentAfterPayNowDiscount - promoDiscountAppliedToRent);
                const totalSavings = payNowDiscount + promoDiscount;
                const total = Math.max(0, totalBeforePromo - promoDiscount);
                const subtotal = Math.max(0, subtotalBeforePromo - promoDiscount);

                return {
                    rawDays,
                    rent,
                    rentAfterDiscounts,
                    extras,
                    payNowDiscount,
                    promoDiscount,
                    totalSavings,
                    subtotal,
                    vat,
                    total,
                    totalBeforePromo,
                    depositCharge,
                    delivery: charges.delivery,
                    returnFee: charges.returnFee
                };
            }

            function updateDurationUI() {
                const pricing = getPricing();
                const start = fromInput && fromInput.value ? new Date(fromInput.value) : null;
                const end = toInput && toInput.value ? new Date(toInput.value) : null;

                if (start && end && end > start) {
                    durationHint.textContent = pricing.rawDays.toFixed(1).replace('.0', '') + ' days selected';
                } else {
                    durationHint.textContent = 'Select your rental duration';
                }

                priceDays.textContent = pricing.rawDays.toFixed(1).replace('.0', '') + ' days';
                priceNumber.textContent = formatAmount(pricing.rent);
            }

            function updateSegmentStates() {
                root.querySelectorAll('.hd-segment__item').forEach((item) => {
                    const input = item.querySelector('input');
                    item.classList.toggle('is-active', !!(input && input.checked));
                });
                root.querySelectorAll('.hd-pay-option').forEach((item) => {
                    const input = item.querySelector('input');
                    item.classList.toggle('is-active', !!(input && input.checked));
                });
            }

            function updateLocationVisibility() {
                syncReturnSameUi();
                if (returnBlock && returnSameToggle) {
                    returnBlock.classList.toggle('is-hidden', returnSameToggle.checked);
                }
                if (returnSameToggle && returnSameToggle.checked) {
                    if (returnZone) {
                        returnZone.value = '';
                    }
                    clearRadioGroup(returnBranchInputs);
                }
                syncCustomAddressUi('delivery');
                syncCustomAddressUi('return');
                syncLocationSelection('pickup');
                syncLocationSelection('return');
            }

            function updatePaymentPanels() {
                if (paySplitPanel) {
                    paySplitPanel.classList.toggle('is-hidden', !isPayNowSelected());
                }
            }

            function updateWaiverState() {
                if (!waiverToggle || !waiverStateOn || !waiverStateOff) return;
                waiverStateOn.classList.toggle('is-hidden', !waiverToggle.checked);
                waiverStateOff.classList.toggle('is-hidden', waiverToggle.checked);
            }

            function getCheckedValue(name, fallback) {
                const input = root.querySelector('input[name="' + name + '"]:checked');
                return input ? input.value : fallback;
            }

            function updatePickupCards() {
                pickupCards.forEach((card) => {
                    const input = card.querySelector('input[type="radio"]');
                    card.classList.toggle('is-active', !!(input && input.checked));
                });
            }

            function updateSummary() {
                const pricing = getPricing();
                const roundedDays = pricing.rawDays.toFixed(1).replace('.0', '');
                summary.rentLabel.textContent = 'Rental Price for ' + roundedDays + ' Day' + (roundedDays === '1' ? '' : 's');
                summary.rent.textContent = formatAmount(pricing.rentAfterDiscounts);
                if (summary.rentOld) {
                    summary.rentOld.textContent = formatAmount(pricing.rent);
                }
                if (summary.rentOldWrap) {
                    summary.rentOldWrap.classList.toggle('is-hidden', pricing.totalSavings <= 0);
                }

                const extraMap = {
                    insurance: ['insuranceRow', 'insurance'],
                    driver: ['driverRow', 'driver'],
                    seat: ['seatRow', 'seat']
                };

                extraInputs.forEach((input) => {
                    const pair = extraMap[input.dataset.hdExtra];
                    if (!pair) return;
                    const row = summary[pair[0]];
                    const target = summary[pair[1]];
                    const amount = Number(input.dataset.hdExtraPrice || 0);
                    if (!row || !target) return;
                    row.classList.toggle('is-hidden', !input.checked);
                    target.textContent = formatAmount(amount);
                });

                if (summary.waiverRow) {
                    summary.waiverRow.classList.toggle('is-hidden', waiverToggle ? false : pricing.depositCharge <= 0);
                }
                if (summary.waiverLabel) {
                    summary.waiverLabel.textContent = waiverToggle && waiverToggle.checked ? 'Deposit Waiver Charges' : 'Deposit';
                }
                if (summary.waiver) {
                    summary.waiver.textContent = formatAmount(waiverToggle && waiverToggle.checked ? Number(waiverToggle.dataset.hdExtraPrice || 0) : pricing.depositCharge);
                }

                if (summary.deliveryRow) {
                    summary.deliveryRow.classList.toggle('is-hidden', pricing.delivery === 0);
                }
                if (summary.returnRow) {
                    summary.returnRow.classList.toggle('is-hidden', pricing.returnFee === 0);
                }
                if (summary.delivery) {
                    summary.delivery.textContent = formatAmount(pricing.delivery);
                }
                if (summary.returnCharge) {
                    summary.returnCharge.textContent = formatAmount(pricing.returnFee);
                }
                if (summary.subtotal) {
                    summary.subtotal.textContent = formatAmount(pricing.subtotal);
                }
                if (summary.vat) {
                    summary.vat.textContent = formatAmount(pricing.vat);
                }
                if (summary.total) {
                    summary.total.textContent = formatAmount(pricing.total);
                }
                if (summary.payNowDiscountRow) {
                    summary.payNowDiscountRow.classList.toggle('is-hidden', pricing.payNowDiscount <= 0);
                }
                if (summary.payNowDiscount) {
                    summary.payNowDiscount.textContent = formatAmount(pricing.payNowDiscount);
                }
                if (summary.discountRow) {
                    summary.discountRow.classList.toggle('is-hidden', pricing.promoDiscount <= 0);
                }
                if (summary.discount) {
                    summary.discount.textContent = formatAmount(pricing.promoDiscount);
                }
                if (summary.savingsRow) {
                    summary.savingsRow.classList.toggle('is-hidden', pricing.totalSavings <= 0);
                }
                if (summary.savings) {
                    summary.savings.textContent = formatAmount(pricing.totalSavings);
                }
                if (summary.savedInline) {
                    summary.savedInline.classList.toggle('is-hidden', pricing.totalSavings <= 0);
                }
                if (summary.savedInlineAmount) {
                    summary.savedInlineAmount.textContent = formatAmount(pricing.totalSavings);
                }

                const payNowAmount = pricing.total * 0.2;
                const payLaterAmount = pricing.total - payNowAmount;
                summary.payNow.textContent = formatAmount(payNowAmount);
                summary.payLater.textContent = formatAmount(payLaterAmount);

                if (paymentSavedNumber) {
                    paymentSavedNumber.textContent = formatAmount(pricing.payNowDiscount);
                }

                if (paymentTitle) {
                    paymentTitle.textContent = isPayNowSelected() ? '5% Pay Now Discount Applied' : 'Pay later on pickup';
                }

                if (paymentSub) {
                    paymentSub.innerHTML = isPayNowSelected()
                        ? 'You saved <span class="hd-price-inline"><img src="<?= $imagePath ?>darham.png" class="hd-price-inline__icon" alt="AED"><span data-hd-payment-saved-number>' + formatAmount(pricing.payNowDiscount) + '</span></span> on rental by paying online.'
                        : 'Turn on online payment to save <span class="hd-price-inline"><img src="<?= $imagePath ?>darham.png" class="hd-price-inline__icon" alt="AED"><span data-hd-payment-saved-number>' + formatAmount(pricing.rent * 0.05) + '</span></span> on rental.';
                }

                if (summary.pickupTime) {
                    summary.pickupTime.textContent = formatBookingDate(fromInput.value);
                }
                if (summary.dropoffTime) {
                    summary.dropoffTime.textContent = formatBookingDate(toInput.value);
                }
                if (pickupTitleNode instanceof HTMLElement) {
                    const titleText = hasZoneValue(deliveryZone) ? 'Delivery' : 'Pickup';
                    const icon = pickupTitleNode.querySelector('.hd-order-loc__icon');
                    pickupTitleNode.textContent = titleText;
                    if (icon) {
                        pickupTitleNode.prepend(icon);
                    }
                }
                if (dropoffTitleNode instanceof HTMLElement) {
                    const icon = dropoffTitleNode.querySelector('.hd-order-loc__icon');
                    dropoffTitleNode.textContent = 'Drop-off';
                    if (icon) {
                        dropoffTitleNode.prepend(icon);
                    }
                }

                const pickupSummary = buildPickupSummaryData();
                const dropoffSummary = buildDropoffSummaryData();

                if (summary.pickupBranch) {
                    renderSummaryBranch(summary.pickupBranch, pickupSummary);
                }
                if (summary.dropoffBranch) {
                    renderSummaryBranch(summary.dropoffBranch, dropoffSummary);
                }

                if (submitAmount) {
                    submitAmount.textContent = formatAmount(isPayNowSelected() ? payNowAmount : pricing.total);
                }
                if (submitCta) {
                    submitCta.classList.toggle('is-pay-now', isPayNowSelected());
                    submitCta.classList.toggle('is-pay-later', !isPayNowSelected());
                    if (submitLabel) {
                        submitLabel.textContent = isPayNowSelected() ? 'Pay Now Only ( 20% off )' : 'Book Now';
                    }
                }

                updatePaymentPanels();
            }

            function syncPromoUi() {
                if (promoApplyBtn) {
                    promoApplyBtn.textContent = state.promoCode ? 'Applied' : 'Apply';
                }
                if (promoApplyBtn) {
                    promoApplyBtn.disabled = !!state.promoCode;
                }
                if (promoRemoveBtn) {
                    promoRemoveBtn.classList.toggle('is-hidden', !state.promoCode);
                }
                if (promoInput) {
                    promoInput.readOnly = !!state.promoCode;
                }
                if (promoNote) {
                    promoNote.textContent = state.promoCode
                        ? 'Promo applied: ' + state.promoCode
                        : 'No promo applied';
                }
            }

            function clearAppliedPromo(silent) {
                if (!state.promoCode && !state.promoDiscount) return;
                state.promoCode = '';
                state.promoDiscount = 0;
                if (promoInput) {
                    promoInput.value = '';
                }
                syncPromoUi();
                updateSummary();
                if (!silent) {
                    showToast('success', 'Promo removed', 'Applied promo code has been removed.');
                }
            }

            function buildBookingPayload() {
                const pricing = getPricing();
                const start = fromInput && fromInput.value ? new Date(fromInput.value) : null;
                const end = toInput && toInput.value ? new Date(toInput.value) : null;
                const payNowSelected = isPayNowSelected();
                const pickupSummary = buildPickupSummaryData();
                const dropoffSummary = buildDropoffSummaryData();
                const pickupBranch = pickupSummary.primary;
                const dropoffBranch = dropoffSummary.primary;
                const phoneNumber = [
                    phoneCountryHidden instanceof HTMLInputElement ? phoneCountryHidden.value.trim() : '',
                    phoneInput instanceof HTMLInputElement ? phoneInput.value.trim() : ''
                ].filter(Boolean).join(' ').trim();
                const hasDeliveryZone = hasZoneValue(deliveryZone);
                const hasReturnZone = hasZoneValue(returnZone);
                const returnSame = !!(returnSameToggle && returnSameToggle.checked);
                const deliveryCustomAddress = getCustomAddressValue('delivery');
                const returnCustomAddress = getCustomAddressValue('return');
                const roundedDays = pricing.rawDays.toFixed(1).replace('.0', '');
                const payNowAmount = pricing.total * 0.2;
                const payLaterAmount = pricing.total - payNowAmount;

                let deliveryLocation = null;
                let deliveryCustomAddressValue = null;
                let deliveryLocationPrice = 0;
                let selfPickupLocation = null;
                let selfPickupAddress = null;
                let returnLocation = null;
                let returnCustomAddressValue = null;
                let returnLocationPrice = 0;
                let selfReturnLocation = null;
                let selfReturnAddress = null;

                if (hasDeliveryZone) {
                    deliveryLocation = deliveryZone.value || '';
                    deliveryCustomAddressValue = deliveryCustomAddress || null;
                    deliveryLocationPrice = pricing.delivery;
                } else if (pickupBranch) {
                    selfPickupLocation = pickupBranch;
                    selfPickupAddress = pickupBranch;
                }

                const selfPickupLocationId = hasDeliveryZone ? '' : getSelectedSpeedLocationId('hd_pickup_branch');

                if (returnSame) {
                    if (hasDeliveryZone) {
                        returnLocation = deliveryZone.value || '';
                        returnLocationPrice = pricing.returnFee;
                    } else if (pickupBranch) {
                        selfReturnLocation = pickupBranch;
                        selfReturnAddress = pickupBranch;
                    }
                } else if (hasReturnZone) {
                    returnLocation = returnZone.value || '';
                    returnCustomAddressValue = returnCustomAddress || null;
                    returnLocationPrice = pricing.returnFee;
                } else if (dropoffBranch) {
                    selfReturnLocation = dropoffBranch;
                    selfReturnAddress = dropoffBranch;
                }

                const selfReturnLocationId = (hasReturnZone || returnSame) ? (returnSame ? selfPickupLocationId : getSelectedSpeedLocationId('hd_return_branch')) : '';

                const payload = {
                    name: submitForm.querySelector('input[name="name"]')?.value.trim() || '',
                    number: phoneNumber,
                    email: submitForm.querySelector('input[name="email"]')?.value.trim() || '',
                    start_date: start && !Number.isNaN(start.getTime()) ? start.getFullYear() + '-' + pad2(start.getMonth() + 1) + '-' + pad2(start.getDate()) : '',
                    end_date: end && !Number.isNaN(end.getTime()) ? end.getFullYear() + '-' + pad2(end.getMonth() + 1) + '-' + pad2(end.getDate()) : '',
                    start_time: start && !Number.isNaN(start.getTime()) ? pad2(start.getHours()) + ':' + pad2(start.getMinutes()) : '',
                    end_time: end && !Number.isNaN(end.getTime()) ? pad2(end.getHours()) + ':' + pad2(end.getMinutes()) : '',
                    rental_type: state.period,
                    rental_price: pricing.rent,
                    rental_duration: roundedDays + ' ' + (roundedDays === '1' ? 'day' : 'days'),
                    resident_tourist: getCustomerType(),
                    full_insurance: isExtraChecked('insurance'),
                    full_insurance_price: isExtraChecked('insurance') ? getExtraPrice('insurance') : 0,
                    additional_driver: isExtraChecked('driver'),
                    additional_driver_charges: isExtraChecked('driver') ? getExtraPrice('driver') : 0,
                    baby_seat: isExtraChecked('seat'),
                    baby_seat_price: isExtraChecked('seat') ? getExtraPrice('seat') : 0,
                    deposit_waiver: waiverToggle && waiverToggle.checked ? 'Waiver' : 'Deposit',
                    deposit_waiver_price: waiverToggle && waiverToggle.checked ? getExtraPrice('waiver') : pricing.depositCharge,
                    delivery_location: deliveryLocation,
                    delivery_custom_address: deliveryCustomAddressValue,
                    delivery_location_price: deliveryLocationPrice,
                    different_city_dropoff_fee: 0,
                    self_pickup_location: selfPickupLocation,
                    self_pickup_location_id: selfPickupLocationId,
                    self_pickup_address: selfPickupAddress,
                    return_location: returnLocation,
                    return_custom_address: returnCustomAddressValue,
                    return_location_price: returnLocationPrice,
                    self_return_location: selfReturnLocation,
                    self_return_location_id: selfReturnLocationId,
                    self_return_address: selfReturnAddress,
                    coupon_code: state.promoCode || '',
                    coupon_amount: pricing.promoDiscount,
                    pay_now_discount: pricing.payNowDiscount,
                    discount_percentage: payNowSelected ? 5 : 0,
                    subtotal: pricing.subtotal,
                    vat_percentage: 5,
                    vat_amount: pricing.vat,
                    total_amount: pricing.total,
                    payment_flow: payNowSelected ? 'now' : 'later',
                    'pay_now_20%_to_Reserve': payNowSelected ? payNowAmount : 0,
                    'pay_at_pickup_80%': payNowSelected ? payLaterAmount : pricing.total,
                    paid_id: '',
                    paid_date: '',
                    paid_status: '',
                    paid_via: '',
                    contact_preference: submitForm.querySelector('[data-hd-no-whatsapp]')?.checked ? 'phone' : 'whatsapp',
                    term_22_years: !!(confirmAge && confirmAge.checked),
                    term_6_month_experience: !!(confirmDriving && confirmDriving.checked),
                    vehicle_group_id: bookingMeta.vehicle_group_id,
                    tariff_group_id: bookingMeta.tariff_group_id,
                    send_booking_id: '',
                    notes: '',
                    speed_response: ''
                };

                setPostField('self_pickup_location_id', payload.self_pickup_location_id);
                setPostField('self_return_location_id', payload.self_return_location_id);
                setPostField('vehicle_group_id', payload.vehicle_group_id);
                setPostField('tariff_group_id', payload.tariff_group_id);

                const notesPayload = {
                        source: 'newwebsite_haladrive',
                        car_id: <?= json_encode($car['id'] ?? null); ?>,
                        car_slug: <?= json_encode($slug); ?>,
                        car_name: <?= json_encode($carName); ?>,
                        pickup_branch: pickupBranch,
                        dropoff_branch: dropoffBranch
                };

                Object.keys(payload).forEach(function (key) {
                    if (key === 'notes') return;
                    notesPayload[key] = payload[key];
                });

                payload.notes = JSON.stringify(notesPayload);

                return payload;
            }

            function applyDefaultRangeForPeriod(period) {
                const now = new Date();
                const base = new Date(now.getTime() + 60 * 60 * 1000);
                if (!fromInput || !toInput) return;

                if (period === 'daily') {
                    fromInput.value = toLocalInputValue(base);
                    toInput.value = toLocalInputValue(new Date(base.getTime() + 24 * 60 * 60 * 1000));
                    return;
                }
                if (period === 'weekly') {
                    fromInput.value = toLocalInputValue(base);
                    toInput.value = toLocalInputValue(new Date(base.getTime() + 7 * 24 * 60 * 60 * 1000));
                    return;
                }
                if (period === 'monthly') {
                    fromInput.value = toLocalInputValue(base);
                    toInput.value = toLocalInputValue(new Date(base.getTime() + 30 * 24 * 60 * 60 * 1000));
                }
            }

            function setActivePeriod(period, shouldApplyDefaultRange) {
                const nextPeriod = period === 'weekly' || period === 'monthly' ? period : 'daily';
                const activeTab = tabs.find((tab) => tab.dataset.hdPeriod === nextPeriod);

                tabs.forEach((item) => item.classList.toggle('is-active', item === activeTab));

                state.period = nextPeriod;
                state.price = parseAmount(state.prices[nextPeriod] || 0);

                if (shouldApplyDefaultRange) {
                    applyDefaultRangeForPeriod(nextPeriod);
                }
            }

            function syncPeriodFromDuration(days) {
                const daysCount = Number(days || 0);
                if (!Number.isFinite(daysCount) || daysCount <= 0) return;

                if (daysCount <= 6) {
                    setActivePeriod('daily', false);
                    return;
                }
                if (daysCount >= 7 && daysCount < 30) {
                    setActivePeriod('weekly', false);
                    return;
                }
                setActivePeriod('monthly', false);
            }

            tabs.forEach((tab) => {
                tab.addEventListener('click', function () {
                    clearAppliedPromo(true);
                    setActivePeriod(tab.dataset.hdPeriod, true);
                    updateDurationUI();
                    updateSummary();
                });
            });

            [fromInput, toInput].forEach((input) => {
                if (!input) return;
                input.addEventListener('change', function () {
                    if (fromInput && toInput && fromInput.value && toInput.value && new Date(toInput.value) <= new Date(fromInput.value)) {
                        const newEnd = new Date(new Date(fromInput.value).getTime() + 24 * 60 * 60 * 1000);
                        const pad = (n) => String(n).padStart(2, '0');
                        toInput.value = newEnd.getFullYear() + '-' + pad(newEnd.getMonth() + 1) + '-' + pad(newEnd.getDate()) + 'T' + pad(newEnd.getHours()) + ':' + pad(newEnd.getMinutes());
                    }
                    clearAppliedPromo(true);
                    const pricing = getPricing();
                    syncPeriodFromDuration(pricing.rawDays);
                    updateDurationUI();
                    updateSummary();
                });
            });

            root.querySelectorAll('input[name="hd_customer_type"]').forEach((input) => {
                input.addEventListener('change', function () {
                    updateSegmentStates();
                    updateSummary();
                });
            });

            paymentFlowInputs.forEach((input) => {
                input.addEventListener('change', function () {
                    clearAppliedPromo(true);
                    updateSegmentStates();
                    if (paymentToggle) {
                        paymentToggle.checked = input.value === 'pay_now' && input.checked;
                    }
                    updateSummary();
                });
            });

            if (paymentToggle) {
                paymentToggle.addEventListener('change', function () {
                    clearAppliedPromo(true);
                    setPaymentFlow(paymentToggle.checked ? 'pay_now' : 'pay_later');
                    updateSegmentStates();
                    updateSummary();
                });
            }

            extraInputs.forEach((input) => {
                input.addEventListener('change', function () {
                    clearAppliedPromo(true);
                    updateWaiverState();
                    updateSummary();
                });
            });

            [deliveryZone, returnZone, returnSameToggle].forEach((input) => {
                if (!input) return;
                input.addEventListener('change', function () {
                    clearAppliedPromo(true);
                    if (input === deliveryZone && hasZoneValue(deliveryZone)) {
                        clearRadioGroup(pickupBranchInputs);
                        updatePickupCards();
                    }
                    if (input === returnZone && hasZoneValue(returnZone)) {
                        clearRadioGroup(returnBranchInputs);
                        updatePickupCards();
                    }
                    updateLocationVisibility();
                    updateSummary();
                });
            });

            [deliveryZone, returnZone].forEach((select) => {
                if (!(select instanceof HTMLSelectElement)) return;
                select.addEventListener('input', function () {
                    clearAppliedPromo(true);
                    if (select === deliveryZone && hasZoneValue(deliveryZone)) {
                        clearRadioGroup(pickupBranchInputs);
                    }
                    if (select === returnZone && hasZoneValue(returnZone)) {
                        clearRadioGroup(returnBranchInputs);
                    }
                    updateLocationVisibility();
                    updateSummary();
                });
            });

            [deliveryCustomAddressInput, returnCustomAddressInput].forEach((input) => {
                if (!(input instanceof HTMLInputElement)) return;
                input.addEventListener('input', function () {
                    updateSummary();
                });
            });

            root.addEventListener('change', function (event) {
                const target = event.target;
                if (!(target instanceof HTMLInputElement)) return;

                if (target.name === 'hd_pickup_branch' || target.name === 'hd_return_branch') {
                    if (!target.checked) return;
                    clearAppliedPromo(true);
                    if (target.name === 'hd_pickup_branch' && deliveryZone) {
                        deliveryZone.value = '';
                        syncCustomSelectUi(deliveryZone);
                    }
                    if (target.name === 'hd_return_branch' && returnZone) {
                        returnZone.value = '';
                        syncCustomSelectUi(returnZone);
                    }
                    updateLocationVisibility();
                    updatePickupCards();
                    updateSummary();
                }
            });

            root.addEventListener('click', function (event) {
                const target = event.target;
                if (!(target instanceof HTMLElement)) return;
                if (target.closest('[data-hd-back-to-booking]')) {
                    event.preventDefault();
                    openBookingFormStep();
                }
                if (target.closest('[data-hd-pay-now-only]')) {
                    event.preventDefault();
                    submitBooking();
                }
            });

            customSelects.forEach(function (wrap) {
                const nativeSelect = wrap.nextElementSibling;
                if (nativeSelect instanceof HTMLSelectElement) {
                    initCustomSelect(nativeSelect);
                }
            });

            document.addEventListener('click', function (event) {
                const target = event.target;
                if (!(target instanceof Node)) return;
                const clickedInside = customSelects.some(function (wrap) {
                    return wrap.contains(target);
                });
                if (!clickedInside) {
                    closeCustomSelects();
                }
            });

            continueBtn.addEventListener('click', function () {
                if (!fromInput.value || !toInput.value) {
                    showToast('error', 'Missing dates', 'Please select pickup and return date/time.');
                    return;
                }

                if (new Date(toInput.value) <= new Date(fromInput.value)) {
                    showToast('error', 'Invalid return', 'Return date/time must be after pickup date/time.');
                    return;
                }

                if (!hasZoneValue(deliveryZone) && !hasCheckedRadio(pickupBranchInputs)) {
                    showToast('error', 'Pickup missing', 'Please select a delivery zone or self-pick-up location.');
                    return;
                }

                if (returnSameToggle && !returnSameToggle.checked && !hasZoneValue(returnZone) && !hasCheckedRadio(returnBranchInputs)) {
                    showToast('error', 'Drop-off missing', 'Please select a return location or self-return location.');
                    return;
                }

                updateSummary();
                formStep.classList.add('is-hidden');
                summaryStep.classList.remove('is-hidden');
                if (heroHead) {
                    heroHead.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });

            backBtn.addEventListener('click', function () {
                summaryStep.classList.add('is-hidden');
                formStep.classList.remove('is-hidden');
            });

            promoApplyBtn.addEventListener('click', async function () {
                const code = (promoInput.value || '').trim();
                if (!code) {
                    showToast('error', 'Promo required', 'Please enter a promo code first.');
                    return;
                }

                const pricing = getPricing({ ignorePromo: true });
                if (!(pricing.total > 0)) {
                    showToast('error', 'Amount unavailable', 'Please set rental details before applying promo.');
                    return;
                }

                const originalLabel = promoApplyBtn.textContent || 'Apply';
                promoApplyBtn.disabled = true;
                promoApplyBtn.textContent = 'Applying...';

                try {
                    const response = await fetchWithTimeout(<?= json_encode($promoApplyEndpoint); ?>, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({
                            code: code,
                            amount: pricing.total
                        })
                    });

                    const data = await response.json().catch(function () {
                        return {};
                    });

                    if (!response.ok || !data || data.status !== true || !data.data) {
                        clearAppliedPromo(true);
                        updateSummary();
                        showToast('error', 'Promo invalid', String((data && (data.message || data.error)) || 'Could not apply promo code.'));
                        return;
                    }

                    state.promoCode = String(data.data.code || code).toUpperCase();
                    state.promoDiscount = parseAmount(Number(data.data.discount_amount || 0));
                    promoInput.value = state.promoCode;
                    syncPromoUi();
                    updateSummary();
                    showToast('success', 'Promo applied', String(data.message || 'Promo code applied successfully.'));
                } catch (error) {
                    clearAppliedPromo(true);
                    updateSummary();
                    showToast(
                        'error',
                        error && error.name === 'AbortError' ? 'Request timeout' : 'Network error',
                        error && error.name === 'AbortError'
                            ? 'Promo request timed out. Please try again.'
                            : 'Could not apply promo code. Please try again.'
                    );
                } finally {
                    if (!state.promoCode) {
                        promoApplyBtn.disabled = false;
                        promoApplyBtn.textContent = originalLabel;
                    }
                    syncPromoUi();
                }
            });

            if (promoRemoveBtn) {
                promoRemoveBtn.addEventListener('click', function () {
                    clearAppliedPromo(false);
                });
            }

            root.querySelectorAll('[data-hd-modal-template]').forEach((button) => {
                button.addEventListener('click', function () {
                    if (!modal || !modalTitle || !modalBody) return;
                    const template = document.getElementById(button.dataset.hdModalTemplate);
                    if (!template) return;
                    modalTitle.textContent = button.dataset.hdModalTitle || '';
                    modalBody.innerHTML = template.innerHTML;
                    modal.classList.remove('is-hidden');
                });
            });

            if (modal) {
                modal.querySelectorAll('[data-hd-modal-close]').forEach((button) => {
                    button.addEventListener('click', function () {
                        modal.classList.add('is-hidden');
                    });
                });
            }

            submitForm.addEventListener('submit', function (event) {
                event.preventDefault();

                const button = submitForm.querySelector('.hd-submit-btn');
                const payload = buildBookingPayload();

                if (confirmAge && !confirmAge.checked) {
                    showToast('error', 'Age confirmation', 'Please confirm your age is above 22 years.');
                    return;
                }

                if (confirmDriving && !confirmDriving.checked) {
                    showToast('error', 'Driving confirmation', 'Please confirm your driving experience is above 6 months.');
                    return;
                }

                if (!payload.name || !payload.email || !payload.number) {
                    showToast('error', 'Missing details', 'Please enter name, email and phone number.');
                    return;
                }

                button.classList.add('loading');
                button.disabled = true;

                fetchWithTimeout(<?= json_encode($bookingSubmitEndpoint); ?>, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify(payload)
                })
                    .then((response) => response.json())
                    .then((data) => {
                        if (data && data.status === true) {
                            showToast('success', 'Booking submitted', data.message || 'Booking request submitted successfully.');
                            submitForm.reset();
                            state.promoCode = '';
                            state.promoDiscount = 0;
                            syncPromoUi();
                            extraInputs.forEach((input) => { input.checked = false; });
                            if (deliveryZone) deliveryZone.value = '';
                            if (returnZone) returnZone.value = '';
                            if (returnSameToggle) returnSameToggle.checked = true;
                            if (paymentToggle) paymentToggle.checked = true;
                            const defaultPickup = pickupBranchInputs[0] || null;
                            const defaultReturnPickup = returnBranchInputs[0] || null;
                            if (defaultPickup) defaultPickup.checked = true;
                            if (defaultReturnPickup) defaultReturnPickup.checked = true;
                            if (phoneInput instanceof HTMLInputElement) {
                                phoneInput.value = '';
                            }
                            if (phoneCountryHidden instanceof HTMLInputElement && phoneIti && typeof phoneIti.getSelectedCountryData === 'function') {
                                const country = phoneIti.getSelectedCountryData();
                                phoneCountryHidden.value = country && country.dialCode ? '+' + country.dialCode : '';
                            }
                            setPaymentFlow('pay_now');
                            updateSegmentStates();
                            updatePickupCards();
                            updateLocationVisibility();
                            updateWaiverState();
                            applyDefaultRangeForPeriod(state.period);
                            updateDurationUI();
                            updateSummary();
                            summaryStep.classList.add('is-hidden');
                            formStep.classList.remove('is-hidden');
                        } else {
                            let message = data && (data.message || data.error) ? String(data.message || data.error) : 'Error submitting booking request.';
                            if (data && data.data && data.data.errors) {
                                const firstField = Object.keys(data.data.errors)[0];
                                if (firstField && Array.isArray(data.data.errors[firstField]) && data.data.errors[firstField][0]) {
                                    message = String(data.data.errors[firstField][0]);
                                }
                            }
                            showToast('error', 'Booking failed', message);
                        }
                    })
                    .catch(() => {
                        showToast('error', 'Server error', 'A server error occurred.');
                    })
                    .finally(() => {
                        button.classList.remove('loading');
                        button.disabled = false;
                    });
            });

            setPaymentFlow(<?= json_encode($initialPaymentFlow); ?>);
            setActivePeriod(state.period, true);
            syncPromoUi();
            updateSegmentStates();
            void loadSpeedLocations();
            updatePickupCards();
            updateLocationVisibility();
            updateWaiverState();
            updateDurationUI();
            updateSummary();
        })();
    </script>
 <?php
    include_once('footer.php');
    exit;
}

// -----------------------------------------
// PAGE WITHOUT SLUG → SHOW MAIN PAGE
// -----------------------------------------
if(isset($_COOKIE['sort'])){
    $_GET['sort'] = $_COOKIE['sort'];
}
if(isset($_COOKIE['stock'])){
    $_GET['stock'] = $_COOKIE['stock'];
}
if(isset($_COOKIE['id'])){
    $_GET['id'] = $_COOKIE['id'];
}

$sort = $_GET['sort'] ?? null;
$id = $_GET['id'] ?? null;
$stock = $_GET['stock'] ?? null;

$currentPage = $_GET['page'] ?? 1;

try {
    $carContent = $api->loadData('car', 'main', ['sort' => $sort, 'id' => $id, 'stock' => $stock, 'page' => $currentPage]);
    if ($carContent['success']) {
        $carContentData = $carContent['data']["data"];

        $titleKey = "title_" . $lang;
        $descKey  = "description_" . $lang;

        $meta_title = $carContentData["meta_data"][$titleKey] ?? '';
        $meta_desc  = $carContentData["meta_data"][$descKey] ?? '';

        if (!empty($currentPage) && $currentPage > 1) {
            $meta_title .= " - Page " . $currentPage;
            $meta_desc  .= " (Page " . $currentPage . ")";
        }

    }
} catch (Exception $e) {
    echo "Error loading car list: " . $e->getMessage();
}

include_once('header.php');

$banner_image = "$imagePath/about/top-banner.webp";
$banner_title = $messages['carsBannerHeading'];
$banner_subtitle = $messages['carsBannerPera'];
$heading = 'h1';
if (!empty($currentPage) && $currentPage > 1) {
    $banner_title .= "- Page " . $currentPage;
}
include_once('banner.php');
?>

    <section class="relative py-16 max-[1024px]:py-10">
        <div class="w-[80%] max-[1024px]:w-[90%] mx-auto">
            <div class="grid grid-cols-4 max-[1024px]:grid-cols-1 gap-10">
                <div class="h-fit col-span-1">
                    <div class="text-black text-center bg-[#f1f4f8] p-4 mb-6">
                        <div class="mb-4 text-[1.3rem]"><?= $messages['price'] ?></div>
                        <div class="flex flex-col gap-2 font-semibold text-[12px]">
                            <a 
                                href="javascript:void(0);" 
                                onClick="
                                    document.cookie = 'sort=;';
                                    document.cookie = 'id=;';
                                    document.cookie = 'stock=;';
                                    location.reload();
                                " 
                                rel="nofollow" 
                                class="bg-white border border-[#b8101f] py-2 px-4"
                            >
                                <?= $messages['default'] ?>
                            </a>
                            <a 
                                href="javascript:void(0);" 
                                onClick="
                                    document.cookie = 'sort=price_asc';
                                    document.cookie = 'stock=;';
                                    document.cookie = 'id=;';
                                    location.reload();
                                " 
                                rel="nofollow" 
                                class="<?= ($_GET['sort'] ?? '') == 'price_asc' ? 'bg-[#ff000d] text-white' : 'bg-white' ?> border border-[#E02D3C] py-2 px-4"
                            >
                                <?= $messages['lowtohigh'] ?>
                            </a>
                            <a 
                                href="javascript:void(0);" 
                                onClick="
                                    document.cookie = 'sort=price_desc';
                                    document.cookie = 'stock=;';
                                    document.cookie = 'id=;';
                                    location.reload();
                                " 
                                rel="nofollow" 
                                class="<?= ($_GET['sort'] ?? '') == 'price_desc' ? 'bg-[#ff000d] text-white' : 'bg-white' ?> border border-[#E02D3C] py-2 px-4"
                            >
                                <?= $messages['hightolow'] ?>
                            </a>
                        </div>
                    </div>
                    <div class="text-black text-center bg-[#f1f4f8] p-4 mb-6">
                        <div class="mb-4 text-[1.3rem]"><?= $messages['typesofcars'] ?></div>
                        <div class="grid grid-cols-2 gap-2 font-semibold text-[12px]">
                            <a 
                                href="javascript:void(0);" 
                                onClick="
                                    document.cookie = 'sort=Economy';
                                    document.cookie = 'id=1';
                                    document.cookie = 'stock=;';
                                    location.reload();
                                " 
                                rel="nofollow" 
                                class="<?= ($_GET['sort'] ?? '') == 'Economy' ? 'bg-[#ff000d] text-white' : 'bg-white' ?> border border-[#E02D3C] py-2 px-4"
                            >
                                <?= $messages['economy'] ?>
                            </a>
                            <a 
                                href="javascript:void(0);" 
                                onClick="
                                    document.cookie = 'sort=suv';
                                    document.cookie = 'id=2';
                                    document.cookie = 'stock=;';
                                    location.reload();
                                " 
                                rel="nofollow" 
                                class="<?= ($_GET['sort'] ?? '') == 'suv' ? 'bg-[#ff000d] text-white' : 'bg-white' ?> border border-[#E02D3C] py-2 px-4"
                            >
                                <?= $messages['suv'] ?>
                            </a>
                            <a 
                                href="javascript:void(0);" 
                                onClick="
                                    document.cookie = 'sort=Midsize';
                                    document.cookie = 'id=3';
                                    document.cookie = 'stock=;';
                                    location.reload();
                                " 
                                rel="nofollow" 
                                class="<?= ($_GET['sort'] ?? '') == 'Midsize' ? 'bg-[#ff000d] text-white' : 'bg-white' ?> border border-[#E02D3C] py-2 px-4"
                            >
                                <?= $messages['midsize'] ?>
                            </a>
                            <a 
                                href="javascript:void(0);" 
                                onClick="
                                    document.cookie = 'sort=Featured';
                                    document.cookie = 'id=4';
                                    document.cookie = 'stock=;';
                                    location.reload();
                                " 
                                rel="nofollow" 
                                class="<?= ($_GET['sort'] ?? '') == 'Featured' ? 'bg-[#ff000d] text-white' : 'bg-white' ?> border border-[#E02D3C] py-2 px-4"
                            >
                                <?= $messages['featured'] ?>
                            </a>
                            <a 
                                href="javascript:void(0);" 
                                onClick="
                                    document.cookie = 'sort=Crossover';
                                    document.cookie = 'id=5';
                                    document.cookie = 'stock=;';
                                    location.reload();
                                " 
                                rel="nofollow" 
                                class="<?= ($_GET['sort'] ?? '') == 'Crossover' ? 'bg-[#ff000d] text-white' : 'bg-white' ?> border border-[#E02D3C] py-2 px-4 col-span-2"
                            >
                                <?= $messages['crossover'] ?>
                            </a>
                        </div>
                    </div>
                    <div class="text-black text-center bg-[#f1f4f8] p-4 mb-6">
                        <div class="mb-4 text-[1.3rem]"><?= $messages['availability'] ?></div>
                        <div class="grid grid-cols-1 gap-2 font-semibold text-[12px]">
                            <a 
                                href="javascript:void(0);" 
                                onClick="
                                    document.cookie = 'stock=in_stock';
                                    location.reload();
                                " 
                                rel="nofollow" 
                                class="<?= ($_GET['stock'] ?? '') == 'in_stock' ? 'bg-[#ff000d] text-white' : 'bg-white' ?> border border-[#E02D3C] py-2 px-4"
                                >
                                <?= $messages['instock'] ?>
                            </a>
                            <a 
                            href="javascript:void(0);" 
                            onClick="
                            document.cookie = 'stock=out_of_stock';
                            location.reload();
                            " 
                            rel="nofollow" 
                                class="<?= ($_GET['stock'] ?? '') == 'out_of_stock' ? 'bg-[#ff000d] text-white' : 'bg-white' ?> border border-[#E02D3C] py-2 px-4"
                            >
                                <?= $messages['outofstock'] ?>
                            </a>
                            <a 
                                href="javascript:void(0);" 
                                onClick="
                                    document.cookie = 'sort=;';
                                    document.cookie = 'id=;';
                                    document.cookie = 'stock=;';
                                    location.reload();
                                " 
                                rel="nofollow" 
                                class="bg-[#ff000d] text-white uppercase py-3 text-[1rem]"
                            >
                                <?= $messages['reset'] ?>
                            </a>
                        </div>
                    </div>
                    <div class="text-black text-center bg-[#f1f4f8] p-4">
                        <div class="mb-4 text-[1.3rem]"><?= $messages['sortbybrand'] ?></div>
                        <div class="grid grid-cols-1 gap-2 text-[12px]">
                            <button id="dropdownUsersButton" data-dropdown-toggle="dropdownUsers"
                                data-dropdown-placement="bottom"
                                class="text-[#939393] bg-white focus:outline-none font-medium rounded-lg text-[1rem] px-5 py-2.5 text-center inline-flex items-center justify-center"
                                type="button"><?= $messages['sortbybrand'] ?> <svg class="w-2.5 h-2.5 ms-3" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m1 1 4 4 4-4" />
                                </svg>
                            </button>

                            <!-- Dropdown menu -->
                            <div id="dropdownUsers" class="z-[99999] relative hidden bg-white rounded-lg shadow-sm dark:bg-gray-700">
                                <ul class="h-48 py-2 overflow-y-auto text-gray-700 dark:text-gray-200 !bg-white"
                                    aria-labelledby="dropdownUsersButton">
                                    <?php foreach($carContentData["brands"] as $brands): ?>
                                        <li>
                                            <a href="carsbrands/<?php echo $brands["slug"]; ?>"
                                            class="flex items-center px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">
                                            <img class="w-6 h-6 me-2 rounded-full object-contain"
                                                src="<?php echo $brands["logo_url"]; ?>" alt="<?php echo $brands["name_{$lang}"]; ?>">
                                            <?php echo $brands["name_{$lang}"]; ?>
                                        </a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-span-3 max-[1024px]:col-span-1 flex flex-col gap-10">
                    <?php if (empty($carContentData["cars"]["data"])): ?>
                        <div class="text-[2rem] font-bold leading-[1] text-center syne">
                            <?= $messages['nocarsfound'] ?? 'No Cars Found' ?>
                        </div>
                    <?php else: ?>

                        <?php foreach($carContentData["cars"]["data"] as $car): ?>
                            <div class="relative p-4 rounded-[10px] shadow-[4px_7px_15px_rgba(75,75,77,.25)]">
                                <div class="flex items-center justify-between mb-2">
                                    <?php if (!empty($car["stock"]) && $car["stock"] == "Yes"): ?>
                                        <div class="bg-[#daffda] text-[#29a71a] border border-[#29a71a] rounded-full text-[.8rem] px-2 py-1">
                                            <?= $messages['instock'] ?>
                                        </div>
                                    <?php else: ?>
                                        <div class="bg-[#d60000] text-white border border-[#d60000] rounded-full text-[.8rem] px-2 py-1">
                                            <?= $messages['outofstock'] ?>
                                        </div>
                                    <?php endif; ?>
                                    <img width="0" hight='0' src="<?php echo $car["brand"]["logo_url"]; ?>" class="w-16" alt="brand logo">
                                </div>
                                <div class="flex items-center max-[1024px]:flex-col gap-4">
                                    <a href='cars/<?php echo $car["slug"]; ?>' class="w-[50%] max-[1024px]:w-full">
                                        <img src="<?php echo $car["image_url"]; ?>" alt="image_url">
                                        <div class="flex gap-2 justify-center items-center mt-3">
                                            <div
                                                class="text-black bg-[#f2fdff] text-[10px] -skew-x-12 border-2 text-center border-[#d1eaee] cursor-pointer hover:text-white hover:bg-[#ff000d] duration-300 py-1 px-2">
                                                <div class=""><?= $messages['daily'] ?></div>
                                                <div class="font-bold car-card-price inline-flex items-center gap-1"><img src="<?= $imagePath ?>darham.png" class="h-[1em] w-auto object-contain" alt="AED"><?php echo $car["price_daily"]; ?></div>
                                            </div>
                                            <div
                                                class="text-black bg-[#f2fdff] text-[10px] -skew-x-12 border-2 text-center border-[#d1eaee] cursor-pointer hover:text-white hover:bg-[#ff000d] duration-300 py-1 px-2">
                                                <div class=""><?= $messages['weekly'] ?></div>
                                                <div class="font-bold car-card-price inline-flex items-center gap-1"><img src="<?= $imagePath ?>darham.png" class="h-[1em] w-auto object-contain" alt="AED"><?php echo $car["price_weekly"]; ?></div>
                                            </div>
                                            <div
                                                class="text-black bg-[#f2fdff] text-[10px] -skew-x-12 border-2 text-center border-[#d1eaee] cursor-pointer hover:text-white hover:bg-[#ff000d] duration-300 py-1 px-2">
                                                <div class=""><?= $messages['monthly'] ?></div>
                                                <div class="font-bold car-card-price inline-flex items-center gap-1"><img src="<?= $imagePath ?>darham.png" class="h-[1em] w-auto object-contain" alt="AED"><?php echo $car["price_monthly"]; ?></div>
                                            </div>
                                        </div>
                                    </a>
                                    <div class="w-[50%] max-[1024px]:w-full">
                                        <div class="text-[2rem] font-bold leading-[1] max-[1024px]:text-center syne"><?php echo $car["name_{$lang}"]; ?></div>
                                        <ul class="list-disc text-[#939393] text-[11px] mt-4 max-[1024px]:mx-auto max-[1024px]:w-fit">
                                            <li class="flex items-center gap-2 ">
                                                <img src="<?= $imagePath ?>cars/star.svg" class="w-3" alt="star">
                                                <div class=""><?= $messages['engine'] ?> 1.5 L</div>
                                            </li>
                                            <li class="flex items-center gap-2 ">
                                                <img src="<?= $imagePath ?>cars/star.svg" class="w-3" alt="star">
                                                <div class=""><?= $messages['bluetooth'] ?> Yes</div>
                                            </li>
                                            <li class="flex items-center gap-2 ">
                                                <img src="<?= $imagePath ?>cars/star.svg" class="w-3" alt="star">
                                                <div class=""><?= $messages['control'] ?> Yes</div>
                                            </li>
                                            <li class="flex items-center gap-2 ">
                                                <img src="<?= $imagePath ?>cars/star.svg" class="w-3" alt="star">
                                                <div class=""><?= $messages['luggage'] ?> Yes</div>
                                            </li>
                                        </ul>
                                        <div class="mt-4 grid grid-cols-2 gap-3 max-w-[430px] max-[1024px]:mx-auto">

                                            <!-- Book Now -->
                                            <a href="cars/<?php echo $car['slug']; ?>?payment_flow=now"
                                                class="group flex items-center justify-center gap-2 book-now-btn bg-[#FF000D] text-white px-4 py-3 rounded-xl text-[15px] font-semibold shadow-md hover:bg-black hover:text-white hover:shadow-xl hover:scale-[1.02] transition-all duration-300">

                                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path d="M3 11l9-8 9 8"></path>
                                                    <path d="M5 10v10h14V10"></path>
                                                </svg>

                                                <span class="leading-tight">
                                                    Book Now
                                                    <span class="block text-[11px] font-medium opacity-90">
                                                        (5% OFF)
                                                    </span>
                                                </span>
                                            </a>

                                            <!-- Pay Later -->
                                            <a href="cars/<?php echo $car['slug']; ?>?payment_flow=later"
                                                class="group flex items-center justify-center gap-2 book-now-btn bg-white border border-gray-300 text-black px-4 py-3 rounded-xl text-[15px] font-semibold shadow-md  hover:text-white hover:shadow-xl hover:scale-[1.02] transition-all duration-300">

                                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <rect x="2" y="5" width="20" height="14" rx="2"></rect>
                                                    <path d="M2 10h20"></path>
                                                </svg>

                                                <span class="leading-tight">
                                                    Pay Later
                                                    <span class="block text-[11px] font-medium text-gray-500 group-hover:text-white transition-all duration-300">
                                                        Reserve First
                                                    </span>
                                                </span>
                                            </a>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>

                        <?php 
                        if (!empty($carContentData["cars"]["links"])): ?>
                            <div class="flex justify-center mt-10 mmm">
                                <div class="flex gap-2 max-[1024px]:flex-wrap items-center pagination">
                                    <?php foreach ($carContentData["cars"]["links"] as $link): ?>
                                        <?php if ($link["url"]): ?>
                                            <?php if ($link["active"]): ?>
                                                <a href="cars<?= $link['page'] == '1' ? '' : '?page=' . $link['page'] ?>" 
                                                    class="px-4 py-2 bg-[#ff000d] text-white rounded"
                                                >
                                                    <?= $link["label"] ?>
                                                </a>

                                            <?php else: ?>
                                                <a 
                                                    href="cars<?= $link['page'] == '1' ? '' : '?page=' . $link['page'] ?>" 
                                                    class="px-4 py-2 bg-white text-[#333] border rounded hover:bg-[#ff000d] hover:text-white"
                                                >
                                                    <?= $link["label"] ?>
                                                </a>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <span class="px-4 py-2 text-gray-500"><?= $link["label"] ?></span>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        </div>
    </section>

 
<?php include_once('footer.php'); ?>
