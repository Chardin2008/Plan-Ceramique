<?php

if (!defined('ABSPATH')) {
    exit;
}

$smtpHost = getenv('SMTP_HOST');

if (!$smtpHost) {
    WP_CLI::log('SMTP skipped: SMTP_HOST is empty.');
    return;
}

$options = get_option('easy_wp_smtp', []);

$options['mail'] = [
    'from_email' => getenv('SMTP_FROM_EMAIL') ?: 'contact@plan-travail-ceramique.fr',
    'from_name' => getenv('SMTP_FROM_NAME') ?: 'Plan Céramique Premium',
    'mailer' => 'smtp',
];

$options['smtp'] = [
    'host' => $smtpHost,
    'port' => (string) (getenv('SMTP_PORT') ?: '587'),
    'encryption' => getenv('SMTP_ENCRYPTION') ?: 'tls',
    'auth' => getenv('SMTP_AUTH') === '0' ? '0' : '1',
    'user' => getenv('SMTP_USERNAME') ?: '',
    'pass' => getenv('SMTP_PASSWORD') ?: '',
];

update_option('easy_wp_smtp', $options, false);

WP_CLI::success('Easy WP SMTP configured from environment variables.');
