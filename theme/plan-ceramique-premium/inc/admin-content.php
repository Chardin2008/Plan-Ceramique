<?php

function pcp_admin_content_page_fields(): array
{
    $commonPageFields = [
        'pcp_hero_eyebrow' => [
            'label' => __('Eyebrow hero', 'plan-ceramique-premium'),
            'type' => 'text',
            'description' => __('Petit libelle au-dessus du titre principal.', 'plan-ceramique-premium'),
        ],
        'pcp_hero_title' => [
            'label' => __('Titre hero', 'plan-ceramique-premium'),
            'type' => 'text',
            'description' => __('Titre principal affiche dans le hero.', 'plan-ceramique-premium'),
        ],
        'pcp_hero_lead' => [
            'label' => __('Texte hero', 'plan-ceramique-premium'),
            'type' => 'textarea',
        ],
        'pcp_hero_image' => [
            'label' => __('Image hero', 'plan-ceramique-premium'),
            'type' => 'text',
            'description' => __('Nom de fichier ou URL. Exemple: hero-services.jpg.', 'plan-ceramique-premium'),
        ],
        'pcp_hero_image_alt' => [
            'label' => __('Texte alternatif image hero', 'plan-ceramique-premium'),
            'type' => 'text',
        ],
        'pcp_primary_cta_text' => [
            'label' => __('Texte bouton principal', 'plan-ceramique-premium'),
            'type' => 'text',
        ],
        'pcp_primary_cta_url' => [
            'label' => __('URL bouton principal', 'plan-ceramique-premium'),
            'type' => 'text',
        ],
        'pcp_secondary_cta_text' => [
            'label' => __('Texte bouton secondaire', 'plan-ceramique-premium'),
            'type' => 'text',
        ],
        'pcp_secondary_cta_url' => [
            'label' => __('URL bouton secondaire', 'plan-ceramique-premium'),
            'type' => 'text',
        ],
        'pcp_intro_eyebrow' => [
            'label' => __('Eyebrow introduction', 'plan-ceramique-premium'),
            'type' => 'text',
        ],
        'pcp_intro_title' => [
            'label' => __('Titre introduction', 'plan-ceramique-premium'),
            'type' => 'text',
        ],
        'pcp_intro_text' => [
            'label' => __('Texte introduction', 'plan-ceramique-premium'),
            'type' => 'textarea',
        ],
        'pcp_cards_json' => [
            'label' => __('Cartes de contenu', 'plan-ceramique-premium'),
            'type' => 'textarea',
            'description' => __('Une carte par ligne au format icon | eyebrow | titre | texte.', 'plan-ceramique-premium'),
        ],
        'pcp_feature_eyebrow' => [
            'label' => __('Eyebrow bloc image', 'plan-ceramique-premium'),
            'type' => 'text',
        ],
        'pcp_feature_title' => [
            'label' => __('Titre bloc image', 'plan-ceramique-premium'),
            'type' => 'text',
        ],
        'pcp_feature_text' => [
            'label' => __('Texte bloc image', 'plan-ceramique-premium'),
            'type' => 'textarea',
        ],
        'pcp_feature_image' => [
            'label' => __('Image bloc image', 'plan-ceramique-premium'),
            'type' => 'text',
        ],
        'pcp_feature_image_alt' => [
            'label' => __('Texte alternatif bloc image', 'plan-ceramique-premium'),
            'type' => 'text',
        ],
        'pcp_feature_cta_text' => [
            'label' => __('Texte bouton bloc image', 'plan-ceramique-premium'),
            'type' => 'text',
        ],
        'pcp_feature_cta_url' => [
            'label' => __('URL bouton bloc image', 'plan-ceramique-premium'),
            'type' => 'text',
        ],
        'pcp_feature_list' => [
            'label' => __('Liste bloc image', 'plan-ceramique-premium'),
            'type' => 'textarea',
            'description' => __('Une ligne par element.', 'plan-ceramique-premium'),
        ],
        'pcp_form_eyebrow' => [
            'label' => __('Eyebrow formulaire', 'plan-ceramique-premium'),
            'type' => 'text',
        ],
        'pcp_form_title' => [
            'label' => __('Titre formulaire', 'plan-ceramique-premium'),
            'type' => 'text',
        ],
        'pcp_contact_email_label' => [
            'label' => __('Libelle email contact', 'plan-ceramique-premium'),
            'type' => 'text',
        ],
        'pcp_contact_zone_label' => [
            'label' => __('Libelle zone contact', 'plan-ceramique-premium'),
            'type' => 'text',
        ],
        'pcp_final_cta_eyebrow' => [
            'label' => __('Eyebrow CTA final', 'plan-ceramique-premium'),
            'type' => 'text',
        ],
        'pcp_final_cta_title' => [
            'label' => __('Titre CTA final', 'plan-ceramique-premium'),
            'type' => 'text',
        ],
        'pcp_final_cta_text' => [
            'label' => __('Texte CTA final', 'plan-ceramique-premium'),
            'type' => 'textarea',
        ],
        'pcp_final_cta_button_text' => [
            'label' => __('Texte bouton CTA final', 'plan-ceramique-premium'),
            'type' => 'text',
        ],
        'pcp_final_cta_button_url' => [
            'label' => __('URL bouton CTA final', 'plan-ceramique-premium'),
            'type' => 'text',
        ],
    ];

    $homeFields = array_merge(
        $commonPageFields,
        [
            'pcp_loader_brand' => [
                'label' => __('Loader - marque', 'plan-ceramique-premium'),
                'type' => 'text',
            ],
            'pcp_loader_text' => [
                'label' => __('Loader - texte', 'plan-ceramique-premium'),
                'type' => 'text',
            ],
            'pcp_hero_badges' => [
                'label' => __('Badges hero', 'plan-ceramique-premium'),
                'type' => 'textarea',
                'description' => __('Une ligne par badge.', 'plan-ceramique-premium'),
            ],
            'pcp_hero_caption' => [
                'label' => __('Legende image hero', 'plan-ceramique-premium'),
                'type' => 'text',
            ],
            'pcp_surface_cards' => [
                'label' => __('Accueil - cartes surface', 'plan-ceramique-premium'),
                'type' => 'textarea',
                'description' => __('Une ligne par carte au format numero | titre | texte.', 'plan-ceramique-premium'),
            ],
            'pcp_surface_eyebrow' => [
                'label' => __('Accueil - eyebrow surface', 'plan-ceramique-premium'),
                'type' => 'text',
            ],
            'pcp_surface_title' => [
                'label' => __('Accueil - titre surface', 'plan-ceramique-premium'),
                'type' => 'text',
            ],
            'pcp_scanner_points' => [
                'label' => __('Accueil - points scanner', 'plan-ceramique-premium'),
                'type' => 'textarea',
                'description' => __('Preparation technique: numero | x | y | titre | texte.', 'plan-ceramique-premium'),
            ],
            'pcp_scanner_eyebrow' => [
                'label' => __('Accueil - eyebrow scanner', 'plan-ceramique-premium'),
                'type' => 'text',
            ],
            'pcp_scanner_title' => [
                'label' => __('Accueil - titre scanner', 'plan-ceramique-premium'),
                'type' => 'text',
            ],
            'pcp_scanner_image' => [
                'label' => __('Accueil - image scanner', 'plan-ceramique-premium'),
                'type' => 'text',
            ],
            'pcp_scanner_image_alt' => [
                'label' => __('Accueil - alt image scanner', 'plan-ceramique-premium'),
                'type' => 'text',
            ],
            'pcp_scanner_panel_eyebrow' => [
                'label' => __('Accueil - eyebrow panneau scanner', 'plan-ceramique-premium'),
                'type' => 'text',
            ],
            'pcp_ambiance_cards' => [
                'label' => __('Accueil - ambiances', 'plan-ceramique-premium'),
                'type' => 'textarea',
                'description' => __('Preparation technique: titre | image | alt | texte | couleurs HEX separees par des virgules.', 'plan-ceramique-premium'),
            ],
            'pcp_ambiance_eyebrow' => [
                'label' => __('Accueil - eyebrow ambiances', 'plan-ceramique-premium'),
                'type' => 'text',
            ],
            'pcp_ambiance_title' => [
                'label' => __('Accueil - titre ambiances', 'plan-ceramique-premium'),
                'type' => 'text',
            ],
            'pcp_ambiance_cta_text' => [
                'label' => __('Accueil - texte lien ambiance', 'plan-ceramique-premium'),
                'type' => 'text',
            ],
            'pcp_ambiance_cta_url' => [
                'label' => __('Accueil - URL lien ambiance', 'plan-ceramique-premium'),
                'type' => 'text',
            ],
            'pcp_applications' => [
                'label' => __('Accueil - applications', 'plan-ceramique-premium'),
                'type' => 'textarea',
                'description' => __('Une ligne par application au format titre | image | alt.', 'plan-ceramique-premium'),
            ],
            'pcp_applications_eyebrow' => [
                'label' => __('Accueil - eyebrow applications', 'plan-ceramique-premium'),
                'type' => 'text',
            ],
            'pcp_applications_title' => [
                'label' => __('Accueil - titre applications', 'plan-ceramique-premium'),
                'type' => 'text',
            ],
            'pcp_compare_columns' => [
                'label' => __('Accueil - comparateur', 'plan-ceramique-premium'),
                'type' => 'textarea',
                'description' => __('Titre de colonne puis liste. Separer les colonnes par une ligne vide.', 'plan-ceramique-premium'),
            ],
            'pcp_compare_eyebrow' => [
                'label' => __('Accueil - eyebrow comparateur', 'plan-ceramique-premium'),
                'type' => 'text',
            ],
            'pcp_compare_title' => [
                'label' => __('Accueil - titre comparateur', 'plan-ceramique-premium'),
                'type' => 'text',
            ],
            'pcp_process_steps' => [
                'label' => __('Accueil - processus', 'plan-ceramique-premium'),
                'type' => 'textarea',
                'description' => __('Une ligne par etape.', 'plan-ceramique-premium'),
            ],
            'pcp_process_eyebrow' => [
                'label' => __('Accueil - eyebrow processus', 'plan-ceramique-premium'),
                'type' => 'text',
            ],
            'pcp_process_title' => [
                'label' => __('Accueil - titre processus', 'plan-ceramique-premium'),
                'type' => 'text',
            ],
            'pcp_premium_details' => [
                'label' => __('Accueil - details premium', 'plan-ceramique-premium'),
                'type' => 'textarea',
                'description' => __('Une ligne par detail.', 'plan-ceramique-premium'),
            ],
            'pcp_details_eyebrow' => [
                'label' => __('Accueil - eyebrow details', 'plan-ceramique-premium'),
                'type' => 'text',
            ],
            'pcp_details_title' => [
                'label' => __('Accueil - titre details', 'plan-ceramique-premium'),
                'type' => 'text',
            ],
            'pcp_final_cta_secondary_text' => [
                'label' => __('Texte bouton secondaire CTA final', 'plan-ceramique-premium'),
                'type' => 'text',
            ],
            'pcp_final_cta_secondary_url' => [
                'label' => __('URL bouton secondaire CTA final', 'plan-ceramique-premium'),
                'type' => 'text',
            ],
        ]
    );

    return [
        'accueil' => $homeFields,
        'nos-services' => $commonPageFields,
        'materiaux' => $commonPageFields,
        'collections' => $commonPageFields,
        'realisations' => $commonPageFields,
        'contact' => $commonPageFields,
        'demander-un-devis' => $commonPageFields,
        'blog' => [
            'pcp_hero_eyebrow' => $commonPageFields['pcp_hero_eyebrow'],
            'pcp_hero_title' => $commonPageFields['pcp_hero_title'],
            'pcp_hero_lead' => $commonPageFields['pcp_hero_lead'],
            'pcp_hero_image' => $commonPageFields['pcp_hero_image'],
            'pcp_hero_image_alt' => $commonPageFields['pcp_hero_image_alt'],
            'pcp_intro_eyebrow' => $commonPageFields['pcp_intro_eyebrow'],
            'pcp_intro_title' => $commonPageFields['pcp_intro_title'],
        ],
    ];
}

function pcp_admin_content_fields_for_post(WP_Post $post): array
{
    if ($post->post_type !== 'page') {
        return [];
    }

    return pcp_admin_content_page_fields()[$post->post_name] ?? [];
}

function pcp_admin_content_register_meta_boxes(): void
{
    add_meta_box(
        'pcp_page_admin_content',
        __('Contenus administrables Plan Ceramique', 'plan-ceramique-premium'),
        'pcp_admin_content_render_meta_box',
        'page',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes_page', 'pcp_admin_content_register_meta_boxes');

function pcp_admin_content_render_meta_box(WP_Post $post): void
{
    $fields = pcp_admin_content_fields_for_post($post);
    wp_nonce_field('pcp_save_page_admin_content', 'pcp_page_admin_content_nonce');

    if (!$fields) {
        echo '<p>' . esc_html__('Aucun champ specifique prepare pour cette page.', 'plan-ceramique-premium') . '</p>';

        return;
    }

    echo '<p class="description">' . esc_html__('Ces champs alimentent les contenus visibles du theme tout en conservant le rendu public actuel.', 'plan-ceramique-premium') . '</p>';
    echo '<table class="form-table" role="presentation"><tbody>';

    foreach ($fields as $key => $field) {
        $value = (string) get_post_meta($post->ID, $key, true);
        echo '<tr>';
        echo '<th scope="row"><label for="' . esc_attr($key) . '">' . esc_html((string) $field['label']) . '</label></th>';
        echo '<td>';

        if (($field['type'] ?? 'text') === 'textarea') {
            echo '<textarea id="' . esc_attr($key) . '" name="pcp_page_admin_content[' . esc_attr($key) . ']" class="large-text" rows="4">' . esc_textarea($value) . '</textarea>';
        } else {
            echo '<input id="' . esc_attr($key) . '" name="pcp_page_admin_content[' . esc_attr($key) . ']" type="text" class="regular-text" value="' . esc_attr($value) . '">';
        }

        if (!empty($field['description'])) {
            echo '<p class="description">' . esc_html((string) $field['description']) . '</p>';
        }

        echo '</td>';
        echo '</tr>';
    }

    echo '</tbody></table>';
}

function pcp_admin_content_save_page(int $post_id): void
{
    if (
        !isset($_POST['pcp_page_admin_content_nonce'])
        || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['pcp_page_admin_content_nonce'])), 'pcp_save_page_admin_content')
        || (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        || !current_user_can('edit_post', $post_id)
    ) {
        return;
    }

    $post = get_post($post_id);

    if (!$post) {
        return;
    }

    $fields = pcp_admin_content_fields_for_post($post);

    if (!$fields) {
        return;
    }

    $input = isset($_POST['pcp_page_admin_content']) && is_array($_POST['pcp_page_admin_content'])
        ? wp_unslash($_POST['pcp_page_admin_content'])
        : [];

    foreach ($fields as $key => $field) {
        $raw = (string) ($input[$key] ?? '');
        $value = ($field['type'] ?? 'text') === 'textarea'
            ? sanitize_textarea_field($raw)
            : sanitize_text_field($raw);

        if ($value === '') {
            delete_post_meta($post_id, $key);
        } else {
            update_post_meta($post_id, $key, $value);
        }
    }
}
add_action('save_post_page', 'pcp_admin_content_save_page');

function pcp_admin_content_value(int $post_id, string $key, string $fallback = ''): string
{
    $value = get_post_meta($post_id, $key, true);

    return is_string($value) && $value !== '' ? $value : $fallback;
}

function pcp_admin_content_lines(int $post_id, string $key, array $fallback = []): array
{
    $value = pcp_admin_content_value($post_id, $key);

    if ($value === '') {
        return $fallback;
    }

    $lines = array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $value) ?: []));

    return $lines ?: $fallback;
}

function pcp_admin_content_pipe_rows(int $post_id, string $key, array $columns, array $fallback = []): array
{
    $lines = pcp_admin_content_lines($post_id, $key);

    if (!$lines) {
        return $fallback;
    }

    $rows = [];
    $columnCount = count($columns);

    foreach ($lines as $line) {
        $parts = array_map('trim', explode('|', $line, $columnCount));

        if (count($parts) !== $columnCount) {
            continue;
        }

        $row = [];

        foreach ($columns as $index => $column) {
            $row[$column] = $parts[$index];
        }

        $rows[] = $row;
    }

    return $rows ?: $fallback;
}

function pcp_admin_content_blocks(int $post_id, string $key, array $fallback = []): array
{
    $value = pcp_admin_content_value($post_id, $key);

    if ($value === '') {
        return $fallback;
    }

    $blocks = array_filter(array_map('trim', preg_split('/\r\n\s*\r\n|\r\s*\r|\n\s*\n/', $value) ?: []));
    $parsed = [];

    foreach ($blocks as $block) {
        $lines = array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $block) ?: []));

        if (count($lines) < 2) {
            continue;
        }

        $parsed[] = [
            'title' => array_shift($lines),
            'items' => $lines,
        ];
    }

    return $parsed ?: $fallback;
}
