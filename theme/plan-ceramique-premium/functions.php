<?php

require_once get_template_directory() . '/inc/setup.php';
require_once get_template_directory() . '/inc/assets.php';
require_once get_template_directory() . '/inc/settings.php';
require_once get_template_directory() . '/inc/async-cf7-mail.php';
require_once get_template_directory() . '/inc/custom-forms.php';

function pcp_theme_version(): string
{
    $theme = wp_get_theme();

    return $theme->get('Version') ?: '1.0.0';
}

function pcp_reading_time(int $post_id = 0): string
{
    $post = get_post($post_id);

    if (!$post) {
        return '';
    }

    $wordCount = str_word_count(wp_strip_all_tags($post->post_content));
    $minutes = max(1, (int) ceil($wordCount / 180));

    return sprintf(_n('%d min de lecture', '%d min de lecture', $minutes, 'plan-ceramique-premium'), $minutes);
}

function pcp_page_hero_data(?WP_Post $post = null): array
{
    $post = $post ?: get_post();
    $default = [
        'eyebrow' => 'Plan de travail en ceramique',
        'description' => 'Surfaces premium en ceramique pour cuisines sur mesure, fabrication, livraison et pose.',
        'image' => get_template_directory_uri() . '/assets/images/hero-home.jpg',
        'image_alt' => 'Cuisine premium avec plan de travail en ceramique sur mesure',
    ];

    if (!$post) {
        return $default;
    }

    $map = [
        'nos-services' => [
            'eyebrow' => 'Conseil, fabrication, livraison, pose',
            'description' => 'Une execution precise du premier echange jusqu a la pose du plan de travail en ceramique.',
            'image' => get_template_directory_uri() . '/assets/images/hero-services.jpg',
            'image_alt' => 'Prise de mesure premium pour plan de travail en ceramique',
        ],
        'materiaux' => [
            'eyebrow' => 'Matiere, resistance, finitions',
            'description' => 'Comprendre la ceramique a travers sa texture, sa tenue a la chaleur, son entretien et ses finitions.',
            'image' => get_template_directory_uri() . '/assets/images/hero-materials.jpg',
            'image_alt' => 'Gros plan premium sur une surface ceramique minerale',
        ],
        'collections' => [
            'eyebrow' => 'Couleurs, veinages, styles',
            'description' => 'Des collections minerales pour construire une cuisine plus elegante et plus coherente.',
            'image' => get_template_directory_uri() . '/assets/images/hero-collections.jpg',
            'image_alt' => 'Showroom premium de finitions et couleurs ceramiques',
        ],
        'realisations' => [
            'eyebrow' => 'Cuisines terminees',
            'description' => 'Des projets livres pour donner une vision concrete de la qualite de fabrication et de pose.',
            'image' => get_template_directory_uri() . '/assets/images/hero-projects.jpg',
            'image_alt' => 'Cuisine terminee avec ilot et plan de travail en ceramique',
        ],
        'blog' => [
            'eyebrow' => 'Conseils et inspirations',
            'description' => 'Le blog rassemble entretien, choix de finitions, fabrication et pose pour mieux cadrer un projet.',
            'image' => get_template_directory_uri() . '/assets/images/hero-blog.jpg',
            'image_alt' => 'Scene editoriale premium autour des plans de travail en ceramique',
        ],
        'contact' => [
            'eyebrow' => 'Prise de contact simple',
            'description' => 'Une prise de contact directe pour orienter votre projet de cuisine sur mesure vers la bonne solution.',
            'image' => get_template_directory_uri() . '/assets/images/hero-contact.jpg',
            'image_alt' => 'Showroom premium et espace de rendez-vous pour projet ceramique',
        ],
        'demander-un-devis' => [
            'eyebrow' => 'Projet sur mesure',
            'description' => 'Le formulaire devis permet de lancer une etude serieuse autour des dimensions, finitions et contraintes du projet.',
            'image' => get_template_directory_uri() . '/assets/images/hero-quote.jpg',
            'image_alt' => 'Ambiance showroom premium pour demande de devis en ceramique',
        ],
        'accueil' => $default,
    ];

    if ($post->post_type === 'post') {
        return [
            'eyebrow' => 'Conseils ceramique',
            'description' => get_the_excerpt($post) ?: $default['description'],
            'image' => get_template_directory_uri() . '/assets/images/hero-blog.jpg',
            'image_alt' => 'Visuel editorial premium autour du conseil ceramique',
        ];
    }

    return $map[$post->post_name] ?? [
        'eyebrow' => $default['eyebrow'],
        'description' => has_excerpt($post) ? wp_strip_all_tags(get_the_excerpt($post)) : $default['description'],
        'image' => $default['image'],
        'image_alt' => $default['image_alt'],
    ];
}
