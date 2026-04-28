<?php

function pcp_enqueue_assets(): void
{
    wp_enqueue_style(
        'pcp-fonts',
        'https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Cormorant+Garamond:wght@500;600;700&display=swap',
        [],
        null
    );

    wp_enqueue_style(
        'pcp-main',
        get_template_directory_uri() . '/assets/css/main.css',
        ['pcp-fonts'],
        pcp_theme_version()
    );

    wp_enqueue_script(
        'pcp-navigation',
        get_template_directory_uri() . '/assets/js/navigation.js',
        [],
        pcp_theme_version(),
        true
    );

    wp_enqueue_script(
        'pcp-forms',
        get_template_directory_uri() . '/assets/js/forms.js',
        [],
        pcp_theme_version(),
        true
    );

    wp_localize_script(
        'pcp-forms',
        'pcpForms',
        [
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'submitUrl' => get_template_directory_uri() . '/inc/fast-form-endpoint.php',
            'nonce' => wp_create_nonce('pcp_submit_form'),
        ]
    );
}
add_action('wp_enqueue_scripts', 'pcp_enqueue_assets');
