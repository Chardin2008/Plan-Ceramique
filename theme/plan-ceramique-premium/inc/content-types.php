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

function pcp_meta_field_admin_details(string $key): array
{
    return [
        'pcp_project_type' => [
            'placeholder' => __('Cuisine, ilot, salle de bain...', 'plan-ceramique-premium'),
            'description' => __('Type de projet utilise pour qualifier le contenu dans WordPress.', 'plan-ceramique-premium'),
        ],
        'pcp_material' => [
            'placeholder' => __('Ceramique effet marbre, pierre claire...', 'plan-ceramique-premium'),
            'description' => __('Nom court de la matiere ou finition principale.', 'plan-ceramique-premium'),
        ],
        'pcp_mood' => [
            'placeholder' => __('Lumineuse, chaleureuse, minerale...', 'plan-ceramique-premium'),
            'description' => __('Ambiance editoriale associee au contenu.', 'plan-ceramique-premium'),
        ],
        'pcp_gallery_filter' => [
            'description' => __('Classement utilise pour organiser les realisations.', 'plan-ceramique-premium'),
        ],
        'pcp_color_family' => [
            'description' => __('Famille utilisee pour organiser les matieres.', 'plan-ceramique-premium'),
        ],
        'pcp_dominant_color' => [
            'placeholder' => __('#C9A76A', 'plan-ceramique-premium'),
            'description' => __('Valeur couleur au format hexadecimal.', 'plan-ceramique-premium'),
        ],
        'pcp_use' => [
            'placeholder' => __('Plan de travail, credence, ilot...', 'plan-ceramique-premium'),
            'description' => __('Usage principal conseille pour administrer le catalogue.', 'plan-ceramique-premium'),
        ],
        'pcp_client_name' => [
            'placeholder' => __('Nom ou initiales du client', 'plan-ceramique-premium'),
            'description' => __('Nom affiche ou reference interne selon le niveau de confidentialite souhaite.', 'plan-ceramique-premium'),
        ],
        'pcp_rating' => [
            'description' => __('La valeur est limitee automatiquement entre 1 et 5.', 'plan-ceramique-premium'),
        ],
    ][$key] ?? [];
}

function pcp_render_meta_box(WP_Post $post, array $box): void
{
    $fields = (array) ($box['args']['fields'] ?? []);
    wp_nonce_field('pcp_save_dynamic_fields', 'pcp_dynamic_fields_nonce');

    echo '<p class="description">' . esc_html__('Ces informations structurent les contenus dans WordPress sans modifier directement la mise en page.', 'plan-ceramique-premium') . '</p>';
    echo '<table class="form-table" role="presentation"><tbody>';
    foreach ($fields as $key => $field) {
        $value = get_post_meta($post->ID, $key, true);
        $details = pcp_meta_field_admin_details($key);
        echo '<tr><th scope="row"><label for="' . esc_attr($key) . '">' . esc_html($field['label']) . '</label></th><td>';

        if (($field['type'] ?? 'text') === 'select') {
            echo '<select id="' . esc_attr($key) . '" name="pcp_dynamic_fields[' . esc_attr($key) . ']">';
            foreach ((array) ($field['options'] ?? []) as $option) {
                echo '<option value="' . esc_attr($option) . '"' . selected($value, $option, false) . '>' . esc_html($option) . '</option>';
            }
            echo '</select>';
        } else {
            $type = ($field['type'] ?? 'text') === 'number' ? 'number' : 'text';
            $numberAttributes = $type === 'number' ? ' min="1" max="5" step="1"' : '';
            $placeholder = isset($details['placeholder']) ? ' placeholder="' . esc_attr($details['placeholder']) . '"' : '';
            echo '<input id="' . esc_attr($key) . '" name="pcp_dynamic_fields[' . esc_attr($key) . ']" type="' . esc_attr($type) . '" class="regular-text" value="' . esc_attr((string) $value) . '"' . $placeholder . $numberAttributes . '>';
        }

        if (!empty($details['description'])) {
            echo '<p class="description">' . esc_html($details['description']) . '</p>';
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

function pcp_admin_column_map(string $post_type): array
{
    return [
        'pcp_realisation' => [
            'pcp_project_type' => __('Type de projet', 'plan-ceramique-premium'),
            'pcp_material' => __('Matière', 'plan-ceramique-premium'),
            'pcp_gallery_filter' => __('Filtre galerie', 'plan-ceramique-premium'),
        ],
        'pcp_matiere' => [
            'pcp_color_family' => __('Famille', 'plan-ceramique-premium'),
            'pcp_dominant_color' => __('Couleur', 'plan-ceramique-premium'),
            'pcp_use' => __('Usage', 'plan-ceramique-premium'),
        ],
        'pcp_avis' => [
            'pcp_client_name' => __('Client', 'plan-ceramique-premium'),
            'pcp_project_type' => __('Type de projet', 'plan-ceramique-premium'),
            'pcp_rating' => __('Note', 'plan-ceramique-premium'),
        ],
    ][$post_type] ?? [];
}

function pcp_admin_manage_columns(array $columns): array
{
    $postType = get_current_screen()->post_type ?? '';
    $customColumns = pcp_admin_column_map($postType);

    if (!$customColumns) {
        return $columns;
    }

    $date = $columns['date'] ?? null;
    unset($columns['date']);

    foreach ($customColumns as $key => $label) {
        $columns[$key] = $label;
    }

    if ($date !== null) {
        $columns['date'] = $date;
    }

    return $columns;
}
add_filter('manage_pcp_realisation_posts_columns', 'pcp_admin_manage_columns');
add_filter('manage_pcp_matiere_posts_columns', 'pcp_admin_manage_columns');
add_filter('manage_pcp_avis_posts_columns', 'pcp_admin_manage_columns');

function pcp_admin_render_column(string $column, int $post_id): void
{
    $postType = get_post_type($post_id);

    if (!isset(pcp_admin_column_map((string) $postType)[$column])) {
        return;
    }

    $value = get_post_meta($post_id, $column, true);

    if ($column === 'pcp_rating' && $value !== '') {
        printf('%s/5', esc_html((string) $value));

        return;
    }

    echo $value !== '' ? esc_html((string) $value) : '&mdash;';
}
add_action('manage_pcp_realisation_posts_custom_column', 'pcp_admin_render_column', 10, 2);
add_action('manage_pcp_matiere_posts_custom_column', 'pcp_admin_render_column', 10, 2);
add_action('manage_pcp_avis_posts_custom_column', 'pcp_admin_render_column', 10, 2);

function pcp_admin_sortable_columns(array $columns): array
{
    $postType = get_current_screen()->post_type ?? '';

    foreach (array_keys(pcp_admin_column_map($postType)) as $key) {
        $columns[$key] = $key;
    }

    return $columns;
}
add_filter('manage_edit-pcp_realisation_sortable_columns', 'pcp_admin_sortable_columns');
add_filter('manage_edit-pcp_matiere_sortable_columns', 'pcp_admin_sortable_columns');
add_filter('manage_edit-pcp_avis_sortable_columns', 'pcp_admin_sortable_columns');

function pcp_admin_filter_fields(string $post_type): array
{
    return [
        'pcp_realisation' => ['pcp_gallery_filter', 'pcp_project_type'],
        'pcp_matiere' => ['pcp_color_family'],
        'pcp_avis' => ['pcp_rating', 'pcp_project_type'],
    ][$post_type] ?? [];
}

function pcp_admin_render_filters(string $post_type): void
{
    foreach (pcp_admin_filter_fields($post_type) as $key) {
        $field = pcp_meta_fields()[$post_type][$key] ?? null;

        if (!$field) {
            continue;
        }

        $selected = sanitize_text_field(wp_unslash($_GET[$key] ?? ''));
        $label = sprintf(
            /* translators: %s: field label. */
            __('Filtrer par %s', 'plan-ceramique-premium'),
            strtolower((string) $field['label'])
        );

        echo '<select name="' . esc_attr($key) . '">';
        echo '<option value="">' . esc_html($label) . '</option>';

        if (($field['type'] ?? 'text') === 'select') {
            $options = (array) ($field['options'] ?? []);
        } elseif ($key === 'pcp_rating') {
            $options = ['1', '2', '3', '4', '5'];
        } else {
            $options = pcp_admin_distinct_meta_values($post_type, $key);
        }

        foreach ($options as $option) {
            echo '<option value="' . esc_attr((string) $option) . '"' . selected($selected, (string) $option, false) . '>' . esc_html((string) $option) . '</option>';
        }

        echo '</select>';
    }
}
add_action('restrict_manage_posts', 'pcp_admin_render_filters');

function pcp_admin_distinct_meta_values(string $post_type, string $meta_key): array
{
    global $wpdb;

    $values = $wpdb->get_col(
        $wpdb->prepare(
            "SELECT DISTINCT pm.meta_value
             FROM {$wpdb->postmeta} pm
             INNER JOIN {$wpdb->posts} p ON p.ID = pm.post_id
             WHERE pm.meta_key = %s
             AND p.post_type = %s
             AND p.post_status != 'trash'
             AND pm.meta_value != ''
             ORDER BY pm.meta_value ASC",
            $meta_key,
            $post_type
        )
    );

    return array_map('strval', $values ?: []);
}

function pcp_admin_apply_filters(WP_Query $query): void
{
    if (!is_admin() || !$query->is_main_query()) {
        return;
    }

    $postType = (string) $query->get('post_type');

    if (!pcp_admin_filter_fields($postType)) {
        return;
    }

    $metaQuery = (array) $query->get('meta_query');

    foreach (pcp_admin_filter_fields($postType) as $key) {
        $value = sanitize_text_field(wp_unslash($_GET[$key] ?? ''));

        if ($value === '') {
            continue;
        }

        $metaQuery[] = [
            'key' => $key,
            'value' => $value,
        ];
    }

    if ($metaQuery) {
        $query->set('meta_query', $metaQuery);
    }

    $orderby = (string) $query->get('orderby');

    if (isset(pcp_admin_column_map($postType)[$orderby])) {
        $query->set('meta_key', $orderby);
        $query->set('orderby', $orderby === 'pcp_rating' ? 'meta_value_num' : 'meta_value');
    }
}
add_action('pre_get_posts', 'pcp_admin_apply_filters');
