<?php

function pcp_theme_setup(): void
{
    load_theme_textdomain('plan-ceramique-premium', get_template_directory() . '/languages');

    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('responsive-embeds');
    add_theme_support('wp-block-styles');
    add_theme_support('editor-styles');
    add_theme_support('align-wide');
    add_theme_support('custom-logo');
    add_theme_support(
        'html5',
        ['comment-form', 'comment-list', 'gallery', 'caption', 'script', 'style', 'search-form']
    );

    add_editor_style(['assets/css/main.css', 'assets/css/editor.css']);

    register_nav_menus(
        [
            'primary' => __('Navigation principale', 'plan-ceramique-premium'),
            'footer' => __('Navigation pied de page', 'plan-ceramique-premium'),
        ]
    );
}
add_action('after_setup_theme', 'pcp_theme_setup');

function pcp_excerpt_more(string $more): string
{
    if (!is_admin()) {
        return '...';
    }

    return $more;
}
add_filter('excerpt_more', 'pcp_excerpt_more');
