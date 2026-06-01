<?php

require_once get_template_directory() . '/inc/setup.php';
require_once get_template_directory() . '/inc/assets.php';
require_once get_template_directory() . '/inc/settings.php';
require_once get_template_directory() . '/inc/content-types.php';
require_once get_template_directory() . '/inc/admin-content.php';
require_once get_template_directory() . '/inc/home-blocks.php';
require_once get_template_directory() . '/inc/mail-brake.php';
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

function pcp_post_topic_data(?WP_Post $post = null): array
{
    $post = $post ?: get_post();
    $slug = $post ? (string) $post->post_name : '';

    $topics = [
        'livraison-pose' => ['label' => 'Pose', 'icon' => 'P'],
        'livraison' => ['label' => 'Pose', 'icon' => 'P'],
        'pose' => ['label' => 'Pose', 'icon' => 'P'],
        'ilot' => ['label' => 'Îlot', 'icon' => 'I'],
        'credence' => ['label' => 'Crédence', 'icon' => 'C'],
        'finition' => ['label' => 'Finition', 'icon' => 'F'],
        'couleur' => ['label' => 'Finition', 'icon' => 'F'],
        'marbre' => ['label' => 'Matière', 'icon' => 'M'],
        'pierre' => ['label' => 'Matière', 'icon' => 'M'],
        'beton' => ['label' => 'Matière', 'icon' => 'M'],
        'entretien' => ['label' => 'Entretien', 'icon' => 'E'],
        'mesures' => ['label' => 'Mesures', 'icon' => 'M'],
        'devis' => ['label' => 'Devis', 'icon' => 'D'],
        'budget' => ['label' => 'Budget', 'icon' => 'B'],
        'quartz' => ['label' => 'Matière', 'icon' => 'C'],
    ];

    foreach ($topics as $needle => $topic) {
        if (str_contains($slug, $needle)) {
            return $topic;
        }
    }

    return ['label' => 'Conseil', 'icon' => 'C'];
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

function pcp_asset_img(string $file): string
{
    return get_template_directory_uri() . '/assets/img/' . ltrim($file, '/');
}

function pcp_site_url(string $url): string
{
    if ($url === '') {
        return home_url('/');
    }

    if (str_starts_with($url, '#')) {
        return home_url('/' . $url);
    }

    if (str_starts_with($url, '/')) {
        return home_url($url);
    }

    return $url;
}

function pcp_default_nav_items(string $location): array
{
    if ($location === 'footer') {
        return [
            ['url' => '#accueil', 'label' => 'Accueil'],
            ['url' => '#matieres', 'label' => 'Matières'],
            ['url' => '#ambiances', 'label' => 'Ambiances'],
            ['url' => '#realisations', 'label' => 'Réalisations'],
            ['url' => '#galerie', 'label' => 'Galerie'],
            ['url' => '#blog', 'label' => 'Blog'],
            ['url' => '#avis', 'label' => 'Avis'],
            ['url' => '#devis', 'label' => 'Devis'],
        ];
    }

    return [
        ['url' => '#accueil', 'label' => 'Accueil'],
        ['url' => '#matieres', 'label' => 'Matières'],
        ['url' => '#ambiances', 'label' => 'Ambiances'],
        ['url' => '#realisations', 'label' => 'Réalisations'],
        ['url' => '#applications', 'label' => 'Applications'],
        ['url' => '#blog', 'label' => 'Blog'],
        ['url' => '#avis', 'label' => 'Avis'],
    ];
}

function pcp_render_nav_menu(string $location, string $menu_class): void
{
    if (has_nav_menu($location)) {
        $locations = get_nav_menu_locations();
        $menu_id = (int) ($locations[$location] ?? 0);
        $menu_items = $menu_id > 0 ? wp_get_nav_menu_items($menu_id) : [];

        if ($menu_items) {
            $seen = [];
            $primary_cta_url = pcp_site_url(pcp_get_setting('primary_cta_url') ?: '#devis');

            echo '<ul class="' . esc_attr($menu_class) . '">';

            foreach ($menu_items as $item) {
                if ((int) $item->menu_item_parent !== 0) {
                    continue;
                }

                $url = (string) $item->url;
                $label = trim(wp_strip_all_tags((string) $item->title));
                $key = mb_strtolower($label . '|' . untrailingslashit($url));
                $normalized_url = untrailingslashit($url);
                $normalized_cta_url = untrailingslashit($primary_cta_url);

                if (
                    $label === ''
                    || isset($seen[$key])
                    || ($location === 'primary' && $normalized_url === $normalized_cta_url)
                    || ($location === 'primary' && str_contains(mb_strtolower($label), 'devis'))
                ) {
                    continue;
                }

                $seen[$key] = true;
                $classes = array_filter(array_map('sanitize_html_class', (array) $item->classes));
                $class_attr = $classes ? ' class="' . esc_attr(implode(' ', $classes)) . '"' : '';

                echo '<li' . $class_attr . '><a href="' . esc_url(pcp_site_url($url)) . '">' . esc_html($label) . '</a></li>';
            }

            echo '</ul>';
        }

        return;
    }

    echo '<ul class="' . esc_attr($menu_class) . '">';

    foreach (pcp_default_nav_items($location) as $item) {
        echo '<li><a href="' . esc_url(pcp_site_url($item['url'])) . '">' . esc_html($item['label']) . '</a></li>';
    }

    echo '</ul>';
}

function pcp_post_image_file(int $post_id, string $fallback = 'blog-material-choice.jpg'): string
{
    $post = get_post($post_id);

    if (!$post) {
        return $fallback;
    }

    $slug = (string) $post->post_name;

    $postImages = [
        'plan-de-travail-ceramique-ou-quartz' => 'blog-material-choice.jpg',
        'prendre-les-mesures-plan-de-travail-ceramique' => 'texture-concrete-light.jpg',
        'entretien-plan-de-travail-ceramique' => 'blog-ceramique-maintenance.jpg',
        'finition-ceramique-cuisine-lumineuse' => 'texture-white-vein.jpg',
        'plan-travail-ilot-central-ceramique' => 'island-light-ceramique.jpg',
        'livraison-pose-plan-travail-ceramique' => 'kitchen-warm-ceramique.jpg',
    ];

    if (isset($postImages[$slug])) {
        return $postImages[$slug];
    }

    if ($post->post_type === 'pcp_realisation') {
        $projectType = strtolower((string) get_post_meta($post_id, 'pcp_project_type', true));
        $mood = strtolower((string) get_post_meta($post_id, 'pcp_mood', true));
        $haystack = strtolower($slug . ' ' . $projectType . ' ' . $mood);

        if (str_contains($haystack, 'salle') || str_contains($haystack, 'bain')) {
            return 'bathroom-light-ceramique.jpg';
        }

        if (str_contains($haystack, 'ilot') || str_contains($haystack, 'îlot')) {
            return 'island-light-ceramique.jpg';
        }

        if (str_contains($haystack, 'credence') || str_contains($haystack, 'crédence')) {
            return 'texture-white-vein.jpg';
        }

        if (str_contains($haystack, 'exterieur') || str_contains($haystack, 'extérieur')) {
            return 'outdoor-light-ceramique.jpg';
        }

        if (str_contains($haystack, 'sauge')) {
            return 'kitchen-sage-ceramique.jpg';
        }

        if (str_contains($haystack, 'warm') || str_contains($haystack, 'chaleur')) {
            return 'kitchen-warm-ceramique.jpg';
        }

        return 'kitchen-white-ceramique.jpg';
    }

    return $fallback;
}

function pcp_post_image_url(int $post_id, string $fallback = 'blog-material-choice.jpg'): string
{
    $remoteImage = pcp_post_meta($post_id, '_pcp_article_image_url');

    return get_the_post_thumbnail_url($post_id, 'large')
        ?: ($remoteImage ?: pcp_asset_img(pcp_post_image_file($post_id, $fallback)));
}

function pcp_post_meta(int $post_id, string $key, string $fallback = ''): string
{
    $value = get_post_meta($post_id, $key, true);

    return is_string($value) && $value !== '' ? $value : $fallback;
}

function pcp_excerpt_text(?WP_Post $post, int $words = 22): string
{
    if (!$post) {
        return '';
    }

    $text = has_excerpt($post) ? get_the_excerpt($post) : wp_strip_all_tags($post->post_content);

    return wp_trim_words($text, $words);
}
