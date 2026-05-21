<?php
declare(strict_types=1);

/**
 * Google Ads Lead Form Webhook Receiver
 *
 * Production notes:
 * 1. Put this behind HTTPS only.
 * 2. Set your real Google Ads webhook key below.
 * 3. Prefer storing raw payload + parsed fields.
 * 4. Deduplicate by lead_id.
 */

header('Content-Type: application/json; charset=utf-8');

/* ---------------------------------------------------------
 | CONFIG
 * --------------------------------------------------------- */
const GOOGLE_ADS_WEBHOOK_KEY = 'YOUR_SECRET_WEBHOOK_KEY';
const LOG_FILE = __DIR__ . '/google-ads-webhook.log';

// Database config
const DB_HOST = '127.0.0.1';
const DB_NAME = 'haladrive_haladriveDB';
const DB_USER = 'haladrive_quickdigital';
const DB_PASS = 'QuBrrdzMo5zx45H67JNg';

/* ---------------------------------------------------------
 | HELPERS
 * --------------------------------------------------------- */
function respond(int $statusCode, array $body): void
{
    http_response_code($statusCode);
    echo json_encode($body, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

function logMessage(string $message, array $context = []): void
{
    $line = sprintf(
        "[%s] %s %s\n",
        date('Y-m-d H:i:s'),
        $message,
        $context ? json_encode($context, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) : ''
    );
    file_put_contents(LOG_FILE, $line, FILE_APPEND);
}

function getJsonBody(): array
{
    $raw = file_get_contents('php://input');

    if ($raw === false || trim($raw) === '') {
        respond(400, ['message' => 'Empty request body']);
    }

    $data = json_decode($raw, true);

    if (!is_array($data)) {
        respond(400, ['message' => 'Invalid JSON payload']);
    }

    return [$data, $raw];
}

function pdo(): PDO
{
    static $pdo = null;

    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';

    $pdo = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);

    return $pdo;
}

function parseUserColumnData(array $userColumnData): array
{
    $parsed = [];

    foreach ($userColumnData as $item) {
        $columnId = $item['column_id'] ?? null;
        $value    = $item['string_value'] ?? null;

        if ($columnId) {
            $parsed[$columnId] = $value;
        }
    }

    return $parsed;
}

function getField(array $fields, string $key, ?string $default = null): ?string
{
    return isset($fields[$key]) && $fields[$key] !== '' ? (string)$fields[$key] : $default;
}

/* ---------------------------------------------------------
 | MAIN
 * --------------------------------------------------------- */
try {
    [$payload, $rawPayload] = getJsonBody();

    // Validate webhook key
    $incomingKey = $payload['google_key'] ?? '';
    if (!hash_equals(GOOGLE_ADS_WEBHOOK_KEY, (string)$incomingKey)) {
        logMessage('Invalid webhook key', ['incoming_key' => $incomingKey]);
        respond(401, ['message' => 'Invalid webhook key']);
    }

    $leadId     = $payload['lead_id'] ?? null;
    $campaignId = $payload['campaign_id'] ?? null;
    $formId     = $payload['form_id'] ?? null;
    $gclId      = $payload['gcl_id'] ?? null;
    $isTest     = (bool)($payload['is_test'] ?? false);
    $userData   = $payload['user_column_data'] ?? [];

    if (!$leadId) {
        respond(400, ['message' => 'Missing lead_id']);
    }

    if (!is_array($userData)) {
        respond(400, ['message' => 'user_column_data must be an array']);
    }

    // Parse fields
    $fields = parseUserColumnData($userData);

    $fullName = getField($fields, 'FULL_NAME');
    $email    = getField($fields, 'EMAIL');
    $phone    = getField($fields, 'PHONE_NUMBER');
    $over_23_age    = getField($fields, 'OVER_23_AGE');

    $allParsedFieldsJson = json_encode($fields, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

    $pdo = pdo();

    /* ---------------------------------------------------------
     | INSERT INTO GOOGLE ADS LEADS TABLE (MAIN STORAGE)
     * --------------------------------------------------------- */

    $checkStmt = $pdo->prepare('SELECT id FROM google_ads_leads WHERE lead_id = :lead_id LIMIT 1');
    $checkStmt->execute(['lead_id' => $leadId]);
    $existing = $checkStmt->fetch();

    if ($existing) {
        logMessage('Duplicate lead ignored', ['lead_id' => $leadId]);
        respond(200, ['status' => true, 'message' => 'Duplicate lead ignored']);
    }

    $insert = $pdo->prepare("
        INSERT INTO google_ads_leads (
            lead_id, campaign_id, form_id, gcl_id,
            full_name, email, phone,
            is_test, raw_payload, parsed_fields_json
        ) VALUES (
            :lead_id, :campaign_id, :form_id, :gcl_id,
            :full_name, :email, :phone,
            :is_test, :raw_payload, :parsed_fields_json
        )
    ");

    $insert->execute([
        'lead_id' => $leadId,
        'campaign_id' => $campaignId,
        'form_id' => $formId,
        'gcl_id' => $gclId,
        'full_name' => $fullName,
        'email' => $email,
        'phone' => $phone,
        'is_test' => $isTest ? 1 : 0,
        'raw_payload' => $rawPayload,
        'parsed_fields_json' => $allParsedFieldsJson,
    ]);

    /* ---------------------------------------------------------
     | INSERT INTO INQUIRIES TABLE (NEW REQUIREMENT)
     | Map Google lead → inquiries structure
     * --------------------------------------------------------- */
    $createdAt = (new DateTime('now', new DateTimeZone('Asia/Dubai')))->format('Y-m-d H:i:s');

    $stmt = $pdo->prepare("
        INSERT INTO inquiries (
            name,
            number,
            email,
            date_from,
            date_to,
            message,
            car_name,
            created_at,
            updated_at
        ) VALUES (
            :name,
            :number,
            :email,
            NULL,
            NULL,
            :message,
            NULL,
            :created_at,
            :updated_at
        )
    ");
    
    $stmt->execute([
        'name' => $fullName,
        'number' => $phone,
        'email' => $email,
        'message' => 'Over 23 Age = '.$over_23_age.' | Leads from Google Webhook',
        'created_at' => $createdAt,
        'updated_at' => $createdAt
    ]);
    
    logMessage('Lead stored successfully (both tables)', [
        'lead_id' => $leadId
    ]);

    respond(200, [
        'status' => true,
        'message' => 'Lead received and stored successfully',
        'lead_id' => $leadId
    ]);

} catch (PDOException $e) {
    logMessage('Database error', ['error' => $e->getMessage()]);
    respond(500, ['message' => 'Database error']);
} catch (Throwable $e) {
    logMessage('Unhandled exception', ['error' => $e->getMessage()]);
    respond(500, ['message' => 'Internal server error']);
}