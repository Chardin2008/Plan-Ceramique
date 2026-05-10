<?php

declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');

$wpLoad = dirname(__DIR__, 4) . '/wp-load.php';
if (is_file($wpLoad)) {
    require_once $wpLoad;
}

function pcp_fast_json(bool $success, string $message, int $status = 200): void
{
    http_response_code($status);
    echo json_encode(
        [
            'success' => $success,
            'data' => ['message' => $message],
        ],
        JSON_UNESCAPED_SLASHES
    );
    exit;
}

function pcp_fast_field(string $key): string
{
    return trim(strip_tags((string) ($_POST[$key] ?? '')));
}

function pcp_fast_message(string $key): string
{
    return trim(strip_tags((string) ($_POST[$key] ?? '')));
}

function pcp_fast_form_recipient(): string
{
    if (function_exists('pcp_get_setting')) {
        $setting = pcp_get_setting('form_recipient_email');

        if ($setting !== '') {
            return $setting;
        }
    }

    return getenv('PCP_FORM_RECIPIENT') ?: 'hello@mpc.contact';
}

function pcp_fast_dir(string $leaf): string
{
    $uploads = dirname(__DIR__, 3) . '/uploads';
    $directory = $uploads . '/' . $leaf;

    if (!is_dir($directory)) {
        mkdir($directory, 0755, true);
    }

    $htaccess = $directory . '/.htaccess';
    if (!is_file($htaccess)) {
        file_put_contents($htaccess, "Require all denied\n", LOCK_EX);
    }

    $index = $directory . '/index.php';
    if (!is_file($index)) {
        file_put_contents($index, "<?php\n// Silence is golden.\n", LOCK_EX);
    }

    return $directory;
}

function pcp_fast_direct_file(string $queueId): string
{
    return pcp_fast_dir('pcp-fast-form-direct') . '/' . preg_replace('/[^a-zA-Z0-9_-]/', '', $queueId) . '.json';
}

function pcp_fast_rate_limit(): void
{
    $directory = pcp_fast_dir('pcp-fast-form-rate');
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    $file = $directory . '/' . md5((string) $ip) . '.txt';

    if (is_file($file) && filemtime($file) > time() - 5) {
        pcp_fast_json(false, 'Merci de patienter quelques secondes avant un nouvel envoi.', 429);
    }

    file_put_contents($file, (string) time(), LOCK_EX);
}

function pcp_fast_attachment(string $field): ?string
{
    if (empty($_FILES[$field]['tmp_name']) || !is_uploaded_file($_FILES[$field]['tmp_name'])) {
        return null;
    }

    $file = $_FILES[$field];

    if ((int) $file['size'] > 10 * 1024 * 1024) {
        return null;
    }

    $extension = strtolower(pathinfo((string) $file['name'], PATHINFO_EXTENSION));
    $allowed = ['jpg', 'jpeg', 'png', 'pdf'];

    if (!in_array($extension, $allowed, true)) {
        return null;
    }

    $directory = pcp_fast_dir('pcp-fast-form-files');
    $target = $directory . '/' . uniqid('pcp-', true) . '.' . $extension;

    return move_uploaded_file($file['tmp_name'], $target) ? $target : null;
}

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    pcp_fast_json(false, 'Methode non autorisee.', 405);
}

if (!empty($_POST['website'])) {
    pcp_fast_json(true, 'Merci, votre demande a bien ete envoyee.');
}

pcp_fast_rate_limit();

$type = pcp_fast_field('pcp_form_type');
$email = filter_var(pcp_fast_field('email'), FILTER_VALIDATE_EMAIL);
$message = pcp_fast_message('message');

if (!$email || $message === '') {
    pcp_fast_json(false, 'Merci de renseigner votre email et votre message.', 422);
}

if ($type === 'quote') {
    $attachment = pcp_fast_attachment('project_file');
    $subject = '[Plan Ceramique Studio] Nouvelle demande de devis';
    $lines = [
        'Type: Demande de devis',
        'Nom: ' . pcp_fast_field('last_name'),
        'Prenom: ' . pcp_fast_field('first_name'),
        'Email: ' . $email,
        'Telephone: ' . pcp_fast_field('phone'),
        'Ville: ' . pcp_fast_field('city'),
        'Type de projet: ' . pcp_fast_field('project_type'),
        'Materiau souhaite: ' . pcp_fast_field('desired_material'),
        'Budget approximatif: ' . pcp_fast_field('budget'),
        'Dimensions: ' . pcp_fast_field('project_dimensions'),
        '',
        'Message:',
        $message,
    ];
    $attachments = $attachment ? [$attachment] : [];
} else {
    $subject = '[Plan Ceramique Studio] Nouveau message de contact';
    $lines = [
        'Type: Contact',
        'Nom: ' . pcp_fast_field('name'),
        'Email: ' . $email,
        'Telephone: ' . pcp_fast_field('phone'),
        '',
        'Message:',
        $message,
    ];
    $attachments = [];
}

$payload = [
    'message_id' => bin2hex(random_bytes(16)),
    'to' => pcp_fast_form_recipient(),
    'reply_to' => $email,
    'subject' => $subject,
    'message' => implode("\n", $lines),
    'attachments' => $attachments,
    'created_at' => date(DATE_ATOM),
];

$queueId = bin2hex(random_bytes(16));
$queueToken = bin2hex(random_bytes(16));
$payload['queue_token_hash'] = hash('sha256', $queueToken);

if (!file_put_contents(pcp_fast_direct_file($queueId), json_encode($payload, JSON_UNESCAPED_SLASHES), LOCK_EX)) {
    pcp_fast_json(false, 'Le message n a pas pu etre prepare. Merci de reessayer.', 500);
}

http_response_code(200);
echo json_encode(
    [
        'success' => true,
        'data' => [
            'message' => 'Merci, votre demande a bien ete envoyee.',
            'queueId' => $queueId,
            'queueToken' => $queueToken,
        ],
    ],
    JSON_UNESCAPED_SLASHES
);
exit;
