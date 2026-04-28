<?php

function pcp_form_recipient(): string
{
    return getenv('PCP_FORM_RECIPIENT') ?: 'hello@mpc.contact';
}

function pcp_form_mail_sender(): string
{
    $from = getenv('SMTP_FROM_EMAIL') ?: 'smtp@meilleur-plan-cuisine.fr';

    return sprintf('Plan Ceramique Premium <%s>', sanitize_email($from));
}

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

function pcp_queue_custom_mail(array $payload): bool
{
    $scheduled = wp_schedule_single_event(time(), 'pcp_send_custom_form_mail', [$payload]);

    return $scheduled && !is_wp_error($scheduled);
}

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
        foreach (['pcp_send_async_mail', 'pcp_send_custom_form_mail'] as $hook) {
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
    if (function_exists('pcp_mail_brake_enabled') && pcp_mail_brake_enabled()) {
        return;
    }

    if (!wp_next_scheduled('pcp_process_fast_form_queue')) {
        wp_schedule_event(time(), 'pcp_every_minute', 'pcp_process_fast_form_queue');
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

function pcp_submit_form(): void
{
    if (!check_ajax_referer('pcp_submit_form', 'nonce', false)) {
        wp_send_json_error(['message' => 'Session expiree. Rechargez la page.'], 403);
    }

    if (!empty($_POST['website'])) {
        wp_send_json_success(['message' => 'Merci, votre demande a bien ete envoyee.']);
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
            'Dimensions: ' . pcp_form_field('project_dimensions'),
            '',
            'Message:',
            $message,
        ];
        $subject = '[Plan Ceramique Premium] Nouvelle demande de devis';
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
        $subject = '[Plan Ceramique Premium] Nouveau message de contact';
        $attachments = [];
    }

    $queued = pcp_queue_custom_mail(
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

    if (!$queued) {
        wp_send_json_error(['message' => 'Le message n a pas pu etre prepare. Merci de reessayer.'], 500);
    }

    wp_send_json_success(['message' => 'Merci, votre demande a bien ete envoyee.']);
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

function pcp_render_contact_form(): string
{
    ob_start();
    ?>
    <form class="pcp-cf7-form" data-pcp-form>
      <input type="hidden" name="pcp_form_type" value="contact">
      <input type="text" name="website" value="" autocomplete="off" tabindex="-1" aria-hidden="true" style="position:absolute;left:-9999px;">
      <label>Nom
        <input type="text" name="name" autocomplete="name" required>
      </label>
      <label>Email
        <input type="email" name="email" autocomplete="email" required>
      </label>
      <label>Telephone
        <input type="tel" name="phone" autocomplete="tel">
      </label>
      <label>Votre message
        <textarea name="message" required></textarea>
      </label>
      <p class="pcp-cf7-submit"><input type="submit" value="Envoyer le message"></p>
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
        <label>Nom
          <input type="text" name="last_name" autocomplete="family-name" required>
        </label>
        <label>Prenom
          <input type="text" name="first_name" autocomplete="given-name" required>
        </label>
      </div>
      <div class="pcp-quote-row">
        <label>Email
          <input type="email" name="email" autocomplete="email" required>
        </label>
        <label>Telephone
          <input type="tel" name="phone" autocomplete="tel">
        </label>
      </div>
      <label>Ville
        <input type="text" name="city">
      </label>
      <label>Type de projet
        <select name="project_type" required>
          <option value="Plan de travail de cuisine">Plan de travail de cuisine</option>
          <option value="Ilot central">Ilot central</option>
          <option value="Credence assortie">Credence assortie</option>
          <option value="Renovation de cuisine">Renovation de cuisine</option>
          <option value="Projet professionnel">Projet professionnel</option>
        </select>
      </label>
      <label>Materiau souhaite
        <select name="desired_material" required>
          <option value="Ceramique aspect marbre">Ceramique aspect marbre</option>
          <option value="Ceramique pleine masse">Ceramique pleine masse</option>
          <option value="Effet pierre naturelle">Effet pierre naturelle</option>
          <option value="Effet beton mineral">Effet beton mineral</option>
          <option value="A definir avec un conseiller">A definir avec un conseiller</option>
        </select>
      </label>
      <label>Dimensions approximatives
        <input type="text" name="project_dimensions" placeholder="Exemple : 320 x 65 cm + ilot 180 x 90 cm">
      </label>
      <label>Message
        <textarea name="message" placeholder="Decrivez votre cuisine, vos contraintes et le niveau de finition attendu." required></textarea>
      </label>
      <label>Plan ou photo
        <input type="file" name="project_file" accept=".jpg,.jpeg,.png,.pdf">
      </label>
      <input type="submit" value="Recevoir mon etude de projet">
      <p class="pcp-form-note" data-pcp-form-status aria-live="polite"></p>
    </form>
    <?php
    return (string) ob_get_clean();
}
