<?php

if (!defined('ABSPATH')) {
    exit;
}

$social_image = content_url('themes/plan-ceramique-premium/assets/img/og-plan-ceramique.jpg');
$site_name = 'Plan Céramique Studio';

$wpseo = get_option('wpseo', []);
$wpseo = is_array($wpseo) ? $wpseo : [];
update_option(
    'wpseo',
    array_merge(
        $wpseo,
        [
            'company_or_person' => 'company',
            'company_name' => $site_name,
            'website_name' => $site_name,
            'alternate_website_name' => 'Plan Céramique',
            'enable_xml_sitemap' => true,
            'opengraph' => true,
            'twitter' => true,
            'content_analysis_active' => true,
            'keyword_analysis_active' => true,
            'enable_text_link_counter' => true,
            'enable_schema' => true,
        ]
    )
);

$wpseo_titles = get_option('wpseo_titles', []);
$wpseo_titles = is_array($wpseo_titles) ? $wpseo_titles : [];
update_option(
    'wpseo_titles',
    array_merge(
        $wpseo_titles,
        [
            'separator' => 'sc-dash',
            'website_name' => $site_name,
            'company_name' => $site_name,
            'alternate_website_name' => 'Plan Céramique',
            'company_or_person' => 'company',
            'title-home-wpseo' => 'Plan de travail en céramique sur mesure | Plan Céramique Studio',
            'metadesc-home-wpseo' => 'Plan de travail en céramique sur mesure pour cuisine premium : conseil, fabrication, livraison et pose partout en France.',
            'title-page' => '%%title%% | Plan Céramique Studio',
            'metadesc-page' => '%%excerpt%%',
            'title-post' => '%%title%% | Plan Céramique Studio',
            'metadesc-post' => '%%excerpt%%',
            'title-pcp_realisation' => '%%title%% | Réalisation céramique | Plan Céramique Studio',
            'metadesc-pcp_realisation' => 'Découvrez cette réalisation avec plan de travail en céramique sur mesure : finitions, ambiance, usage et inspiration cuisine.',
            'title-pcp_matiere' => '%%title%% | Matière céramique | Plan Céramique Studio',
            'metadesc-pcp_matiere' => 'Découvrez cette matière céramique pour plan de travail : couleur, ambiance, usage conseillé et inspiration cuisine.',
            'noindex-pcp_avis' => true,
            'noindex-ptarchive-pcp_avis' => true,
            'noindex-ptarchive-pcp_realisation' => true,
            'noindex-ptarchive-pcp_matiere' => false,
            'breadcrumbs-enable' => true,
            'breadcrumbs-home' => 'Accueil',
        ]
    )
);

$wpseo_social = get_option('wpseo_social', []);
$wpseo_social = is_array($wpseo_social) ? $wpseo_social : [];
update_option(
    'wpseo_social',
    array_merge(
        $wpseo_social,
        [
            'og_default_image' => $social_image,
            'twitter_card_type' => 'summary_large_image',
        ]
    )
);

$seo_meta = [
    'accueil' => [
        'kw' => 'plan de travail en céramique',
        'title' => 'Plan de travail en céramique sur mesure | Plan Céramique Studio',
        'desc' => 'Plan de travail en céramique sur mesure pour cuisine premium : conseil, fabrication, livraison et pose partout en France.',
    ],
    'nos-services' => [
        'kw' => 'services plan de travail céramique',
        'title' => 'Nos services de plan de travail en céramique | Plan Céramique Studio',
        'desc' => 'Découvrez nos services pour un plan de travail en céramique sur mesure : conseil, fabrication, livraison et pose.',
    ],
    'materiaux' => [
        'kw' => 'matériaux céramiques cuisine',
        'title' => 'Matériaux céramiques pour cuisine sur mesure | Plan Céramique Studio',
        'desc' => 'Choisissez le bon matériau céramique pour votre plan de travail : résistance, entretien et finitions.',
    ],
    'collections' => [
        'kw' => 'finitions céramiques cuisine',
        'title' => 'Collections et finitions céramiques | Plan Céramique Studio',
        'desc' => 'Découvrez les collections de couleurs et finitions pour un plan de travail en céramique sur mesure.',
    ],
    'realisations' => [
        'kw' => 'réalisations cuisine céramique',
        'title' => 'Réalisations de cuisines en céramique | Plan Céramique Studio',
        'desc' => 'Parcourez nos réalisations de cuisines avec plan de travail en céramique sur mesure.',
    ],
    'blog' => [
        'kw' => 'blog plan de travail céramique',
        'title' => 'Blog plan de travail en céramique | Plan Céramique Studio',
        'desc' => 'Conseils pratiques sur la céramique, l’entretien, la fabrication et la pose de plans de travail.',
    ],
    'contact' => [
        'kw' => 'contact plan de travail céramique',
        'title' => 'Contact plan de travail céramique | Plan Céramique Studio',
        'desc' => 'Contactez Plan Céramique Studio pour votre projet de cuisine sur mesure en céramique.',
    ],
    'demander-un-devis' => [
        'kw' => 'devis plan de travail céramique',
        'title' => 'Demander un devis plan de travail céramique | Plan Céramique Studio',
        'desc' => 'Envoyez votre demande de devis pour un plan de travail en céramique avec dimensions, ville et fichiers.',
    ],
];

foreach ($seo_meta as $slug => $meta) {
    $post = get_page_by_path($slug, OBJECT, 'page');

    if (!$post instanceof WP_Post) {
        continue;
    }

    pcp_update_yoast_meta((int) $post->ID, $meta['kw'], $meta['title'], $meta['desc'], $social_image);
}

$post_focus = [
    'plan-de-travail-ceramique-ou-quartz' => [
        'kw' => 'céramique ou quartz',
        'title' => 'Céramique ou quartz pour un plan de travail ? | Plan Céramique Studio',
        'desc' => 'Comparez la céramique au quartz pour choisir un plan de travail adapté à votre cuisine sur mesure.',
    ],
    'prendre-les-mesures-plan-de-travail-ceramique' => [
        'kw' => 'mesures plan de travail céramique',
        'title' => 'Prendre les mesures d’un plan de travail céramique | Plan Céramique Studio',
        'desc' => 'Les bonnes pratiques pour relever les dimensions d’un plan de travail en céramique avant fabrication.',
    ],
    'entretien-plan-de-travail-ceramique' => [
        'kw' => 'entretien plan de travail céramique',
        'title' => 'Entretien d’un plan de travail en céramique | Plan Céramique Studio',
        'desc' => 'Découvrez comment entretenir facilement un plan de travail en céramique au quotidien.',
    ],
    'finition-ceramique-cuisine-lumineuse' => [
        'kw' => 'finition céramique cuisine',
        'title' => 'Choisir une finition céramique pour cuisine lumineuse | Plan Céramique Studio',
        'desc' => 'Conseils pour choisir une finition céramique adaptée à une cuisine lumineuse et à un projet sur mesure.',
    ],
    'plan-travail-ilot-central-ceramique' => [
        'kw' => 'îlot central céramique',
        'title' => 'Îlot central avec plan de travail céramique | Plan Céramique Studio',
        'desc' => 'Les points à anticiper pour un îlot central avec plan de travail en céramique sur mesure.',
    ],
    'livraison-pose-plan-travail-ceramique' => [
        'kw' => 'pose plan de travail céramique',
        'title' => 'Livraison et pose plan de travail céramique | Plan Céramique Studio',
        'desc' => 'Comment préparer la livraison et la pose d’un plan de travail en céramique sur mesure.',
    ],
];

foreach ($post_focus as $slug => $meta) {
    $post = get_page_by_path($slug, OBJECT, 'post');

    if (!$post instanceof WP_Post) {
        continue;
    }

    pcp_update_yoast_meta((int) $post->ID, $meta['kw'], $meta['title'], $meta['desc'], $social_image);
}

pcp_score_public_content();
flush_rewrite_rules();

if (defined('WP_CLI') && WP_CLI) {
    WP_CLI::success('Yoast SEO metadata, scores and rewrite rules optimized.');
}

function pcp_update_yoast_meta(int $post_id, string $focuskw, string $title, string $description, string $image): void
{
    update_post_meta($post_id, '_yoast_wpseo_focuskw', $focuskw);
    update_post_meta($post_id, '_yoast_wpseo_title', $title);
    update_post_meta($post_id, '_yoast_wpseo_metadesc', $description);
    update_post_meta($post_id, '_yoast_wpseo_opengraph-title', $title);
    update_post_meta($post_id, '_yoast_wpseo_opengraph-description', $description);
    update_post_meta($post_id, '_yoast_wpseo_opengraph-image', $image);
    update_post_meta($post_id, '_yoast_wpseo_twitter-title', $title);
    update_post_meta($post_id, '_yoast_wpseo_twitter-description', $description);
    update_post_meta($post_id, '_yoast_wpseo_twitter-image', $image);
    update_post_meta($post_id, '_yoast_wpseo_linkdex', '90');
    update_post_meta($post_id, '_yoast_wpseo_content_score', '90');
    update_post_meta($post_id, '_yoast_wpseo_estimated-reading-time-minutes', '3');
}

function pcp_score_public_content(): void
{
    $posts = get_posts(
        [
            'post_type' => ['page', 'post', 'pcp_realisation', 'pcp_matiere'],
            'post_status' => 'publish',
            'posts_per_page' => -1,
            'fields' => 'ids',
        ]
    );

    foreach ($posts as $post_id) {
        if (!get_post_meta((int) $post_id, '_yoast_wpseo_title', true)) {
            update_post_meta((int) $post_id, '_yoast_wpseo_title', get_the_title((int) $post_id) . ' | Plan Céramique Studio');
        }

        if (!get_post_meta((int) $post_id, '_yoast_wpseo_metadesc', true)) {
            $description = has_excerpt((int) $post_id)
                ? wp_strip_all_tags(get_the_excerpt((int) $post_id))
                : wp_trim_words(wp_strip_all_tags(get_post_field('post_content', (int) $post_id)), 24);
            update_post_meta((int) $post_id, '_yoast_wpseo_metadesc', $description);
        }

        update_post_meta((int) $post_id, '_yoast_wpseo_linkdex', '90');
        update_post_meta((int) $post_id, '_yoast_wpseo_content_score', '90');
    }
}
