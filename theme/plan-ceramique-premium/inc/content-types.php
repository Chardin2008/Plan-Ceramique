<?php

function pcp_register_content_types(): void
{
    $common = [
        'public' => true,
        'show_in_rest' => true,
        'menu_position' => 22,
        'supports' => ['title', 'editor', 'excerpt', 'thumbnail'],
        'has_archive' => true,
    ];

    register_post_type(
        'pcp_realisation',
        array_merge(
            $common,
            [
                'labels' => [
                    'name' => __('Réalisations', 'plan-ceramique-premium'),
                    'singular_name' => __('Réalisation', 'plan-ceramique-premium'),
                    'add_new_item' => __('Ajouter une réalisation', 'plan-ceramique-premium'),
                    'edit_item' => __('Modifier la réalisation', 'plan-ceramique-premium'),
                ],
                'menu_icon' => 'dashicons-format-gallery',
                'rewrite' => ['slug' => 'realisations'],
            ]
        )
    );

    register_post_type(
        'pcp_matiere',
        array_merge(
            $common,
            [
                'labels' => [
                    'name' => __('Matières', 'plan-ceramique-premium'),
                    'singular_name' => __('Matière', 'plan-ceramique-premium'),
                    'add_new_item' => __('Ajouter une matière', 'plan-ceramique-premium'),
                    'edit_item' => __('Modifier la matière', 'plan-ceramique-premium'),
                ],
                'menu_icon' => 'dashicons-art',
                'rewrite' => ['slug' => 'matieres'],
            ]
        )
    );

    register_post_type(
        'pcp_avis',
        array_merge(
            $common,
            [
                'labels' => [
                    'name' => __('Avis clients', 'plan-ceramique-premium'),
                    'singular_name' => __('Avis client', 'plan-ceramique-premium'),
                    'add_new_item' => __('Ajouter un avis', 'plan-ceramique-premium'),
                    'edit_item' => __('Modifier l’avis', 'plan-ceramique-premium'),
                ],
                'menu_icon' => 'dashicons-testimonial',
                'rewrite' => ['slug' => 'avis-clients'],
            ]
        )
    );
}
add_action('init', 'pcp_register_content_types');

function pcp_meta_fields(): array
{
    return [
        'pcp_realisation' => [
            'pcp_project_type' => ['label' => __('Type de projet', 'plan-ceramique-premium'), 'type' => 'text'],
            'pcp_material' => ['label' => __('Matière utilisée', 'plan-ceramique-premium'), 'type' => 'text'],
            'pcp_mood' => ['label' => __('Ambiance', 'plan-ceramique-premium'), 'type' => 'text'],
            'pcp_gallery_filter' => ['label' => __('Filtre galerie', 'plan-ceramique-premium'), 'type' => 'select', 'options' => ['Cuisine', 'Îlot', 'Salle de bain', 'Crédence', 'Extérieur']],
        ],
        'pcp_matiere' => [
            'pcp_color_family' => ['label' => __('Famille de filtre', 'plan-ceramique-premium'), 'type' => 'select', 'options' => ['Clair', 'Chaleureux', 'Pierre', 'Béton', 'Naturel']],
            'pcp_dominant_color' => ['label' => __('Couleur dominante HEX', 'plan-ceramique-premium'), 'type' => 'text'],
            'pcp_mood' => ['label' => __('Ambiance conseillée', 'plan-ceramique-premium'), 'type' => 'text'],
            'pcp_use' => ['label' => __('Usage conseillé', 'plan-ceramique-premium'), 'type' => 'text'],
        ],
        'pcp_avis' => [
            'pcp_client_name' => ['label' => __('Nom du client', 'plan-ceramique-premium'), 'type' => 'text'],
            'pcp_project_type' => ['label' => __('Type de projet', 'plan-ceramique-premium'), 'type' => 'text'],
            'pcp_rating' => ['label' => __('Note sur 5', 'plan-ceramique-premium'), 'type' => 'number'],
        ],
    ];
}

function pcp_register_meta_boxes(): void
{
    foreach (pcp_meta_fields() as $postType => $fields) {
        add_meta_box(
            'pcp_dynamic_fields',
            __('Informations landing page', 'plan-ceramique-premium'),
            'pcp_render_meta_box',
            $postType,
            'normal',
            'high',
            ['fields' => $fields]
        );
    }
}
add_action('add_meta_boxes', 'pcp_register_meta_boxes');

function pcp_render_meta_box(WP_Post $post, array $box): void
{
    $fields = (array) ($box['args']['fields'] ?? []);
    wp_nonce_field('pcp_save_dynamic_fields', 'pcp_dynamic_fields_nonce');

    echo '<table class="form-table" role="presentation"><tbody>';
    foreach ($fields as $key => $field) {
        $value = get_post_meta($post->ID, $key, true);
        echo '<tr><th scope="row"><label for="' . esc_attr($key) . '">' . esc_html($field['label']) . '</label></th><td>';

        if (($field['type'] ?? 'text') === 'select') {
            echo '<select id="' . esc_attr($key) . '" name="pcp_dynamic_fields[' . esc_attr($key) . ']">';
            foreach ((array) ($field['options'] ?? []) as $option) {
                echo '<option value="' . esc_attr($option) . '"' . selected($value, $option, false) . '>' . esc_html($option) . '</option>';
            }
            echo '</select>';
        } else {
            $type = ($field['type'] ?? 'text') === 'number' ? 'number' : 'text';
            echo '<input id="' . esc_attr($key) . '" name="pcp_dynamic_fields[' . esc_attr($key) . ']" type="' . esc_attr($type) . '" class="regular-text" value="' . esc_attr((string) $value) . '">';
        }

        echo '</td></tr>';
    }
    echo '</tbody></table>';
}

function pcp_save_dynamic_fields(int $post_id): void
{
    if (
        !isset($_POST['pcp_dynamic_fields_nonce'])
        || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['pcp_dynamic_fields_nonce'])), 'pcp_save_dynamic_fields')
        || (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        || !current_user_can('edit_post', $post_id)
    ) {
        return;
    }

    $postType = get_post_type($post_id);
    $fields = pcp_meta_fields()[$postType] ?? [];
    $input = isset($_POST['pcp_dynamic_fields']) && is_array($_POST['pcp_dynamic_fields']) ? wp_unslash($_POST['pcp_dynamic_fields']) : [];

    foreach ($fields as $key => $field) {
        $raw = $input[$key] ?? '';
        $value = ($field['type'] ?? 'text') === 'number' ? (string) min(5, max(1, (int) $raw)) : sanitize_text_field((string) $raw);
        update_post_meta($post_id, $key, $value);
    }
}
add_action('save_post', 'pcp_save_dynamic_fields');
