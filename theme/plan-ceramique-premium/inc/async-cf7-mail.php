<?php

function pcp_is_cf7_mail_context(): bool
{
    return class_exists('WPCF7_Mail')
        && method_exists('WPCF7_Mail', 'get_current')
        && WPCF7_Mail::get_current();
}

function pcp_prepare_async_mail_attachments(array $attachments): array
{
    $prepared = [];
    $upload = wp_upload_dir();
    $directory = trailingslashit($upload['basedir']) . 'pcp-async-mail';

    if (!wp_mkdir_p($directory)) {
        return [
            'attachments' => $attachments,
            'temporary_files' => [],
        ];
    }

    foreach ($attachments as $attachment) {
        if (!is_string($attachment) || !is_readable($attachment) || !is_file($attachment)) {
            $prepared[] = $attachment;
            continue;
        }

        $target = trailingslashit($directory) . wp_unique_filename($directory, basename($attachment));

        if (copy($attachment, $target)) {
            $prepared[] = $target;
            continue;
        }

        $prepared[] = $attachment;
    }

    return [
        'attachments' => $prepared,
        'temporary_files' => array_values(array_diff($prepared, $attachments)),
    ];
}

function pcp_queue_cf7_mail($preempt, array $atts)
{
    if (!pcp_is_cf7_mail_context()) {
        return $preempt;
    }

    $attachments = pcp_prepare_async_mail_attachments((array) ($atts['attachments'] ?? []));

    $payload = [
        'to' => $atts['to'] ?? '',
        'subject' => $atts['subject'] ?? '',
        'message' => $atts['message'] ?? '',
        'headers' => $atts['headers'] ?? [],
        'attachments' => $attachments['attachments'],
        'temporary_files' => $attachments['temporary_files'],
    ];

    $scheduled = wp_schedule_single_event(time(), 'pcp_send_async_mail', [$payload]);

    if (!$scheduled || is_wp_error($scheduled)) {
        return $preempt;
    }

    return true;
}
add_filter('pre_wp_mail', 'pcp_queue_cf7_mail', 10, 2);

function pcp_send_async_mail(array $payload): void
{
    wp_mail(
        $payload['to'] ?? '',
        $payload['subject'] ?? '',
        $payload['message'] ?? '',
        $payload['headers'] ?? [],
        $payload['attachments'] ?? []
    );

    foreach ((array) ($payload['temporary_files'] ?? []) as $file) {
        if (is_string($file) && is_file($file)) {
            wp_delete_file($file);
        }
    }
}
add_action('pcp_send_async_mail', 'pcp_send_async_mail');
