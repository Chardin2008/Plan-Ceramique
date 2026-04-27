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
}
add_action('wp_enqueue_scripts', 'pcp_enqueue_assets');
