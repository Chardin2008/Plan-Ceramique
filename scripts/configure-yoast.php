<?php

if (!defined('ABSPATH')) {
    exit;
}

$socialImage = content_url('themes/plan-ceramique-premium/assets/img/og-plan-ceramique.jpg');

$wpseo = get_option('wpseo', []);
$wpseo = is_array($wpseo) ? $wpseo : [];
update_option(
    'wpseo',
    array_merge(
        $wpseo,
        [
            'company_or_person' => 'company',
            'company_name' => 'Plan Céramique Studio',
            'website_name' => 'Plan Céramique Studio',
            'alternate_website_name' => 'Plan Céramique',
            'enable_xml_sitemap' => true,
            'opengraph' => true,
            'twitter' => true,
        ]
    )
);

$wpseoTitles = get_option('wpseo_titles', []);
$wpseoTitles = is_array($wpseoTitles) ? $wpseoTitles : [];
update_option(
    'wpseo_titles',
    array_merge(
        $wpseoTitles,
        [
            'separator' => 'sc-dash',
            'title-home-wpseo' => 'Plan de travail en céramique sur mesure | Plan Céramique Studio',
            'metadesc-home-wpseo' => 'Plan de travail en céramique sur mesure pour cuisine premium : conseil, fabrication, livraison et pose partout en France.',
            'title-page' => '%%title%% | Plan Céramique Studio',
            'metadesc-page' => '%%excerpt%%',
            'title-post' => '%%title%% | Plan Céramique Studio',
            'metadesc-post' => '%%excerpt%%',
            'breadcrumbs-enable' => true,
        ]
    )
);

$wpseoSocial = get_option('wpseo_social', []);
$wpseoSocial = is_array($wpseoSocial) ? $wpseoSocial : [];
update_option(
    'wpseo_social',
    array_merge(
        $wpseoSocial,
        [
            'og_default_image' => $socialImage,
            'twitter_card_type' => 'summary_large_image',
        ]
    )
);

$posts = get_posts(
    [
        'post_type' => ['page', 'post'],
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'fields' => 'ids',
    ]
);

foreach ($posts as $postId) {
    $title = get_post_meta((int) $postId, '_yoast_wpseo_title', true);
    $description = get_post_meta((int) $postId, '_yoast_wpseo_metadesc', true);

    if (!$title) {
        $title = get_the_title((int) $postId) . ' | Plan Céramique Studio';
        update_post_meta((int) $postId, '_yoast_wpseo_title', $title);
    }

    if (!$description) {
        $description = has_excerpt((int) $postId)
            ? wp_strip_all_tags(get_the_excerpt((int) $postId))
            : wp_trim_words(wp_strip_all_tags(get_post_field('post_content', (int) $postId)), 24);
        update_post_meta((int) $postId, '_yoast_wpseo_metadesc', $description);
    }

    update_post_meta((int) $postId, '_yoast_wpseo_opengraph-title', $title);
    update_post_meta((int) $postId, '_yoast_wpseo_opengraph-description', $description);
    update_post_meta((int) $postId, '_yoast_wpseo_opengraph-image', $socialImage);
    update_post_meta((int) $postId, '_yoast_wpseo_twitter-title', $title);
    update_post_meta((int) $postId, '_yoast_wpseo_twitter-description', $description);
    update_post_meta((int) $postId, '_yoast_wpseo_twitter-image', $socialImage);
}

if (defined('WP_CLI') && WP_CLI) {
    WP_CLI::success('Yoast defaults and social metadata configured.');
}
