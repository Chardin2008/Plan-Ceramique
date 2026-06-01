<?php

function pcp_form_recipient(): string
{
    $setting = function_exists('pcp_get_setting') ? pcp_get_setting('form_recipient_email') : '';

    return sanitize_email($setting ?: (getenv('PCP_FORM_RECIPIENT') ?: 'hello@mpc.contact'));
}

function pcp_form_mail_sender(): string
{
    $from = getenv('SMTP_FROM_EMAIL') ?: 'smtp@meilleur-plan-cuisine.fr';

    return sprintf('Plan Ceramique Studio <%s>', sanitize_email($from));
}

function pcp_easy_wp_smtp_enabled(): bool
{
    $options = get_option('easy_wp_smtp', []);

    return is_array($options) && ($options['mail']['mailer'] ?? '') === 'smtp';
}

function pcp_configure_smtp_mailer($phpmailer): void
{
    if (pcp_easy_wp_smtp_enabled()) {
        return;
    }

    $host = getenv('SMTP_HOST');

    if (!$host || getenv('SMTP_ENABLED') !== '1') {
        return;
    }

    $phpmailer->isSMTP();
    $phpmailer->Host = $host;
    $phpmailer->Port = (int) (getenv('SMTP_PORT') ?: 587);
    $phpmailer->SMTPAuth = getenv('SMTP_AUTH') === '1';
    $phpmailer->Username = getenv('SMTP_USERNAME') ?: '';
    $phpmailer->Password = getenv('SMTP_PASSWORD') ?: '';

    $encryption = strtolower((string) getenv('SMTP_ENCRYPTION'));
    $phpmailer->SMTPSecure = $encryption === 'none' ? '' : $encryption;

    $from = sanitize_email(getenv('SMTP_FROM_EMAIL') ?: 'smtp@meilleur-plan-cuisine.fr');
    $fromName = sanitize_text_field(getenv('SMTP_FROM_NAME') ?: 'Plan Ceramique Studio');

    if ($from) {
        $phpmailer->setFrom($from, $fromName, false);
    }
}
add_action('phpmailer_init', 'pcp_configure_smtp_mailer');

function pcp_form_rate_key(): string
{
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';

    return 'pcp_form_rate_' . md5((string) $ip);
}

function pcp_form_field(string $key): string
{
    return sanitize_text_field(wp_unslash($_POST[$key] ?? ''));
}

function pcp_form_email_field(string $key): string
{
    return sanitize_email(wp_unslash($_POST[$key] ?? ''));
}

function pcp_form_message_field(string $key): string
{
    return sanitize_textarea_field(wp_unslash($_POST[$key] ?? ''));
}

function pcp_store_uploaded_file(string $field): ?string
{
    if (empty($_FILES[$field]['tmp_name']) || !is_uploaded_file($_FILES[$field]['tmp_name'])) {
        return null;
    }

    $file = $_FILES[$field];
    $allowed = [
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'png' => 'image/png',
        'pdf' => 'application/pdf',
    ];

    $check = wp_check_filetype_and_ext($file['tmp_name'], $file['name'], $allowed);

    if (!$check['ext'] || !$check['type']) {
        return null;
    }

    if ((int) $file['size'] > 10 * MB_IN_BYTES) {
        return null;
    }

    require_once ABSPATH . 'wp-admin/includes/file.php';

    $upload = wp_handle_upload(
        $file,
        [
            'test_form' => false,
            'mimes' => $allowed,
        ]
    );

    return empty($upload['file']) ? null : $upload['file'];
}

function pcp_send_form_mail_now(array $payload): bool
{
    $sent = wp_mail(
        sanitize_email((string) ($payload['to'] ?? pcp_form_recipient())),
        sanitize_text_field((string) ($payload['subject'] ?? 'Nouveau message')),
        (string) ($payload['message'] ?? ''),
        (array) ($payload['headers'] ?? []),
        array_filter((array) ($payload['attachments'] ?? []), 'is_string')
    );

    foreach ((array) ($payload['temporary_files'] ?? []) as $file) {
        if (is_string($file) && is_file($file)) {
            wp_delete_file($file);
        }
    }

    return (bool) $sent;
}

function pcp_form_mail_queue_key(string $queueId): string
{
    return 'pcp_form_mail_' . sanitize_key($queueId);
}

function pcp_process_queued_form_mail(string $queueId): bool
{
    $queueId = sanitize_key($queueId);

    if (!$queueId) {
        return false;
    }

    $key = pcp_form_mail_queue_key($queueId);
    $lockKey = $key . '_lock';
    $payload = get_transient($key);

    if (!is_array($payload) || get_transient($lockKey)) {
        return false;
    }

    set_transient($lockKey, '1', 5 * MINUTE_IN_SECONDS);
    $sent = pcp_send_form_mail_now($payload);

    if ($sent) {
        delete_transient($key);
    }

    delete_transient($lockKey);

    return $sent;
}

function pcp_queue_form_mail(array $payload): array
{
    $queueId = wp_generate_uuid4();
    $nonce = wp_create_nonce('pcp_process_form_mail_' . $queueId);

    set_transient(pcp_form_mail_queue_key($queueId), $payload, HOUR_IN_SECONDS);

    wp_schedule_single_event(
        time() + 60,
        'pcp_process_queued_form_mail_event',
        [$queueId, $nonce]
    );

    return [
        'queue_id' => $queueId,
        'nonce' => $nonce,
    ];
}

function pcp_process_queued_form_mail_request(): void
{
    $queueId = sanitize_key(wp_unslash($_POST['queue_id'] ?? ''));
    $nonce = sanitize_text_field(wp_unslash($_POST['nonce'] ?? ''));

    if (!$queueId || !wp_verify_nonce($nonce, 'pcp_process_form_mail_' . $queueId)) {
        wp_send_json_error(['message' => 'Demande invalide.'], 403);
    }

    $sent = pcp_process_queued_form_mail($queueId);

    if (!$sent) {
        wp_send_json_error(['message' => 'Aucun email en attente.'], 404);
    }

    wp_send_json_success(['message' => 'Email envoye.']);
}
add_action('wp_ajax_pcp_process_queued_form_mail', 'pcp_process_queued_form_mail_request');
add_action('wp_ajax_nopriv_pcp_process_queued_form_mail', 'pcp_process_queued_form_mail_request');

function pcp_fast_direct_form_file(string $queueId): string
{
    $upload = wp_upload_dir();
    $directory = trailingslashit($upload['basedir']) . 'pcp-fast-form-direct';

    if (wp_mkdir_p($directory)) {
        pcp_protect_upload_queue_directory($directory);
    }

    return trailingslashit($directory) . sanitize_key($queueId) . '.json';
}

function pcp_protect_upload_queue_directory(string $directory): void
{
    if (!is_dir($directory)) {
        return;
    }

    $htaccess = trailingslashit($directory) . '.htaccess';
    if (!is_file($htaccess)) {
        file_put_contents($htaccess, "Require all denied\n", LOCK_EX);
    }

    $index = trailingslashit($directory) . 'index.php';
    if (!is_file($index)) {
        file_put_contents($index, "<?php\n// Silence is golden.\n", LOCK_EX);
    }
}

function pcp_process_fast_direct_form_mail_file(string $file): bool
{
    if (!is_file($file)) {
        return false;
    }

    $payload = json_decode((string) file_get_contents($file), true);

    if (!is_array($payload)) {
        wp_delete_file($file);

        return false;
    }

    $messageId = pcp_fast_form_payload_id($payload);

    if (pcp_fast_form_already_sent($messageId)) {
        wp_delete_file($file);

        return true;
    }

    $replyTo = sanitize_email((string) ($payload['reply_to'] ?? ''));
    $attachments = array_filter((array) ($payload['attachments'] ?? []), 'is_string');
    $sent = pcp_send_form_mail_now(
        [
            'to' => sanitize_email((string) ($payload['to'] ?? pcp_form_recipient())),
            'subject' => sanitize_text_field((string) ($payload['subject'] ?? 'Nouveau message')),
            'message' => (string) ($payload['message'] ?? ''),
            'headers' => array_filter(
                [
                    'From: ' . pcp_form_mail_sender(),
                    $replyTo ? 'Reply-To: ' . $replyTo : '',
                ]
            ),
            'attachments' => $attachments,
            'temporary_files' => $attachments,
        ]
    );

    if ($sent) {
        pcp_fast_form_mark_sent($messageId);
        wp_delete_file($file);
    }

    return $sent;
}

function pcp_process_fast_direct_form_mail_request(): void
{
    $queueId = sanitize_key(wp_unslash($_POST['queue_id'] ?? ''));
    $queueToken = sanitize_text_field(wp_unslash($_POST['queue_token'] ?? ''));
    $file = pcp_fast_direct_form_file($queueId);

    if (!$queueId || !$queueToken || !is_file($file)) {
        wp_send_json_error(['message' => 'Aucun email en attente.'], 404);
    }

    $payload = json_decode((string) file_get_contents($file), true);

    if (!is_array($payload) || !hash_equals((string) ($payload['queue_token_hash'] ?? ''), hash('sha256', $queueToken))) {
        wp_send_json_error(['message' => 'Demande invalide.'], 403);
    }

    $sent = pcp_process_fast_direct_form_mail_file($file);

    if ($sent) {
        wp_send_json_success(['message' => 'Email envoye.']);
    }

    wp_send_json_error(['message' => 'Le message n a pas pu etre envoye.'], 500);
}
add_action('wp_ajax_pcp_process_fast_direct_form_mail', 'pcp_process_fast_direct_form_mail_request');
add_action('wp_ajax_nopriv_pcp_process_fast_direct_form_mail', 'pcp_process_fast_direct_form_mail_request');

function pcp_process_fast_direct_form_mail_queue(): void
{
    $upload = wp_upload_dir();
    $directory = trailingslashit($upload['basedir']) . 'pcp-fast-form-direct';

    if (!is_dir($directory) || get_transient('pcp_fast_direct_form_mail_queue_lock')) {
        return;
    }

    pcp_protect_upload_queue_directory($directory);
    set_transient('pcp_fast_direct_form_mail_queue_lock', '1', 5 * MINUTE_IN_SECONDS);

    $processed = 0;
    foreach (glob(trailingslashit($directory) . '*.json') ?: [] as $file) {
        if (!is_file($file) || filemtime($file) > time() - 30) {
            continue;
        }

        pcp_process_fast_direct_form_mail_file($file);
        $processed++;

        if ($processed >= 20) {
            break;
        }
    }

    delete_transient('pcp_fast_direct_form_mail_queue_lock');
}
add_action('pcp_process_fast_direct_form_mail_queue', 'pcp_process_fast_direct_form_mail_queue');

function pcp_process_queued_form_mail_event(string $queueId, string $nonce): void
{
    if (!wp_verify_nonce($nonce, 'pcp_process_form_mail_' . $queueId)) {
        return;
    }

    pcp_process_queued_form_mail($queueId);
}
add_action('pcp_process_queued_form_mail_event', 'pcp_process_queued_form_mail_event', 10, 2);

function pcp_send_custom_form_mail(array $payload): void
{
    // Legacy AJAX submissions are intentionally discarded to stop duplicated cron emails.
    foreach ((array) ($payload['temporary_files'] ?? []) as $file) {
        if (is_string($file) && is_file($file)) {
            wp_delete_file($file);
        }
    }
}
add_action('pcp_send_custom_form_mail', 'pcp_send_custom_form_mail');

function pcp_clear_legacy_form_mail_crons(): void
{
    $crons = _get_cron_array();

    if (!is_array($crons)) {
        return;
    }

    foreach ($crons as $timestamp => $hooks) {
        foreach (['pcp_process_fast_form_queue', 'pcp_send_async_mail', 'pcp_send_custom_form_mail'] as $hook) {
            if (empty($hooks[$hook]) || !is_array($hooks[$hook])) {
                continue;
            }

            foreach ($hooks[$hook] as $event) {
                wp_unschedule_event((int) $timestamp, $hook, $event['args'] ?? []);
            }
        }
    }
}
add_action('init', 'pcp_clear_legacy_form_mail_crons', 1);

function pcp_disable_legacy_fast_form_queue_files(): void
{
    $queue = pcp_fast_form_queue_file();

    if (!$queue) {
        return;
    }

    $directory = dirname($queue);
    $timestamp = gmdate('YmdHis');

    foreach (glob($directory . '/*.jsonl') ?: [] as $file) {
        if (!is_file($file) || str_contains(basename($file), '.disabled.')) {
            continue;
        }

        @rename($file, $file . '.disabled.' . $timestamp);
    }
}
add_action('init', 'pcp_disable_legacy_fast_form_queue_files', 2);

function pcp_fast_form_queue_file(): string
{
    $upload = wp_upload_dir();
    $directory = trailingslashit($upload['basedir']) . 'pcp-fast-form-queue';

    if (!wp_mkdir_p($directory)) {
        return '';
    }

    return trailingslashit($directory) . 'queue.jsonl';
}

function pcp_fast_form_sent_log_file(): string
{
    $queue = pcp_fast_form_queue_file();

    return $queue ? dirname($queue) . '/sent.log' : '';
}

function pcp_fast_form_payload_id(array $payload): string
{
    $messageId = sanitize_key((string) ($payload['message_id'] ?? ''));

    if ($messageId) {
        return $messageId;
    }

    return hash(
        'sha256',
        implode('|', [
            (string) ($payload['to'] ?? ''),
            (string) ($payload['reply_to'] ?? ''),
            (string) ($payload['subject'] ?? ''),
            (string) ($payload['message'] ?? ''),
            (string) ($payload['created_at'] ?? ''),
        ])
    );
}

function pcp_fast_form_already_sent(string $messageId): bool
{
    $log = pcp_fast_form_sent_log_file();

    if (!$messageId || !$log || !is_file($log)) {
        return false;
    }

    $sentIds = file($log, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    return is_array($sentIds) && in_array($messageId, $sentIds, true);
}

function pcp_fast_form_mark_sent(string $messageId): void
{
    $log = pcp_fast_form_sent_log_file();

    if (!$messageId || !$log) {
        return;
    }

    file_put_contents($log, $messageId . "\n", FILE_APPEND | LOCK_EX);
}

function pcp_fast_form_quarantine_legacy_payload(string $line): void
{
    $queue = pcp_fast_form_queue_file();

    if (!$queue) {
        return;
    }

    file_put_contents(dirname($queue) . '/legacy-quarantine.jsonl', $line . "\n", FILE_APPEND | LOCK_EX);
}

function pcp_process_fast_form_queue(): void
{
    $queue = pcp_fast_form_queue_file();

    if (!$queue || !is_file($queue)) {
        return;
    }

    $processing = dirname($queue) . '/processing-' . time() . '-' . wp_generate_uuid4() . '.jsonl';
    $handle = fopen($queue, 'c+');

    if (!$handle) {
        return;
    }

    flock($handle, LOCK_EX);
    $contents = stream_get_contents($handle);
    ftruncate($handle, 0);
    rewind($handle);
    flock($handle, LOCK_UN);
    fclose($handle);

    if (trim((string) $contents) === '') {
        return;
    }

    file_put_contents($processing, $contents, LOCK_EX);

    foreach (array_filter(explode("\n", (string) $contents)) as $line) {
        $payload = json_decode($line, true);

        if (!is_array($payload)) {
            continue;
        }

        if (empty($payload['message_id'])) {
            pcp_fast_form_quarantine_legacy_payload($line);
            continue;
        }

        $messageId = pcp_fast_form_payload_id($payload);

        if (pcp_fast_form_already_sent($messageId)) {
            continue;
        }

        $attachments = array_filter((array) ($payload['attachments'] ?? []), 'is_string');
        $replyTo = sanitize_email((string) ($payload['reply_to'] ?? ''));
        $headers = ['From: ' . pcp_form_mail_sender()];

        if ($replyTo) {
            $headers[] = 'Reply-To: ' . $replyTo;
        }

        $sent = wp_mail(
            sanitize_email((string) ($payload['to'] ?? pcp_form_recipient())),
            sanitize_text_field((string) ($payload['subject'] ?? 'Nouveau message')),
            (string) ($payload['message'] ?? ''),
            $headers,
            $attachments
        );

        if ($sent) {
            pcp_fast_form_mark_sent($messageId);
        }

        foreach ($attachments as $file) {
            if (is_file($file)) {
                wp_delete_file($file);
            }
        }
    }

    if (is_file($processing)) {
        wp_delete_file($processing);
    }
}
add_action('pcp_process_fast_form_queue', 'pcp_process_fast_form_queue');

function pcp_schedule_fast_form_queue(): void
{
    wp_clear_scheduled_hook('pcp_process_fast_form_queue');

    if (!wp_next_scheduled('pcp_process_fast_direct_form_mail_queue')) {
        wp_schedule_event(time() + MINUTE_IN_SECONDS, 'pcp_every_minute', 'pcp_process_fast_direct_form_mail_queue');
    }
}
add_action('init', 'pcp_schedule_fast_form_queue');

function pcp_add_cron_interval(array $schedules): array
{
    $schedules['pcp_every_minute'] = [
        'interval' => 60,
        'display' => 'Every minute',
    ];

    return $schedules;
}
add_filter('cron_schedules', 'pcp_add_cron_interval');

function pcp_form_captcha_secret(): string
{
    return wp_salt('nonce');
}

function pcp_form_captcha_hash(int $answer): string
{
    return hash_hmac('sha256', (string) $answer, pcp_form_captcha_secret());
}

function pcp_validate_captcha_request(): bool
{
    $answer = isset($_POST['captcha_answer']) ? absint(wp_unslash($_POST['captcha_answer'])) : 0;
    $token = isset($_POST['captcha_token']) ? sanitize_text_field(wp_unslash($_POST['captcha_token'])) : '';

    if ($answer < 1 || $token === '') {
        return false;
    }

    return hash_equals(pcp_form_captcha_hash($answer), $token);
}

function pcp_render_captcha_field(): string
{
    $left = random_int(2, 8);
    $right = random_int(2, 8);
    $answer = $left + $right;

    ob_start();
    ?>
    <label class="pcp-form-captcha">Anti-spam : combien font <?php echo esc_html((string) $left); ?> + <?php echo esc_html((string) $right); ?> ?
      <input type="number" name="captcha_answer" inputmode="numeric" min="1" autocomplete="off" required>
      <input type="hidden" name="captcha_token" value="<?php echo esc_attr(pcp_form_captcha_hash($answer)); ?>">
    </label>
    <?php

    return (string) ob_get_clean();
}

function pcp_submit_form(): void
{
    if (!check_ajax_referer('pcp_submit_form', 'nonce', false)) {
        wp_send_json_error(['message' => 'Session expiree. Rechargez la page.'], 403);
    }

    if (!empty($_POST['website'])) {
        wp_send_json_success(['message' => 'Merci, votre demande a bien ete envoyee.']);
    }

    if (!pcp_validate_captcha_request()) {
        wp_send_json_error(['message' => 'Merci de valider la question anti-spam.'], 422);
    }

    $rateKey = pcp_form_rate_key();

    if (get_transient($rateKey)) {
        wp_send_json_error(['message' => 'Merci de patienter quelques secondes avant un nouvel envoi.'], 429);
    }

    set_transient($rateKey, '1', 20);

    $type = pcp_form_field('pcp_form_type');
    $email = pcp_form_email_field('email');
    $message = pcp_form_message_field('message');

    if (!$email || !$message) {
        wp_send_json_error(['message' => 'Merci de renseigner votre email et votre message.'], 422);
    }

    if ($type === 'quote') {
        $lastName = pcp_form_field('last_name');
        $firstName = pcp_form_field('first_name');
        $attachment = pcp_store_uploaded_file('project_file');
        $body = [
            'Type: Demande de devis',
            'Nom: ' . $lastName,
            'Prenom: ' . $firstName,
            'Email: ' . $email,
            'Telephone: ' . pcp_form_field('phone'),
            'Ville: ' . pcp_form_field('city'),
            'Type de projet: ' . pcp_form_field('project_type'),
            'Materiau souhaite: ' . pcp_form_field('desired_material'),
            'Budget approximatif: ' . pcp_form_field('budget'),
            'Dimensions: ' . pcp_form_field('project_dimensions'),
            '',
            'Message:',
            $message,
        ];
        $subject = '[Plan Ceramique Studio] Nouvelle demande de devis';
        $attachments = $attachment ? [$attachment] : [];
    } else {
        $name = pcp_form_field('name');
        $body = [
            'Type: Contact',
            'Nom: ' . $name,
            'Email: ' . $email,
            'Telephone: ' . pcp_form_field('phone'),
            '',
            'Message:',
            $message,
        ];
        $subject = '[Plan Ceramique Studio] Nouveau message de contact';
        $attachments = [];
    }

    $queued = pcp_queue_form_mail(
        [
            'to' => pcp_form_recipient(),
            'subject' => $subject,
            'message' => implode("\n", $body),
            'headers' => [
                'From: ' . pcp_form_mail_sender(),
                'Reply-To: ' . $email,
            ],
            'attachments' => $attachments,
            'temporary_files' => $attachments,
        ]
    );

    wp_send_json_success(
        [
            'message' => 'Merci, votre demande a bien ete envoyee.',
            'queueId' => $queued['queue_id'],
            'queueNonce' => $queued['nonce'],
        ]
    );
}
add_action('wp_ajax_pcp_submit_form', 'pcp_submit_form');
add_action('wp_ajax_nopriv_pcp_submit_form', 'pcp_submit_form');

function pcp_contact_form_shortcode(array $atts): string
{
    $atts = shortcode_atts(['type' => 'contact'], $atts, 'pcp_contact_form');

    if ($atts['type'] === 'quote') {
        return pcp_render_quote_form();
    }

    return pcp_render_contact_form();
}
add_shortcode('pcp_contact_form', 'pcp_contact_form_shortcode');

function pcp_form_setting(string $key, string $fallback = ''): string
{
    return function_exists('pcp_get_setting') ? (pcp_get_setting($key) ?: $fallback) : $fallback;
}

function pcp_render_select_options_from_setting(string $key): void
{
    $options = function_exists('pcp_setting_lines') ? pcp_setting_lines($key) : [];

    foreach ($options as $option) {
        echo '<option value="' . esc_attr($option) . '">' . esc_html($option) . '</option>';
    }
}

function pcp_render_contact_form(): string
{
    ob_start();
    ?>
    <form class="pcp-cf7-form" data-pcp-form>
      <input type="hidden" name="pcp_form_type" value="contact">
      <input type="text" name="website" value="" autocomplete="off" tabindex="-1" aria-hidden="true" style="position:absolute;left:-9999px;">
      <label><?php echo esc_html(pcp_form_setting('contact_form_name_label', 'Nom')); ?>
        <input type="text" name="name" autocomplete="name" required>
      </label>
      <label><?php echo esc_html(pcp_form_setting('contact_form_email_label', 'Email')); ?>
        <input type="email" name="email" autocomplete="email" required>
      </label>
      <label><?php echo esc_html(pcp_form_setting('contact_form_phone_label', 'Telephone')); ?>
        <input type="tel" name="phone" autocomplete="tel">
      </label>
      <label><?php echo esc_html(pcp_form_setting('contact_form_message_label', 'Votre message')); ?>
        <textarea name="message" required></textarea>
      </label>
      <?php echo pcp_render_captcha_field(); ?>
      <p class="pcp-cf7-submit"><input type="submit" value="<?php echo esc_attr(pcp_form_setting('contact_form_submit_text', 'Envoyer le message')); ?>"></p>
      <p class="pcp-form-note" data-pcp-form-status aria-live="polite"></p>
    </form>
    <?php
    return (string) ob_get_clean();
}

function pcp_render_quote_form(): string
{
    ob_start();
    ?>
    <form class="pcp-quote-form" data-pcp-form enctype="multipart/form-data">
      <input type="hidden" name="pcp_form_type" value="quote">
      <input type="text" name="website" value="" autocomplete="off" tabindex="-1" aria-hidden="true" style="position:absolute;left:-9999px;">
      <div class="pcp-quote-row">
        <label><?php echo esc_html(pcp_form_setting('quote_form_last_name_label', 'Nom')); ?>
          <input type="text" name="last_name" autocomplete="family-name" required>
        </label>
        <label><?php echo esc_html(pcp_form_setting('quote_form_first_name_label', 'Prenom')); ?>
          <input type="text" name="first_name" autocomplete="given-name" required>
        </label>
      </div>
      <div class="pcp-quote-row">
        <label><?php echo esc_html(pcp_form_setting('quote_form_email_label', 'Email')); ?>
          <input type="email" name="email" autocomplete="email" required>
        </label>
        <label><?php echo esc_html(pcp_form_setting('quote_form_phone_label', 'Telephone')); ?>
          <input type="tel" name="phone" autocomplete="tel">
        </label>
      </div>
      <label><?php echo esc_html(pcp_form_setting('quote_form_city_label', 'Ville')); ?>
        <input type="text" name="city">
      </label>
      <label><?php echo esc_html(pcp_form_setting('quote_form_project_type_label', 'Type de projet')); ?>
        <select name="project_type" required>
          <?php pcp_render_select_options_from_setting('quote_form_project_type_options'); ?>
        </select>
      </label>
      <label><?php echo esc_html(pcp_form_setting('quote_form_material_label', 'Materiau souhaite')); ?>
        <select name="desired_material" required>
          <?php pcp_render_select_options_from_setting('quote_form_material_options'); ?>
        </select>
      </label>
      <label><?php echo esc_html(pcp_form_setting('quote_form_budget_label', 'Budget approximatif')); ?>
        <select name="budget">
          <?php pcp_render_select_options_from_setting('quote_form_budget_options'); ?>
        </select>
      </label>
      <label><?php echo esc_html(pcp_form_setting('quote_form_dimensions_label', 'Dimensions approximatives')); ?>
        <input type="text" name="project_dimensions" placeholder="<?php echo esc_attr(pcp_form_setting('quote_form_dimensions_placeholder', 'Exemple : 320 x 65 cm + ilot 180 x 90 cm')); ?>">
      </label>
      <label><?php echo esc_html(pcp_form_setting('quote_form_message_label', 'Message')); ?>
        <textarea name="message" placeholder="<?php echo esc_attr(pcp_form_setting('quote_form_message_placeholder', 'Decrivez votre cuisine, vos contraintes et le niveau de finition attendu.')); ?>" required></textarea>
      </label>
      <label><?php echo esc_html(pcp_form_setting('quote_form_file_label', 'Plan ou photo')); ?>
        <input type="file" name="project_file" accept=".jpg,.jpeg,.png,.pdf">
      </label>
      <?php echo pcp_render_captcha_field(); ?>
      <input type="submit" value="<?php echo esc_attr(pcp_form_setting('quote_form_submit_text', 'Recevoir mon etude de projet')); ?>">
      <p class="pcp-form-note" data-pcp-form-status aria-live="polite"></p>
    </form>
    <?php
    return (string) ob_get_clean();
}
