<?php

if (!defined('ABSPATH')) {
    exit;
}

if (!function_exists('pcp_home_default_block_content')) {
    require_once get_template_directory() . '/inc/home-blocks.php';
}

$page = get_page_by_path('accueil');

if (!$page instanceof WP_Post) {
    if (defined('WP_CLI') && WP_CLI) {
        WP_CLI::error('Page "accueil" introuvable.');
    }

    return;
}

$content = (string) $page->post_content;
$argv = $_SERVER['argv'] ?? [];
$force = in_array('--force', $argv, true) || in_array('force', $argv, true);

if (pcp_home_has_blocks($content) && !$force) {
    if (defined('WP_CLI') && WP_CLI) {
        WP_CLI::success('La page accueil contient deja les blocs Gutenberg Plan Ceramique.');
    }

    return;
}

$result = wp_update_post(
    [
        'ID' => $page->ID,
        'post_content' => wp_slash(pcp_home_default_block_content((int) $page->ID)),
    ],
    true
);

if (is_wp_error($result)) {
    if (defined('WP_CLI') && WP_CLI) {
        WP_CLI::error($result->get_error_message());
    }

    return;
}

if (defined('WP_CLI') && WP_CLI) {
    WP_CLI::success('Page accueil migree en blocs Gutenberg dynamiques.');
}
